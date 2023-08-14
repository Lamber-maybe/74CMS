<?php

namespace app\apiadmin\controller;


class Classify extends \app\common\controller\Backend
{
    public function index()
    {
        $type = input('get.type', '', 'trim');
        if (!$type) {
            $this->ajaxReturn(500, '请选择');
        }
        $return = [];
        if (false !== stripos($type, ',')) {
            $type_arr = explode(',', $type);
            foreach ($type_arr as $key => $value) {
                if (method_exists($this, $value)) {
                    $return[$value] = $this->$value(input('get.'));
                }
            }
        } else {
            if (method_exists($this, $type)) {
                $return = $this->$type(input('get.'));
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    private function platform($params = [])
    {
        $list = model('BaseModel')->map_platform;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function adPlatform($params = [])
    {
        $list = model('BaseModel')->map_ad_platform;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function articleCategory($params = [])
    {
        $list = model('ArticleCategory')->getCache();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function hrtoolCategory($params = [])
    {
        $list = model('HrtoolCategory')->getCache();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function helpCategory($params = [])
    {
        $list = model('HelpCategory')->column('id,name');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function adCategory($params = [])
    {
        $return = model('AdCategory')->getTreeCache();
        $return = json_encode($return);
        $return = str_replace('id', 'value', $return);
        $return = json_decode($return, true);
        return $return;
    }
    private function setmealList($params = [])
    {
        $where = [];
        if (
            isset($params['is_display']) &&
            intval($params['is_display']) == 1
        ) {
            $where['display'] = 1;
        }
        $list = model('Setmeal')
            ->where($where)
            ->order('sort_id desc,id asc')
            ->column('id,name');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function education($params = [])
    {
        $list = model('BaseModel')->map_education;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function experience($params = [])
    {
        $list = model('BaseModel')->map_experience;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function major($params = [])
    {
        $toplist = model('CategoryMajor')->getCache('0');
        $return = [];
        foreach ($toplist as $key => $value) {
            $arr = [];
            $arr['value'] = $key;
            $arr['label'] = $value;
            $childrenlist = model('CategoryMajor')->getCache($key . '');
            if ($childrenlist) {
                foreach ($childrenlist as $k => $v) {
                    $subarr['value'] = $k;
                    $subarr['label'] = $v;
                    $arr['children'][] = $subarr;
                }
            }
            $return[] = $arr;
        }
        return $return;
    }
    private function current($params = [])
    {
        $list = model('Category')->getCache('QS_current');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function resumeSex($params = [])
    {
        $list = model('Resume')->map_sex;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function marriage($params = [])
    {
        $list = model('Resume')->map_marriage;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function resumeNature($params = [])
    {
        $list = model('Resume')->map_nature;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function resumeAudit($params = [])
    {
        $list = model('Resume')->map_audit;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function jobcategory($params = [])
    {
        $return = model('CategoryJob')->getTreeCache();
        $return = json_encode($return);
        $return = str_replace('id', 'value', $return);
        $return = json_decode($return, true);
        return $return;
    }
    private function citycategory($params = [])
    {
        $return = model('CategoryDistrict')->getTreeCache();
        $return = json_encode($return);
        $return = str_replace('id', 'value', $return);
        $return = json_decode($return, true);
        return $return;
    }
    private function trade($params = [])
    {
        $list = model('Category')->getCache('QS_trade');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function language($params = [])
    {
        $list = model('Category')->getCache('QS_language');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function languageLevel($params = [])
    {
        $list = model('Category')->getCache('QS_language_level');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function resumeTag($params = [])
    {
        $list = model('Category')->getCache('QS_resumetag');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function companyAudit($params = [])
    {
        $list = model('Company')->map_audit;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function companyNature($params = [])
    {
        $list = model('Category')->getCache('QS_company_type');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function companyScale($params = [])
    {
        $list = model('Category')->getCache('QS_scale');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function jobTag($params = [])
    {
        $list = model('Category')->getCache('QS_jobtag');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function jobAudit($params = [])
    {
        $list = model('Job')->map_audit;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function jobDisplay($params = [])
    {
        $list = model('Job')->map_display;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function jobNature($params = [])
    {
        $list = model('Job')->map_nature;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function companyImgAudit($params = [])
    {
        $list = model('CompanyImg')->map_audit;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function resumeImgAudit($params = [])
    {
        $list = model('ResumeImg')->map_audit;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function couponList($params = [])
    {
        $list = model('Coupon')
            ->order('id asc')
            ->column('id,name');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function companyList($params = [])
    {
        $list = model('Company')
            ->order('id asc')
            ->column('uid,companyname');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function orderStatus($params = [])
    {
        $list = model('Order')->map_status;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function orderPayment($params = [])
    {
        $list = model('Order')->map_payment;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function orderServiceType($params = [])
    {
        $list = array_merge(
            model('Order')->map_service_type_company,
            model('Order')->map_service_type_personal
        );
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function orderServiceTypeCompany($params = [])
    {
        $list = model('Order')->map_service_type_company;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function orderServiceTypePersonal($params = [])
    {
        $list = model('Order')->map_service_type_personal;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function resumeModule($params = [])
    {
        $list = model('ResumeModule')->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $value['module_name'];
            $arr['name'] = $value['module_cn'];
            $return[] = $arr;
        }
        return $return;
    }
    private function wage($params = [])
    {
        $list = [
            '0' => '面议',
            '0-1500' => '1500以下',
            '1500-3000' => '1500~3000',
            '3000-5000' => '3000~5000',
            '5000-8000' => '5000~8000',
            '8000-10000' => '8000~10000',
            '10000-15000' => '10000~15000',
            '15000' => '15000以上'
        ];
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function customerService($params = [])
    {
        $list = model('CustomerService')->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['name'] = $value['name'];
            $arr['status'] = $value['status'];
            $return[] = $arr;
        }
        return $return;
    }
    private function tipoffJob($params = [])
    {
        $list = model('Tipoff')->map_type_job;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function tipoffResume($params = [])
    {
        $list = model('Tipoff')->map_type_resume;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function feedback($params = [])
    {
        $list = model('Feedback')->map_type;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function navPage($params = [])
    {
        return model('Navigation')->map_page;
    }

    private function setmealOpenType(){
        $list = model('MemberSetmealOpenLog')->open_type_arr;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
            $return[] = $arr;
        }
        return $return;
    }
    private function tplOfIndex($params = [])
    {
        $list = model('Tpl')->where('type','index')->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $value['alias'];
            $arr['name'] = $value['title'];
            $return[] = $arr;
        }
        return $return;
    }
}
