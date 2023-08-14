<?php
namespace app\apiadmin\controller;

class SmsBlacklist extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }

        $total = model('SmsBlacklist')->where($where)->count();
        $list = model('SmsBlacklist')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

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
            'mobile' => input('post.mobile/s', '', 'trim'),
            'note' => input('post.note/s', '', 'trim'),
            'addtime'=>time()
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|max:60|checkMobile'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                $info = model('SmsBlacklist')
                    ->where([
                        'mobile' => $value
                    ])
                    ->find();
                if (null === $info) {
                    return true;
                } else {
                    return '手机号已存在黑名单中';
                }
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        if (
            false ===
            model('SmsBlacklist')->save($input_data)
        ) {
            $this->ajaxReturn(500, model('SmsBlacklist')->getError());
        }
        model('AdminLog')->record(
            '添加黑名单手机号。手机号【' .
                $input_data['mobile'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('SmsBlacklist')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'mobile' => input('post.mobile/s', '', 'trim'),
                'note' => input('post.note/s', '', 'trim'),
                'addtime'=>time()
            ];
            $validate = new \think\Validate([
                'mobile' => 'require|max:60|checkMobile'
            ]);
            $validate->extend('checkMobile', function ($value) use ($input_data) {
                if (fieldRegex($value, 'mobile')) {
                    $info = model('SmsBlacklist')
                        ->where([
                            'mobile' => $value
                        ])
                        ->where('id','neq',$input_data['id'])
                        ->find();
                    if (null === $info) {
                        return true;
                    } else {
                        return '手机号已存在黑名单中';
                    }
                } else {
                    return '请输入正确的手机号码';
                }
            });
            if (!$validate->check($input_data)) {
                $this->ajaxReturn(500, $validate->getError());
            }
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('SmsBlacklist')
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('SmsBlacklist')->getError());
            }
            model('AdminLog')->record(
                '编辑黑名单手机号。手机号【' .
                    $input_data['mobile'] .
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
        $list = model('SmsBlacklist')
            ->where('id', 'in', $id)
            ->column('mobile');
        model('SmsBlacklist')->destroy($id);
        model('AdminLog')->record(
            '删除黑名单手机号。手机号【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
