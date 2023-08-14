<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Member extends Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $where = [];
        $list_type = input('get.list_type/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $status = input('get.status/s', '', 'trim');
        $sort = input('get.sort/s', '', 'trim');
        $regtime = input('get.regtime/s', '', 'trim');
        $platform = input('get.platform/s', '', 'trim');
        $utype = input('get.utype/d', 0, 'intval');
        $is_openid = input('get.is_openid/d', 0, 'intval');
        $is_email = input('get.is_email/d', 0, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['a.uid'] = ['eq', $keyword];
                    break;
                case 2:
                    $where['a.username'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['a.mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                case 4:
                    if ($list_type == 'company') {
                        $where['b.companyname'] = ['like', '%' . $keyword . '%'];
                    } else if ($list_type == 'personal') {
                        $where['c.fullname'] = ['like', '%' . $keyword . '%'];
                    }
                    break;
                default:
                    break;
            }
        }
        // switch ($list_type) {
        //     case 'company':
        //         $where['a.utype'] = 1;
        //         $where['b.companyname'] = ['neq',''];
        //         break;
        //     case 'personal':
        //         $where['a.utype'] = 2;
        //         $where['b.fullname'] = 'not null';
        //         break;
        //     default:
        //         $where['a.status'] = 0;
        //         break;
        // }
        if ($status != '') {
            $where['a.status'] = intval($status);
        }
        if ($regtime != '') {
            $cut_date = date('Y-m-d', strtotime('-' . intval($regtime) . 'day'));
            $where['a.reg_time'] = ['egt', strtotime($cut_date)];
        }
        if ($platform != '') {
            $where['a.platform'] = $platform;
        }
        if ($sort != '') {
            $order = 'a.last_login_time desc,a.uid desc';
        } else {
            $order = 'a.uid desc';
        }
        if ($utype > 0) {
            $where['a.utype'] = $utype;
        }
        $wheres = '';
        if ($is_openid === 1) {
            $wheres .= 'd.id is not null';
        } elseif ($is_openid === 2) {
            $wheres .= 'd.id is null';
        }
        if (empty($wheres)) {
            if ($is_email === 1) {
                $wheres .= " a.email != ''";
            } elseif ($is_email === 2) {
                $wheres .= " a.email  = ''";
            }
        } else {
            if ($is_email === 1) {
                $wheres .= "  and a.email != ''";
            } elseif ($is_email === 2) {
                $wheres .= " and a.email = ''";
            }
        }
        $total = model('Member')->alias('a');
        if ($list_type == 'company') {
            $total = $total->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT')->where('a.utype', 1)->where('b.companyname', 'neq', '');
        } else if ($list_type == 'personal') {
            $total = $total->join(config('database.prefix') . 'resume c', 'a.uid=c.uid', 'LEFT')->where('a.utype', 2)->where('c.fullname', 'NOT NULL');
        } else {
            $total = $total->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT')
                ->join(config('database.prefix') . 'resume c', 'a.uid=c.uid', 'LEFT')
                ->where(function ($query) {
                    $query->where('(b.companyname="" or b.companyname is NULL) AND a.utype=1')->whereOr('c.fullname IS NULL AND a.utype=2');
                });
        }

        /**
         * 【ID1000418】
         * 【bug】微信绑定重复数据导致后台多条信息
         * yx - 2022.11.10
         * [旧]：
         * ->join(config('database.prefix').'member_bind d','d.uid=a.uid and d.type="weixin"','left')
         * [join查询修改]：
         * $memberBindSql = model('MemberBind')->where(['type'=>'weixin'])->group('uid')->buildSql();
         */
        $memberBindSql = model('MemberBind')->where(['type' => 'weixin'])->group('uid')->buildSql();
        $total = $total->join([$memberBindSql => 'd'], 'd.uid=a.uid', 'left')->where($wheres)->where($where)->count();
        $field = 'a.uid,a.utype,a.username,a.mobile,a.email,a.reg_time,a.reg_ip,a.reg_address,a.last_login_time,a.last_login_ip,a.last_login_address,a.status,a.avatar,a.robot,a.platform,a.disable_im,d.openid';
        $list = model('Member')->alias('a');
        if ($list_type == 'company') {
            $field .= ',b.companyname';
            $list = $list->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT')->where('a.utype', 1)->where('b.companyname', 'neq', '');
        } else if ($list_type == 'personal') {
            $field .= ',c.fullname';
            $list = $list->join(config('database.prefix') . 'resume c', 'a.uid=c.uid', 'LEFT')->where('a.utype', 2)->where('c.fullname', 'NOT NULL');
        } else {
            $list = $list->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT')
                ->join(config('database.prefix') . 'resume c', 'a.uid=c.uid', 'LEFT')
                ->where(function ($query) {
                    $query->where('(b.companyname="" or b.companyname is NULL) AND a.utype=1')->whereOr('c.fullname IS NULL AND a.utype=2');
                });
        }
        $list = $list->field($field)->where($where)->where($wheres)
            ->join([$memberBindSql => 'd'], 'd.uid=a.uid', 'left')
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            $list[$key]['platform_cn'] = isset(model('BaseModel')->map_platform[$value['platform']]) ? model('BaseModel')->map_platform[$value['platform']] : '未知平台';
            $list[$key]['is_openid'] = empty($value['openid']) ? '否' : '是';
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
            'mobile' => input('post.mobile/s', '', 'trim'),
            'utype' => input('post.utype/d', 1, 'intval'),
            'platform' => config('platform'),
        ];
        $input_data['pwd_hash'] = randstr();
        if ($input_data['password'] != '') {
            $initial_password = $input_data['password'];
            $input_data['password'] = model('Member')->makePassword(
                $input_data['password'],
                $input_data['pwd_hash']
            );
        }

        if (
            false ===
            model('Member')
                ->validate('Member.add')
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        if ($input_data['utype'] == 1) {
            $insert_data_company['uid'] = model('Member')->uid;
            $insert_data_company['companyname'] = '';
            $insert_data_company['short_name'] = '';
            $insert_data_company['nature'] = 0;
            $insert_data_company['trade'] = 0;
            $insert_data_company['district1'] = 0;
            $insert_data_company['district2'] = 0;
            $insert_data_company['district3'] = 0;
            $insert_data_company['district'] = 0;
            $insert_data_company['scale'] = 0;
            $insert_data_company['registered'] = 0;
            $insert_data_company['currency'] = 0;
            $insert_data_company['tag'] = '';
            $insert_data_company['map_lat'] = 0.0;
            $insert_data_company['map_lng'] = 0.0;
            $insert_data_company['map_zoom'] = 0;
            $insert_data_company['logo'] = 0;
            $insert_data_company['addtime'] = time();
            $insert_data_company['refreshtime'] =
                $insert_data_company['addtime'];
            $insert_data_company['cs_id'] = model('Member')->distributionCustomerService();
            model('Company')->save($insert_data_company);
            //赠送套餐
            $data_setmeal['uid'] = model('Member')->uid;
            $data_setmeal['note'] = '';
            $data_setmeal['setmeal_id'] = config('global_config.reg_service');
            model('Member')->setMemberSetmeal($data_setmeal);
        }
        model('Task')->doTask(model('Member')->uid, $input_data['utype'], 'reg');
        model('AdminLog')->writeLog(
            '新增' . ($input_data['utype'] === 1 ? '企业' : '个人') . '会员(UID:' . model('Member')->uid . ')，用户名:' . $input_data['username'] . '；手机号:' . $input_data['mobile'] . '; 密码:' . $initial_password,
            $this->admininfo,
            0,
            2
        );
        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $uid = input('get.uid/d', 0, 'intval');

        if ($uid) {
            $info = model('Member')->find($uid);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            unset($info['password']);
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'uid' => input('post.uid/d', 0, 'intval'),
                'username' => input('post.username/s', '', 'trim'),
                'password' => input('post.password/s', '', 'trim'),
                'mobile' => input('post.mobile/s', '', 'trim'),
            ];

            $uid = intval($input_data['uid']);
            if (!$uid) {
                $this->ajaxReturn(500, '参数错误');
            }
            $info = model('Member')->find($uid);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $log_field = '编辑个人会员(UID:' . $uid . ')信息；';
            if (fieldRegex($input_data['username'], 'mobile')) {
                $this->ajaxReturn(500, '用户名不可以是手机号');
            }
            if (fieldRegex($input_data['username'], 'email')) {
                $this->ajaxReturn(500, '用户名不可以是邮箱');
            }
            if ($input_data['username'] != $info['username']) {
                $log_field .= '用户名:' . $info['username'] . '->' . $input_data['username'] . '，';
            }
            if ($input_data['mobile'] != $info['mobile']) {
                $log_field .= '手机号:' . $info['mobile'] . '->' . $input_data['mobile'] . '，';
            }
            if (isset($input_data['password']) && $input_data['password']) {
                $log_field .= '密码:' . $input_data['password'] . '，';
                $input_data['password'] = model('Member')->makePassword(
                    $input_data['password'],
                    $info['pwd_hash']
                );
            } else {
                $input_data['password'] = $info['password'];
            }

            if ($log_field == '编辑个人会员(UID:' . $uid . ')信息；') {
                $this->ajaxReturn(200, '未作修改');
            }

            $input_data['utype'] = $info['utype'];
            if (
                false ===
                model('Member')
                    ->validate('Member.edit')
                    ->allowField(true)
                    ->save($input_data, ['uid' => $uid])
            ) {
                $this->ajaxReturn(500, model('Member')->getError());
            }

            /**
             * 修改会员手机号、密码。及后台修改时，清除所有登录状态，需重新登录
             * yx - 2022.11.09
             */
            if (
                $input_data['password'] != $info['password']
                ||
                $input_data['mobile'] != $info['mobile']
            ) {
                model('IdentityToken')->where(['uid' => $uid])->delete(); //修改密码即删除token,
            }
            model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function delete()
    {
        $uid = input('post.uid/a');

        if (empty($uid)) {
            $this->ajaxReturn(500, '请选择数据');
        }

        $log_field = '删除';
        foreach ($uid as $id) {
            $utype = model('Member')->where('uid', $id)->value('utype');
            $log_field .= ($utype === 1 ? '企业' : '个人') . '会员(UID:' . $id . ')，';
        }

        \think\Db::startTrans();
        try {

            //删除会员相关信息
            if (
                false ===
                model('Member')->deleteMemberByUids($uid)
            ) {
                throw new \Exception(model('Member')->getError());
            }
            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        model('AdminLog')->writeLog(
            $log_field,
            $this->admininfo,
            0,
            4
        );
        $this->ajaxReturn(200, '删除成功');
    }

    public function isUnique()
    {
        $field = input('post.field/s', '', 'trim');
        $value = input('post.value/s', '', 'trim');
        $selfUid = input('post.self/d', 0, 'intval');
        $utype = input('post.utype/d', 1, 'intval');

        if (!$field || !$value) {
            $this->ajaxReturn(500, '非法请求');
        }
        if (!in_array($field, ['username', 'mobile', 'email'])) {
            $this->ajaxReturn(500, '非法请求');
        }
        if ($selfUid) {
            $where['uid'] = ['neq', $selfUid];
        }
        $where['utype'] = ['eq', $utype];
        $where[$field] = ['eq', $value];
        if (
            model('Member')
                ->where($where)
                ->find()
        ) {
            $this->ajaxReturn(200, $field . '已被占用', 0);
        } else {
            $this->ajaxReturn(200, $field . '可用', 1);
        }
    }

    public function lock()
    {
        $uid = input('post.uid/d', 0, 'intval');
        $status = input('post.status/d', 0, 'intval');

        if (!$uid || !in_array($status, [0, 1])) {
            $this->ajaxReturn(500, '非法请求');
        }

        try {
            $memberInfo = model('Member')->where('uid', $uid)->find();
            if (null === $memberInfo) {
                $this->ajaxReturn(500, '要锁定/解锁的会员不存在');
            }
            $memberInfo = $memberInfo->toArray();

            switch ($memberInfo['utype']) {
                case 1:
                    // 企业
                    $status_zh = $status == 1
                        ? '用户账号解锁'
                        : '用户账号锁定，用户无法登录';

                    $companyInfo = model('Company')->where('uid', $uid)->find();
                    if (null === $companyInfo) {
                        // 无效企业
                        if ($status === 1) {
                            $log_field = '对无效企业会员{手机号:' . $memberInfo['mobile'] . '}(会员ID:' . $uid . ')用户账号解锁';
                        } else {
                            $log_field = '锁定无效企业会员{手机号:' . $memberInfo['mobile'] . '}(会员ID:' . $uid . ')的账号，用户无法登录';
                        }
                    } else {
                        if ($status === 1) {
                            $log_field = '对{' . $companyInfo['companyname'] . '}(企业ID:' . $companyInfo['id'] . ')用户账号解锁';
                        } else {
                            $log_field = '锁定{' . $companyInfo['companyname'] . '}(企业ID:' . $companyInfo['id'] . ')的账号，用户无法登录';
                        }
                    }
                    break;

                case 2:
                    // 个人
                    $status_zh = $status == 1
                        ? '用户账号解锁'
                        : '用户账号锁定，用户无法登录';

                    $resumeInfo = model('Resume')->where('uid', $uid)->find();
                    if (null === $resumeInfo) {
                        // 无效个人
                        $log_field = '对无效个人会员{手机号:' . $memberInfo['mobile'] . '}(会员ID:' . $uid . ')' . $status_zh;
                    } else {
                        $log_field = '对{' . $resumeInfo['fullname'] . '}(会员ID:' . $uid . ')' . $status_zh;
                    }
                    break;

                default:
                    $this->ajaxReturn(500, '会员类型异常【会员ID：' . $uid . '】');
                    break;
            }

            Db::startTrans();

            $set_result = model('Member')->setStatus($uid, $status);
            if (false === $set_result) {
                throw new \Exception(model('Member')->getError());
            }

            /**
             * 日志
             */
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                7
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '操作失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '操作成功');
    }

    public function im()
    {
        $uid = input('post.uid/d', 0, 'intval');
        $disable_im = input('post.disable_im/d', 0, 'intval');
        $reason = input('post.reason/s', '', 'trim');
        if (!$uid || !in_array($disable_im, [0, 1])) {
            $this->ajaxReturn(500, '非法请求');
        }
        model('Member')->setIm($uid, $disable_im, $reason);

        $utype = model('Member')->where('uid', $uid)->value('utype');
        if ($utype === 1) {
            $company_info = model('Company')
                ->field('id,companyname')
                ->where('uid', $uid)
                ->find();

            if ($disable_im == 1) {
                $log_field = '对{' . $company_info['companyname'] . '}(企业ID:' . $company_info['id'] . ')设置禁聊，该企业无法使用聊天功能';
            } else {
                $username = model('Member')->where('uid', $uid)->value('username');
                $log_field = '即时通讯解除' . $username . '(企业会员)用户禁聊状态';
            }
        } else {
            if ($disable_im == 1) {
                $log_field = '禁聊' . '个人会员(UID:' . $uid . ')';
            } else {
                $username = model('Member')->where('uid', $uid)->value('username');
                $log_field = '即时通讯解除' . $username . '(个人会员)用户禁聊状态';
            }
        }

        model('AdminLog')->writeLog(
            $log_field,
            $this->admininfo,
            0,
            7
        );

        $this->ajaxReturn(200, '操作成功');
    }

    public function detail()
    {
        $uid = input('get.uid/d', 0, 'intval');

        $info = model('Member')
            ->field(
                'uid,utype,username,mobile,last_login_time,last_login_ip,last_login_address,reg_time,reg_ip,reg_address,platform,status'
            )
            ->find($uid);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        $info->platform_cn = isset(model('BaseModel')->map_platform[$info->platform]) ? model('BaseModel')->map_platform[$info->platform] : '未知平台';
        $resume = model('Resume')
            ->where('uid', $uid)
            ->find();
        if ($resume !== null) {
            $resume->complete_percent = model('Resume')->countCompletePercent(0, $uid);
            $resume->web_link = url('index/resume/show', ['id' => $resume->id]);
            $resume->mobile_link = config('global_config.mobile_domain') . 'resume/' . $resume->id;
        }
        $company = model('Company')
            ->where('uid', $uid)
            ->find();
        if ($company !== null) {
            $company->web_link = url('index/company/show', ['id' => $company->id]);
            $company->mobile_link = config('global_config.mobile_domain') . 'company/' . $company->id;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'info' => $info,
            'resume' => $resume,
            'company' => $company
        ]);
    }

    //积分管理
    public function points_list()
    {
        $utype = input('get.utype/d', 1, 'intval');
        if ($utype == 1) {
            $this->points_list_company();
        } else {
            $this->points_list_personal();
        }
    }

    //企业积分管理
    protected function points_list_company()
    {
        $where = [];
        $list_type = input('get.list_type/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['c.id'] = ['eq', $keyword];
                    break;
                case 2:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['c.uid'] = ['eq', $keyword];
                    break;
                case 4:
                    $map_uid_arr = model('Member')
                        ->where('mobile', 'like', '%' . $keyword . '%')
                        ->where(['utype' => ['eq', 1]])
                        ->column('uid');
                    $map_uid_arr = is_array($map_uid_arr)
                        ? $map_uid_arr
                        : [$map_uid_arr];
                    if (!empty($map_uid_arr)) {
                        $where['c.uid'] = ['in', $map_uid_arr];
                    } else {
                        $where['c.uid'] = 0;
                    }
                    break;
                default:
                    break;
            }
        }

        $total = model('Company')
            ->alias('c')
            ->join(
                config('database.prefix') . 'member_points p',
                'c.uid=p.uid',
                'LEFT'
            )
            ->where($where)
            ->count();
        $list = model('Company')
            ->alias('c')
            ->join(
                config('database.prefix') . 'company_contact b',
                'c.uid=b.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'member_points p',
                'c.uid=p.uid',
                'LEFT'
            )
            ->field('c.uid,c.companyname,p.points,b.contact,b.mobile')
            ->where($where)
            ->order('c.uid desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['points'] = $value['points'] ? $value['points'] : 0;
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    //个人积分管理
    protected function points_list_personal()
    {
        $where['m.utype'] = 2;
        $list_type = input('get.list_type/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['m.uid'] = ['eq', $keyword];
                    break;
                case 2:
                    $where['m.mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }

        $total = model('Member')
            ->alias('m')
            ->join(
                config('database.prefix') . 'member_points p',
                'm.uid=p.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'resume r',
                'm.uid=r.uid',
                'LEFT'
            )
            ->where($where)
            ->where('r.id', 'not null')
            ->count();
        $list = model('Member')
            ->alias('m')
            ->join(
                config('database.prefix') . 'member_points p',
                'm.uid=p.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'resume r',
                'm.uid=r.uid',
                'LEFT'
            )
            ->field('m.uid,m.mobile,m.reg_time,p.points,r.fullname')
            ->where($where)
            ->where('r.id', 'not null')
            ->order('m.uid desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            $list[$key]['points'] = $value['points'] ? $value['points'] : 0;
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function points_set()
    {
        $uid = input('post.uid/d', 0, 'intval');
        $op = input('post.op/d', 1, 'intval');
        $points_val = input('post.points_val/d', 0, 'intval');
        $explain = input('post.explain/s', '', 'trim');
        $is_charge = input('post.is_charge/d', 0, 'intval');
        $charge_val = input('post.charge_val/f', 0, 'floatval');
        if ($uid == 0) {
            $this->ajaxReturn(500, '请选择会员');
        }
        if ($points_val == 0) {
            $this->ajaxReturn(500, '请输入增减' . config('global_config.points_byname') . '数');
        }

        $member_utype = model('Member')->where('uid', $uid)->value('utype');
        $member_name = model('Member')->getMemberNickNameByUid($uid, '', false);
        $comid = model('Company')->where('uid', $uid)->value('id');
        if ($member_utype === 1) {
            $log_field = '编辑{' . $member_name . '}(企业ID:' . $comid;
        } else {
            $log_field = '编辑{' . $member_name . '}(UID:' . $uid;
        }

        $points_byname = config('global_config.points_byname');
        $log_field .= ')的'
            . $points_byname
            . '，'
            . ($op == 1 ? '增加' : '减少')
            . $points_byname
            . ' ' . $points_val . ' 点，操作说明:' . $explain;

        $note = '系统操作【管理员：' . $this->admininfo->username . '】。' . $explain;
        if ($is_charge == 1 && $charge_val > 0) {
            $note .= '；收费' . $charge_val . '元';
            $log_field .= '；收费金额:' . $charge_val;
        } else {
            $log_field .= '；收费金额:0';
        }

        model('Member')->setMemberPoints(
            ['uid' => $uid, 'points' => $points_val, 'note' => $note],
            $op
        );

        $memberPoints = model('Member')->getMemberPoints($uid);
        $log_field .= '；当前' . $points_byname . ':' . $memberPoints;
        model('AdminLog')->writeLog(
            $log_field,
            $this->admininfo,
            0,
            5
        );

        $this->ajaxReturn(200, '操作成功');
    }

    public function points_log()
    {
        $where = [];
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($uid > 0) {
            $where['uid'] = $uid;
        }

        $total = model('MemberPointsLog')
            ->where($where)
            ->count();
        $list = model('MemberPointsLog')
            ->where($where)
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

    public function loginlog()
    {
        $where = [];
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $where['is_login'] = 1;
        if ($uid > 0) {
            $where['uid'] = $uid;
        }

        $total = model('MemberActionLog')
            ->where($where)
            ->count();
        $list = model('MemberActionLog')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['platform_cn'] = isset(model('BaseModel')->map_platform[$value['platform']]) ? model('BaseModel')->map_platform[$value['platform']] : '-';
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function actionlog()
    {
        $where = [];
        $utype = input('get.utype/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($uid > 0) {
            $where['a.uid'] = $uid;
        }
        if ($utype > 0) {
            $where['a.utype'] = $utype;
        }
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
            $wherefulltext = " MATCH (a.`content`) AGAINST ('" . $against . "' IN BOOLEAN MODE) ";
        } else {
            $wherefulltext = '';
        }
        $total = model('MemberActionLog')
            ->alias('a')
            ->where($where)
            ->where($wherefulltext)
            ->count();
        $list = model('MemberActionLog')->alias('a');
        if ($utype == 1) {
            $list = $list->field('a.*,b.companyname')->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT');
        }
        if ($utype == 2) {
            $list = $list->field('a.*,b.fullname')->join(config('database.prefix') . 'resume b', 'a.uid=b.uid', 'LEFT');
        }
        $list = $list
            ->where($where)
            ->where($wherefulltext)
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['platform_cn'] = isset(model('BaseModel')->map_platform[$value['platform']]) ? model('BaseModel')->map_platform[$value['platform']] : '-';
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function management()
    {
        $uid = input('get.uid/d', 0, 'intval');
        $this->ajaxReturn(
            200,
            '获取数据成功',
            $this->managementExtra($uid)
        );
    }

    protected function managementExtra($uid)
    {
        $userinfo = model('Member')->where('uid', $uid)->find();

        $JwtAuth = \app\common\lib\JwtAuth::mkToken(
            config('sys.safecode'),
            31212000, //360天有效期
            [
                'info' => [
                    'uid' => $uid,
                    'utype' => $userinfo['utype'],
                    'mobile' => $userinfo['mobile']
                ]
            ]
        );
        $user_token = $JwtAuth->getString();
        //把token存入数据表，并设置有效期
        model('IdentityToken')->makeToken($uid, $user_token, $this->expire_platform['web']);
        $next_code = 200;
        if ($userinfo['utype'] == 1) {
            $company_profile = model('Company')
                ->field(true)
                ->where('uid', 'eq', $uid)
                ->find();
            if ($company_profile === null || $company_profile['district'] == 0) {
                $next_code = 50003;
            }
            if ($next_code == 200) {
                if (config('global_config.must_com_audit_certificate') == 1) {
                    if ($company_profile['audit'] != 1) {
                        $next_code = 50004;
                    }
                }
            }

            $log_field = '进入{'
                . (!empty($company_profile['companyname']) ? $company_profile['companyname'] : '-')
                . '}(企业ID:'
                . (!empty($company_profile['id']) ? $company_profile['id'] : '-')
                . ')会员中心';
        } else {
            do {
                $resume_info = model('Resume')
                    ->field(true)
                    ->where('uid', 'eq', $uid)
                    ->find();
                if ($resume_info === null) {
                    $next_code = 50007;
                    break;
                }
                $intention = model('ResumeIntention')
                    ->field('id')
                    ->where('rid', $resume_info['id'])
                    ->find();
                if ($intention === null) {
                    $next_code = 50005;
                    break;
                }
            } while (0);
            $log_field = '进入{'
                . (!empty($resume_info['fullname']) ? $resume_info['fullname'] : '-')
                . '}(会员ID:'
                . $uid
                . ')会员中心';
        }
        $visitor = new \app\common\lib\Visitor;
        $visitor->setLogin([
            'uid' => $uid,
            'utype' => $userinfo['utype'],
            'token' => $user_token
        ], $this->expire_platform['web']);

        model('AdminLog')->writeLog(
            $log_field,
            $this->admininfo,
            0,
            1
        );

        return [
            'uid' => $uid,
            'token' => $user_token,
            'utype' => $userinfo['utype'],
            'mobile' => $userinfo['mobile'],
            'next_code' => $next_code
        ];
    }
}
