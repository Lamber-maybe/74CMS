<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeTraining extends BaseValidate
{
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'starttime' => 'require|number|checkStarttime',
        'endtime' => 'require|number|checkEndtime',
        'todate' => 'require|in:0,1',
        'agency' => 'require|max:30',
        'course' => 'require|max:30',
        'description' => 'require'
    ];
    protected $field = [
        'starttime' => '开始时间',
        'endtime' => '结束时间',
        'todate' => '至今',
        'agency' => '培训机构',
        'course' => '培训课程',
        'description' => '培训内容'
    ];
    protected function checkStarttime($value, $rule, $data)
    {
        if ($value>=time()) {
            return '培训经历开始时间不能晚于当前时间';
        } else {
            return true;
        }
    }
    protected function checkEndtime($value, $rule, $data)
    {
        if (!$value && !$data['todate']) {
            return '请选择培训经历结束时间';
        }else if($data['todate']==0 && $value<$data['starttime']){
            //【优化】提示错误但不知道是哪个，需要提示明确 zch 2022.12.13
            return '培训经历结束时间不能早于开始时间';
        } else {
            return true;
        }
    }
}
