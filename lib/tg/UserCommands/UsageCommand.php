<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class UsageCommand extends \Longman\TelegramBot\Commands\Command
{

    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
        $deepl = \DeeplApi::make();
        $usage = $deepl->getUsage();
        $text = var_export($usage, true);
        return $this->replyToChat($text);
    }
}