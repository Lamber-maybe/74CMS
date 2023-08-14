<?php
namespace app\common\validate;
use app\common\validate\BaseValidate;
class JobfairOnline extends BaseValidate{
    protected $rule = [
        'title' => 'require|max:200',
        'starttime' => 'require|integer',
        'endtime' => 'require|integer',
        'thumb' => 'require|integer',
        'content' => 'require',
        'enable_setmeal_id' => 'require',
        'must_company_audit' => 'integer|in:0,1',
        'min_complete_percent' => 'integer',
        'click' => 'integer',
        'addtime' => 'integer',
        'qrcode' => 'require|integer'
    ];
    protected $message = [
        'title.require' => '招聘会标题不能为空',
        'title.max' => '招聘会标题应在200个字符内',
        'starttime.require' => '请选择举办时间',
        'endtime.require' => '请选择举办时间',
        'starttime.integer' => '请正确选择举办时间',
        'endtime.integer' => '请正确选择举办时间',
        'thumb.require' => '请上传招聘会缩略图',
        'thumb.integer' => '请正确上传招聘会缩略图',
        'content.require' => '招聘会介绍不能为空',
        'enable_setmeal_id.require' => '请选择允许报名套餐',
        'must_company_audit.integer' => '请正确选择仅认证企业报名',
        'must_company_audit.in' => '请正确选择仅认证企业报名',
        'min_complete_percent.integer' => '请正确填写简历完整度',
        'click.integer' => '请正确填查看次数',
        'addtime.integer' => '请正确填写添加时间',
        'qrcode.require' => '请上传客服二维码',
        'qrcode.integer' => '请正确上传客服二维码'
    ];
}