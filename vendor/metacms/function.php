<?php
/**
 * 获取客户端ip
 * @return string
 */
function getClientIp()
{
    if ($_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if (getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;
}

/**
 * 打印数组 调试用
 * @param type $var
 */
function print_g($var)
{
    send_http_status(201);
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    exit();
}

/**
 * 递归创建多级目录
 * @param type $dirPath
 * @param type $mode
 * @return boolean
 */
function mkdirs($dirPath, $mode = 0777)
{
    if (!is_dir($dirPath)) {
        if (!mkdirs(dirname($dirPath))) {
            return FALSE;
        }
        if (!mkdir($dirPath, $mode)) {
            return FALSE;
        }
    }
    return TRUE;
}

/**
 * 创建文件
 * @param type $filePath 文件路径
 * @param type $content 文件内容
 * @return boolean
 */
function mkFile($filePath, $content)
{
    if (is_file($filePath))
        return true;
    mkdirs(dirname($filePath));
    $handle = fopen($filePath, 'w');
    if (!$handle)
        return FALSE;
    if (!fwrite($handle, $content))
        return false;
    fclose($handle);
    return true;
}

/**
 * 框架内置打印调试信息函数
 * @param type $var
 */
function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else
        return $output;
}


/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suff = true)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    if (mb_strlen($str, 'utf8') > $length && $suff) {
        return $slice . '...';
    } else {
        return $slice;
    }
}

/**
 * curl get获取数据
 * @param $url 请求链接
 * @param array $data 表单数据
 * @return mixed
 */
function requestGet($url, array $data = array())
{
    $header = array(
        'token:' . md5(time()),
        'username:furong'
    );
    if (is_array($data) && !empty($data)) {
        $url .= http_build_query($data, '', '&');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    {
        curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1');
        curl_setopt($ch, CURLOPT_PROXYPORT, '7777');
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function requestData($url, $data = array(), $isPost = false)
{
    if (!$isPost) {
        return requestGet($url, $data);
    }

}

//对http协议的状态设定，跳转页面中需要经常使用的函数
function send_http_status($code)
{
    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    if (array_key_exists($code, $_status)) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
    }
}

function errorPage($errno, $error_message, $errfile, $errline, $errtrace)
{
    $errorPage = C('404_PAGE');
    if (ENVIRONMENT == 'develop' or empty($errorPage)) {
        $engine = new \League\Plates\Engine();
        $engine->setDirectory(FRAMEWORK_PATH . '/templates/');
        $engine->setFileExtension('tpl');
        $engine->addData([
            'e' => [
                'code' => $errno,
                'message' => $error_message,
                'file' => $errfile,
                'line' => $errline,
                'trace' => $errtrace,
            ],
        ]);
        if ($errno < 400 || $errno >= 500 || $errno == 200) {
            $errno = 500;
        }
        send_http_status($errno);
        die ($engine->render('think_exception'));
    } else {
        $log_path = APP_PATH . '/log/' . date('Y-m-d', time()) . '/php_error.txt';
        $log_data = [
            'errno' => $errno,
            'error_message' => $error_message,
            'errfile' => $errfile,
            'errline' => $errline,
        ];
        $log_data = json_encode($log_data);
        mkFile($log_path, '');
        $r = error_log($log_data, 3, $log_path);
        header('location:' . C('404_PAGE'));
    }
}

function errorHandle($errno, $error_message, $errfile, $errline)
{
    ob_start();
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $errtrace = ob_get_clean();
    errorPage($errno, $error_message, $errfile, $errline, $errtrace);
}

/**
 * 设置失败信息
 * @param type $msg
 * @return boolean
 */
function fail($msg = '执行失败')
{
    \metacms\base\Application::setMessage($msg);
    return FALSE;
}

/**
 * 设置成功信息
 * @param type $msg
 * @return boolean
 */
function success($msg = '执行成功')
{
    \metacms\base\Application::setMessage($msg);
    return TRUE;
}

/* * ********************别名函数 开始********************************* */

/**
 * 框架getConfig方法 别名函数
 * @param type $configName
 * @return type
 */
function C($configName = '')
{
    if ($configName) {
        return \metacms\base\Application::config()->get($configName);
    } else {
        return \metacms\base\Application::config()->all();
    }
}

/**
 * 组装url
 * @param $url
 * @param array $parm
 * @return string
 */
function U($url, $parm = array())
{
    $url = trim($url, '/');
    $root = '/';
    switch (C('URL_MODE')) {
        case 0:
            $pattern_arr = explode('/', $url);
            $count = count($pattern_arr);
            $key_arr = array(C('VAR_CONTROLLER'), C('VAR_ACTION'));
            $_path = array_combine(array_slice($key_arr, 0, $count), $pattern_arr);
            $_path = array_merge($_path, $parm);
            $path = '?' . http_build_query($_path);
            break;
        case 1:

            break;
        case 2:
            $route = [C('VAR_ROUTE') => $url];
            $_path = array_merge($route, $parm);
            $path = '?' . http_build_query($_path);
            break;
    }
    return urldecode($root . $path);
}

function W($method, array $parm = array())
{
    $_method = explode('/', $method);
    $className = array_shift($_method);
    $methodName = array_shift($_method);
    if (!$className || !$methodName) {
        return '';
    }
    $className = 'app\\widget\\' . ucfirst($className) . 'Widget';
    if (!class_exists($className) || !method_exists($className, $methodName)) {
        return '';
    }
    return call_user_func_array(array(new $className, $methodName), $parm);
}

/* * ********************别名函数 结束********************************* */

/**
 * 创建运行时目录
 * @access public
 * @author furong
 * @return bool
 * @since
 * @abstract
 */
function create_runtime_dir()
{
    $runtime_path = APP_PATH . '/runtime/';
    if (!is_dir($runtime_path)) {
        mkdir($runtime_path);
    }
    return true;
}

