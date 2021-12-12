<?php

namespace cli;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetWebhookCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'webhook:set';
    protected static $defaultDescription = 'Sets the configured webhook';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $webhookUrl = $_ENV['BASE_URI'];
        if(!str_ends_with($webhookUrl, '/')){
            $webhookUrl .= '/';
        }
        $webhookUrl .= 'hook.php';
        try {
            $telegram = new Telegram($_ENV['TG_BOT_SECRET'], $_ENV['TG_BOT_USERNAME']);
            $result = $telegram->setWebhook($webhookUrl);
            if ($result->isOk()) {
                $output->writeln($result->getDescription());
            }
        }catch (TelegramException $telegramException){
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

}