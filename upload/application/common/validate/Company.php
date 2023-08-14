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
        'tag' => 'max:50',
        'map_zoom' => 'number',
        'audit' => 'in:0,1,2',
        'logo' => 'number|egt:0',
        'addtime' => 'number',
        'refreshtime' => 'number',
        'click' => 'number',
        'robot' => 'number|in:0,1'
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
}
