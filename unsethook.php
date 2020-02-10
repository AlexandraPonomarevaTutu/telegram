<?php
declare(strict_types=1);

/**
 * README
 * This file is intended to unset the webhook.
 * Uncommented parameters must be filled
 */

// Load composer
require_once __DIR__ . '/vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable('/');
$dotenv->load();

// Add you bot's API key and name
$bot_api_key  = getenv('TELEGRAM_BOT_TOKEN');
$bot_username = getenv('BOT_NAME');

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Delete webhook
    $result = $telegram->deleteWebhook();

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}