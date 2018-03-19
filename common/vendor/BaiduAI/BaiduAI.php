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
    #注册用户access_token
    protected $access_token;
    private $curlObj;
    static private $instance;

    private function __construct($access_token)
    {
        $this->access_token = $access_token;

        $curl = new \Curl\Curl();
        $curlopt_proxy = C('CURLOPT_PROXY');
        if ($curlopt_proxy) {
            $curl->setOpt(CURLOPT_PROXY, $curlopt_proxy);
        }
        $curl->setOpt(CURLOPT_TIMEOUT, 60);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $this->curlObj = $curl;
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    static public function getInstance($access_token)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($access_token);
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

    public function getAccessToken($client_id = '', $client_secret = '')
    {
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data = [
            'grant_type' => 'client_credentials',
            'client_id' => 'pRUd7wVakrh539PsZ255mCD3',
            'client_secret' => 'eheuqwG24nGxeY6EedAYVLuij6c9GAMr'
        ];

        $curl = $this->getCurlObj();
        $result = $curl->post($url, $post_data);
        $result = json_decode(json_encode($result), true);
        if (is_array($result)) {
            return $result;
        } else {
            return false;
        }
    }


    public function keyword($title, $content)
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/keyword?access_token=' . $this->access_token;
        $post_data = [
            'title' => $title,
            'content' => $content,
        ];
        $curl = $this->getCurlObj();
        $post_data = json_encode($post_data);
        $post_data = iconv('utf-8', 'gbk', $post_data);
        $curl->setHeader('Content-type', 'application/json');
        $responce = $curl->post($url, $post_data);
        if (is_object($responce)) {
            $responce = json_encode($responce);
        }
        $result = iconv('gbk', 'utf-8', $responce);
        $result = json_decode($result, true);
        print_g($result);
    }

    public function lexer($text)
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/lexer?access_token=' . $this->access_token;
        $post_data = [
            'text' => $text
        ];
        $curl = $this->getCurlObj();
        $post_data = json_encode($post_data);
        $post_data = iconv('utf-8', 'gbk', $post_data);
        $curl->setHeader('Content-type', 'application/json');
        $responce = $curl->post($url, $post_data);
        if (is_object($responce)) {
            $responce = json_encode($responce);
        }
//        print_g($responce);
        $result = iconv('gbk', 'utf-8', $responce);
        $result = json_decode($result, true);
        $keyword = [];
        foreach ($result['items'] as $item) {
            if (strpos($item['pos'], 'n') !== false and !empty(trim($item['item']))) {
                $keyword[] = $item['item'];
            }
        }
        $keyword = array_values(array_unique($keyword));
        return ($keyword);
    }

    public function topic($title, $content)
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/topic?access_token=' . $this->access_token;
        $post_data = [
            'title' => $title,
            'content' => $content,
        ];
        $curl = $this->getCurlObj();
        $post_data = json_encode($post_data);
        $post_data = iconv('utf-8', 'gbk', $post_data);
        $curl->setHeader('Content-type', 'application/json');
        $responce = $curl->post($url, $post_data);
        if (is_object($responce)) {
            $responce = json_encode($responce);
        }
        $result = iconv('gbk', 'utf-8', $responce);
        $result = json_decode($result, true);
        return $result;
    }


}