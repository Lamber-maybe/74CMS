<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeLanguage extends BaseValidate
{
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'language' => 'require|number|gt:0',
        'level' => 'require|number|gt:0'
    ];
    protected $field = [
        'language' => '语种',
        'level' => '熟悉程度'
    ];
}
