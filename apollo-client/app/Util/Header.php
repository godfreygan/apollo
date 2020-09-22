<?php
/**
 * 获取请求头工具类
 * User: Liu Zan
 * Date: 2019/3/25
 * Time: 10:23
 */

namespace App\Util;

class Header
{
    const APP_TYPE         = 'APP_TYPE';        //app类型
    const APP_PLATFORM     = 'APP_PLATFORM';    //平台 i-iOS  a-Android mp-小程序 h5-H5站点 pc-PC站点 m-manage ac-安畅 dna-基因检测 s-商家后台 order-订单 script-脚本
    const APP_V            = 'APP_V';           //版本号 1.0.0
    const APP_DEVICE_MODEL = 'APP_DEVICE_MODEL';//设备型号 iPhone，iPad，小米，华为
    const APP_DEVICE_ID    = 'APP_DEVICE_ID';   //设备号 设备唯一ID iOS使用openudid Android
    const SHARE_ID         = 'SHARE_ID';        //分享ID
    
    public static function getVal($key,$http_prefix = 'HTTP_'){
        $key = strtoupper($http_prefix.$key);
        return isset($_SERVER[$key])?$_SERVER[$key]:'';
    }
    
    /**
     * 设备号 设备唯一ID
     * @return bool|string
     */
    public static function getDeviceId(){
        return self::getVal(self::APP_DEVICE_ID);
    }
    
    public static function getAppPlatform()
    {
        return self::getVal(self::APP_PLATFORM);
    }
    
    public static function getAppType(){
        return self::getVal(self::APP_TYPE);
    }
    
    public static function GetHeaderParams()
    {
        $params = [
            'ip'           => getClientIP(),
            'app_type'     => self::getAppType(),
            'app_platform' => self::getAppPlatform(),
            'app_version'  => self::getVal(self::APP_V),
            'api_version'  => 'v1',
            'phone_model'  => self::getVal(self::APP_DEVICE_MODEL),
            'cookie_guid'  => '',
        ];
        return $params;
    }

}
