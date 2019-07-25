<?php
namespace app\index\controller;
use app\index\controller\Common;
class Huiyuan extends Common
{
    public function qqlogin()
    {
        $appid = "101530573";
        //回调地址
        $redirect_uri = urlencode("http://zhangdakang.cn/index.php/index/huiyuan/token");
        $url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=".$appid."&redirect_uri=".$redirect_uri."&scope=get_user_info&state=text";
        //跳转到$url
        //Step1：获取Authorization Code
        $this->success('$url');
        //header("location".$url);
    }

    public function token()
    {
        $code = $_GET['code'];
        dump($code);exit;
    }
}