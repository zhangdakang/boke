<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\Huiyuan as HuiyuanModel;
class Huiyuan extends Common
{
    public function qqlogin()
    {
        $app_id = "101530573";
        //回调地址
        $redirect_uri = urlencode("http://zhangdakang.cn/index.php/index/huiyuan/token");
        $url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=".$app_id."&redirect_uri=".$redirect_uri."&scope=get_user_info&state=text";
        //跳转到$url

        //Step1：获取Authorization Code

        // header("location".$url);
        $this->redirect($url);
    }

    public function token()
    {
        // $code = $_GET['code'];
        // dump($code);exit;
        // appid
        $app_id = "101530573";
        //appkey
        $app_secret = "bf5ad22a433ece66babedf69320ac0df";
        //成功授权后的回调地址
        $my_url = urlencode("http://zhangdakang.cn/index.php/index/huiyuan/token");
        //获取code
        $code = $_GET['code'];

        //Step2：通过Authorization Code获取Access Token

        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=".$app_id."&redirect_uri=".$my_url."&client_secret=".$app_secret."&code=".$code."";
        //file_get_contents() 把整个文件读入一个字符串中。
        $response = file_get_contents($token_url);

        //Step3:在上一步获取的Access Token，得到对应用户身份的OpenID。

        $params = array();
        //parse_str() 函数把查询字符串（'a=x&b=y'）解析到变量中。
        parse_str($response,$params);
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$params['access_token']."";
        $str = file_get_contents($graph_url);
        // dump($str);die;
        // --->找到了字符串：callback( {"client_id":"YOUR_APPID","openid":"YOUR_OPENID"} )
        //
        // strpos() 函数查找字符串在另一字符串中第一次出现的位置，从0开始
        if(strpos($str,"callback")!==false)
        {
            $lpos = strpos($str,"(");
            // strrpos() 函数查找字符串在另一字符串中最后一次出现的位置。
            $rpos = strrpos($str,")");
            //substr(string,start,length) 截取字符串某一位置
            $str = substr($str,$lpos+1,$rpos-$lpos-1);
        }
        // json_decode() 函数用于对 JSON 格式-->{"a":1,"b":2,"c":3,"d":4,"e":5}<--的字符串进行解码，并转换为 PHP 变量,默认返回对象
        $user = json_decode($str);
        // dump($user->openid);die;
        session('openid',$user->openid);

        //Step4: 调用OpenAPI接口,得到json数据，要转换为数组

        $huiyuan = "https://graph.qq.com/user/get_user_info?access_token=".$params['access_token']."&oauth_consumer_key=".$app_id."&openid=".$user->openid."";
        //加上true，得到数组，否则默认得到对象
        $res = json_decode(file_get_contents($huiyuan),true);
        // dump($res['nickname']);dump($res);die;
        $re = db('huiyuan')->where(array('openid'=>$user->openid))->find();
        //如果没有找到，进行注册
        if(!$re){
            if($res['gender']=="男"){
                $res['gender'] = 1;
            }else{
                $res['gender'] = 2;
            }
            $data = [
                'openid'=>$user->openid,
                'username'=>$res['nickname'],
                'image'=>$res['figureurl_qq_2'],
                'sex'=>$res['gender'],
                'year'=>$res['year'],
                'province'=>$res['province'],
                'city'=>$res['city'],
            ];
            $r = db('huiyuan')->insert($data);
            if($r){
                $this->success('注册成功！',url('index/index'));
            }else{
                $this->error('注册失败！');
            }
        }else{
            $this->redirect('login_qq');
        }
    }

    public function login_qq()
    {
        $res = db('huiyuan')->where('openid',session('openid'))->find();
        if(!$res){
            $this->error('不是本站会员！');
        }else{
            session('id',$res['id']);
            $this->success('登录成功！','index/index');

        }
    }

    public function logout()
    {
        session(null);
        $this->success('退出成功！',url('index/index'));
    }

    public function index()
    {
        $res = db('huiyuan')->where('id',session('id'))->find();
        $this->assign('res',$res);
        return $this->fetch();
    }

    public function edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            //dump($data);die;
            $validate = \think\Loader::validate('Huiyuan');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            $huiyuan = new HuiyuanModel();
            $result = $huiyuan->update($data);
            if($result !== false){
                $this->success('更改信息成功！',url('index'));
            }else{
                $this->error('更改信息失败！',url('index'));
            }
        }
        $res = db('huiyuan')->where('id',session('id'))->find();
        $this->assign('res',$res);
        return $this->fetch();
    }

    public function putonglogin()
    {
        if(request()->isPost()){
            //dump(input('post.'));die;
            $huiyuan = new HuiyuanModel();
            $res = $huiyuan->checklogin(input('post.'));
            if($res == 1){
                $this->success('登录成功！',url('index/index'));
            }
            if($res == 2){
                $this->error('密码错误！',url('putonglogin'));
            }
            if($res == 3){
                $this->error('用户不存在！',url('putonglogin'));
            }
            return;
        }
        return $this->fetch();
    }

    public function zhuce()
    {
        if(request()->isPost()){
            //dump(input('post.'));die;
            $huiyuan = new HuiyuanModel();
            $res = $huiyuan->checkzhuce(input('post.'));
            if($res == 1){
                $this->error('用户已经存在！',url('zhuce'));
            }
            if($res == 2){
                $this->success('注册成功！',url('putonglogin'));
            }
            return;
        }
        return $this->fetch();
    }
}