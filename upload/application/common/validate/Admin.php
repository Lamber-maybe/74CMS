<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Admin extends BaseValidate
{
    protected $rule =   [
        'username'  =>  'require|max:15|unique:admin',
        'password'   => 'require|max:32',
        'role_id' => 'require|number',    
    ];
}