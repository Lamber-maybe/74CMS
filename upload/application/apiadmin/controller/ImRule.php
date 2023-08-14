<?php

namespace app\apiadmin\controller;

use think\Db;

class ImRule extends \app\common\controller\Backend
{
    public function index()
    {
        if (request()->isGet()) {
            $utype = input('get.utype');
            $info = model('ImRule')->where(array('utype' => $utype))->column('name,value');
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $inputdata = input('post.');
            $uType = input('post.utype');

            try {
                $ruleNames = array_keys($inputdata);
                $configList = model('ImRule')
                    ->whereIn('name', $ruleNames)
                    ->where('utype', $uType)
                    ->column('*', 'name');
                if (null === $configList) {
                    $this->ajaxReturn(500, '没有要修改的基础配置项');
                }

                switch ($uType) {
                    case 0:
                        $log_field = '修改即时通讯未读消息提醒配置，';
                        break;

                    case 1:
                        $log_field = '修改即时通讯企业配置，';
                        break;

                    case 2:
                        $log_field = '修改即时通讯个人配置，';
                        break;

                    default:
                        $this->ajaxReturn(500, '基础配置项分类异常');
                        break;
                }

                if (isset($inputdata['unread_reminder']) && $inputdata['unread_reminder'] == 1) {
                    if (config('global_config.sendsms_type') == 'qscms') {
                        $account_sms = config('global_config.account_sms');
                        if (empty($account_sms) || !isset($account_sms['app_key']) || !isset($account_sms['secret_key']) || empty($account_sms['app_key']) || empty($account_sms['secret_key'])) {
                            $this->ajaxReturn(200, '未设置短信配置', ['sms_setting' => 0]);
                        }
                    } else {
                        $account_alisms = config('global_config.account_alisms');

                        if (empty($account_alisms) || !isset($account_alisms['signature']) || empty($account_alisms['signature']) || !isset($account_alisms['accesskey_id']) || empty($account_alisms['accesskey_id']) || !isset($account_alisms['accesskey_secret']) || empty($account_alisms['accesskey_secret'])) {
                            $this->ajaxReturn(200, '未设置短信配置', ['sms_setting' => 0]);
                        }
                    }

                }

                $sql_arr = [];
                foreach ($inputdata as $key => $value) {
                    if (!isset($configList[$key]) || empty($configList[$key])) {
                        continue;
                    }
                    $config = $configList[$key];

                    if (is_array($value)) {
                        $config_value = json_encode($value, JSON_UNESCAPED_UNICODE);
                    } else {
                        $config_value = $value;
                    }

                    if ($config_value != $config['value']) {
                        switch ($key) {
                            case 'bind_weixin':
                                $log_field .= '强制关注公众号:' .
                                    model('ImRule')->map_is_open[$config['value']]
                                    .
                                    '->'
                                    . model('ImRule')->map_is_open[$config_value]
                                    . '；';
                                break;

                            case 'complete_percent':
                                $log_field .= '个人聊天简历完整度限制:' . $config['value'] . '%->' . $config_value . '%；';
                                break;

                            case 'audit_status':
                                $log_field .= '简历状态限制:' .
                                    model('ImRule')->map_audit_status[$config['value']]
                                    .
                                    '->'
                                    . model('ImRule')->map_audit_status[$config_value]
                                    . '；';
                                break;

                            case 'max_per_day':
                                $log_field .= '个人发起次数:' . $config['value'] . '->' . $config_value . '次/天；';
                                break;

                            case 'display_status':
                                $log_field .= '企业显示状态限制:' .
                                    model('ImRule')->map_display_status[$config['value']]
                                    .
                                    '->'
                                    . model('ImRule')->map_display_status[$config_value]
                                    . '；';
                                break;

                            case 'unread_reminder':
                                $log_field .= '在线聊未读消息短信通知:' .
                                    model('ImRule')->map_is_open[$config['value']]
                                    .
                                    '->'
                                    . model('ImRule')->map_is_open[$config_value]
                                    . '；';
                                break;

                            case 'unread_templateid':
                                $log_field .= '微信模板消息通知ID:' . $config['value'] . '->' . $config_value . '；';
                                break;

                            default:
                                $this->ajaxReturn(500, '错误的基础配置项');
                                break;
                        }
                        $arr['id'] = $config['id'];
                        $arr['name'] = $config['name'];
                        $arr['value'] = $config_value;
                        $sql_arr[] = $arr;
                    }
                }

                // 开启事务
                Db::startTrans();

                if (!empty($sql_arr)) {
                    if (
                        false ===
                        model('ImRule')
                            ->isUpdate()
                            ->saveAll($sql_arr)
                    ) {
                        throw new \Exception(model('ImRule')->getError());
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
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getLine()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function add()
    {
        $input_data = [
            'content' => input('post.content/s', '', 'trim'),
            'utype' => input('post.utype/d', 1, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        if (
            false ===
            model('ImQuickmsg')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('ImQuickmsg')->getError());
        }
        model('AdminLog')->checkLog(
            '添加即时通讯快捷语。快捷语ID【' .
            model('ImQuickmsg')->id .
            '】;快捷语内容【' .
            $input_data['content'] .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'content' => input('post.content/s', '', 'trim'),
            'utype' => input('post.utype/d', 1, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        if (
            false ===
            model('ImQuickmsg')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id])
        ) {
            $this->ajaxReturn(500, model('ImQuickmsg')->getError());
        }
        model('AdminLog')->checkLog(
            '编辑即时通讯快捷语。快捷语ID【' .
            $id .
            '】;快捷语内容【' .
            $input_data['content'] .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }

    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }

        $info = model('ImQuickmsg')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info->delete();
        model('AdminLog')->checkLog(
            '删除即时通讯快捷语。快捷语ID【' .
            $id .
            '】;快捷语内容【' .
            $info['content'] .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
