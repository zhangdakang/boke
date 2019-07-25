<?php
namespace app\admin\model;
use think\Model;

class Article extends Model
{
    //模型事件---钩子函数----使用条件：——————控制器里面使用模型，不能用助手函数实现——————
    protected static function init()
    {
        Article::event('before_insert', function ($article) {///////改动地方//////
            if($_FILES['pic']['tmp_name']){
                $file = request()->file('pic');
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    //echo $info->getSaveName();
                    $pic = '/uploads' . '/'.$info->getSaveName();
                    $article['pic'] = $pic;//////改动地方///////
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
        });


        Article::event('before_update', function ($article) {
            if($_FILES['pic']['tmp_name']){

                //
                $articles = Article::find($article->id);
                $articles_pic1 = $_SERVER['DOCUMENT_ROOT'].$articles['pic'];
                //判断文件是否存在，这里文件是具体到哪个盘下面
                if(file_exists($articles_pic1)){
                    @unlink($articles_pic1);//unlink() 函数删除文件
                }

                $file = request()->file('pic');
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    //echo $info->getSaveName();
                    $pic = '/uploads' . '/'.$info->getSaveName();
                    $article['pic'] = $pic;
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
        });


        Article::event('before_delete', function ($article) {
            $articles = Article::find($article->id);
            $articles_pic1 = $_SERVER['DOCUMENT_ROOT'].$articles['pic'];
            //判断文件是否存在，这里文件是具体到哪个盘下面
            if(file_exists($articles_pic1)){
                @unlink($articles_pic1);//unlink() 函数删除文件
            }
        });


    }

}
