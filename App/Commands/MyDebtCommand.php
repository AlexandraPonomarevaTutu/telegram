<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class MyDebtCommand extends UserCommand
{
    protected $name = 'debt';                                 // Your command's name
    protected $description = 'get all debts for asking user'; // Your command description
    protected $usage = '/my_debt';                               // Usage of your command
    protected $version = '1.0.0';                             // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chatId = $message->getChat()->getId();   // Get the current Chat ID

        $user = $message->getFrom()->getUsername();

        $debtText = "Эй, {$user}! \n";
        $sessionId = (new SessionTable())->getSessionByChatId($chatId);
        $debtsData = (new DebtTable())->getActiveDebtsForUser($user, $sessionId);

        foreach ($debtsData as $debt)
        {
            $debtText .= "Ты должен {$debt['user_creditor']} {$debt['amount']} за \"{$debt['description']}\".\n";
        }

        $data = [                                  // Set up the new message data
            'chat_id' => $chatId,                  // Set Chat ID to send the message to
            'text'    => $debtText                 // Set message to send
        ];


        return Request::sendMessage($data);        // Send message!
    }
}