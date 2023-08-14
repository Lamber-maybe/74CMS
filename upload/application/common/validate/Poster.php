<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Poster extends BaseValidate
{
    protected $rule =   [
        'type'  => 'require|gt:0',
        'name'   => 'require|max:30'
    ];
}