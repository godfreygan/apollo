<?php

namespace App\Apollo\Console\Commands\Apollo;

use App\Apollo\Console\Command;
use App\Apollo\Library\Enum\RedisExpireEnum;
use App\Apollo\Library\Enum\RedisGroupEnum;
use App\Apollo\Library\Enum\RedisKeyEnum;
use CjsRedis\RedisLock;
use Log;

class Test extends Command
{
    /**
     * The console command name.
     * php artisan apollo:test
     * @var string
     */
    protected $name = 'apollo:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

    /**
     * @title: Execute the console command.
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return bool
     */
    public function handle()
    {
        $this->comment( $this->name ." START" );
        $redisKey  = sprintf(RedisKeyEnum::JOB_CONTROL_KEY,$this->name);
        $i = RedisLock::lock(RedisGroupEnum::COMMON, $redisKey .":redislock", RedisExpireEnum::EXPIRE_MINUTE_TEN);   // 10min 锁
        if(!$i) {       //加锁失败，请勿重复提交，不能处理后续动作
            Log::info(__METHOD__ . ' 加锁失败，阻止多次执行');
            $this->comment( 'existed job');
            return false;
        }

        // todo
        $this->comment( $this->name ." job handle...." );

        RedisLock::unlock(RedisGroupEnum::COMMON,$redisKey .":redislock");       // 释放锁
        $this->comment($this->name ." END" );
    }
}