<?php
/**
 * Created by PhpStorm.
 * User: hiratsukashu
 * Date: 2017/07/05
 * Time: 18:44
 */

namespace App\Lib;

use GuzzleHttp;
use App\Util\HttpClientInterface;
use Illuminate\Support\Facades\Log;

abstract class HttpClient implements HttpClientInterface
{

    protected $objHttp = null;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->objHttp = new GuzzleHttp\Client();
    }


    /**
     * postして返却
     * @param array $param
     * @param $strUrl
     * @param $strErrorMessage
     * @return mixed
     */
    final public function postRequest(array $param,string $strUrl,string $strErrorMessage = '通信に失敗しました')
    {
        // TODO: Implement postRequest() method.
        $response = null;

        Log::debug($strUrl);
        Log::debug($param);

        try {
            $response = $this->objHttp->post(
                $strUrl,
                [

                    'body' => $param
                ]
            );
        } catch(\Exception $e) {
            Log::error($strErrorMessage);
            Log::error($e->__toString());
            Log::error($param);

        }
        return $response;
    }

    /**
     * getして返却
     * @param array $param
     * @param $strUrl
     * @param $strErrorMessage
     * @return mixed
     */
    final public function getRequest(array $param,string $strUrl,string $strErrorMessage = '通信に失敗しました')
    {
        // TODO: Implement postRequest() method.
        $response = null;

//        Log::debug($strUrl);
//        Log::debug($param);

        try {
            $response = $this->objHttp->get(
                $strUrl,
                [

                    'query' => $param
                ]
            );
        } catch(\Exception $e) {
            Log::error($strErrorMessage);
            Log::error($e->__toString());
            Log::error($param);

        }
        return $response;
    }



}