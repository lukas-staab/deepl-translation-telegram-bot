<?php

use Monolog\Handler\RotatingFileHandler;

define("ROOT", dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

$dot = \Dotenv\Dotenv::createImmutable(ROOT );
$dot->load();

$level = isset($_ENV['debug']) ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;
$logger = new \Monolog\Logger('hook', [
  new RotatingFileHandler(ROOT . '/log/telegram.log', 5, )
]);
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($_ENV['TG_BOT_SECRET'], $_ENV['TG_BOT_USERNAME']);
    $telegram->addCommandsPaths([
        ROOT . 'lib/tg/UserCommands/',
        ROOT . 'lib/tg/AdminCommands/',
        ROOT . 'lib/tg/SystemCommands/'
    ]);
    // Handle telegram webhook request
    $logger->debug('Incoming msg');
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    $logger->error('Telegram Exception: ' . $e->getMessage(), $e->getTrace());
}