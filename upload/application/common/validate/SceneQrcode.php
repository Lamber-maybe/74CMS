<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class SceneQrcode extends BaseValidate
{
    protected $rule =   [
        'title'   => 'require|max:30',  
        'uuid'   => 'require',   
        'type'=>'require',
        'deadline'=>'require',
        'platform'=>'require'
    ];
}