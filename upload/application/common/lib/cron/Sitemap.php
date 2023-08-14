<?php

namespace app\common\lib\cron;

use app\common\lib\sitemap\Mysitemap;

class Sitemap
{
    private $pagesize = 500;

    public function execute()
    {
        $sitemap = new Mysitemap(config('global_config.sitedomain'));
        $sitemap_class = new \app\common\lib\sitemap\Sitemap();
        /**
         * 生成企业xml地址
         */
        $total = model('Company')->order('id desc')->count();
        $company_page_num = ceil($total / $this->pagesize);
        for ($i = 1; $i <= $company_page_num; $i++) {
            $result = $sitemap->addItem('/stimap/company_' . $i . '.xml', '0.9', 'always');
            if ($result === false) {
                return false;
            }
        }
        /**
         * 生成职位xml地址
         */
        $total = model('JobSearchRtime')->count();
        $job_page_num = ceil($total / $this->pagesize);
        for ($i = 1; $i <= $job_page_num; $i++) {
            $result = $sitemap->addItem('/stimap/job_' . $i . '.xml', '0.9', 'always');
            if ($result === false) {
                return false;
            }
        }
        /**
         * 生成首页导航
         */
        $result = $sitemap->addItem('/stimap/page.xml', '1.0', 'always');
        if ($result === false) {
            return false;
        }
        for ($i = 1; $i <= $company_page_num; $i++) {
            $result = $sitemap_class->makeJobXml($i, 'company');
            if ($result === false) {
                return false;
            }
        }
        for ($i = 1; $i <= $job_page_num; $i++) {
            $result = $sitemap_class->makeJobXml($i, 'job');
            if ($result === false) {
                return false;
            }
        }
        $navigation = model('navigation')
            ->where('is_display', 1)
            ->where('page', '<>', '')
            ->count();
        $sitemap_class->makeJobXml($navigation, 'page');
        $sitemap->endSitemap();
    }
}