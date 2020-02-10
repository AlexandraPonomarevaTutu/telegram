<?php
require_once('../vendor/autoload.php');
use App\helpers\Names;

$bot_api_key  = getenv('TELEGRAM_BOT_TOKEN');
$bot_username = getenv('BOT_NAME');

$commands_paths = [
    __DIR__ . '/Commands',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    $telegram->addCommandsPaths($commands_paths);
    // Handle telegram webhook request
    $telegram->handle();
} catch (Exception $e) {
    fwrite(STDERR, $e->getMessage());
}
