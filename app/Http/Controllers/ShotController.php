<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ShotSearchConversations;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ShotController extends Controller
{

    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {

        $botman = app('botman');

        $botman->hears('.*', function ($bot) {
            $bot->startConversation(new ShotSearchConversations());
        });

        $botman->listen();

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        Log::debug("test");

        return view('shot');
    }

    /**
     *
     */
    public function init(){

        $botman = app('botman');

        $botman->say('こんにちは！',$botman->getUser()->getId());
        $botman->say('まずはどこで働きたいかを教えてください。',$botman->getUser()->getId());

        $botman->listen();
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     * @param  string $sentence
     */
    public function startConversation(BotMan $bot,string $sentence)
    {
        $bot->startConversation(new ShotSearchConversations());
    }
}
