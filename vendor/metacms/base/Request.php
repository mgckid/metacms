<?php

/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2015/11/4 0004
 * Time: 下午 23:33
 */

namespace metacms\base;

class Request
{

    const URL_MODEL_DEFAULT = 0;
    const URL_MODEL_PATHINFO = 1;
    const URL_MODEL_PATHINFO_REWRITE = 2;
    const URL_MODEL_COMBO = 3;
    const URL_MODEL_COMBO_REWRITE = 4;
    protected $url_mode = 3;
    protected $default_module = 'home';
    protected $default_controller = 'Index';
    protected $default_action = 'index';
    protected $var_module = 'm';
    protected $var_controller = 'c';
    protected $var_action = 'a';
    protected $var_route = 'route';
    protected $main_domain = '';
    protected $sub_domain_open = true;
    protected $sub_domain_rule = [
        'www' => 'home'
    ];

    public function __construct($config = array())
    {
        #初始化配置
        $this->url_mode = isset($config['URL_MODE']) && is_int($config['URL_MODE']) ? $config['URL_MODE'] : $this->url_mode;
        $this->default_module = isset($config['DEFAULT_MODULE']) && !empty($config['DEFAULT_MODULE']) ? $config['DEFAULT_MODULE'] : $this->default_module;
        $this->default_controller = isset($config['DEFAULT_CONTROLLER']) && !empty($config['DEFAULT_CONTROLLER']) ? $config['DEFAULT_CONTROLLER'] : $this->default_controller;
        $this->default_action = isset($config['DEFAULT_ACTION']) && !empty($config['DEFAULT_ACTION']) ? $config['DEFAULT_ACTION'] : $this->default_action;
        $this->var_module = isset($config['VAR_MODULE']) && !empty($config['VAR_MODULE']) ? $config['VAR_MODULE'] : $this->var_module;
        $this->var_controller = isset($config['VAR_CONTROLLER']) && !empty($config['VAR_CONTROLLER']) ? $config['VAR_CONTROLLER'] : $this->var_controller;
        $this->var_action = isset($config['VAR_ACTION']) && !empty($config['VAR_ACTION']) ? $config['VAR_ACTION'] : $this->var_action;
        $this->var_route = isset($config['VAR_ROUTE']) && !empty($config['VAR_ROUTE']) ? $config['VAR_ROUTE'] : $this->var_route;
        $this->sub_domain_open = isset($config['SUB_DOMAIN_OPEN']) && is_bool($config['SUB_DOMAIN_OPEN']) ? $config['SUB_DOMAIN_OPEN'] : $this->sub_domain_open;
        $this->sub_domain_rule = isset($config['SUB_DOMAIN_RULES']) && !empty($config['SUB_DOMAIN_RULES']) ? $config['SUB_DOMAIN_RULES'] : $this->sub_domain_rule;
        $this->main_domain = isset($config['MAIN_DOMAIN']) && !empty($config['MAIN_DOMAIN']) ? $config['MAIN_DOMAIN'] : $this->getMainDomain();
    }

    protected function getMainDomain()
    {
        $main_domain = '';
        if ($this->sub_domain_open) {
            $http_host = $_SERVER['HTTP_HOST'];
            $_http_host = explode('.', $http_host);
            $main_domain = implode('.', array_reverse(array_slice(array_reverse($_http_host), 0, 2)));
            if (strpos($main_domain, ':') !== false) {
                $_main_domain = explode(':', $main_domain);
                $main_domain = $_main_domain[0];
            }
        }
        return $main_domain;
    }

