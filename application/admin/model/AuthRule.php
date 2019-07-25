<?php
namespace app\admin\model;
use think\Model;

class AuthRule extends Model
{
    public function authRuleTree()
    {
        $authrules = $this->order('sort')->select();
        return $this->sort($authrules);
    }

    public function sort($data,$pid=0)
    {
        static $arr = array();
        foreach ($data as $key => $v) {
            if($v['pid'] == $pid){
                $v['dataid']=$this->getparentid($v['id']);
                $arr[] = $v;
                $this->sort($data,$v['id']);
            }
        }
        return $arr;
    }

    //获取子权限
    //
    public function getchilrenid($authRuleId)
    {
        $authRuleRes = $this->select();
        return $this->_getchilrenid($authRuleRes,$authRuleId);
    }


    public function _getchilrenid($authRuleRes,$authRuleId)
    {
        static $arr = array();
        foreach ($authRuleRes as $key => $v) {
            if($v['pid'] == $authRuleId){
                $arr[] = $v['id'];
                $this->_getchilrenid($authRuleRes,$v['id']);
            }
        }
        return $arr;
    }

    //获取父类权限

    public function getparentid($authRuleId){
        $AuthRuleRes=$this->select();
        return $this->_getparentid($AuthRuleRes,$authRuleId,True);
    }

    public function _getparentid($AuthRuleRes,$authRuleId,$clear=False){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach ($AuthRuleRes as $k => $v) {
            if($v['id'] == $authRuleId){
                $arr[]=$v['id'];
                $this->_getparentid($AuthRuleRes,$v['pid'],False);
            }
        }
        asort($arr);//升序排序
        $arrStr=implode('-', $arr);//将数组转换为字符串
        return $arrStr;
    }
}

