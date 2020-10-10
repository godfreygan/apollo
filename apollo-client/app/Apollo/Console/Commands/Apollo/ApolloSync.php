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

class ApolloSync extends Command
{
    /**
     * The console command name.
     * php artisan apollo:apollo_sync --env=dev --app_id=order-service --cluster_name=ganqixin --namespace_name=application --project_name=order-service
     * @var string
     */
    protected $name = 'apollo:apollo_sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步Apollo配置';

    protected function getOptions()
    {
        return [
            ['env', null, InputOption::VALUE_REQUIRED, '环境代码', ''],
            ['app_id', null, InputOption::VALUE_REQUIRED, '应用id', ''],
            ['cluster_name', null, InputOption::VALUE_REQUIRED, '集群名', ''],
            ['namespace_name', null, InputOption::VALUE_OPTIONAL, 'namespace', 'application'],
            ['project_name', null, InputOption::VALUE_OPTIONAL, '项目目录名称', ''],      // 可选，order-service 或 order-service/master
        ];
    }

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

        $env           = $this->option('env');
        $appId         = $this->option('app_id');
        $clusterName   = $this->option('cluster_name');
        $namespaceName = empty($this->option('namespace_name')) ? 'application' : $this->option('namespace_name');
        $projectName   = empty($this->option('project_name'));

        try{
            $ret = Apollo::apolloSync($env, $appId, $clusterName, $namespaceName, $projectName);
            if(! $ret){
                // todo 失败处理
            }
        } catch (ServiceException $e) {
            Log::Error(__METHOD__ . ' 同步失败', [$e->getCode(), $e->getMessage()]);
        }

        RedisLock::unlock(RedisGroupEnum::COMMON,$redisKey .":redislock");       // 释放锁
        $this->comment($this->name ." END" );
    }
}