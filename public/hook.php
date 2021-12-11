<?php

use Monolog\Handler\RotatingFileHandler;

define("ROOT", dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

$dot = \Dotenv\Dotenv::createImmutable(ROOT );
$dot->load();

$logger = new \Monolog\Logger('hook', [
  new RotatingFileHandler(ROOT . '/log/telegram.log')
]);

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($_ENV['TG_BOT_SECRET'], $_ENV['TG_BOT_USERNAME']);
    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}