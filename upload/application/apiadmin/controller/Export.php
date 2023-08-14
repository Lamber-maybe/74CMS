<?php

namespace app\apiadmin\controller;

class Export extends \app\common\controller\Backend
{
    /**
     * 导出职位
     */
    public function job()
    {
        $where = [];
        $daterange = input('post.daterange/a', []);
        $education = input('post.education/d', 0, 'intval');
        $experience = input('post.experience/d', 0, 'intval');
        $limit = input('post.limit/d', 100, 'intval');
        $offset = input('post.offset/d', 1, 'intval');
        $list = model('Job')->field(
            'id,jobname,company_id,category,minwage,maxwage,negotiable,education,experience,amount,department,minage,maxage,age_na,district,address,addtime,refreshtime,audit,is_display,click'
        );
        if (!empty($daterange)) {
            $start = strtotime($daterange[0]);
            $end = strtotime($daterange[1]);
            $list = $list->where('addtime', 'BETWEEN', [$start, $end]);
        }
        if ($education > 0) {
            $list = $list->where('education', 'EQ', $education);
        }
        if ($experience > 0) {
            $list = $list->where('experience', 'EQ', $experience);
        }
        $list = $list
            ->order('id asc')
            ->limit($offset - 1, $limit)
            ->select();
        if (empty($list)) {
            $this->ajaxReturn(500, '没有找到匹配的数据');
        }
        $comid_arr = $comlist = $jobid_arr = $contactlist = [];
        foreach ($list as $key => $value) {
            $comid_arr[] = $value['company_id'];
            $jobid_arr[] = $value['id'];
        }
        if (!empty($comid_arr)) {
            $comlist = model('Company')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'company_contact b',
                    'a.id=b.comid',
                    'LEFT'
                )
                ->where('a.id', 'in', $comid_arr)
                ->column(
                    'a.id,a.companyname,b.contact,b.mobile,b.weixin,b.telephone,b.qq,b.email',
                    'a.id'
                );
        }
        if (!empty($jobid_arr)) {
            $contactlist = model('JobContact')
                ->where('jid', 'in', $jobid_arr)
                ->column(
                    'jid,contact,mobile,weixin,telephone,qq,email,use_company_contact',
                    'jid'
                );
        }
        $return = [];
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $count = 0;
        foreach ($list as $key => $value) {
            $arr['number'] = ++$count;
            $arr['id'] = $value['id'];
            $arr['jobname'] = $value['jobname'];
            $arr['companyname'] = isset($comlist[$value['company_id']])
                ? $comlist[$value['company_id']]['companyname']
                : '';
            $arr['category'] = isset($category_job_data[$value['category']])
                ? $category_job_data[$value['category']]
                : '';
            $arr['wage'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $arr['education'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '不限';
            $arr['experience'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '不限';
            $arr['amount'] =
                $value['amount'] == 0 ? '若干' : $value['amount'] . '人';
            $arr['department'] = $value['department'];
            if ($value['age_na'] == 1) {
                $arr['age'] = '不限';
            } elseif ($value['minage'] > 0 && $value['maxage'] > 0) {
                $arr['age'] = $value['minage'] . '-' . $value['maxage'];
            } else {
                $arr['age'] = '不限';
            }
            $arr['district'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $arr['address'] = $value['address'];
            $arr['addtime'] = date('Y-m-d H:i', $value['addtime']);
            $arr['refreshtime'] = date('Y-m-d H:i', $value['refreshtime']);
            $arr['audit'] = isset(model('Job')->map_audit[$value['audit']])
                ? model('Job')->map_audit[$value['audit']]
                : '审核未通过';
            $arr['is_display'] =
                $value['is_display'] == 1 ? '正常' : '暂停招聘';
            $arr['click'] = $value['click'];
            $arr['contact'] = '';
            $arr['mobile'] = '';
            $arr['weixin'] = '';
            $arr['telephone'] = '';
            $arr['qq'] = '';
            $arr['email'] = '';
            if (isset($contactlist[$value['id']])) {
                $contact_info = $contactlist[$value['id']];
                if ($contact_info['use_company_contact'] == 1) {
                    if (isset($comlist[$value['company_id']])) {
                        $arr['contact'] =
                            $comlist[$value['company_id']]['contact'];
                        $arr['mobile'] =
                            $comlist[$value['company_id']]['mobile'];
                        $arr['weixin'] =
                            $comlist[$value['company_id']]['weixin'];
                        $arr['telephone'] =
                            $comlist[$value['company_id']]['telephone'];
                        $arr['qq'] = $comlist[$value['company_id']]['qq'];
                        $arr['email'] = $comlist[$value['company_id']]['email'];
                    }
                } else {
                    $arr['contact'] = $contact_info['contact'];
                    $arr['mobile'] = $contact_info['mobile'];
                    $arr['weixin'] = $contact_info['weixin'];
                    $arr['telephone'] = $contact_info['telephone'];
                    $arr['qq'] = $contact_info['qq'];
                    $arr['email'] = $contact_info['email'];
                }
            }
            $return[] = $arr;
        }
        if (!empty($return)) {
            model('AdminLog')->record(
                '导出职位信息【' . count($return) . '条】',
                $this->admininfo
            );
        }

        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }
    /**
     * 导出职位ById
     */
    public function jobById()
    {
        $id = input('post.id/a');
        if(empty($id)){
            $this->ajaxReturn(500, '请选择要导出的职位');
        }
        $list = model('Job')->field('id,jobname,company_id,category,minwage,maxwage,negotiable,education,experience,amount,department,minage,maxage,age_na,district,address,addtime,refreshtime,audit,is_display,click')
            ->order('id asc')
            ->whereIn('id',$id)
            ->select();
        if (empty($list)) {
            $this->ajaxReturn(500, '没有找到匹配的数据');
        }
        $comid_arr = $comlist = $jobid_arr = $contactlist = [];
        foreach ($list as $key => $value) {
            $comid_arr[] = $value['company_id'];
            $jobid_arr[] = $value['id'];
        }
        if (!empty($comid_arr)) {
            $comlist = model('Company')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'company_contact b',
                    'a.id=b.comid',
                    'LEFT'
                )
                ->where('a.id', 'in', $comid_arr)
                ->column(
                    'a.id,a.companyname,b.contact,b.mobile,b.weixin,b.telephone,b.qq,b.email',
                    'a.id'
                );
        }
        if (!empty($jobid_arr)) {
            $contactlist = model('JobContact')
                ->where('jid', 'in', $jobid_arr)
                ->column(
                    'jid,contact,mobile,weixin,telephone,qq,email,use_company_contact',
                    'jid'
                );
        }
        $return = [];
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $count = 0;
        foreach ($list as $key => $value) {
            $arr['number'] = ++$count;
            $arr['id'] = $value['id'];
            $arr['jobname'] = $value['jobname'];
            $arr['companyname'] = isset($comlist[$value['company_id']])
                ? $comlist[$value['company_id']]['companyname']
                : '';
            $arr['category'] = isset($category_job_data[$value['category']])
                ? $category_job_data[$value['category']]
                : '';
            $arr['wage'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $arr['education'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '不限';
            $arr['experience'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '不限';
            $arr['amount'] =
                $value['amount'] == 0 ? '若干' : $value['amount'] . '人';
            $arr['department'] = $value['department'];
            if ($value['age_na'] == 1) {
                $arr['age'] = '不限';
            } elseif ($value['minage'] > 0 && $value['maxage'] > 0) {
                $arr['age'] = $value['minage'] . '-' . $value['maxage'];
            } else {
                $arr['age'] = '不限';
            }
            $arr['district'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $arr['address'] = $value['address'];
            $arr['addtime'] = date('Y-m-d H:i', $value['addtime']);
            $arr['refreshtime'] = date('Y-m-d H:i', $value['refreshtime']);
            $arr['audit'] = isset(model('Job')->map_audit[$value['audit']])
                ? model('Job')->map_audit[$value['audit']]
                : '审核未通过';
            $arr['is_display'] =
                $value['is_display'] == 1 ? '正常' : '暂停招聘';
            $arr['click'] = $value['click'];
            $arr['contact'] = '';
            $arr['mobile'] = '';
            $arr['weixin'] = '';
            $arr['telephone'] = '';
            $arr['qq'] = '';
            $arr['email'] = '';
            if (isset($contactlist[$value['id']])) {
                $contact_info = $contactlist[$value['id']];
                if ($contact_info['use_company_contact'] == 1) {
                    if (isset($comlist[$value['company_id']])) {
                        $arr['contact'] =
                            $comlist[$value['company_id']]['contact'];
                        $arr['mobile'] =
                            $comlist[$value['company_id']]['mobile'];
                        $arr['weixin'] =
                            $comlist[$value['company_id']]['weixin'];
                        $arr['telephone'] =
                            $comlist[$value['company_id']]['telephone'];
                        $arr['qq'] = $comlist[$value['company_id']]['qq'];
                        $arr['email'] = $comlist[$value['company_id']]['email'];
                    }
                } else {
                    $arr['contact'] = $contact_info['contact'];
                    $arr['mobile'] = $contact_info['mobile'];
                    $arr['weixin'] = $contact_info['weixin'];
                    $arr['telephone'] = $contact_info['telephone'];
                    $arr['qq'] = $contact_info['qq'];
                    $arr['email'] = $contact_info['email'];
                }
            }
            $return[] = $arr;
        }
        if (!empty($return)) {
            model('AdminLog')->record(
                '导出职位信息【' . count($return) . '条】',
                $this->admininfo
            );
        }

        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }
    /**
     * 导出企业
     */
    public function company()
    {
        $where = [];
        $daterange = input('post.daterange/a', []);
        $audit = input('post.audit/s', '', 'trim');
        $setmeal = input('post.setmeal/d', 0, 'intval');
        $setmeal_overtime = input('post.setmeal_overtime/s', '', 'trim');
        $trade = input('post.trade/d', 0, 'intval');
        $limit = input('post.limit/d', 100, 'intval');
        $offset = input('post.offset/d', 1, 'intval');
        $list = model('Company')
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
        if (!empty($daterange)) {
            $start = strtotime($daterange[0]);
            $end = strtotime($daterange[1]);
            $list = $list->where('a.addtime', 'BETWEEN', [$start, $end]);
        }
        if ($audit != '') {
            $list = $list->where('a.audit', 'EQ', intval($audit));
        }
        if ($setmeal > 0) {
            $list = $list->where('c.setmeal_id', 'EQ', $setmeal);
        }
        if ($setmeal_overtime != '') {
            $setmeal_overtime = intval($setmeal_overtime);
            if ($setmeal_overtime == 0) {
                $list = $list->where('c.deadline', 'ELT', time());
            } else {
                $list = $list->where(
                    'c.deadline',
                    'ELT',
                    strtotime('+' . $setmeal_overtime . 'day')
                );
            }
        }
        if ($trade > 0) {
            $list = $list->where('a.trade', 'EQ', $trade);
        }
        $list = $list
            ->order('a.id asc')
            ->limit($offset - 1, $limit)
            ->select();
        if (empty($list)) {
            $this->ajaxReturn(500, '没有找到匹配的数据');
        }
        $return = [];
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $count = 0;
        foreach ($list as $key => $value) {
            $arr['number'] = ++$count;
            $arr['id'] = $value['id'];
            $arr['companyname'] = $value['companyname'];
            $arr['short_name'] = $value['short_name'];
            $arr['nature'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            $arr['trade'] = isset($category_data['QS_trade'][$value['trade']])
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $arr['district'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $arr['scale'] = isset($category_data['QS_scale'][$value['scale']])
                ? $category_data['QS_scale'][$value['scale']]
                : '';
            $arr['registered'] =
                $value['registered'] .
                '万元' .
                ($value['currency'] == 0 ? '人民币' : '美元');
            $arr['audit'] = isset(model('Company')->map_audit[$value['audit']])
                ? model('Company')->map_audit[$value['audit']]
                : '认证未通过';
            $arr['addtime'] = date('Y-m-d H:i', $value['addtime']);
            $arr['refreshtime'] = date('Y-m-d H:i', $value['refreshtime']);
            $arr['contact'] = $value['contact'];
            $arr['mobile'] = $value['mobile'];
            $arr['weixin'] = $value['weixin'];
            $arr['telephone'] = $value['telephone'];
            $arr['qq'] = $value['qq'];
            $arr['email'] = $value['email'];
            $return[] = $arr;
        }

        if (!empty($return)) {
            model('AdminLog')->record(
                '导出企业信息【' . count($return) . '条】',
                $this->admininfo
            );
        }

        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }
    /**
     * 导出企业ById
     */
    public function companyById()
    {
        $id = input('post.id/a');
        if(empty($id)){
            $this->ajaxReturn(500, '请选择要导出的企业');
        }
        $list = model('Company')
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
            )
            ->whereIn('a.id',$id)
            ->order('a.id asc')
            ->select();
        if (empty($list)) {
            $this->ajaxReturn(500, '没有找到匹配的数据');
        }
        $return = [];
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $count = 0;
        foreach ($list as $key => $value) {
            $arr['number'] = ++$count;
            $arr['id'] = $value['id'];
            $arr['companyname'] = $value['companyname'];
            $arr['short_name'] = $value['short_name'];
            $arr['nature'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            $arr['trade'] = isset($category_data['QS_trade'][$value['trade']])
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $arr['district'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $arr['scale'] = isset($category_data['QS_scale'][$value['scale']])
                ? $category_data['QS_scale'][$value['scale']]
                : '';
            $arr['registered'] =
                $value['registered'] .
                '万元' .
                ($value['currency'] == 0 ? '人民币' : '美元');
            $arr['audit'] = isset(model('Company')->map_audit[$value['audit']])
                ? model('Company')->map_audit[$value['audit']]
                : '认证未通过';
            $arr['addtime'] = date('Y-m-d H:i', $value['addtime']);
            $arr['refreshtime'] = date('Y-m-d H:i', $value['refreshtime']);
            $arr['contact'] = $value['contact'];
            $arr['mobile'] = $value['mobile'];
            $arr['weixin'] = $value['weixin'];
            $arr['telephone'] = $value['telephone'];
            $arr['qq'] = $value['qq'];
            $arr['email'] = $value['email'];
            $return[] = $arr;
        }

        if (!empty($return)) {
            model('AdminLog')->record(
                '导出企业信息【' . count($return) . '条】',
                $this->admininfo
            );
        }

        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }

    /**
     * 导出简历
     */
    public function resume()
    {
        $where = [];
        $daterange = input('post.daterange/a', []);
        $education = input('post.education/d', 0, 'intval');
        $experience = input('post.experience/d', 0, 'intval');
        $limit = input('post.limit/d', 100, 'intval');
        $offset = input('post.offset/d', 1, 'intval');
        $list = model('Resume')
            ->alias('a')
            ->join(
                config('database.prefix') . 'resume_contact b',
                'a.id=b.rid',
                'LEFT'
            )
            ->field(
                'a.id,a.fullname,a.sex,a.birthday,a.residence,a.height,a.marriage,a.education,a.enter_job_time,a.householdaddress,a.major,a.idcard,a.current,a.addtime,a.refreshtime,b.mobile,b.weixin,b.qq,b.email'
            );
        if (!empty($daterange)) {
            $start = strtotime($daterange[0]);
            $end = strtotime($daterange[1]);
            $list = $list->where('a.addtime', 'BETWEEN', [$start, $end]);
        }
        if ($education > 0) {
            $list = $list->where('education', 'EQ', $education);
        }
        if ($experience > 0) {
            $list = $list->where('experience', 'EQ', $experience);
        }
        $list = $list
            ->order('a.id asc')
            ->limit($offset - 1, $limit)
            ->select();
        if (empty($list)) {
            $this->ajaxReturn(500, '没有找到匹配的数据');
        }
        $resumeid_arr = $intention_arr = [];
        foreach ($list as $key => $value) {
            $resumeid_arr[] = $value['id'];
        }
        $return = [];
        $category_data = model('Category')->getCache();
        $category_major_data = model('CategoryMajor')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        if (!empty($resumeid_arr)) {
            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
        }

        $count = 0;
        foreach ($list as $key => $value) {
            $arr['number'] = ++$count;
            $arr['id'] = $value['id'];
            $arr['fullname'] = $value['fullname'];
            $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                ? model('Resume')->map_sex[$value['sex']]
                : '未知';
            $arr['age'] =
                $value['birthday'] != ''
                    ? date('Y') - intval($value['birthday'])
                    : '未知';
            $arr['residence'] = $value['residence'];
            $arr['height'] = $value['height'];
            $arr['marriage'] = isset(
                model('Resume')->map_marriage[$value['marriage']]
            )
                ? model('Resume')->map_marriage[$value['marriage']]
                : '未知';
            $arr['education'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '未知';
            $arr['experience'] =
                $value['enter_job_time'] == 0
                    ? '尚未工作'
                    : format_date($value['enter_job_time']);
            $arr['householdaddress'] = $value['householdaddress'];
            $arr['major'] =
                $value['major'] && isset($category_major_data[$value['major']])
                    ? $category_major_data[$value['major']]
                    : '';
            $arr['idcard'] = $value['idcard'];
            $arr['current'] = isset(
                $category_data['QS_current'][$value['current']]
            )
                ? $category_data['QS_current'][$value['current']]
                : '';
            $district_arr = $category_arr = $wage_arr = $nature_arr = $trade_arr = [];
            if (isset($intention_arr[$value['id']])) {
                foreach ($intention_arr[$value['id']] as $k => $v) {
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
            $arr['addtime'] = date('Y-m-d H:i', $value['addtime']);
            $arr['refreshtime'] = date('Y-m-d H:i', $value['refreshtime']);
            $arr['mobile'] = $value['mobile'];
            $arr['weixin'] = $value['weixin'];
            $arr['qq'] = $value['qq'];
            $arr['email'] = $value['email'];
            $return[] = $arr;
        }

        if (!empty($return)) {
            model('AdminLog')->record(
                '导出简历信息【' . count($return) . '条】',
                $this->admininfo
            );
        }

        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }
}
