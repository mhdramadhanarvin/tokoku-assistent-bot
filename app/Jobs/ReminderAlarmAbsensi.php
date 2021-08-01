<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\AlarmModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReminderAlarmAbsensi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $time = Carbon::now()->format('H:i');

        $alarm = AlarmModel::where('time', '>=', $time)->get();

        foreach ($alarm as $row) {
            if ($row->time == $time) {
                for ($i=1;$i<=10;$i++) {
                    Telegram::sendMessage([
                        'chat_id'    => $row->user_id,
                        'text'  => "Jangan lupa untuk absensi ya.."
                    ]);
                }
            }
        }
    }
}
