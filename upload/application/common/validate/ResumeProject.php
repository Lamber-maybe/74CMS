<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeProject extends BaseValidate
{
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'starttime' => 'require|number|checkStarttime',
        'endtime' => 'require|number|checkEndtime',
        'todate' => 'require|in:0,1',
        'projectname' => 'require|max:30',
        'role' => 'require|max:30',
        'description' => 'require'
    ];
    protected $field = [
        'starttime' => '开始时间',
        'endtime' => '结束时间',
        'todate' => '至今',
        'projectname' => '项目名称',
        'role' => '担任角色',
        'description' => '项目描述'
    ];
    protected function checkStarttime($value, $rule, $data)
    {
        if ($value>=time()) {
            return '开始时间不能晚于当前时间';
        } else {
            return true;
        }
    }
    protected function checkEndtime($value, $rule, $data)
    {
        if (!$value && !$data['todate']) {
            return '请选择结束时间';
        }else if($data['todate']==0 && $value<$data['starttime']){
            return '结束时间不能早于开始时间';
        } else {
            return true;
        }
    }
}
