<?php
namespace app\common\model;

class Admin extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type     = [
        'id'        => 'integer',
        'tid'        => 'integer',
        'is_display' => 'integer',
        'click'      => 'integer',
        'addtime'    => 'integer',
        'sort_id'    => 'integer',
    ];
    protected $insert = ['addtime','last_login_time'=>0,'last_login_ip'=>'','last_login_ipaddress'=>''];
    protected function setAddtimeAttr()
    {
        return time();
    }
    public function makePassword($password, $randstr) {
        return md5(md5($password).$randstr.config('sys.safecode'));
    }
}
