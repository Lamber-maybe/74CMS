<?php

namespace app\common\lib\cron;

use app\common\lib\sitemap\Mysitemap;

class Sitemap
{
    private $pagesize = 10000;

    public function execute()
    {
        $sitemap = new Mysitemap(config('global_config.sitedomain'));
        $sitemap_class = new \app\common\lib\sitemap\Sitemap();
        /**
         * 生成企业xml地址
         */
        $total = model('Company')->count('id');
        $company_page_num = ceil($total / $this->pagesize);
        for ($i = 1; $i <= $company_page_num; $i++) {
            $result = $sitemap->addItem('/sitemap/company_' . $i . '.xml', '0.9', 'always');
            if ($result === false) {
                return false;
            }
            $result = $sitemap_class->makeJobXml($i, 'company');
            if ($result === false) {
                return false;
            }
        }
        /**
         * 生成职位xml地址
         */
        $total = model('JobSearchRtime')->count('id');
        $job_page_num = ceil($total / $this->pagesize);
        for ($i = 1; $i <= $job_page_num; $i++) {
            $result = $sitemap->addItem('/sitemap/job_' . $i . '.xml', '0.9', 'always');
            if ($result === false) {
                return false;
            }
            $result = $sitemap_class->makeJobXml($i, 'job');
            if ($result === false) {
                return false;
            }
        }
        /**
         * 生成首页导航
         */
        $result = $sitemap->addItem('/sitemap/page.xml', '1.0', 'always');
        if ($result === false) {
            return false;
        }
        $sitemap_class->makeJobXml(0, 'page');
        $sitemap->endSitemap();
    }
}