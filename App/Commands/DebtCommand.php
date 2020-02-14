<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class DebtCommand extends UserCommand
{
    protected $name = 'debt';                      // Your command's name
    protected $description = 'A command for debt'; // Your command description
    protected $usage = '/debt';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $user = $message->getFrom()->getUsername();

        $text = $message->getText(true);

        $user2 = $this->getUserMention($message, $text);

        preg_match_all('/\d+/', $text, $matches);

        $sum = $matches[0][0];

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => "Эй, {$user}! За тобой должок на {$sum}, а должен ты {$user2} "
                . "\n исходное сообщение: $text" // Set message to send
        ];

        // TODO если MVP - получать текущую сессию и передавать сюда
        //  если не MVP - получать/создавать payment и передавать payment_id в addDebt
        (new DebtTable())->addDebt($user, $user2, $sum, 1);

        return Request::sendMessage($data);        // Send message!
    }

    /**
     * @param $message \Longman\TelegramBot\Entities\Message
     * @param $text
     * @return bool|string
     */
    private function getUserMention($message, $text)
    {
        $entities = $message->getEntities();

        foreach ($entities as $entity) {
            if ($entity->getType() === 'mention') {
                $offset = $entity->getOffset();
                $length = $entity->getLength();
                $user2 = substr($text, $offset + 1, $length + 1);
            }
        }
        return $user2;
    }
}
