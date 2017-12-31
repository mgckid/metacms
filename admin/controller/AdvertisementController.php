<?php

namespace app\controller;


use app\model\AdvertisementListModel;
use app\model\AdvertisementPositioModel;
use metacms\web\Form;
use metacms\base\Page;
use app\logic\BaseLogic;

/**
 * 广告管理
 *
 * @privilege 广告管理|Admin/Advertisement|5e46e259-2002-11e7-8ad5-9cb3ab404081|1
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/25
 * Time: 22:05
 */
class AdvertisementController extends UserBaseController
{
    /**
     * 增加广告位
     * @privilege 增加广告位|Admin/Advertisement/addPosition|7c5b0f9a-2002-11e7-8ad5-9cb3ab404081|2
     */
    public function addPosition()
    {
        if (IS_POST) {
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData('advertisement_position', 'table');

            $advertisementPositionModel = new AdvertisementPositioModel();
            $result = $advertisementPositionModel->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail('广告位添加失败');
            } else {
                $this->ajaxSuccess('广告位添加成功');
            }

        } else {
            $logic = new BaseLogic();
            $form_init = $logic->getFormInit('advertisement_position', 'table');

            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb([
                '广告管理' => U('admin/Advertisement/index'),
                '添加广告位' => ''
            ]);
            $this->display('Advertisement/addPosition');
        }
    }

    /**
     * 编辑广告位
     * @privilege 编辑广告位|Admin/Advertisement/editPosition|9cffab54-becf-11e7-a5e9-14dda97b937d|3
     */
    public function editPosition()
    {
        if (IS_POST) {
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData('advertisement_position', 'table');

            $advertisementPositionModel = new AdvertisementPositioModel();
            $result = $advertisementPositionModel->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail('广告位添加失败');
            } else {
                $this->ajaxSuccess('广告位添加成功');
            }

        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            $logic = new BaseLogic();
            $form_init = $logic->getFormInit('advertisement_position', 'table');

            $advertisementPositionModel = new AdvertisementPositioModel();
            $positionInfo = $advertisementPositionModel->getRecordInfoById($id);

            Form::getInstance()->form_schema($form_init)->form_data($positionInfo);
            #面包屑导航
            $this->crumb([
                '广告管理' => U('admin/Advertisement/index'),
                '编辑广告位' => ''
            ]);
            $this->display('Advertisement/addPosition');
        }
    }

    /**
     * 广告位列表
     * @privilege 广告位列表|Admin/Advertisement/index|92224545-2002-11e7-8ad5-9cb3ab404081|2
     */
    public function index()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 20;

        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit('advertisement_position');

        $advertisementPositionModel = new AdvertisementPositioModel();
        $count = $advertisementPositionModel->getRecordList('', '', '', true);
        $page = new Page($count, $p, $pageSize);
        $result = $advertisementPositionModel->getRecordList('', $page->getOffset(), $pageSize, false);

        $data['list_data'] = $result;
        $data['list_init'] = $list_init;
        $data['pages'] = $page->getPageStruct();

        #面包屑导航
        $this->crumb([
            '广告管理' => U('admin/Advertisement/index'),
            '广告位列表' => ''
        ]);
        $this->display('Advertisement/index', $data);
    }

    /**
     * 添加广告
     *
     * @privilege 添加广告|Admin/Advertisement/addAd|cd34d4cd-2002-11d8-8ad5-9cb3ab404081|2
     * @access public
     * @author furong
     * @return void
     * @since  2017年4月13日 12:39:59
     * @abstract
     */
    public function addAd()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('advertisement_list', 'table');
            $advertisementListModel = new AdvertisementListModel();
            $result = $advertisementListModel->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail('添加广告失败' . $this->getMessage());
            } else {
                $this->ajaxSuccess('添加广告成功');
            }
        } else {
            #获取表单初始数据
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('advertisement_list', 'table');
            #自定义枚举值
            {
                #获取广告位下拉
                $positionModel = new AdvertisementPositioModel();
                $postion_result = $positionModel->getAllRecord();
                foreach ($postion_result as $value) {
                    $form_init['position_id']['enum'][] = [
                        'name' => $value['position_name'],
                        'value' => $value['id'],
                    ];
                }
            }
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb([
                '广告管理' => U('admin/Advertisement/index'),
                '添加广告' => ''
            ]);
            $this->display('Advertisement/addAd');
        }
    }

    /**
     * 编辑广告
     *
     * @privilege 编辑广告|Admin/Advertisement/editAd|cd34d4cd-2002-11e7-8ad5-9cb3ab404081|3
     * @access public
     * @author furong
     * @return void
     * @since  2017年4月13日 12:39:59
     * @abstract
     */
    public function editAd()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('advertisement_list', 'table');
            $advertisementListModel = new AdvertisementListModel();
            $result = $advertisementListModel->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail('添加广告失败' . $this->getMessage());
            } else {
                $this->ajaxSuccess('添加广告成功');
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            #获取表单初始数据
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('advertisement_list', 'table');

            #自定义枚举值
            {
                #获取广告位下拉
                $positionModel = new AdvertisementPositioModel();
                $postion_result = $positionModel->getAllRecord();
                foreach ($postion_result as $value) {
                    $form_init['position_id']['enum'][] = [
                        'name' => $value['position_name'],
                        'value' => $value['id'],
                    ];
                }
            }

            $advertisementListModel = new AdvertisementListModel();
            $adInfo = $advertisementListModel->getRecordInfoById($id);
            Form::getInstance()->form_schema($form_init)->form_data($adInfo);
            #面包屑导航
            $this->crumb([
                '广告管理' => U('admin/Advertisement/index'),
                '编辑广告' => ''
            ]);
            $this->display('Advertisement/addAd');
        }
    }


    /**
     * 广告列表
     *
     * @privilege 广告列表|Admin/Advertisement/advertisementList|f0a3b5e6-2002-11e7-8ad5-9cb3ab404081|2
     * @access public
     * @author furong
     * @return void
     * @since  2017年4月13日 12:39:59
     * @abstract
     */
    public function advertisementList()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 20;

        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit('advertisement_list');
        #完善列表枚举值
        {
            $advertisementPositionModel = new AdvertisementPositioModel();
            $position_result = $advertisementPositionModel->getAllRecord();
            $enum = [];
            foreach ($position_result as $value) {
                $enum[$value['id']] = $value['position_name'];
            }
            $list_init['position_id']['enum'] = $enum;
        }

        $advertisementListModel = new AdvertisementListModel();
        $count = $advertisementListModel->getAdvertisementList(array(), '', '', true);
        $page = new Page($count, $p, $pageSize);
        $result = $advertisementListModel->getAdvertisementList(array(), $page->getOffset(), $pageSize, false);

        $data['adList'] = $result;
        $data['pages'] = $page->getPageStruct();
        $data['list_init'] = $list_init;
        #面包屑导航
        $this->crumb([
            '广告管理' => U('admin/Advertisement/index'),
            '广告位列表' => ''
        ]);
        $this->display('Advertisement/advertisementList', $data);
    }

    /**
     * 删除广告位
     *
     * @privilege 删除广告位|Admin/Advertisement/delPosition|2a7bd490-2003-11e7-8ad5-9cb3ab404081|3
     * @access public
     * @author furong
     * @return void
     * @since  2017年4月13日 12:39:59
     * @abstract
     */
    public function delPosition()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $advertisementPositionModel = new AdvertisementPositioModel();
        $advertisementListModel = new AdvertisementListModel();
        #验证
        $rule = array(
            'id' => 'required|numeric|integer',
        );
        $attr = array(
            'id' => '广告位ID',
        );
        $validate = $advertisementPositionModel->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        #统计广告位下广告
        $where = array(
            'where' => array('al.position_id', $id),
        );
        $adCount = $advertisementListModel->getAdvertisementList($where, '', '', true);
        if ($adCount > 0) {
            $this->ajaxFail('请先删除广告位下广告');
        }
        #删除广告位
        $result = $advertisementPositionModel->deleteRecordById($id);
        if (!$result) {
            $this->ajaxFail('删除失败');
        } else {
            $this->ajaxSuccess('删除成功');
        }
    }

    /**
     * 删除广告
     *
     * @privilege 删除广告位|Admin/Advertisement/delad|3ed45bed-bed0-11e7-a5e9-14dda97b937d|3
     * @access public
     * @author furong
     * @return void
     * @since  2017年4月13日 12:39:59
     * @abstract
     */
    public function delAd()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $advertisementListModel = new AdvertisementListModel();
        #验证
        $rule = array(
            'id' => 'required|numeric|integer',
        );
        $attr = array(
            'id' => '广告ID',
        );
        $validate = $advertisementListModel->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        #删除广告位
        $result = $advertisementListModel->deleteRecordById($id);
        if (!$result) {
            $this->ajaxFail('删除失败');
        } else {
            $this->ajaxSuccess('删除成功');
        }
    }

}