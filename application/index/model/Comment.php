<?php
namespace app\index\model;
use think\Model;

class Comment extends Model
{
    public function getallcomment($artid)
    {
        $comments = $this::where('article_id',$artid)->select();
        return $this->getCommentList($comments);
    }
    public function getCommentList($data,$parent_id=0)
    {
        static $arr = array();
        foreach ($data as $k => $v) {
            if($v['parent_id']==$parent_id){
                $arr[] = $v;
                $this->getCommentList($data,$v['id']);
            }
        }
        return $arr;
    }
}