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

namespace metacms\base;

class Page
{

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

    /**
     * @param $recordNum 记录总条数
     * @param int $currentPage 当前页
     * @param int $pageSize 每页条数
     * @param bool $ajax 是否开启ajax分页
     * @param string $ajaxfunction ajax分页js函数名
     */
    public function __construct($recordNum, $currentPage = 1, $pageSize = 20, $ajax = false, $ajaxfunction = 'ajaxPage')
    {
        $this->recordNum = $recordNum;      //记录总条数
        $this->currentPage = $currentPage;  //当前页
        $this->pageSize = $pageSize;        //每页条数
        $this->ajax = $ajax;                //是否开启ajax分页
        $this->ajaxfunction = $ajaxfunction;  //ajax分页js函数名
        $this->getOffset();
        $this->getPageNum();
        $this->getLastPage();
        $this->getPrePage();
        $this->getNextPage();
        $this->getPageStruct();
    }

    /**
     * 获取分页总页数
     * @return type
     */
    private function getPageNum()
    {
        $this->pageNum = ceil($this->recordNum / $this->pageSize);
    }

    /**
     * 获取分页偏移起始位置
     * @return type
     */
    public function getOffset()
    {
        $this->offset = ($this->currentPage - 1) * $this->pageSize;
        return $this->offset;
    }

    /**
     * 获取最后一页
     * @return type
     */
    private function getLastPage()
    {
        $this->lastPage = $this->pageNum;
    }

    /**
     * 获取上一页
     */
    private function getPrePage()
    {
        $this->prePage = ($this->currentPage == $this->firstPage) ? $this->firstPage : $this->currentPage - 1;
    }

    /**
     * 获取下一页
     */
    private function getNextPage()
    {
        $this->nextPage = ($this->currentPage == $this->lastPage) ? $this->lastPage : $this->currentPage + 1;
    }

    /**
     * 设置分页跳转js函数
     * @param type $page
     * @return type
     */
    private function setOperateFunc($page)
    {
        return $this->operateFunc . "($page)";
    }

    /**
     * 获取分页html结构,兼容bootstrap3
     * @return string
     */
    public function getPageStruct($type = 1)
    {

        $firstPageHref = $this->setPageHref($this->firstPage);
        $lastPageHref = $this->setPageHref($this->lastPage);
        $prePageHref = $this->setPageHref($this->prePage);
        $nextPageHref = $this->setPageHref($this->nextPage);

        $this->pageStruct = $this->getStruct($firstPageHref, $prePageHref, $nextPageHref, $lastPageHref, $type);
        return htmlspecialchars($this->pageStruct);
    }

    private function getStruct($firstPageHref, $prePageHref, $nextPageHref, $lastPageHref, $type = 1)
    {
        switch ($type) {
            case 1:
                $pageNum = '';
                for ($i = 1; $i <= $this->pageNum; $i++) {
                    $class = ($i == $this->currentPage) ? 'class="active"' : '';
                    $pageNum .= '<li ' . $class . '><a ' . $this->setPageHref($i) . '>' . $i . '</a></li>';
                }

                $pageStruct = <<<EOT
    <ul class="pagination">
        <li>
            <a $firstPageHref aria-label="first">
                <span aria-hidden="true">首页</span>
            </a>
        </li>
        <li>
            <a $prePageHref aria-label="Previous">
                <span aria-hidden="true">上一页</span>
            </a>
        </li>
        $pageNum
        <li>
            <a  $nextPageHref aria-label="Next">
                <span aria-hidden="true">下一页</span>
            </a>
        </li>
        <li>
            <a $lastPageHref aria-label="last">
                <span aria-hidden="true">尾页</span>
            </a>
        </li>
    </ul>
EOT;
                break;
            case 2:
                $pageNum = '';
                for ($i = 1; $i <= $this->pageNum; $i++) {
                    $class = ($i == $this->currentPage) ? 'class="active"' : '';
                    $pageNum .= '<a ' . $class . ' ' . $this->setPageHref($i) . '>' . $i . '</a>';
                }
                $pageStruct = <<<EOT
            <div class="pagination">
                <a $firstPageHref >首页</a>
                <a $prePageHref>上一页</a>
                $pageNum
                <a $nextPageHref>下一页</a>
                <a $lastPageHref>尾页</a>
            </div>
EOT;
                break;
        }
        return $pageStruct;
    }

    private function setPageHref($p)
    {
        if ($p == $this->currentPage) {
            return 'href="javascript:void(0)"';
        }
        $href = '';
        if ($this->ajax) {
            $href = 'href="javascript:void(0)" onclick="' . $this->ajaxfunction . '(' . $p . ')"';
        } else {

            $_get = $_GET;
            $_get['p'] = $p;
            $requestUrl = U(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME, $_get);
            $href = 'href="' . $requestUrl . '"';
        }
        return $href;
    }

    /**
     * 获取分页信息
     * @return type
     */
    public function getPageInfo()
    {
        $pageinfo = array(
            'currentPage' => $this->currentPage,
            'pageSize' => $this->pageSize,
            'offset' => $this->offset,
            'pageNum' => $this->pageNum,
            'firstPgae' => $this->firstPage,
            'lastPage' => $this->lastPage,
            'prePage' => $this->prePage,
            'nextPage' => $this->nextPage,
            'pageStruct' => htmlspecialchars($this->pageStruct),
        );
        return $pageinfo;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

}
