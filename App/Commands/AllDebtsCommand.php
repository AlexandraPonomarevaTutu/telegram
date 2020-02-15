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
    // TODO почему-то не работает - разобраться у меня не получилось.
    //  Но вроде эта команда не очень нужна, т.к. /calculate нормально работает

    protected $name = 'all_debts';                                            // Your command's name
    protected $description = 'get all debts for session without aggregation'; // Your command description
    protected $usage = '/all_debts';                                           // Usage of your command
    protected $version = '1.0.0';                                             // Version of your command

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
        $hiText = "Эй, юзеры! \n";
        $debtText = '';
        $debtsData = $this->getDebtTable()->getAllActiveDebts($session);
        foreach ($debtsData as $debt) {
            if (isset($debt['user_debtor']) && $debt['user_creditor'] && $debt['amount'] && $debt['description']) {
                $debtText .= "{$debt['user_debtor']} должен {$debt['user_creditor']} {$debt['amount']} за \"{$debt['description']}\".\n";
            }
        }
        if (empty($debtText)) {
            $debtText =  "Поздравляю! У вас нет долгов в текущей сессии";
        }
        return $hiText . $debtText;
    }

    private function getDebtTable()
    {
        return new DebtTable();
    }
}