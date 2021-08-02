<?php

namespace App\Telegram\Commands;

use App\Models\AuthModel;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Http;


class AuthCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "auth";
    protected $client_id = "seller-web_d946981ba00f215004d36c42ffc9a602";

    /**
     * @var string Command Description
     */
    protected $description = "Set authenticate for Tokoku Itemku.";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;
        $format = $this->checkFormat($fromTelegram);

        if (is_array($format)) {
            $valid = $this->checkValid($format[0], $format[1]);

            if (is_array($valid)) {
                $this->saveAuth($fromTelegram['chat']['id'], $format[0], $format[1], $valid[0], $valid[1]);
                $this->replyWithMessage([
                    'text' => "OK, otentikasi disimpan!"
                ]);
            }
        }
    }

    public function checkFormat($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);
        $hash = base64_encode('testtoken');
        if (count($arguments) < 2) {
            return $this->replyWithMessage([
                'text' => "Format tidak valid. Format /token <token> <client_id> \n<token> : required \n<client_id> : optional \nContoh: /token $hash ffc9a602 \n"
            ]);
        }
        $client_id = $arguments[2] ?? $this->client_id;

        return [$arguments[1], $client_id];
    }

    public function checkValid($token, $client_id)
    {
        $response = Http::withHeaders([
            "Accept"        => "application/json",
            "Authorization" => "Bearer $token",
            "Client-ID"     => $client_id,
            "Content-Type"  => "application/json"
        ])->get('https://tokoku.itemku.com:81/shop');

        if ($response->failed()) {
            $this->replyWithMessage([
                'text' => "Token yang dimasukkan tidak valid (unauthorized)!"
            ]);

            return false;
        }
        $data = json_decode($response->body())->data->data;

        return [$data->name, "https://itemku.com/toko/" . $data->slug . "/" . $data->user_id];
    }

    public function saveAuth($user_id, $token, $client_id, $toko_name, $link_toko)
    {
        $auth = new AuthModel;
        $auth->user_id = $user_id;
        $auth->toko_name = $toko_name;
        $auth->link_toko = $link_toko;
        $auth->token = $token;
        $auth->client_id = $client_id;
        $auth->save();
    }
}
