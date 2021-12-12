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
    protected $usage = '/translate <text>';

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
        $msg = $this->getMessage();
        $deepl = \DeeplApi::make();
        $translate = $deepl->translate($msg->getText(true), 'EN', 'DE', true);
        $percent = $deepl->getUsage()['character_percent'];
        $warning = "<i>Your deepL char contingent is at $percent</i>";
        return $this->replyToChat($translate . PHP_EOL . $warning, [
            'disable_notification' => true,
            'reply_to_message_id' => $msg->getMessageId(),
            'parse_mode' => 'html',
        ]);
    }
}