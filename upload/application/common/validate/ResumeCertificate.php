<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeCertificate extends BaseValidate
{
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'obtaintime' => 'require|number',
        'name' => 'require|max:30'
    ];
    protected $field = [
        'name' => '证书名称',
        'obtaintime' => '获得时间'
    ];
}
