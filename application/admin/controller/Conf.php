<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\model\Conf as ConfModel;

class Conf extends Common
{
    //分类显示：
    public function lst()
    {
        $conf = new ConfModel();
        $res = $conf->order('id')->paginate(2);
        $this->assign('res',$res);
        return $this->fetch();
    }

    //添加分类：
    public function add()
    {
        //判断是否提交表单
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Conf');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            if($data['values']){
                $data['values'] = str_replace('，',',',$data['values']);
            }
            $conf = new ConfModel();
            $add = $conf->save($data);
            if($add){
                $this->success('添加配置成功！',url('lst'));
            }else {
                $this->error('添加配置失败！',url('lst'));
            }
        }

        return $this->fetch();
    }

    //删除栏目
    public function del()
    {
        $del = db('conf')->delete(input('id'));
        if($del){
            $this->success('删除配置成功！',url('lst'));
        }else{
            $this->error('删除配置失败！',url('lst'));
        }
    }

    //编辑栏目
    public function edit()
    {
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Conf');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            if($data['values']){
                $data['values'] = str_replace('，',',',$data['values']);
            }
            $save = db('conf')->update($data);

            //用($save !== false)，不应用($save),因为没有修改的话，返回0，用($save),则会修改失败
            if($save !== false){
                $this->success('修改配置成功！',url('lst'));
            }else{
                $this->error('修改配置失败！',url('lst'));
            }
            return;//////////////////
        }
        $conf = new ConfModel();
        $confs = $conf->find(input('id'));
        $this->assign('confs',$confs);
        return view();
    }

    public function conf()
    {
        if(request()->isPost()){
            $data = input('post.');
            $formarr = array();
            foreach ($data as $k => $v) {
                $formarr[] = $k;
            }
            $_confarr = db('conf')->field('enname')->select();
            $confarr = array();
            foreach ($_confarr as $k1 => $v1) {
                $confarr[] = $v1['enname'];
            }
            $checkboxarr =array();
            foreach ($confarr as $k2 => $v2) {
                if(!in_array($v2,$formarr)){
                    $checkboxarr[] = $v2;
                }
            }
            if($checkboxarr){
                foreach ($checkboxarr as $k3 => $v3) {
                    ConfModel::where('enname',$v3)->update(['value'=>'']);
                }
            }
            if($data){
                foreach ($data as $k => $v) {
                    ConfModel::where('enname',$k)->update(['value'=>$v]);
                }
                $this->success('修改配置项成功！',url('conf'));
            }
        }
        $confs = ConfModel::select();
        $this->assign('confs',$confs);
        return view();
    }
}
