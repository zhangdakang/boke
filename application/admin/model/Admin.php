<?php
namespace app\admin\model;
use think\Model;

class Admin extends Model
{
    public function addadmin($data)
    {
        if(empty($data) || !is_array($data)){
            return false;
        }
        if($data['password']){
            $data['password'] = md5($data['password']);
        }
        $admindata = array();
        $admindata['name'] = $data['name'];
        $admindata['password'] = $data['password'];
        if($this->save($admindata)){
            $groupAccess['uid'] = $this->id;
            $groupAccess['group_id'] = $data['group_id'];
            db('auth_group_access')->insert($groupAccess);
            return true;
        }else{
            return false;
        }
    }

    public function editadmin($data,$admins)
    {
        if($data['password']==$admins['password']){
            $data['password'] = $admins['password'];
        }else{
            $data['password'] = md5($data['password']);
        }
        $admindata = array();
        $admindata['name'] = $data['name'];
        $admindata['password'] = $data['password'];
        $admindata['sex'] = $data['sex'];
        $admindata['email'] = $data['email'];
        $admindata['id'] = $data['id'];
        db('auth_group_access')->where(array('uid'=>$data['id']))->update(['group_id'=>$data['group_id']]);
        return $this::update($admindata);
    }

    public function showadmin()
    {
        return $this::order('id')->paginate(5);
    }

    public function deladmin($id)
    {
        if($this::destroy($id)){
            return true;
        }else{
            return false;
        }
    }

    public function checklogin($data)
    {
        $admin = Admin::getByName($data['name']);
        if($admin){
            if($admin['password'] == md5($data['password'])){
                session('name',$admin['name']);
                session('id',$admin['id']);
                return 1;  //信息正确
            }else{
                return 2;  //密码错误
            }
        }else{
            return 3;  //用户不存在
        }
    }


}
