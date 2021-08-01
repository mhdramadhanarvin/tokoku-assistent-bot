<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Telegram\Bot\Api;
use App\Models\AlarmModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{

    public function __construct()
    {
        Telegram::commandsHandler(true);
    }

    public function index()
    {
        $artisan = Artisan::call('schedule:run');

        return response()->json($artisan);
    }

    public function webhook(Request $request)
    {
        $chat = $request->message;

        // return response()->json($chat);
    }
}
