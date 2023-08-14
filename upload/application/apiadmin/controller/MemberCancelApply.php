<?php
namespace app\apiadmin\controller;

class MemberCancelApply extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $utype = input('get.utype/d',0,'intval');
        $status = input('get.status/d',0,'intval');
        $sortType = input('get.sortType/s','','trim');
        $sort = input('get.sort/s','desc','trim');

        switch ($sortType)
        {
            case 'addtime':
                $order = 'addtime '.$sort;
                break;
            case 'handlertime':
                $order = 'handlertime '.$sort;
                break;
            default:
                $order = 'status asc,id desc ';
                break;
        }
        switch ($status)
        {
            case 1: // 待处理
                $where['status'] = 0;
                break;
            case 2: // 已处理
                $where['status'] = 1;
                break;
        }

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        if($utype > 0)
        {
            $where['utype'] = $utype;
        }
        $total = model('MemberCancelApply')->where($where)->count();
        $list = model('MemberCancelApply')->where($where)
                ->order($order)
                ->page($current_page . ',' . $pagesize)
                ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function delete()
    {
        $id = input('post.id/d',0,'intval');

        if ($id==0) {
            $this->ajaxReturn(500, '请选择');
        }
        model('MemberCancelApply')->where('id',$id)->delete();
        model('AdminLog')->record(
            '删除账号注销申请。申请ID【' . $id . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function deleteAll()
    {
        $id = input('post.id/a',[]);
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择');
        }
        model('MemberCancelApply')->whereIn('id',$id)->delete();
        model('AdminLog')->record(
            '删除账号注销申请。申请ID【' . implode(',',$id) . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function handle()
    {
        $id = input('post.id/d',0,'intval');
        if ($id==0) {
            $this->ajaxReturn(500, '请选择');
        }
        $info = model('MemberCancelApply')->where('id',$id)->find();
        $uid = $info['uid'];
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
        $info->status = 1;
        $info->handlertime = time();
        $info->save();
        model('AdminLog')->record(
            '处理账号注销申请。申请ID【' . $id . '】；会员UID【' . $uid . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '处理成功');
    }

    /**
     * 注销备份
     */
    private function addBackups($id)
    {
        $cancel_apply = model('MemberCancelApply')
            ->where('id',$id)
            ->where('is_backups',0)
            ->field('id,uid,utype,addtime')
            ->find();
        if (count($cancel_apply) > 0) {
            $cancel_apply = $cancel_apply->toArray();

            switch ($cancel_apply['utype']) {
                case 1:
                    $arrs = [
                        'cancel_apply_id' => $cancel_apply['id'],
                        'uid' => $cancel_apply['uid'], // 企业uid
                        'cancel_apply_time' => $cancel_apply['addtime'], // 注销申请时间
                        'create_time' => time(), // 添加时间
                    ];
                    $company = model('company')
                        ->alias('c')
                        ->join('member m', 'c.uid=m.uid', 'left')
                        ->join('setmeal s','s.id=c.setmeal_id','left')
                        ->where('m.uid', $cancel_apply['uid'])
                        ->field('c.*,m.mobile,m.username,m.reg_time,s.name as setmeal_name')
                        ->find();
                    if ($company) {
                        $arrs['cid'] = $company['id'];
                        $arrs['mobile'] = $company['mobile'];
                        $arrs['companyname'] = $company['companyname'];
                        $arrs['addtime'] = $company['addtime'];
                        $arrs['reg_time'] = $company['reg_time'];
                        $arrs['setmeal_id'] = $company['setmeal_id'];
                        $arrs['setmeal_name'] = $company['setmeal_name'];
                        $arrs['jobs_num'] =  model('JobSearchKey')->where('uid',$cancel_apply['uid'])->count();
                        $arrs['job_apply_count'] = model('JobApply')->where('company_uid',$cancel_apply['uid'])->count();
                        $arrs['download_resume_num'] = model('CompanyDownResume')->where('uid',$cancel_apply['uid'])->count();
                        $company_contact = model('CompanyContact')->where('uid',$cancel_apply['uid'])->field('contact,mobile')->find();
                        $arrs['contact'] = !empty($company_contact['contact']) ?$company_contact['contact'] : '';
                        $arrs['contact_mobile'] = !empty($company_contact['mobile']) ?$company_contact['mobile'] : '';
                    }
                    $getCompany = $this->getCompany($cancel_apply['uid']);
                    if ($getCompany)
                    {
                        $arrs['content'] = json_encode($getCompany);
                    }

                    $model = 'CompanyCancelApplyBackups';
                    break;

                case 2:
                    $resume = model('Member')
                        ->alias('m')
                        ->join('resume r', 'r.uid=m.uid', 'left')
                        ->where('m.uid', $cancel_apply['uid'])
                        ->field('m.mobile,m.reg_time,r.*')
                        ->find();
                    if ($resume) {
                        $contact_mobile = model('ResumeContact')->where('uid',$cancel_apply['uid'])->view('mobile');
                        $arrs = [
                            'cancel_apply_id' => $cancel_apply['id'],
                            'uid' => $cancel_apply['uid'],
                            'rid' => $resume['id'],
                            'mobile' => $resume['mobile'],
                            'fullname' => $resume['fullname'],
                            'contact_mobile' => $contact_mobile,//简历联系人电话
                            'addtime' => $resume['addtime'],// 简历添加时间
                            'reg_time' => $resume['reg_time'],
                            'cancel_apply_time' => $cancel_apply['addtime'],//注销申请时间
                            'create_time' => time(),
                            'education' => $resume['education'],// 学历id
                            'education_name' => !empty(
                                model('BaseModel')->map_education[$resume['education']]
                            )
                                ? model('BaseModel')->map_education[$resume['education']]
                                : '学历未知',// 学历名称
                            'enter_job_time' => $resume['enter_job_time'],// 参加工作时间
                            'delivery_num' => model('JobApply')->where('personal_uid',$cancel_apply['uid'])->count(),// 投递数
                            'download_num' => model('CompanyDownResume')->where('personal_uid',$cancel_apply['uid'])->count(),// 被下载数
                            'viewed_num' => model('ViewResume')->where('personal_uid',$cancel_apply['uid'])->count(),// 被查看数
                            'invitation_num' => model('CompanyInterview')->where('personal_uid',$cancel_apply['uid'])->count(),// 被邀请数
                        ];
                        $getResume = $this->getResume($cancel_apply['uid']);
                        if ($getResume)
                        {
                            $arrs['content'] = json_encode($getResume);
                        }
                    }
                    $model = 'PersonCancelApplyBackups';
                    break;
            }
            \think\Db::startTrans();
            try {
                $add_backups = model($model)->insert($arrs);
                if ($add_backups === false)
                {
                    throw new \Exception(model($model)->getError());
                }
                $save_member_cancel_apply = model('MemberCancelApply')
                    ->where('id', $id)
                    ->update(['is_backups'=>1]);
                if ($save_member_cancel_apply === false)
                {
                    throw new \Exception(model('MemberCancelApply')->getError());
                }
                //提交事务
                \think\Db::commit();
                return ['code'=>200,'msg'=>'备注成功'];

            } catch (\Exception $e) {
                \think\Db::rollBack();
                return ['code'=>500,'msg'=>$e->getMessage()];

            }
        }
        return ['code'=>500,'msg'=>'此条注销数据已备注'];

    }

    public function backups()
    {
        $id = input('post.id/d',0,'intval');
        if (!$id)
        {
            $this->ajaxReturn(500, '请选择数据');
        }
        $data = $this->addBackups($id);
        if ($data['code'] === 500)
        {
            $this->ajaxReturn(500, $data['msg']);
        }
        $this->ajaxReturn(200, '备份成功');
    }

    /**
     * 备份列表
     */
    public function backupsList()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $utype = input('get.utype/d',0,'intval');
        $setmeal_id = input('get.setmeal_id/d',0,'intval');
        $sort_type = input('get.sort_type/s','','trim');
        $sort = input('get.sort/s','desc','trim');

        switch ($sort_type)
        {
            case 'reg_time':
                $order = 'reg_time '.$sort;
                break;
            case 'cancel_apply_time':
                $order = 'cancel_apply_time '.$sort;
                break;
            default:
                $order = 'cancel_apply_time '.$sort;
                break;
        }
        if ($setmeal_id > 0 && $utype === 1)
        {
            $where['setmeal_id'] = $setmeal_id;
        }
        switch ($utype)
        {
            case 1:
                if ($keyword && $key_type) {
                    switch ($key_type) {
                        case 1:
                            $where['companyname'] = ['like', '%' . $keyword . '%'];
                            break;
                        default:
                            break;
                    }
                }
                $model = 'CompanyCancelApplyBackups';
                $field = 'id,cancel_apply_id,cid,uid,mobile,companyname,contact,contact_mobile,addtime,reg_time,setmeal_id,setmeal_name,jobs_num,job_apply_count,download_resume_num,cancel_apply_time,create_time';
                break;
            case 2:
                if ($keyword && $key_type) {
                    switch ($key_type) {
                        case 1:
                            $where['fullname'] = ['like', '%' . $keyword . '%'];
                            break;
                        default:
                            break;
                    }
                }
                $model = 'PersonCancelApplyBackups';
                $field = 'id,cancel_apply_id,uid,rid,mobile,fullname,contact_mobile,addtime,reg_time,cancel_apply_time,create_time,education,education_name,enter_job_time,delivery_num,download_num,viewed_num,invitation_num';
                break;
            default:
                $model = 'CompanyCancelApplyBackups';
                $field = 'id,cancel_apply_id,cid,uid,mobile,companyname,contact,contact_mobile,addtime,reg_time,setmeal_id,setmeal_name,jobs_num,job_apply_count,download_resume_num,cancel_apply_time,create_time';
                break;
        }

        $total = model($model)->where($where)->count();
        $list = model($model)->where($where)
            ->order($order)
            ->field($field)
            ->page($current_page . ',' . $pagesize)
            ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 导出备份
     */
    public function exportBackups()
    {
        $id = input('post.id/s', '', 'trim');
        $utype = input('post.utype/d',0,'intval');
        if (empty($id))
        {
            $this->ajaxReturn(500, '请选择要导出的数据');
        }
        switch ($utype)
        {
            case 1:
                $model = 'CompanyCancelApplyBackups';
                break;
            case 2:
                $model = 'PersonCancelApplyBackups';
                break;
            default:
                $model = 'CompanyCancelApplyBackups';
                break;
        }
        $backups = model($model)
            ->whereIn('id',$id)
            ->order('create_time desc,id desc')
            ->field('content')
            ->select();
        $list = [];
        foreach ($backups as $k => $v)
        {
            $content = json_decode($v['content']);
            $list[] = $content;
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    /**
     * 删除申请备注
     */
    public function backupsDelete()
    {
        $id = input('post.id/a',[]);
        $utype = input('post.utype/d',0,'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择');
        }
        switch ($utype)
        {
            case 1:
                $model = 'CompanyCancelApplyBackups';
                $msg = '删除企业注销申请备注';
                break;
            case 2:
                $model = 'PersonCancelApplyBackups';
                $msg = '删除个人注销申请备注';
                break;
            default:
                $model = 'CompanyCancelApplyBackups';
                $msg = '删除企业注销申请备注';
                break;
        }
        model($model)->whereIn('id',$id)->delete();
        model('AdminLog')->record(
            $msg.'。申请ID【' . implode(',',$id) . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }

    /**
     * 查询简历
     */
    public function getResume($uid)
    {
        $resume = model('Resume')
            ->alias('a')
            ->join(
                config('database.prefix') . 'resume_contact b',
                'a.id=b.rid',
                'LEFT'
            )
            ->field(
                'a.id,a.fullname,a.sex,a.birthday,a.residence,a.height,a.marriage,a.education,a.enter_job_time,a.householdaddress,a.major,a.idcard,a.current,a.addtime,a.refreshtime,b.mobile,b.weixin,b.qq,b.email'
            );
        $resume = $resume->where('a.uid',$uid)->find();
        if ($resume === null)
        {
            return false;
        }
        $category_data = model('Category')->getCache();
        $category_major_data = model('CategoryMajor')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        if (!empty($resume['id'])) {
            $intention_data = model('ResumeIntention')
                ->where('rid', $resume['id'])
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
        }

            $arr['id'] = $resume['id'];
            $arr['fullname'] = $resume['fullname'];
            $arr['sex'] = isset(model('Resume')->map_sex[$resume['sex']])
                ? model('Resume')->map_sex[$resume['sex']]
                : '未知';
            $arr['age'] =
                $resume['birthday'] != ''
                    ? date('Y') - intval($resume['birthday'])
                    : '未知';
            $arr['residence'] = $resume['residence'];
            $arr['height'] = $resume['height'];
            $arr['marriage'] = isset(
                model('Resume')->map_marriage[$resume['marriage']]
            )
                ? model('Resume')->map_marriage[$resume['marriage']]
                : '未知';
            $arr['education'] = isset(
                model('BaseModel')->map_education[$resume['education']]
            )
                ? model('BaseModel')->map_education[$resume['education']]
                : '未知';
            $arr['experience'] =
                $resume['enter_job_time'] == 0
                    ? '尚未工作'
                    : format_date($resume['enter_job_time']);
            $arr['householdaddress'] = $resume['householdaddress'];
            $arr['major'] =
                $resume['major'] && isset($category_major_data[$resume['major']])
                    ? $category_major_data[$resume['major']]
                    : '';
            $arr['idcard'] = $resume['idcard'];
            $arr['current'] = isset(
                $category_data['QS_current'][$resume['current']]
            )
                ? $category_data['QS_current'][$resume['current']]
                : '';
            $district_arr = $category_arr = $wage_arr = $nature_arr = $trade_arr = [];
            if (isset($intention_arr[$resume['id']])) {
                foreach ($intention_arr[$resume['id']] as $k => $v) {
                    if ($v['trade']) {
                        $trade_arr[] = $category_data['QS_trade'][$v['trade']];
                    }
                    if ($v['nature']) {
                        $nature_arr[] = model('Resume')->map_nature[
                        $v['nature']
                        ];
                    }
                    $wage_arr[0] = model('BaseModel')->handle_wage(
                        $v['minwage'],
                        $v['maxwage']
                    );
                    if ($v['category']) {
                        $category_arr[] = isset(
                            $category_job_data[$v['category']]
                        )
                            ? $category_job_data[$v['category']]
                            : '';
                    }
                    if ($v['district']) {
                        $district_arr[] = isset(
                            $category_district_data[$v['district']]
                        )
                            ? $category_district_data[$v['district']]
                            : '';
                    }
                }
            }
            if (!empty($trade_arr)) {
                $trade_arr = array_unique($trade_arr);
                $arr['intention_trade'] = implode(',', $trade_arr);
            } else {
                $arr['intention_trade'] = '';
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $arr['intention_jobs'] = implode(',', $category_arr);
            } else {
                $arr['intention_jobs'] = '';
            }
            if (!empty($wage_arr)) {
                $wage_arr = array_unique($wage_arr);
                $arr['intention_wage'] = implode(',', $wage_arr);
            } else {
                $arr['intention_wage'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $arr['intention_district'] = implode(',', $district_arr);
            } else {
                $arr['intention_district'] = '';
            }
            if (!empty($nature_arr)) {
                $nature_arr = array_unique($nature_arr);
                $arr['intention_nature'] = implode(',', $nature_arr);
            } else {
                $arr['intention_nature'] = '';
            }
            $arr['addtime'] = date('Y-m-d H:i', $resume['addtime']);
            $arr['refreshtime'] = date('Y-m-d H:i', $resume['refreshtime']);
            $arr['mobile'] = $resume['mobile'];
            $arr['weixin'] = $resume['weixin'];
            $arr['qq'] = $resume['qq'];
            $arr['email'] = $resume['email'];
            return $arr;
    }

    /**
     * 查询企业
     */
    public function getCompany($uid)
    {
        $company = model('Company')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company_contact b',
                'a.id=b.comid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'member_setmeal c',
                'a.uid=c.uid',
                'LEFT'
            )
            ->field(
                'a.id,a.companyname,a.short_name,a.nature,a.trade,a.district,a.scale,a.registered,a.currency,a.audit,a.addtime,a.refreshtime,b.contact,b.mobile,b.telephone,b.weixin,b.qq,b.email'
            );
        $company = $company
            ->order('a.id asc')
            ->find();
        if (empty($company)) {
            return false;
        }
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $arr['id'] = $company['id'];
        $arr['companyname'] = $company['companyname'];
        $arr['short_name'] = $company['short_name'];
        $arr['nature'] = isset(
            $category_data['QS_company_type'][$company['nature']]
        )
            ? $category_data['QS_company_type'][$company['nature']]
            : '';
        $arr['trade'] = isset($category_data['QS_trade'][$company['trade']])
            ? $category_data['QS_trade'][$company['trade']]
            : '';
        $arr['district'] = isset(
            $category_district_data[$company['district']]
        )
            ? $category_district_data[$company['district']]
            : '';
        $arr['scale'] = isset($category_data['QS_scale'][$company['scale']])
            ? $category_data['QS_scale'][$company['scale']]
            : '';
        $arr['registered'] =
            $company['registered'] .
            '万元' .
            ($company['currency'] == 0 ? '人民币' : '美元');
        $arr['audit'] = isset(model('Company')->map_audit[$company['audit']])
            ? model('Company')->map_audit[$company['audit']]
            : '认证未通过';
        $arr['addtime'] = date('Y-m-d H:i', $company['addtime']);
        $arr['refreshtime'] = date('Y-m-d H:i', $company['refreshtime']);
        $arr['contact'] = $company['contact'];
        $arr['mobile'] = $company['mobile'];
        $arr['weixin'] = $company['weixin'];
        $arr['telephone'] = $company['telephone'];
        $arr['qq'] = $company['qq'];
        $arr['email'] = $company['email'];
        return $arr;
    }
}
