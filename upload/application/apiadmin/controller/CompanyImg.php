<?php

namespace app\apiadmin\controller;

use think\Db;

class CompanyImg extends \app\common\controller\Backend
{
    public function index()
    {
        $list_type = input('get.list_type/d', 0, 'intval');
        $audit = input('get.audit/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $where = [];

        switch ($list_type) {
            case 1: // 客户公海
                $where['b.admin_id'] = ['=', 0];
                break;
            case 2: // 我的客户
                $where['b.admin_id'] = ['=', $this->admininfo->id];
                break;
            case 0:
            default: // 全部客户
                break;
        }

        /**
         * 【ID1000590】
         * 【新增】企业风采批量审核
         * yx - 2023.03.22
         */
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        if (!empty($keyword) && !empty($key_type)) {
            switch ($key_type) {
                case 1: // 企业名称
                    $where['b.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2: // 企业ID
                    $where['a.comid'] = ['=', $keyword];
                    break;
                case 3: // 会员UID
                    $where['a.uid'] = ['=', $keyword];
                    break;
                default:
                    break;
            }
        }

        if ($audit != '') {
            $where['a.audit'] = ['eq', intval($audit)];
        }

        $paginate = model('CompanyImg')->alias('a')
            ->join(config('database.prefix') . 'company b', 'a.comid=b.id')
            ->join(config('database.prefix') . 'company_contact c', 'b.id=c.comid')
            ->where($where)
            ->order('a.audit asc,a.id desc')
            ->field('a.*,b.companyname,c.mobile')
            ->paginate($pagesize)->toArrays();

        if (!empty($paginate['items'])) {
            $img_id_arr = array_column($paginate['items'], 'img');
            $img_src_data = [];
            if (!empty($img_id_arr)) {
                $img_src_data = model('Uploadfile')->getFileUrlBatch($img_id_arr);
            }

            foreach ($paginate['items'] as &$com_img) {
                $com_img['img_src'] = isset($img_src_data[$com_img['img']])
                    ? $img_src_data[$com_img['img']]
                    : '';
                $com_img['link_url'] = url('index/company/show', ['id' => $com_img['comid']]);
            }
        }

        $this->ajaxReturn(200, '获取数据成功', $paginate);
    }

    public function setAudit()
    {
        $id = input('post.id/a', []);
        $audit = input('post.audit/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CompanyImg')
            ->where('id', 'in', $id)
            ->setField('audit', $audit);

        //完成上传企业风采任务
        if ($audit == 1) {
            $list = model('CompanyImg')
                ->where('id', 'in', $id)
                ->select();
            foreach ($list as $key => $value) {
                model('Task')->doTask($value['uid'], 1, 'upload_img');
            }
        }

        model('AdminLog')->record(
            '将企业风采认证状态变更为【' .
            model('CompanyImg')->map_audit[$audit] .
            '】。企业风采ID【' .
            implode(",", $id) .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '审核成功');
    }

    public function delete()
    {
        $id = input('post.id/a', []);
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CompanyImg')
            ->where('id', 'in', $id)
            ->delete();
        model('AdminLog')->record(
            '删除企业风采。企业风采ID【' . implode(",", $id) . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }

    public function deleteOne()
    {
        $id = input('post.id/d', 0);
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要删除的企业风采');
        }

        if ($this->admininfo->access_delete == 0) {
            $this->ajaxReturn(500, '当前管理员没有删除数据权限');
        }

        try {
            $companyImg = model('CompanyImg')
                ->find($id);
            if (!isset($companyImg) || empty($companyImg)) {
                $this->ajaxReturn(500, '要删除的企业风采不存在');
            }

            Db::startTrans();

            model('CompanyImg')
                ->where('id', $id)
                ->delete();

            model('AdminLog')->record(
                '删除企业风采。企业ID【' . $companyImg['comid'] . '】，企业风采ID【' . $id . '】。',
                $this->admininfo
            );

            Db::commit();
            $this->ajaxReturn(200, '删除成功');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }
    }

    public function edit()
    {
        $id = input('post.id/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要修改的企业风采');
        }

        $input_data = [
            'img' => input('post.img/d', 0, 'intval'),
            'addtime' => time(),
            'audit' => 1
        ];

        try {
            $companyImg = model('CompanyImg')
                ->find($id);
            if (!isset($companyImg) || empty($companyImg)) {
                $this->ajaxReturn(500, '要修改的企业风采不存在');
            }

            Db::startTrans();

            model('CompanyImg')
                ->allowField(true)
                ->isUpdate(true)
                ->save(
                    $input_data,
                    ['id' => $id]
                );

            model('AdminLog')->record(
                '编辑企业风采。企业ID【' . $companyImg['comid'] . '】，企业风采ID【' . $id . '】。',
                $this->admininfo
            );

            Db::commit();
            $this->ajaxReturn(200, '保存成功');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }
    }
}
