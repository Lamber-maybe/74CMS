<?php
namespace app\apiadmin\controller;

class Setmeal extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
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
            $input_data['preferential_expense_start'] = $input_data[
                'preferential_expense_start'
            ]
                ? strtotime($input_data['preferential_expense_start'])
                : 0;
            $input_data['preferential_expense_end'] = $input_data[
                'preferential_expense_end'
            ]
                ? strtotime($input_data['preferential_expense_end'])
                : 0;
        } else {
            $input_data['preferential_expense_start'] = 0;
            $input_data['preferential_expense_end'] = 0;
        }

        $result = model('Setmeal')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('Setmeal')->getError());
        }
        model('AdminLog')->record(
            '添加套餐。套餐ID【' .
                model('Setmeal')->id .
                '】；套餐名称【' .
                $input_data['name'] .
                '】',
            $this->admininfo
        );
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
                $input_data['preferential_expense_start'] = $input_data[
                    'preferential_expense_start'
                ]
                    ? strtotime($input_data['preferential_expense_start'])
                    : 0;
                $input_data['preferential_expense_end'] = $input_data[
                    'preferential_expense_end'
                ]
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
            $result = model('Setmeal')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('Setmeal')->getError());
            }
            model('AdminLog')->record(
                '编辑套餐。套餐ID【' .
                    $id .
                    '】；套餐名称【' .
                    $input_data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }

        $info = model('Setmeal')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info->delete();
        model('AdminLog')->record(
            '删除套餐。套餐ID【' . $id . '】;套餐名称【' . $info['name'] . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
