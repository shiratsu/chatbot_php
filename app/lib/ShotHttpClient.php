<?php
/**
 * Created by PhpStorm.
 * User: hiratsukashu
 * Date: 2018/02/07
 * Time: 13:00
 */

namespace App\Lib;
use App\Lib\HttpClient;
use Illuminate\Http\Request;

class ShotHttpClient extends HttpClient
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
    public function makeParamNER(string $sentence,array $aryNER) : array{

        $param = [];
        $param['sentence'] = $sentence;
        $param['ner'] = $aryNER;

        return $param;
    }

}