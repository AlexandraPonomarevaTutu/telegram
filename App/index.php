<?php
require_once('../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

if ($_GET['token'] === 1234) {
    echo 'збс у тебя токен';
} else {
    echo 'токен параша';
}
