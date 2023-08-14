<?php
/**
 * 企业名录 Validate
 * @author edc
 * Date：2022-11-17
 */
namespace app\common\validate;

use app\common\base\BaseValidate;

class CompanyDirectoryValidate extends BaseValidate
{

    protected $interfaceParam  =   [
        // 类名
        'B2bcrm.companyDirectory'=>[
            // 保存配置
            'baseConfig'=>[
                'validate'=>[
                    ['field_name'=>'app_key','name'=>'AppKey','type'=>'is_string','default'=>'','require'=>false],
                    ['field_name'=>'app_secret','name'=>'AppSecret','type'=>'is_string','default'=>'','require'=>false],
                ],
            ],
            // 查询缓存是否存在
            'queryCacheIsExists'=>[
                'validate'=>[
                    ['field_name'=>'type','name'=>'维度','type'=>'is_string','require'=>false],
                    ['field_name'=>'keyword','name'=>'关键词','type'=>'is_string','require'=>false],
                    ['field_name'=>'province','name'=>'省','type'=>'is_numeric','require'=>false],
                    ['field_name'=>'province_name','name'=>'省名称','type'=>'is_string','require'=>false],
                    ['field_name'=>'city','name'=>'市','type'=>'is_numeric','require'=>false],
                    ['field_name'=>'city_name','name'=>'市名称','type'=>'is_string','require'=>false],
                    ['field_name'=>'district','name'=>'区','type'=>'is_numeric','require'=>false],
                    ['field_name'=>'district_name','name'=>'区名称','type'=>'is_string','require'=>false],
                    ['field_name'=>'starttime','name'=>'成立开始时间','type'=>'is_string','require'=>false],
                    ['field_name'=>'endtime','name'=>'成立结束时间','type'=>'is_string','require'=>false],
                    ['field_name'=>'ent_status','name'=>'营业状态','type'=>'is_string','require'=>false],
                    ['field_name'=>'contact','name'=>'联系方式','type'=>'is_string','require'=>false],
                    ['field_name'=>'page','name'=>'页数','type'=>'is_numeric','default'=>1,'require'=>false],
                ],
            ],
            // 获取企业线索列表
            'getCompanyClueList'=>[
                'validate'=>[
                    ['field_name'=>'type','name'=>'维度','type'=>'is_string','require'=>false],
                    ['field_name'=>'keyword','name'=>'关键词','type'=>'is_string','require'=>false],
                    ['field_name'=>'province','name'=>'省','type'=>'is_numeric','require'=>false],
                    ['field_name'=>'province_name','name'=>'省名称','type'=>'is_string','require'=>false],
                    ['field_name'=>'city','name'=>'市','type'=>'is_numeric','require'=>false],
                    ['field_name'=>'city_name','name'=>'市名称','type'=>'is_string','require'=>false],
                    ['field_name'=>'district','name'=>'区','type'=>'is_numeric','require'=>false],
                    ['field_name'=>'district_name','name'=>'区名称','type'=>'is_string','require'=>false],
                    ['field_name'=>'starttime','name'=>'成立开始时间','type'=>'is_string','require'=>false],
                    ['field_name'=>'endtime','name'=>'成立结束时间','type'=>'is_string','require'=>false],
                    ['field_name'=>'ent_status','name'=>'营业状态','type'=>'is_string','require'=>false],
                    ['field_name'=>'contact','name'=>'联系方式','type'=>'is_string','require'=>false],
                    ['field_name'=>'page','name'=>'页数','type'=>'is_numeric','default'=>1,'require'=>false],
                ],
            ],
            // 获取消耗记录列表
            'getConsumeRecordList'=>[
                'validate'=>[
                    ['field_name'=>'pagesize','name'=>'每页显示数量','type'=>'is_numeric','long'=>11,'default'=>10,'require'=>false],
                ],
            ],
            // 导入线索
            'importClue'=>[
                'validate'=>[
                    ['field_name'=>'place','name'=>'导入位置','type'=>'is_numeric','default'=>1,'require'=>false],
                    ['field_name'=>'clue_list','name'=>'线索信息','type'=>'is_array','require'=>false],
                ],
            ],
        ]
    ];
}