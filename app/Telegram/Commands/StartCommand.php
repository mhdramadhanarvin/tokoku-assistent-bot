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

        $fromTelegram = request()->message['chat'];
        $user = UsersModel::find($fromTelegram['id']);
        $name = $fromTelegram['username'];

        $this->replyWithMessage(['text' => "Oke, $name bot sudah siap.."]);

        $response = 'Daftar Command:' . PHP_EOL;
        $commands = $this->getTelegram()->getCommands();
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }
        $this->replyWithMessage(['text' => $response]);
    }
}
