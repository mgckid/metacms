<?php

/**
 * https://www.php.cn/php-weizijiaocheng-393305.html
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2019/7/25
 * Time: 0:46
 */

class Curlhelper {
    private static $instance = null;
    private $options = [];

    private function __construct() {
    }

    private function __clone() {
        // TODO: Implement __clone() method.
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function setopt(array $options) {
        $this->options = (array)$this->options;
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
        return true;
    }

    /**
     * post发送
     * @param $request_url
     * @param $form_data
     * @return mixed
     */
    public function post($request_url, $form_data, array $header_arr = []) {
        $form_data = is_scalar($form_data) ? $form_data : http_build_query($form_data);
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
//        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
//        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($ch, CURLOPT_URL, $request_url);
        if (empty($form_data)) {//（老版本curl兼容） post数据为空时 使用 CURLOPT_CUSTOMREQUEST 代替 CURLOPT_POST，否则会报 HTTP 400 Bad Request.
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        } else {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $form_data);//在没有需要上传文件的情况下，尽量对post提交的数据进行http_build_query，然后发送出去，能实现更好的兼容性，更小的请求数据包。
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//true 获取的信息以文件流的形式返回,可以赋给变量，false 直接输出到页面
        curl_setopt($ch, CURLOPT_HEADER, 0);//显示返回的Header区域内容
        if ($this->options) {
            curl_setopt_array($ch, $this->options);
        }
        if ($header_arr) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header_arr);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     *  post发送json数据
     * @param $request_url
     * @param array|string $form_data
     * @return mixed
     */
    public function post_json($request_url, $form_data) {
        $json_data = is_scalar($form_data) ? $form_data : json_encode($form_data, JSON_UNESCAPED_UNICODE);
        return $this->post($request_url, $form_data, ['Content-Type: application/json; charset=utf-8',]);
    }

    /**
     * curl get获取
     */
    public function get($request_url, $form_data) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form_data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//true 获取的信息以文件流的形式返回,可以赋给变量，false 直接输出到页面
        if ($this->options) {
            curl_setopt_array($ch, $this->options);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * curl 下载文件
     * @param $request_url
     * @param $save_path
     * @return bool
     */
    public function download($request_url, $save_path) {
        if (empty($request_url)) {
            return false;
        }
        if (!is_dir($save_path)) {
            mkdir($save_path, 777, true);
        }

        $tmp_file = trim($save_path, '/\\') . DIRECTORY_SEPARATOR . time();
        $tmp_header = $tmp_file . '_header';

        $fh = fopen($tmp_file, 'w+');
        $fh_header = fopen($tmp_header, 'w+');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_setopt($ch, CURLOPT_WRITEHEADER, $fh_header);
        //这个选项是意思是跳转，如果你访问的页面跳转到另一个页面，也会模拟访问。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        if ($this->options) {
            curl_setopt_array($ch, $this->options);
        }

        curl_exec($ch);
        fflush($fh);
        fclose($fh);

        if (curl_errno($ch)) {
            throw new \mysql_xdevapi\Exception(curl_error($ch));
        }
        curl_close($ch);

        rewind($fh_header);
        $headers = stream_get_contents($fh_header);
        if (preg_match('/Content-Disposition: .*filename=([^ ]+)/', $headers, $matches)) {
            $real_file = trim($save_path, '/\\') . DIRECTORY_SEPARATOR . trim($matches[1]);
            copy($tmp_file, $real_file);
            unlink($tmp_file);
            unlink($tmp_header);
            fclose($fh_header);
        }
        return $real_file ?? '';
    }

    /**
     * 上传
     * @param $request_url
     * @param array $form_data
     * @return mixed
     */
    public function upload($request_url, array $form_data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $form_data);//在没有需要上传文件的情况下，尽量对post提交的数据进行http_build_query，然后发送出去，能实现更好的兼容性，更小的请求数据包。
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//true 获取的信息以文件流的形式返回,可以赋给变量，false 直接输出到页面
        if ($this->options) {
            curl_setopt_array($ch, $this->options);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * curl模拟异步请求
     * @param string $url
     * @param int $time_out
     * @param string $callback
     * @return bool|string
     */
    public function async($url, $time_out = 1, $callback = '') {
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => $time_out,
        );
        curl_setopt_array($ch, $curlConfig);
        if ($this->options) {
            curl_setopt_array($ch, $this->options);
        }
        $result = curl_exec($ch);
        if (!empty($callback)) call_user_func($callback, $ch, $result, $url);
        curl_close($ch);
        return $result;
    }
}