<?php
/**
 * 会员信息相关
 */

namespace app\v1_0\controller\member;

class Info extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
        if ($this->userinfo->utype == 1) {
            $this->interceptCompanyProfile();
            $this->interceptCompanyAuth();
        } else {
            $this->interceptPersonalResume();
        }
    }

    /**
     * 我的积分
     */
    public function myPoints()
    {
        $return['points'] = model('Member')->getMemberPoints(
            $this->userinfo->uid
        );
        if ($this->userinfo->utype == 1) {
            $return['logo'] =
                $this->company_profile['logo'] > 0
                    ? model('Uploadfile')->getFileUrl(
                    $this->company_profile['logo']
                )
                    : default_empty('logo');

            $return['companyname'] = $this->company_profile['companyname'];
        } else {
            $return['photo'] =
                $this->resume_info['photo_img'] > 0
                    ? model('Uploadfile')->getFileUrl(
                    $this->resume_info['photo_img']
                )
                    : default_empty('photo', $this->resume_info['sex']);
            $return['fullname'] = $this->resume_info['fullname'];
        }
        $return['task'] = model('Task')->taskSituation(
            $this->userinfo->uid,
            $this->userinfo->utype
        );
        $return['taskPoints'] = model('Task')->countTaskPoints($this->userinfo->uid, $this->userinfo->utype);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 积分明细
     */
    public function pointsLog()
    {
        $type = input('get.type/d', 0, 'intval');
        $where['uid'] = $this->userinfo->uid;
        switch ($type) {
            case 1:
                $where['op'] = 1;
                break;
            case 2:
                $where['op'] = 2;
                break;
            default:
                break;
        }
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $list = model('MemberPointsLog')
            ->field('id,uid', true)
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }

    /**
     * 积分明细统计
     */
    public function pointsLogTotal()
    {
        $type = input('get.type/d', 0, 'intval');
        $where['uid'] = $this->userinfo->uid;
        switch ($type) {
            case 1:
                $where['op'] = 1;
                break;
            case 2:
                $where['op'] = 2;
                break;
            default:
                break;
        }
        $total = model('MemberPointsLog')
            ->where($where)
            ->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }

    /**
     * 套餐日志
     */
    public function setmealLog()
    {
        $where['uid'] = $this->userinfo->uid;
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');

        $list = model('MemberSetmealLog')
            ->field('content,addtime')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }

    /**
     * 套餐日志总数统计
     */
    public function setmealLogTotal()
    {
        $where['uid'] = $this->userinfo->uid;

        $total = model('MemberSetmealLog')
            ->where($where)
            ->count();

        $this->ajaxReturn(200, '获取数据成功', $total);
    }

    /**
     * 获取专属客服信息
     * CRM客服修改
     */
    public function customerService()
    {
        $customer_service = model('b2bcrm.CrmCustomerService')->alias('s')
            ->join('Company c', 's.admin_id=c.admin_id', 'left')
            ->where([
                'c.uid' => $this->userinfo->uid,
                's.admin_id' => ['>', 0]
            ])
            ->field('s.id as id,s.photo,s.name,s.mobile,s.tel,s.wx_qrcode,s.qq')
            ->find();

        if (empty($customer_service)) {
            $customer_service['name'] = '暂未分配客服';
            $customer_service['tel'] = config('global_config.contact_tel');
            $customer_service['photo'] = model('Uploadfile')
                ->getFileUrl(config('global_config.square_logo'));
        } else {
            if (empty($customer_service['mobile']) && empty($customer_service['tel'])) {
                $customer_service['tel'] = config('global_config.contact_tel');
            }

            $customer_service['photo'] = model('Uploadfile')
                ->getFileUrl(!empty($customer_service['photo'])
                    ? $customer_service['photo'] : config('global_config.square_logo'));

            $customer_service['wx_qrcode'] = model('Uploadfile')
                ->getFileUrl(!empty($customer_service['wx_qrcode']) ?
                    $customer_service['wx_qrcode'] : 0);
        }
        $customer_service['weixin'] = '';

        $this->ajaxReturn(200, '获取数据成功', ['info' => $customer_service]);
    }

    /**
     * 投诉客服
     */
    public function customerServiceComplaint()
    {
        $input_data = [
            'uid' => $this->userinfo->uid,
            'addtime' => time(),
            'status' => 0,
            'cs_id' => input('post.cs_id/d', 1, 'intval'),
            'content' => input('post.content/s', '', 'trim'),
        ];
        $validate = new \think\Validate([
            'cs_id' => 'require|number',
            'content' => 'require|max:200',
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        model('CustomerServiceComplaint')->save($input_data);
        $this->writeMemberActionLog($this->userinfo->uid, '投诉客服【客服ID：' . $input_data['cs_id'] . '】');
        $this->ajaxReturn(200, '投诉成功');
    }

    /**
     * 意见反馈
     */
    public function feedback()
    {
        $input_data = [
            'type' => input('post.type/d', 1, 'intval'),
            'utype' => $this->userinfo->utype,
            'uid' => $this->userinfo->uid,
            'addtime' => time(),
            'status' => 0,
            'content' => input('post.content/s', '', 'trim'),
        ];
        $validate = new \think\Validate([
            'type' => 'require|number|gt:0',
            'content' => 'require|max:200',
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        model('Feedback')->save($input_data);
        $this->writeMemberActionLog($this->userinfo->uid, '提交意见反馈信息');
        $this->ajaxReturn(200, '感谢您的反馈，我们会尽快处理');
    }

    /**
     * 投诉
     */
    public function tipoff()
    {
        $input_data = [
            'target_id' => input('post.target_id/d', 0, 'intval'),
            'type' => input('post.type/d', 1, 'intval'),
            'uid' => $this->userinfo->uid,
            'reason' => input('post.reason/d', 1, 'intval'),
            'img' => input('post.img/a', []),
            'addtime' => time(),
            'status' => 0,
            'content' => input('post.content/s', '', 'trim'),
        ];
        $validate = new \think\Validate([
            'target_id' => 'require|number|gt:0',
            'type' => 'require|number|gt:0',
            'reason' => 'require|number|gt:0',
            'content' => 'require|max:200',
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $input_data['img'] = !empty($input_data['img']) ? implode(",", $input_data['img']) : '';
        $id = model('Tipoff')->insertGetId($input_data);
        if ($input_data['type'] == 1) {
            model('Task')->doTask($this->userinfo->uid, 2, 'report_job');
        }
        $this->writeMemberActionLog($this->userinfo->uid, '举报' . ($input_data['type'] == 1 ? '职位' : '简历') . '信息【举报信息ID：' . $input_data['target_id'] . '】');
        $this->ajaxReturn(200, '举报成功，我们会尽快核实处理');
    }
}
