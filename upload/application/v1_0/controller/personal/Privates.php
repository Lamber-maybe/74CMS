<?php
namespace app\v1_0\controller\personal;

class Privates extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
        $this->interceptPersonalResume();
    }
    /**
     * 获取隐私设置信息
     */
    public function index()
    {
        $shield_company_uid_arr = model('Shield')
            ->where(['personal_uid' => ['eq', $this->userinfo->uid]])
            ->column('company_uid');

        if (!empty($shield_company_uid_arr)) {
            $list = model('Company')
                ->where(['uid' => ['in', $shield_company_uid_arr]])
                ->column('id,companyname', 'id');
        } else {
            $list = [];
        }
        $return = [];
        foreach ($list as $key => $value) {
            $return[] = ['id' => $key, 'companyname' => $value];
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'blacklist' => $return,
            'is_display' => $this->resume_info['is_display'],
            'display_name' => $this->resume_info['display_name']
        ]);
    }
    /**
     * 屏蔽企业时搜索企业
     */
    public function searchCompany()
    {
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        if ($keyword == '') {
            $this->ajaxReturn(200, '获取数据成功', ['items' => []]);
        }
        $shield_company_uid_arr = model('Shield')
            ->where(['personal_uid' => ['eq', $this->userinfo->uid]])
            ->column('company_uid');
        $list = model('Company')
            ->field('id,uid,companyname,scale,trade,nature')
            ->where('companyname|short_name', 'like', '%' . $keyword . '%')
            ->select();
        $category_data = model('Category')->getCache();
        $return = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            if (in_array($value['uid'], $shield_company_uid_arr)) {
                $tmp_arr['in_blacklist'] = 1;
            } else {
                $tmp_arr['in_blacklist'] = 0;
            }
            $tmp_arr['companyname'] = $value['companyname'];
            $tmp_arr['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $tmp_arr['scale_text'] = isset(
                $category_data['QS_scale'][$value['scale']]
            )
                ? $category_data['QS_scale'][$value['scale']]
                : '';
            $tmp_arr['nature_text'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            $return[] = $tmp_arr;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }
    /**
     * 添加屏蔽企业
     */
    public function addBlacklist()
    {
        $company_id_arr = input('post.id/a', []);
        if (empty($company_id_arr)) {
            $this->ajaxReturn(500, '请选择企业');
        }
        $uid_arr = model('Company')
            ->where('id', 'in', $company_id_arr)
            ->column('uid');
        $insert_arr = [];
        foreach ($uid_arr as $key => $value) {
            $arr['personal_uid'] = $this->userinfo->uid;
            $arr['company_uid'] = $value;
            $insert_arr[] = $arr;
        }
        if (!empty($insert_arr)) {
            model('Shield')->saveAll($insert_arr);
        }
        $this->writeMemberActionLog($this->userinfo->uid,'添加屏蔽企业【企业ID：'.implode(",",$company_id_arr).'】');
        $this->ajaxReturn(200, '添加成功');
    }
    /**
     * 删除屏蔽企业
     */
    public function deleteBlacklist()
    {
        $company_id = input('post.id/d', 0, 'intval');
        if ($company_id === 0) {
            $this->ajaxReturn(500, '请选择企业');
        }
        $company_info = model('Company')
            ->where('id', 'eq', $company_id)
            ->find();
        if ($company_info === null) {
            $this->ajaxReturn(500, '没有找到企业');
        }
        model('Shield')
            ->where('company_uid', $company_info['uid'])
            ->where('personal_uid', $this->userinfo->uid)
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除屏蔽企业【企业ID：'.$company_id.'】');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 简历显示状态
     */
    public function setDisplay()
    {
        $display = input('post.display/d', 0, 'intval');
        model('Resume')->setDisplay(0, $this->userinfo->uid, $display);
        $this->writeMemberActionLog($this->userinfo->uid,'设置简历显示状态【'.($display==1?'显示':'隐藏').'】');
        $this->ajaxReturn(200, '设置成功');
    }
    /**
     * 简历姓名显示状态
     */
    public function setDisplayName()
    {
        $display = input('post.display/d', 0, 'intval');
        model('Resume')
            ->where('uid', $this->userinfo->uid)
            ->setField('display_name', $display);
        $this->writeMemberActionLog($this->userinfo->uid,'设置简历姓名显示状态');
        $this->ajaxReturn(200, '设置成功');
    }
}
