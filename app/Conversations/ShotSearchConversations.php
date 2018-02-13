<?php
/**
 * Created by PhpStorm.
 * User: hiratsukashu
 * Date: 2018/02/06
 * Time: 13:05
 */

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Log;
use App\Util\ShotHttpClient;

class ShotSearchConversations extends Conversation
{

    private $_objHttp = null;

    private $_strConversationId = null;

    public function __construct(string $strId = null) {
        $this->_strConversationId = $strId;
//        parent::__construct();
        $this->_objHttp = new ShotHttpClient();

    }

    /**
     * @return mixed
     */
    public function run()
    {
        // TODO: Implement run() method.
        $this->askWantLocation();

    }

    /**
     * まずはどこで働きたいかを聞く
     */
    public function askWantLocation(){

        Log::debug($this->bot->getMessage()->getText());

        $param = $this->_objHttp->makeParamNER($this->bot->getMessage()->getText(),'LOC',$this->_strConversationId);

        $response = $this->_objHttp->postRequest($param,env('LANGUAGE_ANALYSIS_URL').'/shot');

        // nullなら失敗
        if($response == null){
            $this->_askAgain();
        }

        // 200じゃないなら
        if($response->getStatusCode() != "200"){
            $this->_askAgain();
        }

        Log::info($response->getBody()->getContents());
        Log::info(json_decode($response->getBody(),true));

        // デコード
        $objContent = json_decode($response->getBody(), true);

        Log::info($objContent);


        $strMessage = null;
        $strMessage = $objContent['sentence'];
        if(!empty($strMessage)){
            $this->_askJob($strMessage);
        }

    }

    /**
     * 職種を聞く
     * @param string $strMessage
     */
    private function _askJob(string $strMessage){

        $this->ask($strMessage, function(Answer $answer) {

        });
    }

    /**
     * もう一度問い合わせる場合に使う。
     */
    private function _askAgain(){
        $this->ask('すみません、通信エラーが発生しました。もう一度お願いします。', function(Answer $answer) {
//            // Save result
//            $this->firstname = $answer->getText();
//
//            $this->say('Nice to meet you '.$this->firstname);
//            $this->askEmail();
        });
    }


}