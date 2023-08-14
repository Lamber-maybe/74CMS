<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Badword extends BaseValidate
{
    protected $rule =   [
        'name'   => 'require|max:30',
        'replace_text'   => 'require|max:30'
    ];
}