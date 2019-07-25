<?php
namespace app\index\model;
use think\Model;

class Huiyuan extends Model
{
    //模型事件---钩子函数----使用条件：——————控制器里面使用模型，不能用助手函数实现——————
    protected static function init()
    {
        Huiyuan::event('before_update', function ($huiyuan) {
            if($_FILES['image']['tmp_name']){
                //
                $huiyuans = Huiyuan::find($huiyuan->id);
                //dump($huiyuans['image']);dump($_SERVER['DOCUMENT_ROOT']);die;
                $huiyuans_image1 = $_SERVER['DOCUMENT_ROOT'].$huiyuans['image'];
                //判断文件是否存在，这里文件是具体到哪个盘下面
                if(file_exists($huiyuans_image1)){
                    @unlink($huiyuans_image1);//unlink() 函数删除文件
                }

                $file = request()->file('image');
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    //echo $info->getSaveName();
                    $savename = str_replace("\\", "/", $info->getSaveName());//把路径中的\转换为/
                    $image = '/uploads' . '/'.$savename;
                    //dump($image);die;
                    $huiyuan['image'] = $image;
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
        });
    }

    //检测登录
    public function checklogin($data)
    {
        $huiyuan = Huiyuan::getByusername($data["username"]);
        if($huiyuan){
            if($huiyuan['password'] == md5($data['password'])){
                session('id',$huiyuan['id']);
                // session('username',$huiyuan['username']);
                // session('image',$huiyuan['image']);
                return 1;  //信息正确
            }else{
                return 2;  //密码错误
            }
        }else{
            return 3;  //用户不存在
        }
    }

    //检测注册
    public function checkzhuce($data)
    {
        $huiyuan = Huiyuan::getByusername($data["username"]);
        if($huiyuan){
            return 1;  //用户存在
        }else{
            $data['password'] = md5($data['password']);
            db('huiyuan')->insert($data);
            return 2;  //注册不存在
        }
    }
}
