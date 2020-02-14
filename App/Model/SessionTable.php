<?php
declare(strict_types=1);

namespace App\Model;

use PDO;

class SessionTable extends AbstractTable
{
    public const SESSION_TABLE = 'session';

    public function getSessionByChatId(string $chatId): int
    {
        $request = 'SELECT id FROM ' . self::SESSION_TABLE
            . ' WHERE chat_id = ?';

        $statement = $this->pdo->prepare($request);
        $statement->execute([$chatId]);
        return $queryResult = $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
    }

}