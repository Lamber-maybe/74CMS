<?php

namespace app\apiadmin\controller;

use think\Db;

class CompanyReport extends \app\common\controller\Backend
{
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
                    $where['a.company_id'] = ['eq', $keyword];
                    break;
                case 2:
                    $where['b.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['a.uid'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        $total = model('CompanyReport')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->where($where)
            ->count();
        $list = model('CompanyReport')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'company_contact c',
                'a.uid=c.uid',
                'LEFT'
            )
            ->field('a.*,b.companyname,c.contact,c.mobile')
            ->where($where)
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            $list[$key]['preview_link'] = url('index/company/report', ['id' => $value['company_id']]);
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);

        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function add()
    {
        $input_data = input('post.');
        if (
            isset($input_data['company_id']) &&
            intval($input_data['company_id']) > 0
        ) {
            $com_info = model('Company')
                ->where('id', $input_data['company_id'])
                ->field('uid')
                ->find();
            $input_data['uid'] = $com_info['uid'];
        }
        if (isset($input_data['reg_time']) && $input_data['reg_time'] != '') {
            $input_data['reg_time'] = strtotime($input_data['reg_time']);
        }

        if (isset($input_data['addtime']) && $input_data['addtime'] != '') {
            $input_data['addtime'] = strtotime($input_data['addtime']);
        } else {
            $input_data['addtime'] = time();
        }

        Db::startTrans();
        try {
            if (
                false ===
                model('CompanyReport')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('CompanyReport')->getError());
            }

            $companyname = model('Company')->where('id', $input_data['company_id'])->value('companyname');

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '添加{' . $companyname . '}(企业ID:' . $input_data['company_id'] . ')的实地认证资料',
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('CompanyReport')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $img_id_arr = $info['img'] == '' ? [] : explode(',', $info['img']);
            $img_src_arr = model('Uploadfile')->getFileUrlBatch($img_id_arr);
            $img_list = [];
            foreach ($img_src_arr as $key => $value) {
                $img_list[] = ['id' => $key, 'img_src' => $value];
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'img_list' => $img_list
            ]);
        } else {
            $input_data = input('post.');
            $id = isset($input_data['id']) ? intval($input_data['id']) : 0;
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $info = model('CompanyReport')->find($id);
            if (
                isset($input_data['reg_time']) &&
                $input_data['reg_time'] != ''
            ) {
                $input_data['reg_time'] = strtotime($input_data['reg_time']);
            }

            if (isset($input_data['addtime']) && $input_data['addtime'] != '') {
                $input_data['addtime'] = strtotime($input_data['addtime']);
            } else {
                $input_data['addtime'] = time();
            }

            Db::startTrans();
            try {
                if (
                    false ===
                    model('CompanyReport')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('CompanyReport')->getError());
                }

                $companyname = model('Company')->where('id', $info['company_id'])->value('companyname');

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    '修改{' . $companyname . '}(企业ID:' . $info['company_id'] . ')的实地认证资料',
                    $this->admininfo,
                    0,
                    3
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('CompanyReport')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $companyname = model('Company')->where('id', $info['company_id'])->value('companyname');

            $info->delete();

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '删除{' . $companyname . '}(企业ID:' . $info['company_id'] . ')的实地认证资料',
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function searchCompany()
    {
        $keyword = input('get.keyword/s', '', 'trim');
        if ($keyword != '') {
            $list = model('Company')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'company_report b',
                    'a.uid=b.uid',
                    'LEFT'
                )
                ->where('a.id', 'eq', $keyword)
                ->whereOr('a.companyname', 'like', '%' . $keyword . '%')
                ->column('a.id,a.uid,a.companyname,b.company_id');
        } else {
            $list = [];
        }

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
