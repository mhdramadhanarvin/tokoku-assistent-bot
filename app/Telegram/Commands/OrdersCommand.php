<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\UsersModel;
use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Http;

class OrdersCommand extends Command
{
    protected $name = "orders";

    protected $description = "Check orders in Tokoku Itemku";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $fromTelegram = request()->message;
        $order_code_status = $this->checkFormat($fromTelegram);

        if (is_int($order_code_status)) {
            $user = UsersModel::with('toko')->find($fromTelegram['chat']['id']);
            $toko = $user->toko()->first();
            $token = $toko->token ?? '';
            $client_id = $toko->client_id ?? '';

            $authCheck = (new StatusCommand)->authCheck($token, $client_id);

            if ($authCheck == 'Success') {
                return $this->replyWithMessage($this->getOrders($user->id, $order_code_status));
            } else {
                return $this->replyWithMessage([
                    'text' => "Unauthorized"
                ]);
            }
        }
    }

    public function checkFormat($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);
        $status = ['', 'all', 'unprocessed', 'waiting', 'done', 'on-cancel', 'canceled'];
        if (count($arguments) == 2) {
            if (!in_array($arguments[1], $status)) {
                return $this->replyWithMessage([
                    'text' => "Format tidak valid. Format /orders <status>\n<status> : optional | default:unprocessed all|unprocessed|waiting|done|on-cancel|canceled\nContoh: /orders unprocessed"
                ]);
            }
            $code_history = array_search($arguments[1], $status);
        } else {
            $code_history = 2;
        }

        return $code_history;
    }

    public function getOrders($user_id, $order_code_status, $limit = 5)
    {
        $user = UsersModel::with('toko')->find($user_id);
        $toko = $user->toko()->first();
        $token = $toko->token ?? '';
        $client_id = $toko->client_id ?? '';
        $response = Http::withHeaders([
            "Accept"        => "application/json",
            "Authorization" => "Bearer $token",
            "Client-ID"     => $client_id,
            "Content-Type"  => "application/json"
        ])->get("https://tokoku.itemku.com:81/order-history?status=$order_code_status&page=1&sort=latest&include_order_seller=1&include_order_info=1&search_type=order_number");

        $data = json_decode($response->body())->data->data;

        $response = "Orders" . PHP_EOL . PHP_EOL;
        foreach ($data as $key => $row) {
            $status = ['', 'all', 'unprocessed', 'waiting', 'done', 'on-cancel', 'canceled'];
            $premium = $row->is_premium ? " \t\t<b><i>premium</i></b>"  : "";
            $response .= $row->order_number . $premium . PHP_EOL;
            $response .= "\t" . "Product Name : \t" . $row->product_name . PHP_EOL;
            $response .= "\t" . "Product Category : \t" . $row->game_name . PHP_EOL;
            $response .= "\t" . "Order Date : \t" . date("d F Y H:i:s", strtotime($row->paid_at)) . PHP_EOL;
            $response .= "\t" . "Price Sale : \t Rp " . number_format($row->order_value, 0, ',', ' ') . PHP_EOL;
            $response .= "\t" . "Seller Income : \t Rp " . number_format($row->seller_income, 0, ',', ' ') . PHP_EOL;
            $response .= "\t" . "Status : \t" . ucwords($status[$row->status]) . PHP_EOL;
            $response .= PHP_EOL;

            if ($key == 2) break;
        }

        if (count($data) == 0) $response .= "`DATA KOSONG`";

        return ['text' => $response, 'parse_mode' => "HTML"];
    }
}
