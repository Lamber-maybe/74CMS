<?php
namespace app\apiadmin\controller;

class StatPersonal extends \app\common\controller\Backend
{
    /**
     * 求职者各学历性别分布
     */
    public function edu()
    {
        $return = [
            'dimensions' => ['学历', '男', '女'],
            'source' => []
        ];
        $datalist_boy = model('Resume')
            ->where('sex', 1)
            ->group('education')
            ->column('education,count(*) as total');
        $datalist_girl = model('Resume')
            ->where('sex', 2)
            ->group('education')
            ->column('education,count(*) as total');
        foreach (model('BaseModel')->map_education as $key => $value) {
            $arr['学历'] = $value;
            $arr['男'] = isset($datalist_boy[$key]) ? $datalist_boy[$key] : 0;
            $arr['女'] = isset($datalist_girl[$key]) ? $datalist_girl[$key] : 0;
            $return['source'][] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 求职者年龄性别分布
     */
    public function age()
    {
        $year = date('Y');
        $field =
            'count(*) as num,CASE 
                WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 16) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 20) .
            ' THEN "16-20岁"
            WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 20) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 30) .
            ' THEN "21-30岁"
            WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 30) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 40) .
            ' THEN "31-40岁"
            WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 40) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 50) .
            ' THEN "41-50岁"
            ELSE "50岁以上"
            END AS age_cn';

        $list_boy = [];
        $data_boy = model('Resume')
            ->field($field)
            ->group('age_cn')
            ->where('sex', 1)
            ->select();
        if ($data_boy) {
            $data_boy = collection($data_boy)->toArray();
            foreach ($data_boy as $key => $value) {
                $list_boy[$value['age_cn']] = $value['num'];
            }
        }
        $list_girl = [];
        $data_girl = model('Resume')
            ->field($field)
            ->group('age_cn')
            ->where('sex', 2)
            ->select();
        if ($data_girl) {
            $data_girl = collection($data_girl)->toArray();
            foreach ($data_girl as $key => $value) {
                $list_girl[$value['age_cn']] = $value['num'];
            }
        }

        $return = [
            'dimensions' => ['年龄', '男', '女'],
            'source' => []
        ];
        $return['source'] = [
            [
                '年龄' => '16-20岁',
                '男' => isset($list_boy['16-20岁']) ? $list_boy['16-20岁'] : 0,
                '女' => isset($list_girl['16-20岁']) ? $list_girl['16-20岁'] : 0
            ],
            [
                '年龄' => '21-30岁',
                '男' => isset($list_boy['21-30岁']) ? $list_boy['21-30岁'] : 0,
                '女' => isset($list_girl['21-30岁']) ? $list_girl['21-30岁'] : 0
            ],
            [
                '年龄' => '31-40岁',
                '男' => isset($list_boy['31-40岁']) ? $list_boy['31-40岁'] : 0,
                '女' => isset($list_girl['31-40岁']) ? $list_girl['31-40岁'] : 0
            ],
            [
                '年龄' => '41-50岁',
                '男' => isset($list_boy['41-50岁']) ? $list_boy['41-50岁'] : 0,
                '女' => isset($list_girl['41-50岁']) ? $list_girl['41-50岁'] : 0
            ],
            [
                '年龄' => '50岁以上',
                '男' => isset($list_boy['50岁以上'])
                    ? $list_boy['50岁以上']
                    : 0,
                '女' => isset($list_girl['50岁以上'])
                    ? $list_girl['50岁以上']
                    : 0
            ]
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 求职者工作经验性别分布
     */
    public function exp()
    {
        $field =
            'count(*) as num,CASE 
                WHEN enter_job_time=0 
                THEN "应届生"
            WHEN enter_job_time>' .
            strtotime('-2 year') .
            ' THEN "一年"
            WHEN enter_job_time>' .
            strtotime('-3 year') .
            ' AND enter_job_time<=' .
            strtotime('-2 year') .
            ' THEN "二年"
            WHEN enter_job_time>' .
            strtotime('-4 year') .
            ' AND enter_job_time<=' .
            strtotime('-3 year') .
            ' THEN "三年"
            WHEN enter_job_time>' .
            strtotime('-5 year') .
            ' AND enter_job_time<=' .
            strtotime('-4 year') .
            ' THEN "三年-五年"
            WHEN enter_job_time>' .
            strtotime('-10 year') .
            ' AND enter_job_time<=' .
            strtotime('-5 year') .
            ' THEN "五年-十年"
            ELSE "十年以上"
            END AS exp_cn';

        $list_boy = [];
        $data_boy = model('Resume')
            ->field($field)
            ->group('exp_cn')
            ->where('sex', 1)
            ->select();
        if ($data_boy) {
            $data_boy = collection($data_boy)->toArray();
            foreach ($data_boy as $key => $value) {
                $list_boy[$value['exp_cn']] = $value['num'];
            }
        }
        $list_girl = [];
        $data_girl = model('Resume')
            ->field($field)
            ->group('exp_cn')
            ->where('sex', 2)
            ->select();
        if ($data_girl) {
            $data_girl = collection($data_girl)->toArray();
            foreach ($data_girl as $key => $value) {
                $list_girl[$value['exp_cn']] = $value['num'];
            }
        }
        $return = [
            'dimensions' => ['工作经验', '男', '女'],
            'source' => []
        ];
        $return['source'] = [
            [
                '工作经验' => '应届生',
                '男' => isset($list_boy['应届生']) ? $list_boy['应届生'] : 0,
                '女' => isset($list_girl['应届生']) ? $list_girl['应届生'] : 0
            ],
            [
                '工作经验' => '一年',
                '男' => isset($list_boy['一年']) ? $list_boy['一年'] : 0,
                '女' => isset($list_girl['一年']) ? $list_girl['一年'] : 0
            ],
            [
                '工作经验' => '二年',
                '男' => isset($list_boy['二年']) ? $list_boy['二年'] : 0,
                '女' => isset($list_girl['二年']) ? $list_girl['二年'] : 0
            ],
            [
                '工作经验' => '三年',
                '男' => isset($list_boy['三年']) ? $list_boy['三年'] : 0,
                '女' => isset($list_girl['三年']) ? $list_girl['三年'] : 0
            ],
            [
                '工作经验' => '三年-五年',
                '男' => isset($list_boy['三年-五年'])
                    ? $list_boy['三年-五年']
                    : 0,
                '女' => isset($list_girl['三年-五年'])
                    ? $list_girl['三年-五年']
                    : 0
            ],
            [
                '工作经验' => '五年-十年',
                '男' => isset($list_boy['五年-十年'])
                    ? $list_boy['五年-十年']
                    : 0,
                '女' => isset($list_girl['五年-十年'])
                    ? $list_girl['五年-十年']
                    : 0
            ],
            [
                '工作经验' => '十年以上',
                '男' => isset($list_boy['十年以上'])
                    ? $list_boy['十年以上']
                    : 0,
                '女' => isset($list_girl['十年以上'])
                    ? $list_girl['十年以上']
                    : 0
            ]
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 求职者职类性别分布TOP20
     */
    public function jobcategory()
    {
        $return = [
            'dimensions' => ['职位类别', '男', '女'],
            'source' => []
        ];
        $datalist_boy = model('ResumeIntention')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.rid=b.id', 'LEFT')
            ->where('b.sex', 1)
            ->group('a.category')
            ->column('a.category,count(*) as total');
        $datalist_girl = model('ResumeIntention')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.rid=b.id', 'LEFT')
            ->where('b.sex', 2)
            ->group('a.category')
            ->column('a.category,count(*) as total');
        foreach ($datalist_boy as $k => $v) {
            if (isset($datalist_girl[$k])) {
                $datalist_boy[$k] = $v + $datalist_girl[$k];
            }
        }
        $datalist_all = $datalist_boy + $datalist_girl;
        arsort($datalist_all);
        $category_job_data = model('CategoryJob')->getCache();
        $counter = 0;
        foreach ($datalist_all as $key => $value) {
            if ($counter >= 20) {
                break;
            }
            $arr['职位类别'] = isset($category_job_data[$key])
                ? $category_job_data[$key]
                : '未知' . $counter;
            $arr['男'] = isset($datalist_boy[$key]) ? $datalist_boy[$key] : 0;
            $arr['女'] = isset($datalist_girl[$key]) ? $datalist_girl[$key] : 0;
            $return['source'][] = $arr;
            $counter++;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
