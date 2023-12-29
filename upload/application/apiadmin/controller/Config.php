<?php

namespace app\apiadmin\controller;

use think\Db;

class Config extends \app\common\controller\Backend
{
    public function index()
    {
        if (request()->isGet()) {
            $info = model('Config')->column('name,value');
            $info['logoUrl'] = model('Uploadfile')->getFileUrl($info['logo']);
            $info['logoUrl'] = $info['logoUrl'] ? $info['logoUrl'] : make_file_url('resource/logo.png');
            $info['squarelogoUrl'] = model('Uploadfile')->getFileUrl($info['square_logo']);
            $info['squarelogoUrl'] = $info['squarelogoUrl'] ? $info['squarelogoUrl'] : make_file_url('resource/square_logo.png');
            $info['qrcodeUrl'] = model('Uploadfile')->getFileUrl($info['wechat_qrcode']);
            $info['qrcodeUrl'] = $info['qrcodeUrl'] ? $info['qrcodeUrl'] : make_file_url('resource/weixin_img.jpg');
            $info['infopicUrl'] = model('Uploadfile')->getFileUrl($info['wechat_info_img']);
            $info['infopicUrl'] = $info['infopicUrl'] ? $info['infopicUrl'] : make_file_url('resource/wechat_info_img.jpg');
            $info['guide_qrcodeUrl'] = model('Uploadfile')->getFileUrl($info['guide_qrcode']);
            $info['guide_qrcodeUrl'] = $info['guide_qrcodeUrl'] ? $info['guide_qrcodeUrl'] : ($info['sitedomain'] . $info['sitedir'] . 'apiadmin/qrcode/normal?url=' . $info['mobile_domain']);
            foreach ($info as $key => $value) {
                $value = $value;
                if (is_json($value)) {
                    $info[$key] = json_decode($value, true);
                }
                if (
                    in_array($key, ['agreement', 'privacy', 'remittance_desc', 'wechat_welcome_text', 'statistics'])
                ) {
                    $info[$key] = htmlspecialchars_decode($value, ENT_QUOTES);
                }
            }

            /**
             * 增加app资质公示字段
             * cy 2023-7-28
             */
            // 电子营业执照
            $info['qualification_publicity']['business_license_url'] = isset($info['qualification_publicity']['business_license_id']) && !empty($info['qualification_publicity']['business_license_id']) ? model('Uploadfile')->getFileUrl($info['qualification_publicity']['business_license_id']) : '';
            // 人力资源服务许可证
            $info['qualification_publicity']['service_license_url'] = isset($info['qualification_publicity']['service_license_id']) && !empty($info['qualification_publicity']['service_license_id']) ? model('Uploadfile')->getFileUrl($info['qualification_publicity']['service_license_id']) : '';
            // 增值电信业务经营许可证
            $info['qualification_publicity']['business_licenses_url'] = isset($info['qualification_publicity']['business_licenses_id']) && !empty($info['qualification_publicity']['business_licenses_id']) ? model('Uploadfile')->getFileUrl($info['qualification_publicity']['business_licenses_id']) : '';
            if (isset($info['map_lng']) && isset($info['map_lat']))
            {
                $coordinate = model('Config')->bd09ToWgs84($info['map_lng'],$info['map_lat']);
                $info['map_lng'] = $coordinate['lng'];
                $info['map_lat'] = $coordinate['lat'];
            }
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $inputdata = input('post.');
            if (isset($inputdata['map_type']) && !empty($inputdata['map_lng']) && !empty($inputdata['map_lat']))
            {
                $coordinate = model('Config')->wgs84ToBd09($inputdata['map_lng'],$inputdata['map_lat']);
                $inputdata['map_lng'] = $coordinate['lng'];
                $inputdata['map_lat'] = $coordinate['lat'];
            }
            /**
             * 分站顶级域名，修改config-cookie配置
             */
            if (isset($inputdata['subsite_domain']) || isset($inputdata['subsite_open'])) {
                if (intval($inputdata['subsite_open']) === 1) {
                    setConfigPHP(['domain'], [$inputdata['subsite_domain']]);
                } else {
                    setConfigPHP(['domain'], ['']);
                }
            }
            /**
             * 【ID1000706】
             * 【bug/新增】系统内置验证码加纯数字
             * cy 2023-6-30
             * 增加数字和英文类型的验证码
             */
            if (isset($inputdata['captcha_picture_rule']['code_mode']) && !empty($inputdata['captcha_picture_rule']['code_mode'])) {
                // code_mode：1|中文，2|数字，3|英文
                if ($inputdata['captcha_picture_rule']['code_mode'] == 1) {
                    // 中文
                    $inputdata['captcha_picture_rule']['useZh'] = '1';
                } elseif ($inputdata['captcha_picture_rule']['code_mode'] == 2) {
                    // 数字
                    $inputdata['captcha_picture_rule']['codeSet'] = '0123456789';
                } elseif ($inputdata['captcha_picture_rule']['code_mode'] == 3) {
                    // 英文
                    $inputdata['captcha_picture_rule']['codeSet'] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                }
            }
            // 电子营业执照
            if (isset($inputdata['qualification_publicity']['business_license_id']) && !empty($inputdata['qualification_publicity']['business_license_id'])) {
                $inputdata['qualification_publicity']['business_license_url'] = model('Uploadfile')->getFileUrl($inputdata['qualification_publicity']['business_license_id']);
            }
            // 人力资源服务许可证
            if (isset($inputdata['qualification_publicity']['service_license_id']) && !empty($inputdata['qualification_publicity']['service_license_id'])) {
                $inputdata['qualification_publicity']['service_license_url'] = model('Uploadfile')->getFileUrl($inputdata['qualification_publicity']['service_license_id']);
            }
            // 增值电信业务经营许可证
            if (isset($inputdata['qualification_publicity']['business_licenses_id']) && !empty($inputdata['qualification_publicity']['business_licenses_id'])) {
                $inputdata['qualification_publicity']['business_licenses_url'] = model('Uploadfile')->getFileUrl($inputdata['qualification_publicity']['business_licenses_id']);
            }

            try {
                // 开启事务
                Db::startTrans();
                $config_result = model('Config')->saveConfig($inputdata, $this->admininfo);
                if (false === $config_result) {
                    throw new \Exception(model('Config')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function clearCache()
    {
        rmdirs(RUNTIME_PATH . '/cache/');
        rmdirs(RUNTIME_PATH . '/log/');
        rmdirs(RUNTIME_PATH . '/temp/');
        rmdirs(SYS_UPLOAD_PATH . '/poster/');

        model('AdminLog')->writeLog(
            '后台更新缓存',
            $this->admininfo,
            0,
            1
        );

        $this->ajaxReturn(200, '更新缓存成功');
    }

    public function smsTplList()
    {
        if (request()->isGet()) {
            $list = model('SmsTpl')->select();
            $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
        } else {
            $inputdata = input('post.');
            if (!$inputdata) {
                $this->ajaxReturn(500, '提交数据为空');
            }

            $list = model('SmsTpl')->select();
            $tpl_list = array_column($list, null, 'id');

            $sqldata = [];
            $log_field = '修改系统-基础配置-短信配置，模板设置-阿里云通信，';
            foreach ($inputdata as $key => $value) {
                if (!$value['id']) {
                    continue;
                }
                if (!isset($tpl_list[$value['id']]) || empty($tpl_list[$value['id']])) {
                    continue;
                }
                $old_tplcode = $tpl_list[$value['id']]['alisms_tplcode'];
                if ($old_tplcode == $value['alisms_tplcode']) {
                    continue;
                }
                $old_tplcode = empty($old_tplcode) ? '未填写' : $old_tplcode;
                $arr['id'] = $value['id'];
                $arr['alisms_tplcode'] = $value['alisms_tplcode'];
                $log_field .= '注册账号-阿里模板ID:' . $old_tplcode . '->' . $value['alisms_tplcode'] . '；';
                $sqldata[] = $arr;
            }
            model('SmsTpl')
                ->isUpdate()
                ->saveAll($sqldata);

            model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );

            $this->ajaxReturn(200, '保存数据成功');
        }
    }

    public function fieldRule()
    {
        if (request()->isGet()) {
            $type = input('get.type/s', '', 'trim');
            switch ($type) {
                case 'resume':
                    $where['model_name'] = [
                        'in',
                        ['Resume', 'ResumeContact', 'ResumeIntention'],
                    ];
                    break;
                case 'job':
                    $where['model_name'] = ['in', ['Job', 'JobContact']];
                    break;
                case 'company':
                    $where['model_name'] = [
                        'in',
                        ['Company', 'CompanyInfo', 'CompanyContact'],
                    ];
                    break;
                default:
                    $this->ajaxReturn(500, '参数错误');
                    break;
            }
            $list = model('FieldRule')
                ->where($where)
                ->select();
            $this->ajaxReturn(200, '获取数据成功', $list);
        } else {
            $inputdata = input('post.');

            try {
                $fieldIds = array_column($inputdata, 'id');
                $fieldList = model('FieldRule')
                    ->whereIn('id', $fieldIds)
                    ->column('*', 'id');
                if (empty($fieldList)) {
                    $this->ajaxReturn(500, '没有要修改的自定义字段');
                }

                $model_name = model('FieldRule')->whereIn('id', $fieldIds)
                    ->value('model_name');
                switch ($model_name) {
                    case 'Resume':
                    case 'ResumeContact':
                    case 'ResumeIntention':
                        $log_field = '系统-基础配置-系统扩展配置，修改简历信息，';
                        break;

                    case 'Job':
                    case 'JobContact':
                        $log_field = '系统-基础配置-系统扩展配置，修改职位信息，';
                        break;

                    case 'Company':
                    case 'CompanyInfo':
                    case 'CompanyContact':
                        $log_field = '系统-基础配置-系统扩展配置，修改企业信息，';
                        break;

                    default:
                        $log_field = '系统-基础配置-系统扩展配置，修改自定义字段，';
                        break;
                }

                $sqldata = [];
                foreach ($inputdata as $value) {
                    if (!isset($fieldList[$value['id']]) || empty($fieldList[$value['id']])) {
                        continue;
                    }
                    $config = $fieldList[$value['id']];
                    if ($value['is_display'] != $config['is_display']
                        ||
                        $value['is_require'] != $config['is_require']
                        ||
                        $value['field_cn'] != $config['field_cn']
                    ) {
                        $log_field .= $config['field_cn'];
                        if ($value['field_cn'] != $config['field_cn']) {
                            $log_field .= '->' . $value['field_cn'];
                        }
                        $log_field .= ':'
                            . model('FieldRule')->map_is_display[$config['is_display']]
                            . '/'
                            . model('FieldRule')->map_is_require[$config['is_require']]
                            . '->'
                            . model('FieldRule')->map_is_display[$value['is_display']]
                            . '/'
                            . model('FieldRule')->map_is_require[$value['is_require']] . '；';
                    }
                    $arr['id'] = $value['id'];
                    $arr['field_cn'] = $value['field_cn'];
                    $arr['is_require'] = $value['is_require'];
                    $arr['is_display'] = $value['is_display'];
                    $sqldata[] = $arr;
                }

                // 开启事务
                Db::startTrans();

                if (!empty($sqldata)) {
                    if (
                        false ===
                        model('FieldRule')
                            ->isUpdate()
                            ->saveAll($sqldata)
                    ) {
                        throw new \Exception(model('FieldRule')->getError());
                    }
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    3
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function getFieldRule()
    {
        $list = model('FieldRule')->getCache();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    public function resumeModule()
    {
        if (request()->isGet()) {
            $list = model('ResumeModule')->select();
            $this->ajaxReturn(200, '获取数据成功', $list);
        } else {
            $inputdata = input('post.');

            try {
                $moduleIds = array_column($inputdata, 'id');
                $moduleList = model('ResumeModule')->whereIn('id', $moduleIds)
                    ->column('*', 'id');
                if (empty($moduleList)) {
                    $this->ajaxReturn(500, '没有要修改的简历模块');
                }

                $log_field = '系统-基础配置-系统扩展配置，修改简历模块，';

                $sqldata = [];
                foreach ($inputdata as $value) {
                    if (!isset($moduleList[$value['id']]) || empty($moduleList[$value['id']])) {
                        continue;
                    }
                    $config = $moduleList[$value['id']];
                    if ($value['is_display'] != $config['is_display']
                        ||
                        $value['score'] != $config['score']
                    ) {
                        $log_field .= $config['module_cn'] . ':'
                            . model('ResumeModule')->map_is_display[$config['is_display']]
                            . '/'
                            . '完整度' . $config['score']
                            . '->'
                            . model('ResumeModule')->map_is_display[$value['is_display']]
                            . '/'
                            . '完整度' . $value['score'] . '；';
                    }
                    $arr['id'] = $value['id'];
                    $arr['is_display'] = $value['is_display'];
                    $arr['score'] = $value['score'];
                    $sqldata[] = $arr;
                }

                // 开启事务
                Db::startTrans();

                if (!empty($sqldata)) {
                    if (
                        false ===
                        model('ResumeModule')
                            ->isUpdate()
                            ->saveAll($sqldata)
                    ) {
                        throw new \Exception(model('ResumeModule')->getError());
                    }
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    3
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    /**
     * 发送测试邮件
     */
    public function sendMailTest()
    {
        $inputdata = [
            'type' => input('post.type/s', '', 'trim'),
            'account' => input('post.account/a'),
            'email' => input('post.email/s', '', 'trim'),
        ];
        $class = new \app\common\lib\Mail();
        if (
            false ===
            $class->testSend(
                $inputdata['type'],
                $inputdata['account'],
                $inputdata['email'],
                '欢迎使用骑士人才系统邮件服务',
                '当您收到这封邮件，意味着您的邮箱服务已配置成功'
            )
        ) {
            $this->ajaxReturn(500, $class->getError());
        }

        model('AdminLog')->writeLog(
            '系统-基础配置-邮箱配置，发送测试邮件，接收邮箱:' . $inputdata['email'],
            $this->admininfo,
            0,
            1
        );

        $this->ajaxReturn(200, '发送邮件成功');
    }

    /**
     * 发送测试短信
     */
    public function sendSmsTest()
    {
        $inputdata = [
            'type' => input('post.type/s', '', 'trim'),
            'account' => input('post.account/a'),
            'mobile' => input('post.mobile/s', '', 'trim'),
        ];
        $class = new \app\common\lib\Sms();
        if (
            false ===
            $class->testSend(
                $inputdata['type'],
                $inputdata['account'],
                $inputdata['mobile']
            )
        ) {
            $this->ajaxReturn(500, $class->getError());
        }

        model('AdminLog')->writeLog(
            '系统-基础配置-短信配置，发送测试短信，接收手机号:' . $inputdata['mobile'],
            $this->admininfo,
            0,
            1
        );

        $this->ajaxReturn(200, '发送短信成功');
    }

    public function setMobileIndexModule()
    {
        if (request()->isGet()) {
            $list = model('MobileIndexModule')->column(
                'alias,is_display,plan_id'
            );
            $this->ajaxReturn(200, '获取数据成功', $list);
        } else {
            $inputdata = input('post.');
            $configlist = model('MobileIndexModule')->column(
                'id,alias,is_display,plan_id',
                'alias'
            );
            $sqldata = [];
            foreach ($inputdata as $key => $value) {
                if (!isset($configlist[$value['alias']])) {
                    continue;
                }
                $arr['id'] = $configlist[$value['alias']]['id'];
                $arr['is_display'] = $value['is_display'];
                $arr['plan_id'] = is_array($value['plan_id'])
                    ? implode(',', $value['plan_id'])
                    : $value['plan_id'];
                $sqldata[] = $arr;
            }
            model('MobileIndexModule')
                ->isUpdate()
                ->saveAll($sqldata);

            model('AdminLog')->writeLog(
                '修改触屏端个性化首页配置',
                $this->admininfo,
                0,
                3
            );

            $this->ajaxReturn(200, '保存数据成功');
        }
    }

    public function mobileIndexMenuList()
    {
        $list = model('MobileIndexMenu')
            ->order('sort_id desc,id asc')
            ->select();
        foreach ($list as $key => $value) {
            $value['iconUrl'] = model('Uploadfile')->getFileUrl($value['icon']);
            /**
             * 【ID1000432】
             * 【优化】链接带参数跳转后没有筛选状态
             * yx - 2022.11.21
             * [新增]:
             * $value['link_url'] = htmlspecialchars_decode($value['link_url']);
             */
            $value['link_url'] = htmlspecialchars_decode($value['link_url']);
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }

    public function mobileIndexMenuEdit()
    {
        $inputdata = [
            'id' => input('post.id/d', 0, 'intval'),
            'custom_title' => input('post.custom_title/s', '', 'trim'),
            'icon' => input('post.icon/d', 0, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
        ];

        $info = model('MobileIndexMenu')->find($inputdata['id']);
        if (null === $info) {
            $this->ajaxReturn(500, '要修改的菜单不存在');
        }

        model('MobileIndexMenu')
            ->allowField(true)
            ->save($inputdata, ['id' => $inputdata['id']]);

        // 日志
        $log_field = '触屏端个性化首页修改菜单信息，菜单名称:' . $info['title'];
        model('AdminLog')->writeLog(
            $log_field,
            $this->admininfo,
            0,
            3
        );

        $this->ajaxReturn(200, '保存数据成功');
    }

    public function setNotifyRule()
    {
        $inputdata = input('post.');

        try {
            $ruleIds = array_column($inputdata, 'id');
            $ruleList = model('NotifyRule')->whereIn('id', $ruleIds)
                ->column('id,title,open_message,open_sms,open_email,open_push', 'id');
            if (empty($ruleList)) {
                $this->ajaxReturn(500, '没有要修改的消息通知');
            }

            $uType = model('NotifyRule')->whereIn('id', $ruleIds)
                ->value('utype');
            switch ($uType) {
                case '1':
                    $log_field = '系统-基础配置-消息通知，通知企业，修改通知方式，';
                    break;

                case '2':
                    $log_field = '系统-基础配置-消息通知，通知求职者，修改通知方式，';
                    break;

                default:
                    $this->ajaxReturn(500, '错误的消息通知类型');
                    break;
            }

            $sqldata = [];
            foreach ($inputdata as $value) {
                if (!isset($ruleList[$value['id']]) || empty($ruleList[$value['id']])) {
                    continue;
                }
                $config = $ruleList[$value['id']];
                if ($value['open_message'] != $config['open_message']
                    ||
                    $value['open_sms'] != $config['open_sms']
                    ||
                    $value['open_email'] != $config['open_email']
                ) {
                    $log_one = $config['title'] . ':';
                    if ($value['open_message'] != $config['open_message']) {
                        $log_one .= '站内信'
                            . model('NotifyRule')->map_is_open[$config['open_message']]
                            . '->站内信'
                            . model('NotifyRule')->map_is_open[$value['open_message']]
                            . ';';
                    }
                    if ($value['open_sms'] != $config['open_sms']) {
                        $log_one .= '短信'
                            . model('NotifyRule')->map_is_open[$config['open_sms']]
                            . '->短信'
                            . model('NotifyRule')->map_is_open[$value['open_sms']]
                            . ';';
                    }
                    if ($value['open_email'] != $config['open_email']) {
                        $log_one .= '邮件'
                            . model('NotifyRule')->map_is_open[$config['open_email']]
                            . '->邮件'
                            . model('NotifyRule')->map_is_open[$value['open_email']]
                            . ';';
                    }
                    $log_field .= rtrim($log_one, ';') . '；';
                }
                $arr['id'] = $value['id'];
                $arr['open_message'] = $value['open_message'];
                $arr['open_sms'] = $value['open_sms'];
                $arr['open_email'] = $value['open_email'];
                $sqldata[] = $arr;
            }

            // 开启事务
            Db::startTrans();

            if (!empty($sqldata)) {
                if (
                    false ===
                    model('NotifyRule')
                        ->isUpdate()
                        ->saveAll($sqldata)
                ) {
                    throw new \Exception(model('NotifyRule')->getError());
                }
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function getNotifyRule()
    {
        $utype = input('get.utype/d', 1, 'intval');
        $return = [];
        $list = model('NotifyRule')->getCache();
        $list = $list[$utype];
        foreach ($list as $key => $value) {
            $value['type_cn'] = model('Message')->map_type[$value['type']];
            $return[] = $value;
        }

        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function setWechatNotifyRule()
    {
        $inputdata = input('post.');
        $configlist = model('WechatNotifyRule')->column('id,title,utype', 'id');
        $sqldata = [];
        foreach ($inputdata as $value) {
            if (!isset($configlist[$value['id']])) {
                continue;
            }
            $arr['id'] = $value['id'];
            $arr['is_open'] = $value['is_open'];
            $arr['tpl_id'] = $value['tpl_id'];
            $sqldata[] = $arr;
            $utype = $configlist[$value['id']]['utype'];
        }
        if (empty($sqldata)) {
            $this->ajaxReturn(500, '模板数据不存在');
        }

        try {
            Db::startTrans();

            // DB1:保存配置
            $save_result = model('WechatNotifyRule')
                ->isUpdate()
                ->saveAll($sqldata);
            if ($save_result === false) {
                throw new \Exception(model('WechatNotifyRule')->getError());
            }

            switch ($utype) {
                case 1:
                    $log_field = '系统-微信平台配置-模板消息通知，修改通知企业公众号模板配置';
                    break;
                case 2:
                    $log_field = '系统-微信平台配置-模板消息通知，修改通知求职者公众号模板配置';
                    break;
                case 3:
                    $log_field = '系统-微信平台配置-模板消息通知，修改通知管理员公众号模板配置';
                    break;
                case 4:
                    $log_field = '工具-营销工具-服务号群发，修改公众号模板配置';
                    break;
                default:
                    $this->ajaxReturn(500, '错误的模板系统类型');
                    break;
            }

            // DB2:日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();

            $this->ajaxReturn(200, '保存数据成功');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '保存数据失败', ['err_msg' => $e->getMessage()]);
        }
    }

    public function getWechatNotifyRule()
    {
        $utype = input('get.utype/d', 1, 'intval');
        $return = [];
        $list = model('WechatNotifyRule')->getCache();
        if (isset($list[$utype]) && !empty($list[$utype])) {
            $list = $list[$utype];
            foreach ($list as $value) {
                $return[] = $value;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 切换微信模板类型
     * @access public
     * @author edc
     */
    public function switchWechatTplType()
    {
        $wechatTplType = input('post.wechat_tpl_type/d', 1, 'intval');
        if (!in_array($wechatTplType, [1, 2])) {
            $this->ajaxReturn(500, '参数有误');
        }
        $wechatTplTypeData = [
            1 => '历史模板',
            2 => '类目模板'
        ];

        $configWhere = [
            'name' => 'wechat_tpl_type'
        ];
        $configModel = model('config');
        $configId = $configModel->where($configWhere)->value('id');
        if (empty($configId)) {
            $this->ajaxReturn(500, '未找到对应参数');
        }

        // 开启事务
        $configModel->startTrans();
        try {
            // 修改模板类型
            $configData = [
                'id' => $configId,
                'value' => $wechatTplType
            ];
            $result = $configModel->isUpdate()->save($configData);
            if (false === $result) {
                throw new \Exception('修改模板类型失败-请求SQL为：' . $configModel->getLastSql());
            }

            // 记录日志
            $log_result = model('AdminLog')->writeLog(
                '系统-微信平台配置-模板消息通知，选择模板:' . $wechatTplTypeData[$wechatTplType] . '。',
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 清除微信模板规则缓存
            cache('cache_wechat_notify_rule', null);

            // 提交事务
            $configModel->commit();
        } catch (\Throwable $e) {
            // 回滚事务
            $configModel->rollback();
            saveLog('切换失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            $this->ajaxReturn(500, '切换失败');
        }
        $this->ajaxReturn(200, '切换成功');
    }

}
