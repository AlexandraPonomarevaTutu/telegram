<?php
declare(strict_types=1);

namespace App\Model;

use PDO;

class DebtTable extends AbstractTable
{
    public const DEBT_TABLE = 'debt';

    public function addDebt(
        string $userDebtor,
        string $userCreditor,
        float $amount,
        int $sessionId,
        string $description = ''
    ) {
        //TODO захардкодим пока payment_id = $sessionId, но потом надо будет доделать.
        $sql = 'INSERT INTO ' . self::DEBT_TABLE . ' (user_debtor, user_creditor, amount, description, payment_id) 
        VALUES (:user_debtor, :user_creditor, :amount, :about, :payment_id)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_debtor' => $userDebtor,
            'user_creditor' => $userCreditor,
            'amount' => $amount,
            'about' => $description,
            'payment_id' => $sessionId
        ]);
    }

    public function getAllActiveDebts(int $sessionId)
    {
//         TODO заменить на этот селект когда доработаем payment
//        $request = 'SELECT d.user_debtor, d.user_creditor, d.amount FROM ' . self::DEBT_TABLE . ' d'
//            . ' JOIN ' . PaymentTable::PAYMENT_TABLE . ' p ON p.id = d.payment_id JOIN '
//            . SessionTable::SESSION_TABLE . ' s on s.id = p.session_id'
//            . ' WHERE p.session_id = ? AND d.is_open = true';

        // пока debt.payment_id = $sessionId, а табличка payment не используется, достаточно простого запроса
        $request = 'SELECT d.user_debtor, d.user_creditor, d.amount, d.description FROM ' . self::DEBT_TABLE . ' d
         WHERE d.payment_id = ? AND d.is_open = true ORDER BY d.user_debtor';

        $statement = $this->pdo->prepare($request);
        $statement->execute([$sessionId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveDebtsForUser(string $debtor, int $sessionId)
    {
        // TODO это временное решение, пока нет payment и захардкожено payment_id = $sessionId
        $request = 'SELECT d.user_creditor, d.amount, d.description FROM ' . self::DEBT_TABLE . ' d
         WHERE d.user_debtor = ? AND d.is_open = true AND d.payment_id = ? 
         ORDER BY d.user_creditor';

        $statement = $this->pdo->prepare($request);
        $statement->execute([$debtor, $sessionId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Вернет список долгов, просуммированных по кредиторам
     * то есть, если у Саша 2 долга перед Петей на 2 и 3 рубля, а у Пети 3 долга Саше на 1, на 2, и на 4 рубля,
     * то вернет [
     * ['user_debtor' => Саша, 'user_creditor' => Петя, 'sum' => 5],
     * ['user_debtor' => Петя, 'user_creditor' => Саша, 'sum' => 7]
     * ]
     */
    public function getAllDebtsSummed($sessionId)
    {
        $req = 'SELECT user_debtor, user_creditor, SUM (amount) AS sum FROM debt 
WHERE payment_id = ? GROUP BY user_creditor,user_debtor';
        $statement = $this->pdo->prepare($req);
        $statement->execute([$sessionId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}