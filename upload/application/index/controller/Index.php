<?php

namespace app\index\controller;

class Index extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        if (is_mobile_request() === true) {
            $this->redirect($this->sub_site_domain_m, 302);
            exit;
        }
        if ($this->subsite !== null) {
            $index_tpl = $this->subsite->tpl;
        } else {
            $index_tpl = config('global_config.index_tpl');
        }
        $index_tpl = $index_tpl ? $index_tpl : 'def';
        $instance = new \app\common\lib\Tpl($this->visitor);
        $return = $instance->index($index_tpl);
        foreach ($return as $key => $value) {
            $this->assign($key, $value);
        }
        $this->initPageSeo('index');
        $this->assign('pageHeader', $this->pageHeader);
        $this->assign('navSelTag', 'index');
        $prompt = [
            'is_look_apply' => 0,
            'is_look_interview' => 0
        ];
        if ($this->visitor['utype'] === 1){
            $is_look_apply = model('JobApply')
                ->where('company_uid',$this->visitor['uid'])
                ->where('is_look',0)
                ->find();
            $prompt['is_look_apply'] = !empty($is_look_apply) ? 1 : 0;
        }else{
            $is_look_interview = model('CompanyInterview')
                ->where('personal_uid',$this->visitor['uid'])
                ->where('is_look',0)
                ->find();
            $prompt['is_look_interview'] = !empty($is_look_interview) ? 1 : 0;
        }
        $this->assign('prompt',$prompt);
        return $this->fetch('index/' . $index_tpl . '/index');
    }
}
