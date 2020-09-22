<?php

namespace App\Apollo\Console\Commands\Apollo;

use App\Apollo\Console\Command;
use App\Apollo\Library\Enum\RedisExpireEnum;
use App\Apollo\Library\Enum\RedisGroupEnum;
use App\Apollo\Library\Enum\RedisKeyEnum;
use App\Apollo\Library\Exceptions\ServiceException;
use App\Apollo\Modules\Apollo;
use CjsConsole\Input\InputOption;
use CjsRedis\RedisLock;
use Log;

class ApolloSyncAll extends Command
{
    /**
     * The console command name.
     * php artisan apollo:apollo_sync_all
     * @var string
     */
    protected $name = 'apollo:apollo_sync_all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步所有Apollo配置';

    /**
     * @title: Execute the console command.
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return bool
     */
    public function handle()
    {
        $this->comment( $this->name ." START" );
        Log::info($this->name .' START', []);
        $redisKey  = sprintf(RedisKeyEnum::JOB_CONTROL_KEY, $this->name);
        $i = RedisLock::lock(RedisGroupEnum::COMMON, $redisKey .":redislock", RedisExpireEnum::EXPIRE_HOUR_ONE);   // 10min 锁
        if(!$i) {       //加锁失败，请勿重复提交，不能处理后续动作
            Log::info(__METHOD__ . ' 加锁失败，阻止多次执行');
            $this->comment( 'existed job');
            return false;
        }

        try{
            $appList = Apollo::getApps();       // 获取app列表
            if(! empty($appList)){
                foreach($appList as $appInfo){
                    if(isset($appInfo['appId']) && ! empty($appInfo['appId'])){
                        $envClusters = Apollo::getEnvByAppId($appInfo['appId']);    // app数据列表
                        if(! empty($envClusters)){
                            foreach($envClusters as $envCluster){
                                if(! isset($envCluster['env']) || empty($envCluster['env'])){
                                    Log::Error(__METHOD__ . ' 数据不全', [$envCluster]);
                                    continue;
                                }
                                if(isset($envCluster['clusters']) && empty($envCluster['clusters'])){
                                    Log::Error(__METHOD__ . ' 数据不全', [$envCluster]);
                                    continue;
                                }

                                foreach($envCluster['clusters'] as $cluster){
                                    $ret = Apollo::apolloSync($envCluster['env'], $appInfo['appId'], $cluster);
                                    if(! $ret){
                                        Log::Error(__METHOD__ . ' 配置同步失败', [$envCluster['env'], $appInfo['appId'], $cluster]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (ServiceException $e) {
            Log::Error(__METHOD__ . ' 同步失败', [$e->getCode(), $e->getMessage()]);
        }

        RedisLock::unlock(RedisGroupEnum::COMMON,$redisKey .":redislock");       // 释放锁
        $this->comment($this->name ." END" );
    }
}