<?php

namespace app\apiadmin\controller\zhitoo;

use app\common\controller\Backend;
use app\common\lib\zhitoo\DocumentView;
use think\Db;
use think\Validate;

class Document extends Backend
{
    public function config()
    {
        if (request()->isGet()) {
            // [GET]请求 - 获取在线文档预览配置项
            $config = model('Config')
                ->where('name', 'account_zhitoo_document')
                ->value('value');
            if (isset($config) && !empty($config)) {
                $config = json_decode($config, true);
            } else {
                $this->ajaxReturn(200, '缺少内置配置项', $config);
            }
            $this->ajaxReturn(200, '获取数据成功', $config);

        } elseif (request()->isPost()) {
            // [POST]请求 - 配置在线文档预览配置项

            $data = [
                'appKey' => input('post.appKey/s', '', 'trim'),
                'appSecret' => input('post.appSecret/s', '', 'trim')
            ];

            $rule = [
                'appKey' => 'require|length:8',
                'appSecret' => 'require|length:32'
            ];

            $msg = [
                'appKey' => '请输入8位AppKey',
                'appSecret' => '请输入32位AppSecret'
            ];

            if (!empty($data['appKey']) || !empty($data['appSecret'])) {
                $validate = new Validate($rule, $msg);
                if (!$validate->check($data)) {
                    $this->ajaxReturn(500, $validate->getError());
                }
            }

            Db::startTrans();
            try {
                $save_config = model('Config')
                    ->isUpdate(true)
                    ->allowField(true)
                    ->save(
                        ['value' => json_encode($data)],
                        ['name' => 'account_zhitoo_document']
                    );
                if (false === $save_config) {
                    throw new \Exception(model('Config')->getError());
                }

                model('AdminLog')->record(
                    '修改配置信息。配置标识【account_zhitoo_document】',
                    $this->admininfo
                );

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
        $documentView = new DocumentView(false);
        $check = $documentView->check();

        if (false === $check) {
            $return = [
                'is_check' => 0,
                'deadline' => 0
            ];
            $this->ajaxReturn(200, $documentView->getError(), $return);
        } else {
            $this->ajaxReturn(200, 'SUCCESS', $check);

        }
    }
}