<?php
/**
 * 百度百聘
 *
 * @author
 */

namespace app\common\lib;

class Baiduxml
{
    private $_error = '';
    private $pagesize = 10000;
    private $baseDirName = 'baiduxml';
    private $global;
    public function __construct()
    {
        $this->global = config('global_config');
    }
    /**
     * 调用更新xml文件
     */
    public function update(){
        if(is_dir(PUBLIC_PATH . $this->baseDirName)){
            rmdirs(PUBLIC_PATH . $this->baseDirName);
        }
        $total = model('JobSearchRtime')->count();
        $pageNum = ceil($total/$this->pagesize);
        for ($i=1; $i <= $pageNum; $i++) { 
            $result = $this->makeXml($i);
            if($result===false){
                return false;
            }
        }
        return $this->makeXmlIndex($pageNum);
    }
    /**
     * 删除职位
     */
    public function delete($id){
        $value = model('Job')->where('id',$id)->find();
        if($value===null){
            return true;
        }
        $access_token = '';
        $resource_id = '';
        //接口地址问百度要
        $url = 'http://post.kg.baidu.com/sitemap?site='.$this->global['sitedomain'].$this->global['sitedir'].'&resource_name=jobs_expand&access_token='.$access_token.'&resource_id='.$resource_id.'&submit=1&user_method=1&user=10002';
        $xml = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<urlset>';
        $xml .= '<url>';
        $xml .= '<loc>'.url('index/job/show',['id'=>$value['id']]).'?sid=aladingb</loc>';
        $xml .= '<lastmod>'.date('Y-m-d',$value['refreshtime']).'</lastmod>';
        $xml .= '<changefreq>always</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '<data>';
        $xml .= '<display>';
        $xml .= '<wapurl>'.$this->global['mobile_domain'].'job/'.$value['id'].'?sid=aladingb</wapurl>';
        $xml .= '<title>'.$value['jobname'].'</title>';
        if ($value['age_na'] == 1) {
            $xml .= '<age>不限</age>';
        } else if ($value['minage'] > 0 || $value['maxage'] > 0) {
            $xml .= '<age>'.$value['minage'] . '-' . $value['maxage'].'</age>';
        }
        $xml .= '<description>'.cut_str(strip_tags(htmlspecialchars_decode($value['content'],ENT_QUOTES)),100).'</description>';
        $xml .= '<education>'.(isset(model('BaseModel')->map_education[$value['education']])? model('BaseModel')->map_education[$value['education']]:'不限').'</education>';
        $xml .= '<experience>'.(isset(model('BaseModel')->map_experience[$value['experience']])? model('BaseModel')->map_experience[$value['experience']]:'不限').'</experience>';
        $xml .= '<startdate>'.date('Y-m-d',$value['addtime']).'</startdate>';
        $xml .= '<enddate>'.date('Y-m-d',$value['refreshtime']+3600*24*30).'</enddate>';
        $xml .= '<salary>'.$this->formatWage($value['maxwage'],$value['negotiable']).'</salary>';
        if(isset($category_district_data[$value['district2']])){
            $xml .= '<city>'.$category_district_data[$value['district2']].'</city>';
        }else{
            $xml .= '<city>北京市</city>';
        }
        if(isset($category_district_data[$value['district3']])){
            $xml .= '<district>'.$category_district_data[$value['district3']].'</district>';
        }else{
            $xml .= '<district>不限</district>';
        }
        $xml .= '<location>'.($value['address']?$value['address']:'未知地址').'</location>';
        $xml .= '<type>'.(isset(model('Job')->map_nature[$value['nature']]) ? model('Job')->map_nature[$value['nature']] : '全职').'</type>';
        $xml .= '<officialname>'.$value['companyname'].'</officialname>';
        $xml .= '<commonname>'.$value['short_name'].'</commonname>';
        $xml .= '<logo>'.make_file_url($value['logo_save_path'], $value['logo_platform']).'</logo>';
        $xml .= '<companyaddress>'.($value['company_address']?$value['company_address']:'未知地址').'</companyaddress>';
        $xml .= '<employertype>'.$this->formatCompanyNature(isset($category_data['QS_company_type'][$value['company_nature']]) ? $category_data['QS_company_type'][$value['company_nature']] : '').'</employertype>';
        $xml .= '<size></size>';
        $xml .= '<companydescription></companydescription>';
        $xml .= '<industry>'.(isset($category_data['QS_trade'][$value['trade']]) ? $category_data['QS_trade'][$value['trade']] : '').'</industry>';
        $xml .= '<secondindustry>'.(isset($category_data['QS_trade'][$value['trade']]) ? $category_data['QS_trade'][$value['trade']] : '').'</secondindustry>';
        $xml .= '<companyID>'.$value['company_id'].'</companyID>';
        $xml .= '<source>'.$this->global['sitename'].'</source>';
        $xml .= '<sourcelink>'.$this->global['sitedomain'].$this->global['sitedir'].'</sourcelink>';
        $xml .= '</display>';
        $xml .= '</data>';
        $xml .= '</url>';
        $xml .= '</urlset>';
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml; charset=utf-8"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        if(curl_errno($ch))
        {
            $this->_error = curl_error($ch);
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        // $result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        return true;
    }
    /**
     * 生成索引文件
     */
    private function makeXmlIndex($pageNum){
        $timestamp = date('Y-m-d');
        $xml = '<?xml version="1.0" encoding="utf-8" ?>';
        $xml .= '<sitemapindex>';
        for ($i=1; $i <= $pageNum; $i++) { 
            $xml .= '<sitemap>';
            $xml .= '<loc>'.$this->global['sitedomain'].$this->global['sitedir'].'baiduxml/'.$i.'.xml</loc>';
            $xml .= '<lastmod>'.$timestamp.'</lastmod>';
            $xml .= '</sitemap>';
        }
        $xml .= '</sitemapindex>';
        return $this->fileWrite('index.xml',$xml);
    }
    /**
     * 生成职位xml文件
     */
    private function makeXml($page){
        $offset = ($page-1)*$this->pagesize;
        $list = model('Job')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'company b',
                    'a.company_id=b.id',
                    'LEFT'
                )
                ->join(
                    config('database.prefix') . 'uploadfile c',
                    'b.logo=c.id',
                    'LEFT'
                )
                ->join(
                    config('database.prefix') . 'company_info d',
                    'b.id=d.comid',
                    'LEFT'
                )
                ->field('d.address as company_address,b.nature as company_nature,b.trade,a.id,a.company_id,a.refreshtime,a.jobname,a.category1,a.category2,a.category3,a.amount,a.age_na,a.minage,a.maxage,a.content,a.education,a.experience,a.addtime,a.refreshtime,a.maxwage,a.negotiable,a.district1,a.district2,a.district3,a.address,a.nature,b.companyname,b.short_name,c.save_path as logo_save_path,c.platform as logo_platform')
                ->where('a.audit',1)
                ->where('a.is_display',1)
                ->order('a.id desc')
                ->limit($offset,$this->pagesize)
                ->select();
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache('all');
        $category_district_data = model('CategoryDistrict')->getCache('all');
        $xml = '<?xml version="1.0" encoding="utf-8" ?>';
        $xml .= '<urlset>';
        foreach ($list as $key => $value) {
            $xml .= '<url>';
            $xml .= '<loc>'.url('index/job/show',['id'=>$value['id']]).'?sid=aladingb</loc>';
            $xml .= '<lastmod>'.date('Y-m-d',$value['refreshtime']).'</lastmod>';
            $xml .= '<changefreq>always</changefreq>';
            $xml .= '<priority>1.0</priority>';
            $xml .= '<data>';
            $xml .= '<display>';
            $xml .= '<wapurl>'.$this->global['mobile_domain'].'job/'.$value['id'].'?sid=aladingb</wapurl>';
            $xml .= '<title>'.$value['jobname'].'</title>';
            if(isset($category_job_data[$value['category1']])){
                $xml .= '<jobfirstclass>'.$category_job_data[$value['category1']].'</jobfirstclass>';
            }
            if(isset($category_job_data[$value['category2']])){
                $xml .= '<jobsecondclass>'.$category_job_data[$value['category2']].'</jobsecondclass>';
            }
            if(intval($value['amount'])>0){
                $xml .= '<number>'.$value['amount'].'人</number>';
            }
            if ($value['age_na'] == 1) {
                $xml .= '<age>不限</age>';
            } else if ($value['minage'] > 0 || $value['maxage'] > 0) {
                $xml .= '<age>'.$value['minage'] . '-' . $value['maxage'].'</age>';
            }
            $xml .= '<description>'.cut_str(strip_tags(htmlspecialchars_decode($value['content'],ENT_QUOTES)),100).'</description>';
            $xml .= '<education>'.(isset(model('BaseModel')->map_education[$value['education']])? model('BaseModel')->map_education[$value['education']]:'不限').'</education>';
            $xml .= '<experience>'.(isset(model('BaseModel')->map_experience[$value['experience']])? model('BaseModel')->map_experience[$value['experience']]:'不限').'</experience>';
            $xml .= '<startdate>'.date('Y-m-d',$value['addtime']).'</startdate>';
            $xml .= '<enddate>'.date('Y-m-d',$value['refreshtime']+3600*24*30).'</enddate>';
            $xml .= '<salary>'.$this->formatWage($value['maxwage'],$value['negotiable']).'</salary>';
            if(isset($category_district_data[$value['district2']])){
                $xml .= '<city>'.$category_district_data[$value['district2']].'</city>';
            }else{
                $xml .= '<city>北京市</city>';
            }
            if(isset($category_district_data[$value['district3']])){
                $xml .= '<district>'.$category_district_data[$value['district3']].'</district>';
            }else{
                $xml .= '<district>不限</district>';
            }
            $xml .= '<location>'.($value['address']?$value['address']:'未知地址').'</location>';
            $xml .= '<type>'.(isset(model('Job')->map_nature[$value['nature']]) ? model('Job')->map_nature[$value['nature']] : '全职').'</type>';
            if(isset($category_job_data[$value['category3']])){
                $xml .= '<jobthirdclass>'.$category_job_data[$value['category3']].'</jobthirdclass>';
            }
            $xml .= '<officialname>'.$value['companyname'].'</officialname>';
            $xml .= '<commonname>'.$value['short_name'].'</commonname>';
            $xml .= '<logo>'.make_file_url($value['logo_save_path'], $value['logo_platform']).'</logo>';
            $xml .= '<companyaddress>'.($value['company_address']?$value['company_address']:'未知地址').'</companyaddress>';
            $xml .= '<employertype>'.$this->formatCompanyNature(isset($category_data['QS_company_type'][$value['company_nature']]) ? $category_data['QS_company_type'][$value['company_nature']] : '').'</employertype>';
            $xml .= '<size></size>';
            $xml .= '<companydescription></companydescription>';
            $xml .= '<industry>'.(isset($category_data['QS_trade'][$value['trade']]) ? $category_data['QS_trade'][$value['trade']] : '').'</industry>';
            $xml .= '<secondindustry>'.(isset($category_data['QS_trade'][$value['trade']]) ? $category_data['QS_trade'][$value['trade']] : '').'</secondindustry>';
            $xml .= '<companyID>'.$value['company_id'].'</companyID>';
            $xml .= '<source>'.$this->global['sitename'].'</source>';
            $xml .= '<sourcelink>'.$this->global['sitedomain'].$this->global['sitedir'].'</sourcelink>';
            $xml .= '</display>';
            $xml .= '</data>';
            $xml .= '</url>';
        }
        $xml .= '</urlset>';
        $result = $this->fileWrite($page.'.xml',$xml);
        unset($list,$category_data,$category_district_data,$category_job_data,$xml);
        return $result;
    }
    /**
     * 格式化企业性质
     */
    private function formatCompanyNature($nature){
        $all = ['民营','国企','股份制','外商独资/办事处','中外合资/合作','上市公司','事业单位','政府机关','个人企业','其他'];
        foreach ($all as $key => $value) {
            if(stripos($nature,$value)!==false || stripos($value,$nature)!==false){
                return $all[$key];
            }
        }
        return '其他';
    }
    /**
     * 格式化薪资
     */
    private function formatWage($max,$negotiable){
        if($negotiable==1){
            return '面议';
        }
        if($max<1000){
            return '1000元以下';
        }
        if($max>25000){
            return '25000以上';
        }else if($max>20000){
            return '20000-25000';
        }else if($max>12000){
            return '12000-20000';
        }else if($max>8000){
            return '8000-12000';
        }else if($max>5000){
            return '5000-8000';
        }else if($max>3000){
            return '3000-5000';
        }else if($max>2000){
            return '2000-3000';
        }else{
            return '1000-2000';
        }
    }
    /**
     * 生成文件方法
     */
    private function fileWrite($filename,$content){
        $dir = PUBLIC_PATH . $this->baseDirName . '/';
        if(!is_dir($dir)){
            mkdir($dir,0777);
        }
        if(file_exists($dir.$filename) && !is_writable($dir.$filename)){
            $this->_error = $dir.$filename.'没有写入权限';
            return false; 
        }
        file_put_contents($dir.$filename,$content);
    }
    public function getError()
    {
        return $this->_error;
    }
}
