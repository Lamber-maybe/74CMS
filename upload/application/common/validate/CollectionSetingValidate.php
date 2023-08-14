<?php
/**
 * 采集设置 Validate
 * @author chenyang
 * Date Time：2022年4月11日11:25:32
 */
namespace app\common\validate;

use app\common\base\BaseValidate;

class CollectionSetingValidate extends BaseValidate
{
    protected $paramData = [
        'status'            => '', // 采集状态:0|关闭,1|开启
        'matching_accuracy' => '', // 匹配精准度
        'recruit_status'    => '', // 招聘状态:0|已暂停,1|招聘中
        'audit_status'      => '', // 审核状态:0|待审核,1|通过,2|未通过
        'nature'            => '', // 职位性质:1|全职,2|实习
        'category1'         => '', // 职位类别1
        'category2'         => '', // 职位类别2
        'category3'         => '', // 职位类别3
        'experience'        => '', // 经验要求
        'education'         => '', // 学历要求
        'recruit_num'       => '', // 招聘人数
        'minwage'           => '', // 最低工资
        'maxwage'           => '', // 最高工资
        'district1'         => '', // 地区1
        'district2'         => '', // 地区2
        'district3'         => '', // 地区3
        'welfare'           => '', // 岗位福利
        'minage'            => '', // 最低年龄
        'maxage'            => '', // 最高年龄
        'age_na'            => '', // 是否限制年龄:0|限,1|不限
        'is_display'        => '', // 显示状态:0|不显示,1|显示
        'scale'             => '', // 企业规模
        'trade'             => '', // 企业所属行业
        'registered'        => '', // 注册资金
        'currency'          => '', // 计量单位:0|RMB,1|USD
        'name_prefix'       => '', // 用户名前缀
        'name_rule'         => '', // 用户名规则:1|随机字符串,2|手机号
        'pwd_rule'          => '', // 密码规则:1|与用户名相同,2|指定密码
        'password'          => '', // 密码
    ];

    protected $interfaceParam  =   [
        // 类名
        'CollectionSeting'=>[
            // 保存采集设置
            'saveSeting'=>[
                'validate'=>[
                    ['field_name'=>'status','name'=>'采集状态','type'=>'is_numeric','long'=>1,'require_value'=>[0,1],'default'=>0,'require'=>true],
                    ['field_name'=>'matching_accuracy','name'=>'匹配精准度','type'=>'is_numeric','long'=>3,'egt'=>0,'elt'=>100,'default'=>0,'require'=>true],
                ],
            ],
            // 保存职位设置
            'saveJobSeting'=>[
                'validate'=>[
                    ['field_name'=>'recruit_status','name'=>'招聘状态','type'=>'is_numeric','long'=>1,'require_value'=>[0,1],'default'=>0,'require'=>true],
                    ['field_name'=>'audit_status','name'=>'审核状态','type'=>'is_numeric','long'=>1,'require_value'=>[0,1,2],'default'=>0,'require'=>true],
                    ['field_name'=>'nature','name'=>'职位性质','type'=>'is_numeric','long'=>1,'require_value'=>[1,2],'default'=>1,'require'=>true],
                    ['field_name'=>'category1','name'=>'职位类别1','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'category2','name'=>'职位类别2','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'category3','name'=>'职位类别3','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'experience','name'=>'经验要求','type'=>'is_numeric','long'=>1,'require_value'=>[0,1,2,3,4,5,6,7],'default'=>0,'require'=>true],
                    ['field_name'=>'education','name'=>'学历要求','type'=>'is_numeric','long'=>1,'require_value'=>[0,1,2,3,4,5,6,7,8,9],'default'=>0,'require'=>true],
                    ['field_name'=>'recruit_num','name'=>'招聘人数','type'=>'is_numeric','long'=>5,'default'=>0,'require'=>true],
                    ['field_name'=>'minwage','name'=>'最低工资','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'maxwage','name'=>'最高工资','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'district1','name'=>'工作地区1','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'district2','name'=>'工作地区2','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'district3','name'=>'工作地区3','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'welfare','name'=>'岗位福利','type'=>'is_string','default'=>'','require'=>false],
                    ['field_name'=>'minage','name'=>'最低年龄','type'=>'is_numeric','long'=>2,'default'=>0,'require'=>true],
                    ['field_name'=>'maxage','name'=>'最高年龄','type'=>'is_numeric','long'=>2,'default'=>0,'require'=>true],
                    ['field_name'=>'age_na','name'=>'是否限制年龄','type'=>'is_numeric','long'=>1,'default'=>0,'require_value'=>[0,1],'require'=>true],
                ],
            ],
            // 保存企业设置
            'saveCompanySeting'=>[
                'validate'=>[
                    ['field_name'=>'is_display','name'=>'显示状态','type'=>'is_numeric','long'=>1,'require_value'=>[0,1],'default'=>0,'require'=>true],
                    ['field_name'=>'audit_status','name'=>'审核状态','type'=>'is_numeric','long'=>1,'require_value'=>[0,1,2],'default'=>0,'require'=>true],
                    ['field_name'=>'nature','name'=>'企业性质','type'=>'is_numeric','long'=>11,'empty'=>false,'require'=>true],
                    ['field_name'=>'scale','name'=>'企业规模','type'=>'is_numeric','long'=>11,'empty'=>false,'require'=>true],
                    ['field_name'=>'trade','name'=>'企业所属行业','type'=>'is_numeric','long'=>11,'empty'=>false,'require'=>true],
                    ['field_name'=>'registered','name'=>'注册资金','type'=>'is_numeric','long'=>11,'require'=>true],
                    ['field_name'=>'currency','name'=>'计量单位','type'=>'is_numeric','long'=>1,'require_value'=>[0,1],'default'=>0,'require'=>true],
                    ['field_name'=>'district1','name'=>'企业地区1','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'district2','name'=>'企业地区2','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                    ['field_name'=>'district3','name'=>'企业地区3','type'=>'is_numeric','long'=>11,'default'=>0,'empty'=>false,'require'=>true],
                ],
            ],
            // 保存账号设置
            'saveAccountSeting'=>[
                'validate'=>[
                    ['field_name'=>'name_prefix','name'=>'用户名前缀','type'=>'is_string','empty'=>false,'require'=>true],
                    ['field_name'=>'name_rule','name'=>'用户名规则','type'=>'is_numeric','long'=>1,'require_value'=>[1,2],'default'=>1,'empty'=>false,'require'=>true],
                    ['field_name'=>'pwd_rule','name'=>'密码规则','type'=>'is_numeric','long'=>1,'require_value'=>[1,2],'default'=>1,'empty'=>false,'require'=>true],
                    ['field_name'=>'password','name'=>'密码','type'=>'is_string','long'=>15,'default'=>'','require'=>false],
                ],
            ],
        ]
    ];
}