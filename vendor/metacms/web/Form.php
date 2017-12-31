<?php
/**
 * Created by PhpStorm.
 * User: furong
 * Date: 2017/8/1
 * Time: 11:00
 */

namespace metacms\web;

use metacms\base\Application;


class Form
{
    static protected $instance;
    #表单结构数据
    private $form_schema;
    #表单数据
    private $form_data;
    #表单提交地址
    private $form_action;
    #表单提交方法
    private $form_method;
    #表单样式
    private $form_class;
    #表单名称
    private $form_name;


    protected function __construct($form_name = '', $form_method = '', $form_action = '', $form_class = '')
    {
        $this->form_name = !empty($form_name) ? $form_name : 'autoform';
        $this->form_method = !empty($form_method) ? $form_method : 'post';
        $this->form_action = !empty($form_action) ? $form_action : $_SERVER['REQUEST_URI'];
        $this->form_class = !empty($form_class) ? $form_class : 'form form-horizontal';
    }

    /**
     * 获取类实例化对象
     * @return $this
     */
    public static function getInstance($form_name = '', $form_method = '', $form_action = '', $form_class = '')
    {
        if (empty(self::$instance)) {
            self::$instance = new self($form_name, $form_method, $form_action, $form_class);
        }
        return self::$instance;
    }

    /**
     * 获取表单结构配置
     * @access public
     * @author furong
     * @return mixed
     * @since
     * @abstract
     */
    public function get_form_schema()
    {
        return $this->form_schema ? $this->form_schema : [];
    }

    /**
     * 添加表单结构数据
     * @access protected
     * @author furong
     * @param $form_schema
     * @return $this
     * @since
     * @abstract
     */
    public function form_schema($form_schema)
    {
        $this->form_schema = $form_schema;
        return $this;
    }

    /**
     * 添加表单数据
     * @access public
     * @author furong
     * @param $form_data
     * @return $this
     * @since
     * @abstract
     */
    public function form_data($form_data)
    {
        $this->form_data = $form_data;
        return $this;
    }

    /**
     * 设置表单提交地址
     * @access public
     * @author furong
     * @param $action
     * @return $this
     * @since
     * @abstract
     */
    public function form_action($action)
    {
        $this->form_action = $action;
        return $this;
    }

    /**
     * 设置表单name
     * @access public
     * @author furong
     * @param $name
     * @return $this
     * @since
     * @abstract
     */
    public function form_name($name)
    {
        $this->form_name = $name;
        return $this;
    }

    /**
     * 设置表单form class
     * @access public
     * @author furong
     * @param $class
     * @return $this
     * @since
     * @abstract
     */
    public function form_class($class)
    {
        $this->form_class = $class;
        return $this;
    }

    /**
     * 添加post提交
     * @access public
     * @author furong
     * @return $this
     * @since
     * @abstract
     */
    public function form_method_post()
    {
        $this->form_method = 'post';
        return $this;
    }

    /**
     * 添加get提交
     * @access public
     * @author furong
     * @return $this
     * @since
     * @abstract
     */
    public function form_method_get()
    {
        $this->form_method = 'get';
        return $this;
    }

    /**
     * 添加文本域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_text($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'text'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加密码域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_password($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'password'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加隐藏域元素
     * @access public
     * @author furong
     * @param $name  input name
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_hidden($name, $default_value = '')
    {
        $schema = [
            'name' => $name,
            'type' => 'hidden'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加文件域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_file($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'file'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加单选域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function radio($title, $name, $enum, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'radio',
            'enum' => $enum,
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加多选域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function checkbox($title, $name, $enum, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'checkbox',
            'enum' => $enum,
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加下拉域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function select($title, $name, $enum, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'select',
            'enum' => $enum,
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加多行文本域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function textarea($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'textarea'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加富文本编辑器域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function editor($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'textarea'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }


    /**
     * 创建表单
     * @access public
     * @author furong
     * @return string
     * @since
     * @abstract
     */
    public function  create()
    {
        $form_action_str = !empty($this->form_action) ? 'action="' . $this->form_action . '"' : '';
        $form_name_str = !empty($this->form_name) ? 'name="' . $this->form_name . '"' : '';
        $form_id_str = !empty($this->form_name) ? 'id="' . $this->form_name . '"' : '';
        $form_method_str = !empty($this->form_method) ? 'method="' . $this->form_method . '"' : '';
        $form_class_str = !empty($this->form_class) ? 'class="' . $this->form_class . '"' : '';
        $inputs_str = $this->render();
        $template = '<form %s %s %s %s %s> %s';
        return sprintf($template, $form_action_str, $form_name_str, $form_id_str, $form_method_str, $form_class_str, $inputs_str);
    }

