<?php
/**
 * Created by PhpStorm.
 * User: furong
 * Date: 2017/8/15
 * Time: 17:57
 */

namespace metacms\api;

use metacms\base\Application;

class Controller
{
    const
        S100_CONTINUE = 100,
        S101_SWITCHING_PROTOCOLS = 101,
        S102_PROCESSING = 102,
        S200_OK = 200,
        S201_CREATED = 201,
        S202_ACCEPTED = 202,
        S203_NON_AUTHORITATIVE_INFORMATION = 203,
        S204_NO_CONTENT = 204,
        S205_RESET_CONTENT = 205,
        S206_PARTIAL_CONTENT = 206,
        S207_MULTI_STATUS = 207,
        S208_ALREADY_REPORTED = 208,
        S226_IM_USED = 226,
        S300_MULTIPLE_CHOICES = 300,
        S301_MOVED_PERMANENTLY = 301,
        S302_FOUND = 302,
        S303_SEE_OTHER = 303,
        S303_POST_GET = 303,
        S304_NOT_MODIFIED = 304,
        S305_USE_PROXY = 305,
        S307_TEMPORARY_REDIRECT = 307,
        S308_PERMANENT_REDIRECT = 308,
        S400_BAD_REQUEST = 400,
        S401_UNAUTHORIZED = 401,
        S402_PAYMENT_REQUIRED = 402,
        S403_FORBIDDEN = 403,
        S404_NOT_FOUND = 404,
        S405_METHOD_NOT_ALLOWED = 405,
        S406_NOT_ACCEPTABLE = 406,
        S407_PROXY_AUTHENTICATION_REQUIRED = 407,
        S408_REQUEST_TIMEOUT = 408,
        S409_CONFLICT = 409,
        S410_GONE = 410,
        S411_LENGTH_REQUIRED = 411,
        S412_PRECONDITION_FAILED = 412,
        S413_REQUEST_ENTITY_TOO_LARGE = 413,
        S414_REQUEST_URI_TOO_LONG = 414,
        S415_UNSUPPORTED_MEDIA_TYPE = 415,
        S416_REQUESTED_RANGE_NOT_SATISFIABLE = 416,
        S417_EXPECTATION_FAILED = 417,
        S421_MISDIRECTED_REQUEST = 421,
        S422_UNPROCESSABLE_ENTITY = 422,
        S423_LOCKED = 423,
        S424_FAILED_DEPENDENCY = 424,
        S426_UPGRADE_REQUIRED = 426,
        S428_PRECONDITION_REQUIRED = 428,
        S429_TOO_MANY_REQUESTS = 429,
        S431_REQUEST_HEADER_FIELDS_TOO_LARGE = 431,
        S451_UNAVAILABLE_FOR_LEGAL_REASONS = 451,
        S500_INTERNAL_SERVER_ERROR = 500,
        S501_NOT_IMPLEMENTED = 501,
        S502_BAD_GATEWAY = 502,
        S503_SERVICE_UNAVAILABLE = 503,
        S504_GATEWAY_TIMEOUT = 504,
        S505_HTTP_VERSION_NOT_SUPPORTED = 505,
        S506_VARIANT_ALSO_NEGOTIATES = 506,
        S507_INSUFFICIENT_STORAGE = 507,
        S508_LOOP_DETECTED = 508,
        S510_NOT_EXTENDED = 510,
        S511_NETWORK_AUTHENTICATION_REQUIRED = 511;

    protected $http_status_codes = [
        self::S200_OK => 'OK',
        self::S201_CREATED => 'CREATED',
        self::S204_NO_CONTENT => 'NO CONTENT',
        self::S304_NOT_MODIFIED => 'NOT MODIFIED',
        self::S400_BAD_REQUEST => 'BAD REQUEST',
        self::S401_UNAUTHORIZED => 'UNAUTHORIZED',
        self::S403_FORBIDDEN => 'FORBIDDEN',
        self::S404_NOT_FOUND => 'NOT FOUND',
        self::S405_METHOD_NOT_ALLOWED => 'METHOD NOT ALLOWED',
        self::S406_NOT_ACCEPTABLE => 'NOT ACCEPTABLE',
        self::S409_CONFLICT => 'CONFLICT',
        self::S500_INTERNAL_SERVER_ERROR => 'INTERNAL SERVER ERROR',
        self::S501_NOT_IMPLEMENTED => 'NOT IMPLEMENTED'
    ];

    public function __construct()
    {

    }


    public function response($data = null, $http_code = null, $messaage = null, $cached = false)
    {
        header("Content-type: application/json;charset=utf-8");
        ob_start();
        // If the HTTP status is not null, then cast as an integer
        if (!empty($http_code)) {
            // So as to be safe later on in the process
            $http_code = (int)$http_code;
        }
        // Set the output as null by default
        $output = null;
        // If data is null and no HTTP status code provided, then display, error and exit
        if (empty($data) && empty($http_code)) {
            $http_code = self::S404_NOT_FOUND;
        }
        if (!empty($http_code) && empty($messaage)) {
            $messaage = $this->http_status_codes[$http_code];
        }
        $output = [
            'code' => $http_code,
            'message' => $messaage,
            'data' => $data,
            'cached' => $cached ? 1 : 0,
        ];
        $return = json_encode($output, JSON_UNESCAPED_UNICODE);
        echo $return;
        exit();
    }

    public function errorResponse($messaage = null, $http_code = self::S400_BAD_REQUEST)
    {
        $this->response(null, $http_code, $messaage);
    }

    /**
     * 设置消息
     * @param type $url
     */
    public function setMessage($msg)
    {
        return Application::setMessage($msg);
    }

    /**
     * 获取消息
     * @param
     */
    public function getMessage()
    {
        return Application::getMessage();
    }

    /**
     * 获取数据
     * @param
     */
    public function getInfo($key)
    {
        return Application::getInfo($key);
    }

    /**
     *  设置数据
     * @param type $key
     * @param type $value
     */
    public function setInfo($key, $value)
    {
        Application::setInfo($key, $value);
    }




} 