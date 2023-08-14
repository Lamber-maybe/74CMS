<?php

namespace app\common\validate;

use app\common\validate\BaseValidate;

class Company extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('Company');
    }

    protected $rule = [
        'uid' => 'number|gt:0',
        'companyname' => 'require|max:60|uniqueCompanyname',
        'short_name' => 'max:60',
        'nature' => 'require|number|gt:0',
        'trade' => 'require|number|gt:0',
        'district1' => 'require|number|gt:0',
        'district2' => 'require|number|egt:0',
        'district3' => 'require|number|egt:0',
        'scale' => 'number|egt:0',
        'registered' => 'max:15',
        'currency' => 'in:0,1',
        'tag' => 'max:100',
        'map_zoom' => 'number',
        'audit' => 'in:0,1,2',
        'logo' => 'number|egt:0',
        'addtime' => 'number',
        'refreshtime' => 'number',
        'click' => 'number',
        'robot' => 'number|in:0,1'
    ];
    protected $message = [
        'district1.gt' => '请选择所在地区',
        'companyname' => '请输入公司名称，长度在1-60个字',
        'short_name' => '请输入公司简称，长度在1-60个字',
        'nature' => '请选择企业性质',
        'trade' => '请选择所属行业',
        'district1' => '请选择地区',
        'district2' => '请选择二级地区',
        'district3' => '请选择三级地区',
        'scale' => '请选择公司规模',
        'registered' => '请输入注册资金，长度在1-15位',
        'currency' => '请选择计量单位',
        'map_zoom' => '地图缩放级别必须为数字',
        'audit' => '认证状态必须为数字0、1、2',
        'addtime' => '添加时间必须为数字',
        'refreshtime' => '刷新时间必须为数字',
        'click' => '点击量必须为数字',
        'robot' => '是否采集信息必须为数字0或1',
        'tag' => '企业福利不能为空'
    ];

    protected function uniqueCompanyname($value, $rule, $data)
    {
        if (config('global_config.company_repeat') == 1) {
            return true;
        } else {
            $info = model('Company')
                ->where('companyname', $value)
                ->find();
            if ($info === null || $info['uid'] == $data['uid']) {
                return true;
            } else {
                return '企业名称已被占用';
            }
        }
    }

    /**
     * 场景验证
     */
    protected $scene = [
        'edit' => ['uid', 'companyname', 'short_name', 'logo', 'nature', 'trade', 'scale'],
        'location' => ['district1', 'district2', 'district3', 'map_zoom'],
        'registered' => ['registered', 'currency'],
        'logo' => ['logo'],
        'short_name' => ['short_name'],
        'nature' => ['nature'],
        'trade' => ['trade'],
        'scale' => ['scale'],
        'tag' => ['tag'],
        'audit' => ['audit']
    ];
}