    /**
     * 创建表单input
     * @access public
     * @author furong
     * @param $form_schema
     * @param array $form_data
     * @return string
     * @since
     * @abstract
     */
    public function render()
    {
        $form_schema = $this->form_schema;
        $form_data = $this->form_data;
        $input_str = '';
        if (!empty($form_schema) && is_array($form_schema)) {
            foreach ($form_schema as $value) {
                $input_title = isset($value['title']) ? $value['title'] : '';
                $input_type = isset($value['type']) ? $value['type'] : '';
                $input_name = isset($value['name']) ? $value['name'] : '';
                $input_enum = isset($value['enum']) ? $value['enum'] : [];
                $input_value = isset($form_data[$input_name]) ? $form_data[$input_name] : '';
                $input_description = isset($value['description']) ? $value['description'] : '';
                $input_placeholder = !empty($input_title) ? '请输入' . $input_title : '';

                switch ($input_type) {
                    case 'hidden':
                        $input_str .= $this->render_input_hidden($input_name, $input_value);
                        break;
                    case 'text':
                        $input_str .= $this->render_input_text($input_title, $input_name, $input_placeholder, $input_description, $input_value);
                        break;
                    case 'password':
                        $input_str .= $this->render_input_password($input_title, $input_name, $input_placeholder, $input_description, $input_value);
                        break;
                    case 'file':
                        $input_str .= $this->render_input_file($input_title, $input_name, $input_description, $input_value);
                        break;
                    case 'textarea':
                        $input_str .= $this->render_textarea($input_title, $input_name, $input_placeholder, $input_description, $input_value);
                        break;
                    case 'editor':
                        $input_str .= $this->render_editor($input_title, $input_name, $input_placeholder, $input_description, $input_value);
                        break;
                    case "radio":
                        $input_str .= $this->render_radio($input_title, $input_name, $input_enum, $input_description, $input_value);
                        break;
                    case 'checkbox':
                        $input_str .= $this->render_checkbox($input_title, $input_name, $input_enum, $input_description, $input_value);
                        break;
                    case 'select':
                        $input_str .= $this->render_select($input_title, $input_name, $input_enum, $input_description, $input_value);
                        break;
                    default:
                        throw new \Exception($input_type . '表单类型不存在');
                }
            }
        }
        return $input_str;
    }


