<?php

namespace app\common\model;

class SceneQrcode extends BaseModel
{
    public $platform_arr = [
        0 => '公众号二维码',
        1 => '触屏二维码',
        2 => '微信小程序码'
    ];
    public $type_arr = [
        'index' => [
            'name' => '首页',
            'alias' => 'index',
            'offiaccount_param_name' => '',
            'mobile_page' => ''
        ],
        'reg_personal' => [
            'name' => '求职者注册页',
            'alias' => 'reg_personal',
            'offiaccount_param_name' => '',
            'mobile_page' => 'member/reg/personal'
        ],
        'reg_company' => [
            'name' => '企业注册页',
            'alias' => 'reg_company',
            'offiaccount_param_name' => '',
            'mobile_page' => 'member/reg/company'
        ],
        'company' => [
            'name' => '公司详情页',
            'alias' => 'company',
            'offiaccount_param_name' => 'comid',
            'mobile_page' => 'company/:id',
            'query_model' => 'Company',
            'pk_name' => '企业ID',
            'query_field_en' => 'companyname',
            'query_field_zh' => '企业名称'
        ],
        'job' => [
            'name' => '职位详情页',
            'alias' => 'job',
            'offiaccount_param_name' => 'jobid',
            'mobile_page' => 'job/:id',
            'query_model' => 'Job',
            'pk_name' => '职位ID',
            'query_field_en' => 'jobname',
            'query_field_zh' => '职位名称'

        ],
        'resume' => [
            'name' => '简历详情页',
            'alias' => 'resume',
            'offiaccount_param_name' => 'resumeid',
            'mobile_page' => 'resume/:id',
            'query_model' => 'Resume',
            'pk_name' => '简历ID',
            'query_field_en' => 'fullname',
            'query_field_zh' => '简历名称'
        ],
        'notice' => [
            'name' => '公告详情页',
            'alias' => 'notice',
            'offiaccount_param_name' => 'noticeid',
            'mobile_page' => 'notice/:id',
            'query_model' => 'Notice',
            'pk_name' => '公告ID',
            'query_field_en' => 'title',
            'query_field_zh' => '公告标题'
        ],
        'jobfairol' => [
            'name' => '网络招聘会详情页',
            'alias' => 'jobfairol',
            'offiaccount_param_name' => 'jobfairolid',
            'mobile_page' => 'jobfairol/:id',
            'query_model' => 'JobfairOnline',
            'pk_name' => '网络招聘会ID',
            'query_field_en' => 'title',
            'query_field_zh' => '网络招聘会标题'
        ],
        'news' => [
            'name' => '资讯详情页',
            'alias' => 'news',
            'offiaccount_param_name' => 'newsid',
            'mobile_page' => 'news/:id',
            'query_model' => 'Article',
            'pk_name' => '资讯ID',
            'query_field_en' => 'title',
            'query_field_zh' => '资讯标题'
        ]
    ];
}
