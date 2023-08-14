<?php
namespace app\index\controller;

class Subsitelist extends Base
{
    public function show()
    {
		if (intval(config('global_config.subsite_open')) === 0) {
		    $this->redirect($this->sub_site_domain, 302);
		    exit;
		}
		
        $this->assign('pageHeader', $this->pageHeader);
        return $this->fetch('show');
    }
}
