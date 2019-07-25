<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\model\AuthRule as AuthRuleModel;

class AuthRule extends Common
{
    //分类显示：
    public function lst()
    {
        $authRule = new AuthRuleModel();
        if(request()->isPost()){
            $sorts = input('post.');
            foreach ($sorts as $k => $v) {
                $authRule->update(['id'=>$k,'sort'=>$v]);
            }
            $this->success('更新排序成功！',url('lst'));
            return;
        }
        $res = $authRule->authRuleTree();
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
            $plevel = db('auth_rule')->where('id',$data['pid'])->field('level')->find();
            if($plevel){
                $data['level'] = $plevel['level']+1;
            }else{
                $data['level'] = 0;
            }
            // dump($plevel['level']);dump($data);die;
            $authRule = new AuthRuleModel();
            $add = $authRule->save($data);
            if($add){
                $this->success('添加权限成功！',url('lst'),2);
            }else {
                $this->error('添加权限失败！',url('lst'),2);
            }
        }

        $authRule = new AuthRuleModel();
        $res = $authRule->authRuleTree();
        $this->assign('res',$res);
        return $this->fetch();
    }

    //删除栏目
    public function del()
    {
        $authRule = new AuthRuleModel();
        $authRuleId = $authRule->getchilrenid(input('id'));
        //值赋值给数组形式
        $authRuleId[] = input('id');
        $del = db('auth_rule')->delete($authRuleId);
        if($del){
            $this->success('删除权限成功！',url('lst'));
        }else{
            $this->error('删除权限失败！',url('lst'));
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

            $plevel = db('auth_rule')->where('id',$data['pid'])->field('level')->find();
            if($plevel){
                $data['level'] = $plevel['level']+1;
            }else{
                $data['level'] = 0;
            }

            $save = db('auth_rule')->update($data);

            //用($save !== false)，不应用($save),因为没有修改的话，返回0，用($save),则会修改失败
            if($save !== false){
                $this->success('修改权限成功！',url('lst'));
            }else{
                $this->error('修改权限失败！',url('lst'));
            }
            return;//////////////////
        }
        $authr = new AuthRuleModel();
        $res = $authr->find(input('id'));
        $authres = $authr->authRuleTree();
        $this->assign([
            'res'=>$res,
            'authres'=>$authres
            ]);
        return view();
    }
}
