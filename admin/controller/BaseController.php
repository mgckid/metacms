<?php

/**
 * Description of BaseController
 *
 * @author Administrator
 */

namespace app\controller;

use metacms\base\Application;
use metacms\web\Controller;
use metacms\web\View;
use app\model\SiteConfigModel;

class BaseController extends Controller
{
    /**
     * @var 站点配置
     */
    public $siteInfo;

    public function __construct()
    {
    }

    public function getSiteInfo($key='')
    {
        $siteConfigModel = new \app\model\SiteConfigModel();
        $result = $siteConfigModel->getConfigList([], 'name,value');
        $site_info = [];
        foreach ($result as $value) {
            $site_info[$value['name']] = $value['value'];
        }
        if ($key && isset($site_info[$key])) {
            return $site_info[$key];
        }else{
            return $site_info;
        }
    }


    /**
     * 输出模版方法
     * @param type $view
     * @param type $dataData
     */
    public function display($view, $data = array())
    {
        View::setDirectory(MODULE_PATH. '/' . C('DIR_VIEW') . '/');
        View::display($view, $data);
    }

    /**
     * 会话组件
     * @return  \Aura\Session\Session
     */
    public function session()
    {
        $container = Application::container();
        if (!$container->offsetExists('session')) {
            $container['session'] = function ($c) {
                return (new \Aura\Session\SessionFactory())->newInstance($_COOKIE);
            };
        }
        return $container['session'];
    }

    /**
     * session 分片
     * @return  \Aura\Session\Segment
     */
    function segment()
    {
        $container = Application::container();
        if (!$container->offsetExists('segment')) {
            $container['segment'] = function ($c) {
                $session = $this->session();
                $session->setCookieParams(array('lifetime' => 1800 * 24));
                $segment_key = Application::config()->get('SEGMENT_KEY');
                return $session->getSegment($segment_key);
            };
        }
        return $container['segment'];
    }


}
