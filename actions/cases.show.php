<?php
loadModule('casescat');
loadModule('certcat');
loadModule('cert');
class ActionCasesShow extends BaseAction{
    function execute() {
        /** @var goods $news */
        $news = getDTable('cases');
        /** @var GoodsCat $cat */
        $cat = getDTable('casesCat');

        $id = intval($_REQUEST['id']);
        $cat = getCasecat();
        $news->increment('hits', $id);

        $this->info = $news->getRow($id);
        $this->catName = $cat->getName($this->info['cid']);
        $this->getPath()->addItem($this->catName, $this->url('product', 'cid=' . $this->info['cid']));
        $this->getSeo()->setVar('title', $this->info['title']);
        $this->cat = $cat;
        $paihang = $news->getList(9, 'order by hits desc');
        $this->paihang = $paihang;
        //shejishi
        $cert = new Cert();
        $certcat = new Certcat();
        $jiebie = Cert::getJiebie();
        $bumen = Cert::getBumen();
        $cert_info = $cert->getRow($this->info['shejishi']);
        $this->cert_info = $cert_info;
        $this->cert = $cert;
        $this->certcat = $certcat;
        $this->jiebie = $jiebie;
        $this->jingyan = $jingyan;
        $this->bumen = $bumen;
        
        $info_id = $this->info['id'];
        $this->next = $news->getRow(' And id > ' .$info_id.' order by id asc limit 1');
        $this->upAc = $news->getRow('And id < ' .$info_id.' order by id desc limit 1');
        $this->max = $news->getFiled('max(id)',array());
        $this->min = $news->getFiled('min(id)',array());
    }
}