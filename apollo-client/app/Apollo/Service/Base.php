<?php namespace App\Apollo\Service;
use App\Apollo\Library\Exceptions\ServiceException;
use CjsLsf\Core\Config;
use Log;

/**
 * 第三方服务base
 * 所有方法必须为static类型
 * User: Liu Zan
 * Date: 2019/3/12
 * Time: 17:00
 */
class Base
{
    /**
     * 获取第三方返回数据data部分
     * @param $resopnsData
     * @return mixed
     * @throws ServiceException
     */
    protected static function data($resopnsData){
        if($resopnsData['code']){
            Log::error(__METHOD__ .' 第三方请求失败',[$resopnsData]);
            //throw new ServiceException('RPC_SERVICE_ERROR');
            throw new ServiceException($resopnsData['msg'],$resopnsData['code']);
        }
        return $resopnsData['data'];
    }

    /**
     * @title: 格式化处理URL
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $url
     * @param array $params
     * @return string|string[]
     */
    protected static function urlFormat($url, $params = [])
    {
        $preg = "/^http(s)?:\\/\\/.+/";
        if(empty(preg_match($preg, $url))) {
            $url = 'http://'. $url;
        }
        if (is_array($params) && count($params)) {
            $search_keys = array_keys($params);
            foreach ($search_keys as $key) {
                if (strpos($url, "{" . $key . "}")) {
                    $url = str_replace("{" . $key . "}", array_get($params, $key), $url);
                }
            }
        }
        return $url;
    }
}