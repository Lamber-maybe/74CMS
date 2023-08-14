<?php
namespace app\apiadmin\controller;

class StatBusiness extends \app\common\controller\Backend
{
    /**
     * 企业套餐会员分析
     */
    public function setmeal()
    {
        $return = $this->_setmeal();
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function _setmeal()
    {
        $return = [
            'dimensions' => ['套餐类型', '企业数', '过期数'],
            'source' => []
        ];
        $datalist_all = model('MemberSetmeal')
            ->group('setmeal_id')
            ->column('setmeal_id,count(*) as total');
        $datalist_overtime = model('MemberSetmeal')
            ->where('deadline', 'BETWEEN', [0, time()])
            ->where('deadline', 'neq',0)
            ->group('setmeal_id')
            ->column('setmeal_id,count(*) as total');
        $setmeal_list = model('Setmeal')->select();
        foreach ($setmeal_list as $key => $value) {
            $arr['套餐类型'] = $value['name'];
            $arr['企业数'] = isset($datalist_all[$value['id']])
                ? $datalist_all[$value['id']]
                : 0;
            $arr['过期数'] = isset($datalist_overtime[$value['id']])
                ? $datalist_overtime[$value['id']]
                : 0;
            $return['source'][] = $arr;
        }
        return $return;
    }
    /**
     * 职位增值服务分析
     */
    public function service()
    {
        $return = [
            'label' => ['置顶', '紧急', '智能刷新'],
            'dataset' => [
                [
                    'name' => '置顶',
                    'value' => model('Job')
                        ->where('stick', 1)
                        ->count()
                ],
                [
                    'name' => '紧急',
                    'value' => model('Job')
                        ->where('emergency', 1)
                        ->count()
                ],
                [
                    'name' => '智能刷新',
                    'value' => model('RefreshjobQueue')->count('DISTINCT jobid')
                ]
            ]
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function down()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
        } else {
            $endtime = strtotime('today');
            $starttime = $endtime - 86400 * 30;
        }
        $daterange = [$starttime, $endtime + 86400 - 1];

        $down_resume_data = model('CompanyDownResume')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $down_resume_data = $down_resume_data->where('platform', $platform);
        }
        $down_resume_data = $down_resume_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][] = isset($down_resume_data[$i])
                ? $down_resume_data[$i]
                : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
