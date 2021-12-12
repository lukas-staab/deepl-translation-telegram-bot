<?php

namespace Longman\TelegramBot\Commands\SystemCommands;


use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class GenericmessageCommand extends \Longman\TelegramBot\Commands\SystemCommand
{
    protected $name = Telegram::GENERIC_MESSAGE_COMMAND;                      // Your command's name
    protected $description = 'Default Translation Command'; // Your command description
    protected $version = '1.0.0';                  // Version of your command
    protected $need_mysql = false;


    /**
     * @inheritDoc
     */
    public function execute(): \Longman\TelegramBot\Entities\ServerResponse
    {
        $message = $this->getMessage();            // Get Message object
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $deepL = \DeeplApi::make();
        $translation = $deepL->translate($message->getText(true), 'EN', 'DE');
        $data = [
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => $translation, // Set message to send
        ];

        return Request::sendMessage($data);        // Send message!
    }
}