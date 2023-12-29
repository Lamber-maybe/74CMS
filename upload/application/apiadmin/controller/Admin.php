<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Admin extends Backend
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
            'role_id' => input('post.role_id/d', 0, 'intval'),
            'is_sc' => input('post.is_sc/d', 0, 'intval')
        ];
        $password = $input_data['password'];
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

        $role_name = model('AdminRole')->where('id', $input_data['role_id'])->value('name');
        model('AdminLog')->writeLog(
            '系统-系统管理员-管理员列表，添加管理员，角色:' . $role_name . '；登录名:' . $input_data['username'] . '；密码:' . $password,
            $this->admininfo,
            0,
            2
        );

        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Admin')
                ->field('id,username,role_id,is_sc,status')
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
                'role_id' => input('post.role_id/d', 0, 'intval'),
                'is_sc' => input('post.is_sc/d', 0, 'intval'),
                'status' => input('post.status/d', 0, 'intval'),
            ];
            $id = intval($input_data['id']);
            $is_release = input('post.is_release/d', 0, 'intval');
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $info = model('Admin')
                ->alias('a')
                ->join('admin_role r', 'r.id = a.role_id', 'LEFT')
                ->field('a.id,a.username,a.role_id,a.password,a.pwd_hash,a.status,r.name as role_name')
                ->where('a.id', $id)
                ->find();
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }

            $log_field = '系统-系统管理员-管理员列表，修改管理员，';
            $is_update = false;
            $delToken = false;

            if ($input_data['role_id'] != $info['role_id']) {
                $role_name = model('AdminRole')->where('id', $input_data['role_id'])->value('name');
                $log_field .= '角色:' . $info['role_name'] . '->' . $role_name . '；';
                $is_update = true;
            } else {
                $log_field .= '角色:' . $info['role_name'] . '；';
            }

            if ($input_data['username'] != $info['username']) {
                $log_field .= '登录名:' . $info['username'] . '->' . $input_data['username'] . '；';
                $is_update = true;
            } else {
                $log_field .= '登录名:' . $info['username'] . '；';
            }

            if (isset($input_data['password']) && $input_data['password']) {
                $log_field .= '密码:' . $input_data['password'];
                $input_data['password'] = model('Admin')->makePassword(
                    $input_data['password'],
                    $info['pwd_hash']
                );
                $is_update = true;
                /**
                 * 如果不是修改的自己的密码就清除登录信息
                 * 进行重新登录
                 * cy 2023-10-20
                 */
                if ($info['id'] != $this->admininfo['id']) {
                    $delToken = true;
                }
            } else {
                $input_data['password'] = $info['password'];
            }

            Db::startTrans();
            try {
                $result = model('Admin')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
                if (false === $result) {
                    $this->ajaxReturn(500, model('Admin')->getError());
                }

                if ($info['status'] != $input_data['status']) {
                    if ($input_data['status'] === 1) {
                        $deblocking = model('AdminLog')->writeLog(
                            '系统-系统管理员-管理员列表，解锁管理员，角色:' . $info['role_name'] . '；登录名:' . $info['username'] . '。',
                            $this->admininfo,
                            0,
                            7
                        );
                        if (false === $deblocking) {
                            throw new \Exception(model('AdminLog')->getError());
                        }
                    } else {
                        if (isset($is_release) && $is_release === 1) {
                            $this->adminCrmRelease($id);
                        }
                    }
                }

                if ($is_update) {
                    $log_result = model('AdminLog')->writeLog(
                        $log_field,
                        $this->admininfo,
                        0,
                        3
                    );
                    if (false === $log_result) {
                        throw new \Exception(model('AdminLog')->getError());
                    }
                }
                /**
                 * 【ID1000728】
                 * 【优化】修改密码后清空状态需重新登录
                 * cy 2023-10-20
                 */
                if (true === $delToken) {
                    model('AdminIdentityToken')->where(['admin_id' => $info['id']])->delete();
                }

                //提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

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
        model('AdminLog')->writeLog(
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
        $type = input('get.type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($admin_id > 0) {
            $where['admin_id'] = $admin_id;
        }

        if ($type > 0) {
            $where['type'] = $type;
        }

        if ($is_login > 0) {
            $where['is_login'] = $is_login;
            $wherefulltext = '';
        } else {
            if ($keyword != '') {
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
            } else {
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
        $typeAll = model('AdminLog')->map_type;
        foreach ($list as &$log) {
            $log['type_name'] = isset($typeAll[$log['type']])
                ? $typeAll[$log['type']]
                : '未知';
        }
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

    public function getBindQrcode()
    {
        $id = input('get.id/d', 0, 'intval');
        $info = model('Admin')->where('id', $id)->find();
        if ($info === null) {
            $this->ajaxReturn(500, '没有找到管理员信息');
        }
        if (config('global_config.wechat_open') == 0) {
            $this->ajaxReturn(500, '微信公众号功能未开启，请先开启');
        }
        if (config('global_config.wechat_appid') == '' || config('global_config.wechat_appsecret') == '') {
            $this->ajaxReturn(500, '微信公众号功能未正确配置，请先配置');
        }
        $params = [
            'alias' => 'admin_bind',
            'admin_id' => $id
        ];
        $class = new \app\common\lib\Wechat;
        $qrcode = $class->makeQrcode($params);
        if ($qrcode) {
            $this->ajaxReturn(200, '', $qrcode);
        } else {
            $this->ajaxReturn(500, '生成二维码失败');
        }
    }

    public function bindQrcodeCancel()
    {
        $id = input('post.id/d', 0, 'intval');
        $info = model('Admin')->where('id', $id)->find();
        if ($info === null) {
            $this->ajaxReturn(500, '没有找到管理员信息');
        }
        $info->openid = '';
        $info->save();
        model('AdminLog')->writeLog(
            '个人会员中心修改账号资料，绑定微信，解绑成功',
            $this->admininfo,
            0,
            1
        );
        $this->ajaxReturn(200, '解绑成功');
    }

    /**
     * 判断当前管理员是否绑定微信
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function isBindWechat()
    {
        $admin_info = $this->admininfo;
        if (!isset($admin_info) || empty($admin_info)) {
            $this->ajaxReturn(500, '管理员信息缺失');
        }
        $adminId = $admin_info->id ? $admin_info->id : 0;
        if (!isset($adminId) || empty($adminId)) {
            $this->ajaxReturn(500, '管理员信息错误');
        }

        $adminInfo = model('Admin')
            ->where('id', $adminId)
            ->find();

        if (isset($adminInfo['openid']) && !empty($adminInfo['openid'])) {
            $this->ajaxReturn(200, '已绑定', ['is_bind' => 1]);
        }
        $this->ajaxReturn(200, '未绑定', ['is_bind' => 0]);
    }

    /**
     * 锁定系统管理员
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lock()
    {
        $adminId = input('post.id/d', 0, 'intval');
        if (1 === $adminId) {
            $this->ajaxReturn(500, '不允许操作内置admin账号');
        }
        if (!$adminId) {
            $this->ajaxReturn(500, '请选择要锁定的管理员');
        }

        Db::startTrans();
        try {
            $this->adminCrmRelease($adminId);
            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '管理员锁定失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '管理员锁定成功');
    }

    /**
     * 解锁系统管理员
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function deblocking()
    {
        $adminId = input('post.id/d', 0, 'intval');
        if (!$adminId) {
            $this->ajaxReturn(500, '请选择要解锁的管理员');
        }
        if (1 === $adminId) {
            $this->ajaxReturn(500, '不允许操作内置admin账号');
        }
        $info = model('Admin')
            ->alias('a')
            ->join('admin_role r', 'r.id = a.role_id', 'LEFT')
            ->field('a.username,r.name as role_name')
            ->where('a.id', $adminId)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '要解锁的管理员不存在');
        }

        Db::startTrans();
        try {
            # 1.解锁系统管理员
            $deblocking_result = model('Admin')
                ->allowField(true)
                ->save(
                    ['status' => 1],
                    ['id' => $adminId]
                );
            if (false === $deblocking_result) {
                throw new \Exception(model('Admin')->getError());
            }

            # 2.写入管理员操作日志
            $log_result = model('AdminLog')->writeLog(
                '系统-系统管理员-管理员列表，解锁管理员，角色:' . $info['role_name'] . '；登录名:' . $info['username'] . '。',
                $this->admininfo,
                0,
                7
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '管理员解锁失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '管理员解锁成功');
    }

    /**
     * 获取管理员CRM数据统计
     * @return void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAdminCrmData()
    {
        $adminId = input('post.id/d', 0, 'intval');
        if (!$adminId) {
            $this->ajaxReturn(500, '请选择要统计的管理员');
        }
        $info = model('Admin')
            ->where('id', $adminId)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '要统计的管理员不存在');
        }

        $total_company = model('Company')
            ->where('admin_id', $adminId)
            ->count('id');//累计总客户
        $total_clue = model('b2bcrm.CrmClue')
            ->where('admin_id', $adminId)
            ->count('id');//累计总线索统计

        $return = [
            'total_company' => $total_company,
            'total_clue' => $total_clue,
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 锁定后台管理员执行任务
     * @param $adminId
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function adminCrmRelease($adminId)
    {
        $info = model('Admin')
            ->alias('a')
            ->join('admin_role r', 'r.id = a.role_id', 'LEFT')
            ->field('a.username,r.name as role_name')
            ->where('a.id', $adminId)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '要锁定的管理员不存在');
        }

        # 1.锁定系统管理员
        $lock_result = model('Admin')
            ->allowField(true)
            ->save(
                ['status' => 2],
                ['id' => $adminId]
            );
        if (false === $lock_result) {
            throw new \Exception(model('Admin')->getError());
        }

        # 2.线索释放公海
        $clue_ids = model('b2bcrm.CrmClue')
            ->where(['admin_id' => $adminId])
            ->column('id');

        if (isset($clue_ids) && !empty($clue_ids)) {
            $clue_release = model('b2bcrm.CrmClueRelease')->releaseAdd($clue_ids, $adminId, 2, 1);
            if (false === $clue_release) {
                throw new \Exception(model('b2bcrm.CrmClueRelease')->getError());
            }

            $release_clue = model('b2bcrm.CrmClue')
                ->where(['admin_id' => $adminId])
                ->update(['admin_id' => 0]);
            if (false === $release_clue) {
                throw new \Exception(model('b2bcrm.CrmClue')->getError());
            }
        }

        # 3.客户释放公海
        $company_ids = model('Company')
            ->where(['admin_id' => $adminId])
            ->column('id');

        if (isset($company_ids) && !empty($company_ids)) {
            $crm_release = model('b2bcrm.CrmClueRelease')->releaseCompany($company_ids, 2, 1);
            if (false === $crm_release) {
                throw new \Exception(model('b2bcrm.CrmClueRelease')->getError());
            }

            $release_company = model('Company')
                ->where(['admin_id' => $adminId])
                ->update(['admin_id' => 0]);
            if (false === $release_company) {
                throw new \Exception(model('Company')->getError());
            }
        }

        # 4.清空客户自动分配规则
        model('b2bcrm.CrmAutoAssign')
            ->destroy(['admin_id' => $adminId]);

        # 5.写入管理员操作日志
        $log_result = model('AdminLog')->writeLog(
            '系统-系统管理员-管理员列表，锁定管理员，角色:' . $info['role_name'] . '；登录名:' . $info['username'] . '。',
            $this->admininfo,
            0,
            7
        );
        if (false === $log_result) {
            throw new \Exception(model('AdminLog')->getError());
        }
    }

    public function adminLogTypeAll()
    {
        $typeAll = model('AdminLog')->map_type;
        $this->ajaxReturn(200, '获取数据成功', $typeAll);
    }

    public function delAdminLog()
    {
        $this->checkIsAdministrator();

        $days = input('post.days/d', 0, 'intval');

        switch ($days) {
            case '1':
                $log_field = '清理一个月前的管理员日志';
                break;

            case '3':
                $log_field = '清理三个月前的管理员日志';
                break;

            case '6':
                $log_field = '清理半年前的管理员日志';
                break;

            case '12':
                $log_field = '清理一年前的管理员日志';
                break;

            default:
                $this->ajaxReturn(500, '请选择删除日志的时间节点');
                break;
        }

        Db::startTrans();
        try {
            # 1.删除日志
            $del_result = model('AdminLog')
                ->whereTime('addtime', '<', "-{$days} month")
                ->delete();
            if (false === $del_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            # 2.写入管理员操作日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();

            $this->ajaxReturn(200, '日志清理成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '日志清理失败', ['err_msg' => $e->getMessage()]);
        }

    }
}
