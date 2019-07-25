<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule;

class AuthGroup extends Common
{
    //分类显示：
    public function lst()
    {
        $authg = new AuthGroupModel();
        $res = $authg->order('id')->paginate(4);
        $this->assign('res',$res);
        return $this->fetch();
    }

    //添加分类：
    public function add()
    {
        //判断是否提交表单
        if(request()->isPost()){
            $data = input('post.');
            // $validate = \think\Loader::validate('Link');
            // if(!$validate->scene('add')->check($data)){
            //     $this->error($validate->getError());
            // }
            $_sta = array();
            foreach ($data as $k => $v) {
                $_sta[] = $k;
            }
            if(in_array('rules', $_sta)){
                $data['rules'] = implode(',',$data['rules']);
            }
            $authg = new AuthGroupModel();
            $add = $authg->save($data);
            if($add){
                $this->success('添加用户组成功！',url('lst'),2);
            }else {
                $this->error('添加用户组失败！',url('lst'),2);
            }
            return;
        }

        $authRule = new AuthRule();
        $authRuleRes = $authRule->authRuleTree();
        $this->assign('authRuleRes',$authRuleRes);
        return $this->fetch();
    }

    //删除栏目
    public function del()
    {
        $del = db('auth_group')->delete(input('id'));
        if($del){
            $this->success('删除用户组成功！',url('lst'));
        }else{
            $this->error('删除用户组失败！',url('lst'));
        }
    }

    //编辑栏目
    public function edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            // $validate = \think\Loader::validate('Link');
            // if(!$validate->scene('edit')->check($data)){
            //     $this->error($validate->getError());
            // }

            // 启动状态
            $_sta = array();
            foreach ($data as $k => $v) {
                $_sta[] = $k;
            }
            if(!in_array('status', $_sta)){
                $data['status'] = 0;
            }

            //权限选定状态
            if(in_array('rules', $_sta)){
                $data['rules'] = implode(',',$data['rules']);
            }else{
                $data['rules'] = "";
            }
            //dump($data);die;

            $save = db('auth_group')->update($data);

            //用($save !== false)，不应用($save),因为没有修改的话，返回0，用($save),则会修改失败
            if($save !== false){
                $this->success('修改用户组成功！',url('lst'));
            }else{
                $this->error('修改用户组失败！',url('lst'));
            }
            return;//////////////////
        }
        $authg = new AuthGroupModel();
        $authgs = $authg->find(input('id'));
        $authRule = new AuthRule();
        $authRuleRes = $authRule->authRuleTree();
        $this->assign(array(
            'authRuleRes'=>$authRuleRes,
            'authgs'=>$authgs,
            ));
        return view();
    }
}
