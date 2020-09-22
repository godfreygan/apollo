<?php namespace App\Util;

/**
 * AES加密解密算法
 *
 */
class Aes
{
    /**
     * 算法,另外还有192和256两种长度
     */
    const CIPHER = MCRYPT_RIJNDAEL_128;

    /**
     * 模式
     */
    const MODE = MCRYPT_MODE_ECB;

    /**
     * AES加密
     *
     * @author chengjinsheng
     * @date 2016-10-11
     * @param string $key 密钥
     * @param string $str 需加密的字符串
     * @return string
     */
    public static function encode($key, $str)
    {
        $size = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $string = mcrypt_encrypt(self::CIPHER, $key, $str, self::MODE, $iv);
        $string = base64_encode($string);
        return $string;
    }

    /**
     * AES解密
     *
     * @author chengjinsheng
     * @date 2016-10-11
     * @param string $key 密钥
     * @param string $str 需解密的字符串
     * @return string
     */
    public static function decode($key, $str)
    {
        $size = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $string = base64_decode($str);
        $string = mcrypt_decrypt(self::CIPHER, $key, $string, self::MODE, $iv);

        /**
         * 解决下边问题
         * the given cipher and mode. If the size of the data is not n * blocksize,
         * the data will be padded with '\0'.
         */
        $string = trim($string);

        return $string;
    }
}
