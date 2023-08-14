<?php

namespace app\apiadmin\controller\b2bcrm;

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
        $clue_id = input('get.clue_id/d', 0, 'intval');
        $uid = input('get.uid/d', 0, 'intval');
        if ($clue_id == 0 && $uid == 0) {
            $this->ajaxReturn(200, '缺少参数线索id或uid', []);
        }
        $where = [];
        if ($uid > 0) {
            $where['a.uid'] = $uid;
        }
        if ($clue_id > 0) {
            $where['a.clue_id'] = $clue_id;
        }
        $field = 'a.mode,a.result,a.enclosure,a.admin_id,a.create_time,b.username,b.avatar,a.link_man as name,a.uid';
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
                'type' => input('post.type/d', 1, 'intval'),
                'clue_id' => input('post.clue_id/d', 0, 'intval'),
                'uid' => input('post.uid/d', 0, 'intval'),
                'relation_id' => input('post.relation_id/d', 0, 'intval'),
                'admin_id' => $this->admininfo->id,
                'utype' => input('post.utype/d', 0, 'intval'),
                'mode' => input('post.mode/d', 0, 'intval'),
                'next_time' => input('post.next_time/d', 0, 'intval'),
                'result' => input('post.result/s', '', 'trim'),
                'enclosure' => input('post.enclosure/s', '', 'trim'),
                'create_time' => time(),
                'link_man' => input('post.link_man/s', '', 'trim'),
                'link_mobile' => input('post.link_mobile/d', '', 'trim'),
                'linkman_id' => input('post.linkman_id/d', 0, 'intval'),
            ];

            $rule = [
                'type' => 'require|number|max:1',
                'clue_id' => 'require|number|length:1,10',
                'uid' => 'number|length:1,10',
                'relation_id' => 'number|length:1,10',
                'utype' => 'number|max:1',
                'mode' => 'require|max:1|number',
                'result' => 'max:250',
                'next_time' => 'number|length:1,10',
                'link_man' => 'require|length:1,30',
                'linkman_id' => 'require|number|length:1,10'
            ];

            $msg = [
                'type' => '跟进类型参数错误',
                'clue_id' => '请传正确的线索id',
                'mode' => '请选择跟进方式',
                'result' => '请输入1~250字内的跟进结果',
                'next_time' => '请选择下次跟进时间',
                'link_man' => '请输入1-10字内的联系人',
                'linkman_id' => '联系人id必须为1-10位的数字'
            ];
            $validate = new Validate($rule, $msg);
            if (!$validate->check($data)) {
                $this->ajaxReturn(500, $validate->getError());
            }

            if (!fieldRegex($data['link_mobile'], 'mobile')) {
                $data['link_mobile'] = '';
            }

            switch ($data['type']) {
                case 1:
                    // 1:线索跟进;
                    $clueInfo = model('b2bcrm.CrmClue')->field('name,admin_id')->where('id', $data['clue_id'])->find();
                    if ($clueInfo['admin_id'] != $this->admininfo->id) {
                        $this->ajaxReturn(500, '该线索不是您的线索，无法跟进');
                    }
                    $log_field = '对{' . $clueInfo['name'] . '}(线索ID:' . $data['clue_id'];
                    break;

                case 2:
                    // 2:客户跟进;
                    $company_info = model('Company')->field('id,admin_id,companyname')->where('uid', $data['uid'])->find();
                    if (null === $company_info) {
                        $this->ajaxReturn(500, '要跟进的客户不存在');
                    }
                    if ($company_info['admin_id'] != $this->admininfo->id) {
                        $this->ajaxReturn(500, '该客户不是您的客户，无法跟进');
                    }
                    $log_field = '对{' . $company_info['companyname'] . '}(企业ID:' . $company_info['id'];
                    break;

                default:
                    $this->ajaxReturn(500, '错误的跟进类型');
                    break;
            }

            Db::startTrans();
            if ($data['uid'] > 0) {
                model('Company')
                    ->isUpdate(true)
                    ->save(['last_visit_time' => time(), 'last_visit_admin' => $this->admininfo->id], ['uid' => $data['uid']]);
            }
            model('b2bcrm.CrmFollowUp')->saveData($data);

            $log_field .= ')添加跟进，内容:' . model('b2bcrm.CrmFollowUp')->method[$data['mode']] . ':电话拜访；跟进结果:' . $data['result'];
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                1
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
    }

    /*
     * 当日待跟进列表
     * */
    public function toBeFollowedup()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $type = input('get.type/d', 0, 'intval');
        $switch_type = input('get.switch_type/d', 0, 'intval');
        $start_time = input('get.start_time/s', '', 'trim');
        $end_time = input('get.end_time/s', '', 'trim');
        $s = strtotime(date('Y-m-d') . '00:00:00');
        $l = strtotime(date('Y-m-d') . '23:59:59');
        if ($switch_type == 0) {
            $where['next_time'] = ['between', [$s, $l]];
        } else {
            if ($start_time != '' && $end_time != '') {
                $where['next_time'] = [['>=', strtotime($start_time . '00:00:00')], ['<=', strtotime($end_time . '23:59:59')], 'and'];
            }
        }
        $where['a.admin_id'] = $this->admininfo->id;
        $field = "
        a.type,
        a.clue_id,
        a.link_man,
        a.uid,
        a.relation_id,
        a.link_man,
        a.link_mobile,
        a.next_time,
        ifnull(c.name,'')as name,
        ifnull(d.companyname,'') as companyname,
        d.id as company_id,
        CASE
        WHEN next_time > UNIX_TIMESTAMP(NOW())
          THEN 1
          ELSE 0
        END 
          AS is_outtime";
        if ($type > 0) {
            $where['type'] = $type;
        }
        $order = ['is_outtime DESC', 'a.next_time ASC'];
        $list = model('b2bcrm.CrmFollowUp')->getList($where, $order, $field, $current_page, $pagesize);
        if (empty($list)) {
            $data = [
                'rows' => [],
                'pages' => [
                    'now_page' => $current_page,
                    'record_num' => 0,
                    'count' => 0
                ]
            ];
            $this->ajaxReturn(200, '获取数据成功', $data);
        }
        foreach ($list['rows'] as $k => $v) {
            switch ($v['type']) {
                case 1:
                    $list['rows'][$k]['type_name'] = '线索';
                    break;
                case 2:
                    $list['rows'][$k]['type_name'] = '客户';
                    $list['rows'][$k]['name'] = $v['companyname'];
                    break;
                case 3:
                    $list['rows'][$k]['type_name'] = '简历';
                    $list['rows'][$k]['name'] = $v['link_man'];
                    $resume = model('Resume')->where('id', $v['relation_id'])->find();
                    $list['rows'][$k]['age'] =
                        intval($resume['birthday']) == 0
                            ? '年龄未知'
                            : date('Y') - intval($resume['birthday']) . '岁';
                    $list['rows'][$k]['education_cn'] = isset(
                        model('BaseModel')->map_education[$resume['education']]
                    )
                        ? model('BaseModel')->map_education[$resume['education']]
                        : '学历未知';
                    $list['rows'][$k]['experience_cn'] =
                        $resume['enter_job_time'] == 0
                            ? '无经验'
                            : format_date($resume['enter_job_time']);
                    $list['rows'][$k]['sex'] =
                        intval($resume['sex']) == 1
                            ? '男'
                            : '女';
                    $list['rows'][$k]['name'] = $v['link_man'];
                    break;
                default:
                    $list['rows'][$k]['type_name'] = '';
                    break;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}
