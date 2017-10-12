<?php

/**
 * @example 
 * 
     require(SYSTEM_CLASS_PATH . "pagesClass/pager.class.php");
     $pager = new Pager(); // default display 20 record per page, change it use $pager->setNums($nums)
     
     ......
     
     $sql = "
        SELECT  ...  
        FROM ...
        WHERE .. 
        ORDER BY ...
        LIMIT $pager->start, $pager->nums
    ";
    $list = $dbTools->getCacheResult($sql, EXPIRE_TIME);
    
     ......

     $pageInfo = $pager->getHtml($sql);
 *
 */

class Mpager
{
    var $total;
    
    var $start;
    
    var $nums;
    
    var $currentPage;
    
    var $pageCount;
    
    var $displayPages = 10;
    
    var $parameterName = "page";
    
    var $_url;
    
    var $_db;
    
    /**
     * Enter description here...
     *
     * @param int $nums 
     * @param int $currentPag 
     * @return Pager
     */
    function Mpager($nums = 20)
    {
        
        $this->setCurrentPage($currentPag);
        $this->setNums($nums);
        $this->_url = $_SERVER['REQUEST_URI'];
        
        // set dbo object
        global $db, $dbTools;
        if(is_object($dbTools)){
            $this->_db = & $dbTools;
        }elseif ($db) {
            $this->_db = & $db;
        }
    }
    
    /**
     * 设置当前页码
     *
     * @param int $currentPag
     */
    function setCurrentPage($currentPag)
    {
        if(!is_numeric($currentPag)){
            $currentPage = $this->getDefaultCurrentPag();
        }
        $this->currentPage = $currentPage;
        if($this->currentPage <= 0){
            $this->currentPage = 1;
        }
    }
    
    /**
     * 从地址获取默认当前页
     *
     * @return int
     */
    function getDefaultCurrentPag()
    {
        return intval($_GET[$this->parameterName]);
    }
    
    /**
     * 设置每页显示数量
     *
     * @param int $nums
     */
    function setNums($nums)
    {
        $this->nums = intval($nums);
        if($this->nums <= 0){
            $this->nums = 20;
        }
        
        $this->start = ($this->currentPage - 1) * $this->nums;
        $this->pageCount = ceil($this->total/$this->nums);
    }
    
    /**
     * 设置总记录数
     *
     * @param mixd $total 数字或sql语句
     */
    function setTotal($total)
    {
        if(!is_numeric($total) && is_string($total)){
            if(!preg_match('/^\s*select\s+/si', $total)){
                die(__FILE__ . "(" . __LINE__ . "): '{$total}' is invalid sql");
            }
            $sql = preg_replace(
                '/^\s*select\s+(?:.+?)\sfrom\s(.+?)\swhere(.+?)\s(?:order|limit).+/si',
                'SELECT COUNT(*) FROM $1 WHERE $2',
                $total
            );
            
            if(preg_match('/\sgroup\s/si', $sql)){
                $sql = preg_replace(
                    '/SELECT (?:.+) FROM (.+)\sGROUP BY\s(.+)\s*/si',
                    'SELECT COUNT(DISTINCT $2) FROM $1',
                    $sql
                );
            }

            if(is_object($this->_db) && method_exists($this->_db, "getOne")){
                $total = $this->_db->getOne($sql);
            }else{
                list($total) = @mysql_fetch_array(@mysql_query($sql));
            }
        }
        
        $this->total = intval($total);
        $this->pageCount = ceil($this->total/$this->nums);
    }
    
    /**
     * 设置分页地址
     *
     * @param int $page
     * @return string 
     */
    function setPage($page)
    {
        if(strpos($this->_url, "?") === false){
            return "{$this->_url}?{$this->parameterName}={$page}";
        }
        $search = "/([\?&]{$this->parameterName}=)(\d*)/i";
        if($page > 1){
            $replace = '${1}' . $page ;
        } else {
            $replace = null;
        }
        
        if(preg_match($search, $this->_url)){
            return preg_replace($search, $replace, $this->_url);
        }else{
            return $this->_url . "&{$this->parameterName}={$page}";
        }
    }
    
    /**
     * 获取分页信息
     *
     * @return string
     */
    function getHtml($total = '')
    {
        if($total){
            $this->setTotal($total);
        }
        
        $pageBuffer = "";
        // get pageInfo forward
        if($this->currentPage > 1 ){
            $pageBuffer .= "<li><a href=\"" . $this->setPage(1) . "\">首页</a></li>\n";
            $pageBuffer .= "&nbsp;&nbsp;<li><a href=\"" . $this->setPage($this->currentPage - 1) . "\">上一页</a></li>\n";
        }
        
        // get pageInfo center
        $nextnum = ceil($this->currentPage / $this->displayPages) * $this->displayPages;
        if($nextnum == $this->currentPage){
            $nextnum = $this->currentPage + $this->displayPages;
        }
        if($nextnum > $this->pageCount){
            $nextnum = $this->pageCount;
        }
        $nextstart = $nextnum - $this->displayPages;
        if($nextstart <=0 ){
            $nextstart = 1;
        }
        
        for($i = $nextstart; $i <= $nextnum; $i++){
            if($i == $this->currentPage){
                $pageBuffer .= " <li class=\"thisclass\">$i</strong></li>\n";
            }else{
                $pageBuffer .= " <li><a href=\"" . $this->setPage($i) . "\">{$i}</a></li> \n";
            }
        }
        if($totalPage > $nextnum){
            $pageBuffer .= "...";
        }

        // get pageInfo end
        if($this->currentPage < $this->pageCount){
            $pageBuffer .= " <li>&nbsp;&nbsp;<a href=\"" . $this->setPage($this->currentPage + 1) . "\">下一页</a> </li>\n";
            $pageBuffer .= " <li>&nbsp;&nbsp;<a href=\"" . $this->setPage($this->pageCount) . "\">尾页</a> </li>\n";
        }

        // get count pageInfo
        if ($this->currentPage >= $this->pageCount){
            $endId = $this->start + $this->total % $this->nums;
        }else{
            $endId = $this->start + $this->nums;
        }
       // $pageBuffer .=  "<p style=\"padding-top: 5px;\">共{$this->total}条, 当前显示{$this->start} -- {$endId}条, 共{$this->pageCount}页</p>\n";

        return $pageBuffer;
    }
}

/*
$pager = new Pager();
echo $pager->start . "--" . $pager->nums . "<br />";
echo $pager->getHtml(205);
*/

?>