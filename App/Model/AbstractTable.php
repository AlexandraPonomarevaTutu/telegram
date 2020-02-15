<?php
declare(strict_types=1);

namespace App\Model;

abstract class AbstractTable
{
    protected $pdo;

    public function __construct() {
        $dbopts = parse_url(getenv('DATABASE_URL'));
        $db = ltrim($dbopts["path"], '/');
        $this->pdo = new \PDO("pgsql:host={$dbopts["host"]} dbname={$db}", $dbopts["user"], $dbopts["pass"]);
    }
}