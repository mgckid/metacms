<?php


namespace app\controller;

use app\model\SiteFlinkModel;
use metacms\web\Form;
use metacms\base\Page;
use app\logic\BaseLogic;

/**
 * 友情链接管理控制器
 * @privilege 友情链接管理|Admin/Flink|c1a2f7e9-200a-11e7-8ad5-9cb3ab404081|1
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2016/7/29
 * Time: 14:12
 */
class FlinkController extends UserBaseController
{

    /**
     * 添加友情链接
     * @privilege 添加友情链接|Admin/Flink/addFlink|c1ac6529-200a-11e7-8ad5-9cb3ab404081|3
     */
    public function addFlink()
    {
        if (IS_POST) {
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData('site_link', 'table');

            $SiteFlinkModel = new SiteFlinkModel();
            $result = $SiteFlinkModel->addRecord($request_data);
            if ($result) {
                $this->ajaxSuccess('友情链接添加成功');
            } else {
                $this->ajaxFail('友情链接添加失败');
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            $logic = new BaseLogic();
            $form_init = $logic->getFormInit('site_link', 'table');

            $result = [];
            if ($id) {
                $SiteFlinkModel = new SiteFlinkModel();
                $result = $SiteFlinkModel->getRecordInfoById($id);
            }
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '运营管理' => U('admin/Flink/index'),
                '添加友情链接' => ''
            ));
            $this->display('Flink/addFlink');
        }
    }

    /**
     * 友情链接列表
     * @privilege 友情链接列表|Admin/Flink/flinkList|c217dd89-200a-11e7-8ad5-9cb3ab404081|2
     */
    public function flinkList()
    {

        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 20;
        #获取列表字段
        $dictionarylogic = new BaseLogic();
        $list_init = $dictionarylogic->getListInit('site_link');
        $flinkModel = new SiteFlinkModel();
        $count = $flinkModel->getRecordList('', '', '', true);
        $page = new Page($count, $p, $pageSize, false);
        $result = $flinkModel->getRecordList('', $page->getOffset(), $pageSize, false);
        $data = array(
            'list' => $result,
            'pages' => $page->getPageStruct(),
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '运营管理' => U('admin/Flink/index'),
            '友情链接管理' => ''
        ));
        $this->display('Flink/flinkList', $data);
    }

    /**
     * 删除友情链接
     * @privilege 删除友情链接|Admin/Flink/delFlink|c2235b2e-200a-11e7-8ad5-9cb3ab404081|3
     */
    public function delFlink()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $model = new SiteFlinkModel();
        #验证
        $rule = array(
            'id' => 'required',
        );
        $attr = array(
            'id' => '友情链接ID',
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $result = $model->deleteRecordById($id);
        if (!$result) {
            $this->ajaxFail('删除失败');
        } else {
            $this->ajaxSuccess('删除成功');
        }
    }


}
