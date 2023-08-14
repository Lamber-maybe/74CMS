<?php

namespace app\apiadmin\controller;

class ImQuickmsg extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();

        $this->baseUrl = 'https://imserv.v2.74cms.com';
        $this->config = config('global_config.account_im');
        $this->im_open = config('global_config.im_open');
        if ($this->im_open != '1') {
            $this->ajaxReturn(200, '职聊功能已关闭', null);
        }
        if (empty($this->config['app_key'])) {
            $this->ajaxReturn(200, 'app_key不能为空', null);
        }
        if (empty($this->config['app_secret'])) {
            $this->ajaxReturn(200, 'app_secret不能为空', null);
        }
    }

    public function index()
    {
        $utype = input('get.utype/d', 1, 'intval');
        $url = $this->baseUrl . '/phraseTpl/list';
        $data = [
            'utype' => $utype,
            'appkey' => $this->config['app_key'],
            'appsecret' => $this->config['app_secret']
        ];
        $result = https_request($url, $data);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg'], null);
        } else {
            $this->ajaxReturn(200, $result['msg'], $result['result']);
        }
    }

    public function add()
    {
        $url = $this->baseUrl . '/phraseTpl/addSave';
        $data = [
            'utype' => input('post.utype/d', 0, 'intval'),
            'appkey' => $this->config['app_key'],
            'appsecret' => $this->config['app_secret'],
            'content' => input('post.content/s', '', 'trim')
        ];
        $result = https_request($url, $data);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg'], null);
        } else {
            $utype = $data['utype'] == 1 ? '企业' : '个人';
            model('AdminLog')->writeLog(
                '添加' . $utype . '常用语，"' . $data['content'] . '"',
                $this->admininfo,
                0,
                2
            );
            $this->ajaxReturn(200, '保存成功', $result['result']);
        }
    }

    public function edit()
    {
        $id = input('get.id/s', '', 'trim');
        if ($id) {
            $url = $this->baseUrl . '/phraseTpl/edit';
            $data = [
                'id' => $id,
                'appkey' => $this->config['app_key'],
                'appsecret' => $this->config['app_secret']
            ];
            $result = https_request($url, $data);
            $result = json_decode($result, 1);
            if ($result['code'] == 500) {
                $this->ajaxReturn(500, $result['msg'], null);
            } else {
                $this->ajaxReturn(200, $result['msg'], $result['result']);
            }
        } else {
            $input_data = [
                'id' => input('post.id/s', '', 'trim'),
                'content' => input('post.content/s', '', 'trim')
            ];
            $id = trim($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            /**
             * 通过ID获取常用语详情
             */
            $details_url = $this->baseUrl . '/phraseTpl/edit';
            $details_data = [
                'id' => $id,
                'appkey' => $this->config['app_key'],
                'appsecret' => $this->config['app_secret']
            ];
            $details_result = https_request($details_url, $details_data);
            $details_result = json_decode($details_result, 1);
            if ($details_result['code'] == 500) {
                $this->ajaxReturn(500, '要修改的常用语信息异常', ['err_msg' => $details_result['msg']]);
            }

            $url = $this->baseUrl . '/phraseTpl/editSave';
            $data = [
                'id' => $id,
                'appkey' => $this->config['app_key'],
                'appsecret' => $this->config['app_secret'],
                'content' => $input_data['content']
            ];
            $result = https_request($url, $data);
            $result = json_decode($result, 1);
            if ($result['code'] == 500) {
                $this->ajaxReturn(500, $result['msg'], null);
            } else {
                $utype = $details_result['result']['utype'] == 1 ? '企业' : '个人';
                model('AdminLog')->writeLog(
                    '修改' . $utype . '常用语，"' . $details_result['result']['content'] . '" -> "' . $data['content'] . '"',
                    $this->admininfo,
                    0,
                    3
                );
                $this->ajaxReturn(200, '保存成功', $result['result']);
            }
        }
    }

    public function delete()
    {
        $id = input('post.id/s', '', 'trim');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        /**
         * 通过ID获取常用语详情
         */
        $details_url = $this->baseUrl . '/phraseTpl/edit';
        $details_data = [
            'id' => $id,
            'appkey' => $this->config['app_key'],
            'appsecret' => $this->config['app_secret']
        ];
        $details_result = https_request($details_url, $details_data);
        $details_result = json_decode($details_result, 1);
        if ($details_result['code'] == 500) {
            $this->ajaxReturn(500, '要删除的常用语信息异常', ['err_msg' => $details_result['msg']]);
        }

        $url = $this->baseUrl . '/phraseTpl/delete';
        $data = [
            'id' => $id,
            'appkey' => $this->config['app_key'],
            'appsecret' => $this->config['app_secret']
        ];
        $result = https_request($url, $data);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, '删除失败', null);
        } else {
            $utype = $details_result['result']['utype'] == 1 ? '企业' : '个人';
            model('AdminLog')->writeLog(
                '删除' . $utype . '常用语，"' . $details_result['result']['content'] . '"',
                $this->admininfo,
                0,
                4
            );
            $this->ajaxReturn(200, '删除成功');
        }
    }
}
