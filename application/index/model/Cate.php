<?php
namespace app\index\model;
use think\Model;

class Cate extends Model
{
    public function catetree()
    {
        $cateres = $this->select();
        return $this->sort($cateres);
    }

    public function sort($data,$pid=0,$level=0)
    {
        static $arr = array();
        foreach ($data as $key => $v) {
            if($v['pid'] == $pid){
                $v['level'] = $level;
                $arr[] = $v;
                $this->sort($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }

    public function getchilrenid($cateid)
    {
        $cateres = $this->select();
        $arr = $this->_getchilrenid($cateres,$cateid);
        $arr[] = $cateid;
        $strId = implode(',',$arr);//数组转换为字符串
        return $strId;
    }
    public function _getchilrenid($cateres,$cateid)
    {
        static $arr = array();
        foreach ($cateres as $key => $v) {
            if($v['pid'] == $cateid){
                $arr[] = $v['id'];
                $this->_getchilrenid($cateres,$v['id']);
            }
        }
        return $arr;
    }

    public function getparents($cateid){
        $cateres=$this->field('id,pid,catename')->select();
        $cates=db('cate')->field('id,pid,catename')->find($cateid);
        $pid=$cates['pid'];
        //判断pid是否等于0（非->0，是->1）
        if($pid){
            $arr=$this->_getparentsid($cateres,$pid);
        }
        $arr[]=$cates;
        return $arr;
    }

    public function _getparentsid($cateres,$pid){
        static $arr=array();
        foreach ($cateres as $k => $v) {
            if($v['id'] == $pid){
                $arr[]=$v;
                $this->_getparentsid($cateres,$v['pid']);
            }
        }

        return $arr;
    }

    //首页推荐
    public function getCateIndex()
    {
        $cateIndex = $this->where('rec_index','=',1)->order('id desc')->select();
        return $cateIndex;
    }

    //底部推荐
    public function getCateBottom()
    {
        $cateBottom = $this->where('rec_bottom','=',1)->order('id desc')->select();
        return $cateBottom;
    }

    public function getCateInfo($cateid)
    {
        $cateinfo = $this->field('catename,keywords,desc')->find($cateid);
        return $cateinfo;
    }
}
