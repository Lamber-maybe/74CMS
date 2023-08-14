<?php

namespace app\v1_0\controller\home;

class Classify extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
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
    private function resumeStrongTag($params = [])
    {
        $list = model('PersonalServiceTag')->map_tag;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['name'] = $value;
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
}
