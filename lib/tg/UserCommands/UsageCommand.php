<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class UsageCommand extends \Longman\TelegramBot\Commands\UserCommand
{
    /**
     * @var string
     */
    protected $name = 'usage';

    /**
     * @var string
     */
    protected $description = 'Shows contingent';

    /**
     * @var string
     */
    protected $usage = '/usage';

    /**
     * @var string
     */
    protected $version = '1.0.0';


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