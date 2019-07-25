<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\model\Cate as CateModel;
use app\admin\model\Article as ArticleModel;

class Cate extends Common
{
    protected $beforeActionList = [
    //只有执行del方法，才会执行delsoncate方法
    //执行del方法之前，执行delsoncate方法
        'delsoncate' => ['only' => 'del'],
    ];

    //分类显示：
    public function lst()
    {
        $cate = new CateModel();
        $res = $cate->catetree();
        $this->assign('res',$res);
        return $this->fetch();
    }

    //添加分类：
    public function add()
    {
        $cate = new CateModel();
        //判断是否提交表单
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Cate');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $add = $cate->save($data);
            if($add){
                $this->success('添加栏目成功！',url('lst'),2);
            }else {
                $this->error('添加栏目失败！',url('lst'),2);
            }
        }

        $res = $cate->catetree();
        $this->assign('res',$res);
        return $this->fetch();
    }

    //删除栏目
    public function del()
    {
        $del = db('cate')->delete(input('id'));
        if($del){
            $this->success('删除栏目成功！',url('lst'));
        }else{
            $this->error('删除栏目失败！',url('lst'));
        }
    }

    //删除子栏目
    public function delsoncate()
    {
        $cateid = input('id');
        $cate = new CateModel();
        $sonids = $cate->getchilrenid($cateid);
        $allcateid = $sonids;
        $allcateid[] = $cateid;//-----------------[]
        foreach ($allcateid as $key => $v){
            $article = new ArticleModel();
            $article->where(array('cateid'=>$v))->delete();
        }
        if($sonids){
            db('cate')->delete($sonids);
        }
    }

    //编辑栏目
    public function edit()
    {
        $cate = new CateModel();
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Cate');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $save = db('cate')->update($data);
            if($save !== false){
                $this->success('修改栏目成功！',url('lst'));
            }else{
                $this->error('修改栏目失败！',url('lst'));
            }
            return;//////////////////
        }
        $cates = $cate->find(input('id'));
        $cateres = $cate->catetree();
        $this->assign(array(
                'cates' => $cates,
                'cateres' => $cateres,
            ));
        return view();
    }
}
