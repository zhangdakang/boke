<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\model\Link as LinkModel;

class Link extends Common
{
    //分类显示：
    public function lst()
    {
        $link = new LinkModel();
        $res = $link->order('id')->paginate(2);
        $this->assign('res',$res);
        return $this->fetch();
    }

    //添加分类：
    public function add()
    {
        //判断是否提交表单
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Link');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $link = new LinkModel();
            $add = $link->save($data);
            if($add){
                $this->success('添加友情链接成功！',url('lst'),2);
            }else {
                $this->error('添加友情链接失败！',url('lst'),2);
            }
        }

        return $this->fetch();
    }

    //删除栏目
    public function del()
    {
        $del = db('link')->delete(input('id'));
        if($del){
            $this->success('删除友情链接成功！',url('lst'));
        }else{
            $this->error('删除友情链接失败！',url('lst'));
        }
    }

    //编辑栏目
    public function edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Link');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $save = db('link')->update($data);

            //用($save !== false)，不应用($save),因为没有修改的话，返回0，用($save),则会修改失败
            if($save !== false){
                $this->success('修改友情链接成功！',url('lst'));
            }else{
                $this->error('修改友情链接失败！',url('lst'));
            }
            return;//////////////////
        }
        $link = new LinkModel();
        $links = $link->find(input('id'));
        $this->assign('links',$links);
        return view();
    }
}
