<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\UsersModel;
use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Http;

class StatusCommand extends Command
{
    protected $name = "status";

    protected $description = "Show status of Tokoku Itemku";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;
        $user = UsersModel::find($fromTelegram['chat']['id']);
        $toko = $user->toko->first();
        $toko_name = $toko->toko_name;
        // $link_toko = $toko->link_toko;
        $link_toko = "";
        $auth_check = $this->authCheck($toko->token, $toko->client_id);
        $notification = $user->enable_notification == 1 ? "Yes" : "No";
        // $orders = $this->getOrders($toko->token, $toko->client_id);
        // dd(json_decode($orders)->data->data);

        $this->replyWithMessage([
            'text' => "Info : \nStore Name : $toko_name\nStore Link : $link_toko\nStore Auth : $auth_check\nEnable Notification : $notification\n"
        ]);

        // $this->triggerCommand('start');
    }

    public function authCheck($token, $client_id)
    {
        $response = Http::withHeaders([
            "Accept"        => "application/json",
            "Authorization" => "Bearer $token",
            "Client-ID"     => $client_id,
            "Content-Type"  => "application/json"
        ])->get('https://tokoku.itemku.com:81/point');

        return $response->successful() ? 'Success' : 'Failed';
    }

    public function getOrders($token, $client_id)
    {
        $response = Http::withHeaders([
            "Accept"        => "application/json",
            "Authorization" => "Bearer $token",
            "Client-ID"     => $client_id,
            "Content-Type"  => "application/json"
        ])->get('https://tokoku.itemku.com:81/order-history?status=4&page=1&sort=latest&include_order_seller=1&include_order_info=1&search_type=order_number');

        return $response->body();
    }
}
