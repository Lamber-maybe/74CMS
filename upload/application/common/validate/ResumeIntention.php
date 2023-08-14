<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeIntention extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('ResumeIntention');
    }
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'nature' => 'require|number|gt:0',
        'category1' => 'require|number|gt:0',
        'category2' => 'require|number|egt:0',
        'category3' => 'require|number|egt:0',
        'category' => 'require|number|gt:0',
        'district1' => 'require|number|gt:0',
        'district2' => 'require|number|egt:0',
        'district3' => 'require|number|egt:0',
        'district' => 'require|number|gt:0',
        'minwage' => 'require|number|gt:0',
        'maxwage' => 'require|number|gt:0',
        'trade' => 'number|egt:0'
    ];
    protected $scene = [
        //app表单式注册
        'reg_from_app_by_form' => [
            'uid',
            'rid',
            'category1',
            'category2',
            'category3',
            'category',
            'district1',
            'district2',
            'district3',
            'district',
            'minwage',
            'maxwage'
        ],
        //app交互式注册
        'reg_from_app_by_interactive' => [
            'uid',
            'rid',
            'category1',
            'category2',
            'category3',
            'category',
            'district1',
            'district2',
            'district3',
            'district',
            'minwage',
            'maxwage'
        ]
    ];
}