    /**
     * 打包参数
     * @return $this
     */
    public function run()
    {
        $data = [];
        #url自动识别选择打包方式
        if (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
            $data = $this->dispatchByPathinfo($_SERVER['PATH_INFO']);
        } elseif (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            if (false !== strpos($_SERVER['QUERY_STRING'], $this->var_route . '=')) {
                $data = $this->dispatchByCompromise($_SERVER['QUERY_STRING']);
            } elseif (isset($_REQUEST[$this->var_module]) || isset($_REQUEST[$this->var_controller]) || isset($_REQUEST[$this->var_action])) {
                $data = $this->dispatchByDynamic();
            }
        }
        #指定打包方式
        if (empty($data)) {
            switch ($this->url_mode) {
                #动态模式
                case 0:
                    $data = $this->dispatchByDynamic();
                    break;
                #pathinfo 模式
                case 1:
                case 2:
                    $request = (isset($_SERVER['PATH_INFO']) and !empty($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : '';
                    $data = $this->dispatchByPathinfo($request);
                    break;
                #兼容模式
                case 3:
                case 4:
                    $request = (isset($_SERVER['QUERY_STRING']) and !empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
                    $data = $this->dispatchByCompromise($request);
                    break;
            }
        }
        if (empty($data['module'])) {
            if ($this->sub_domain_open) {
                $mainDomain = $this->main_domain;
                $subMain = strtolower(trim(str_replace($mainDomain, '', HTTP_HOST), '.'));
                $data['module'] = $this->sub_domain_rule[$subMain];
            } else {
                $data['module'] = lcfirst($this->default_module);
            }
        }
        $data['controller'] = empty($data['controller']) ? ucfirst($this->default_controller) : ucfirst($data['controller']);
        $data['action'] = empty($data['action']) ? lcfirst($this->default_action) : lcfirst($data['action']);
        return $data;
    }


    public function dispatchByPathinfo($request = '')
    {
        $data = array(
            'module' => '',
            'controller' => '',
            'action' => '',
        );
        $path = explode('/', trim($request, '/'));
        if (!$path) {
            return $data;
        }
        foreach ($data as $k => $v) {
            $data[$k] = array_shift($path);
        }
        $param = array();
        if ($path) {
            $key = array();
            $value = array();
            foreach ($path as $k => $v) {
                if ($k % 2 == 0) {
                    $key[] = $v;
                } else {
                    $value[] = $v;
                }
            }
            if (count($key) > count($value)) {
                for ($i = 0; $i <= count($key) - count($value); $i++) {
                    $value[] = '';
                }
            }
            $param = array_combine($key, $value);
        }
        $_GET = array_merge($_GET, $param);
        $_REQUEST = array_merge($_REQUEST, $param);
        return $data;
    }

    /**
     * 兼容模式打包url参数
     * @access public
     * @author furong
     * @param $request
     * @return array
     * @since
     * @abstract
     */
    public function dispatchByCompromise($request = '')
    {
        $data = array(
            'module' => '',
            'controller' => '',
            'action' => '',
        );
        $request = trim($request, '/,?');
        if (!empty($request)) {
            $_request = explode('&', $request);
            unset($request);
            foreach ($_request as $value) {
                if (empty($value)) {
                    continue;
                }
                $param = strpos($value, '=') !== false ? explode('=', $value) : [$value, null];
                $request[$param[0]] = $param[1];
            }
            $route = isset($request[$this->var_route]) && !empty($request[$this->var_route]) ? explode('/', trim($request[$this->var_route], '/')) : [];
            if (!empty($route)) {
                if (!$this->sub_domain_open) {
                    $data['module'] = current(array_splice($route, 0, 1));
                }
            }
            if (!empty($route)) {
                $controller = array_splice($route, 0, 1);
                $data['controller'] = count($controller) == 1 ? current($controller) : join('/', $controller);
            }
            if (!empty($route)) {
                $data['action'] = current(array_splice($route, 0, 1));
            }
            unset($request[$this->var_route]);
            array_merge($_REQUEST, $request);
            array_merge($_GET, $request);
        }
        return $data;
    }


    /**
     * 打包url参数by动态传参模式
     * @param type $request
     * @return type
     */
    public function dispatchByDynamic()
    {
        $data = array(
            'module' => '',
            'controller' => '',
            'action' => '',
        );
        if (isset($_REQUEST[$this->var_module]) && !empty($_REQUEST[$this->var_module])) {
            $data['module'] = $_REQUEST[$this->var_module];
            unset($_GET[$this->var_module]);
            unset($_REQUEST[$this->var_module]);
        }
        if (isset($_REQUEST[$this->var_controller]) && !empty($_REQUEST[$this->var_controller])) {
            $data['controller'] = $_REQUEST[$this->var_controller];
            unset($_GET[$this->var_controller]);
            unset($_REQUEST[$this->var_controller]);
        }
        if (isset($_REQUEST[$this->var_action]) && !empty($_REQUEST[$this->var_action])) {
            $data['action'] = $_REQUEST[$this->var_action];
            unset($_GET[$this->var_action]);
            unset($_REQUEST[$this->var_action]);
        }
        return $data;
    }


}
