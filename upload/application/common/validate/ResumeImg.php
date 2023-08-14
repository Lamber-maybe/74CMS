<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeImg extends BaseValidate
{
    protected $rule = [
        'uid' => 'require|number|gt:0',
        'rid' => 'require|number|gt:0',
        'img' => 'require|max:255',
        'title' => 'max:20',
        'addtime' => 'require|number',
        'audit' => 'require|in:0,1'
    ];
    protected $field = [
        'img' => '作品照片',
        'title' => '作品名称'
    ];
}
