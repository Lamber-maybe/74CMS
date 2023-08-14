<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class CrmClue extends BaseValidate
{
    public $rule =   [
        'name'  => 'require|max:20',
        'mobile'   => 'max:11|number',
        'contacts'=>'require|max:6',
        'weixin'=>'max:30',
        'district1'=>'max:10|number',
        'district2'=>'max:10|number',
        'district3'=>'max:10|number',
        'district'=>'number|max:10',
        'address'=>'max:50',
        'remark'=>'max:50',
        'trade'=>'number|max:10',
        'company_size'=>'number|max:10'
    ];

    public $message  =   [
        'name' => '线索名称不能超过20个字',
        'mobile' => '手机号必须为数字，且不能超过11位',
        'contacts' => '请填写6字以内的联系人名称',
        'weixin' => '微信不能超过30个字',
        'district1' => '所在地区省必须为数字，且不能超过10位',
        'district2' => '所在地区市必须为数字，且不能超过10位',
        'district3' => '所在地区区必须为数字，且不能超过10位',
        'address' => '详细地址不能超过50个字',
        'remark' => '备注不能超过50个字',
        'trade' => '客户行业必须为数字，且不能超过10位',
        'company_size' => '公司规模必须为数字，且不能超过10位'
    ];

    public $scene = [
        'add' => [
            'name',
            'mobile',
            'contacts',
            'weixin',
            'district1',
            'district2',
            'district3',
            'district',
            'address',
            'remark',
            'trade',
            'company_size'
        ],
        'edit' => [
            'mobile',
            'contacts',
            'weixin',
            'district1',
            'district2',
            'district3',
            'district',
            'address',
            'trade',
            'company_size'
        ]
    ];
}