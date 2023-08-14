<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class CategoryJobTemplate extends BaseValidate
{
    protected $rule =   [
        'pid'  => 'require|number|gt:0',
        'title'   => 'require|max:30',   
        'content'   => 'require',  
    ];
    
}