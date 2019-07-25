<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\model\Admin as AdminModel;


class Admin extends Common
{
    public function lst()
    {
        $auth = new Auth();
        $admin = new AdminModel();
        $res = $admin->showadmin();
        foreach ($res as $k => $v) {
            $group = $auth->getGroups($v['id']);
            $groupTitle = $group[0]['title'];
            $v['groupTitle'] = $groupTitle;
        }
        //dump($groupTitle);die;
        $this->assign('res',$res);
        return $this->fetch();
    }

    public function add()
    {
        //判断是否提交表单
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Admin');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $admin = new AdminModel();
            if($admin->addadmin($data)){
                $this->success('添加管理员成功！',url('lst'),2);
            }else {
                $this->error('添加管理员失败！',url('lst'),2);
            }
            return;
        }
        $authGroup = db('auth_group')->select();
        $this->assign('authGroup',$authGroup);
        return $this->fetch();
    }

    public function edit($id)
    {
        //没有模型实例化，用助手函数db
        $admins = db('admin')->find($id);
        $data = input('post.');
        //判断是否是提交表单
        if(request()->isPost()){
            $validate = \think\Loader::validate('Admin');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $admin = new AdminModel();
            if($admin->editadmin($data,$admins)!==false){
                $this->success("修改管理员成功！",url('lst'));
            }else{
                $this->error("修改管理员失败！");
            }
            return;
        }
        if(!$admins){
            $this->error("管理员不存在！");
        }

        $groupAccess = db('auth_group_access')->where(array('uid'=>$id))->find();
        $authGroup = db('auth_group')->select();
        $this->assign(array(
            'authGroup'=>$authGroup,
            'admin'=>$admins,
            'groupId'=>$groupAccess['group_id']
            ));
        return $this->fetch();
    }

    public function del($id)
    {
        $admin = new AdminModel();
        $res = $admin->deladmin($id);
        if($res == true){
            $this->success("删除管理员成功！");
        }else{
            $this->error("删除管理员失败！");
        }
    }

    public function logout()
    {
        session(null);
        $this->success('退出成功！',url('login/login'));
    }
}
