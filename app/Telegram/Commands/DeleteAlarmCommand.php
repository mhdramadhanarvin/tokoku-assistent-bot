<?php

namespace App\Telegram\Commands;

use App\Models\AlarmModel;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class DeleteAlarmCommand extends Command
{
    protected $name = "delalarm";

    protected $description = "Hapus alarm anda. Contoh: /delalarm <id>";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;
        $arguments = explode(' ', $fromTelegram['text']);

        $this->checkFormat($arguments);
        $this->checkOwner($fromTelegram);

        $this->triggerCommand('myalarm');
    }

    public function checkFormat($arguments)
    {
        if (count($arguments) != 2) {
            $this->replyWithMessage([
                "text" => "Format tidak valid. Contoh: /deletealarm <id>"
            ]);
            exit;
        } else {
            if (!is_numeric($arguments[1])) {
                $this->replyWithMessage([
                    "text" => "Format tidak valid. Contoh: /deletealarm <id>"
                ]);
                exit;
            }
        }
    }

    public function checkOwner($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);
        $alarm = AlarmModel::find($arguments[1]);

        if ($alarm) {
            if ($alarm->user_id != $fromTelegram['chat']['id']) {
                $this->replyWithMessage([
                    "text" => "Permintaan tidak dapat diproses!"
                ]);
                exit;
            }
        } else {
            $this->replyWithMessage([
                "text" => "Alarm tidak ditemukan!"
            ]);
            exit;
        }

        $this->deleteAlarm($alarm);
    }

    public function deleteAlarm(AlarmModel $alarm)
    {
        $alarm->delete();

        $this->replyWithMessage([
            "text" => "Alarm berhasil dihapus."
        ]);
    }
}
