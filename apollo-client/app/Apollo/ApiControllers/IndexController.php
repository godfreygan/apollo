<?php
namespace App\Apollo\ApiControllers;


use App\Apollo\Library\Exceptions\ServiceException;
use App\Apollo\Modules\Apollo;
use App\Apollo\Modules\Apollo AS ApolloModule;
use App\Util\ValidatorUtil;

class IndexController extends ApiBase {

    public function indexAction()
    {
        return $this->responseSuccess(['tips'=>'移动接口Api正常','cloud_name'=>getenv('CLOUD_NAME')], __METHOD__);
    }

    /**
     * @title: 获取App列表
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return array
     */
    public function getAppsAction()
    {
        //参数定义
        $rulesMap = [];
        try {
            list($rules, $message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate(request()->getJson(), $rules, $message, TRUE, __METHOD__);
            $ret = ApolloModule::getApps();
            return $this->responseSuccess($ret, __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), null, __METHOD__);
        }
    }

    /**
     * @title: 获取App集群信息
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return array
     */
    public function getEnvByAppIdAction()
    {
        //参数定义
        $rulesMap = [
            'app_id' => ['required|string', '应用id'],
        ];
        try {
            list($rules, $message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate(request()->getJson(), $rules, $message, TRUE, __METHOD__);
            $ret = ApolloModule::getEnvByAppId($data['app_id']);
            return $this->responseSuccess($ret, __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), null, __METHOD__);
        }
    }

    /**
     * @title: 查询最新的一套配置
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return array
     */
    public function getConfigAction()
    {
        //参数定义
        $rulesMap = [
            'env'            => ['required|string', '环境代码'],
            'app_id'         => ['required|string', '应用id'],
            'cluster_name'   => ['required|string', '集群名'],
            'namespace_name' => ['sometimes|string', 'namespace'],
        ];
        try {
            list($rules, $message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate(request()->getJson(), $rules, $message, TRUE, __METHOD__);
            $ret = ApolloModule::getConfig($data['env'], $data['app_id'], $data['cluster_name'], $data['namespace_name']);
            return $this->responseSuccess($ret, __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), null, __METHOD__);
        }
    }

    /**
     * @title: 同步Apollo配置
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return array
     */
    public function apolloSyncAction()
    {
        //参数定义
        $rulesMap = [
            'env'            => ['required|string', '环境代码'],
            'app_id'         => ['required|string', '应用id'],
            'cluster_name'   => ['required|string', '集群名'],
            'namespace_name' => ['sometimes|string', 'namespace'],
            'project_name'   => ['sometimes|string', '项目目录名称'],
        ];
        try {
            list($rules, $message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate(request()->getJson(), $rules, $message, TRUE, __METHOD__);
            $ret = ApolloModule::apolloSync($data['env'], $data['app_id'], $data['cluster_name'], $data['namespace_name'], $data['project_name']);
            return $this->responseSuccess(['message' => $ret ? '操作成功' : '操作失败'], __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), null, __METHOD__);
        }
    }
}