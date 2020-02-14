<?php
declare(strict_types=1);

namespace App\Model;

use PDO;

class SessionTable extends AbstractTable
{
    public const SESSION_TABLE = 'session';

    public function getLastActiveSessionByChatId(string $chatId): int
    {
        $request = 'SELECT id FROM ' . self::SESSION_TABLE
            . ' WHERE chat_id = ? AND is_active = true ORDER BY id DESC LIMIT 1';

        $statement = $this->pdo->prepare($request);
        $statement->execute([$chatId]);
        $queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!isset($queryResult[0]['id'])) {
            // TODO сделать нормальную обработку, когда запилим создание сессии
            //   или может, тут создавать новую сессию?
            return 1;
        }
        return $queryResult[0]['id'];
    }

}