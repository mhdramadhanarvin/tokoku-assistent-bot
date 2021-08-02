<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\UsersModel;
use Telegram\Bot\Commands\Command;

class NotificationCommand extends Command
{
    protected $name = "notification";

    protected $description = "Notification status Tokoku";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;

        $arguments = explode(' ', $fromTelegram['text']);

        if (count($arguments) == 2) {
            if (!in_array($arguments[1], ['on', 'off'])) {
                $this->replyWithMessage(['text' => "Format tidak valid. Format: /notification <on/off>"]);
            } else {
                $user = UsersModel::find($fromTelegram['chat']['id']);
                $user->enable_notification = $arguments[1] == 'on' ? 1 : 0;
                $user->save();

                $this->replyWithMessage(['text' => "Status notifikasi berhasil diupdate!"]);
            }
        } else {
            $this->replyWithMessage(['text' => "Format tidak valid. Format: /notification <on/off>"]);
        }
    }
}
