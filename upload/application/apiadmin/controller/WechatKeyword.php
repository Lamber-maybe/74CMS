<?php
namespace app\apiadmin\controller;
class WechatKeyword extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('WechatKeyword')
            ->where($where)
            ->count();
        $list = model('WechatKeyword')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        /**
         *【ID1000595】
         * 【优化】公众号关键词回复链接
         * yx - 2023.03.20
         * [新增]:
         */
        foreach ($list as &$keyword) {
            $keyword['return_link'] = htmlspecialchars_decode($keyword['return_link']);
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function add()
    {
        $input_data = [
            'word' => input('post.word/s', '', 'trim'),
            'return_text' => input('post.return_text/s', '', 'trim'),
            'return_img' => input('post.return_img/s', '', 'trim'),
            'return_img_mediaid' => input('post.return_img_mediaid/s', '', 'trim'),
            'return_link' => input('post.return_link/s', '', 'trim')
        ];
        if (
            false ===
            model('WechatKeyword')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('WechatKeyword')->getError());
        }
        model('AdminLog')->record(
            '添加微信公众号自定义关键词。关键词【' .
                $input_data['word'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('WechatKeyword')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $returnImgUrl = model('Uploadfile')->getFileUrl($info['return_img']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'returnImgUrl' => $returnImgUrl
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'word' => input('post.word/s', '', 'trim'),
                'return_text' => input('post.return_text/s', '', 'trim'),
                'return_img' => input('post.return_img/s', '', 'trim'),
                'return_img_mediaid' => input('post.return_img_mediaid/s', '', 'trim'),
                'return_link' => input('post.return_link/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('WechatKeyword')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('WechatKeyword')->getError());
            }
            model('AdminLog')->record(
                '添加微信公众号自定义关键词。关键词【' .
                    $input_data['word'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $list = model('WechatKeyword')
            ->where('id', 'in', $id)
            ->column('word');
        model('WechatKeyword')->destroy($id);
        model('AdminLog')->record(
            '删除微信公众号自定义关键词。关键词【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
