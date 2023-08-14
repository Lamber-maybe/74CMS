<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class HelpCategory extends BaseValidate
{
    protected $rule =   [
        'name'  => 'require|max:10',
    ];
}