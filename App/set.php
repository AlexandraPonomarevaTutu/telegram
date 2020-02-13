<?php
declare(strict_types=1);
require_once('../vendor/autoload.php');

$bot_api_key  = getenv('TELEGRAM_BOT_TOKEN');
$bot_username = getenv('BOT_NAME');
$hook_url     = getenv('DOMAIN') . '/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    echo $e->getMessage();
}