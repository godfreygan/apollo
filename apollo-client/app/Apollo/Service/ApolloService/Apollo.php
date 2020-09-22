<?php

namespace App\Apollo\Service\ApolloService;

use App\Apollo\Library\Enum\ExceptionCodeEnum;
use App\Apollo\Library\Exceptions\ServiceException;
use App\Apollo\Service\Base;
use App\Util\Curl;
use Log;

class Apollo extends Base
{

    /**
     * @title: 获取App列表
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $param
     * @param $method
     * @return mixed
     * @throws ServiceException
     */
    public static function getApps($param, $method = Curl::CURL_REQUEST_POST) {
        $url = env('DOMAIN_APOLLO_SERVICE_HOST') . '/openapi/v1/apps';
        $url = self::urlFormat($url, $param);
        $header = [
            "content-type: application/json",
            "Authorization: ". env('APOLLO_AUTH_TOKEN', ''),
        ];
        Log::info(__METHOD__.'请求入参',['request' => ['url' => $url, 'header' => $header, 'param' => $param, 'method' => $method]]);

        $result = Curl::request($url, $param, $method, Curl::REQUEST_TIMEOUT, $header);

        Log::info(__METHOD__.'返回出参', ['response' => json_encode($result)]);

        //抛异常
        if(! $result){
            Log::error(__METHOD__.'服务不可用，暂无数据',['url'=>$url, 'param'=>$param, 'method'=>$method]);
            throw new ServiceException('服务不可用，暂无数据',ExceptionCodeEnum::DATA_NOT_EXIST);
        }
        return json_decode($result['data'],true);
    }

    /**
     * @title: 获取App环境和集群信息
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $param
     * @param $method
     * @return mixed
     * @throws ServiceException
     */
    public static function getEnvClusters($param, $method = Curl::CURL_REQUEST_POST) {
        $url = env('DOMAIN_APOLLO_SERVICE_HOST') . '/openapi/v1/apps/{appId}/envclusters';
        $url = self::urlFormat($url, $param);
        $header = [
            "content-type: application/json",
            "Authorization: ". env('APOLLO_AUTH_TOKEN', ''),
        ];
        Log::info(__METHOD__.'请求入参',['request' => ['url' => $url, 'header' => $header, 'param' => $param, 'method' => $method]]);

        $result = Curl::request($url, $param, $method, Curl::REQUEST_TIMEOUT, $header);

        Log::info(__METHOD__.'返回出参', ['response' => json_encode($result)]);

        //抛异常
        if(! $result){
            Log::error(__METHOD__.'服务不可用，暂无数据',['url'=>$url, 'param'=>$param, 'method'=>$method]);
            throw new ServiceException('服务不可用，暂无数据',ExceptionCodeEnum::DATA_NOT_EXIST);
        }
        return json_decode($result['data'],true);
    }

    /**
     * @title: 获取某个Namespace当前生效的已发布配置
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $param
     * @param $method
     * @return mixed
     * @throws ServiceException
     */
    public static function getLastReleases($param, $method = Curl::CURL_REQUEST_POST) {
        $url = env('DOMAIN_APOLLO_SERVICE_HOST') . '/openapi/v1/envs/{env}/apps/{appId}/clusters/{clusterName}/namespaces/{namespaceName}/releases/latest';
        $url = self::urlFormat($url, $param);
        $header = [
            "content-type: application/json",
            "Authorization: ". env('APOLLO_AUTH_TOKEN', ''),
        ];
        Log::info(__METHOD__.'请求入参',['request' => ['url' => $url, 'header' => $header, 'param' => $param, 'method' => $method]]);

        $result = Curl::request($url, $param, $method, Curl::REQUEST_TIMEOUT, $header);

        Log::info(__METHOD__.'返回出参', ['response' => json_encode($result)]);

        //抛异常
        if(! $result) {
            Log::error(__METHOD__.'服务不可用，暂无数据',['url'=>$url, 'param'=>$param, 'method'=>$method]);
            throw new ServiceException('服务不可用，暂无数据',ExceptionCodeEnum::DATA_NOT_EXIST);
        }
        return json_decode($result['data'],true);
    }
}
