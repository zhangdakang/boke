<?php
namespace app\index\validate;
use think\Validate;

class Huiyuan extends Validate
{
    protected $rule = [
        'username'=>'require|max:25',
        'year'=>'length:4',
        'province'=>'max:25',
        'city'=>'max:25',
    ];

    protected $message = [
        'username.require'=>'昵称不能为空',
        'username.max'=>'昵称长度不能大于25个字符',
        'year.lenght'=>'年份长度为4',
        'province.max'=>'昵称长度不能大于25个字符',
        'city.max'=>'昵称长度不能大于25个字符',
    ];
}
