<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Cate as CateModel;

class Article extends Common
{
    public function lst()
    {
        //联表查询
        $res = db('article')->field('a.*,b.catename')->alias('a')->join('cate b','a.cateid=b.id')->order('id')->paginate(8);
        $this->assign('res',$res);
        return view();
    }

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
            //如果有照片
            // if($_FILES['pic']['tmp_name']){
            //     $file = request()->file('pic');
            //     // 移动到框架应用根目录/public/uploads/ 目录下
            //     $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            //     if($info){
            //         // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            //         //echo $info->getSaveName();
            //         $pic = ROOT_PATH . 'public' . DS . 'uploads' . '/'.$info->getSaveName();
            //         $data['pic'] = $pic;
            //     }else{
            //         // 上传失败获取错误信息
            //         echo $file->getError();
            //     }
            // }

            if($article->save($data)){
                $this->success('添加文章成功！',url('lst'));
            }else{
                $this->error('添加文章失败！',url('lst'));
            }
        }

        $cate = new CateModel();
        $res = $cate->catetree();
        $this->assign('res',$res);
        return view();

    }

    public function edit()
    {
        $article = new ArticleModel();
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Article');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $save = $article->update($data);
            //dump($save);die;
            if($save){
                $this->success('修改文章成功！',url('lst'));
            }else{
                $this->error('修改文章失败！',url('lst'));
            }
            return;//////////////////
        }
        $cate = new CateModel();
        $res = $cate->catetree();
        $articles = $article->find(input('id'));
        $this->assign(array(
                'res' => $res,
                'articles' => $articles,
            ));
        return view();
    }

    public function del()
    {
        $del = ArticleModel::destroy(input('id'));
        if($del){
            $this->success('删除文章成功！',url('lst'));
        }else{
            $this->error('删除文章失败！',url('lst'));
        }
    }
}
