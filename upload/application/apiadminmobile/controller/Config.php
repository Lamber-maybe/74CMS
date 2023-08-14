<?php

namespace app\apiadminmobile\controller;

class Config extends \app\apiadmin\controller\Config
{
    /**
     * 获取全局配置信息
     */
    public function index()
    {
        $nameArr = ['sitename','sitedomain','sitedir','mobile_domain','isclose','close_reason','closereg','points_byname','points_quantifier'];
        $info = model('Config')->whereIn('name',$nameArr)->column('name,value');
        $this->ajaxReturn(200, '获取数据成功', $info);
    }
    /**
     * 开启/关闭网站
     */
    public function closeOpenSite(){
        $is_close = input('post.is_close/d',0,'intval');
        if($is_close===1){
            model('Config')->save(['value'=>1],['name'=>'isclose']);
            $note = '关闭网站';
        }else{
            model('Config')->save(['value'=>0],['name'=>'isclose']);
            $note = '开启网站';
        }
        model('AdminLog')->record(
            $note,
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存数据成功');
    }
    /**
     * 开启/关闭注册
     */
    public function closeOpenReg(){
        $is_close = input('post.is_close/d',0,'intval');
        if($is_close===1){
            model('Config')->save(['value'=>1],['name'=>'closereg']);
            $note = '关闭会员注册';
        }else{
            model('Config')->save(['value'=>0],['name'=>'closereg']);
            $note = '开启会员注册';
        }
        model('AdminLog')->record(
            $note,
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存数据成功');
    }
    /**
     * 账号信息
     */
    
    public function adminDetail()
    {
        $id = $this->admininfo->id;
        $info = model('Admin')
            ->alias('a')
            ->join(config('database.prefix').'admin_role b','a.role_id=b.id','LEFT')
            ->field('a.id,a.username,a.addtime,a.last_login_time,a.last_login_ip,a.last_login_ipaddress,
            b.name as role_name,b.access_mobile')
            ->where('a.id',$id)
            ->find();
        $info['access_mobile'] = $info['access_mobile'] == 'all' ? $info['access_mobile'] : unserialize($info['access_mobile']);

        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        $this->ajaxReturn(200, '获取数据成功', $info);
    }
    public function adminUsername()
    {
        $input_data = [
            'username' => input('post.username/s', '', 'trim')
        ];
        $id = $this->admininfo->id;
        $info = model('Admin')->find($id);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        if(!$input_data['username']){
            $this->ajaxReturn(500, '请输入用户名');
        }
        $check_username = model('Admin')->where('username',$input_data['username'])->where('id','neq',$id)->find();
        if($check_username!==null){
            $this->ajaxReturn(500, '用户名已被占用');
        }
        $result = model('Admin')
            ->allowField(true)
            ->save($input_data, ['id' => $id]);
        if (false === $result) {
            $this->ajaxReturn(500, model('Admin')->getError());
        }
        model('AdminLog')->record(
            '编辑管理员登录名。管理员ID【' .
                $id .
                '】;管理员登录名【' .
                $input_data['username'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function adminPassword()
    {
        $input_data = [
            'old_password' => input('post.old_password/s', '', 'trim'),
            'new_password' => input('post.new_password/s', '', 'trim'),
            'new_password_repeat' => input('post.new_password_repeat/s', '', 'trim')
        ];
        $id = $this->admininfo->id;
        $info = model('Admin')->find($id);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        if(!$input_data['old_password']){
            $this->ajaxReturn(500, '请输入原密码');
        }
        if(!$input_data['new_password']){
            $this->ajaxReturn(500, '请输入新密码');
        }
        if(!$input_data['new_password_repeat']){
            $this->ajaxReturn(500, '请输入确认密码');
        }
        if($input_data['new_password'] != $input_data['new_password_repeat']){
            $this->ajaxReturn(500, '新密码和确认密码不一致');
        }
        $md5 = model('Admin')->makePassword(
            $input_data['old_password'],
            $info['pwd_hash']
        );
        if($md5!=$info->password){
            $this->ajaxReturn(500, '原密码错误');
        }
        $dataset['password'] = model('Admin')->makePassword(
            $input_data['new_password'],
            $info['pwd_hash']
        );
        $result = model('Admin')
            ->allowField(true)
            ->save($dataset, ['id' => $id]);
        if (false === $result) {
            $this->ajaxReturn(500, model('Admin')->getError());
        }
        model('AdminLog')->record(
            '修改管理员密码。管理员ID【' .
                $id .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '修改密码成功');
    }
}
