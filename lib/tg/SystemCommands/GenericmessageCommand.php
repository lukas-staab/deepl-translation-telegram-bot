<?php

namespace Longman\TelegramBot\Commands\SystemCommands;


use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommands\TranslateCommand;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;

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
        if($this->getUpdate() !== null){
            TelegramLog::debug('GenericMessage update', $this->getUpdate()->getRawData());
            if($this->getUpdate()->getUpdateType() === 'message'){
                return (new TranslateCommand($this->telegram, $this->update))->execute();
            }
            TelegramLog::debug('MESSAGE TYPE', [$this->getUpdate()->getUpdateType()]);
        }else{
            TelegramLog::debug('GenericMessage update == null');
        }
        return Request::emptyResponse();
    }
}