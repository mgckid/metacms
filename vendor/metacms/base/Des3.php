<?php
/**
 * 基于openssl的加密解密方法
 * Created by PhpStorm.
 * User: furong
 * Date: 2018/1/5
 * Time: 14:36
 */

namespace metacms\base;

class Des3
{
    #openssl des3加密方法
    const DES_EDE3_CBC = 'DES-EDE3-CBC';
    #openssl aes 128位加密方法
    const AES_128_CBC = 'AES-128-CBC';

    #加密密钥
    protected $key;
    #加密向量
    protected $iv = '01234567012345678';

    function __construct($key, $iv = '')
    {
        if (!$key) {
            throw new \Exception('请设置加密key');
            return false;
        }
        $this->key = $key;
        if ($iv) {
            $this->iv = $iv;
        }
    }

    /**
     * 加密
     * @access public
     * @author furong
     * @param string $data 明文数据
     * @param string $method 加密方法
     * @return string
     * @since 2018年1月5日 15:38:49
     * @abstract
     * @throws \Exception
     */
    public function encrypt(string $data, string $method = self::DES_EDE3_CBC)
    {
        $iv = $this->getIv($method);
        $result = openssl_encrypt($data, $method, $this->key, 0, $iv);
        return $result;
    }

    /**
     * 解密
     * @access public
     * @author furong
     * @param string $data 密文
     * @param string $method 加密方法
     * @return string
     * @since 2018年1月5日 15:38:53
     * @abstract
     * @throws \Exception
     */
    public function decrypt(string $data, string $method = self::DES_EDE3_CBC)
    {
        $iv = $this->getIv($method);
        $result = openssl_decrypt($data, $method, $this->key, 0, $iv);
        return $result;
    }

    /**
     * 获取安全向量
     * @access protected
     * @author furong
     * @param $method
     * @return string
     * @since 2018年1月5日 15:01:24
     * @abstract
     * @throws \Exception
     */
    protected function getIv($method)
    {
        $iv = '';
        switch ($method) {
            case 'DES-EDE3-CBC':
                $iv = str_pad(mb_substr($this->iv, 0, 8), 8, '0');
                break;
            case 'AES-128-CBC':
                $iv = str_pad(mb_substr($this->iv, 0, 16), 16, '0');
                break;
            default:
                throw new \Exception('openssl加密方法不存在');
        }
        return $iv;
    }

} 