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
                    ['field_name'=>'contact_name','name'=>'联系人名称','type'=>'is_string','default'=>'','require'=>false],
                    ['field_name'=>'contact_mobile','name'=>'联系人电话','type'=>'is_string','default'=>'','require'=>false],
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
                    ['field_name'=>'contact_name','name'=>'联系人名称','type'=>'is_string','default'=>'','require'=>false],
                    ['field_name'=>'contact_mobile','name'=>'联系人电话','type'=>'is_string','default'=>'','require'=>false],
                ],
            ],
            // 保存资讯信息
            'saveArticle'=>[
                'validate'=>[
                    ['field_name'=>'username','name'=>'用户名','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'password','name'=>'密码','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'title','name'=>'文章标题','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'content','name'=>'文章内容','type'=>'is_string','default'=>'','require'=>true],
                    ['field_name'=>'category','name'=>'文章分类','type'=>'is_string','default'=>'','require'=>false],
                    ['field_name'=>'click','name'=>'点击量','type'=>'is_numeric','default'=>0,'require'=>false],
                    ['field_name'=>'sort_id','name'=>'排序','type'=>'is_numeric','default'=>0,'require'=>false],
                    ['field_name'=>'source','name'=>'来源','type'=>'is_string','default'=>'','require'=>false],
                ],
            ],
        ]
    ];
}