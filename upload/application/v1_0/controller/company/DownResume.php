<?php
/**
 * 下载简历列表
 */
namespace app\v1_0\controller\company;

class DownResume extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
    }
    public function index()
    {
        $where['a.uid'] = $this->userinfo->uid;
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');

        $list = model('CompanyDownResume')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->join(config('database.prefix') . 'resume_remark rr', 'a.resume_id=rr.resume_id and a.comid=rr.comid', 'left')
            ->field('a.id,a.resume_id,a.addtime,b.fullname,b.display_name,b.high_quality,b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,b.current,b.audit,b.specialty,IFNULL(rr.remark,"") as remark')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $resumeid_arr = [];
        $resumelist = [];
        $intention_arr = [];
        $photo_id_arr = [];
        $photo_data = [];
        $work_list = [];
        foreach ($list as $key => $value) {
            $resumeid_arr[] = $value['resume_id'];
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if (!empty($photo_id_arr)) {
            $photo_data = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }
        if (!empty($resumeid_arr)) {
            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }

            $work_data = model('ResumeWork')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id desc')
                ->select();
            foreach ($work_data as $key => $value) {
                if (isset($work_list[$value['rid']])) {
                    //只取第一份工作经历（最后填写的一份）
                    continue;
                }
                $work_list[$value['rid']] = $value;
            }
        }

        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($list as $key => $value) {
            $value['companyname'] = '';
            $value['jobname'] = '';
            foreach($work_data as $k=>$v)
            {
                if ($v['rid'] == $value['resume_id'])
                {
                    $value['companyname'] = $v['companyname'];
                    $value['jobname'] = $v['jobname'];
                }
            }
            $value['fullname'] = $value['fullname'];
            $value['high_quality'] = $value['high_quality'];
            $value['sex_text'] = model('Resume')->map_sex[
                $value['sex']
            ];
            $value['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                    $value['education']
                ]
                : '';
            $value['experience_text'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']) . '经验';
            $value['current_text'] = isset(
                $category_data['QS_current'][$value['current']]
            )
                ? $category_data['QS_current'][$value['current']]
                : '';
            if (isset($work_list[$value['resume_id']])) {
                $value['recent_work'] =
                    $work_list[$value['resume_id']]['jobname'];
            } else {
                $value['recent_work'] = '';
            }
            $value['age'] = date('Y') - intval($value['birthday']);
            $district_arr = $category_arr = [];
            if (isset($intention_arr[$value['resume_id']])) {
                foreach ($intention_arr[$value['resume_id']] as $k => $v) {
                    if ($v['trade']) {
                        $trade_arr[] =
                            $category_data['QS_trade'][$v['trade']];
                    }
                    if ($v['nature']) {
                        $nature_arr[] = model('Resume')->map_nature[
                            $v['nature']
                        ];
                    }
                    $wage_arr[0] = $v['minwage'] . '-' . $v['maxwage'];
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
                $value['intention_trade'] = implode(',', $trade_arr);
            } else {
                $value['intention_trade'] = '';
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $value['intention_jobs'] = implode(',', $category_arr);
            } else {
                $value['intention_jobs'] = '';
            }
            if (!empty($wage_arr)) {
                $wage_arr = array_unique($wage_arr);
                $value['intention_wage'] = implode(',', $wage_arr);
            } else {
                $value['intention_wage'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $value['intention_district'] = implode(',', $district_arr);
            } else {
                $value['intention_district'] = '';
            }
            if (!empty($nature_arr)) {
                $nature_arr = array_unique($nature_arr);
                $value['intention_nature'] = implode(',', $nature_arr);
            } else {
                $value['intention_nature'] = '';
            }
            $value['photo_img_src'] = isset(
                $photo_data[$value['photo_img']]
            )
                ? $photo_data[$value['photo_img']]
                : default_empty('photo');

            $value['resume_link_url_web'] = url('index/resume/show', ['id' => $value['resume_id']], true, $this->sub_site_domain);
            $list[$key] = $value;
        }

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    
    public function total()
    {
        $where['a.uid'] = $this->userinfo->uid;
        $total = model('CompanyDownResume')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }

    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        $info = model('CompanyDownResume')->where(['id' => ['eq', $id],'uid' => $this->userinfo->uid])->find();
        model('CompanyDownResume')
            ->where([
                'id' => ['eq', $id],
                'uid' => $this->userinfo->uid
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除已下载简历【简历id：'.$info->resume_id.'】');
        $this->ajaxReturn(200, '删除成功');
    }
}
