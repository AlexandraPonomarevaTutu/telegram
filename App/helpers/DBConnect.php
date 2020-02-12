<?php
declare(strict_types=1);

namespace App\Commands;

class DBConnect
{
    private $connection;

    public function __construct() {
        $dbopts = parse_url(getenv('DATABASE_URL'));

        $driver = 'pgsql';
        $user = $dbopts["user"];
        $pass = $dbopts["pass"];
        $host = $dbopts["host"];
        $port = $dbopts["port"];
        $db = ltrim($dbopts["path"], '/');
        $this->connection = new \PDO("pgsql:host=$host dbname=$db", $user, $pass);
    }

    public function addDebt ($username, $debt_to, $credit)
    {
        $sql = 'INSERT INTO debts (username, debt_to, credit) VALUES (:username, debt_to, :credit)';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['username' => $username, 'debt_to' => $debt_to, 'credit' => $credit]);
    }

}