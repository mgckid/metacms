<?php

/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/7/17
 * Time: 10:40
 */
namespace app\logic;

use app\model\CmsFieldModel;
use app\model\CmsModelModel;
use app\model\DictionaryModel;
use app\model\DictionaryModelModel;
use BosonNLP\BosonNLP;
use metacms\base\Model;
use metacms\web\Controller;
use app\model\CmsCategoryModel;
use app\model\DictionaryTableModel;
use app\model\DictionaryAttributeModel;
use Overtrue\Pinyin\Pinyin;

class BaseLogic extends Controller
{
    /**
     * 获取字典名称
     * @access public
     * @author furong
     * @param $field
     * @param $dictionary_name
     * @param string $field_name
     * @return array
     * @since 2017年7月17日 14:10:59
     * @abstract
     */
    public function getDictionaryName($field, $dictionary_name, $field_name = '')
    {
        $dictionaryModel = new DictionaryModel();
        $all_dictionary = treeStructForLayer($dictionaryModel->getAllDictionary());
        $name = [];
        foreach ($all_dictionary as $value) {
            if ($value['field_value'] == $dictionary_name) {
                foreach ($value['sub'] as $val) {
                    if (empty($field_name)) {
                        if (in_array($val['field_value'], $field)) {
                            $name[$val['field_value']] = $val['field_name'];
                        }
                    } else {
                        foreach ($val['sub'] as $v) {
                            if (in_array($v['field_value'], $field)) {
                                $name[$v['field_value']] = $v['field_name'];
                            }
                        }
                    }
                }
            }
        }
        return $name;
    }

    public function getTableDefinded($table_name)
    {
        $model = new DictionaryTableModel();
        $field_result = $model->orm()->table_alias('m')
            ->left_join('dictionary_table_field', ['m.id', '=', 'f.dictionary_id'], 'f')
            ->select_expr('f.*')
            ->where('m.dictionary_value', $table_name)
            ->where('m.deleted', 0)
            ->where('f.deleted', 0)
            ->find_array();
        if ($field_result) {
            $dictionaryAttributeModel = new DictionaryAttributeModel();
            foreach ($field_result as $key => $value) {
                $orm = $dictionaryAttributeModel->orm()->where('field_value', $value['field_value'])->where('deleted', 0);
                $enum_result = $dictionaryAttributeModel->getAllRecord($orm, 'attribute_name,attribute_value');
                $enum = [];
                if (!empty($enum_result)) {
                    foreach ($enum_result as $e) {
                        $enum[$e['attribute_value']] = [
                            'value' => $e['attribute_value'],
                            'name' => $e['attribute_name'],
                        ];
                    }
                }
                $value['enum'] = $enum;
                $value['belong_to_table'] = $table_name;
                $field_result[$key] = $value;
            }
        }
        return $field_result;
    }

