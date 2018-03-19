<?php

/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2018/3/19
 * Time: 15:39
 */
namespace BaiduAI;

use Curl\Curl;

class BaiduAI
{
    #注册用户api_key
    protected $api_key;
    private $curlObj;
    static private $instance;

    private function __construct($api_key)
    {
        $this->api_key = $api_key;
        $this->curlObj = new Curl();
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    static public function getInstance($api_key)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($api_key);
        }
        return self::$instance;
    }

    /**
     * @access public
     * @author furong
     * @return Curl
     * @since 2018年3月19日 16:14:00
     * @abstract
     */
    private function getCurlObj()
    {
        return $this->curlObj;
    }

    public function getAccessToken($client_id, $client_secret)
    {
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data = [
            'grant_type' => 'client_credentials',
            'client_id' => 'pRUd7wVakrh539PsZ255mCD3',
            'client_secret' => 'eheuqwG24nGxeY6EedAYVLuij6c9GAMr'
        ];

        $result = file_get_contents($url . '?' . http_build_query($post_data));
        $result = json_decode($result, true);
        if (is_array($result)) {
            return $result;
        } else {
            return false;
        }
    }


    public function keyword($title, $content)
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/?access_token=' . $this->api_key;
        $data = [
            'title' => $title,
            'content' => $content,
        ];
        $curl = $this->getCurlObj();
        $curl->setHeader('Content-type', 'application/json');
        $result = $curl->post($url, $data);
        var_dump($result);
    }


}