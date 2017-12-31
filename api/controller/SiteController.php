<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/19
 * Time: 23:53
 */

namespace app\controller;


use app\model\AdvertisementListModel;
use app\model\SiteConfigModel;
use app\model\SiteFlinkModel;

class SiteController extends BaseController
{
    public function siteConfig()
    {
        $siteConfigModel = new SiteConfigModel();
        $orm = $siteConfigModel->orm()->where('deleted', 0);
        $result = $siteConfigModel->getAllRecord($orm, 'name,value,description');
        $siteInfo = [];
        foreach ($result as $value) {
            $siteInfo[$value['name']] = $value['value'];
        }
        $this->response($siteInfo, self::S200_OK, null, true);
    }

    public function flink()
    {
        $siteConfigModel = new SiteFlinkModel();
        $orm = $siteConfigModel->orm()->where('deleted', 0);
        $result = $siteConfigModel->getAllRecord($orm, 'fname,furl,fdesc');
        $this->response($result, self::S200_OK, null, true);
    }

    public function  advertisement(){
        $model = new AdvertisementListModel();
        $orm = $model->orm()->where('al.deleted', 0);
        $count  = $model->getAdvertisementList($orm,'','',true);
        $result = $model->getAdvertisementList($orm,0,$count,false,'al.*,ap.position_name,ap.position_key');
        $data = [];
        if ($result) {
            $sort = array_column($result, 'sort');
            array_multisort($sort, SORT_DESC, $result);
            foreach ($result as $value) {
                $data[$value['position_key']][] = $value;
            }
        }
        $this->response($data, self::S200_OK, null, true);
    }


}