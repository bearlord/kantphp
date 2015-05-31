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
    public $config = array('preview' => 'Preview', 'next' => 'Next');
    //处理情况 Ajax分页 Html分页(静态化时) 普通get方式 
    protected $method = 'default';
    //ajax分页时函数
    protected $ajaxFunction = 'ajaxpage';

    // 分页显示页数
    const PAGE_ROLLPAGE = 8;
    // 分页每页显示记录数
    const PAGE_LISTROWS = 20;
    // 默认分页跳转变量
    const VAR_PAGE = 'page';

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

    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function setConfig($name, $value) {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 
     * @param type $name
     * @param type $value
     */
    protected function setParameter($value) {
        $this->parameter = $value;
    }

    /**
     * 
     * @param type $var
     */
    public function setMethod($var) {
        $this->method = $var;
    }

    /**
     * 
     * @param type $page
     * @param type $text
     * @return type
     */
    protected function getLink($page, $text) {
        switch ($this->method) {
            case 'ajax':
                $parameter = '';
                return '<li><a onclick="' . $this->ajaxFunction . '(\'' . $page . '\'' . $parameter . ')" href="javascript:void(0)">' . $text . '</a></li>';
                break;
            case 'html':
                $url = str_replace('?', $page, $this->parameter);
                return '<li><a  href="' . $page . '">' . $text . '</a></li>';
                break;
            default:
                return '<li><a href="' . $this->getUrl($page) . '">' . $text . '</a></li>';
                break;
        }
    }

    /**
     * 设置当前页面链接
     */
    protected function getUrl($page) {
        $url = $_SERVER['REQUEST_URI'];
        $parse = parse_url($url);
        if (isset($parse['query'])) {
            parse_str($parse['query'], $params);
            $params[self::VAR_PAGE] = $page;
            if (!empty($params)) {
                $url = $parse['path'] . '?' . http_build_query($params);
            } else {
                $url = $parse['path'] . '?' . http_build_query($params);
            }
        } else {
            $params[self::VAR_PAGE] = $page;
            $url = $parse['path'] . '?' . http_build_query($params);
        }
        return $url;
    }

    /**
     * 
     */
    public function show($theme = 'default') {
        if (0 == $this->totalRows) {
            return '';
        }
        $methodName = "show" . ucfirst($theme);
        if (method_exists($this, $methodName)) {
            $show = call_user_func(array($this, $methodName));
            return $show;
        }
    }

    /**
     * 
     */
    protected function showDefault() {
        $pageString = "";
        $linkPage = "";
        if ($this->totalPages <= 1) {
            return false;
        }
        $linkPage .= $this->previewPage();
        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($i == $this->nowPage) {
                $linkPage .= "<li class='active'><a>$i</a></li>";
            } else {
                if ($this->nowPage - $i >= 4 && $i != 1) {
                    $linkPage .="<li>...</li>";
                    $i = $this->nowPage - 3;
                } else {
                    if ($i >= $this->nowPage + 5 && $i != $this->totalPages) {
                        $linkPage .="<li><span>...</span></li>";
                        $i = $this->totalPages;
                    }
                    $linkPage .= $this->getLink($i, $i);
                }
            }
        }
        $linkPage .= $this->nextPage();
        $pageString = $linkPage;
        return $pageString;
    }

    /**
     * 
     * @param type $name
     * @return string
     */
    protected function previewPage($name = '') {
        if ($name == '') {
            $name = $this->config['preview'];
        }
        if ($this->totalRows != 0) {
            return $this->getLink($this->nowPage - 1, $name);
        }
    }

    /**
     * 
     */
    protected function nextPage($name = '') {
        if ($name == '') {
            $name = $this->config['next'];
        }
        if ($this->nowPage < $this->totalPages) {
            return $this->getLink($this->nowPage + 1, $name);
        }
    }

}

?>