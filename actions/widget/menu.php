<?php

class WidgetMenu extends Widget{

    /**
     * @param array $params
     * @return array()
     */
    function execute($params = array())  {
        $menus = [
            ['网站首页', $this->caller->url()],
            ['自驾活动', $this->url('news', 'cid=zjhd')],
            ['线路策划', $this->url('product', 'cid=7')],
            ['订房服务', $this->url('about', 'id=dffw')],
            ['自驾游记', $this->url('news', 'cid=zjyj')],
            ['旅途景色', $this->url('news', 'cid=ltjs')],
            ['会员福利', $this->url('about', 'id=hyfl')],
            ['特价旅游', $this->url('product', 'cid=8')],
            ['俱乐部介绍', $this->url('about', 'id=about')],
            ['留言板', $this->url('feedback')],
        ];
        return array(
            'items' => $menus
        );
    }
}