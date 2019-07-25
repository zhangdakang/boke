<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\Comment as CommentModel;

class Comment extends Common
{
    public function add()
    {
        if(request()->isAjax()){
            $con = input('post.');
            $con['create_time'] = time();
            $res = db('comment')->insert($con);
            if($res){
                return 1;
            }else{
                return 2;
            }
        }
    }
}
