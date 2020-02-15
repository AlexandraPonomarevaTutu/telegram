<?php


namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class CalculateCommand  extends UserCommand
{
    protected $name = 'calculate';                                 // Your command's name
    protected $description = 'get all debts for session'; // Your command description
    protected $usage = '/calculate';                               // Usage of your command
    protected $version = '1.0.0';                             // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chatId = $message->getChat()->getId();   // Get the current Chat ID
        
        $text = "Эй, юзеры! \n";
        try {
            $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chatId);
        } catch (\Throwable $e) {
            $sessionId = 1; // TODO как надо обработать ошибку?
        }
        $debtsData = $this->getRowDebts($sessionId);

        foreach ($debtsData as $debt) {
            $text .= "{$debt['user_debtor']} должен {$debt['user_creditor']} {$debt['amount']} за \"{$debt['description']}\".\n";
        }

        $data = [                                  // Set up the new message data
            'chat_id' => $chatId,                  // Set Chat ID to send the message to
            'text'    => $text                 // Set message to send
        ];


        return Request::sendMessage($data);        // Send message!
    }

    /**
     *  Простой подсчет - сколько Петя должен Васе без учета того, сколько они оба должны Саше
     */
    private function getRowDebts(int $session)
    {
        return $this->getDebtTable()->getAllActiveDebts($session);
    }

    private function getDebtTable()
    {
        return new DebtTable();
    }
}