<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\AlarmModel;
use Telegram\Bot\Commands\Command;

class UpdateAlarmCommand extends Command
{
    protected $name = "editalarm";

    protected $description = "Ubah data alarm. Contoh: /editalarm <id> <time>";

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
        if (count($arguments) != 3) {
            $this->replyWithMessage([
                "text" => "Format tidak valid. Format MM:DD 24-Hour. \nContoh: /editalarm <id> <time>"
            ]);
            exit;
        } else {
            if (!is_numeric($arguments[1])) {
                $this->replyWithMessage([
                    "text" => "Format tidak valid. Format MM:DD 24-Hour. \nContoh: /editalarm <id> <time>"
                ]);
                exit;
            }

            $format = preg_match("/^(?:2[0-4]|[01][1-9]|10):([0-5][0-9])$/", $arguments[2]);
            if (!$format) {
                $this->replyWithMessage(['text' => "Format tidak valid. Format MM:DD 24-Hour. \nContoh: /setalarm 16:55"]);
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

        $this->updateAlarm($alarm, $arguments);
    }

    public function updateAlarm(AlarmModel $alarm, $arguments)
    {
        $alarm->time = $arguments[2];
        $alarm->save();

        $this->replyWithMessage([
            "text" => "Alarm berhasil diperbarui."
        ]);
    }
}
