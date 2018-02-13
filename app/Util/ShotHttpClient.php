<?php
/**
 * Created by PhpStorm.
 * User: hiratsukashu
 * Date: 2018/02/07
 * Time: 13:00
 */

namespace App\Util;
use App\Util\HttpClient;
use Illuminate\Http\Request;

final class ShotHttpClient extends HttpClient
{

    /**
     * パラメータを作成
     * @param Request $request
     * @param array $
     * @return mixed
     */
    public function makeParam(Request $request,array $something = []) : array{

        $param = [];

        return $param;
    }

    /**
     * パラメータを作成
     * @param Request $request
     * @param array $
     * @return mixed
     */
    public function makeParamNER(string $sentence,string $strWhatAsk,string $conversationId) : array{

        $param = [];
        $param['conversation_id'] = $conversationId;
        $param['sentence'] = $sentence;
        $param['what_ask'] = $strWhatAsk;

        return $param;
    }

}