<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Start command
 */
class TranslateCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'translate';

    /**
     * @var string
     */
    protected $description = 'Translate command';

    /**
     * @var string
     */
    protected $usage = '/translate';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        global $logger;
        $msg = $this->getMessage();
        $logger->debug('Translate', $msg->getRawData());
        //$deepl = \DeeplApi::make();
        //$translate = $deepl->translate($msg->getText(true), 'EN', 'DE', true);
        return $this->replyToChat('OK');
    }
}