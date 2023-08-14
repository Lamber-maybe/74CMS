<?php
/**
 * 微海报
 */
namespace app\v1_0\controller\company;

class Microposte extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 根据职位数获取模板
     */
    public function tpl()
    {
        $jobnum = input('get.jobnum/d',0,'intval');
        $list = model('MicroposteTpl')->where('jobnum',$jobnum)->select();
        $this->ajaxReturn(200, '获取数据成功', ['items'=>$list]);
    }
    /**
     * 根据职位id获取职位信息
     */
    public function joblist(){
        $jobid = input('post.jobid/a',[]);
        if(empty($jobid)){
            $this->ajaxReturn(500, '请选择职位');
        }
        $list = model('Job')->whereIn('id',$jobid)->select();
        $return['items'] = $this->get_datalist($list);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    protected function get_datalist($joblist)
    {
        $result_data_list = $comid_arr = $cominfo_arr = $logo_id_arr = $logo_arr = [];
        foreach ($joblist as $key => $value) {
            $comid_arr[] = $value['company_id'];
        }
        if (!empty($comid_arr)) {
            $cominfo_arr = model('Company')
                ->where('id', 'in', $comid_arr)
                ->column(
                    'id,companyname,audit,logo,nature,scale,trade',
                    'id'
                );
            foreach ($cominfo_arr as $key => $value) {
                $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
            }
            if (!empty($logo_id_arr)) {
                $logo_arr = model('Uploadfile')->getFileUrlBatch(
                    $logo_id_arr
                );
            }
        }
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($joblist as $key => $val) {
            $tmp_arr = [];
            $tmp_arr['id'] = $val['id'];
            $tmp_arr['company_id'] = $val['company_id'];
            $tmp_arr['jobname'] = $val['jobname'];
            $tmp_arr['emergency'] = $val['emergency'];
            $tmp_arr['stick'] = $val['stick'];
            if (isset($cominfo_arr[$val['company_id']])) {
                $tmp_arr['companyname'] =
                    $cominfo_arr[$val['company_id']]['companyname'];
                $tmp_arr['company_audit'] =
                    $cominfo_arr[$val['company_id']]['audit'];
                $tmp_arr['company_logo'] = isset(
                    $logo_arr[$cominfo_arr[$val['company_id']]['logo']]
                )
                ? $logo_arr[$cominfo_arr[$val['company_id']]['logo']]
                : default_empty('logo');
                $tmp_arr['company_trade_text'] = isset(
                    $category_data['QS_trade'][
                        $cominfo_arr[$val['company_id']]['trade']
                    ]
                )
                ? $category_data['QS_trade'][
                    $cominfo_arr[$val['company_id']]['trade']
                ]
                : '';
                $tmp_arr['company_scale_text'] = isset(
                    $category_data['QS_scale'][
                        $cominfo_arr[$val['company_id']]['scale']
                    ]
                )
                ? $category_data['QS_scale'][
                    $cominfo_arr[$val['company_id']]['scale']
                ]
                : '';
                $tmp_arr['company_nature_text'] = isset(
                    $category_data['QS_company_type'][
                        $cominfo_arr[$val['company_id']]['nature']
                    ]
                )
                ? $category_data['QS_company_type'][
                    $cominfo_arr[$val['company_id']]['nature']
                ]
                : '';
            } else {
                $tmp_arr['companyname'] = '';
                $tmp_arr['company_audit'] = 0;
                $tmp_arr['company_logo'] = '';
                $tmp_arr['company_trade_text'] = '';
                $tmp_arr['company_scale_text'] = '';
                $tmp_arr['company_nature_text'] = '';
            }

            if ($val['district']) {
                $tmp_arr['district_text'] = isset(
                    $category_district_data[$val['district']]
                )
                ? $category_district_data[$val['district']]
                : '';
            } else {
                $tmp_arr['district_text'] = '';
            }
            $tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                $val['minwage'],
                $val['maxwage'],
                $val['negotiable']
            );

            $tmp_arr['education_text'] = isset(
                model('BaseModel')->map_education[$val['education']]
            )
            ? model('BaseModel')->map_education[$val['education']]
            : '学历不限';
            $tmp_arr['experience_text'] = isset(
                model('BaseModel')->map_experience[$val['experience']]
            )
            ? model('BaseModel')->map_experience[$val['experience']]
            : '经验不限';
            $tmp_arr['tag'] = [];
            if ($val['tag']) {
                $tag_arr = explode(',', $val['tag']);
                foreach ($tag_arr as $k => $v) {
                    if (
                        is_numeric($v) &&
                        isset($category_data['QS_jobtag'][$v])
                    ) {
                        $tmp_arr['tag'][] = $category_data['QS_jobtag'][$v];
                    } else {
                        $tmp_arr['tag'][] = $v;
                    }
                }
            }
            $tmp_arr['refreshtime'] = daterange_format(
                $val['addtime'],
                $val['refreshtime']
            );
            $tmp_arr['content'] = $val['content'];
            $tmp_arr['address'] = $val['address'];
            $result_data_list[] = $tmp_arr;
        }
        return $result_data_list;
    }
}
