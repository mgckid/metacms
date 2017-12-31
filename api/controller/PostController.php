<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 0:01
 */

namespace app\controller;


use app\logic\BaseLogic;
use app\model\CmsPostExtendAttributeModel;
use app\model\CmsPostModel;
use metacms\base\Page;
use app\model\CmsCategoryModel;
use app\model\DictionaryModelModel;

class PostController extends BaseController
{


    /**
     * 标签列表
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function tagList()
    {
        $cmsPostModel = new CmsPostModel();
        $result = $cmsPostModel->getAllRecord('', 'post_tag');
        $tags = [];
        foreach ($result as $value) {
            $tags = array_merge($tags, explode(',', $value['post_tag']));
        }
        $tags = array_values(array_unique($tags));
        foreach ($tags as $key => $value) {
            if (empty($value)) {
                unset($tags[$key]);
            }
        }
        $this->response($tags, self::S200_OK, null, true);
    }


    public function tagPostList()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'p' => 'required|integer',
            'page_size' => 'required|integer',
            'tag_name' => 'required',
            'model_id' => 'integer',
            'with_extend' => 'boolean',
        ];
        $map = [
            'p' => '[p]当前页数',
            'page_size' => '[page_size]每页记录条数',
            'tag_name' => '[tag_name]标签名称',
            'model_id' => '[model_id]模型id',
            'with_extend' => '[with_extend]返回扩展内容'
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }

        $p = $request_param['p'];
        $page_size = $request_param['page_size'];
        $tag_name = $request_param['tag_name'];
        $model_id = $request_param['model_id'];
        $with_extend = isset($request_param['with_extend']) ? $request_param['with_extend'] : 0;

        $orm = $cmsPostModel->orm()->where_like('post_tag', '%' . $tag_name . '%');
        if ($model_id) {
            $orm->where('model_id', $model_id);
        }
        $count = $cmsPostModel->getRecordList($orm, '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostModel->getRecordList($orm, $page->getOffset(), $page->getPageSize(), false);
        $cmsCategoryModel = new CmsCategoryModel();
        $post_list = [];
        foreach ($result as $key => $value) {
            if ($with_extend) {
                $value = $cmsPostModel->getModelRecordInfoByPostId($value['post_id']);
            }
            $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
            $value['category_name'] = $category_result['category_name'];
            $value['category_alias'] = $category_result['category_alias'];
            $post_list[] = $value;
        }
        $return = [
            'count' => $count,
            'list' => $post_list,
        ];
        $this->response($return, self::S200_OK, null, true);
    }


    /**
     * 文档栏目
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function allCategory()
    {
        $model = new CmsCategoryModel();
        $cateResult = $model->getAllRecord();
        $this->response($cateResult, self::S200_OK, null, true);
    }

    /**
     * 栏目信息
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function category()
    {
        $cmsCategoryModel = new CmsCategoryModel();
        $rules = [
            'category_alias' => 'required_without:category_id',
            'category_id' => 'required_without:category_alias',
        ];
        $map = [
            'category_alias' => '栏目别名',
            'category_id' => '栏目id'
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsCategoryModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }

        $category_alias = isset($request_param['category_alias']) ? $request_param['category_alias'] : '';
        $category_id = isset($request_param['category_id']) ? $request_param['category_id'] : 0;

        $logic = new BaseLogic();
        #获取栏目信息
        {
            if ($category_alias) {
                $orm = $cmsCategoryModel->orm()->where('category_alias', $category_alias);
            } elseif ($category_id) {
                $orm = $cmsCategoryModel->orm()->where('id', $category_id);
            }
            $category_result = $cmsCategoryModel->getRecordInfo($orm);
            $model_result = $logic->getModelInfo($category_result['model_id']);
            $category_result['dictionary_value'] = $model_result['dictionary_value'];
        }
        $return = [
            'category_info' => $category_result
        ];
        $this->response($return, self::S200_OK, null, true);
    }

    /**
     * 热门文章
     * @access 文档栏目下子栏目
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function subCategory()
    {
        $cmsCategoryModel = new CmsCategoryModel();
        $rules = [
            'category_id' => 'required|numeric'
        ];
        $map = [
            'category_id' => '栏目id',
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsCategoryModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $category_id = $request_param['category_id'];
        $orm = $cmsCategoryModel->orm()->where('pid', $category_id);
        $result = $cmsCategoryModel->getAllRecord($orm);
        #子栏目没有时，获取同级栏目
        if (!$result) {
            $orm = $cmsCategoryModel->orm()->where('id', $category_id);
            $category_info = $cmsCategoryModel->getRecordInfo($orm);
            $orm = $cmsCategoryModel->orm()->where('pid', $category_info['pid']);
            $result = $cmsCategoryModel->getAllRecord($orm);
        }
        $this->response($result, self::S200_OK, null, true);
    }

    /**
     * 栏目下文档
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function categoryPosts()
    {
        $cmsCategoryModel = new CmsCategoryModel();
        $map = [
            'category_id' => '栏目id',
        ];
        $rules = [
            'category_id' => 'required|integer',
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsCategoryModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $category_id = isset($request_param['category_id']) && !empty($request_param['category_id']) ? intval($request_param['category_id']) : 0;
        $orm = $cmsCategoryModel->orm()->table_alias('c')->right_join('dictionary_model', ['c.model_id', '=', 'm.id'], 'm')->where(['c.id' => $category_id]);
        $field = 'c.*,m.dictionary_value';
        $category_result = $cmsCategoryModel->getRecordInfo($orm, $field);
        #获取栏目下文档
        {
            $category_type = '';
            $category_posts = [];
            $category_id = $category_result['id'];
            $dictionaryModel = new DictionaryModelModel();
            $all_model_result = $dictionaryModel->getAllRecord();
            $pmodel = getParents($all_model_result, $category_result['model_id']);
            $model_names = array_column($pmodel, 'dictionary_value');
            if ($category_result['category_type'] == 'channel') {#频道内容
                if (!isset($request_param['list_size'])) {
                    $this->response(null, self::S400_BAD_REQUEST, '列表记录数不能为空');
                }
                $list_size = isset($request_param['list_size']) ? intval($request_param['list_size']) : 10;
                #获取下级栏目
                $orm = $cmsCategoryModel->orm()->where('pid', $category_id);
                $sub_category_result = $cmsCategoryModel->getAllRecord($orm);
                $cmsPostModel = new CmsPostModel();
                foreach ($sub_category_result as $key => $value) {
                    $orm = $cmsPostModel->orm()->where('category_id', $value['id']);
                    $post_result = $cmsPostModel->getModelRecordList($value['model_id'], $orm, 0, $list_size, false, 'id', 'desc');
                    $value['post_list'] = $post_result;
                    $sub_category_result[$key] = $value;
                }
                $category_posts = $sub_category_result;
            } else if ($category_result['category_type'] == 'list') {#列表内容
                if (!isset($request_param['page_size'])) {
                    $this->response(null, self::S400_BAD_REQUEST, '每页记录数不能为空');
                }
                $p = isset($request_param['p']) ? intval($request_param['p']) : 1;
                $page_size = isset($request_param['page_size']) ? intval($request_param['page_size']) : 10;
                $cmsPostModel = new CmsPostModel();
                $orm = $cmsPostModel->orm()->where(['category_id' => $category_id]);
                $count = $cmsPostModel->getModelRecordList($category_result['model_id'], $orm, '', '', true);
                $page = new Page($count, $p, $page_size);
                $result = $cmsPostModel->getModelRecordList($category_result['model_id'], $orm, $page->getOffset(), $page->getPageSize(), false);
                foreach ($result as $key => $value) {
                    $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
                    $value['category_name'] = $category_result['category_name'];
                    $value['category_alias'] = $category_result['category_alias'];
                    $result[$key] = $value;
                }
                $category_posts = [
                    'page_size' => $page_size,
                    'p' => $p,
                    'count' => $count,
                    'list' => $result,
                ];
            } else if ($category_result['category_type'] == 'page') {#单页内容
                $cmsPostModel = new CmsPostModel();
                $orm = $cmsPostModel->orm()->where(['category_id' => $category_id]);
                $count = $cmsPostModel->getModelRecordList($category_result['model_id'], $orm, '', '', true);
                $result = $cmsPostModel->getModelRecordList($category_result['model_id'], $orm, 0, $count, false);
                $category_posts = $result;
            } else {
                $this->response(null, self::S400_BAD_REQUEST, '栏目类型不存在');
            }
            $this->response($category_posts, self::S200_OK, null, true);
        }
    }

    public function search()
    {
        $cmsPostModel = new CmsPostModel();
        $map = [
            'keyword' => '关键字',
            'p' => '当前页数',
            'page_size' => '每页记录条数',
        ];
        $rules = [
            'keyword' => 'required',
            'p' => 'required|integer',
            'page_size' => 'required|integer',
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $keyword = $request_param['keyword'];
        $p = intval($request_param['p']);
        $page_size = intval($request_param['page_size']);
        $sql = "SELECT  post_id FROM cms_post WHERE `title` LIKE '%{$keyword}%' or  `keywords` LIKE '%{$keyword}%' or  `description` LIKE '%{$keyword}%' or  `post_tag` LIKE '%{$keyword}%' GROUP by post_id UNION  SELECT  post_id FROM cms_post_extend_attribute WHERE `value` LIKE '%{$keyword}%' GROUP by post_id UNION SELECT  post_id FROM cms_post_extend_text WHERE `value` LIKE '%{$keyword}%'  GROUP by post_id ";
        $result = $cmsPostModel->orm()->raw_query($sql)->find_array();
        if (!$result) {
            $this->response(null, self::S200_OK, '没有搜索到数据');
        }
        $post_ids = array_column($result, 'post_id');
        $orm = $cmsPostModel->orm()->where_in('cms_post.post_id', $post_ids);
        $count = $cmsPostModel->getRecordList($orm, '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $result = $cmsPostModel->getRecordList($orm, $page->getOffset(), $page->getPageSize(), false);
        $cmsCategoryModel = new CmsCategoryModel();
        foreach ($result as $key => $value) {
            $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
            $value['category_name'] = $category_result['category_name'];
            $value['category_alias'] = $category_result['category_alias'];
            $result[$key] = $value;
        }
        $return = [
            'count' => $count,
            'list' => $result,
        ];
        $this->response($return, self::S200_OK, null, true);
    }


    public function recommendPost()
    {
        $model = new CmsPostModel();
        $rules = [
            'model_id' => 'required|integer',
            'rule' => 'required|array'
        ];
        $map = [
            'model_id' => '栏目id',
            'rule' => '推荐规则'
        ];
        $request_param = $this->getRequestParam();
        $validate = $model->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $model_id = $request_param['model_id'];
        $rule = $request_param['rule'];
        $list = [];
        foreach ($rule as $value) {
            $r = explode(',', $value);
            $recommd_id = $r[0];
            $limit = $r[1];
            $orm = $model->orm()->where_like('is_recommed', '%' . $recommd_id . '%');
            $list[$recommd_id] = $model->getModelRecordList($model_id, $orm, 0, $limit, false);
        }
        $this->response($list, self::S200_OK, null, true);
    }

    /**
     * 热门文章
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function hotPost()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'page_size' => 'required|integer',
            'model_id' => 'required|integer',
        ];
        $map = [
            'page_size' => '每页记录条数',
            'model_id' => '内容模型id',
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $page_size = $request_param['page_size'];
        $model_id = $request_param['model_id'];
        $orm = $cmsPostModel->orm()->where('model_id', $model_id);
        $result = $cmsPostModel->getRecordList($orm, 0, $page_size, false, 'click');
        $this->response($result, self::S200_OK, null, true);
    }

    /**
     * 相关文章
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function relatedPost()
    {
        $cmsPostModel = new CmsPostModel();
        $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
        $rules = [
            'post_id' => 'required|numeric',
            'page_size' => 'required|integer',
        ];
        $map = [
            'post_id' => '内容id',
            'page_size' => '列表记录数'
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $post_id = $request_param['post_id'];
        $page_size = $request_param['page_size'];
        $post_result = $cmsPostModel->getModelRecordInfoByPostId($post_id);
        if (!$post_result) {
            $this->response(null, self::S400_BAD_REQUEST, '文档不存在');
        }
        $post_ids = [];
        #获取标签关联文章
        if ($post_result['post_tag']) {
            $tag = explode(',', $post_result['post_tag']);
            foreach ($tag as $value) {
                $orm = $cmsPostExtendAttributeModel->orm()->where(['field' => 'post_tag', 'value' => $value]);
                $result = $cmsPostExtendAttributeModel->getAllRecord($orm, 'post_id');
                if ($result) {
                    $result = array_column($result, 'post_id');
                    $post_ids = array_merge($post_ids, $result);
                }
            }
        }
        #获取同栏目下文章
        if (count($post_ids) < $page_size) {
            $page_size = $page_size - count($post_ids);
            $orm = $cmsPostModel->orm()->where('category_id', $post_result['category_id']);
            if ($post_ids) {
                $orm = $orm->where_not_in('post_id', $post_ids);
            }
            $result = $cmsPostModel->getRecordList($orm, 0, $page_size);
            if ($result) {
                $result = array_column($result, 'post_id');
                $post_ids = array_merge($post_ids, $result);
            }
        } else {
            $post_ids = array_slice($post_ids, 0, 6);
        }
        $orm = $cmsPostModel->orm()->where_in('cms_post.post_id', $post_ids);
        $result = $cmsPostModel->getModelRecordList($post_result['model_id'], $orm);
        $this->response($result, self::S200_OK, '', true);
    }

    /**
     * 最新文章
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function latestPost()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'page_size' => 'required|integer',
            'model_id' => 'required|integer',
        ];
        $map = [
            'model_id' => '[model_id]文档模型id',
            'page_size' => '[page_size]每页记录条数',
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $model_id = $request_param['model_id'];
        $page_size = $request_param['page_size'];
        $orm = $cmsPostModel->orm()->where('model_id', $model_id);
        $result = $cmsPostModel->getRecordList($orm, 0, $page_size, false, 'id', 'desc');
        $cmsCategoryModel = new CmsCategoryModel();
        foreach ($result as $key => $value) {
            $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
            $value['category_name'] = $category_result['category_name'];
            $value['category_alias'] = $category_result['category_alias'];
            $result[$key] = $value;
        };
        $this->response($result, self::S200_OK, null, true);
    }

    public function postList()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'page_size' => 'required|integer',
            'p' => 'required|integer',
            'sort' => 'required|in:desc,asc',
            'model_id' => 'required|integer',
        ];
        $map = [
            'page_size' => '[page_size]每页记录条数',
            'p' => '[p]当前页',
            'sort' => '[sort]排序方式',
            'model_id' => '[model_id]文档模型id',
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $model_id = $request_param['model_id'];
        $page_size = $request_param['page_size'];
        $p = $request_param['p'];
        $sort = $request_param['sort'];
        $orm = $cmsPostModel->orm()->where('model_id', $model_id);
        $count = $cmsPostModel->getRecordList($orm, '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostModel->getRecordList($orm, $page->getOffset(), $page_size, false, 'id', $sort);
        $data = [];
        if ($result) {
            $cmsCategoryModel = new CmsCategoryModel();
            foreach ($result as $key => $value) {
                $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
                $value['category_name'] = $category_result['category_name'];
                $value['category_alias'] = $category_result['category_alias'];
                $result[$key] = $value;
            };
        }
        $data['post_list'] = $result;
        $data['count'] = $count;
        $this->response($data, self::S200_OK, null, true);
    }


    /**
     * 文档详情
     * @access public
     * @author furong
     * @return void
     * @since 2017年11月21日 16:36:13
     * @abstract
     */
    public function post()
    {
        $cmsPostModel = new CmsPostModel();
        $cmsCategoryModel = new CmsCategoryModel();
        $rules = [
            'post_id' => 'required|numeric'
        ];
        $map = [
            'post_id' => '文档id',
        ];
        $request_param = $this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $post_id = $request_param['post_id'];
        $article = $cmsPostModel->getModelRecordInfoByPostId($post_id);
        $category_result = $cmsCategoryModel->getRecordInfoById($article['category_id']);
        $article['category_name'] = $category_result['category_name'];
        $article['category_alias'] = $category_result['category_alias'];
        if (!$article) {
            $this->response(null, self::S404_NOT_FOUND);
        } else {
            $id = $article['id'];
            $pre_result = $cmsPostModel->getPre($id, '*');
            $next_result = $cmsPostModel->getNext($id, '*');
            $result['article'] = $article;
            $result['pre'] = $pre_result;
            $result['next'] = $next_result;
            $post_data = [
                'id' => $article['id'],
                'click' => $article['click'] + 1,
                'model_id' => $article['model_id'],
            ];
            $cmsPostModel->addRecord($post_data);
            $this->response($result, self::S200_OK, null, true);
        }
    }


}