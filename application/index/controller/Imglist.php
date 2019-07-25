<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\Article as ArticleModel;

class Imglist extends Common
{
    public function imglist()
    {
    	$articles = new ArticleModel();
        $artRes = $articles->getAllArticle(input('cateid'));
        $cate = new \app\index\model\Cate();
        $cateBottom = $cate->getCateInfo(input('cateid'));
        $this->assign(array(
            'artRes'=>$artRes,
            'cateBottom'=>$cateBottom,
            ));
        return $this->fetch();
    }

}