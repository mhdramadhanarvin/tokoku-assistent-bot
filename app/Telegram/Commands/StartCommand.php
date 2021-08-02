<?php

namespace App\Telegram\Commands;

use App\Models\UsersModel;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.
        // $commands = $this->getTelegram()->getCommands();

        // // Build the list
        // $response = '';
        // foreach ($commands as $name => $command) {
        //     $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        // }

        // Reply with the commands list
        // $this->replyWithMessage(['text' => $response]);

        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
        // $this->triggerCommand('subscribe');


        $fromTelegram = request()->message['chat'];
        $user = UsersModel::find($fromTelegram['id']);
        $name = $fromTelegram['username'];

        // if (!$user) {
        //     $users = new UsersModel;
        //     $users->id = $fromTelegram['id'];
        //     $users->name = $name;
        //     $users->save();
        // }

        $this->replyWithMessage(['text' => "Oke, $name bot sudah siap.."]);

        $response = 'Daftar Command:' . PHP_EOL;
        $commands = $this->getTelegram()->getCommands();
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }
        $this->replyWithMessage(['text' => $response]);
    }
}
