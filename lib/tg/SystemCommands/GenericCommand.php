<?php

namespace Longman\TelegramBot\Commands\SystemCommands;


use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Telegram;

class GenericCommand extends SystemCommand
{
    protected $name = Telegram::GENERIC_COMMAND;                      // Your command's name
    protected $description = 'Unknown Command'; // Your command description
    protected $version = '1.2.0';                  // Version of your command

    /**
     * @inheritDoc
     */
    public function execute(): \Longman\TelegramBot\Entities\ServerResponse
    {
        return $this->replyToChat('Unbekanntes Command / Unknown Command');
    }
}