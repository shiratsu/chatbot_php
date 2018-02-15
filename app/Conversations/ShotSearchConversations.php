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

        Log::debug("askWantLocation");

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
                    $this->_askJob($strMessage,$this->_strConversationId);
                }else{
                    // 確認をする
                }

            }
        }

    }

    /**
     * 言語解析＋文言作成のAPIを叩く
     * @param string $text
     * @param string|null $strConversationId
     * @param string|null $strWhatAsk
     * @return Response|null
     */
    private function _callChatAPI(string $text,string $strConversationId = null,string $strWhatAsk = null) : ?Response
    {
        Log::info("--------------------------_callChatAPI");
        Log::info($text);
        Log::info($strConversationId);
        Log::info($strWhatAsk);

        $this->_objHttp = new ShotHttpClient();

        if($text == null || $strConversationId == null || $strWhatAsk == null){
            return null;
        }
        $param = $this->_objHttp->makeParamNER($text,$strWhatAsk,$strConversationId);

        $response = $this->_objHttp->postRequest($param,env('LANGUAGE_ANALYSIS_URL').'/shot');

        return $response;
    }

    /**
     * 職種を聞く
     * @param string $strMessage
     * @param string $strConversationId
     */
    private function _askJob(string $strMessage,string $strConversationId){

        Log::info("_askJob");
        Log::info($strMessage);

        $this->ask($strMessage, function(Answer $answer) use ($strConversationId) {
            $strAnswer = $answer->getText();

            Log::info($strAnswer);
            Log::info($strConversationId);

            if(empty($strAnswer)){
                $this->_askJob('空文字です。再度お願いします。',$strConversationId);
                return $this;
            }



            $response = $this->_callChatAPI($strAnswer,$strConversationId,'JOB');


            if ($this->_checkAnswer($response)){
                Log::info(json_decode($response->getBody(),true));

                // デコード
                $objContent = json_decode($response->getBody(), true);

                Log::info($objContent);


                $strMessage = null;
                $strMessage = $objContent['sentence'];
                $strWhatAsk = $objContent['what_ask'];
                if(!empty($strMessage) && !empty($strWhatAsk)){

                    if($strWhatAsk == 'MONEY'){
                        $this->_askMoney($strMessage,$strConversationId);
                    }else{
                        // 確認をする
                    }

                }
            }
        });
    }

    /**
     * 給与に関して聞く
     * @param $strMessage
     */
    private function _askMoney(string $strMessage,string $strConversationId){

        Log::info("_askJob");


        $this->ask($strMessage, function(Answer $answer) use ($strConversationId) {

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
    private function _checkAnswer(Response $response = null): bool{

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