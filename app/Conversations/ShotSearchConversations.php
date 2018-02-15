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
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

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

        $response = $this->_callChatAPI($this->bot->getMessage()->getText(),$this->_strConversationId,'LOC');

        if ($this->_checkAnswer($response)){
            Log::info(json_decode($response->getBody(),true));

            // デコード
            $objContent = json_decode($response->getBody(), true);

            Log::info($objContent);


            $strMessage = null;
            $strMessage = $objContent['sentence'];
            $strWhatAsk = $objContent['what_ask'];
            if(!empty($strMessage) && !empty($strWhatAsk)){

                if($strWhatAsk == 'JOB'){
                    $this->_askJob($strMessage);
                }else{
                    // 確認をする
                }

            }
        }

    }

    /**
     * 言語解析＋文言作成のAPIを叩く
     * @param string $text
     * @param string $strConversationId
     * @param string $strWhatAsk
     */
    private function _callChatAPI(string $text,string $strConversationId,string $strWhatAsk) : ?Response
    {
        $param = $this->_objHttp->makeParamNER($text,$strWhatAsk,$strConversationId);

        $response = $this->_objHttp->postRequest($param,env('LANGUAGE_ANALYSIS_URL').'/shot');

        return $response;
    }

    /**
     * 職種を聞く
     * @param string $strMessage
     */
    private function _askJob(string $strMessage){

        $this->ask($strMessage, function(Answer $answer) use ($strMessage) {
            $strAnswer = $answer->getText();

            $response = $this->_callChatAPI($strAnswer,$this->_strConversationId,'JOB');

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



    /**
     * @param ResponseInterface $response
     */
    private function _checkAnswer(Response $response): bool{

        // nullなら失敗
        if($response == null){
            $this->_askAgain();
            return false;
        }
        // 200じゃないなら
        if($response->getStatusCode() != "200"){
            $this->_askAgain();
            return false;
        }
        return true;

    }


}