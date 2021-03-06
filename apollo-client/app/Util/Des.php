<?php namespace App\Util;

/**
 * Des算法相关
 *
 * @author  chengjinsheng
 * @date 2016-10-11
 * Class Des
 * @package App\Util
 */
class Des
{

    protected $key;
    protected $iv;

    // 偏移量
    public function __construct($key, $iv = 0)
    {
        $this->key = $key;
        if ($iv == 0) {
            $this->iv = $key;
        } else {
            $this->iv = $iv;
        }
    }

    // 加密
    public function encrypt($str)
    {
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = $this->pkcs5Pad($str, $size);
        $data = mcrypt_encrypt(MCRYPT_DES, $this->key, $str, MCRYPT_MODE_CBC, $this->iv);
        // $data=strtoupper(bin2hex($data));
        // 返回大写十六进制字符串
        return base64_encode($data);
    }

    // 解密
    public function decrypt($str)
    {
        $str = base64_decode($str);
        // $strBin = $this->hex2bin( strtolower($str));
        $str = mcrypt_decrypt(MCRYPT_DES, $this->key, $str, MCRYPT_MODE_CBC, $this->iv);
        $str = $this->pkcs5Unpad($str);
        return $str;
    }

    protected function hex2bin($hexData)
    {
        $binData = "";
        for ($i = 0; $i < strlen($hexData); $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }

    protected function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    protected function pkcs5Unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }


}