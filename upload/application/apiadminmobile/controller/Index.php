<?php
namespace app\apiadminmobile\controller;

class Index extends \app\apiadmin\controller\Index
{
    /**
     * 首页基本信息
     */
    public function index()
    {
        $return['today_data'] = $this->getTodayData();
        $return['today_data']['orderpay_today'] = $return['today_data']['orderpay_company_today'] + $return['today_data']['orderpay_personal_today'];
        $return['today_data']['orderpay_yesterday'] = $return['today_data']['orderpay_company_yesterday'] + $return['today_data']['orderpay_personal_yesterday'];
        $return['pending_data'] = $this->getPendingData();
        $return['income_data'] = $this->todayIncomeTotal();
        $return['reg_line'] = $this->chartReg(7);
        $return['down_apply_line'] = $this->chartDownAndApply(7);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
