<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\Article as ArticleModel;
use app\index\model\Comment as CommentModel;
use app\index\model\Cate as CateModel;
class Article extends Common
{
    //文章页面
    public function article()
    {
        $artid = input('artid');
        $article = new ArticleModel();
        //setInc:自增1
        $article->where(array('id'=>$artid))->setInc('click');
        $artRes = $article->find($artid);
        $artHots = $article->getHotArticle($artRes['cateid']);
        $num  = db('comment')->where('article_id',$artid)->count();//评论条数
        $this->assign(array(
            'artRes'=>$artRes,
            'artHots'=>$artHots,
            'num'=>$num
            ));
        //dump($artRes);die;
        return $this->fetch();
    }

    //获取评论
    public function getcomment()
    {
        if(request()->isAjax()){
            $artid = input('artid');
            $comment = new CommentModel();
            $comments = $comment->getallcomment($artid);
            foreach ($comments as $k => $v) {
                if($v['parent_id']==0){
                    $v['huifu'] = '';
                    $userinfo = db('huiyuan')->where('id',$v['user_id'])->field('username,image')->find();
                    $v['user_name'] = $userinfo['username'];
                    $v['user_pic'] = $userinfo['image'];
                    $v['article_id'] = $artid;
                }else{
                    $user = db('comment')->where('id',$v['parent_id'])->field('user_id')->find();
                    $huifu = db('huiyuan')->where('id',$user['user_id'])->field('username')->find();
                    $v['huifu'] = '回复'.$huifu['username'].':';
                    $userinfo = db('huiyuan')->where('id',$v['user_id'])->field('username,image')->find();
                    $v['user_name'] = $userinfo['username'];
                    $v['user_pic'] = $userinfo['image'];
                    $v['article_id'] = $artid;
                }
            }
            return $comments;
        }
    }

    //点赞功能
    public function dianzan()
    {
        if(request()->isAjax()){
            $artid = input('artid');
            $userid = input('userid');
            $res = db('userzan')->where(array('artid'=>$artid,'userid'=>$userid))->field('zan')->find();
            if ($res==null) {   //之前没有点赞过
                $res1 = db('article')->where('id',$artid)->setInc('zan');
                $res2 = db('userzan')->insert(['artid'=>$artid,'userid'=>$userid,'zan'=>1]);
                if($res1&&$res2){
                    return 1;
                }else{
                    return 2;
                }
            }else{
                $res3 = db('userzan')->where(array('artid'=>$artid,'userid'=>$userid))->delete();
                $res4 = db('article')->where('id',$artid)->setDec('zan');
                if($res3&&$res4){
                    return 3;
                }else{
                    return 4;
                }
            }
        }
    }

    //发布文章
    public function add()
    {
        if(request()->isPost()){
            $data = input('post.');
            $data['time'] = time();
            $validate = \think\Loader::validate('Article');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $article = new ArticleModel();
            if($article->save($data)){
                $this->success('添加文章成功！',url('index/index'));
            }else{
                $this->error('添加文章失败！',url('add'));
            }
        }
        $cate = new CateModel();
        $res = $cate->catetree();
        //dump($res);die;
        $this->assign('res',$res);
        return view();
    }

    //uploadify上传照片
    public function upimg()
    {
        $file = request()->file('img');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public/uploads/article');
        if($info){
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            //echo $info->getSaveName();
            $savename = str_replace("\\", "/", $info->getSaveName());//把路径中的\转换为/
            $pic =  '/uploads/article' . '/'.$savename;
            echo $pic;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

    //撤销图片
    public function delimg()
    {
        if(request()->isAjax()){
            $imgurl = input('imgurl');
            $url = $_SERVER['DOCUMENT_ROOT'].$imgurl;
            //判断文件是否存在，这里文件是具体到哪个盘下面
            $res = @unlink($url);//unlink() 函数删除文件
            if($res){
                return 1;
            }else{
                return 2;
            }
        }
    }
}