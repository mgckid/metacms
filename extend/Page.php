<?php

/**
 * @className:      Page
 * @description:    分页处理类
 * @encoding:       UTF-8
 * @author:         mgckid <654352323@qq.com/www.ruanpower.com>
 * @date:           2015-04-04 10:41:04
 * @copyright:      CopyRight(c) 2015
 * @version:        LEAFMVC 1.0
 */
class Page {

    private $recordNum = null;             //总记录条数
    private $currentPage = null;           //当前页
    private $pageSize = null;              //每页条数
    private $operateFunc = null;              //分页跳转js函数
    private $offset = null;                //分页偏移起始位置
    private $pageNum = null;               //获取分页总页数
    private $firstPage = 1;                //第一页页码
    private $lastPage = null;                 //最后一页页码
    private $prePage = null;                  //上一页
    private $nextPage = null;                  //下一页
    private $pageStruct = null;               //分页html结构
    private $ajax;
    private $ajaxfunction;
    private $language;
    private $lang = [
        'cn' => [
            'index' => '首页',
            'end' => '尾页',
            'prev' => '上一页',
            'next' => '下一页',
        ],
        'en' => [
            'index' => 'Index',
            'end' => 'End',
            'prev' => 'Prev',
            'next' => 'Next',
        ],
    ];
    private $show_page_info = null;

    public function __sleep() {
        // TODO: Implement __sleep() method.
        return ['$recordNum'];
    }


    /**
     * @param $recordNum 记录总条数
     * @param int $currentPage 当前页
     * @param int $pageSize 每页条数
     * @param bool $ajax 是否开启ajax分页
     * @param string $ajaxfunction ajax分页js函数名
     */
    public function __construct($recordNum, $currentPage = 1, $pageSize = 20, $ajax = false, $ajaxfunction = 'ajaxPage', $language = 'cn', $show_page_info = true) {
        $this->recordNum = $recordNum;      //记录总条数
        $this->currentPage = $currentPage?$currentPage:1;  //当前页
        $this->pageSize = $pageSize;        //每页条数
        $this->ajax = $ajax;                //是否开启ajax分页
        $this->ajaxfunction = $ajaxfunction;  //ajax分页js函数名
        $this->language = $language;//语言
        $this->show_page_info = $show_page_info;
    }

    /**
     * 获取分页总页数
     * @return type
     */
    private function getPageNum() {
        $this->pageNum = ceil($this->recordNum / $this->pageSize);
    }

    /**
     * 获取分页偏移起始位置
     * @return type
     */
    public function getOffset() {
        $this->offset = ($this->currentPage - 1) * $this->pageSize;
        return $this->offset;
    }

    /**
     * 获取最后一页
     * @return type
     */
    private function getLastPage() {
        $this->lastPage = $this->pageNum;
    }

    /**
     * 获取上一页
     */
    private function getPrePage() {
        $this->prePage = ($this->currentPage == $this->firstPage) ? $this->firstPage : $this->currentPage - 1;
    }

    /**
     * 获取下一页
     */
    private function getNextPage() {
        $this->nextPage = ($this->currentPage == $this->lastPage) ? $this->lastPage : $this->currentPage + 1;
    }

    /**
     * 设置分页跳转js函数
     * @param type $page
     * @return type
     */
    private function setOperateFunc($page) {
        return $this->operateFunc . "($page)";
    }

    /**
     * 获取分页html结构,兼容bootstrap3
     * @return string
     */
    public function getPageStruct($type = 1) {
        $this->getOffset();
        $this->getPageNum();
        $this->getLastPage();
        $this->getPrePage();
        $this->getNextPage();
        $firstPageHref = $this->setPageHref($this->firstPage);
        $lastPageHref = $this->setPageHref($this->lastPage);
        $prePageHref = $this->setPageHref($this->prePage);
        $nextPageHref = $this->setPageHref($this->nextPage);
        $res = $this->getStruct($firstPageHref, $prePageHref, $nextPageHref, $lastPageHref, $type);
        $res = htmlspecialchars($res);
        return $res;
    }

