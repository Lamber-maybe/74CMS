<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Setmeal extends Backend
{
    public function index()
    {
        $list = model('Setmeal')
            ->order('sort_id desc,id asc')
            ->select();
        $return['items'] = $list;

        // 增加是否是超管字段:0|否,1|是 chenyang 2022年3月9日18:38:15
        $return['is_administrator'] = 0;
        if ($this->admininfo->access == 'all') {
            $return['is_administrator'] = 1;
        }

        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'is_apply' => input('post.is_apply/d', 0, 'intval'),
            'days' => input('post.days/d', 0, 'intval'),
            'expense' => input('post.expense/d', 0, 'intval'),
            'preferential_open' => input(
                'post.preferential_open/d',
                0,
                'intval'
            ),
            'preferential_expense' => input(
                'post.preferential_expense/d',
                0,
                'intval'
            ),
            'preferential_expense_start' => input(
                'post.preferential_expense_start/s',
                '',
                'trim'
            ),
            'preferential_expense_end' => input(
                'post.preferential_expense_end/s',
                '',
                'trim'
            ),
            'service_added_discount' => input(
                'post.service_added_discount/f',
                0.0,
                'floatval'
            ),
            'jobs_meanwhile' => input('post.jobs_meanwhile/d', 0, 'intval'),
            'refresh_jobs_free_perday' => input(
                'post.refresh_jobs_free_perday/d',
                0,
                'intval'
            ),
            'download_resume_point' => input(
                'post.download_resume_point/d',
                0,
                'intval'
            ),
            'download_resume_max_perday' => input(
                'post.download_resume_max_perday/d',
                0,
                'intval'
            ),
            'enable_video_interview' => input(
                'post.enable_video_interview/d',
                0,
                'intval'
            ),
            'enable_poster' => input('post.enable_poster/d', 0, 'intval'),
            'note' => input('post.note/s', '', 'trim'),
            'recommend' => input('post.recommend/d', 0, 'intval'),
            'gift_point' => input('post.gift_point/d', 0, 'intval'),
            'show_apply_contact' => input(
                'post.show_apply_contact/d',
                0,
                'intval'
            ),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'icon' => input('post.icon/d', 0, 'intval'),
            'im_max_perday' => input(
                'post.im_max_perday/d',
                0,
                'intval'
            ),
            'im_total' => input(
                'post.im_total/d',
                0,
                'intval'
            )
        ];
        if ($input_data['preferential_open'] == 1) {
            $preferential_expense_start = $input_data['preferential_expense_start'];
            $preferential_expense_end = $input_data['preferential_expense_end'];
            $input_data['preferential_expense_start'] = $input_data['preferential_expense_start']
                ? strtotime($input_data['preferential_expense_start'])
                : 0;
            $input_data['preferential_expense_end'] = $input_data['preferential_expense_end']
                ? strtotime($input_data['preferential_expense_end'])
                : 0;
        } else {
            $input_data['preferential_expense_start'] = 0;
            $input_data['preferential_expense_end'] = 0;
        }

        try {
            Db::startTrans();

            $result = model('Setmeal')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if (false === $result) {
                throw new \Exception(model('Setmeal')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-套餐配置，套餐管理，添加套餐，套餐名称:'
                . $input_data['name']
                . '(ID:'
                . model('Setmeal')->id
                . ')；价格:'
                . $input_data['expense']
                . '；时长:'
                . $input_data['days']
                . '天；开启限时特惠:'
                . model('Setmeal')->map_preferential_open[$input_data['preferential_open']]
                . ($input_data['preferential_open'] === 1
                    ? ",特惠购买价:{$input_data['preferential_expense']},特惠时间:{$preferential_expense_start}-{$preferential_expense_end}"
                    : '')
                . '；下载简历点数:'
                . $input_data['download_resume_point']
                . '；赠送积分数:'
                . $input_data['gift_point']
                . '；同时在招职位数:'
                . $input_data['jobs_meanwhile']
                . '；职聊次数:'
                . $input_data['im_total']
                . '；允许发起聊天数:'
                . $input_data['im_max_perday']
                . '次/天；使用视频面试:'
                . model('Setmeal')->map_enable_video_interview[$input_data['enable_video_interview']]
                . '；收到简历免费查看:'
                . model('Setmeal')->map_show_apply_contact[$input_data['show_apply_contact']]
                . '；是否推荐:'
                . model('Setmeal')->map_recommend[$input_data['recommend']]
                . '；允许申请:'
                . model('Setmeal')->map_is_apply[$input_data['is_apply']]
                . '；是否显示:'
                . model('Setmeal')->map_is_display[$input_data['is_display']]
                . '；下载简历上限:'
                . $input_data['download_resume_max_perday']
                . '份/天；免费刷新职位:'
                . $input_data['refresh_jobs_free_perday']
                . '次/天；套餐增值包折扣:'
                . $input_data['service_added_discount']
                . '；排序:'
                . $input_data['sort_id']
                . '；其他说明:'
                . (empty($input_data['note']) ? '未填写' : $input_data['note']);

            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Setmeal')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['preferential_expense_start'] =
                $info['preferential_expense_start'] == 0
                    ? 0
                    : date('Y-m-d', $info['preferential_expense_start']);
            $info['preferential_expense_end'] =
                $info['preferential_expense_end'] == 0
                    ? 0
                    : date('Y-m-d', $info['preferential_expense_end']);
            $iconUrl = model('Uploadfile')->getFileUrl($info['icon']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'iconUrl' => $iconUrl
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'is_display' => input('post.is_display/d', 0, 'intval'),
                'is_apply' => input('post.is_apply/d', 0, 'intval'),
                'days' => input('post.days/d', 0, 'intval'),
                'expense' => input('post.expense/d', 0, 'intval'),
                'preferential_open' => input(
                    'post.preferential_open/d',
                    0,
                    'intval'
                ),
                'preferential_expense' => input(
                    'post.preferential_expense/d',
                    0,
                    'intval'
                ),
                'preferential_expense_start' => input(
                    'post.preferential_expense_start/s',
                    '',
                    'trim'
                ),
                'preferential_expense_end' => input(
                    'post.preferential_expense_end/s',
                    '',
                    'trim'
                ),
                'service_added_discount' => input(
                    'post.service_added_discount/f',
                    0.0,
                    'floatval'
                ),
                'jobs_meanwhile' => input('post.jobs_meanwhile/d', 0, 'intval'),
                'refresh_jobs_free_perday' => input(
                    'post.refresh_jobs_free_perday/d',
                    0,
                    'intval'
                ),
                'download_resume_point' => input(
                    'post.download_resume_point/d',
                    0,
                    'intval'
                ),
                'download_resume_max_perday' => input(
                    'post.download_resume_max_perday/d',
                    0,
                    'intval'
                ),
                'enable_video_interview' => input(
                    'post.enable_video_interview/d',
                    0,
                    'intval'
                ),
                'enable_poster' => input('post.enable_poster/d', 0, 'intval'),
                'note' => input('post.note/s', '', 'trim'),
                'recommend' => input('post.recommend/d', 0, 'intval'),
                'gift_point' => input('post.gift_point/d', 0, 'intval'),
                'show_apply_contact' => input(
                    'post.show_apply_contact/d',
                    0,
                    'intval'
                ),
                'sort_id' => input('post.sort_id/d', 0, 'intval'),
                'icon' => input('post.icon/d', 0, 'intval'),
                'im_max_perday' => input(
                    'post.im_max_perday/d',
                    0,
                    'intval'
                ),
                'im_total' => input(
                    'post.im_total/d',
                    0,
                    'intval'
                )
            ];

            if ($input_data['preferential_open'] == 1) {
                $input_data['preferential_expense_start'] = $input_data['preferential_expense_start']
                    ? strtotime($input_data['preferential_expense_start'])
                    : 0;
                $input_data['preferential_expense_end'] = $input_data['preferential_expense_end']
                    ? strtotime($input_data['preferential_expense_end'])
                    : 0;
            } else {
                $input_data['preferential_expense_start'] = 0;
                $input_data['preferential_expense_end'] = 0;
            }
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $info = model('Setmeal')
                    ->where('id', $id)
                    ->find();
                if (null === $info) {
                    $this->ajaxReturn(500, '要修改的套餐不存在');
                }

                Db::startTrans();

                $update_result = model('Setmeal')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
                if (false === $update_result) {
                    throw new \Exception(model('Setmeal')->getError());
                }

                /**
                 * 日志
                 */
                $log_field = '系统-企业业务配置-套餐配置，套餐管理，添加套餐，套餐名称:'
                    . $info['name'];
                if ($input_data['name'] != $info['name']) {
                    $log_field .= '->' . $input_data['expense'];
                }
                $log_field .= '(ID:' . $info['id'] . ')';
                if ($input_data['expense'] != $info['expense']) {
                    $log_field .= '；价格:' . $info['expense'] . '->' . $input_data['expense'];
                }
                if ($input_data['days'] != $info['days']) {
                    $log_field .= '；时长:' . $info['days'] . '->' . $input_data['days'] . '天';
                }
                if ($input_data['preferential_open'] != $info['preferential_open']
                    ||
                    $input_data['preferential_expense_start'] != $info['preferential_expense_start']
                    ||
                    $input_data['preferential_expense_end'] != $info['preferential_expense_end']
                ) {
                    $log_field .= '；开启限时特惠:'
                        . model('Setmeal')->map_preferential_open[$info['preferential_open']]
                        . '->'
                        . model('Setmeal')->map_preferential_open[$input_data['preferential_open']]
                        . ',特惠购买价:'
                        . $info['preferential_expense']
                        . '->'
                        . $input_data['preferential_expense']
                        . ',特惠时间:'
                        . (!empty($info['preferential_expense_start']) ? date('Y-m-d', $info['preferential_expense_start']) : '关闭')
                        . '-'
                        . (!empty($info['preferential_expense_end']) ? date('Y-m-d', $info['preferential_expense_end']) : '关闭')
                        . '->'
                        . (!empty($input_data['preferential_expense_start']) ? date('Y-m-d', $input_data['preferential_expense_start']) : '关闭')
                        . '-'
                        . (!empty($input_data['preferential_expense_end']) ? date('Y-m-d', $input_data['preferential_expense_end']) : '关闭');
                }
                if ($input_data['download_resume_point'] != $info['download_resume_point']) {
                    $log_field .= '；下载简历点数:' . $info['download_resume_point'] . '->' . $input_data['download_resume_point'];
                }
                if ($input_data['gift_point'] != $info['gift_point']) {
                    $log_field .= '；赠送积分数:' . $info['gift_point'] . '->' . $input_data['gift_point'];
                }
                if ($input_data['jobs_meanwhile'] != $info['jobs_meanwhile']) {
                    $log_field .= '；同时在招职位数:' . $info['jobs_meanwhile'] . '->' . $input_data['jobs_meanwhile'];
                }
                if ($input_data['im_total'] != $info['im_total']) {
                    $log_field .= '；职聊次数:' . $info['im_total'] . '->' . $input_data['im_total'];
                }
                if ($input_data['im_max_perday'] != $info['im_max_perday']) {
                    $log_field .= '；允许发起聊天数:' . $info['im_max_perday'] . '->' . $input_data['im_max_perday'] . '次/天';
                }
                if ($input_data['enable_video_interview'] != $info['enable_video_interview']) {
                    $log_field .= '；使用视频面试:'
                        . model('Setmeal')->map_enable_video_interview[$info['enable_video_interview']]
                        . '->'
                        . model('Setmeal')->map_enable_video_interview[$input_data['enable_video_interview']];
                }
                if ($input_data['show_apply_contact'] != $info['show_apply_contact']) {
                    $log_field .= '；收到简历免费查看:'
                        . model('Setmeal')->map_show_apply_contact[$info['show_apply_contact']]
                        . '->'
                        . model('Setmeal')->map_show_apply_contact[$input_data['show_apply_contact']];
                }
                if ($input_data['recommend'] != $info['recommend']) {
                    $log_field .= '；是否推荐:'
                        . model('Setmeal')->map_recommend[$info['recommend']]
                        . '->'
                        . model('Setmeal')->map_recommend[$input_data['recommend']];
                }
                if ($input_data['is_apply'] != $info['is_apply']) {
                    $log_field .= '；允许申请:'
                        . model('Setmeal')->map_is_apply[$info['is_apply']]
                        . '->'
                        . model('Setmeal')->map_is_apply[$input_data['is_apply']];
                }
                if ($input_data['is_display'] != $info['is_display']) {
                    $log_field .= '；是否显示:'
                        . model('Setmeal')->map_is_display[$info['is_display']]
                        . '->'
                        . model('Setmeal')->map_is_display[$input_data['is_display']];
                }
                if ($input_data['download_resume_max_perday'] != $info['download_resume_max_perday']) {
                    $log_field .= '；下载简历上限:' . $info['download_resume_max_perday'] . '->' . $input_data['download_resume_max_perday'] . '份/天';
                }
                if ($input_data['refresh_jobs_free_perday'] != $info['refresh_jobs_free_perday']) {
                    $log_field .= '；免费刷新职位:' . $info['refresh_jobs_free_perday'] . '->' . $input_data['refresh_jobs_free_perday'] . '次/天';
                }
                if ($input_data['service_added_discount'] != $info['service_added_discount']) {
                    $log_field .= '；套餐增值包折扣:' . $info['service_added_discount'] . '->' . $input_data['service_added_discount'];
                }
                if ($input_data['sort_id'] != $info['sort_id']) {
                    $log_field .= '；排序:' . $info['sort_id'] . '->' . $input_data['sort_id'];
                }
                if ($input_data['note'] != $info['note']) {
                    $log_field .= '；其他说明:'
                        . (empty($info['note']) ? '未填写' : $info['note'])
                        . '->'
                        . (empty($input_data['note']) ? '未填写' : $input_data['note']);
                }

                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    3
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $info = model('Setmeal')
                ->where('id', $id)
                ->find();
            if (null === $info) {
                $this->ajaxReturn(500, '要删除的套餐不存在');
            }

            /*
             * 【bug】优惠卷绑定套餐后，删除套餐后台优惠卷报错
             * zch 2022.10.18
             * 新增套餐是否被套餐和优惠卷使用验证
             * */
            $company_setmeal = model('Company')->where('setmeal_id', $id)->find();
            if (!empty($company_setmeal)) {
                $this->ajaxReturn(500, '此套餐已被企业使用，不可删除！');
            }
            $coupon = model('Coupon')->where('bind_setmeal_id', $id)->find();
            if (!empty($coupon)) {
                $this->ajaxReturn(500, '此套餐已被优惠卷绑定，不可删除！');
            }

            Db::startTrans();

            $del_result = model('Setmeal')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Setmeal')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-套餐配置，套餐管理，添加套餐，套餐名称:'
                . $info['name']
                . '(ID:'
                . $info['id']
                . ')；价格:'
                . $info['expense']
                . '；时长:'
                . $info['days']
                . '天；开启限时特惠:'
                . model('Setmeal')->map_preferential_open[$info['preferential_open']]
                . ($info['preferential_open'] === 1
                    ? ",特惠购买价:{$info['preferential_expense']},特惠时间:{$info['preferential_expense_start']}-{$info['preferential_expense_end']}"
                    : '')
                . '；下载简历点数:'
                . $info['download_resume_point']
                . '；赠送积分数:'
                . $info['gift_point']
                . '；同时在招职位数:'
                . $info['jobs_meanwhile']
                . '；职聊次数:'
                . $info['im_total']
                . '；允许发起聊天数:'
                . $info['im_max_perday']
                . '次/天；使用视频面试:'
                . model('Setmeal')->map_enable_video_interview[$info['enable_video_interview']]
                . '；收到简历免费查看:'
                . model('Setmeal')->map_show_apply_contact[$info['show_apply_contact']]
                . '；是否推荐:'
                . model('Setmeal')->map_recommend[$info['recommend']]
                . '；允许申请:'
                . model('Setmeal')->map_is_apply[$info['is_apply']]
                . '；是否显示:'
                . model('Setmeal')->map_is_display[$info['is_display']]
                . '；下载简历上限:'
                . $info['download_resume_max_perday']
                . '份/天；免费刷新职位:'
                . $info['refresh_jobs_free_perday']
                . '次/天；套餐增值包折扣:'
                . $info['service_added_discount']
                . '；排序:'
                . $info['sort_id']
                . '；其他说明:'
                . (empty($info['note']) ? '未填写' : $info['note']);

            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }
}
