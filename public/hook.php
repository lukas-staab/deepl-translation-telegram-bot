<?php
define("ROOT", dirname(__DIR__));

error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set("error_log", ROOT . '/log/php.log');

use Longman\TelegramBot\TelegramLog;
use Monolog\Handler\RotatingFileHandler;



require ROOT . '/vendor/autoload.php';

$dot = \Dotenv\Dotenv::createImmutable(ROOT );
$dot->load();

$level = isset($_ENV['debug']) ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;
$logger = new \Monolog\Logger('hook', [
  new RotatingFileHandler(ROOT . '/log/telegram.log', 5, $level)
]);
$updateLog = new \Monolog\Logger('update', [
    new RotatingFileHandler(ROOT . '/log/telegram.log', 5, $level)
]);

const ADMIN_IDS = [
    74241675, //Lukas
    2018213, //Vanessa
];

try {
    // Create Telegram API object
    TelegramLog::initialize($logger, $updateLog);
    $telegram = new Longman\TelegramBot\Telegram($_ENV['TG_BOT_SECRET'], $_ENV['TG_BOT_USERNAME']);
    $telegram->addCommandsPaths([
        ROOT . '/lib/tg/UserCommands/',
        ROOT . '/lib/tg/AdminCommands/',
        ROOT . '/lib/tg/SystemCommands/'
    ]);
    $telegram->enableAdmins(ADMIN_IDS);
    // Handle telegram webhook request
    $telegram->handle();
} catch (Exception $e) {
    // Silence is golden!
    // log telegram errors
    $logger->error('Telegram Exception: ' . $e->getMessage(), $e->getTrace());
}