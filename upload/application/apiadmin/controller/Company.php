<?php

namespace app\apiadmin\controller;

class Company extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 企业列表
     */
    public function index()
    {
        $where = [];
        $list_type = input('get.list_type/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $audit = input('get.audit/s', '', 'trim');
        $setmeal = input('get.setmeal/d', 0, 'intval');
        $regtime = input('get.regtime/d', 0, 'intval');
        $service = input('get.service/s', '', 'trim');
        $setmeal_overtime = input('get.setmeal_overtime/s', '', 'trim');
        $is_display = input('get.is_display/s', '', 'trim');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['c.id'] = ['eq', intval($keyword)];
                    break;
                case 3:
                    $map_userinfo = model('Member')
                        ->where(['mobile' => ['eq', $keyword]])
                        ->where(['utype' => ['eq', 1]])
                        ->find();
                    if ($map_userinfo === null) {
                        $where['c.id'] = 0;
                    } else {
                        $where['c.uid'] = ['eq', $map_userinfo['uid']];
                    }
                    break;
                case 4:
                    $where['c.uid'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }

        if($setmeal>0){
            $where['c.setmeal_id'] = $setmeal;
        }
        if($regtime>0){
            $cut_date = date('Y-m-d',strtotime('-'.$regtime.'day'));
            $where['c.addtime'] = ['egt',strtotime($cut_date)];
        }
        if($service!=''){
            $where['c.cs_id'] = $service;
        }
        if($is_display!=''){
            $where['c.is_display'] = intval($is_display);
        }
        switch($setmeal_overtime){
            case '0':
                $where['m.deadline'] = [['eq',0],['gt',time()],'or'];
                break;
            case '1':
                $where['m.deadline'] = [['neq',0],['lt',time()],'and'];
                break;
        }
        $total = model('Company')
            ->alias('c')
            ->join(config('database.prefix').'member_setmeal m','c.uid=m.uid','LEFT')
            ->join(config('database.prefix').'setmeal s','m.setmeal_id=s.id','LEFT');
        if ($list_type == 'noaudit') {
            $total = $total->join(config('database.prefix').'company_auth a','a.uid=c.uid','LEFT')->where('c.audit',0)->where('a.id','not null');
        }else if($audit!=''){
            $total = $total->join(config('database.prefix').'company_auth a','a.uid=c.uid','LEFT');
            if($audit==3){
                $total = $total->where('c.audit',0)->where('a.id','null');
            }else{
                $total = $total->where('c.audit',intval($audit))->where('a.id','not null');
            }
        }
        $total = $total->where($where)->count();
        $list = model('Company')
            ->alias('c')
            ->field('c.*,s.name as setmeal_name,m.deadline as setmeal_deadline')
            ->join(config('database.prefix').'member_setmeal m','c.uid=m.uid','LEFT')
            ->join(config('database.prefix').'setmeal s','m.setmeal_id=s.id','LEFT');
        if ($list_type == 'noaudit') {
            $list = $list->join(config('database.prefix').'company_auth a','a.uid=c.uid','LEFT')->where('c.audit',0)->where('a.id','not null');
        }else if($audit!=''){
            $list = $list->join(config('database.prefix').'company_auth a','a.uid=c.uid','LEFT');
            if($audit==3){
                $list = $list->where('c.audit',0)->where('a.id','null');
            }else{
                $list = $list->where('c.audit',intval($audit))->where('a.id','not null');
            }
        }
        $list = $list->join(config('database.prefix').'company_contact contact','c.id=contact.comid','LEFT')->field('c.*,contact.contact,contact.mobile')->where($where)->order('c.uid desc')->page($current_page . ',' . $pagesize)->select();
        $uid_arr = [];
        foreach ($list as $key => $value) {
            $uid_arr[] = $value['uid'];
        }
        if (!empty($uid_arr)) {
            $job_total_list = model('JobSearchRtime')
                ->where('uid', 'in', $uid_arr)
                ->group('uid')
                ->column('count(*) as num,uid', 'uid');
            $auth_list = model('CompanyAuth')
                ->where('uid', 'in', $uid_arr)
                ->column(
                    'uid,legal_person_idcard_front,legal_person_idcard_back,license,proxy',
                    'uid'
                );
            $member_list = model('Member')
                ->where('uid', 'in', $uid_arr)
                ->column(
                    'uid,mobile,email',
                    'uid'
                );
            $company_contact_list = model('CompanyContact')
                ->where('uid', 'in', $uid_arr)
                ->column(
                    'uid,mobile,contact',
                    'uid'
                );
            $auth_img_id_arr = $auth_img_url_arr = [];
            foreach ($auth_list as $key => $value) {
                $value['legal_person_idcard_front'] > 0 &&
                    ($auth_img_id_arr[] = $value['legal_person_idcard_front']);
                $value['legal_person_idcard_back'] > 0 &&
                    ($auth_img_id_arr[] = $value['legal_person_idcard_back']);
                $value['license'] > 0 &&
                    ($auth_img_id_arr[] = $value['license']);
                $value['proxy'] > 0 &&
                    ($auth_img_id_arr[] = $value['proxy']);
            }
            if (!empty($auth_img_id_arr)) {
                $auth_img_id_arr = array_unique($auth_img_id_arr);
                $auth_img_url_arr = model('Uploadfile')->getFileUrlBatch(
                    $auth_img_id_arr
                );
            }
            foreach ($auth_list as $key => $value) {
                $auth_list[$key]['legal_person_idcard_front'] = isset(
                    $auth_img_url_arr[$value['legal_person_idcard_front']]
                )
                    ? $auth_img_url_arr[$value['legal_person_idcard_front']]
                    : '';
                $auth_list[$key]['legal_person_idcard_back'] = isset(
                    $auth_img_url_arr[$value['legal_person_idcard_back']]
                )
                    ? $auth_img_url_arr[$value['legal_person_idcard_back']]
                    : '';
                $auth_list[$key]['license'] = isset(
                    $auth_img_url_arr[$value['license']]
                )
                    ? $auth_img_url_arr[$value['license']]
                    : '';
                $auth_list[$key]['proxy'] = isset(
                    $auth_img_url_arr[$value['proxy']]
                )
                    ? $auth_img_url_arr[$value['proxy']]
                    : '';
            }
        } else {
            $job_total_list = [];
            $auth_list = [];
            $member_list = [];
            $company_contact_list = [];
        }
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($list as $key => $value) {
            $value['companyname'] = htmlspecialchars_decode($value['companyname'],ENT_QUOTES);
            $value['setmeal_name'] = $value['setmeal_name']!='' ? $value['setmeal_name'] : '未开通套餐';
            $value['setmeal_overtime'] = ($value['setmeal_deadline']>time() || $value['setmeal_deadline']==0)?0:1;
            $value['setmeal_deadline_text'] = ($value['setmeal_deadline']==0)?'无限期':(date('Y-m-d',$value['setmeal_deadline']).'到期');
            $value['jobs_num'] = isset($job_total_list[$value['uid']])
                ? $job_total_list[$value['uid']]
                : 0;
            $value['auth_status'] = $value['audit'];
            if (isset($auth_list[$value['uid']])) {
                $value['has_auth_info'] = 1;
                $value['legal_person_idcard_front'] =
                    $auth_list[$value['uid']]['legal_person_idcard_front'];
                $value['legal_person_idcard_back'] =
                    $auth_list[$value['uid']]['legal_person_idcard_back'];
                $value['license'] = $auth_list[$value['uid']]['license'];
                $value['proxy'] = $auth_list[$value['uid']]['proxy'];
            } else {
                $value['has_auth_info'] = 0;
                $value['legal_person_idcard_front'] = '';
                $value['legal_person_idcard_back'] = '';
                $value['license'] = '';
                $value['proxy'] = '';
            }
            if($value['auth_status']==0 && $value['has_auth_info']==0){
                $value['auth_status'] = 3;
            }
            $value['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $value['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $value['nature_text'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            if (isset($member_list[$value['uid']])) {
                $value['mobile'] = $member_list[$value['uid']]['mobile'];
            } else {
                $value['mobile'] = '';
            }
            if (isset($company_contact_list[$value['uid']])) {
                $value['contact_mobile'] = $company_contact_list[$value['uid']]['mobile'];
            } else {
                $value['contact_mobile'] = '';
            }
            $value['link'] = url('index/company/show', ['id' => $value['id']]);
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
            'companyname' => input('post.companyname/s', '', 'trim'),
            'short_name' => input('post.short_name/s', '', 'trim'),
            'nature' => input('post.nature/d', 0, 'intval'),
            'trade' => input('post.trade/d', 0, 'intval'),
            'logo' => input('post.logo/d', 0, 'intval'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'scale' => input('post.scale/d', 0, 'intval'),
            'registered' => input('post.registered/s', '', 'trim'),
            'currency' => input('post.currency/d', 0, 'intval'),
            'tag' => input('post.tag/a'),
            'audit' => input('post.audit/d', 0, 'intval'),
            'member' => [
                'username' => input('post.member.username/s', '', 'trim'),
                'password' => input('post.member.password/s', '', 'trim'),
                'mobile' => input('post.member.mobile/s', '', 'trim'),
                'utype' => 1
            ],
            'contact' => [
                'contact' => input('post.contact.contact/s', '', 'trim'),
                'mobile' => input('post.contact.mobile/s', '', 'trim'),
                'weixin' => input('post.contact.weixin/s', '', 'trim'),
                'telephone' => input('post.contact.telephone/s', '', 'trim'),
                'qq' => input('post.contact.qq/s', '', 'trim'),
                'email' => input('post.contact.email/s', '', 'trim')
            ],
            'info' => [
                'website' => input('post.info.website/s', '', 'trim'),
                'short_desc' => input('post.info.short_desc/s', '', 'trim'),
                'content' => input('post.info.content/s', '', 'trim'),
                'address' => input('post.info.address/s', '', 'trim')
            ],
            'setmeal' => [
                'points' => input('post.setmeal.points/d', 0, 'intval'),
                'setmeal_id' => input('post.setmeal.setmeal_id/d', 0, 'intval'),
                'charge' => input('post.setmeal.charge/d', 0, 'intval')
            ]
        ];

        if (
            false === ($insert_id = model('Company')->backendAdd($input_data))
        ) {
            $this->ajaxReturn(500, model('Company')->getError());
        }
        model('AdminLog')->record(
            '添加企业。企业ID【' .
                model('Company')->id .
                '】;企业名称【' .
                $input_data['companyname'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');

        if ($id) {
            $info = model('Company')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $info['companyname'] = htmlspecialchars_decode($info['companyname'],ENT_QUOTES);
            $info['short_name'] = htmlspecialchars_decode($info['short_name'],ENT_QUOTES);
            $logoUrl = model('Uploadfile')->getFileUrl($info['logo']);
            // $info['tag'] = $info['tag']==''?[]:explode(",",$info['tag']);
            $info_contact = model('CompanyContact')
                ->where('comid', $id)
                ->find();
            if (empty($info_contact)) {
                $info['contact'] = [];
            } else {
                $info['contact'] = $info_contact->toArray();
                $info['contact']['contact'] = htmlspecialchars_decode($info['contact']['contact'],ENT_QUOTES);
                $info['contact']['weixin'] = htmlspecialchars_decode($info['contact']['weixin'],ENT_QUOTES);
                $info['contact']['telephone'] = htmlspecialchars_decode($info['contact']['telephone'],ENT_QUOTES);
            }
            $info_info = model('CompanyInfo')
                ->where('comid', $id)
                ->find();
            if (empty($info_info)) {
                $info['info'] = [];
            } else {
                $info['info'] = $info_info->toArray();
                $info['info']['address'] = htmlspecialchars_decode($info['info']['address'],ENT_QUOTES);
                $info['info']['short_desc'] = htmlspecialchars_decode($info['info']['short_desc'],ENT_QUOTES);
                $info['info']['content'] = htmlspecialchars_decode($info['info']['content'],ENT_QUOTES);
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'logoUrl' => $logoUrl
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'uid' => input('post.uid/d', 0, 'intval'),
                'companyname' => input('post.companyname/s', '', 'trim'),
                'short_name' => input('post.short_name/s', '', 'trim'),
                'nature' => input('post.nature/d', 0, 'intval'),
                'trade' => input('post.trade/d', 0, 'intval'),
                'logo' => input('post.logo/d', 0, 'intval'),
                'district1' => input('post.district1/d', 0, 'intval'),
                'district2' => input('post.district2/d', 0, 'intval'),
                'district3' => input('post.district3/d', 0, 'intval'),
                'scale' => input('post.scale/d', 0, 'intval'),
                'registered' => input('post.registered/s', '', 'trim'),
                'currency' => input('post.currency/d', 0, 'intval'),
                'tag' => input('post.tag/a'),
                'audit' => input('post.audit/d', 0, 'intval'),
                'contact' => [
                    'contact' => input('post.contact.contact/s', '', 'trim'),
                    'mobile' => input('post.contact.mobile/s', '', 'trim'),
                    'weixin' => input('post.contact.weixin/s', '', 'trim'),
                    'telephone' => input(
                        'post.contact.telephone/s',
                        '',
                        'trim'
                    ),
                    'qq' => input('post.contact.qq/s', '', 'trim'),
                    'email' => input('post.contact.email/s', '', 'trim')
                ],
                'info' => [
                    'website' => input('post.info.website/s', '', 'trim'),
                    'short_desc' => input('post.info.short_desc/s', '', 'trim'),
                    'content' => input('post.info.content/s', '', 'trim'),
                    'address' => input('post.info.address/s', '', 'trim')
                ]
            ];

            if (false === model('Company')->backendEdit($input_data)) {
                $this->ajaxReturn(500, model('Company')->getError());
            }
            model('AdminLog')->record(
                '编辑企业。企业ID【' .
                    $input_data['id'] .
                    '】;企业名称【' .
                    $input_data['companyname'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function setAudit()
    {
        $id = input('post.id/a');
        $audit = input('post.audit/d', 0, 'intval');
        $reason = input('post.reason/s', '', 'trim');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('Company')->setAudit($id, $audit, $reason);
        model('AdminLog')->record(
            '将企业认证状态变更为【' .
                model('Company')->map_audit[$audit] .
                '】。企业ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '审核成功');
    }
    /**
     * 删除企业
     */
    public function delete()
    {
        $uid = input('post.uid/a');
        if (empty($uid)) {
            $this->ajaxReturn(500, '请选择企业');
        }
        model('Member')->deleteMemberByUids($uid);
        model('AdminLog')->record('删除企业。企业UID【'.implode(",",$uid).'】',$this->admininfo);
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 分配客服
     */
    public function setService(){
        $this->checkSetServiceAccess();
        $id = input('post.id/a');
        $cs_id = input('post.cs_id/d',0,'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择企业');
        }
        if (!$cs_id) {
            $this->ajaxReturn(500, '请选择客服');
        }
        model('Company')->save(['cs_id'=>$cs_id],['id'=>['in',$id]]);
        $this->ajaxReturn(200, '分配成功');
    }
    /**
     * 设置显示状态
     */
    public function setDisplay()
    {
        $id = input('post.id/d',0,'intval');
        if ($id==0) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $is_display = input('post.is_display/d',1,'intval');

        model('Company')->where('id',$id)->setField('is_display',$is_display);
        $jobid_arr = model('Job')->where('company_id',$id)->column('id');
        model('Job')->refreshSearchBatch($jobid_arr);
        model('AdminLog')->record(
            '将企业显示状态变更为【' .
                ($is_display==1?'显示':'不显示') .
                '】。企业ID【' .
                $id .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '设置成功');
    }
}
