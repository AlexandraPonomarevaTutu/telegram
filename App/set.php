<?php
declare(strict_types=1);
require_once('../vendor/autoload.php');
use App\helpers\Names;

$bot_api_key  = getenv('TELEGRAM_BOT_TOKEN');
$bot_username = Names::SASHA_TEST_BOT;
$hook_url     = 'https://agile-waters-93062.herokuapp.com/hook.php';

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