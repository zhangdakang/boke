<?php
namespace app\admin\validate;
use think\Validate;

class Cate extends Validate
{
    protected $rule = [
        'catename'=>'unique:cate|require|max:25',
    ];

    protected $message = [
        'catename.require'=>'栏目名称不能为空',
        'catename.unique'=>'栏目名称不能重复',
        'catename.max'=>'栏目名称长度不能大于25个字符',
    ];

    //链接场景
    protected $scene = [
        'add'=>['catename'],
        'edit'=>['catename'],
    ];
}