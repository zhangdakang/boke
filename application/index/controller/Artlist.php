<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\Article as ArticleModel;

class Artlist extends Common
{
    public function artlist()
    {
        $articles = new ArticleModel();
        $artRes = $articles->getAllArticle(input('cateid'));
        $artHot = $articles->getHotArticle(input('cateid'));
        $cate = new \app\index\model\Cate();
        $cateBottom = $cate->getCateInfo(input('cateid'));
        $this->assign(array(
            'artRes'=>$artRes,
            'artHots'=>$artHot,
            'cateBottom'=>$cateBottom,
            ));
    	return $this->fetch();
    }

}