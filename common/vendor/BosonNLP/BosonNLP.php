<?php

/**
 * 玻森中文语义开放平台语义分析API
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/8
 * Time: 9:34
 */
namespace BosonNLP;
class BosonNLP
{
    #玻森 注册用户token
    protected $token;
    #情感分析api地址
    const SENTIMENT_URL = 'http://api.bosonnlp.com/sentiment/analysis';
    #关键词提取api地址
    const KEYWORDS_URL = 'http://api.bosonnlp.com/keywords/analysis';
    #新闻摘要api地址
    const SUMMARY_URL = 'http://api.bosonnlp.com/summary/analysis';
    #命名实体识别地址
    const NER_URL = 'http://api.bosonnlp.com/ner/analysis';

    #情感分析 操作
    const ACTION_SENTIMENT = 'sentiment';
    #关键词提取 操作
    const ACTION_KEYWORDS = 'keywords';
    #新闻摘要 操作
    const ACTION_SUMMARY = 'summary';
    #命名实体识别 操作
    const ACTION_NER = 'ner';

    function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * 调用api主方法
     * @param $action 操作
     * @param $data 待分析数据
     * @param array $parm api参数
     * @return mixed
     */
    public function analysis($action, $data, array $parm = [])
    {
        switch ($action) {
            case self::ACTION_KEYWORDS:
                $url = self::KEYWORDS_URL;
                break;
            case self::ACTION_SENTIMENT:
                $url = self::SENTIMENT_URL;
                break;
            case self::ACTION_SUMMARY:
                $url = self::SUMMARY_URL;
                break;
            case self::ACTION_NER:
                $url = self::NER_URL;
                break;
        }

        if (!empty($parm)) {
            $url .= '?' . http_build_query($parm);
        }
        if (!is_array($data)) {
            $data = [$data];
        }
        return $this->doAnalysis($url, $data);
    }

    protected function doAnalysis($url, $data)
    {
        $curlOpt = array(
//            CURLOPT_PROXY => '127.0.0.1',
//            CURLOPT_PROXYPORT => '7777',
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array(
                "Accept:application/json",
                "Content-Type: application/json",
                "X-Token: {$this->token}",
            ),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
            CURLOPT_RETURNTRANSFER => true,
        );
        $ch = curl_init();
        curl_setopt_array($ch, $curlOpt);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result) {
            return json_decode($result, true);
        } else {
            return false;
        }
    }
}