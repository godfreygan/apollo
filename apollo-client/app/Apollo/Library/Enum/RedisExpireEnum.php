<?php
/**
 * redis 有效时间配置 单位是秒
 * User: Liu Zan
 * Date: 2019/3/7
 * Time: 10:01
 */
namespace App\Apollo\Library\Enum;

class RedisExpireEnum
{
    const EXPIRE_SECOND_ONE    = 1; //1秒
    const EXPIRE_SECOND_TEN    = 10;//10秒
    const EXPIRE_SECOND_THIRTY = 30;//30秒

    const EXPIRE_MINUTE_ONE    = 60;  //1分钟
    const EXPIRE_MINUTE_TEN    = 600; //10分钟
    const EXPIRE_MINUTE_THIRTY = 1800;//30分钟

    const EXPIRE_HOUR_ONE  = 3600; //1小时
    const EXPTRE_HOUR_FOUR = 14400;//4个小时
    const EXPIRE_HOUR_TEN  = 36000;//10小时


    const EXPIRE_DAY_ONE     = 86400;  //1天
    const EXPIRE_DAY_THREE   = 259200; //3天
    const EXPIRE_DAY_FIFTEEN = 1296000;//15天
}