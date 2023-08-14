<?php
namespace app\apiadmin\controller;

class Market extends \app\common\controller\Backend
{
    public function tplList()
    {
        $list = model('MarketTpl')
            ->order('id asc')
            ->select();
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function tplAdd()
    {
        $input_data = input('post.');
        if (
            false ===
            model('MarketTpl')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('MarketTpl')->getError());
        }
        model('AdminLog')->record(
            '添加营销模板。模板ID【' .
                model('MarketTpl')->id .
                '】;模板名称【' .
                $input_data['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function tplEdit()
    {
        $input_data = input('post.');
        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        if (
            false ===
            model('MarketTpl')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id])
        ) {
            $this->ajaxReturn(500, model('MarketTpl')->getError());
        }
        model('AdminLog')->record(
            '编辑营销模板。模板ID【' .
                $id .
                '】;模板名称【' .
                $input_data['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function tplDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('MarketTpl')
            ->where('id', 'eq', $id)
            ->find();
        model('MarketTpl')
            ->where('id', $id)
            ->delete();
        model('AdminLog')->record(
            '删除营销模板。模板ID【' .
                $id .
                '】;模板名称【' .
                $info['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function taskList()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('MarketTask')->count();
        $list = model('MarketTask')
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
    public function taskAdd()
    {
        $input_data = [
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim'),
            'send_type' => input('post.send_type/a', []),
            'target' => input('post.target/s', '', 'trim'),
            'condition' => input('post.condition/a', []),
            'total' => 0,
            'success' => 0,
            'status' => 0,
            'addtime' => time()
        ];
        $input_data['send_type'] = implode(',', $input_data['send_type']);
        $input_data['total'] = model('MarketTask')->countTotal(
            $input_data['target'],
            $input_data['condition']
        );
        $input_data['condition'] = json_encode(
            $input_data['condition'],
            JSON_UNESCAPED_UNICODE
        );

        if (
            false ===
            model('MarketTask')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('MarketTask')->getError());
        }
        model('MarketTask')->recordQueue(model('MarketTask')->id);
        model('AdminLog')->record(
            '添加营销任务。任务ID【' .
                model('MarketTask')->id .
                '】;任务名称【' .
                $input_data['title'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '添加成功');
    }
    public function taskRun()
    {
        $id = input('post.id/d', 0, 'intval');
        $current_page = input('post.page/d', 1, 'intval');
        $pagesize = input('post.pagesize/d', 10, 'intval');
        $info = model('MarketTask')->find($id);
        $total = model('MarketQueue')
            ->where('task_id', $id)
            ->count();
        $list = model('MarketQueue')
            ->where('task_id', $id)
            ->order('id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        if (empty($list)) {
            if ($current_page == 1) {
                $this->ajaxReturn(500, '没有找到符合条件的任务');
            } else {
                model('MarketQueue')
                    ->where('task_id', 'eq', $id)
                    ->delete();
                $this->ajaxReturn(200, '任务执行完成', ['continue' => 0]);
            }
        }
        $count = 0;
        $mail_to_arr = $sms_to_arr = $message_to_arr = [];
        foreach ($list as $key => $value) {
            $value['message'] == 1 && ($message_to_arr[] = $value['uid']);
            if ($value['mobile']) {
                $sms_to_arr[] = $value['mobile'];
            }
            if ($value['email']) {
                $mail_to_arr[] = $value['email'];
            }
            $count++;
        }
        if (!empty($message_to_arr)) {
            $message_to_arr = array_unique($message_to_arr);
            model('Message')->sendMessage(
                $message_to_arr,
                $info['content'],
                \app\common\model\Message::TYPE_NOTICE
            );
        }
        if (!empty($sms_to_arr)) {
            $sms_to_arr = array_unique($sms_to_arr);
            $instance = new \app\common\lib\sms\qscms();
            $instance->sendDirect($sms_to_arr, $info['content']);
        }
        if (!empty($mail_to_arr)) {
            $mail_to_arr = array_unique($mail_to_arr);
            $instance = new \app\common\lib\Mail();
            $instance->send($mail_to_arr, $info['title'], $info['content']);
        }
        if ($count > 0) {
            $info->success = $info->success + $count;
            $info->status = 1;
            $info->save();
            model('AdminLog')->record(
                '成功执行营销任务【' . $count . '条】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '执行任务成功', [
                'continue' => 1,
                'total' => $total,
                'success' => $count
            ]);
        } else {
            model('MarketQueue')
                ->where('task_id', 'eq', $id)
                ->delete();
            $this->ajaxReturn(200, '任务执行完成', ['continue' => 0]);
        }
    }
    public function taskDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('MarketTask')
            ->where('id', 'eq', $id)
            ->find();
        model('MarketTask')
            ->where('id', $id)
            ->delete();
        model('MarketQueue')
            ->where('task_id', $id)
            ->delete();
        model('AdminLog')->record(
            '删除营销任务。任务ID【' .
                $id .
                '】;任务名称【' .
                $info['title'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function searchMember()
    {
        $keyword = input('get.keyword/s', '', 'trim');
        $list = [];
        if ($keyword != '') {
            $datalist = model('Member')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'company b',
                    'a.uid=b.uid',
                    'LEFT'
                )
                ->where('a.uid', 'eq', $keyword)
                ->whereOr('a.mobile', 'like', '%' . $keyword . '%')
                ->whereOr('b.companyname', 'like', '%' . $keyword . '%')
                ->field('a.uid,a.mobile,a.utype,b.companyname')
                ->select();
            foreach ($datalist as $key => $value) {
                $arr['uid'] = $value['uid'];
                $arr['mobile'] = $value['mobile'];
                $arr['utype'] = $value['utype'] == 1 ? '企业' : '个人';
                $arr['companyname'] =
                    $value['utype'] == 1 ? $value['companyname'] : '';
                $list[] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
}
