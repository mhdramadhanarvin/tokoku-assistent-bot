<?php

namespace App\Telegram\Commands;

use App\Models\AuthModel;
use Carbon\Carbon;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Http;


class AuthCommand extends Command
{
    protected $name = "auth";
    protected $client_id = "seller-web_d946981ba00f215004d36c42ffc9a602";

    protected $description = "Set authenticate for Tokoku Itemku. Example: /auth <token> <client_id>";

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;
        $data = $this->checkFormat($fromTelegram);
        $this->checkValid($data[0], $data[1]);
        $this->saveAuth($fromTelegram['chat']['id'], $data[0], $data[1]);

        $this->replyWithMessage([
            'text' => "OK, otentikasi disimpan!"
        ]);
    }

    public function checkFormat($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);
        $hash = base64_encode('testtoken');
        if (count($arguments) < 2) {
            $this->replyWithMessage([
                'text' => "Format tidak valid. Format /token <token> <client_id> \n<token> : required \n<client_id> : optional \nContoh: /token $hash ffc9a602 \n"
            ]);
            exit;
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
        ])->get('https://tokoku.itemku.com:81/point');

        if ($response->failed()) {
            $this->replyWithMessage([
                'text' => "Token tidak valid #Unauthorized"
            ]);
            exit;
        }
    }

    public function saveAuth($user_id, $token, $client_id)
    {
        $auth = new AuthModel;
        $auth->user_id = $user_id;
        $auth->token = $token;
        $auth->client_id = $client_id;
        $auth->save();
    }
}
