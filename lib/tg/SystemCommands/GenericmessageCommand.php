<?php

namespace Longman\TelegramBot\Commands\SystemCommands;


use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommands\TranslateCommand;
use Longman\TelegramBot\Telegram;

class GenericmessageCommand extends SystemCommand
{
    protected $name = Telegram::GENERIC_MESSAGE_COMMAND;                      // Your command's name
    protected $description = 'Default Action'; // Your command description
    protected $version = '1.2.0';                  // Version of your command

    /**
     * @inheritDoc
     */
    public function execute(): \Longman\TelegramBot\Entities\ServerResponse
    {
        return (new TranslateCommand($this->telegram, $this->update))->execute();
    }
}