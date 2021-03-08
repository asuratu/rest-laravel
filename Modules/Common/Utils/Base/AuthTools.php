<?php

namespace Modules\Common\Utils\Base;

//use phpseclib3\Crypt\AES;
//use phpseclib3\Crypt\RSA;

use phpseclib3\Crypt\RSA;

class AuthTools
{
    /** 生成rsa密钥对
     * @return array privatekey,publickey
     */
    public static function rsaCreateKey()
    {
        $rsa = new RSA();
        $key = $rsa->createKey();
        return $key;
    }

    /** sa加密
     * @param $string
     * @param $key
     * @return string
     */
    public static function rsaEncrypt($string, $key)
    {
        $rsa = new RSA();
        $rsa->loadKey($key);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_NONE);    //选择加密的模式
        return base64_encode($rsa->encrypt($string));    //需要对结果进行base64转码
    }

    /** rsa解密
     * @param $encryptStr
     * @param $key
     * @return mixed
     */
    public static function rsaDecrypt($encryptStr, $key)
    {
        $rsa = new RSA();
        $rsa->loadKey($key);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_NONE);
        return $rsa->decrypt(base64_decode($encryptStr));
    }

    /** 随机字符串作为aes key
     * @param int $len
     * @return string
     */
    public static function aesCreateKey($len = 16)
    {
        return \phpseclib\Crypt\Random::string($len);
    }

    /** aes加密
     * @param $string
     * @param $aesKey
     * @return string
     */
    public static function aesEncrypt($string, $aesKey)
    {
        $aes = new AES();
        $aes->setKey($aesKey);
        return base64_encode($aes->encrypt($string));
    }

    /** aes解密
     * @param $encryptStr
     * @param $aesKey
     * @return string
     */
    public static function aesDecrypt($encryptStr, $aesKey)
    {
        $aes = new AES();
        $aes->setKey($aesKey);
        return $aes->decrypt(base64_decode($encryptStr));
    }

}
