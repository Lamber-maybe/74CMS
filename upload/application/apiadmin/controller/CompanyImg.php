<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class CompanyImg extends Backend
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
            $this->ajaxReturn(500, '请选择要审核的企业风采');
        }

        try {
            Db::startTrans();

            $list = model('CompanyImg')
                ->where('id', 'in', $id)
                ->select();
            if (null === $list) {
                throw new \Exception('没有要审核的企业风采');
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

            /**
             * 日志
             */
            $comIds = array_column($list, 'comid', 'comid');
            $company_arr = model('Company')->whereIn('id', $comIds)->column('id,companyname');
            $l_list = [];
            foreach ($company_arr as $c_id => $c_name) {
                $l_list[] = '{' . $c_name . '}(企业ID:' . $c_id . ')';
            }
            $log_field = '批量审核'
                . implode('；', $l_list)
                . '上传的企业风采，审核结果:'
                . model('CompanyImg')->map_audit[$audit];
            model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                6
            );

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '审核失败', ['err_msg' => $e->getMessage()]);
        }

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
        model('AdminLog')->checkLog(
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
            $companyImg = $companyImg->toArray();

            Db::startTrans();

            if (
                false ===
                model('CompanyImg')
                    ->where('id', $id)
                    ->delete()
            ) {
                throw new \Exception(model('CompanyImg')->getError());
            }

            $company_name = model('Company')->where('id', $companyImg['comid'])->value('companyname');
            $log_result = model('AdminLog')->writeLog(
                '删除{' . $company_name . '}(企业ID:' . $companyImg['comid'] . ')的企业风采图片',
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

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
            $companyImg = $companyImg->toArray();

            Db::startTrans();

            if (
                false ===
                model('CompanyImg')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save(
                        $input_data,
                        ['id' => $id]
                    )
            ) {
                throw new \Exception(model('CompanyImg')->getError());
            }

            $company_name = model('Company')->where('id', $companyImg['comid'])->value('companyname');
            $log_result = model('AdminLog')->writeLog(
                '修改{' . $company_name . '}(企业ID:' . $companyImg['comid'] . ')的企业风采图片',
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
            $this->ajaxReturn(200, '保存成功');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }
    }
}
