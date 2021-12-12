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
use Longman\TelegramBot\TelegramLog;

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
        $text = $msg->getText(true);
        if(empty($text)){
            return Request::emptyResponse();
        }
        TelegramLog::debug('command: translate', [$text]);
        $deepl = \DeeplApi::make();
        $underContingent = $deepl->checkUsage(strlen($text));
        if ($underContingent){
            $translate = $deepl->translate($text, 'EN');
            $percent = $deepl->getUsage()['character_percent'];
            $warning = "<i>Your deepL char contingent is at $percent</i>";
            return $this->replyToChat($translate . PHP_EOL . $warning, [
                'disable_notification' => true,
                'reply_to_message_id' => $msg->getMessageId(),
                'parse_mode' => 'html',
            ]);
        }
        // over limit :O
        $usage = $deepl->getUsage();
        $leftover = $usage['character_limit'] - $usage['character_count'];
        return $this->replyToChat("Your message is too long. You have no contingent left. Only $leftover chars left :(", [
            'disable_notification' => true,
            'reply_to_message_id' => $msg->getMessageId(),
            'parse_mode' => 'html',
        ]);
    }
}