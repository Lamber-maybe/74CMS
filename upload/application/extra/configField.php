<?php
/**
 * 系统配置项字段
 */

return [
    'sitename' => [
        'name' => 'sitename',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'sitename',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '网站名称',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'sitedomain' => [
        'name' => 'sitedomain',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'sitename',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '网站域名',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'sitedir' => [
        'name' => 'sitedir',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'sitedir',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '安装目录',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'contact_tel' => [
        'name' => 'contact_tel',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'contact_tel',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '联系电话',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'fileupload_type' => [
        'name' => 'fileupload_type',
        'is_frontend' => '0',
        'value' => 'fileupload_type',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '文件保存位置',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '文件上传'
        ]
    ],
    'fileupload_size' => [
        'name' => 'fileupload_size',
        'is_frontend' => '1',
        'value' => 'contact_tel',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '文件上传最大限制',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '文件上传'
        ]
    ],
    'fileupload_ext' => [
        'name' => 'fileupload_ext',
        'is_frontend' => '1',
        'value' => 'fileupload_ext',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '允许上传的文件类型',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '文件上传'
        ]
    ],
    'contact_email' => [
        'name' => 'contact_email',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'contact_email',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '联系邮箱',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'contact_address' => [
        'name' => 'contact_address',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'contact_address',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '联系地址',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'bottom_other' => [
        'name' => 'bottom_other',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'bottom_other',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '其他说明',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'icp' => [
        'name' => 'icp',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'icp',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '网站备案号(ICP)',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'isclose' => [
        'name' => 'isclose',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'isclose',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '否',
            1 => '是'
        ],
        'field_name' => '暂时关闭网站',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'close_reason' => [
        'name' => 'close_reason',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'close_reason',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '关闭网站原因',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'statistics' => [
        'name' => 'statistics',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'statistics',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '第三方统计代码',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'logo' => [
        'name' => 'logo',
        'is_log' => 0,
        'is_frontend' => '1',
        'value' => 'logo',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '网站logo',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'closereg' => [
        'name' => 'closereg',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'closereg',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '关闭',
            1 => '开启'
        ],
        'field_name' => '关闭会员注册',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '注册设置'
        ]
    ],
    'reg_prefix' => [
        'name' => 'reg_prefix',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'reg_prefix',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '用户名前缀',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '注册设置'
        ]
    ],
    'agreement' => [
        'name' => 'agreement',
        'is_log' => 0,
        'is_frontend' => '0',
        'value' => 'agreement',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '注册协议',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '注册设置'
        ]
    ],
    'privacy' => [
        'name' => 'privacy',
        'is_log' => 0,
        'is_frontend' => '0',
        'value' => 'privacy',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '隐私政策',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '注册设置'
        ]
    ],
    'map_lng' => [
        'name' => 'map_lng',
        'is_frontend' => '1',
        'value' => 'map_lng',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '默认中心点X坐标',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'map_lat' => [
        'name' => 'map_lat',
        'is_frontend' => '1',
        'value' => 'map_lat',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '默认中心点Y坐标',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'map_zoom' => [
        'name' => 'map_zoom',
        'is_frontend' => '1',
        'value' => 'map_zoom',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '默认缩放级别',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'map_ak' => [
        'name' => 'map_ak',
        'is_frontend' => '1',
        'value' => 'map_ak',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '百度地图客户端AK',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'map_server_ak' => [
        'name' => 'map_server_ak',
        'is_frontend' => '0',
        'value' => 'map_server_ak',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '百度地图服务端AK',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'points_byname' => [
        'name' => 'points_byname',
        'is_frontend' => '1',
        'value' => 'points_byname',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '积分代替名',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'points_quantifier' => [
        'name' => 'points_quantifier',
        'is_frontend' => '1',
        'value' => 'points_quantifier',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '积分量词',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'payment_rate' => [
        'name' => 'payment_rate',
        'is_frontend' => '1',
        'value' => 'payment_rate',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '换算比例',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'resume_list_max' => [
        'name' => 'resume_list_max',
        'is_frontend' => '0',
        'value' => 'resume_list_max',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '简历列表最大显示条数',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'resume_display_name' => [
        'name' => 'resume_display_name',
        'is_frontend' => '0',
        'value' => 'resume_display_name',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '简历姓名默认显示方式',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'sendmail_type' => [
        'name' => 'sendmail_type',
        'is_frontend' => '0',
        'value' => 'sendmail_type',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '默认发送方式',
        'menu_zh' => [
            '系统',
            '基础配置',
            '邮箱配置',
            '参数配置'
        ]
    ],
    'sendsms_type' => [
        'name' => 'sendsms_type',
        'is_frontend' => '0',
        'value' => 'sendsms_type',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '默认服务商',
        'menu_zh' => [
            '系统',
            '基础配置',
            '短信配置',
            '参数配置'
        ]
    ],
    'job_list_max' => [
        'name' => 'job_list_max',
        'is_frontend' => '0',
        'value' => 'job_list_max',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '职位列表最大显示条数',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '基本配置'
        ]
    ],
    'captcha_open' => [
        'name' => 'captcha_open',
        'is_frontend' => '1',
        'value' => 'captcha_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '开启验证',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'captcha_type' => [
        'name' => 'captcha_type',
        'is_frontend' => '1',
        'value' => 'captcha_type',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '验证渠道',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'captcha_picture_rule' => [
        'name' => 'captcha_picture_rule',
        'is_frontend' => '0',
        'value' => 'captcha_picture_rule',
        'is_secret' => '0',
        'is_json' => ['useZh' => '使用中文验证码', 'useCurve' => '是否画混淆曲线', 'useNoise' => '是否添加杂点', 'length' => '验证码位数'],
        'association' => '图片验证码',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'company_repeat' => [
        'name' => 'company_repeat',
        'is_frontend' => '1',
        'value' => 'company_repeat',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '允许公司名称重复',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '基本配置'
        ]
    ],
    'apply_jobs_space' => [
        'name' => 'apply_jobs_space',
        'is_frontend' => '0',
        'value' => 'apply_jobs_space',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '申请职位间隔时间',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '基本配置'
        ]
    ],
    'refresh_jobs_space' => [
        'name' => 'refresh_jobs_space',
        'is_frontend' => '0',
        'value' => 'refresh_jobs_space',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '刷新职位时间间隔',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '基本配置'
        ]
    ],
    'showjobcontact' => [
        'name' => 'showjobcontact',
        'is_frontend' => '0',
        'value' => 'showjobcontact',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1, 2, 3],
        'field_name' => 'web端查看联系方式条件',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '联系方式'
        ]
    ],
    'showjobcontact_mobile' => [
        'name' => 'showjobcontact_mobile',
        'is_frontend' => '0',
        'value' => 'showjobcontact_mobile',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1, 2, 3],
        'field_name' => '移动端查看联系方式条件',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '联系方式'
        ]
    ],
    'audit_edit_com' => [
        'name' => 'audit_edit_com',
        'is_frontend' => '0',
        'value' => 'audit_edit_com',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '修改企业资料后认证状态 ',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'audit_verifycom_editjob' => [
        'name' => 'audit_verifycom_editjob',
        'is_frontend' => '0',
        'value' => 'audit_verifycom_editjob',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '已认证企业修改职位后审核状态 ',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'audit_unverifycom_editjob' => [
        'name' => 'audit_unverifycom_editjob',
        'is_frontend' => '0',
        'value' => 'audit_unverifycom_editjob',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '未认证企业修改职位后审核状态 ',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'must_com_audit_certificate' => [
        'name' => 'must_com_audit_certificate',
        'is_frontend' => '0',
        'value' => 'must_com_audit_certificate',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '强制企业认证营业执照 ',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'enable_com_buy_points' => [
        'name' => 'enable_com_buy_points',
        'is_frontend' => '0',
        'value' => 'enable_com_buy_points',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '允许企业充值积分',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'reg_service' => [
        'name' => 'reg_service',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'reg_service',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新注册会员赠送服务', // 字段名称
        'correlation' => [ // 关联
            'model' => 'Setmeal',
            'pk' => 'id',
            'value' => 'name',
            'condition' => '=',
            'implode' => '、'
        ],
        'menu_zh' => [ // 菜单
            '系统',
            '企业业务配置',
            '基础配置',
            '基本设置',
            '套餐基础配置'
        ]
    ],
    'meal_min_remind' => [
        'name' => 'meal_min_remind',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'meal_min_remind',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '套餐到期前提醒',
        'unit' => '天',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '基本设置',
            '套餐基础配置'
        ]
    ],
    'resume_download_points_conf' => [
        'name' => 'resume_download_points_conf',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'resume_download_points_conf',
        'is_secret' => '0',
        'is_json' => [],
        'is_foreach' => [
            'pk' => 'alias',
            'name' => 'name',
            'value' => 'value',
            'unit' => '点'
        ],
        'association' => '',
        'map' => [],
        'field_name' => '简历下载点数配置',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '简历下载点'
        ]
    ],
    'apply_jobs_max_perday' => [
        'name' => 'apply_jobs_max_perday',
        'is_frontend' => '0',
        'value' => 'apply_jobs_max_perday',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '每天允许申请职位',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'apply_job_min_percent' => [
        'name' => 'apply_job_min_percent',
        'is_frontend' => '1',
        'value' => 'apply_job_min_percent',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '申请职位要求简历完整度',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'refresh_resume_space' => [
        'name' => 'refresh_resume_space',
        'is_frontend' => '0',
        'value' => 'refresh_resume_space',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '刷新简历时间间隔',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'refresh_resume_max_perday' => [
        'name' => 'refresh_resume_max_perday',
        'is_frontend' => '0',
        'value' => 'refresh_resume_max_perday',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '每天允许刷新简历次数',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'down_resume_limit' => [
        'name' => 'down_resume_limit',
        'is_frontend' => '0',
        'value' => 'down_resume_limit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [1, 2, 3],
        'field_name' => '简历下载要求',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'showresumecontact' => [
        'name' => 'showresumecontact',
        'is_frontend' => '0',
        'value' => 'showresumecontact',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1, 2],
        'field_name' => 'web端查看联系方式条件',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '联系方式'
        ]
    ],
    'showresumecontact_mobile' => [
        'name' => 'showresumecontact_mobile',
        'is_frontend' => '0',
        'value' => 'showresumecontact_mobile',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1, 2],
        'field_name' => '移动端查看联系方式条件',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '联系方式'
        ]
    ],
    'audit_edit_resume' => [
        'name' => 'audit_edit_resume',
        'is_frontend' => '0',
        'value' => 'audit_edit_resume',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '修改简历后审核状态',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'account_qiniu' => [
        'name' => 'account_qiniu',
        'is_frontend' => '0',
        'value' => 'account_qiniu',
        'is_secret' => '0',
        'is_json' => ['bucket' => '存储空间名', 'access_key' => 'accessKey', 'secret_key' => 'secretKey', 'domain' => '外链域名', 'protocol' => '资源访问协议'],
        'association' => '七牛云',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '文件上传'
        ]
    ],
    'account_sms' => [
        'name' => 'account_sms',
        'is_frontend' => '0',
        'value' => 'account_sms',
        'is_secret' => '0',
        'is_json' => ['app_key' => '用户名', 'secret_key' => '密钥'],
        'association' => '骑士官网',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '短信配置',
            '参数配置'
        ]
    ],
    'account_smtp' => [
        'name' => 'account_smtp',
        'is_frontend' => '0',
        'value' => 'account_smtp',
        'is_secret' => '0',
        'is_json' => ['host' => 'SMTP服务器', 'port' => 'SMTP端口', 'username' => 'SMTP用户名', 'password' => 'SMTP密码', 'sender_address' => '发送方地址'],
        'association' => 'SMTP',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '邮箱配置',
            '参数配置'
        ]
    ],
    'account_sendcloud' => [
        'name' => 'account_sendcloud',
        'is_frontend' => '0',
        'value' => 'account_sendcloud',
        'is_secret' => '0',
        'is_json' => ['api_user' => '用户名', 'api_key' => 'Key', 'sender_address' => '发送方地址'],
        'association' => 'SendCloud',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '邮箱配置',
            '参数配置'
        ]
    ],
    'account_aliyundm' => [
        'name' => 'account_aliyundm',
        'is_frontend' => '0',
        'value' => 'account_aliyundm',
        'is_secret' => '0',
        'is_json' => ['access_key' => 'AccessKey', 'access_secret' => 'AccessSecret', 'sender_address' => '发送方地址'],
        'association' => '阿里云邮件推送',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '邮箱配置',
            '参数配置'
        ]
    ],
    'account_alisms' => [
        'name' => 'account_alisms',
        'is_frontend' => '0',
        'value' => 'account_alisms',
        'is_secret' => '0',
        'is_json' => ['signature' => '签名', 'accesskey_id' => 'accesskeyid', 'accesskey_secret' => 'accesskeysecret'],
        'association' => '阿里云通信',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '短信配置',
            '参数配置'
        ]
    ],
    'account_alipay' => [
        'name' => 'account_alipay',
        'is_frontend' => '0',
        'value' => 'account_alipay',
        'is_secret' => '0',
        'is_json' => ['appid' => '应用APPID', 'privatekey' => '应用私钥(rsaPrivateKey)', 'publickey' => '支付宝公钥(alipayrsaPublicKey)'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '支付配置',
            '支付宝'
        ]
    ],
    'resume_download_points_talent' => [
        'name' => 'resume_download_points_talent',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'resume_download_points_talent',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '下载优质简历所需',
        'unit' => '点',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '简历下载点',
            '优质简历下载点数配置'
        ]
    ],
    'setmeal_overtime_conf' => [
        'name' => 'setmeal_overtime_conf',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'setmeal_overtime_conf',
        'is_secret' => '0',
        'is_json' => [
            'download_resume_max_perday' => [
                'name' => '下载简历上限',
                'unit' => '份/天',
                'map' => []
            ],
            'enable_video_interview' => [
                'name' => '使用视频面试',
                'unit' => '',
                'map' => [
                    0 => '不允许',
                    1 => '允许'
                ]
            ],
            'im_max_perday' => [
                'name' => '发起聊天数上限',
                'unit' => '次/天',
                'map' => []
            ],
            'jobs_meanwhile' => [
                'name' => '同时在招职位数',
                'unit' => '',
                'map' => [

                ]
            ],
            'refresh_jobs_free_perday' => [
                'name' => '免费刷新职位',
                'unit' => '次/天',
                'map' => [

                ]
            ],
            'service_added_discount' => [
                'name' => '套餐增值包折扣',
                'unit' => '',
                'map' => [

                ]
            ],
            'show_apply_contact' => [
                'name' => '收到简历免费查看',
                'unit' => '',
                'map' => [
                    0 => '不允许',
                    1 => '允许'
                ]
            ],
//            'resume_view_num' => [
//                'name' => '收到简历查看上限',
//                'unit' => '份/天',
//                'map' => []
//            ],
        ],
        'association' => '',
        'map' => [],
        'field_name' => '套餐到期使用权限',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '基本设置',
            '会员到期套餐配置'
        ]
    ],
    'audit_new_com' => [
        'name' => 'audit_new_com',
        'is_frontend' => '0',
        'value' => 'audit_new_com',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '新注册企业认证状态',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'audit_verifycom_addjob' => [
        'name' => 'audit_verifycom_addjob',
        'is_frontend' => '0',
        'value' => 'audit_verifycom_addjob',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '已认证企业新发布职位审核状态',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'audit_unverifycom_addjob' => [
        'name' => 'audit_unverifycom_addjob',
        'is_frontend' => '0',
        'value' => 'audit_unverifycom_addjob',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '未认证企业新发布职位审核状态',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'job_recommend_weight' => [
        'name' => 'job_recommend_weight',
        'is_frontend' => '0',
        'value' => 'job_recommend_weight',
        'is_secret' => '0',
        'is_json' => ['category' => '职位类别', 'trade' => '行业', 'wage' => '薪资', 'district' => '工作地区', 'nature' => '职位性质', 'service_added' => '增值服务', 'refreshtime' => '刷新时间'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '精准推荐',
            '推荐职位'
        ]
    ],
    'resume_recommend_weight' => [
        'name' => 'resume_recommend_weight',
        'is_frontend' => '0',
        'value' => 'resume_recommend_weight',
        'is_secret' => '0',
        'is_json' => ['category' => '职位类别', 'trade' => '行业', 'wage' => '薪资', 'district' => '工作地区', 'nature' => '职位性质', 'service_added' => '增值服务', 'refreshtime' => '刷新时间', 'education' => '学历', 'experience' => '工作经验', 'birthyear' => '年龄要求'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '精准推荐',
            '推荐简历'
        ]
    ],
    'coupon_config' => [
        'name' => 'coupon_config',
        'is_frontend' => '1',
        'value' => 'coupon_config',
        'is_secret' => '0',
        'is_json' => ['is_open' => '开启优惠卷功能', 'remind_days' => '到期提前几天提醒', 'is_reg_gift' => '新注册会员赠送优惠卷', 'reg_gift_list' => '新注册会员赠送优惠卷'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '工具',
            '优惠卷',
            '优惠卷设置'
        ]
    ],
    'wage_unit' => [
        'name' => 'wage_unit',
        'is_frontend' => '1',
        'value' => 'wage_unit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1, 2],
        'field_name' => '薪资显示方式',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'famous_enterprises' => [
        'name' => 'famous_enterprises',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'wage_unit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '名企套餐',
        'correlation' => [ // 关联
            'model' => 'Setmeal',
            'pk' => 'id',
            'value' => 'name',
            'condition' => 'in',
            'implode' => '、'
        ],
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '基本设置'
        ]
    ],
    'account_im' => [
        'name' => 'account_im',
        'is_frontend' => '0',
        'value' => 'account_im',
        'is_secret' => '0',
        'is_json' => ['app_key' => 'app_key', 'app_secret' => 'app_secret'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '工具',
            '即时通讯',
            '基本配置',
            '基本配置'
        ]
    ],
    'personal_reg_mode' => [
        'name' => 'personal_reg_mode',
        'is_frontend' => '1',
        'value' => 'personal_reg_mode',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [1, 2],
        'field_name' => '注册简历引导',
        'menu_zh' => [
            '移动端',
            '手机触屏端',
            '基础配置',
            '基础配置'
        ]
    ],
    'linksubmit_token' => [
        'name' => 'linksubmit_token',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'linksubmit_token',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '准入密钥',
        'menu_zh' => [
            '工具',
            '百度推送',
            'URL推送'
        ]
    ],
    'single_resume_download_open' => [
        'name' => 'single_resume_download_open',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_resume_download_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '关闭',
            1 => '开启'
        ],
        'field_name' => '单份简历下载',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '下载单份简历'
        ]
    ],
    'single_resume_download_expense_conf' => [
        'name' => 'single_resume_download_expense_conf',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_resume_download_expense_conf',
        'is_secret' => '0',
        'is_json' => [],
        'is_foreach' => [
            'pk' => 'alias',
            'name' => 'name',
            'value' => 'value',
            'unit' => '元'
        ],
        'association' => '',
        'map' => [],
        'field_name' => '普通简历单价',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '下载单份简历'
        ]
    ],
    'single_resume_download_enable_points_deduct' => [
        'name' => 'single_resume_download_enable_points_deduct',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_resume_download_enable_points_deduct',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '不允许',
            1 => '允许'
        ],
        'field_name' => '允许积分全抵',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '下载单份简历'
        ]
    ],
    'single_resume_download_points_conf' => [
        'name' => 'single_resume_download_points_conf',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_resume_download_points_conf',
        'is_secret' => '0',
        'is_json' => [],
        'is_foreach' => [
            'pk' => 'alias',
            'name' => 'name',
            'value' => 'value',
            'unit' => '个'
        ],
        'association' => '',
        'map' => [],
        'field_name' => '所需积分',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '下载单份简历'
        ]
    ],
    'single_job_refresh_expense' => [
        'name' => 'single_job_refresh_expense',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_job_refresh_expense',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '刷新职位单价',
        'unit' => '元',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '刷新职位'
        ]
    ],
    'single_job_refresh_enable_points_deduct' => [
        'name' => 'single_job_refresh_enable_points_deduct',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_job_refresh_enable_points_deduct',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '不允许',
            1 => '允许'
        ],
        'field_name' => '允许积分全抵',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '刷新职位'
        ]
    ],
    'single_job_refresh_deduce_points' => [
        'name' => 'single_job_refresh_deduce_points',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_job_refresh_deduce_points',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '所需积分',
        'unit' => '个',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '刷新职位'
        ]
    ],
    'single_resume_download_expense_talent' => [
        'name' => 'single_resume_download_expense_talent',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_resume_download_expense_talent',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '优质简历单价',
        'unit' => '元',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '优质简历单价'
        ]
    ],
    'single_resume_download_points_talent' => [
        'name' => 'single_resume_download_points_talent',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'single_resume_download_points_talent',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '优质简历所需积分',
        'unit' => '个',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '快捷消费',
            '基本设置',
            '下载单份简历'
        ]
    ],
    'wechat_open' => [
        'name' => 'wechat_open',
        'is_frontend' => '1',
        'value' => 'wechat_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '是否开启',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '微信对接配置',
            '微信对接配置'
        ]
    ],
    'wechat_type' => [
        'name' => 'wechat_type',
        'is_frontend' => '1',
        'value' => 'wechat_type',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [1, 2],
        'field_name' => '公众号类型',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '微信对接配置',
            '微信对接配置'
        ]
    ],
    'wechat_token' => [
        'name' => 'wechat_token',
        'is_frontend' => '0',
        'value' => 'wechat_token',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'Token',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '微信对接配置',
            '微信对接配置'
        ]
    ],
    'wechat_appid' => [
        'name' => 'wechat_appid',
        'is_frontend' => '1',
        'value' => 'wechat_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'AppId',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '微信对接配置',
            '微信对接配置'
        ]
    ],
    'wechat_appsecret' => [
        'name' => 'wechat_appsecret',
        'is_frontend' => '0',
        'value' => 'wechat_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'AppSecret',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '微信对接配置',
            '微信对接配置'
        ]
    ],
    'wechat_encodingaeskey' => [
        'name' => 'wechat_encodingaeskey',
        'is_frontend' => '0',
        'value' => 'wechat_encodingaeskey',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'EncodingAESKey',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '微信对接配置',
            '微信对接配置'
        ]
    ],
    'wechat_name' => [
        'name' => 'wechat_name',
        'is_frontend' => '1',
        'value' => 'wechat_name',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '公众号名称',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '公众号基础信息',
            '公众号基础信息'
        ]
    ],
    'wechat_code' => [
        'name' => 'wechat_code',
        'is_frontend' => '1',
        'value' => 'wechat_code',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '微信号',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '公众号基础信息',
            '公众号基础信息'
        ]
    ],
    'wechat_qrcode' => [
        'name' => 'wechat_qrcode',
        'is_frontend' => '1',
        'value' => 'wechat_qrcode',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '二维码图片',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '公众号基础信息',
            '公众号基础信息'
        ]
    ],
    'wechat_welcome_text' => [
        'name' => 'wechat_welcome_text',
        'is_frontend' => '0',
        'value' => 'wechat_welcome_text',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '关注欢迎语',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '公众号基础信息',
            '公众号基础信息'
        ]
    ],
    'wechat_welcome_img' => [
        'name' => 'wechat_welcome_img',
        'is_frontend' => '0',
        'value' => 'wechat_welcome_img',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '关注欢迎图片',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '公众号基础信息',
            '公众号基础信息'
        ]
    ],
    'wechat_info_img' => [
        'name' => 'wechat_info_img',
        'is_frontend' => '0',
        'value' => 'wechat_info_img',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '默认信息图',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '公众号基础信息',
            '公众号基础信息'
        ]
    ],
    'wechat_miniprogram_appid' => [
        'name' => 'wechat_miniprogram_appid',
        'is_frontend' => '0',
        'value' => 'wechat_miniprogram_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'appid',
        'menu_zh' => [
            '移动端',
            '微信小程序',
            '基础配置',
            '基础配置'
        ]
    ],
    'square_logo' => [
        'name' => 'square_logo',
        'is_frontend' => '1',
        'value' => 'square_logo',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '小程序码',
        'menu_zh' => [
            '移动端',
            '微信小程序',
            '基础配置',
            '基础配置'
        ]
    ],
    'wechat_miniprogram_appsecret' => [
        'name' => 'wechat_miniprogram_appsecret',
        'is_frontend' => '0',
        'value' => 'wechat_miniprogram_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'appsecret',
        'menu_zh' => [
            '移动端',
            '微信小程序',
            '基础配置',
            '基础配置'
        ]
    ],
    'mobile_domain' => [
        'name' => 'mobile_domain',
        'is_frontend' => '1',
        'value' => 'mobile_domain',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '访问地址',
        'menu_zh' => [
            '移动端',
            '基础配置',
            '基础配置'
        ]
    ],
    'qrcode_type' => [
        'name' => 'qrcode_type',
        'is_frontend' => '0',
        'value' => 'qrcode_type ',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '二维码展示方式',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'captcha_tencent_appid' => [
        'name' => 'captcha_tencent_appid',
        'is_frontend' => '1',
        'value' => 'captcha_tencent_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '腾讯防水墙',
        'map' => [],
        'field_name' => 'appid',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'captcha_tencent_appsecret' => [
        'name' => 'captcha_tencent_appsecret',
        'is_frontend' => '0',
        'value' => 'captcha_tencent_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '腾讯防水墙',
        'map' => [],
        'field_name' => 'secretkey',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'captcha_vaptcha_vid' => [
        'name' => 'captcha_vaptcha_vid',
        'is_frontend' => '1',
        'value' => 'captcha_vaptcha_vid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '手势验证',
        'map' => [],
        'field_name' => 'vid',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'captcha_vaptcha_key' => [
        'name' => 'captcha_vaptcha_key',
        'is_frontend' => '0',
        'value' => 'captcha_vaptcha_key',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '手势验证',
        'map' => [],
        'field_name' => 'key',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'captcha_show_by_pwd_error' => [
        'name' => 'captcha_show_by_pwd_error',
        'is_frontend' => '1',
        'value' => 'captcha_show_by_pwd_error',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '手势验证',
        'map' => [],
        'field_name' => '密码登录错误次数',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'captcha_show_by_code_error' => [
        'name' => 'captcha_show_by_code_error',
        'is_frontend' => '1',
        'value' => 'captcha_show_by_code_error',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '手势验证',
        'map' => [],
        'field_name' => '密码登录错误次数',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            '验证码'
        ]
    ],
    'wechat_open_appid' => [
        'name' => 'wechat_open_appid',
        'is_frontend' => '1',
        'value' => 'wechat_open_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'AppId',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '微信开发平台'
        ]
    ],
    'wechat_open_appsecret' => [
        'name' => 'wechat_open_appsecret',
        'is_frontend' => '0',
        'value' => 'wechat_open_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'AppSecret',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '微信开发平台'
        ]
    ],
    'wechat_miniprogram_qrcode' => [
        'name' => 'wechat_miniprogram_qrcode',
        'is_frontend' => '1',
        'value' => 'wechat_miniprogram_qrcode',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '小程序码',
        'menu_zh' => [
            '移动端',
            '微信小程序',
            '基础配置',
            '基础配置'
        ]
    ],
    'app_android_download_url' => [
        'name' => 'app_android_download_url',
        'is_frontend' => '1',
        'value' => 'app_android_download_url',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '安卓下载地址',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'app_ios_download_url' => [
        'name' => 'app_ios_download_url',
        'is_frontend' => '1',
        'value' => 'app_ios_download_url',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'ios下载地址',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'payment_wechat_appid' => [
        'name' => 'payment_wechat_appid',
        'is_frontend' => '1',
        'value' => 'payment_wechat_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '绑定支付的APPID',
        'menu_zh' => [
            '系统',
            '基础配置',
            '支付配置',
            '微信支付'
        ]
    ],
    'payment_wechat_appsecret' => [
        'name' => 'payment_wechat_appsecret',
        'is_frontend' => '0',
        'value' => 'payment_wechat_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '公众帐号secert',
        'menu_zh' => [
            '系统',
            '基础配置',
            '支付配置',
            '微信支付'
        ]
    ],
    'payment_wechat_mchid' => [
        'name' => 'payment_wechat_mchid',
        'is_frontend' => '0',
        'value' => 'payment_wechat_mchid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '商户号(MCHID)',
        'menu_zh' => [
            '系统',
            '基础配置',
            '支付配置',
            '微信支付'
        ]
    ],
    'payment_wechat_key' => [
        'name' => 'payment_wechat_key',
        'is_frontend' => '0',
        'value' => 'payment_wechat_key',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '商户支付密钥(KEY)',
        'menu_zh' => [
            '系统',
            '基础配置',
            '支付配置',
            '微信支付'
        ]
    ],
    'payment_wechat_app_appid' => [
        'name' => 'payment_wechat_app_appid',
        'is_frontend' => '0',
        'value' => 'payment_wechat_app_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'APP(ios/android)appid',
        'menu_zh' => [
            '系统',
            '基础配置',
            '支付配置',
            '微信支付'
        ]
    ],
    'payment_wechat_app_app_appsecret' => [
        'name' => 'payment_wechat_app_app_appsecret',
        'is_frontend' => '0',
        'value' => 'payment_wechat_app_app_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'APP(ios/android)appsecret',
        'menu_zh' => [
            '系统',
            '基础配置',
            '支付配置',
            '微信支付'
        ]
    ],
    'jpush_appkey' => [
        'name' => 'jpush_appkey',
        'is_frontend' => '0',
        'value' => 'jpush_appkey',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '极光推送appkey',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'jpush_appsecret' => [
        'name' => 'jpush_appsecret',
        'is_frontend' => '0',
        'value' => 'jpush_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '极光推送appsecret',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'service_time' => [
        'name' => 'service_time',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'service_time',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '服务时间',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'service_qq' => [
        'name' => 'service_qq',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'service_qq',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '客服qq',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'network_security' => [
        'name' => 'network_security',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'network_security',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '公网安备',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'service_license' => [
        'name' => 'service_license',
        'is_frontend' => '1',
        'value' => 'service_license',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '人力资源服务许可证',
        'is_log' => 1,
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'flink_qq' => [
        'name' => 'flink_qq',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'flink_qq',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '申请友链qq',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'member_dirname' => [
        'name' => 'member_dirname',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'member_dirname',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'web端会员中心目录名称',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'nologin_notice_timerange' => [
        'name' => 'nologin_notice_timerange',
        'is_frontend' => '0',
        'value' => 'nologin_notice_timerange',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '用户未登录条件区间',
        'menu_zh' => [
            '工具',
            '休眠用户提醒',
            '基本配置'
        ]
    ],
    'nologin_notice_counter' => [
        'name' => 'nologin_notice_counter',
        'is_frontend' => '0',
        'value' => 'nologin_notice_counter',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '提醒次数限制',
        'menu_zh' => [
            '工具',
            '休眠用户提醒',
            '基本配置'
        ]
    ],
    'account_qqlogin_appid' => [
        'name' => 'account_qqlogin_appid',
        'is_frontend' => '1',
        'value' => 'account_qqlogin_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'AppId',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            'qq互联'
        ]
    ],
    'account_qqlogin_appkey' => [
        'name' => 'account_qqlogin_appkey',
        'is_frontend' => '0',
        'value' => 'account_qqlogin_appkey',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'AppKey',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            'qq互联'
        ]
    ],
    'app_android_version' => [
        'name' => 'app_android_version',
        'is_frontend' => '0',
        'value' => 'app_android_version',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '安卓当前版本号',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'app_ios_version' => [
        'name' => 'app_ios_version',
        'is_frontend' => '0',
        'value' => 'app_ios_version',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'ios当前版本号',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'app_android_upgrade_content' => [
        'name' => 'app_android_upgrade_content',
        'is_frontend' => '0',
        'value' => 'app_android_upgrade_content',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '安卓当前版本更新内容',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'app_ios_upgrade_content' => [
        'name' => 'app_ios_upgrade_content',
        'is_frontend' => '0',
        'value' => 'app_ios_upgrade_content',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'ios当前版本更新内容',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'app_android_upgrade_force' => [
        'name' => 'app_android_upgrade_force',
        'is_frontend' => '0',
        'value' => 'app_android_upgrade_force',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '安卓开启强制更新',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'app_ios_upgrade_force' => [
        'name' => 'app_ios_upgrade_force',
        'is_frontend' => '0',
        'value' => 'app_ios_upgrade_force',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => 'ios开启强制更新',
        'menu_zh' => [
            '移动端',
            'App',
            '基础配置',
            '基础配置'
        ]
    ],
    'account_trtc_appid' => [
        'name' => 'account_trtc_appid',
        'is_frontend' => '0',
        'value' => 'account_trtc_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'AppId',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '腾讯rtc'
        ]
    ],
    'account_trtc_secretkey' => [
        'name' => 'account_trtc_secretkey',
        'is_frontend' => '0',
        'value' => 'account_trtc_secretkey',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'SecretKey',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '腾讯rtc'
        ]
    ],
    'job_search_login' => [
        'name' => 'job_search_login',
        'is_frontend' => '0',
        'value' => 'job_search_login',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [0, 1],
        'field_name' => '搜索职位登录限制',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'job_search_login_num' => [
        'name' => 'job_search_login_num',
        'is_frontend' => '0',
        'value' => 'job_search_login_num',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '搜索职位登录条数限制',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'resume_search_login' => [
        'name' => 'resume_search_login',
        'is_frontend' => '0',
        'value' => 'resume_search_login',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '搜索简历登录限制',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'resume_search_login_num' => [
        'name' => 'resume_search_login_num',
        'is_frontend' => '0',
        'value' => 'resume_search_login_num',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '搜索简历登录条数限制',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'wechat_welcome_img_mediaid' => [
        'name' => 'wechat_welcome_img_mediaid',
        'is_frontend' => '0',
        'value' => 'wechat_welcome_img_mediaid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '关注欢迎图片',
        'menu_zh' => [
            '系统',
            '微信平台配置',
            '公众号基础信息',
            '公众号基础信息'
        ]
    ],
    'alicloud_phone_protect_open' => [
        'name' => 'alicloud_phone_protect_open',
        'is_frontend' => '0',
        'value' => 'alicloud_phone_protect_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '开启保护',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '号码隐私保护'
        ]
    ],
    'alicloud_phone_protect_type' => [
        'name' => 'alicloud_phone_protect_type',
        'is_frontend' => '0',
        'value' => 'alicloud_phone_protect_type',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '号码隐私模式',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '号码隐私保护'
        ]
    ],
    'alicloud_phone_protect_target' => [
        'name' => 'alicloud_phone_protect_target',
        'is_frontend' => '0',
        'value' => 'alicloud_phone_protect_target',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '隐私保护主体',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '号码隐私保护'
        ]
    ],
    'alicloud_app_key' => [
        'name' => 'alicloud_app_key',
        'is_frontend' => '0',
        'value' => 'alicloud_app_key',
        'is_secret' => '0',
        'is_json' => [],
        'association' => 'AxN',
        'map' => [],
        'field_name' => 'AccessKeyId',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '号码隐私保护'
        ]
    ],
    'alicloud_pool_key_axn' => [
        'name' => 'alicloud_pool_key_axn',
        'is_frontend' => '0',
        'value' => 'alicloud_pool_key_axn',
        'is_secret' => '0',
        'is_json' => [],
        'association' => 'AxN',
        'map' => [],
        'field_name' => '号池key',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '号码隐私保护'
        ]
    ],
    'alicloud_appsecret' => [
        'name' => 'alicloud_appsecret',
        'is_frontend' => '0',
        'value' => 'alicloud_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => 'AxN',
        'map' => [],
        'field_name' => 'AccessKeySecret',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '号码隐私保护'
        ]
    ],
    'alicloud_pool_key' => [
        'name' => 'alicloud_pool_key',
        'is_frontend' => '0',
        'value' => 'alicloud_pool_key',
        'is_secret' => '0',
        'is_json' => [],
        'association' => 'AxB',
        'map' => [],
        'field_name' => '号池key',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '号码隐私保护'
        ]
    ],
    'filter_ip' => [
        'name' => 'filter_ip',
        'is_frontend' => '1',
        'value' => 'filter_ip',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '黑名单IP',
        'menu_zh' => [
            '系统',
            '基础配置',
            '安全配置',
            'IP黑名单'
        ]
    ],
    'freelance_publish_resume_fee' => [
        'name' => 'freelance_publish_resume_fee',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_publish_resume_fee',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '简历发布费用',
        'unit' => '元',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '收费设置',
            '基础配置'
        ]
    ],
    'freelance_view_resume_fee' => [
        'name' => 'freelance_view_resume_fee',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_view_resume_fee',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '查看简历费用',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '收费设置',
            '基础配置'
        ]
    ],
    'freelance_publish_subject_fee' => [
        'name' => 'freelance_publish_subject_fee',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_publish_subject_fee',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '项目发布费用',
        'unit' => '元',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '收费设置',
            '基础配置'
        ]
    ],
    'freelance_view_subject_fee' => [
        'name' => 'freelance_view_subject_fee',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_view_subject_fee',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '查看项目费用',
        'unit' => '元',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '收费设置',
            '基础配置'
        ]
    ],
    'freelance_refresh_subject_fee' => [
        'name' => 'freelance_refresh_subject_fee',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_refresh_subject_fee',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '刷新项目费用',
        'unit' => '元',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '收费设置',
            '基础配置'
        ]
    ],
    'freelance_promote_subject_sets' => [
        'name' => 'freelance_promote_subject_sets',
        'is_frontend' => '1',
        'value' => 'freelance_promote_subject_sets',
        'is_secret' => '0',
        'is_json' => ['name' => '套餐名称', 'days' => '时长', 'fee' => '价格', 'sys' => '操作删除'],
        'association' => '',
        'map' => [],
        'field_name' => '项目推广设置',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '收费设置',
            '推广设置',
            '项目推广设置'
        ]
    ],
    'freelance_new_resume_audit' => [
        'name' => 'freelance_new_resume_audit',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_new_resume_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '未审核',
            1 => '已审核'
        ],
        'field_name' => '新创建简历状态',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '审核设置'
        ]
    ],
    'freelance_edited_resume_audit' => [
        'name' => 'freelance_edited_resume_audit',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_edited_resume_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '未审核',
            -1 => '保持不变'
        ],
        'field_name' => '修改后简历状态',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '审核设置'
        ]
    ],
    'freelance_new_subject_audit' => [
        'name' => 'freelance_new_subject_audit',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_new_subject_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '未审核',
            1 => '已审核'
        ],
        'field_name' => '新创建项目状态',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '审核设置'
        ]
    ],
    'freelance_edited_subject_audit' => [
        'name' => 'freelance_edited_subject_audit',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'freelance_edited_subject_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '未审核',
            -1 => '保持不变'
        ],
        'field_name' => '修改后项目状态',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '审核设置'
        ]
    ],
    'freelance_hot_words' => [
        'name' => 'freelance_hot_words',
        'is_frontend' => '1',
        'value' => 'freelance_hot_words',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新增热门词',
        'menu_zh' => [
            '应用',
            '自由职业',
            '基础配置',
            '热门词'
        ]
    ],
    'cityinfo_title' => [
        'name' => 'cityinfo_title',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'cityinfo_title',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '平台名称',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '基础设置',
            '应用设置'
        ]
    ],
    'cityinfo_kefu' => [
        'name' => 'cityinfo_kefu',
        'is_frontend' => '1',
        'value' => 'cityinfo_kefu',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '客服二维码',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '基础设置',
            '应用设置'
        ]
    ],
    'cityinfo_free_refresh_time' => [
        'name' => 'cityinfo_free_refresh_time',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'cityinfo_free_refresh_time',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '每天免费刷新次数',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '基础设置',
            '应用设置'
        ]
    ],
    'cityinfo_free_refresh_period' => [
        'name' => 'cityinfo_free_refresh_period',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'cityinfo_free_refresh_period',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '刷新间隔',
        'unit' => '秒',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '基础配置',
            '应用设置'
        ]
    ],
    'cityinfo_new_article_audit' => [
        'name' => 'cityinfo_new_article_audit',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'cityinfo_new_article_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '未审核',
            1 => '已审核'
        ],
        'field_name' => '新发布信息状态',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '基础设置',
            '审核设置'
        ]
    ],
    'cityinfo_edited_article_audit' => [
        'name' => 'cityinfo_edited_article_audit',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'cityinfo_edited_article_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            -1 => '保持不变',
            0 => '未审核'
        ],
        'field_name' => '修改后信息状态',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '基础设置',
            '审核设置'
        ]
    ],
    'cityinfo_promote_set' => [
        'name' => 'cityinfo_promote_set',
        'is_frontend' => '0',
        'value' => 'cityinfo_promote_set',
        'is_secret' => '0',
        'is_json' => ['name' => '套餐名称', 'days' => '时长', 'fee' => '价格', 'sys' => '操作删除'],
        'association' => '',
        'map' => [],
        'field_name' => '信息置顶配置',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '收费设置',
            '信息置顶配置'
        ]
    ],
    'cityinfo_refresh_article_fee' => [
        'name' => 'cityinfo_refresh_article_fee',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'cityinfo_refresh_article_fee',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '设置金额',
        'unit' => '元/次',
        'menu_zh' => [
            '应用',
            '同城信息',
            '基础配置',
            '收费设置',
            '付费刷新配置'
        ]
    ],
    'guide_title' => [
        'name' => 'guide_title',
        'is_frontend' => '1',
        'value' => 'guide_title',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '标题',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '底部引导条'
        ]
    ],
    'guide_content' => [
        'name' => 'guide_content',
        'is_frontend' => '1',
        'value' => 'guide_content',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '内容',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '底部引导条'
        ]
    ],
    'guide_open' => [
        'name' => 'guide_open',
        'is_frontend' => '1',
        'value' => 'guide_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '是否开启',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '底部引导条'
        ]
    ],
    'guide_qrcode' => [
        'name' => 'guide_qrcode',
        'is_frontend' => '1',
        'value' => 'guide_qrcode',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '二维码图片',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '底部引导条'
        ]
    ],
    'service_ol_open' => [
        'name' => 'service_ol_open',
        'is_frontend' => '1',
        'value' => 'service_ol_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '是否显示',
        'menu_zh' => [
            '系统',
            '基础配置',
            '在线客服',
            '在线客服'
        ]
    ],
    'account_qqlogin_open' => [
        'name' => 'account_qqlogin_open',
        'is_frontend' => '1',
        'value' => 'account_qqlogin_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '开启qq登录',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            'qq互联'
        ]
    ],
    'wechat_login_open' => [
        'name' => 'wechat_login_open',
        'is_frontend' => '1',
        'value' => 'wechat_login_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '开启微信登录',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '微信开发平台'
        ]
    ],
    'resume_auto_refresh' => [
        'name' => 'resume_auto_refresh',
        'is_frontend' => '1',
        'value' => 'resume_auto_refresh',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '登录自动刷新简历',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '基本设置'
        ]
    ],
    'audit_add_resume' => [
        'name' => 'audit_add_resume',
        'is_frontend' => '0',
        'value' => 'audit_add_resume',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新注册简历审核状态',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'hotword_display_method' => [
        'name' => 'hotword_display_method',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'hotword_display_method',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '默认排序',
            1 => '随机排序'
        ],
        'field_name' => '显示方式',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '热门关键词'
        ]
    ],
    'screen_token' => [
        'name' => 'screen_token',
        'is_frontend' => '0',
        'value' => 'screen_token',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'token',
        'menu_zh' => [
            '画像',
            '数据大屏'
        ]
    ],
    'screen_base' => [
        'name' => 'screen_base',
        'is_frontend' => '0',
        'value' => 'screen_base',
        'is_secret' => '0',
        'is_json' => ['企业数基数', '岗位数基数', '岗位需求数基数', '简历数基数'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '画像',
            '数据大屏'
        ]
    ],
    'screen_title' => [
        'name' => 'screen_title',
        'is_frontend' => '0',
        'value' => 'screen_title',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '标题',
        'menu_zh' => [
            '画像',
            '数据大屏'
        ]
    ],
    'rand_click_job' => [
        'name' => 'rand_click_job',
        'is_frontend' => '0',
        'value' => 'rand_click_job',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '职位浏览量随机值',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'rand_click_company' => [
        'name' => 'rand_click_company',
        'is_frontend' => '0',
        'value' => 'rand_click_company',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '企业浏览量随机值',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'rand_click_resume' => [
        'name' => 'rand_click_resume',
        'is_frontend' => '0',
        'value' => 'rand_click_resume',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '简历浏览量随机值',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'index_tpl' => [
        'name' => 'index_tpl',
        'is_frontend' => '1',
        'value' => 'index_tpl',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '首页模板',
        'menu_zh' => [
            '系统',
            '基础配置',
            '模板管理',
            'PC端模板',
            '首页模板'
        ]
    ],
    'audit_com_project' => [
        'name' => 'audit_com_project',
        'is_frontend' => '1',
        'value' => 'audit_com_project',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '需要认证项目',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '认证/审核'
        ]
    ],
    'shortvideo_enable' => [
        'name' => 'shortvideo_enable',
        'is_frontend' => '1',
        'value' => 'shortvideo_enable',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '视频功能启用',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置',
            '基础配置'
        ]
    ],
    'shortvideo_jobing_view_init' => [
        'name' => 'shortvideo_jobing_view_init',
        'is_frontend' => '0',
        'value' => 'shortvideo_jobing_view_init',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '视频招聘浏览量随机值',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置',
            '基础配置'
        ]
    ],
    'shortvideo_finding_view_init' => [
        'name' => 'shortvideo_finding_view_init',
        'is_frontend' => '0',
        'value' => 'shortvideo_finding_view_init',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '视频求职浏览量随机值',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置',
            '基础配置'
        ]
    ],
    'shortvideo_jobing_hot' => [
        'name' => 'shortvideo_jobing_hot',
        'is_frontend' => '1',
        'value' => 'shortvideo_jobing_hot',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '视频招聘浏览量热门值',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置',
            '基础配置'
        ]
    ],
    'shortvideo_finding_hot' => [
        'name' => 'shortvideo_finding_hot',
        'is_frontend' => '1',
        'value' => 'shortvideo_finding_hot',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '视频求职浏览量热门值',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置',
            '基础配置'
        ]
    ],
    'shortvideo_enable_setmeal' => [
        'name' => 'shortvideo_enable_setmeal',
        'is_frontend' => '1',
        'value' => 'shortvideo_enable_setmeal',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '允许企业套餐发布',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置',
            '企业发布配置'
        ]
    ],
    'shortvideo_new_jobing_audit' => [
        'name' => 'shortvideo_new_jobing_audit',
        'is_frontend' => '0',
        'value' => 'shortvideo_new_jobing_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新发布视频招聘默认审核状态',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置'
        ]
    ],
    'shortvideo_edited_jobing_audit' => [
        'name' => 'shortvideo_edited_jobing_audit',
        'is_frontend' => '0',
        'value' => 'shortvideo_edited_jobing_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '修改视频招聘后默认审核状态',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置'
        ]
    ],
    'shortvideo_new_finding_audit' => [
        'name' => 'shortvideo_new_finding_audit',
        'is_frontend' => '0',
        'value' => 'shortvideo_new_finding_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新发布视频简历默认审核状态',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置'
        ]
    ],
    'shortvideo_edited_finding_audit' => [
        'name' => 'shortvideo_edited_finding_audit',
        'is_frontend' => '0',
        'value' => 'shortvideo_edited_finding_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '修改视频简历后默认审核状态',
        'menu_zh' => [
            '应用',
            '视频招聘',
            '功能配置',
        ]
    ],
    'fast_job_contact' => [
        'name' => 'fast_job_contact',
        'is_frontend' => '1',
        'value' => 'fast_job_contact',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '快速招聘联系方式',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'fast_resume_contact' => [
        'name' => 'fast_resume_contact',
        'is_frontend' => '1',
        'value' => 'fast_resume_contact',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '快速求职联系方式',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'fast_job_num' => [
        'name' => 'fast_job_num',
        'is_frontend' => '0',
        'value' => 'fast_job_num',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '快速招聘发布次数',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'fast_resume_num' => [
        'name' => 'fast_resume_num',
        'is_frontend' => '0',
        'value' => 'fast_resume_num',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '快速求职发布次数',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'fast_job_add_audit' => [
        'name' => 'fast_job_add_audit',
        'is_frontend' => '0',
        'value' => 'fast_job_add_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新发布职位',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'fast_resume_add_audit' => [
        'name' => 'fast_resume_add_audit',
        'is_frontend' => '0',
        'value' => 'fast_resume_add_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新发布简历',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'fast_job_edit_audit' => [
        'name' => 'fast_job_edit_audit',
        'is_frontend' => '0',
        'value' => 'fast_job_edit_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '修改后职位',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'fast_resume_edit_audit' => [
        'name' => 'fast_resume_edit_audit',
        'is_frontend' => '0',
        'value' => 'fast_resume_edit_audit',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '修改后简历',
        'menu_zh' => [
            '应用',
            '快招信息',
            '基础配置'
        ]
    ],
    'subsite_open' => [
        'name' => 'subsite_open',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'subsite_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '不开启',
            1 => '开启'
        ],
        'field_name' => '是否开启',
        'menu_zh' => [
            '应用',
            '地区分站',
            '分站管理'
        ]
    ],
    'display_new_com' => [
        'name' => 'display_new_com',
        'is_frontend' => '0',
        'value' => 'display_new_com',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '新注册企业显示状态',
        'menu_zh' => [
            '应用',
            '地区分站',
            '分站管理'
        ]
    ],
    'reopen_setmeal_deadline' => [
        'name' => 'reopen_setmeal_deadline',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'reopen_setmeal_deadline',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '不叠加',
            1 => '叠加'
        ],
        'field_name' => '重开套餐时间处理',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '基本设置',
            '重新开通套餐配置'
        ]
    ],
    'reopen_setmeal_resource' => [
        'name' => 'reopen_setmeal_resource',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'reopen_setmeal_resource',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '不叠加',
            1 => '叠加'
        ],
        'field_name' => '重开套餐资源处理',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '基本设置',
            '重新开通套餐配置'
        ]
    ],
    'overtime_setmeal_resource' => [
        'name' => 'overtime_setmeal_resource',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'overtime_setmeal_resource',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '清空',
            1 => '保留'
        ],
        'field_name' => '套餐到期资源处理',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '基本设置',
            '会员到期套餐配置'
        ]
    ],
    'overtime_setmeal_jobnum' => [
        'name' => 'overtime_setmeal_jobnum',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'overtime_setmeal_jobnum',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '关闭超出职位',
            1 => '保留全部职位'
        ],
        'field_name' => '套餐到期超出职位处理',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '套餐配置',
            '基本设置',
            '会员到期套餐配置'
        ]
    ],
    'im_open' => [
        'name' => 'im_open',
        'is_frontend' => '1',
        'value' => 'im_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '开启职聊功能',
        'menu_zh' => [
            '工具',
            '即时通讯',
            '基本配置',
            '基本设置'
        ]
    ],
    'im_notice_open' => [
        'name' => 'im_notice_open',
        'is_frontend' => '1',
        'value' => 'im_notice_open',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '开启聊天公告',
        'menu_zh' => [
            '工具',
            '即时通讯',
            '基本配置',
            '基本设置'
        ]
    ],
    'im_notice' => [
        'name' => 'im_notice',
        'is_frontend' => '1',
        'value' => 'im_notice',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '公告内容',
        'menu_zh' => [
            '工具',
            '即时通讯',
            '基本配置',
            '基本设置'
        ]
    ],
    'im_unread' => [
        'name' => 'im_unread',
        'is_frontend' => '1',
        'value' => 'im_unread',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '开启未读通知',
        'menu_zh' => [
            '工具',
            '即时通讯',
            '基本配置',
            '基本设置'
        ]
    ],
    'rand_click_article' => [
        'name' => 'rand_click_article',
        'is_frontend' => '0',
        'value' => 'rand_click_article',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '资讯浏览量随机值',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'follow_red_envelopes' => [
        'name' => 'follow_red_envelopes',
        'is_frontend' => '1',
        'value' => 'follow_red_envelopes',
        'is_secret' => '0',
        'is_json' => ['is_open' => '是否开启', 'min_price' => '红包金额', 'max_price' => '红包金额'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '工具',
            '红包活动',
            '红包设置',
            '基础红包'
        ]
    ],
    'binding_red_envelopes' => [
        'name' => 'binding_red_envelopes',
        'is_frontend' => '1',
        'value' => 'binding_red_envelopes',
        'is_secret' => '0',
        'is_json' => ['is_open' => '是否开启', 'min_price' => '红包金额', 'max_price' => '红包金额'],
        'association' => '',
        'map' => [],
        'field_name' => '绑定微信公众号红包',
        'menu_zh' => [
            '工具',
            '红包活动',
            '红包设置',
            '基础红包'
        ]
    ],
    'improve_resume_red_envelopes' => [
        'name' => 'improve_resume_red_envelopes',
        'is_frontend' => '1',
        'value' => 'improve_resume_red_envelopes',
        'is_secret' => '0',
        'is_json' => ['is_open' => '是否开启', 'Integrity' => '简历完整度要求', 'min_price' => '红包金额', 'max_price' => '红包金额'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '工具',
            '红包活动',
            '红包设置',
            '基础红包'
        ]
    ],
    'invite_register' => [
        'name' => 'invite_register',
        'is_frontend' => '1',
        'value' => 'invite_register',
        'is_secret' => '0',
        'is_json' => ['is_open' => '是否开启', 'price' => '发放金额'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '工具',
            '红包活动',
            '邀请注册',
            '参数配置'
        ]
    ],
    'withdrawal' => [
        'name' => 'withdrawal',
        'is_frontend' => '1',
        'value' => 'withdrawal',
        'is_secret' => '0',
        'is_json' => ['enterprise_certification' => '企业认证提现设置', 'amount_setting' => '最低提现金额设置', 'tips' => '提现规则', 'amount_limit' => '最低提现金额限制'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '工具',
            '红包活动',
            '红包设置',
            '提现设置'
        ]
    ],
    'corpwechat_api' => [
        'name' => 'corpwechat_api',
        'is_frontend' => '0',
        'value' => 'corpwechat_api',
        'is_secret' => '0',
        'is_json' => ['is_open' => '开关', 'corpid' => '企业ID', 'agentid' => '应用ID', 'corpsecret' => '应用Secret', 'customer_contact_secret' => '通讯录同步Secret', 'token' => '回调服务Token', 'encoding_ase_key' => 'EncodingAESKey'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '应用',
            '企微管理',
            '企微设置',
            '参数配置'
        ]
    ],
    'live_app_key' => [
        'name' => 'live_app_key',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'live_app_key',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'app_id',
        'menu_zh' => [
            '应用',
            '直播招聘',
            '基础配置',
            '基础配置'
        ]
    ],
    'live_app_secret' => [
        'name' => 'live_app_secret',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'live_app_secret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'app_secret',
        'menu_zh' => [
            '应用',
            '直播招聘',
            '基础配置',
            '基础配置'
        ]
    ],
    'qianfan_token' => [
        'name' => 'qianfan_token',
        'is_frontend' => '0',
        'value' => 'qianfan_token',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'token',
        'menu_zh' => [
            '移动端',
            '第三方配置',
            '基础配置',
            '千帆配置'
        ]
    ],
    'qianfan_domain' => [
        'name' => 'qianfan_domain',
        'is_frontend' => '0',
        'value' => 'qianfan_domain',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'API域名',
        'menu_zh' => [
            '移动端',
            '第三方配置',
            '基础配置',
            '千帆配置'
        ]
    ],
    'magappx_secret' => [
        'name' => 'magappx_secret',
        'is_frontend' => '0',
        'value' => 'magappx_secret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'secret',
        'menu_zh' => [
            '移动端',
            '第三方配置',
            '基础配置',
            '马甲配置'
        ]
    ],
    'magappx_domain' => [
        'name' => 'magappx_domain',
        'is_frontend' => '0',
        'value' => 'magappx_domain',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '客户端域名',
        'menu_zh' => [
            '移动端',
            '第三方配置',
            '基础配置',
            '马甲配置'
        ]
    ],
    'job_show_tpl' => [
        'name' => 'job_show_tpl',
        'is_frontend' => '0',
        'value' => 'job_show_tpl',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '职位详情',
        'menu_zh' => [
            '系统',
            '基础配置',
            '模板管理',
            'PC端模板',
            '职位详情'
        ]
    ],
    'company_show_tpl' => [
        'name' => 'company_show_tpl',
        'is_frontend' => '0',
        'value' => 'company_show_tpl',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '企业详情',
        'menu_zh' => [
            '系统',
            '基础配置',
            '模板管理',
            'PC端模板',
            '企业详情'
        ]
    ],
    'resume_show_tpl' => [
        'name' => 'resume_show_tpl',
        'is_frontend' => '0',
        'value' => 'resume_show_tpl',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '简历详情',
        'menu_zh' => [
            '系统',
            '基础配置',
            '模板管理',
            'PC端模板',
            '简历详情'
        ]
    ],
    'mobile_job_show_tpl' => [
        'name' => 'mobile_job_show_tpl',
        'is_frontend' => '1',
        'value' => 'mobile_job_show_tpl',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '职位详情',
        'menu_zh' => [
            '系统',
            '基础配置',
            '模板管理',
            '触屏H5模板',
            '职位详情'
        ]
    ],
    'mobile_company_show_tpl' => [
        'name' => 'mobile_company_show_tpl',
        'is_frontend' => '1',
        'value' => 'mobile_company_show_tpl',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '企业详情',
        'menu_zh' => [
            '系统',
            '基础配置',
            '模板管理',
            '触屏H5模板',
            '企业详情'
        ]
    ],
    'mobile_resume_show_tpl' => [
        'name' => 'mobile_resume_show_tpl',
        'is_frontend' => '1',
        'value' => 'mobile_resume_show_tpl',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '简历详情',
        'menu_zh' => [
            '系统',
            '基础配置',
            '模板管理',
            '触屏H5模板',
            '简历详情'
        ]
    ],
    'qualification_publicity' => [
        'name' => 'qualification_publicity',
        'is_frontend' => '1',
        'value' => 'qualification_publicity',
        'is_secret' => '0',
        'is_json' => ['business_license' => '电子营业执照', 'service_license' => '人力资源服务许可证', 'service_license_id' => '人力资源服务许可证照片', 'business_licenses' => '增值电信业务经营许可证', 'business_licenses_id' => '增值电信业务经营许可证照片'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '网站配置'
        ]
    ],
    'account_outbound' => [
        'name' => 'account_outbound',
        'is_frontend' => '0',
        'value' => 'account_outbound',
        'is_secret' => '0',
        'is_json' => ['app_id' => 'AppKey', 'app_secret' => 'AppSecret'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '应用',
            '外呼中心',
            '外呼配置',
            '外呼配置'
        ]
    ],
    'job_search_order' => [
        'name' => 'job_search_order',
        'is_frontend' => '0',
        'value' => 'job_search_order',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [1, 2],
        'field_name' => '搜索结果排序方式',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'resume_search_order' => [
        'name' => 'resume_search_order',
        'is_frontend' => '0',
        'value' => 'resume_search_order',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [1, 2],
        'field_name' => '搜索结果排序方式',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'account_zhitoo_resume' => [
        'name' => 'account_zhitoo_resume',
        'is_frontend' => '0',
        'value' => 'account_zhitoo_resume',
        'is_secret' => '0',
        'is_json' => ['is_open' => '启用简历解析', 'appKey' => 'AppKey', 'appSecret' => 'AppSecret'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '简历解析'
        ]
    ],
    'account_zhitoo_document' => [
        'name' => 'account_zhitoo_document',
        'is_frontend' => '0',
        'value' => 'account_zhitoo_resume',
        'is_secret' => '0',
        'is_json' => ['appKey' => 'AppKey', 'appSecret' => 'AppSecret'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '系统',
            '基础配置',
            '合作账号',
            '在线文档预览'
        ]
    ],
    'account_qy_directory' => [
        'name' => 'account_qy_directory',
        'is_frontend' => '0',
        'value' => 'account_qy_directory',
        'is_secret' => '0',
        'is_json' => ['app_key' => 'AppKey', 'app_secret' => 'AppSecret'],
        'association' => '',
        'map' => [],
        'field_name' => '',
        'menu_zh' => [
            '用户',
            '企业名录',
            '基础配置',
            '基础配置'
        ]
    ],
    'token_expire' => [
        'name' => 'token_expire',
        'is_frontend' => '0',
        'value' => 'token_expire',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '登录时效',
        'menu_zh' => [
            '系统',
            '基础配置',
            '自定义数据配置',
            '自定义数据'
        ]
    ],
    'job_search_engine' => [
        'name' => 'job_search_engine',
        'is_frontend' => '0',
        'value' => 'job_search_engine',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [1, 2],
        'field_name' => '搜索方式',
        'menu_zh' => [
            '系统',
            '企业业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'resume_search_engine' => [
        'name' => 'resume_search_engine',
        'is_frontend' => '0',
        'value' => 'resume_search_engine',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [1, 2],
        'field_name' => '搜索方式',
        'menu_zh' => [
            '系统',
            '个人业务配置',
            '基础配置',
            '搜索'
        ]
    ],
    'douyin_miniprogram_appid' => [
        'name' => 'douyin_miniprogram_appid',
        'is_frontend' => '0',
        'value' => 'douyin_miniprogram_appid',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'appid',
        'menu_zh' => [
            '移动端',
            '抖音小程序',
            '基础配置',
            '基础配置'
        ]
    ],
    'douyin_miniprogram_appsecret' => [
        'name' => 'douyin_miniprogram_appsecret',
        'is_frontend' => '0',
        'value' => 'douyin_miniprogram_appsecret',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => 'appsecret',
        'menu_zh' => [
            '移动端',
            '抖音小程序',
            '基础配置',
            '基础配置'
        ]
    ],
    'douyin_miniprogram_qrcode' => [
        'name' => 'douyin_miniprogram_qrcode',
        'is_frontend' => '0',
        'value' => 'douyin_miniprogram_qrcode',
        'is_secret' => '1',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '小程序码',
        'menu_zh' => [
            '移动端',
            '抖音小程序',
            '基础配置',
            '基础配置'
        ]
    ],
    'is_open_map' => [
        'name' => 'is_open_map',
        'is_frontend' => '1',
        'value' => 'is_open_map',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '是否开启电子地图',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'map_type' => [
        'name' => 'map_type',
        'is_frontend' => '1',
        'value' => 'map_type',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '地图类型',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'tian_map_ak' => [
        'name' => 'tian_map_ak',
        'is_frontend' => '1',
        'value' => 'tian_map_ak',
        'is_secret' => '1',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '天地图AK',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'tian_map_server_ak' => [
        'name' => 'tian_map_server_ak',
        'is_frontend' => '0',
        'value' => 'tian_map_server_ak',
        'is_secret' => '1',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '天地图服务端AK',
        'menu_zh' => [
            '系统',
            '基础配置',
            '系统配置',
            '电子地图'
        ]
    ],
    'subsite_domain' => [
        'name' => 'subsite_domain',
        'is_log' => 1,
        'is_frontend' => '1',
        'value' => 'subsite_domain',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [],
        'field_name' => '分站顶级域名',
        'menu_zh' => [
            '应用',
            '地区分站',
            '分站管理'
        ]
    ],
    'douyin_miniprogram_examine' => [
        'name' => 'douyin_miniprogram_examine',
        'is_log' => 1,
        'is_frontend' => '0',
        'value' => 'douyin_miniprogram_examine',
        'is_secret' => '0',
        'is_json' => [],
        'association' => '',
        'map' => [
            0 => '关闭',
            1 => '开启'
        ],
        'field_name' => '抖音账号登录',
        'menu_zh' => [
            '移动端',
            '抖音小程序',
            '基础配置',
            '基础配置'
        ]
    ],
];