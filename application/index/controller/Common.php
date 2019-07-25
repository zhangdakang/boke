<?php
namespace app\index\controller;
use think\Controller;

class Common extends Controller
{
    public function _initialize(){

        //判断是否是移动端
        if($this->request->isMobile()===true){
            $this->view->config('view_path',ROOT_PATH.'public/mobile/');
        }

        if(input('cateid')){
            $this->getPos(input('cateid'));
        }
        if(input('artid')){
            $articles = db('article')->field('cateid')->find(input('artid'));
            $cateid = $articles['cateid'];
            $this->getPos($cateid);
        }

        if(session('id')!==''){
            $user_info = db('huiyuan')->where('id',session('id'))->field('username,image')->find();
            $this->assign('usermess',$user_info);
        }

        //底部推荐
        $cate = new \app\index\model\Cate();
        $cateBottom = $cate->getCateBottom();
        $this->assign('cateBottom',$cateBottom);

        $this->getConf();
        $this->getNavCates();
    }

    //网站栏目导航
    public function getNavCates()
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
    }

    //网站配置项
    public function getConf()
    {
        $conf = new \app\index\model\Conf();
        $_confres = $conf->getAllConf();
        $confres = array();
        foreach ($_confres as $k => $v) {
            $confres[$v['enname']] = $v['value'];
        }
        // dump($confres);die;
        $this->assign('confres',$confres);
    }

    //当前位置
    public function getPos($cateid)
    {
        $cate = new \app\index\model\Cate();
        $posArr = $cate->getparents($cateid);
        $this->assign('posArr',$posArr);
        // dump($posArr);die;
    }
}
