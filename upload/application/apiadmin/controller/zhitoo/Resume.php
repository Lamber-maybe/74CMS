<?php

namespace app\apiadmin\controller\zhitoo;

use app\common\controller\Backend;
use app\common\lib\zhitoo\ResumeAnalysis;
use think\Db;
use think\Validate;

class Resume extends Backend
{
    /**
     * 启用简历解析
     * @var string[] 启用简历解析 0:关闭|1:开启
     */
    public $map_is_open = [
        0 => '关闭',
        1 => '开启'
    ];

    public function config()
    {
        if (request()->isGet()) {
            // [GET]请求 - 获取简历解析配置项
            $config = model('Config')
                ->where('name', 'account_zhitoo_resume')
                ->value('value');
            if (isset($config) && !empty($config)) {
                $config = json_decode($config, true);
            } else {
                $this->ajaxReturn(200, '缺少内置配置项', $config);
            }
            $this->ajaxReturn(200, '获取数据成功', $config);

        } elseif (request()->isPost()) {
            // [POST]请求 - 配置简历解析配置项

            $data = [
                'is_open' => input('post.is_open/d', 0, 'intval'),
                'appKey' => input('post.appKey/s', '', 'trim'),
                'appSecret' => input('post.appSecret/s', '', 'trim')
            ];

            if ($data['is_open'] === 1) {
                $rule = [
                    'is_open' => 'require|eq:1',
                    'appKey' => 'require|length:8',
                    'appSecret' => 'require|length:32'
                ];
            } else {
                $rule = [
                    'is_open' => 'require|eq:0',
                ];
            }

            $msg = [
                'is_open' => '简历解析开启状态异常',
                'appKey' => '请输入8位AppKey',
                'appSecret' => '请输入32位AppSecret'
            ];
            $validate = new Validate($rule, $msg);
            if (!$validate->check($data)) {
                $this->ajaxReturn(500, $validate->getError());
            }

            try {
                $configInfo = model('Config')
                    ->where('name', 'account_zhitoo_resume')
                    ->find();
                if (null === $configInfo) {
                    $this->ajaxReturn(500, '在线文档预览配置项不存在');
                }
                $configValue = json_decode($configInfo['value'], true);

                Db::startTrans();

                $save_config = model('Config')
                    ->isUpdate(true)
                    ->allowField(true)
                    ->save(
                        ['value' => json_encode($data)],
                        ['name' => 'account_zhitoo_resume']
                    );
                if (false === $save_config) {
                    throw new \Exception(model('Config')->getError());
                }

                $log_field = '系统-基础配置-合作账号，修改简历解析配置';
                if ($data['is_open'] != $configValue['is_open']) {
                    $log_field .= '，启用简历解析:'
                        . $this->map_is_open[$configValue['is_open']]
                        . '->'
                        . $this->map_is_open[$data['is_open']];
                }
                if ($data['appKey'] != $configValue['appKey']) {
                    $log_field .= '，AppKey:' . $configValue['appKey'] . '->' . $data['appKey'];
                }
                if ($data['appSecret'] != $configValue['appSecret']) {
                    $log_field .= '，AppSecret:' . $configValue['appSecret'] . '->' . $data['appSecret'];
                }
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
                $this->ajaxReturn(200, '保存成功');
            } catch (\Exception $e) {
                Db::rollback();
                $this->ajaxReturn(500, $e->getMessage());
            }
        } else {
            $this->ajaxReturn(200, '错误的请求');
        }
    }

    public function check()
    {
        $resumeAnalysis = new ResumeAnalysis(false);
        $check = $resumeAnalysis->check();

        if (false === $check) {
            $return = [
                'is_check' => 0,
                'total' => 0,
                'list' => []
            ];
            $this->ajaxReturn(200, $resumeAnalysis->getError(), $return);
        } else {
            $this->ajaxReturn(200, 'SUCCESS', $check);

        }
    }
}