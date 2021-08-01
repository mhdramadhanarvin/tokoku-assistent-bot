<?php

namespace App\Telegram\Commands;

use App\Models\AbsensiModel;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ReportCommand extends Command
{
    protected $name = "report";

    protected $description = "Daftar laporan absensi";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;
        $data = AbsensiModel::where('user_id', $fromTelegram['chat']['id'])
                            ->whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            ->orderBy('created_at', 'ASC')
                            ->get();

        $response = "ABSENSI SAYA" . PHP_EOL . PHP_EOL;
        $date = [];
        $response .= "`";
        $response .= "TGL\t| \tWAKTU" . PHP_EOL;
        foreach ($data as $row) {

            $date = substr($row->created_at, 8, 2);
            $date = is_numeric(strpos($response, $date . "\t | \t")) ? "  " : $date;
            $response .= $date  . "\t | \t" . $row->time . PHP_EOL;

        }
        if (count($data) == 0) $response .= "DATA KOSONG";

        $response .= "`";

        // echo $response;
        $this->replyWithMessage(['text' => $response, 'parse_mode' => "MarkdownV2"]);
    }
}
