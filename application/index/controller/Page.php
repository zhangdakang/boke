<?php
namespace app\index\controller;
use app\index\controller\Common;

class Page extends Common
{
    public function page()
    {
        $page = db('cate')->find(input('cateid'));
        $cate = new \app\index\model\Cate();
        $cateBottom = $cate->getCateInfo(input('cateid'));
        $this->assign(array(
            'page'=>$page,
            'cateBottom'=>$cateBottom,
            ));
    	return $this->fetch();
    }

}