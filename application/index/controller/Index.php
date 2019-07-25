<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\Article as ArticleModel;
use think\cache\driver\Redis;

class Index extends Common
{
    public function index()
    {
        $article = new ArticleModel();
        //最新文章调用
        $newArts = $article->getNewArticle();
        //热门文章
        $allHotArt = $article->getAllHot();
        //友情链接
        $links = db('link')->select();
        //轮转图
        $rec = $article->getRecArt();
        //首页推荐
        $cate = new \app\index\model\Cate();
        $cateIndex = $cate->getCateIndex();
        $this->assign(array(
            'newArts'=>$newArts,
            'allHotArt'=>$allHotArt,
            'links'=>$links,
            'rec'=>$rec,
            'cateIndex'=>$cateIndex,
            ));
    	return $this->fetch();
    }

    //
    //mobile推荐页面
    public function tuijian()
    {
      $article = new ArticleModel();
      //最新文章调用
      $newArts = $article->getNewArticle();
      //热门文章
      $HotArt = $article->getAllHot();
      //轮转图--推荐文章
      $rec = $article->getRecArt();
      $this->assign(array(
            'newArts'=>$newArts,
            'HotArt'=>$HotArt,
            'rec'=>$rec,
            ));
      return $this->fetch();
    }

    //mobile分类页面
    public function category()
    {
       $cateres = db('cate')->where(array('pid'=>0))->select();
        foreach ($cateres as $k => $v) {
            $children = db('cate')->where(array('pid'=>$v['id']))->select();
            if($children){
                $cateres[$k]['children'] = $children;
            }else{
                $cateres[$k]['children'] = 0;
            }
        }
        $this->assign('cateres',$cateres);
        //dump($cateres);die;
        return $this->fetch();
    }





}