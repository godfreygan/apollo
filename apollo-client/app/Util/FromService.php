<?php
/**
 * 来源服务名配置
 * User: jelly
 * Date: 2019-08-21
 * Time: 15:15
 */

namespace App\Util;

use HdsCommon\Util\BaseFromService;
class FromService extends BaseFromService
{

    //允许的服务名
    public function allowFromService() {
        return [self::MOBILE_API, self::MANAGE_API, self::SUPPLIER_API, self::OPEN_API, self::SHOP_API, self::PAY_SERVICE];
    }

    //项目模块的服务名对应的命名空间
    public function fromService2Namespace($moduleName, $fromService) {
        $res = '';
        $moduleName = ucfirst($moduleName);
        $config = [
            'Apollo'=>[
                    self::MOBILE_API=>'\App\\Apollo\\ApiControllers\\',
                ]
            ];
        if(isset($config[$moduleName])) {
            $res = $config[$moduleName][$fromService];
        }
        return $res;
    }
}