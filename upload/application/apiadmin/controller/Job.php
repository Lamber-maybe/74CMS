<?php
namespace app\apiadmin\controller;

class Job extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $where = [];
        $list_type = input('get.list_type/s', '', 'trim');
        $audit = input('get.audit/s', '', 'trim');
        $display = input('get.display/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $sort = input('get.sort/s', '', 'trim');

        if ($list_type == 'noaudit') {
            $where['j.audit'] = 0;
        } else {
            if ($audit != '') {
                $where['j.audit'] = ['eq', $audit];
            }
        }
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['j.jobname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['j.id'] = ['eq', intval($keyword)];
                    break;
                case 4:
                    $where['j.company_id'] = ['eq', intval($keyword)];
                    break;
                case 5:
                    $where['j.uid'] = ['eq', intval($keyword)];
                    break;
                case 6:
                    $where['m.mobile'] = ['eq', $keyword];
                    $where['m.utype'] = ['eq', 1];
                    break;
                default:
                    break;
            }
        }
        if ($display != '') {
            $where['j.is_display'] = ['eq', $display];
        }
        if($sort!=''){
            $order = 'refreshtime desc';
        }else{
            $order = 'id desc';
        }
        $total = model('Job')
            ->alias('j')
            ->join(
                config('database.prefix') . 'company c',
                'c.id=j.company_id',
                'LEFT'
            )
            ->join(config('database.prefix').'member m','j.uid=m.uid','LEFT')
            ->where($where)
            ->count();
        $list = model('Job')
            ->alias('j')
            ->join(
                config('database.prefix') . 'company c',
                'c.id=j.company_id',
                'LEFT'
            )
            ->join(config('database.prefix').'member m','j.uid=m.uid','LEFT')
            ->field('j.*,c.companyname,m.mobile as member_mobile')
            ->where($where)
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();
        $jobid_arr = $comid_arr = [];
        foreach ($list as $key => $value) {
            $jobid_arr[] = $value['id'];
            $comid_arr[] = $value['company_id'];
        }

        if (!empty($jobid_arr)) {
            $contact_list = model('JobContact')
                ->where('jid', 'in', $jobid_arr)
                ->column('jid,contact,mobile,use_company_contact', 'jid');
            $contact_company_list = model('CompanyContact')
                ->where('comid', 'in', $comid_arr)
                ->column('comid,contact,mobile', 'comid');
        } else {
            $contact_list = [];
            $contact_company_list = [];
        }
        foreach ($list as $key => $value) {
            if(isset($contact_list[$value['id']])){
                if($contact_list[$value['id']]['use_company_contact']==1){
                    $value['contact'] = isset($contact_company_list[$value['company_id']])?$contact_company_list[$value['company_id']]['contact']:'';
                    $value['mobile'] = isset($contact_company_list[$value['company_id']])?$contact_company_list[$value['company_id']]['mobile']:'';
                }else{
                    $value['contact'] = isset($contact_list[$value['id']])?$contact_list[$value['id']]['contact']:'';
                    $value['mobile'] = isset($contact_list[$value['id']])?$contact_list[$value['id']]['mobile']:'';
                }
            }else{
                $value['contact'] = '';
                $value['mobile'] = '';
            }
            $value['jobname'] = htmlspecialchars_decode($value['jobname'],ENT_QUOTES);
            $value['companyname'] = htmlspecialchars_decode($value['companyname'],ENT_QUOTES);
            $value['job_link'] = url('index/job/show', ['id' => $value['id']]);
            $value['company_link'] = url('index/company/show', [
                'id' => $value['company_id']
            ]);
            $value['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
            ? model('BaseModel')->map_education[$value['education']]
            : '不限';
            $value['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
            ? model('BaseModel')->map_experience[$value['experience']]
            : '不限';
            $value['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $value['sitename'] = config('global_config.sitename');
            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');

        if ($id) {
            $info = model('Job')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $info['jobname'] = htmlspecialchars_decode($info['jobname'],ENT_QUOTES);
            $info['address'] = htmlspecialchars_decode($info['address'],ENT_QUOTES);
            $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
            $info['department'] = htmlspecialchars_decode($info['department'],ENT_QUOTES);
            $info['custom_field_1'] = htmlspecialchars_decode($info['custom_field_1'],ENT_QUOTES);
            $info['custom_field_2'] = htmlspecialchars_decode($info['custom_field_2'],ENT_QUOTES);
            $info['custom_field_3'] = htmlspecialchars_decode($info['custom_field_3'],ENT_QUOTES);

            $info_contact = model('JobContact')
                ->where('jid', $id)
                ->find();
            $info['contact'] = $info_contact->toArray();
            $info['contact']['contact'] = htmlspecialchars_decode($info['contact']['contact'],ENT_QUOTES);
            $info['contact']['weixin'] = htmlspecialchars_decode($info['contact']['weixin'],ENT_QUOTES);
            $info['contact']['telephone'] = htmlspecialchars_decode($info['contact']['telephone'],ENT_QUOTES);

            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'jobname' => input('post.jobname/s', '', 'trim'),
                'emergency' => input('post.emergency/d', 0, 'intval'),
                'stick' => input('post.stick/d', 0, 'intval'),
                'nature' => input('post.nature/d', 0, 'intval'),
                'sex' => input('post.sex/d', 0, 'intval'),
                'category1' => input('post.category1/d', 0, 'intval'),
                'category2' => input('post.category2/d', 0, 'intval'),
                'category3' => input('post.category3/d', 0, 'intval'),
                'minwage' => input('post.minwage/d', 0, 'intval'),
                'maxwage' => input('post.maxwage/d', 0, 'intval'),
                'negotiable' => input('post.negotiable/d', 0, 'intval'),
                'education' => input('post.education/d', 0, 'intval'),
                'experience' => input('post.experience/d', 0, 'intval'),
                'content' => input('post.content/s', '', 'trim'),
                'tag' => input('post.tag/a'),
                'custom_field_1' => input('post.custom_field_1/s', '', 'trim'),
                'custom_field_2' => input('post.custom_field_2/s', '', 'trim'),
                'custom_field_3' => input('post.custom_field_3/s', '', 'trim'),
                'amount' => input('post.amount/d', 0, 'intval'),
                'department' => input('post.department/s', '', 'trim'),
                'minage' => input('post.minage/d', 0, 'intval'),
                'maxage' => input('post.maxage/d', 0, 'intval'),
                'age_na' => input('post.age_na/d', 0, 'intval'),
                'district1' => input('post.district1/d', 0, 'intval'),
                'district2' => input('post.district2/d', 0, 'intval'),
                'district3' => input('post.district3/d', 0, 'intval'),
                'address' => input('post.address/s', '', 'trim'),
                'audit' => input('post.audit/d', 0, 'intval'),
                'is_display' => input('post.is_display/d', 0, 'intval'),
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
                    'email' => input('post.contact.email/s', '', 'trim'),
                    'is_display' => input('post.contact.is_display/d', 0, 'intval'),
                    'use_company_contact' => input('post.contact.use_company_contact/d', 0, 'intval')
                ]
            ];

            if (false === model('Job')->backendEdit($input_data)) {
                $this->ajaxReturn(500, model('Job')->getError());
            }
            model('AdminLog')->record(
                '编辑职位。职位ID【' .
                    $input_data['id'] .
                    '】;职位名称【' .
                    $input_data['jobname'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择职位');
        }
        $list = model('Job')
            ->where('id', 'in', $id)
            ->column('jobname');
        if (false === model('Job')->deleteJobByIds($id)) {
            $this->ajaxReturn(500, model('Job')->getError());
        }
        model('AdminLog')->record(
            '删除职位。职位ID【' .
                implode(',', $id) .
                '】;职位名称【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function setAudit()
    {
        $id = input('post.id/a');
        $audit = input('post.audit/d', 0, 'intval');
        $reason = input('post.reason/s', '', 'trim');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择职位');
        }
        model('Job')->setAudit($id, $audit, $reason);
        model('AdminLog')->record(
            '将职位审核状态变更为【' .
                model('Job')->map_audit[$audit] .
                '】。职位ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '审核成功');
    }
    public function refresh()
    {
        $idArr = input('post.id/a');
        $uid = input('post.uid/a');
        if (empty($idArr) || empty($uid)) {
            $this->ajaxReturn(500, '请选择职位');
        }

        // 刷新职位信息 chenyang 2022年3月21日11:27:34
        $refreshParams = [
            'id' => $idArr,
        ];
        /**
         * 刷新职位
         * yx - 2022.11.11
         * [旧]:
         * $result = model('Job')->refreshJobData($refreshParams);
         * [新]:
         * $result = model('Job')->refreshJobMind($refreshParams);
         */
        $result = model('Job')->refreshJobMind($refreshParams);
        if ($result['status'] === false) {
            $this->ajaxReturn(500, $result['msg']);
        }

        $jobIdArr = array_column($result['data'], 'id');
        model('AdminLog')->record(
            '刷新职位；职位ID【' .
                implode(',', $jobIdArr) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '成功刷新'. count($jobIdArr) .'条职位');
    }
}