    private function getStruct($firstPageHref, $prePageHref, $nextPageHref, $lastPageHref, $type = 1) {
        switch ($type) {
            case 1:
                $pageNum = '';
                $i = $this->currentPage - 5 < 0 ? 1 : $this->currentPage - 5;
                for ($i = $i; $i <= $this->pageNum; $i++) {
                    if ($i - $this->currentPage > 5) {
                        continue;
                    }
                    $class = ($i == $this->currentPage) ? 'class="active"' : '';
                    $pageNum .= '<li ' . $class . '><a ' . $this->setPageHref($i) . '>' . $i . '</a></li>';
                }
                $indexs = $this->lang[$this->language]['index'];
                $ends = $this->lang[$this->language]['end'];
                $prevs = $this->lang[$this->language]['prev'];
                $nexts = $this->lang[$this->language]['next'];
                $page_info = '';
                if ($this->show_page_info) {
                    $page_info = "<li><a>共 $this->pageNum 页,每页 $this->pageSize 条，总计 $this->recordNum 条记录</a></li>";
                }
                $pageStruct = <<<EOT
    <ul class="pagination">
        <li>
            <a $firstPageHref aria-label="first">
                <span aria-hidden="true">$indexs</span>
            </a>
        </li>
        <li>
            <a $prePageHref aria-label="Previous">
                <span aria-hidden="true">$prevs</span>
            </a>
        </li>
        $pageNum
        <li>
            <a  $nextPageHref aria-label="Next">
                <span aria-hidden="true">$nexts</span>
            </a>
        </li>
        <li>
            <a $lastPageHref aria-label="last">
                <span aria-hidden="true">$ends</span>
            </a>
        </li>
         $page_info
    </ul>
EOT;
                break;
            case 2:
                $pageNum = '';
                for ($i = 1; $i <= $this->pageNum; $i++) {
                    $class = ($i == $this->currentPage) ? 'class="active"' : '';
                    $pageNum .= '<a ' . $class . ' ' . $this->setPageHref($i) . '>' . $i . '</a>';
                }
                $indexs = $this->lang[$this->language]['index'];
                $ends = $this->lang[$this->language]['end'];
                $prevs = $this->lang[$this->language]['prev'];
                $nexts = $this->lang[$this->language]['next'];
                $pageStruct = <<<EOT
            <div class="pagination">
                <a $firstPageHref >$indexs</a>
                <a $prePageHref>$prevs</a>
                $pageNum
                <a $nextPageHref>$nexts</a>
                <a $lastPageHref>$ends</a>
            </div>
EOT;
                break;
        }
        return $pageStruct;
    }

    private function setPageHref($p) {
        if ($p == $this->currentPage) {
            return 'href="javascript:void(0)"';
        }
        if ($this->ajax) {
            $href = 'href="javascript:void(0)" onclick="' . $this->ajaxfunction . '(' . $p . ')"';
        } else {
            $param = array_merge($this->get_request(), ['page' => $p]);
            $param_html = $param ? '?' . http_build_query($param) : '';
            $href = sprintf("href='%s'", \think\facade\Request::baseUrl().$param_html);
        }
        return $href;
    }

    private function get_request() {
        $data = $_REQUEST;
        foreach ($data as $k => $value) {
            if ($value === '') {
                unset($data[$k]);
            }
        }
        return $data;
    }

    /**
     * 获取分页信息
     * @return type
     */
    public function getPageInfo() {
        $pageinfo = array(
            'currentPage' => $this->currentPage,
            'pageSize' => $this->pageSize,
            'offset' => $this->offset,
            'pageNum' => $this->pageNum,
            'firstPgae' => $this->firstPage,
            'lastPage' => $this->lastPage,
            'prePage' => $this->prePage,
            'nextPage' => $this->nextPage,
            'pageStruct' => $this->pageStruct,
        );
        return $pageinfo;
    }

    public function getPageSize() {
        return $this->pageSize;
    }

    public function __toString() {
        // TODO: Implement __toString() method.
        return $this->getPageStruct();
    }

}
