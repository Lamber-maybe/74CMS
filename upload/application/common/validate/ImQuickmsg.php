<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ImQuickmsg extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
    }
    protected $rule = [
        'content' => 'require|max:100'

    ];
}
