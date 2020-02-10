<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class EqualCommand extends UserCommand
{
    protected $name = 'equal';                      // Your command's name
    protected $description = 'A command for test'; // Your command description
    protected $usage = '/equal';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $user = $message->getFrom()->getUsername();

        $text = $message->getText(true);

        $count = Request::getChatMembersCount(['chat_id' => $chat_id])->getResult();

        $arr = preg_match_all('/\d+/', $text, $matches);

        $sum = $matches[0][0];

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => "Эй, {$user}! Вас тут {$count}, я поделил сумму {$sum} на всех, получилось "
                . $sum/$count . "\n исходное сообщение: $text" // Set message to send
        ];

        return Request::sendMessage($data);        // Send message!
    }
}
