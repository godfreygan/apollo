<?php
namespace App\Util;


class EncryptNew
{

    /**
     * 加密密码
     *
     * @author chengjinsheng
     * @date 2017-09-27
     * @param string $sPassword 待加密密码
     * @param string $sSalt 加密盐值
     * @return string 加密后结果
     */
    public static function passwordHash($sPassword, $sSalt)
    {
        if (empty($sPassword) || empty($sSalt)) {
            return '';
        }
        $sHash = md5("win_hds_an_ds_" . $sPassword);
        $sHash = md5("win-hds-an-ds-" . $sHash . $sSalt);
        return $sHash;
    }

    /**
     * 验证密码
     *
     * @author chengjinsheng
     * @date 2017-09-27
     * @param string $sPassword 待验证的密码
     * @param string $sHash 加密后的哈希值
     * @param string $sSalt 加密盐值，已经在db中存好的sSalt
     * @return bool
     */
    public static function passwordVerify($sPassword, $sHash, $sSalt='')
    {
        $sPassword = self::passwordHash($sPassword, $sSalt);
        if ($sPassword == $sHash) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成加密盐值
     *
     * @author chengjinsheng
     * @date 2017-09-27
     * @return string 盐值(4-32位)
     */
    public static function generateSalt()
    {
        $length = mt_rand(4, 32);
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $salt = "";
        for($i = 0; $i < $length; $i++)
        {
            $salt .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $salt;
    }

}