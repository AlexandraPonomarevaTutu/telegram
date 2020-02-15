<?php


namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class AllDebtsCommand  extends UserCommand
{
    protected $name = 'calculate';                                 // Your command's name
    protected $description = 'get all debts for session'; // Your command description
    protected $usage = '/calculate';                               // Usage of your command
    protected $version = '1.0.0';                             // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chatId = $message->getChat()->getId();   // Get the current Chat ID

        try {
            $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chatId);
        } catch (\Throwable $e) {
            $sessionId = 1; // TODO как надо обработать ошибку?
        }

        $text = $this->prepareRawDebtsText($sessionId);

        $data = [                                  // Set up the new message data
            'chat_id' => $chatId,                  // Set Chat ID to send the message to
            'text'    => $text                 // Set message to send
        ];


        return Request::sendMessage($data);        // Send message!
    }

    /**
     *  Все долги по текущей сессии без аггрегации, но с комментариями
     */
    private function prepareRawDebtsText(int $session)
    {
        $text = "Эй, юзеры! \n";
        $debtsData = $this->getDebtTable()->getAllDebtsSummed($session);
        if (empty($debtsData)) {
            return "{$text} Поздравляю! У вас нет долгов в текущей сессии";
        }
        foreach ($debtsData as $debt) {
            $text .= "{$debt['user_debtor']} должен {$debt['user_creditor']} {$debt['amount']}.\n";
        }
        return $text;
    }

    private function getDebtTable()
    {
        return new DebtTable();
    }
}