    /**
     * 渲染文本域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_text($input_title, $input_name, $input_placeholder, $input_description, $input_value = '')
    {
        $input_str = $this->render_input('text', $input_name, 'form-control', $input_placeholder, $input_value);
        $html = $this->render_form_group($input_title, $input_str, $input_description);
        return $html;
    }

    /**
     * 渲染隐藏域结构
     * @access public
     * @author furong
     * @param $name input name
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_hidden($input_name, $input_value = '')
    {
        $html = $this->render_input('hidden', $input_name, '', '', $input_value);
        return $html;
    }

    /**
     * 渲染密码域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_password($input_title, $input_name, $input_placeholder, $input_description, $input_value = '')
    {
        $input_str = $this->render_input('password', $input_name, 'form-control', $input_placeholder, $input_value);
        $html = $this->render_form_group($input_title, $input_str, $input_description);
        return $html;
    }

    /**
     * 渲染文件域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_file($input_title, $input_name, $input_description, $input_value = '')
    {
        $hiddenInput = $this->render_input_hidden($input_name, $input_value);
        $image_url = !empty($input_value) ? getImage($input_value) : '';
        //  $fileInput = '<input type="file" id="upload_file" data-preview="' . $image_url . '" />';

        $html = $hiddenInput;
        $html = $this->render_form_group($input_title, $html, $input_description);
        return $html;
    }

    /**
     * 渲染多行文本域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_textarea($input_title, $input_name, $input_placeholder, $input_description, $input_value = '')
    {
        $id_str = !empty($input_name) ? 'id="' . $input_name . '"' : '';
        $name_str = !empty($input_name) ? 'name="' . $input_name . '"' : '';
        $placeholder_str = !empty($input_placeholder) ? 'placeholder="' . $input_placeholder . '"' : '';
        $class_str = 'class="form-control"';
        $template = '<textarea %s %s %s %s >%s</textarea>';

        $html = sprintf($template, $name_str, $id_str, $class_str, $placeholder_str, $input_value);
        return $this->render_form_group($input_title, $html, $input_description);
    }

    /**
     * 渲染富文本编辑器域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_editor($input_title, $input_name, $input_placeholder, $input_description, $input_value = '')
    {
        $id_str = !empty($input_name) ? 'id="' . $input_name . '"' : '';
        $name_str = !empty($input_name) ? 'name="' . $input_name . '"' : '';
        $placeholder_str = !empty($input_placeholder) ? 'placeholder="' . $input_placeholder . '"' : '';
        $template = '<textarea %s %s %s style="height:500px;" >%s</textarea>';
        $html = sprintf($template, $name_str, $id_str, $placeholder_str, $input_value);
        return $this->render_form_group($input_title, $html, $input_description);
    }


    /**
     * 渲染单选域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $enum 选项数据
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_radio($input_title, $input_name, $input_enum, $input_description, $input_value = '')
    {
        $label_str = '';
        foreach ($input_enum as $value) {
            $checked_str = $value['value'] == $input_value ? 'checked="checked"' : '';
            $input_str = $this->render_input('radio', $input_name, '', '', $value['value'], $checked_str) . $value['name'];
            $label_str .= $this->render_label('radio-inline', $input_str) . '&nbsp;&nbsp;';
        }
        $html = $this->render_form_group($input_title, $label_str, $input_description);
        return $html;
    }

    /**
     * 渲染多选域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $enum 选项数据
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_checkbox($input_title, $input_name, $input_enum, $input_description, $input_value = '')
    {
        $label_str = '';
        $input_value = $input_value ? explode(',', $input_value) : [];
        foreach ($input_enum as $value) {
            $checked_str = in_array($value['value'], $input_value) ? 'checked="checked"' : '';
            $input_str = $this->render_input('checkbox', $input_name . '[]', '', '', $value['value'], $checked_str) . $value['name'];
            $label_str .= $this->render_label('checkbox-inline', $input_str) . '&nbsp;&nbsp;';
        }
        $html = $this->render_form_group($input_title, $label_str, $input_description);
        return $html;
    }

    /**
     * 渲染下拉选择域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $enum 选项数据
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_select($input_title, $input_name, $input_enum, $input_description, $input_value = '')
    {
        $id_str = !empty($input_name) ? 'id="' . $input_name . '"' : '';
        $name_str = !empty($input_name) ? 'name="' . $input_name . '"' : '';
        $selected_data_str = !empty($input_value) ? 'data-selected="' . $input_value . '"' : '';
        $class_str = 'class="form-control"';

        $options_str = '<option value="">请选择' . $input_title . '</option>';
        foreach ($input_enum as $value) {
            $option_title_str = !empty($value['name']) ? $value['name'] : '';
            $option_selected_str = ($input_value == $value['value']) ? 'selected="selected"' : '';
            $option_value_str = ($value['value'] === 0 || $value['value'] === '0' || !empty($value['value'])) ? 'value="' . $value['value'] . '"' : '';
            $option_template = '<option %s %s >%s</option>';
            $options_str .= sprintf($option_template, $option_value_str, $option_selected_str, $option_title_str);
        }
        $select_template = '<select %s %s %s %s>%s</select>';
        $html = sprintf($select_template, $name_str, $id_str, $class_str, $selected_data_str, $options_str);
        $html = $this->render_form_group($input_title, $html, $input_description);
        return $html;
    }

    /**
     * 构建input表单
     * @access protected
     * @author furong
     * @param $type 表单类型
     * @param $name 表单名称
     * @param $fieldHtmlClass 字段样式class
     * @param $placeholder 默认提示
     * @return string
     * @since  2017年7月13日 16:22:14
     * @abstract
     */
    protected function render_input($input_type, $input_name, $input_class, $input_placeholder, $input_value = '', $extra_str = '')
    {


        $name_str = !empty($input_name) ? 'name="' . $input_name . '"' : '';
        $type_str = !empty($input_type) ? 'type="' . $input_type . '"' : '';
        $class_str = !empty($input_class) ? 'class="' . $input_class . '"' : '';
        $placeholder_str = !empty($input_placeholder) ? 'placeholder="' . $input_placeholder . '"' : '';
        $value_str = ($input_value === 0 || $input_value === '0' || !empty($input_value)) ? 'value="' . $input_value . '"' : '';

        $template = '<input %s %s %s %s %s %s/>';
        return sprintf($template, $type_str, $name_str, $class_str, $placeholder_str, $value_str, $extra_str);
    }

    protected function render_label($label_class, $label_content)
    {
        $template = '<label class="%s">%s</label>';
        return sprintf($template, $label_class, $label_content);
    }

    /**
     * 构建表单组
     * @access  protected
     * @author furong
     * @param $htmlClass
     * @param $title_str
     * @param $form_control_str
     * @return string
     * @since 2017年7月13日 16:56:17
     * @abstract
     */
    protected function render_form_group($input_title, $input_str, $input_description)
    {
        $template = <<<EOT
             <div class="form-group">
               <label class="control-label col-sm-2"> %s</label>
                <div class="col-sm-8">
                  %s
                </div>
                <label class="col-sm-2"> %s</label>
              </div>
EOT;
        return sprintf($template, $input_title, $input_str, $input_description);
    }


}