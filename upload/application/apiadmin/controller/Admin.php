<?php
namespace app\apiadmin\controller;

class Admin extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $total = model('Admin')->count();
        $list = model('Admin')
            ->order('id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $role_id_arr = $role_list = [];
        foreach ($list as $key => $value) {
            $role_id_arr[] = $value['role_id'];
        }
        if (!empty($role_id_arr)) {
            $role_list = model('AdminRole')
                ->where('id', 'in', $role_id_arr)
                ->column('id,name');
        }
        foreach ($list as $key => $value) {
            $value['role_name'] = isset($role_list[$value['role_id']])
                ? $role_list[$value['role_id']]
                : '';
            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function add()
    {
        $input_data = [
            'username' => input('post.username/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
            'role_id' => input('post.role_id/d', 0, 'intval')
        ];
        $input_data['pwd_hash'] = randstr();
        $input_data['password'] = model('Admin')->makePassword(
            $input_data['password'],
            $input_data['pwd_hash']
        );
        $result = model('Admin')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('Admin')->getError());
        }
        model('AdminLog')->record(
            '添加管理员。管理员ID【' .
                model('Admin')->id .
                '】;管理员登录名【' .
                $input_data['username'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Admin')
                ->field('id,username,role_id')
                ->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            unset($info['password']);
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'username' => input('post.username/s', '', 'trim'),
                'password' => input('post.password/s', '', 'trim'),
                'role_id' => input('post.role_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $info = model('Admin')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            if (isset($input_data['password']) && $input_data['password']) {
                $input_data['password'] = model('Admin')->makePassword(
                    $input_data['password'],
                    $info['pwd_hash']
                );
            } else {
                $input_data['password'] = $info['password'];
            }
            $result = model('Admin')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('Admin')->getError());
            }
            model('AdminLog')->record(
                '编辑管理员。管理员ID【' .
                    $id .
                    '】;管理员登录名【' .
                    $input_data['username'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('Admin')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info->delete();
        model('AdminLog')->record(
            '删除管理员。管理员ID【' .
                $id .
                '】;管理员登录名【' .
                $info['username'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function roleoptions()
    {
        $list = model('AdminRole')->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function loglist()
    {
        $where = [];
        $is_login = input('get.is_login/d', 0, 'intval');
        $admin_id = input('get.admin_id/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($admin_id > 0) {
            $where['admin_id'] = $admin_id;
        }

        if ($is_login > 0) {
            $where['is_login'] = $is_login;
            $wherefulltext = '';
        } else {
            if ($keyword!='') {
                $against = '';
                $keyword = trim($keyword);
                if (false !== stripos($keyword, ' ')) {
                    $keyword = merge_spaces($keyword);
                    $tmp_keyword_arr = explode(' ', $keyword);
                    foreach ($tmp_keyword_arr as $key => $value) {
                        $against .= '+' . $value . ' ';
                    }
                    $against = trim($against);
                } else {
                    $against = $keyword;
                }
                $wherefulltext = " MATCH (`content`) AGAINST ('" . $against . "' IN BOOLEAN MODE) ";
            }else{
                $wherefulltext = '';
            }
        }

        $total = model('AdminLog')
            ->where($where)
            ->where($wherefulltext)
            ->count();
        $list = model('AdminLog')
            ->where($where)
            ->where($wherefulltext)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function alladmin()
    {
        $list = model('Admin')
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function getBindQrcode(){
        $id = input('get.id/d',0,'intval');
        $info = model('Admin')->where('id',$id)->find();
        if($info===null){
            $this->ajaxReturn(500, '没有找到管理员信息');
        }
        if(config('global_config.wechat_open')==0){
            $this->ajaxReturn(500, '微信公众号功能未开启，请先开启');
        }
        if(config('global_config.wechat_appid')=='' || config('global_config.wechat_appsecret')==''){
            $this->ajaxReturn(500, '微信公众号功能未正确配置，请先配置');
        }
        $params = [
            'alias'=>'admin_bind',
            'admin_id'=>$id
        ];
        $class = new \app\common\lib\Wechat;
        $qrcode = $class->makeQrcode($params);
        if($qrcode){
            $this->ajaxReturn(200, '', $qrcode);
        }else{
            $this->ajaxReturn(500, '生成二维码失败');
        }
    }
    public function bindQrcodeCancel(){
        $id = input('post.id/d',0,'intval');
        $info = model('Admin')->where('id',$id)->find();
        if($info===null){
            $this->ajaxReturn(500, '没有找到管理员信息');
        }
        $info->openid = '';
        $info->save();
        $this->ajaxReturn(200, '解绑成功');
    }
}
