<?php

namespace App\Telegram\Commands;

use Carbon\Carbon;
use Telegram\Bot\Actions;
use App\Models\AbsensiModel;
use Telegram\Bot\Commands\Command;

class AbsensiCommand extends Command
{
    protected $name = "absensi";

    protected $description = "Absensi sekarang!";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;
        $arguments = explode(' ', $fromTelegram['text']);

        $this->checkLimit($fromTelegram);
        $this->checkFormat($fromTelegram);
    }

    public function checkLimit($fromTelegram)
    {
        $absensi = AbsensiModel::whereDate('created_at', Carbon::today())
                                ->where('user_id', $fromTelegram['chat']['id'])
                                ->count();

        if ($absensi == 2) {
            $this->replyWithMessage(['text' => "Absensi sudah mencapai batas maksimum 2 perhari"]);
            exit;
        }
    }

    public function checkFormat($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);

        if (count($arguments) == 1) {
            $this->saveWithoutSetTIme($fromTelegram);
        } else if (count($arguments) == 2) {
            $this->checkFormatTime($fromTelegram);
        }
    }

    public function checkFormatTime($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);

        $format = preg_match("/^(?:2[0-4]|[01][1-9]|10):([0-5][0-9])$/", $arguments[1]);
        if (!$format) {
            $this->replyWithMessage(['text' => "Format tidak valid. Format MM:DD 24-Hour. Contoh: /absensi 16:55"]);
            exit;
        }

        $this->saveWithSetTime($fromTelegram);
    }

    public function saveWithoutSetTIme($fromTelegram)
    {
        $absensi = new AbsensiModel;
        $absensi->user_id = $fromTelegram['chat']['id'];
        $absensi->time = Carbon::now()->format('H:i');
        $absensi->save();

        $this->sendMessage();
    }

    public function saveWithSetTime($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);

        $absensi = new AbsensiModel;
        $absensi->user_id = $fromTelegram['chat']['id'];
        $absensi->time = $arguments[1];
        $absensi->save();

        $this->sendMessage();
    }

    public function sendMessage()
    {
        $this->replyWithMessage(['text' => "OK, absensi disimpan."]);
    }
}
