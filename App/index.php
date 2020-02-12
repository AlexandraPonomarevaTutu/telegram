<?php
require_once('../vendor/autoload.php');

echo 'тут живет бот, иди куда шел';


$dbopts = parse_url(getenv('DATABASE_URL'));

$driver = 'pgsql';
$user = $dbopts["user"];
$pass = $dbopts["pass"];
$host = $dbopts["host"];
$port = $dbopts["port"];
$db = ltrim($dbopts["path"], '/');
try {
    $conn = new PDO("pgsql:host=$host dbname=$db", $user, $pass);
    echo 'АТАС';
} catch (PDOException $e) {
    echo $e->getMessage();
}
