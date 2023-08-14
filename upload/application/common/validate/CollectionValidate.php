<?php
/**
 * 采集 Validate
 * @author chenyang
 * Date Time：2022年3月25日14:10:55
 */
namespace app\common\validate;

use app\common\base\BaseValidate;

class CollectionValidate extends BaseValidate
{
    protected $paramData = [
        'username'           => '', // 用户名
        'password'           => '', // 密码
        'jobname'            => '', // 职位名称
        'content'            => '', // 职位描述
        'nature'             => '', // 工作性质
        'salary'             => '', // 薪资待遇
        'recruit_num'        => '', // 招聘人数
        'education'          => '', // 学历要求
        'experience'         => '', // 经验要求
        'district'           => '', // 工作地区
        'address'            => '', // 工作地址
        'welfare'            => '', // 岗位福利
        'company_name'       => '', // 企业名称
        'company_website'    => '', // 企业网址
        'company_nature'     => '', // 企业性质
        'company_trade'      => '', // 企业所属行业
        'company_district'   => '', // 企业地区
        'company_address'    => '', // 企业地址
        'company_scale'      => '', // 企业规模
        'company_content'    => '', // 企业介绍
        'company_short_desc' => '', // 企业简介
    ];

    protected $interfaceParam  =   [
        // 类名
        'Collection'=>[
            // 保存职位信息
            'saveJob'=>[
                'validate'=>[
                    ['field_name'=>'username','name'=>'用户名','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'password','name'=>'密码','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'jobname','name'=>'职位名称','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'content','name'=>'职位描述','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'nature','name'=>'工作性质','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'salary','name'=>'薪资待遇','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'recruit_num','name'=>'招聘人数','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'education','name'=>'学历要求','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'experience','name'=>'经验要求','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'district','name'=>'工作地区','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'address','name'=>'工作地址','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'welfare','name'=>'岗位福利','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_name','name'=>'企业名称','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'company_website','name'=>'企业网址','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_nature','name'=>'企业性质','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_trade','name'=>'企业所属行业','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_district','name'=>'企业地区','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_address','name'=>'企业地址','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_scale','name'=>'企业规模','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_content','name'=>'企业介绍','type'=>'is_string','default'=>'','require'=>false],
                    ['field_name'=>'company_short_desc','name'=>'企业简介','type'=>'is_string','default'=>'','require'=>false],
                ],
            ],
            // 保存企业信息
            'saveCompany'=>[
                'validate'=>[
                    ['field_name'=>'username','name'=>'用户名','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'password','name'=>'密码','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'company_name','name'=>'企业名称','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'company_website','name'=>'企业网址','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_nature','name'=>'企业性质','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_trade','name'=>'企业所属行业','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_district','name'=>'企业地区','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_address','name'=>'企业地址','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_scale','name'=>'企业规模','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_content','name'=>'企业介绍','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'company_short_desc','name'=>'企业简介','type'=>'is_string','default'=>'','require'=>true],
                ],
            ],
        ]
    ];
}