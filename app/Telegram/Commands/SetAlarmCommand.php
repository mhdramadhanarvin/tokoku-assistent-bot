<?php

namespace App\Telegram\Commands;

use App\Models\AlarmModel;
use Telegram\Bot\Actions;
use App\Models\UsersModel;
use Telegram\Bot\Commands\Command;

class SetAlarmCommand extends Command
{
    protected $name = "setalarm";

    protected $description = "Setel alarm. Contoh: /setalarm <time>";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $this->checkFormat();
        $this->checkLimit();

        $this->triggerCommand('myalarm');
    }

    public function checkFormat()
    {
        $fromTelegram = request()->message;
        $arguments = explode(' ', $fromTelegram['text']);

        if (count($arguments) != 2) {
            $this->replyWithMessage(['text' => "Format tidak valid. Format MM:DD 24-Hour. \nContoh: /setalarm <time>"]);
            exit;
        } else {
            $format = preg_match("/^(?:2[0-4]|[01][1-9]|10):([0-5][0-9])$/", $arguments[1]);
            if (!$format) {
                $this->replyWithMessage(['text' => "Format tidak valid. Format MM:DD 24-Hour. \nContoh: /setalarm <time>"]);
                exit;
            }
        }
    }

    public function checkLimit()
    {
        $fromTelegram = request()->message;

        $alarm = AlarmModel::where('user_id', $fromTelegram['chat']['id'])->count();

        if ($alarm >= 3) {
            $this->replyWithMessage(['text' => "Alarm sudah mencapai batas, max 3 alarm."]);
            exit;
        } else {
            $this->saveAlarm();
            $this->replyWithMessage(['text' => "OK, alarm disimpan."]);
        }

    }

    public function saveAlarm()
    {
        $fromTelegram = request()->message;
        $arguments = explode(' ', $fromTelegram['text']);

        $alarm = new AlarmModel;
        $alarm->user_id = $fromTelegram['chat']['id'];
        $alarm->time = $arguments[1];
        $alarm->save();
    }
}
