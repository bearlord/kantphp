<?php

class Page extends Base {

    // 起始行数
    public $firstRow;
    // 列表每页显示行数
    public $listRows;
    // 页数跳转时要带的参数
    public $parameter;
    // 分页总页面数
    protected $totalPages;
    // 总行数
    protected $totalRows;
    // 当前页数
    protected $nowPage;
    // 分页的栏的总页数
    protected $coolPages;
    // 分页栏每页显示的页数
    protected $rollPage;
    // 分页显示定制
    public $config = array('header' => 'Results', 'prev' => 'Preview', 'next' => 'Next', 'first' => 'First', 'last' => 'Last', 'page' => '', 'theme' => ' %totalRow% %header% %nowPage%/%totalPage% Pages %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

    const PAGE_ROLLPAGE = 8; // 分页显示页数
    const PAGE_LISTROWS = 20; // 分页每页显示记录数
    const VAR_PAGE = 'page'; // 默认分页跳转变量

    /**
      +----------------------------------------------------------
     * 架构函数
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
      +----------------------------------------------------------
     */

    public function __construct($totalRows, $listRows, $parameter = '') {
        parent::__construct();
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->rollPage = self::PAGE_ROLLPAGE; // 默认分页跳转变量
        $this->listRows = !empty($listRows) ? $listRows : self::PAGE_LISTROWS; // 分页显示页数
        $this->totalPages = ceil($this->totalRows / $this->listRows);  //总页数
        $this->coolPages = ceil($this->totalPages / $this->rollPage);
        $this->nowPage = !empty($_GET[self::VAR_PAGE]) ? $_GET[self::VAR_PAGE] : 1;
        if (!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows * ($this->nowPage - 1);
    }

    public function setConfig($name, $value) {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
      +----------------------------------------------------------
     * 分页显示输出
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function show($onclick = '') {
        if ($onclick) {
            $onclick = ' onclick="return ' . $onclick . '";';
        }
        if (0 == $this->totalRows)
            return '';
        $p = 'page';
        $nowCoolPage = ceil($this->nowPage / $this->rollPage);
        $url = $this->pageUrl();
        //上下翻页字符串
        $upRow = $this->nowPage - 1;
        $downRow = $this->nowPage + 1;
        if ($upRow > 0) {
            $upPage = "<a href='" . $this->pageUrl($upRow) . "' $onclick>" . $this->config['prev'] . "</a>";
        } else {
            $upPage = "";
        }

        if ($downRow <= $this->totalPages) {
            $downPage = "<a href='" . $this->pageUrl($downRow) . "' $onclick>" . $this->config['next'] . "</a>";
        } else {
            $downPage = "";
        }
        // << < > >>
        if ($nowCoolPage == 1) {
            $theFirst = "";
            $prePage = "";
        } else {
            $preRow = $this->nowPage - $this->rollPage;
            $prePage = "<a href='" . $this->pageUrl($preRow) . "'>" . $this->config['prev'] . $this->rollPage . $this->config['page'] . "</a>";
            $theFirst = "<a href='" . $this->pageUrl(1) . "' >" . $this->config['first'] . "</a>";
        }
        if ($nowCoolPage == $this->coolPages) {
            $nextPage = "";
            $theEnd = "";
        } else {
            $nextRow = $this->nowPage + $this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='" . $this->pageUrl($nextRow) . "'>" . $this->config['next'] . $this->rollPage . $this->config['page'] . "</a>";
            $theEnd = "<a href='" . $this->pageUrl($theEndRow) . "' >" . $this->config['last'] . "</a>";
        }
        // 1 2 3 4 5
        $linkPage = "<span>";
        $startRollPage = ($nowCoolPage - 1) * $this->rollPage;
        $endRollPage = $nowCoolPage * $this->rollPage;

        if ($this->nowPage - $startRollPage < 3) {
            for ($i = 3; $i >= 1; $i--) {
                $page = $this->nowPage - $i;
                if ($page > 1) {
                    $linkPage .= "<a href='" . $this->pageUrl($page) . "' $onclick>" . $page . "</a>&nbsp;";
                }
            }
        }
        for ($i = 1; $i <= $this->rollPage; $i++) {
            $page = ($nowCoolPage - 1) * $this->rollPage + $i;
            if ($page != $this->nowPage) {
                if ($page <= $this->totalPages) {
                    $linkPage .= "<a href='" . $this->pageUrl($page) . "' $onclick>" . $page . "</a>&nbsp;";
                } else {
                    break;
                }
            } else {
                if ($this->totalPages != 1) {
                    $linkPage .= "<a class='active' $onclick>" . $page . "</a>&nbsp;";
                }
            }
        }
        if ($endRollPage - $this->nowPage <= 3) {
            for ($i = 1; $i <= 3; $i++) {
                $page = $endRollPage + $i;
                if ($page <= $this->totalPages) {
                    $linkPage .= "<a href='" . $this->pageUrl($page) . "' $onclick>" . $page . "</a>&nbsp;";
                }
            }
        }
        $linkPage .= "</span>";
        $pageStr = str_replace(
                array('%header%', '%nowPage%', '%totalRow%', '%totalPage%', '%upPage%', '%downPage%', '%first%', '%prePage%', '%linkPage%', '%nextPage%', '%end%'), array($this->config['header'], $this->nowPage, $this->totalRows, $this->totalPages, $upPage, $downPage, $theFirst, $prePage, $linkPage, $nextPage, $theEnd), $this->config['theme']);
        return $pageStr;
    }

    private function pageUrl($page = '') {
        $m = $this->get['module'];
        $c = $this->get['ctrl'];
        $a = $this->get['act'];
        $extend = array();
        foreach ($this->get as $k => $v) {
            if (in_array($k, array('module', 'ctrl', 'act', 'page')) == false) {
                $extend[$k] = $v;
            }
        }
        $MCA = !empty($m) ? sprintf("%s/%s/%s", $m, $c, $a) : sprintf("%s/%s", $c, $a);
        if (!empty($page)) {
            $_page = array(
                'page' => $page
            );
            $extend = array_merge($extend, $_page);
        };
        $pageurl = $this->url($MCA, $extend);
        return $pageurl;
    }

}

?>