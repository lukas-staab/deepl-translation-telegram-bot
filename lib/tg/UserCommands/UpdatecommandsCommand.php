<?php


namespace Longman\TelegramBot\Commands\UserCommands;


use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class UpdatecommandsCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'updatecommands';

    /**
     * @var string
     */
    protected $description = 'Aktualisiert alle Commands die vorgeschlagen werden.';

    /**
     * @var string
     */
    protected $usage = '/updatecommands <user|admin|all>';

    /**
     * @var string
     */
    protected $version = '1.0';


    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */

    public function execute() : ServerResponse
    {

        $message = $this->getMessage();
        $text = trim($this->getMessage()->getText(true));
        $photo = $this->getMessage()->getPhoto();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $data = [
            "chat_id" => $chat_id,
            "parse_mode" => 'markdown',
        ];

        if($text !== "all" && $text !== "admin") {
            $text = "user";
        }

        $allCommands = $this->telegram->getCommandsList();

        $commandsAdmin = [];
        $commandsSystem = [];
        $commandsUser = [];
        $commandNames = [];

        foreach ($allCommands as $name => $command){
            /* @var $command Command */
            if($command->showInHelp() && $command->isEnabled()){
                if($command->isAdminCommand()){
                    $commandsAdmin[] = [
                        "command" => $name,
                        "description" => "[ADMIN] " . $command->getDescription(),
                    ];
                    $commandNames["admin"][] = "/" . $command->getName();
                }else if($command->isSystemCommand()){
                    $commandsSystem[] = [
                        "command" => $name,
                        "description" => "[SYSTEM] " . $command->getDescription(),
                    ];
                    $commandNames["system"][] = "/" . $command->getName();
                }elseif($command->isUserCommand()){
                    $commandsUser[] = [
                        "command" => $name,
                        "description" => $command->getDescription(),
                    ];
                    $commandNames["user"][] = "/" . $command->getName();
                }
            }
        }

        $entries = $commandsUser;
        if($text === "admin" || $text === "all"){
            $entries = array_merge($entries,$commandsAdmin);
        }else{
            $commandNames = array_diff_key($commandNames, ["admin" => false]);
        }
        if($text === "all"){
            $entries = array_merge($entries,$commandsSystem);
        }else{
            $commandNames = array_diff_key($commandNames, ["system" => false]);
        }

        $setCommands = Request::setMyCommands(["commands" => $entries]);
        if($setCommands->isOk()){
            $data["text"] = "";
            foreach ($commandNames as $name => $commandName){
                $data["text"] .= "Registrierte `$name`-Commands:" .
                    PHP_EOL . implode(PHP_EOL, $commandName). PHP_EOL . PHP_EOL;
            }
        }else{
            $data["text"] = "Ein Fehler ist aufgetreten";
        }
        return Request::sendMessage($data);
    }
}
