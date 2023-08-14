<?php

namespace app\apiadmin\controller;

class Config extends \app\common\controller\Backend
{
    public function index()
    {
        if (request()->isGet()) {
            $info = model('Config')->column('name,value');
            $info['logoUrl'] = model('Uploadfile')->getFileUrl($info['logo']);
            $info['logoUrl'] = $info['logoUrl']?$info['logoUrl']:make_file_url('resource/logo.png');
            $info['squarelogoUrl'] = model('Uploadfile')->getFileUrl($info['square_logo']);
            $info['squarelogoUrl'] = $info['squarelogoUrl']?$info['squarelogoUrl']:make_file_url('resource/square_logo.png');
            $info['qrcodeUrl'] = model('Uploadfile')->getFileUrl($info['wechat_qrcode']);
            $info['qrcodeUrl'] = $info['qrcodeUrl']?$info['qrcodeUrl']:make_file_url('resource/weixin_img.jpg');
            $info['infopicUrl'] = model('Uploadfile')->getFileUrl($info['wechat_info_img']);
            $info['infopicUrl'] = $info['infopicUrl']?$info['infopicUrl']:make_file_url('resource/wechat_info_img.jpg');
            $info['guide_qrcodeUrl'] = model('Uploadfile')->getFileUrl($info['guide_qrcode']);
            $info['guide_qrcodeUrl'] = $info['guide_qrcodeUrl']?$info['guide_qrcodeUrl']:($info['sitedomain'].$info['sitedir'].'apiadmin/qrcode/normal?url='.$info['mobile_domain']);
            foreach ($info as $key => $value) {
                $value = $value;
                if (is_json($value)) {
                    $info[$key] = json_decode($value, true);
                }
                if (
                    in_array($key, ['agreement', 'privacy', 'remittance_desc','wechat_welcome_text','statistics'])
                ) {
                    $info[$key] = htmlspecialchars_decode($value,ENT_QUOTES);
                }
            }

            /**
             * 【新增】
             * app资质公示增加字段
             * zch - 2022.09.14
             */
            $info['business_license_url'] = model('Uploadfile')->getFileUrl($info['qualification_publicity']['business_license_id']); //电子营业执照
            $info['service_license_url'] = model('Uploadfile')->getFileUrl($info['qualification_publicity']['service_license_id']); // 人力资源服务许可证
            $info['business_licenses_url'] = model('Uploadfile')->getFileUrl($info['qualification_publicity']['business_licenses_id']); // 增值电信业务经营许可证

            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $inputdata = input('post.');
            $configlist = model('Config')->column('name,id');
            $sqldata = [];
            foreach ($inputdata as $key => $value) {
                if (!isset($configlist[$key])) {
                    continue;
                }
                $arr['id'] = $configlist[$key];
                $arr['name'] = $key;
                if (is_array($value)) {
                    $arr['value'] = json_encode($value, JSON_UNESCAPED_UNICODE);
                } else {
                    $arr['value'] = $value;
                }
                $sqldata[] = $arr;
            }
            model('Config')
                ->isUpdate()
                ->saveAll($sqldata);
            $name_list = [];
            foreach ($sqldata as $key => $value) {
                $name_list[] = $value['name'];
            }
            model('AdminLog')->record(
                '修改配置信息。配置标识【' . implode(',', $name_list) . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存数据成功');
        }
    }
    public function clearCache()
    {
        rmdirs(RUNTIME_PATH . '/cache/');
        rmdirs(RUNTIME_PATH . '/log/');
        rmdirs(RUNTIME_PATH. '/temp/');
        rmdirs(SYS_UPLOAD_PATH . '/poster/');
        model('AdminLog')->record('清除缓存', $this->admininfo);
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
            $sqldata = [];
            foreach ($inputdata as $key => $value) {
                if (!$value['id']) {
                    continue;
                }
                $arr['id'] = $value['id'];
                $arr['alisms_tplcode'] = $value['alisms_tplcode'];
                $sqldata[] = $arr;
            }
            model('SmsTpl')
                ->isUpdate()
                ->saveAll($sqldata);
            model('AdminLog')->record('保存短信模板', $this->admininfo);
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
            $configlist = model('FieldRule')->column('id,field_name', 'id');
            $sqldata = [];
            foreach ($inputdata as $key => $value) {
                if (!isset($configlist[$value['id']])) {
                    continue;
                }
                $arr['id'] = $value['id'];
                $arr['field_cn'] = $value['field_cn'];
                $arr['is_require'] = $value['is_require'];
                $arr['is_display'] = $value['is_display'];
                $sqldata[] = $arr;
            }
            model('FieldRule')
                ->isUpdate()
                ->saveAll($sqldata);
            model('AdminLog')->record('保存自定义字段规则', $this->admininfo);
            $this->ajaxReturn(200, '保存数据成功');
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
            $configlist = model('ResumeModule')->column('id,module_name', 'id');
            $sqldata = [];
            foreach ($inputdata as $key => $value) {
                if (!isset($configlist[$value['id']])) {
                    continue;
                }
                $arr['id'] = $value['id'];
                $arr['is_display'] = $value['is_display'];
                $arr['score'] = $value['score'];
                $sqldata[] = $arr;
            }
            model('ResumeModule')
                ->isUpdate()
                ->saveAll($sqldata);
            model('AdminLog')->record('保存自定义简历模块', $this->admininfo);
            $this->ajaxReturn(200, '保存数据成功');
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
        model('AdminLog')->record('发送测试邮件', $this->admininfo);
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
        model('AdminLog')->record('发送测试短信', $this->admininfo);
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
            model('AdminLog')->record(
                '保存触屏端首页模块设计',
                $this->admininfo
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
        model('MobileIndexMenu')
            ->allowField(true)
            ->save($inputdata, ['id' => $inputdata['id']]);
        model('AdminLog')->record(
            '编辑触屏端首页导航。导航ID【' . $inputdata['id'] . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存数据成功');
    }
    public function setNotifyRule()
    {
        $inputdata = input('post.');
        $configlist = model('NotifyRule')->column('id,title', 'id');
        $sqldata = [];
        foreach ($inputdata as $key => $value) {
            if (!isset($configlist[$value['id']])) {
                continue;
            }
            $arr['id'] = $value['id'];
            $arr['open_message'] = $value['open_message'];
            $arr['open_sms'] = $value['open_sms'];
            $arr['open_email'] = $value['open_email'];
            $sqldata[] = $arr;
        }
        model('NotifyRule')
            ->isUpdate()
            ->saveAll($sqldata);
        model('AdminLog')->record('保存消息通知规则', $this->admininfo);
        $this->ajaxReturn(200, '保存数据成功');
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
        $configlist = model('WechatNotifyRule')->column('id,title', 'id');
        $sqldata = [];
        foreach ($inputdata as $key => $value) {
            if (!isset($configlist[$value['id']])) {
                continue;
            }
            $arr['id'] = $value['id'];
            $arr['is_open'] = $value['is_open'];
            $arr['tpl_id'] = $value['tpl_id'];
            $sqldata[] = $arr;
        }
        model('WechatNotifyRule')
            ->isUpdate()
            ->saveAll($sqldata);
        model('AdminLog')->record('保存微信模板消息通知规则', $this->admininfo);
        $this->ajaxReturn(200, '保存数据成功');
    }
    public function getWechatNotifyRule()
    {
        $utype = input('get.utype/d', 1, 'intval');
        $return = [];
        $list = model('WechatNotifyRule')->getCache();
        $list = $list[$utype];
        foreach ($list as $key => $value) {
            $return[] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
