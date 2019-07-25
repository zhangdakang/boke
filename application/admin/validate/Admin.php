<?php
namespace app\admin\validate;
use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'name'=>'unique:admin|require|max:25',
        'password'=>'require|min:6',
        'email'=>'require|email',
    ];

    protected $message = [
        'name.require'=>'管理员名称不能为空',
        'name.unique'=>'管理员名称不能重复',
        'name.max'=>'管理员名称长度不能大于25个字符',
        'password.require'=>'管理员密码不能为空',
        'password.min'=>'管理员密码不能少于6个字符',
        'email.require'=>'邮箱不能为空',
        'email.email'=>'邮箱格式不正确',
    ];

    //链接场景
    protected $scene = [
        'add'=>['name','password'],
        'edit'=>['name','password'=>'min:6','email'],
    ];
}