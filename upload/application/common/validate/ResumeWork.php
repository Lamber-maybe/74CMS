<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeWork extends BaseValidate
{
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'starttime' => 'require|number|checkStarttime',
        'endtime' => 'require|number|checkEndtime',
        'todate' => 'require|in:0,1',
        'companyname' => 'require|max:30',
        'jobname' => 'require|max:30',
        'duty' => 'require'
    ];
    protected $field = [
        'starttime' => '入职时间',
        'endtime' => '离职时间',
        'todate' => '至今',
        'companyname' => '公司名称',
        'jobname' => '职位名称',
        'duty' => '工作职责'
    ];
    protected function checkStarttime($value, $rule, $data)
    {
        if ($value>=time()) {
            return '入职时间不能晚于当前时间';
        } else {
            return true;
        }
    }
    protected function checkEndtime($value, $rule, $data)
    {
        if (!$value && !$data['todate']) {
            return '请选择离职时间';
        }else if($data['todate']==0 && $value<$data['starttime']){
            return '离职时间不能早于入职时间';
        } else {
            return true;
        }
    }
}
