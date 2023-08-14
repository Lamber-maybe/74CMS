<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class CompanyServiceResumePackage extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'download_resume_point' => 'require|number|gt:0',
        'expense' => 'require|number|gt:0',
        'sort_id' => 'require|number|egt:0'
    ];
}
