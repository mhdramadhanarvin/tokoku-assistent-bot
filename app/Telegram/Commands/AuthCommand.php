<?php

namespace App\Telegram\Commands;

use App\Models\AuthModel;
use Telegram\Bot\Actions;
use App\Jobs\ReminderNewOrder;
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
                    'text' => "OK, autentikasi disimpan!"
                ]);
            }
        }
    }

    public function checkFormat($fromTelegram)
    {
        $arguments = explode(' ', $fromTelegram['text']);
        $client_id = $arguments[2] ?? $this->client_id;
        if (count($arguments) < 2) {
            $autoAuthGoogle = $this->autoAuthfromGoogle();

            if (is_string($autoAuthGoogle)) {
                $arguments[1] = $autoAuthGoogle;
            } else {
                return $this->replyWithMessage([
                    'text' => "Format tidak valid. Format /auth <token> <client_id> \n<token> : required \n<client_id> : optional \nContoh: /auth ffiduy6673290d.dwadw ffc9a602 \n"
                ]);
            }
        }

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

    public function autoAuthfromGoogle()
    {
        $response = Http::withHeaders([
            "cookie"    => "LSOLH=||||||_SVI_CgAQq5mCqoWI8gIYECI_TUFFREhmX3ExMERPTTJEeF82X0ptRHpwTVhpdHg0VXlLRm1LSlNBRFVPS0xCX2xYbFNGWm1lNGJXeWlndzFJ_:||||||27125906:3143; __Secure-3PSID=AQiQKLGIRDpHRgcEtrmeIWZbheYcvp9JbdCD8M8HJxYmfZnN2zkG7k-bVGI5I6xGV94NHQ.; __Host-3PLSID=doritos|o.calendar.google.com|o.chat.google.com|o.console.cloud.google.com|o.console.developers.google.com|o.console.firebase.google.com|o.gds.google.com|o.groups.google.com|o.mail.google.com|o.myaccount.google.com|o.passwords.google.com|o.takeout.google.com|s.ID|s.blogger|s.youtube:AQiQKG31u5lTPmcuxEE5Ecwy12K8X-6BGhDTwqskC8ZijJCel-mRALqllZfMtk8d3dtLZg.; __Secure-3PAPISID=abS4ddFybk72Ps7A/AsCQKRFmNDyMHCJki; 1P_JAR=2021-08-04-02; NID=220=LpXXFcGAMrKbBUqa1OPDzlGjz31GY7vffbpWQ6GOKAk78yR1Mc1uaCBhpAQRR684NNdKtWlWSjd9oqdjl97a56OWioOWB0gs3If8Jrnb-t9_C_TUQEpRdmZeTAlONuKa0LFsePLfsM0FyHdTrvOsbiocvErBVN6zdTbl4LNMVS4o79NBHbwpauKGkVpgHdwaT6h26G0Bk9NidlV9wSRIVNvU1mRZu5N2LJ9eF70Qa-ZSpbNJwi0GVOajdvVeSDiClkn9Fw6QCDrQRlHuAk0RpIxLOuGZ706yjPcM_Qg40T1P9ce5bqWA46wOqRNZInIMrflc3efspg; __Secure-3PSIDCC=AJi4QfFhDFW5Pn5E4chY9emZjcTu_BLgWcx0NDb_-nZEF7HEL2t4Om07lDKbIu2EbA6P4InDOw ; __Secure-3PSIDCC=AJi4QfHkiA78mbZVUfoyXz2BewEJsUOrftHlU7tq9z1wq93P_DX-nf1jHxH2Lh2dbrF0zzgrXA",
            "referer"   => "https://accounts.google.com/o/oauth2/iframe",
            "sec-ch-ua" => '"Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
            "sec-ch-ua-mobile"  => "?0",
            "sec-fetch-dest"    => "empty",
            "sec-fetch-mode"    => "cors",
            "sec-fetch-site"    => "same-origin",
            "user-agent"        => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36',
            "x-chrome-id-consistency-request"   => 'version=1,client_id=77185425430.apps.googleusercontent.com,device_id=46cb86c7-fbd9-4958-813f-bc0157d81bf7,sync_account_id=116426962399119174538,signin_mode=all_accounts,signout_mode=show_confirmation',
            "x-requested-with"   => 'XmlHttpRequest'
        ])->get('https://accounts.google.com/o/oauth2/iframerpc?action=issueToken&response_type=token%20id_token&login_hint=AJDLj6K_ACoTXtJXaK8eVP4CyrLe3zM0ebWCmPjjKN3NKgfagF-ZSOnYRRQa62l-KvroCnWW_MhSNl4XHXeo-ScKJchJbF64xg&client_id=912636350905-m0m08kaami7dslumuosi8oga6bf0ti5f.apps.googleusercontent.com&origin=https%3A%2F%2Ftokoku.itemku.com&scope=openid%20profile%20email&ss_domain=https%3A%2F%2Ftokoku.itemku.com');

        if ($response->successful()) {
            dd($response->body());
            $googleToken = json_decode($response->body())->id_token;
            $token = $this->loginGoogle($googleToken);

            if ($token) {
                return $token;
            }
        } else {
            $this->replyWithMessage([
                'text' => "Auto auth google failed (unauthorized)!"
            ]);

            return false;
        }
    }

    public function loginGoogle($googleToken)
    {
        $response = Http::withHeaders([
            "Authorization"     => "Bearer undefined",
            "Client-ID"         => $this->client_id,
            "sec-ch-ua"         => '"Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
            "sec-ch-ua-mobile"  => "?0",
            "User-Agent"        => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36"
        ])->post('https://tokoku.itemku.com:81/user/google-login', [
            "social_id_token"   => $googleToken
        ]);

        if ($response->successful()) {
            $data = json_decode($response->body())->data->access_token;
            return $data;
        } else {
            $this->replyWithMessage([
                'text' => "Auto auth google failed (unauthorized)!"
            ]);

            return false;
        }
    }

    public function saveAuth($user_id, $token, $client_id, $toko_name, $link_toko)
    {
        $dashboard = (new ReminderNewOrder)->dashboardTokoku($token, $client_id)->data;

        $auth = AuthModel::updateOrCreate(
            ['user_id'  => $user_id],
            [
                'toko_name' => $toko_name,
                'link_toko' => $link_toko,
                'token'     => $token,
                'client_id'     => $client_id,
                'unprocessed_order' => $dashboard->waiting_for_seller,
                'dashboard_data'    => json_encode($dashboard)
            ]
        );

        return $auth;
    }
}
