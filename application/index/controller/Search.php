<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\Article as ArticleModel;
class Search extends Common
{
    public function index()
    {
    $keywords = input('keywords');
    $serRes=db('article')->order('id desc')->where('title','like','%'.$keywords.'%')->paginate(2,false,$config = ['query'=>array('keywords'=>$keywords)]);
    //热门文章
    $article = new ArticleModel();
    $allHotArt = $article->getAllHot();
    $this->assign(array(
        'serRes'=>$serRes,
        'allHotArt'=>$allHotArt,
        'keywords'=>$keywords,
        ));
    return view('search');
    }

}
