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
        $clue_id = input('get.clue_id/d',0,'intval');
        $uid = input('get.uid/d',0,'intval');
        if ($clue_id == 0 && $uid == 0)
        {
            $this->ajaxReturn(200, '缺少参数线索id或uid', []);
        }
        $where = [];
        if ($uid > 0)
        {
            $where['a.uid'] = $uid;
        }
        if ($clue_id > 0)
        {
            $where['a.clue_id'] = $clue_id;
        }
        $field = 'a.mode,a.result,a.enclosure,a.admin_id,a.create_time,b.username,b.avatar,a.link_man as name,a.uid';
        $list = model('b2bcrm.CrmFollowUp')
            ->getList($where,['a.create_time DESC'],$field,$current_page,$pagesize);
        if(empty($list))
        {
            $this->ajaxReturn(200, '获取成功', []);
        }
        foreach($list['rows'] as $k=>$v)
        {
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
        try{
            $data = [
                'type' => input('post.type/d',1,'intval'),
                'clue_id' => input('post.clue_id/d', 0, 'intval'),
                'uid' => input('post.uid/d',0,'intval'),
                'relation_id' => input('post.relation_id/d',0,'intval'),
                'admin_id' => $this->admininfo->id,
                'utype' => input('post.utype/d',0,'intval'),
                'mode' =>  input('post.mode/d', 0, 'intval'),
                'next_time' =>  input('post.next_time/d', 0, 'intval'),
                'result' =>  input('post.result/s', '', 'trim'),
                'enclosure' => input('post.enclosure/s','','trim'),
                'create_time' => time(),
                'link_man' => input('post.link_man/s','','trim'),
                'link_mobile' => input('post.link_mobile/d','','trim'),
                'linkman_id' => input('post.linkman_id/d',0,'intval'),
            ];

            $rule = [
                'type' => 'require|number|max:1',
                'clue_id' => 'require|number|length:1,10',
                'uid' => 'number|length:1,10',
                'relation_id' => 'number|length:1,10',
                'utype' => 'number|max:1',
                'mode' => 'require|max:1|number',
                'result' => 'max:100',
                'next_time' => 'number|length:1,10',
                'link_man' => 'require|length:1,30',
                'link_mobile' => 'require|number|max:11',
                'linkman_id' => 'require|number|length:1,10'
            ];

            $msg = [
                'type' => '跟进类型参数错误',
                'clue_id' => '请传正确的线索id',
                'mode' => '请选择跟进方式',
                'result' => '请输入1~100字内的跟进结果',
                'next_time' => '请选择下次跟进时间',
                'link_man' => '请输入1-10字内的联系人',
                'link_mobile' => '联系人手机号必须为11个数字',
                'linkman_id' => '联系人id必须为1-10位的数字'
            ];
            $validate = new Validate($rule, $msg);
            if (!$validate->check($data)) {
                $this->ajaxReturn(500, $validate->getError());
            }
            Db::startTrans();
            if ($data['uid'] > 0)
            {
                model('Company')
                    ->isUpdate(true)
                    ->save(['last_visit_time'=>time(),'last_visit_admin'=>$this->admininfo->id],['uid'=>$data['uid']]);
            }
            model('b2bcrm.CrmFollowUp')->saveData($data, $this->admininfo);

            Db::commit();
            $this->ajaxReturn(200, '保存成功');
        }catch(\Exception $e){
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
        $type = input('get.type/d',0,'intval');

        $s = strtotime(date('Y-m-d').'00:00:00');
        $l = strtotime(date('Y-m-d').'23:59:59');

        $where['next_time'] = ['between',[$s, $l]];
        $where['a.admin_id'] = $this->admininfo->id;
        $field = "
        a.type,
        a.clue_id,
        a.uid,
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
        if ($type > 0)
        {
            $where['type'] = $type;
        }
        $order = ['is_outtime DESC', 'a.next_time ASC'];
        $list = model('b2bcrm.CrmFollowUp')->getList($where,$order,$field,$current_page,$pagesize);
        if (empty($list))
        {
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
        foreach ($list['rows'] as $k=>$v)
        {
            switch ($v['type'])
            {
                case 1:
                    $list['rows'][$k]['type_name'] = '线索';
                    break;
                case 2:
                    $list['rows'][$k]['type_name'] = '客户';
                    $list['rows'][$k]['name'] = $v['companyname'];
                    break;
                default:
                    $list['rows'][$k]['type_name'] = '';
                    break;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}