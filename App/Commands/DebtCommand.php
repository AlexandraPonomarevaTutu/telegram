<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class DebtCommand extends UserCommand
{
    protected $name = 'debt';                      // Your command's name
    protected $description = 'A command for debt'; // Your command description
    protected $usage = '/debt';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    private $user;
    private $sum;
    private $debtDescription;
    private $user2;

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID


        if (!$this->validateMessage($message)) {
            $reply = "Команда должна быть формата /debt @user СУММА описание";
        } else {
            $this->prepareData($message);

            try {
                $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chat_id);
            } catch (\Throwable $e) {
                $sessionId = 1; // TODO как надо обработать ошибку?
            }
            $reply = "{$this->user}! Задолжал {$this->user2} {$this->sum} рублей, за {$this->debtDescription}";

            // TODO получать/создавать payment и передавать payment_id вместо сессии
            (new DebtTable())->addDebt($this->user, $this->user2, $this->sum, $sessionId, $this->debtDescription);
        }

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => $reply
        ];

        return Request::sendMessage($data);        // Send message!
    }

    /**
     * @param $message \Longman\TelegramBot\Entities\Message
     */
    private function prepareData($message)
    {
        $entities = $message->getEntities();
        $text = $message->getText(false);

        foreach ($entities as $entity) {
            if ($entity->getType() === 'mention') {
                $offset = $entity->getOffset();
                $length = $entity->getLength();
                $user2 = substr($text, $offset, $length);
            }
        }

        $this->user = $message->getFrom()->getUsername();
        $this->user2 = $user2;
        //TODO:добавить проверку, что юзер есть в чате

        $this->debtDescription = substr($text, $offset + $length);
        preg_match_all('/\d+/', $text, $matches);
        $this->sum = $sum = $matches[0][0];
    }

    /**
     * @param $message \Longman\TelegramBot\Entities\Message
     *
     * @return false|int
     */
    private function validateMessage($message)
    {
        return preg_match('/\/debt @.+\s+\d+\s+.+/', $message->getText());
    }
}
