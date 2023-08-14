<?php
namespace app\apiadmin\controller;

class PromotionJob extends \app\common\controller\Backend
{
    public function index()
    {
        $where['a.utype'] = 1;
        $type = input('get.type/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $sort = input('get.sort/d', 0, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['b.jobname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['b.id'] = ['eq', $keyword];
                    break;
                case 4:
                    $where['b.company_id'] = ['eq', $keyword];
                    break;
                case 5:
                    $where['b.uid'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($type != '') {
            $where['a.type'] = ['eq', $type];
        }
        if ($settr) {
            $where['a.deadline'] = ['lt', strtotime('+' . $settr . ' day')];
        }
        $order = 'a.addtime desc';
        if($sort>0){
            $order = 'a.deadline asc';
        }

        $total = model('ServiceQueue')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.pid=b.id', 'LEFT')
            ->join(
                config('database.prefix') . 'company c',
                'b.company_id=c.id',
                'LEFT'
            )
            ->where('b.id','not null')
            ->where($where)
            ->count();
        $list = model('ServiceQueue')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.pid=b.id', 'LEFT')
            ->join(
                config('database.prefix') . 'company c',
                'b.company_id=c.id',
                'LEFT'
            )
            ->field('a.*,b.uid,b.jobname,c.companyname')
            ->where($where)
            ->where('b.id','not null')
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['days'] = ($value['deadline'] - $value['addtime'])/3600/24;
            $list[$key]['days'] = ceil($list[$key]['days']);
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function searchCompany()
    {
        $keyword = input('get.keyword/s', '', 'trim');
        if ($keyword != '') {
            $list = model('Company')
                ->where('id', 'eq', $keyword)
                ->whereOr('companyname', 'like', '%' . $keyword . '%')
                ->column('id,uid,companyname');
        } else {
            $list = [];
        }

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function searchJob()
    {
        $company_id = input('get.company_id/d', 0, 'intval');
        if ($company_id > 0) {
            $list = model('Job')
                ->where('company_id', 'eq', $company_id)
                ->column('id,uid,jobname');
        } else {
            $list = [];
        }
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function add()
    {
        $input_data = [
            'pid' => input('post.pid/s', '', 'trim'),
            'days' => input('post.days/d', 0, 'intval'),
            'type' => input('post.type/s', '', 'trim')
        ];
        $rule = [
            'pid' => 'require',
            'days' => 'require',
            'type' => 'require'
        ];
        $msg = [
            'pid.require' => '请选择职位',
            'days.require' => '请输入推广天数',
            'type.require' => '请选择推广类型'
        ];
        $validate = new \think\Validate($rule, $msg);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $check_has = model('ServiceQueue')
            ->where('pid', 'eq', intval($input_data['pid']))
            ->where('type', $input_data['type'])
            ->find();
        if ($check_has !== null) {
            $this->ajaxReturn(500, '该职位已经在推广状态，不能重复推广');
        }
        $data['type'] = $input_data['type'];
        $data['utype'] = 1;
        $data['pid'] = $input_data['pid'];
        $data['addtime'] = time();
        $data['deadline'] = strtotime('+' . $input_data['days'] . ' day');
        if (
            false ===
            model('ServiceQueue')
                ->allowField(true)
                ->save($data)
        ) {
            $this->ajaxReturn(500, model('ServiceQueue')->getError());
        }
        if ($data['type'] == 'jobstick') {
            model('Job')
                ->where('id', 'eq', $data['pid'])
                ->setField('stick', 1);
            model('JobSearchRtime')
                ->where('id', 'eq', $data['pid'])
                ->setField('stick', 1);
            model('JobSearchKey')
                ->where('id', 'eq', $data['pid'])
                ->setField('stick', 1);
        } elseif ($data['type'] == 'emergency') {
            model('Job')
                ->where('id', 'eq', $data['pid'])
                ->setField('emergency', 1);
            model('JobSearchRtime')
                ->where('id', 'eq', $data['pid'])
                ->setField('emergency', 1);
            model('JobSearchKey')
                ->where('id', 'eq', $data['pid'])
                ->setField('emergency', 1);
        }

        model('AdminLog')->record(
            '添加企业推广。职位ID【' .
                $data['pid'] .
                '】；推广类型【' .
                ($data['type'] == 'jobstick' ? '置顶' : '紧急') .
                '】；推广天数【' .
                $input_data['days'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'days' => input('post.days/d', 0, 'intval')
        ];
        $rule = [
            'id' => 'require',
            'days' => 'require'
        ];
        $msg = [
            'id.require' => '请选择推广记录',
            'days.require' => '请输入延长推广天数'
        ];
        $validate = new \think\Validate($rule, $msg);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $info = model('ServiceQueue')
            ->where('id', $input_data['id'])
            ->find();
        $info->deadline = strtotime(
            '+' . $input_data['days'] . ' day',
            $info->deadline
        );
        if (false === $info->save()) {
            $this->ajaxReturn(500, model('ServiceQueue')->getError());
        }
        model('AdminLog')->record(
            '编辑企业推广。推广记录ID【' .
                $input_data['id'] .
                '】；延长推广天数【' .
                $input_data['days'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function cancel()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('ServiceQueue')
            ->where('id', 'eq', $id)
            ->find();
        if ($info['type'] == 'jobstick') {
            model('Job')
                ->where('id', 'eq', $info['pid'])
                ->setField('stick', 0);
            model('JobSearchRtime')
                ->where('id', 'eq', $info['pid'])
                ->setField('stick', 0);
            model('JobSearchKey')
                ->where('id', 'eq', $info['pid'])
                ->setField('stick', 0);
        } elseif ($info['type'] == 'emergency') {
            model('Job')
                ->where('id', 'eq', $info['pid'])
                ->setField('emergency', 0);
            model('JobSearchRtime')
                ->where('id', 'eq', $info['pid'])
                ->setField('emergency', 0);
            model('JobSearchKey')
                ->where('id', 'eq', $info['pid'])
                ->setField('emergency', 0);
        }
        $info->delete();
        model('AdminLog')->record(
            '取消企业推广。推广ID【' . $id . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '取消成功');
    }
}
