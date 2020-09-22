<?php
namespace App\Util;

class Unique
{
    /**
     * 根据干扰值，产生唯一值
     *
     * @author chengjinsheng
     * @date 2017-02-18
     *
     * 返回md5之后的32个字符
     * @param string $annoyance
     * @return string
     */
    public static function get($annoyance)
    {
        $str = $annoyance . microtime(true) . self::randStr();
        return md5($str);
    }

    /**
     * 随机产生字符串
     *
     * @author chengjinsheng
     * @date 2017-02-18
     *       
     * @param int $length
     *
     * @return string
     */
    public static function randStr($length=6) {

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    
    }
    
}

