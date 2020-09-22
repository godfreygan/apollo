<?php

namespace App\Apollo\Modules;


use App\Util\Curl;
use App\Apollo\Service\ApolloService\Apollo AS ApolloService;
use Log;

class Apollo extends Base
{
    /**
     * @title: 查询最新一套配置
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $env
     * @param $appId
     * @param $clusterName
     * @param string $namespaceName
     * @return mixed
     * @throws \App\Apollo\Library\Exceptions\ServiceException
     */
    public static function getConfig($env, $appId, $clusterName, $namespaceName = 'application')
    {
        $namespaceName = empty($namespaceName) ? 'application' : $namespaceName;
        $requestParam = ['env' => $env, 'appId' => $appId, 'clusterName' => $clusterName, 'namespaceName' => $namespaceName];
        return ApolloService::getLastReleases($requestParam, Curl::CURL_REQUEST_GET);
    }

    /**
     * @title: 获取App列表
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return mixed
     * @throws \App\Apollo\Library\Exceptions\ServiceException
     */
    public static function getApps()
    {
        $requestParam = [];
        return ApolloService::getApps($requestParam, Curl::CURL_REQUEST_GET);
    }

    /**
     * @title: 获取App集群信息
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $appId
     * @return mixed
     * @throws \App\Apollo\Library\Exceptions\ServiceException
     */
    public static function getEnvByAppId($appId)
    {
        $requestParam = ["appId" => $appId];
        return ApolloService::getEnvClusters($requestParam, Curl::CURL_REQUEST_GET);
    }

    /**
     * @title: 拉取apollo配置
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $env
     * @param $appId
     * @param $clusterName
     * @param string $namespaceName
     * @return bool
     */
    public static function apolloSync($env, $appId, $clusterName, $namespaceName = 'application')
    {
        try{
            $data = self::getConfig($env, $appId, $clusterName, $namespaceName);
            $configData = self::formatConfigData($data);
            $ret = self::saveEnv($configData, $appId, $clusterName);
            if(empty($ret)){
                return FALSE;
            }
        } catch(\Exception $e) {
            Log::Error("同步Apollo配置错误", [$e->getCode(), $e->getMessage()]);
        }
        return TRUE;
    }

    /**
     * @title: 格式化配置数据（保存前的格式化）
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $data
     * @return array|string
     */
    private static function formatConfigData($data)
    {
        $apollo = array_get($data, 'configurations', []);
        ksort($apollo);

        $newArray = array();
        if (is_array($apollo) && ! empty($apollo)) {
            $releaseKey = array_get($data, 'name', array_get($data, 'id', 0));
            $newArray = [
                'desc' => "# get env from apollo releaseKey：$releaseKey at " . date("Y-m-d H:i:s"),
            ];

            $idx = 0;
            foreach ($apollo as $key => $value) {
                if (preg_match('/\s/', $value) > 0 && (strpos($value, '"') > 0 && strpos($value, '"', -0) > 0)) {
                    $value = '"' . $value . '"';
                }

                $newArray[$idx] = $key . "=" . $value;
                $idx++;
            }

            $newArray = implode("\n", $newArray);
        }
        return $newArray;
    }

    /**
     * @title: 保存.env文件
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param $data
     * @param $app
     * @param $clusterName
     * @return bool|string
     */
    private static function saveEnv($data, $app, $clusterName)
    {
        $saveDir = rtrim(env("OUTPUT_APPLICATION_PATH"), '/');

        if(env('APP_ENV') == 'dev') {
            // 针对clusterName进行特殊分割
            $firstIndex = strpos($clusterName, '_');
            if($firstIndex === FALSE || $firstIndex == 0){
                $developName = $clusterName;
                $dir = $saveDir . DIRECTORY_SEPARATOR . $developName . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR;
            } else {
                $developName = substr($clusterName, 0, $firstIndex);
                $branchName = substr($clusterName, $firstIndex + 1);
                $dir = $saveDir . DIRECTORY_SEPARATOR . $developName . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $branchName . DIRECTORY_SEPARATOR;
            }
        } else {
            $dir = $saveDir . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR;
        }

        // 判断path是否可写
        if (! is_dir($dir)) {
            Log::error("目录不存在", [$app, $dir]);
            return false;
        } elseif (! is_writable($dir)) {
            Log::error("目录不可写", [$app, $dir]);
            return false;
        }
        file_put_contents($dir . ".env", $data);

        return $dir;
    }
}
