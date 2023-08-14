<?php

namespace app\apiadmin\controller\b2burm;

use app\common\controller\Backend;
use think\Db;
use think\Validate;

class FollowUp extends Backend
{
    /*
     * 跟进记录列表
     * */
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $uid = input('get.uid/d', 0, 'intval');
        if ($uid == 0) {
            $this->ajaxReturn(200, '缺少参数线索id或uid', []);
        }
        $where = [];
        if ($uid > 0) {
            $where['a.uid'] = $uid;
        }
        $field = 'a.mode,a.result,a.enclosure,a.admin_id,a.create_time,b.username,b.avatar,a.link_man';
        $list = model('b2bcrm.CrmFollowUp')
            ->getList($where, ['a.create_time DESC'], $field, $current_page, $pagesize);
        if (empty($list)) {
            $this->ajaxReturn(200, '获取成功', []);
        }
        foreach ($list['rows'] as $k => $v) {
            $list['rows'][$k]['mode'] = isset(model('b2bcrm.CrmFollowUp')->method[$v['mode']]) ? model('b2bcrm.CrmFollowUp')->method[$v['mode']] : '';
        }
        $return['items'] = $list['rows'];
        $return['total'] = $list['pages']['record_num'];
        $return['pages'] = $list['pages'];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /*
     * 添加跟进记录
     * */
    public function addVisit()
    {
        try {
            $data = [
                'uid' => input('post.uid/d', 0, 'intval'),
                'admin_id' => $this->admininfo->id,
                'mode' => input('post.mode/d', 0, 'intval'),
                'next_time' => input('post.next_time/d', 0, 'intval'),
                'result' => input('post.result/s', '', 'trim'),
                'enclosure' => input('post.enclosure/s', '', 'trim'),
                'create_time' => time(),
            ];

            $rule = [
                'uid' => 'number|length:1,10',
                'mode' => 'require|max:1|number',
                'result' => 'max:100',
                'next_time' => 'number|length:1,10',
            ];

            $msg = [
                'mode' => '请选择跟进方式',
                'result' => '请输入1~100字内的跟进结果',
                'next_time' => '请选择下次跟进时间',
            ];
            $validate = new Validate($rule, $msg);
            if (!$validate->check($data)) {
                $this->ajaxReturn(500, $validate->getError());
            }
            Db::startTrans();
            if ($data['uid'] > 0) {
                model('Resume')
                    ->isUpdate(true)
                    ->save(['last_visit_time' => time(), 'last_visit_admin' => $this->admininfo->id], ['uid' => $data['uid']]);
            }
            $resume = model('Resume')->where('uid', $data['uid'])->find();
            $data['clue_id'] = 0;
            $data['link_mobile'] = model('ResumeContact')->where('uid', $data['uid'])->value('mobile');
            $data['type'] = 3;
            $data['utype'] = 2;
            $data['relation_id'] = $resume['id'];
            $data['link_man'] = $resume['fullname'];
            model('b2bcrm.CrmFollowUp')->saveData($data, $this->admininfo);

            Db::commit();
            $this->ajaxReturn(200, '保存成功');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, $e->getMessage());
        }
    }
}