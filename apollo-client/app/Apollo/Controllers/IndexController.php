<?php
namespace App\Apollo\Controllers;

use App\Apollo\Library\Enum\ExceptionCodeEnum;
use App\Apollo\Models\OrderDB\Order;
use App\Apollo\Modules\Queue\Kafka\KafkaResend;
use CjsRedis\Sequence;
use App\Util\ValidatorUtil;
use CjsRedis\Redis;
use App\Apollo\Library\Exceptions\ServiceException;

class IndexController extends Base {

    public function indexAction()
    {
        return $this->responseSuccess(['tips'=>'order web服务正常'], __METHOD__);
    }

    /**
     * @title: 清除redis缓存
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param array $param
     * @return array
     */
    public function cleanRedisAction($param = []){
        $rulesMap = [
            'group' => ['required|string', 'RedisGroup'],
            'key'   => ['required|string', 'RedisKey'],
        ];
        try{
            list($rules,$message) = ValidatorUtil::formatRule($rulesMap);
            $requestData = $this->validate($param,$rules,$message,TRUE, __METHOD__);
            $redisGroup = $requestData['group'];
            $redisKeyList = explode(',',$requestData['key']);
            foreach ($redisKeyList as $redisKey) {
                if(Redis::exists($redisGroup,$redisKey)){
                    continue;
                }
                Redis::del($redisGroup,$redisKey);
            }
            return $this->responseSuccess('操作成功', __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), null, __METHOD__);
        }


    }

    /**
     * @title: 发送kafka消息
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @param array $params
     * @return array
     */
    public function sendKafkaMessageAction($params = [])
    {
        $rulesMap = [
            'queue' => ['required|string', 'queue'],
            'type'  => ['required|string', 'type'],
            'data'  => ['required|array', 'data'],
            'id'    => ['sometimes|string', 'id'],
        ];
        try{
            list($rules, $message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate($params,$rules,$message,TRUE, __METHOD__);
            $id = empty($data['id']) ? NULL : $data['id'];
            $ret = (new KafkaResend())->send02($data['queue'], $data['type'], $data['data'], $id);
            if($ret){
                return $this->responseSuccess('操作成功', __METHOD__);
            }else{
                return $this->responseError(ExceptionCodeEnum::HANDEL_FAIL, '操作失败', null, __METHOD__);
            }
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), null, __METHOD__);
        }
    }
}