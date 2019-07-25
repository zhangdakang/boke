<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;

class Login extends Controller
{
    public function login()
    {
        if(request()->isPost()){
            $this->check(input('code'));
            $admin = new Admin();
            $res = $admin->checklogin(input('post.'));
            if($res == 1){
                $this->success('登录成功！',url('index/index'));
            }
            if($res == 2){
                $this->error('密码错误！',url('login'));
            }
            if($res == 3){
                $this->error('用户不存在！',url('login'));
            }
            return;
        }
        return $this->fetch();
    }

    public function check($code)
    {
        if(!captcha_check($code)){
            $this->error('验证码错误！',url('login'));
        }else{
            return true;
        }
    }
}
