<?php
namespace App\Apollo\ApiControllers;
/**
 * 404页面
 *
 */
use Log;

class NotfoundController extends ApiBase {

    /**
     * @title: 404 地址
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return array
     */
    public function indexAction()
    {
        $uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        Log::info(__METHOD__ . '请求地址不存在', ['uri' => $uri]);
        return $this->responseError('404','api接口不存在', new \stdClass(), __METHOD__);
    }


}