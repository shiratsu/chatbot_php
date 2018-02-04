<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {

        $botman = app('botman');

        $botman->hears('Hello', function ($bot) {

            $this->startConversation($bot);
        });

        $botman->listen();

    }

    /**
     * 初期ロードと同じタイミングで呼ばれる
     */
    public function init(){
        Log::debug("test4");
        $botman = app('botman');
        $botman->say('Hi! I am bot man!', '99999');
        $botman->listen();

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        Log::debug("test");

        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
