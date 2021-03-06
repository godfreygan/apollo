<?php
require dirname(__DIR__) . '/bootstrap/autoload.php';

//供swagger跨域使用,暂时放在这里
if(env('APP_ENV', 'production') == 'dev'){
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers:content-type,*');
}
$xFromService = isset($_SERVER['HTTP_X_FROM_SERVICE'])?$_SERVER['HTTP_X_FROM_SERVICE']:'mobile-api';
$routeObj = CjsSimpleRoute\Route::getInstance()->init(\App\Util\FromService::getInstance()->fromService2Namespace(getModuleName(), $xFromService))->setUrlPattern('');
$res = $routeObj->run(function($me){
    $ret = [
        'className'=>$me->getAppCtlNamespace() . 'IndexController',
        'method'=>"indexAction", //默认方法
    ];

    $uri = $me->getUri();
    $uriInfo = explode('?', $uri, 2);
    $uriPath = trim(array_shift($uriInfo), '/');
    if (!empty($uriPath)) {
        if(preg_match('/^v[0-9]+[a-z_]+/i', $uriPath)) {//类似这种接口/v1_gateway_coupon/nologin/coupon/info
            $uriPath =  explode('/', $uriPath);
            $ret['className'] = sprintf('%s%sController', $me->getAppCtlNamespace(), ucfirst(preg_replace_callback('/(_|-|\.)([a-zA-Z])/', function($match){return '\\'.strtoupper($match[2]);}, $uriPath[0])) );
            if (isset($uriPath[1])) {
                $ret['method'] =  $uriPath[1] . 'Action';
                unset($uriPath[0], $uriPath[1]);
            } else {
                unset($uriPath[0]);
            }
        } else { // order/v1/order/info 或 v1/order/info
            $tmp = \HdsCommon\CommonFunc::parserPath($uriPath, 'apollo');
            $ret['className'] = sprintf('%s%sController', $me->getAppCtlNamespace(),$tmp['controller']);
            $ret['method'] =  $tmp['function'] . 'Action';

        }
    }
    return $ret;
});
if($routeObj->getRouteExists()){
    if(is_array($res)) {
        if(!isset($res['data']) || !$res['data'] || is_null($res['data'])) {
            $res['data'] = new \stdClass();
        }
        echo json_encode($res, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    } else {
        echo $res;
    }
} else {
    $notfoundObj = new \App\Apollo\ApiControllers\NotfoundController();
    $res = $notfoundObj->indexAction();
    echo json_encode($res, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

}
