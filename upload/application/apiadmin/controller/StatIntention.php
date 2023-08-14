<?php
namespace app\apiadmin\controller;

class StatIntention extends \app\common\controller\Backend
{
    /**
     * 求职者申请职位企业性质流向
     */
    public function comNature()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('JobApply')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.comid=b.id',
                'LEFT'
            )
            ->group('b.nature')
            ->column('b.nature,count(*) as total');
        $category_data = model('Category')->getCache();
        if (!empty($datalist)) {
            $number = 1;
            foreach ($datalist as $key => $value) {
                $arr['number'] = $number++;
                $arr['name'] = isset($category_data['QS_company_type'][$key])
                    ? $category_data['QS_company_type'][$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 求职者申请职位企业规模流向
     */
    public function comScale()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('JobApply')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.comid=b.id',
                'LEFT'
            )
            ->group('b.scale')
            ->column('b.scale,count(*) as total');
        $category_data = model('Category')->getCache();
        if (!empty($datalist)) {
            $number = 1;
            foreach ($datalist as $key => $value) {
                $arr['number'] = $number++;
                $arr['name'] = isset($category_data['QS_scale'][$key])
                    ? $category_data['QS_scale'][$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 求职者申请职位地区流向
     */
    public function comDistrict()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('JobApply')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.comid=b.id',
                'LEFT'
            )
            ->group('b.district')
            ->column('b.district,count(*) as total');
        $category_district_data = model('CategoryDistrict')->getCache();
        if (!empty($datalist)) {
            $number = 1;
            foreach ($datalist as $key => $value) {
                if ($number > 30) {
                    break;
                }
                $arr['number'] = $number++;
                $arr['name'] = isset($category_district_data[$key])
                    ? $category_district_data[$key]
                    : '';
                $arr['value'] = $value;
                $return['label'][] = $arr['name'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 求职者申请职位企业行业流向
     */
    public function comTrade()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('JobApply')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.comid=b.id',
                'LEFT'
            )
            ->group('b.trade')
            ->column('b.trade,count(*) as total');
        $category_data = model('Category')->getCache();
        if (!empty($datalist)) {
            $number = 1;
            foreach ($datalist as $key => $value) {
                if ($number > 30) {
                    break;
                }
                $arr['number'] = $number++;
                $arr['name'] = isset($category_data['QS_trade'][$key])
                    ? $category_data['QS_trade'][$key]
                    : '';
                $arr['value'] = $value;
                $return['label'][] = $arr['name'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