    public function getModelDefined($model_name)
    {
        $model_result = $this->getModelInfo($model_name);

        $dictionary_model = new DictionaryModelModel();
        $all_model_result = $dictionary_model->getAllRecord();
        $pmodel = getParents($all_model_result, $model_result['id']);
        $model_name = array_column($pmodel, 'dictionary_value');
        $model = new DictionaryModelModel();
        $field_result = $model->orm()->table_alias('m')
            ->left_join('dictionary_model_field', ['m.id', '=', 'f.dictionary_id'], 'f')
            ->select_expr('f.*')
            ->where_in('m.dictionary_value', $model_name)
            ->where('m.deleted', 0)
            ->where('f.deleted', 0)
            ->find_array();
        $result = [];
        if ($field_result) {
            $dictionaryAttributeModel = new DictionaryAttributeModel();
            foreach ($field_result as $key => $value) {
                $orm = $dictionaryAttributeModel->orm()->where('field_value', $value['field_value'])->where('deleted', 0);
                $enum_result = $dictionaryAttributeModel->getAllRecord($orm, 'attribute_name,attribute_value');
                $enum = [];
                if (!empty($enum_result)) {
                    foreach ($enum_result as $e) {
                        $enum[$e['attribute_value']] = [
                            'value' => $e['attribute_value'],
                            'name' => $e['attribute_name'],
                        ];
                    }
                }
                $value['enum'] = $enum;
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function getFormInit($table_name, $mode = 'table')
    {
        if ($mode == 'table') {
            $field_definded = $this->getTableDefinded($table_name);
        } elseif ($mode = 'model') {
            if (is_numeric($table_name)) {
                $model_result = $this->getModelInfo($table_name);
                $table_name = $model_result['dictionary_value'];
            }
            $field_definded = $this->getModelDefined($table_name);
/*            #注册钩子方法
            foreach ($field_definded as $value) {
                $hook = $value['belong_to_table'];
                $function = 'app\\logic\\' . ucfirst($hook) . '::' . $value['field_value'];
                Hook::getInstance()->add_action($hook, $function);
            }*/
        }
        $form_init = [];
        foreach ($field_definded as $key => $value) {
            if ($value['form_type'] == 'none') {
                continue;
            }
            $form_init[$value['field_value']] = [
                'name' => $value['field_value'],
                'title' => $value['field_name'],
                'type' => $value['form_type'],
                'enum' => $value['enum'],
//                'description' => '这是描述',
            ];
        }
        return $form_init;
    }

    public function getRequestData($name, $mode = 'table')
    {
        if ($mode == 'table') {
            $field_definded = $this->getTableDefinded($name);
        } elseif ($mode = 'model') {
            if (is_numeric($name)) {
                $model_result = $this->getModelInfo($name);
                $name = $model_result['dictionary_value'];
            }
            $field_definded = $this->getModelDefined($name);
        }
        if (!$field_definded) {
            throw new \Exception('字段定义不存在');
        }

        #获取验证规则
        $validate_rule = [];
        $name_map = [];
        $request_data = [];
        foreach ($field_definded as $value) {
            if (!empty($value['validate_rule']) && $value['form_type'] != 'none') {
                $validate_rule[$value['field_value']] = $value['validate_rule'];
                $name_map[$value['field_value']] = $value['field_name'];
            }
            if (isset($_REQUEST[$value['field_value']])) {
                $request_data[$value['field_value']] = $_REQUEST[$value['field_value']];
            }
        }
        #验证输入数据
        if (!empty($validate_rule)) {
            $model = new Model();
            $validate = $model->validate()->make($request_data, $validate_rule, [], $name_map);
            if (false === $validate->passes()) {
                if (IS_POST || IS_AJAX) {
                    $this->ajaxFail($validate->messages()->first());
                } else {
                    die($validate->messages()->first());
                }
            }
        }
        #格式化数据
        foreach ($field_definded as $value) {
            if (isset($request_data[$value['field_value']])) {
                switch ($value['form_type']) {
                    case 'checkbox':
                        $request_data[$value['field_value']] = implode(',', $request_data[$value['field_value']]);
                        break;
                    case 'editor':
                        $request_data[$value['field_value']] = htmlspecialchars($request_data[$value['field_value']]);
                        break;
                }
                switch ($value['data_type']) {
                    case 'int':
                        $request_data[$value['field_value']] = intval($request_data[$value['field_value']]);
                        break;
                    case 'varchar':
                        $request_data[$value['field_value']] = trim($request_data[$value['field_value']]);
                        break;
                    case 'text':
                        $request_data[$value['field_value']] = htmlspecialchars($request_data[$value['field_value']]);
                        break;
                    case 'password':
                        $request_data[$value['field_value']] = sha1(trim($request_data[$value['field_value']]));
                        break;
                }
            }
        }
        return $request_data;
    }

    public function getListInit($table_name, $mode = 'table')
    {
        $list_init = [];
        if ($mode == 'table') {
            $table_defined = $this->getTableDefinded($table_name);
        } elseif ($mode == 'model') {
            if (is_numeric($table_name)) {
                $model_result = $this->getModelInfo($table_name);
                $table_name = $model_result['dictionary_value'];
            }
            $table_defined = $this->getModelDefined($table_name);
        }
        foreach ($table_defined as $value) {
            #只显示列表运许显示显示的字段
            if ($value['list_ignore'] == 1) {
                continue;
            }
            $field = $value['field_value'];
            $enum = [];
            if (!empty($value['enum'])) {
                foreach ($value['enum'] as $v) {
                    $enum[$v['value']] = $v['name'];
                }
            }
            $value['enum'] = $enum;
            $list_init[$field] = $value;
        }
        return $list_init;
    }

    public function getModelInfo($model_name)
    {
        if (is_numeric($model_name)) {
            $where = ['id' => $model_name];
        } else {
            $where = ['dictionary_value' => $model_name];
        }
        $cmsModel = new DictionaryModelModel();
        $result = $cmsModel->getRecordInfo($cmsModel->orm()->where($where));
        return $result;
    }


    public function getFenci($text)
    {
        $text = strip_tags(htmlspecialchars_decode($text));
        if (empty($text)) {
            $this->setMessage('源数据不能为空');
            return false;
        }
        $siteConfigModel = new \app\model\SiteConfigModel();
        $result = $siteConfigModel->getConfigList([], 'name,value');
        $site_info = [];
        foreach ($result as $value) {
            $site_info[$value['name']] = $value['value'];
        }
        $token = $site_info['cfg_BosonNLP_TOKEN'];
        if (empty($token)) {
            $this->setMessage('请先设置玻森分词api Token');
            return false;
        }
        $fenci = new BosonNLP($token);
        //提取关键字
        $pram = [
            'top_k' => 10,
        ];
        $result = $fenci->analysis($fenci::ACTION_KEYWORDS, $text, $pram);
        if (!$result) {
            $this->setMessage('分词失败');
            return false;
        }
        $keyword = [];
        foreach ($result[0] as $key => $val) {
            $keyword[] = $val[1];
        }
        //提取描述
        $data = [
            'content' => $text,
            'not_exceed' => 0,
            'percentage' => 0.1,
        ];
        $result = $fenci->analysis($fenci::ACTION_SUMMARY, $data);
        $summary = !empty($result) ? str_replace(PHP_EOL, "", $result) : '';
        $return = [
            'keyword' => join(',', $keyword),
            'tag' => join(',', array_slice($keyword, 0, 5)),
            'description' => $summary,
        ];
        return $return;
    }

    public function getCategoryData()
    {
        $cmsCategoryModel = new CmsCategoryModel();
        $all_category_result = $cmsCategoryModel->getAllRecord();
        $list = treeStructForLevel($all_category_result);
        $data = [];
        foreach ($list as $value) {
            $data[] = [
                'id' => $value['id'],
                'category_name' => $value['placeHolder'] . $value['category_name'],
            ];
        }
        return $data;
    }

    public function getModelData()
    {
        #模型分类
        $cmsModelModel = new DictionaryModelModel();
        $model_result = $cmsModelModel->getAllRecord();
        $data = [];
        foreach ($model_result as $key => $value) {
            $data[] = [
                'id' => $value['id'],
                'name' => $value['dictionary_name'],
            ];
        }
        return $data;
    }


}