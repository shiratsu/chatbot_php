<?php

namespace App\Lib;

use Illuminate\Http\Request;
/**
 * Created by PhpStorm.
 * User: hiratsukashu
 * Date: 2017/07/05
 * Time: 18:36
 */
interface HttpClientInterface
{

    /**
     * パラメータを作成
     * @param Request $request
     * @param array $
     * @return mixed
     */
    public function makeParam(Request $request,array $something = []);

    /**
     * postして返却
     * @param array $param
     * @param $strUrl
     * @param $strErrorMessage
     * @return mixed
     */
    public function postRequest(array $param,string $strUrl,string $strErrorMessage = '通信に失敗しました');

    /**
     * getして返却
     * @param array $param
     * @param $strUrl
     * @param $strErrorMessage
     * @return mixed
     */
    public function getRequest(array $param,string $strUrl,string $strErrorMessage = '通信に失敗しました');

}