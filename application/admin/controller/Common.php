<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;

class Common extends Controller
{
    public function _initialize(){
        if(!session('id') || !session('name')){
            $this->error('你还没有登录系统！',url('login/login'));
        }
        $auth = new Auth();
        $request = Request::instance();
        $con = $request->controller();
        $action = $request->action();
        $name = $con.'/'.$action;
        //转换为小写的字符串
        // $name = strtolower($name);
        $notcheck = array('Index/index','Admin/logout');
        //如果不是超级管理员
        if(session('id')!=1){
            if(!in_array($name,$notcheck)){
                if(!$auth->check($name,session('id'))){
                $this->error('没有权限访问',url('index/index'));
                }
            }

        }
    }

}
