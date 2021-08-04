<?php

namespace App\Jobs;

use App\Models\UsersModel;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Telegram\Commands\StatusCommand;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReminderNewOrder implements ShouldQueue
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
        $users = UsersModel::with('toko')->where('enable_notification', 1)->get();

        foreach ($users as $key => $row) {
            $toko = $row->toko()->first();
            $checkAuth = (new StatusCommand)->authCheck($toko->token, $toko->client_id);

            if ($checkAuth == "Success") {
            } else {
                for ($i = 0; $i < 2; $i++) {
                    Telegram::sendMessage([
                        'chat_id' => $row->id,
                        'text'  => "Unauthorized Token"
                    ]);
                }
            }
        }
    }

    public function dashboardTokoku()
    {
    }
}