<?php
namespace app\apiadmin\controller;

class MemberCancelApply extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }
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
                    $where['companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        $total = model('MemberCancelApply')->where($where)->count();
        $list = model('MemberCancelApply')->where($where)
                ->order('status asc,id desc')
                ->page($current_page . ',' . $pagesize)
                ->select();
        
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function delete()
    {
        $id = input('post.id/d',0,'intval');

        if ($id==0) {
            $this->ajaxReturn(500, '请选择');
        }
        model('MemberCancelApply')->where('id',$id)->delete();
        model('AdminLog')->record(
            '删除账号注销申请。申请ID【' . $id . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function handle()
    {
        $id = input('post.id/d',0,'intval');
        if ($id==0) {
            $this->ajaxReturn(500, '请选择');
        }
        $info = model('MemberCancelApply')->where('id',$id)->find();
        $uid = $info['uid'];
        \think\Db::startTrans();
        try {
            //删除会员相关信息
            if (
                false ===
                model('Member')->deleteMemberByUids($uid)
            ) {
                throw new \Exception(model('Member')->getError());
            }
            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        $info->status = 1;
        $info->handlertime = time();
        $info->save();
        model('AdminLog')->record(
            '处理账号注销申请。申请ID【' . $id . '】；会员UID【' . $uid . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '处理成功');
    }
}
