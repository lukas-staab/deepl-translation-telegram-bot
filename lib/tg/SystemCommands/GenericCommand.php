<?php

namespace Longman\TelegramBot\Commands\SystemCommands;


use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class GenericCommand extends SystemCommand
{
    protected $name = Telegram::GENERIC_COMMAND;                      // Your command's name
    protected $description = 'Unknown Command'; // Your command description
    protected $version = '1.2.0';                  // Version of your command

    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
        $msg = $this->getMessage();
        if(str_starts_with($msg, '/')){
            return $this->replyToChat('Unbekanntes Command / Unknown Command');
        }
        return Request::emptyResponse();
    }
}