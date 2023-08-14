<?php

namespace app\common\lib\sitemap;

class Sitemap
{
    private $pagesize = 500;
    public function makeJobXml($page,$type){
        $offset = ($page-1)*$this->pagesize;
        switch ($type){
            case 'job':
                $list = model('Job')
                    ->order('id desc')
                    ->limit($offset,$this->pagesize)
                    ->select();
                break;
            case 'company':
                $list = model('Company')
                    ->order('id desc')
                    ->select();
                break;
            case 'page':
                $list = model('navigation')
                    ->where('is_display',1)
                    ->where('page','<>','')
                    ->select();
                foreach ($list as $k=>$v){
                    if($v['page'] == 'fastjob'){
                        $list[$k]['page'] = 'fast/job';
                    }
                    if($v['page'] == 'fastresume'){
                        $list[$k]['page'] = 'fast/resume';
                    }
                    if($v['page'] == 'job_register'){
                        $list[$k]['page'] = 'job/register';
                    }
                    if($v['page'] == 'daily'){
                        $list[$k]['page'] = 'dailyList';
                    }
                    if($v['page'] == 'daily'){
                        $list[$k]['page'] = 'dailyList';
                    }
                    if($v['page'] == 'videoRecruitment'){
                        $list[$k]['page'] = 'shortvideo';
                    }
                }
                break;
        }
        $xml = '<?xml version="1.0" encoding="utf-8" ?>';
        $xml .= '<urlset>';
        foreach ($list as $key => $value) {
            $xml .= '<url>';
            if($type == 'job'){
                $xml .= '<loc>'.url('index/job/show',['id'=>$value['id']]).'</loc>';
            }
            if($type == 'company'){
                $xml .= '<loc>'.url('index/company/show',['id'=>$value['id']]).'</loc>';
            }
            if($type == 'page'){
                $xml .= '<loc>'.config('global_config.sitedomain').'/'.$value['page'].'.html</loc>';
            }else{
                $xml .= '<lastmod>'.date('Y-m-d',$value['refreshtime']).'</lastmod>';
            }
            $xml .= '<changefreq>always</changefreq>';
            if($type == 'page'){
                $xml .= '<priority>1.0</priority>';
            }else{
                 $xml .= '<priority>0.8</priority>';
            }
            $xml .= '</url>';
        }
        $xml .= '</urlset>';
        if($type == 'page'){
            $result = $this->fileWrite($type.'.xml',$xml);
        }else{
            $result = $this->fileWrite($type.'_'.$page.'.xml',$xml);
        }
        unset($list,$category_data,$category_district_data,$category_job_data,$xml);
        return $result;
    }
    /**
     * 生成文件方法
     */
    private function fileWrite($filename,$content){
        $dir =  './sitemap/';
        if(!is_dir($dir)){
            mkdir($dir,0755);
        }
        if(file_exists($dir.$filename) && !is_writable($dir.$filename)){
            $this->_error = $dir.$filename.'没有写入权限';
            return false;
        }
        file_put_contents($dir.$filename,$content);
    }
}