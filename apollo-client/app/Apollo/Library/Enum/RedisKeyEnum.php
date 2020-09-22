<?php namespace App\Apollo\Library\Enum;

/**
 * redis key前缀常量
 * @package App\User\Library\Enum
 * @date  2017-09-12
 */
class RedisKeyEnum extends Enum
{
    const JOB_CONTROL_KEY = 'job:%s';                                // 脚本控制

}
