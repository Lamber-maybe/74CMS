<?php
namespace app\apiadminmobile\controller;

class Stat extends \app\apiadmin\controller\StatOverview
{
    /**
     * 总览数据统计
     */
    public function index()
    {
        $return = $this->numTotal();
        $todayTimestamp = strtotime('today');
        $days = 7;
        $daterange = [date('Y-m-d',$todayTimestamp - 3600 * 24 * $days),date('Y-m-d',$todayTimestamp)];
        $return['reg_line'] = $this->_reg('',$daterange);
        $return['active_line'] = $this->_active('',$daterange);
        $return['setmeal_line'] = (new \app\apiadmin\controller\StatOrder)->_paySetmeal('',$daterange);
        $setmeal_table_data = (new \app\apiadmin\controller\StatBusiness)->_setmeal();
        $setmeal_table_data = $setmeal_table_data['source'];
        $setmeal_table_list = [];
        foreach ($setmeal_table_data as $key => $value) {
            $arr = [
                'setmeal_name'=>$value['套餐类型'],
                'company_num'=>$value['企业数'],
                'overtime_num'=>$value['过期数']
            ];
            $setmeal_table_list[] = $arr;
        }
        $return['setmeal_table'] = $setmeal_table_list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
