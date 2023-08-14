<?php
namespace app\apiadmin\controller;

class ImRule extends \app\common\controller\Backend
{
    public function index()
    {
        if (request()->isGet()) {
            $utype = input('get.utype');
            $info = model('ImRule')->where(array('utype'=>$utype))->column('name,value');
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $inputdata = input('post.');
            if (isset($inputdata['unread_reminder']) && $inputdata['unread_reminder'] == 1)
            {
                if (config('global_config.sendsms_type') == 'qscms')
                {
                    $account_sms = config('global_config.account_sms');
                    if (empty($account_sms) || !isset($account_sms['app_key']) || !isset($account_sms['secret_key']) || empty($account_sms['app_key']) || empty($account_sms['secret_key']))
                    {
                        $this->ajaxReturn(200, '未设置短信配置', ['sms_setting' => 0]);
                    }
                }else
                {
                    $account_alisms = config('global_config.account_alisms');

                    if (empty($account_alisms) || !isset($account_alisms['signature']) || empty($account_alisms['signature']) || !isset($account_alisms['accesskey_id']) || empty($account_alisms['accesskey_id']) || !isset($account_alisms['accesskey_secret']) || empty($account_alisms['accesskey_secret']))
                    {
                        $this->ajaxReturn(200, '未设置短信配置', ['sms_setting' => 0]);
                    }
                }

            }
			$utype = input('post.utype');
            $configlist = model('ImRule')->where(array('utype'=>$utype))->column('name,id');
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
            model('ImRule')
                ->isUpdate()
                ->saveAll($sqldata);
            $name_list = [];
            foreach ($sqldata as $key => $value) {
                $name_list[] = $value['name'];
            }
            model('AdminLog')->record(
                '修改即时通讯配置信息。配置标识【' . implode(',', $name_list) . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存数据成功');
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
        model('AdminLog')->record(
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
        model('AdminLog')->record(
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
        model('AdminLog')->record(
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
