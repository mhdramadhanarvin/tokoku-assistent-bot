<?php

namespace App\Jobs;

use App\Models\AuthModel;
use App\Models\UsersModel;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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
            $toko = $row->toko;
            $checkAuth = (new StatusCommand)->authCheck($toko->token, $toko->client_id);

            if ($checkAuth == "Success") {
                $token = $toko->token ?? '';
                $client_id = $toko->client_id ?? '';
                $dashboard = $this->dashboardTokoku($token, $client_id);

                $waiting_seller = $dashboard->data->waiting_for_seller;
                if ($waiting_seller > 0 and $waiting_seller != $toko->unprocessed_order) {
                    $this->sendReminderNewOrder($row->id, $row->repeat_order_notification);
                    $this->saveNewUnProcessedOrder($toko, $waiting_seller, $dashboard->data);
                }
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

    public function sendReminderNewOrder($user_id, $count = 1)
    {
        $no = 0;
        for ($i = 0; $i < $count; $i++) {
            $no++;
            Telegram::sendMessage([
                'chat_id' => $user_id,
                'text'  => "Alert #$no !!!. New Order...",
                'parse_mode' => "HTML"
            ]);
        }
    }

    public function dashboardTokoku($token, $client_id)
    {
        $response = Http::withHeaders([
            "Accept"        => "application/json",
            "Authorization" => "Bearer $token",
            "Client-ID"     => $client_id,
            "Content-Type"  => "application/json"
        ])->get('https://tokoku.itemku.com:81/dashboard');

        // return json_decode($response->body());

        //temp test
        $response_tmp = '{"success":true,"data":{"waiting_for_seller":1,"waiting_for_buyer":0,"done":21,"complained":0,"refunded":0,"out_of_Stock":2,"shop_balance":523159,"in_progress_transaction":0},"message":"Success","statusCode":"SUCCESS"}';
        return json_decode($response_tmp);
    }

    public function saveNewUnProcessedOrder(AuthModel $toko, $new_data, $dashboard)
    {
        $toko->unprocessed_order = $new_data;
        $toko->dashboard_data = json_encode($dashboard);
        // $toko->save();
    }
}
