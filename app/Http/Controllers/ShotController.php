<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ShotSearchConversations;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;


class ShotController extends Controller
{

    private $_strConversationId = null;

    /**
     * Place your BotMan logic here.
     */
    public function handle(Request $request)
    {

        Log::debug('------------conversation_id-----------------');
        Log::debug($request->get('conversation_id'));

        $this->_strConversationId = $request->get('conversation_id');

        $botman = app('botman');

        if(!empty($this->_strConversationId)){

            // 空文字はスルー
            $botman->hears('.+', function ($bot) {
                $bot->startConversation(new ShotSearchConversations($this->_strConversationId));
            });
        }else{
            $botman->say('不正アクセスのため処理を中断します。',$botman->getUser()->getId());
        }


        $botman->listen();



    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker(Request $request)
    {
        Log::debug("test");

        $param = [];
        $param['strConversationId'] = $this->_makeStrConverSationId($request);

        $this->_makeStrConverSationId($request);

        return view('shot',$param);
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
     * 会話IDを作成
     * @param Request $request
     * @return string
     */
    private function _makeStrConverSationId(Request $request) : ?string{

        // TODO: もうちょっと何とかする。さらに時間を加えるとか。
//        $strConversationId = '';
        $strWorkId = $request->get('workid');
        $strConversationId = $strWorkId;
        return $strConversationId;
    }
}
