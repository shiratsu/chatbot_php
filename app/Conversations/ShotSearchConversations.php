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
use App\Lib\ShotHttpClient;

class ShotSearchConversations extends Conversation
{

    //抜き出したい固有表現
    private $_aryNamedEntity = ['LOC','MONEY_UNIT','MONEY','JOB','DATE'];
    private $_objHttp = null;

    public function __construct() {
        parent::__construct();
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

        $param = $this->_objHttp->makeParam();

        $this->ask($this->bot->getMessage()->getText(), function(Answer $answer) {
//            // Save result
//            $this->firstname = $answer->getText();
//
//            $this->say('Nice to meet you '.$this->firstname);
//            $this->askEmail();
        });

    }
}