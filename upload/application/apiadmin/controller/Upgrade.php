<?php
namespace app\apiadmin\controller;

use EasyZip\ZipHandle;
use app\common\lib\Download;

class Upgrade extends \app\common\controller\Backend
{
    protected $api = 'https://www.74cms.com/plusse';
    protected $save_dir = '';
    protected $unzip_dir = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->save_dir = PUBLIC_PATH.'upgrade/zip/';
        $this->unzip_dir = PUBLIC_PATH.'upgrade/unzip/';
    }

    public function newVersionList()
    {
        $current_version = config('version.version');
        $edition_en = config('version.edition_en');
        $ver = explode(".",$current_version);
        $version_int_1 = str_pad($ver[0], 2, '0', STR_PAD_LEFT);
        $version_int_2 = str_pad($ver[1], 2, '0', STR_PAD_LEFT);
        $version_int_3 = str_pad($ver[2], 3, '0', STR_PAD_LEFT);
        $current_version_int = $version_int_1 . $version_int_2 . $version_int_3;
        $url = $this->api.'/getVersionList?version='.$current_version_int.'&edition='.$edition_en;
        $result = https_request($url);
        if(false === $result){
            $this->ajaxReturn(500, 'http请求数据失败');
        }
        $result = json_decode($result,true);
        if($result['code']==500){
            $this->ajaxReturn(500, $result['message']);
        }
        $latest_version = '';
        foreach ($result['data'] as $key=>$value){
            if($key==0){
                $result['data'][$key]['enable'] = 1;
            }else{
                $result['data'][$key]['enable'] = 0;
            }
            $result['data'][$key]['size_show'] = $this->getsize($value['size']);
            $result['data'][$key]['version_text'] = '';
            if(ltrim($value['version_int_1'],'0') != ''){
                $result['data'][$key]['version_text'] .= ltrim($value['version_int_1'],'0').'.';
            }else{
                $result['data'][$key]['version_text'] .= '0.';
            }
            if(ltrim($value['version_int_2'],'0') != ''){
                $result['data'][$key]['version_text'] .= ltrim($value['version_int_2'],'0').'.';
            }else{
                $result['data'][$key]['version_text'] .= '0.';
            }
            if(ltrim($value['version_int_3'],'0') != ''){
                $result['data'][$key]['version_text'] .= ltrim($value['version_int_3'],'0');
            }else{
                $result['data'][$key]['version_text'] .= '0';
            }
            $latest_version = $result['data'][$key]['version_text'];
        }

        $return['items'] = $result['data'];
        $return['latest_version'] = $latest_version;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function startup()
    {
        $domain = input('get.domain/s', '', 'trim');
        $current_version = config('version.version');
        $ver = explode(".",$current_version);
        $version_int_1 = str_pad($ver[0], 2, '0', STR_PAD_LEFT);
        $version_int_2 = str_pad($ver[1], 2, '0', STR_PAD_LEFT);
        $version_int_3 = str_pad($ver[2], 3, '0', STR_PAD_LEFT);
        $current_version_int = $version_int_1 . $version_int_2 . $version_int_3;
        $edition_en = config('version.edition_en');

        $url = $this->api.'/getPackageUrl?domain='.$domain.'&version='.$current_version_int.'&edition='.$edition_en;
        $result = https_request($url);
        if(false === $result){
            $this->ajaxReturn(500, 'http请求数据失败');
        }
        $result = json_decode($result,true);
        if($result['code']==500){
            $this->ajaxReturn(500, $result['message']);
        }
        $path = $result['data'];  //需要下载的文件目录+文件名
        $this->ajaxReturn(200, '开始更新',['path'=>urlencode($path),'timestamp'=>time()]);
    }
    public function download()
    {
        $timestamp = input('get.timestamp/s', '', 'trim');
        $path = input('get.path/s', '', 'trim,urldecode');
        $filename = basename($path);
        $dirname = str_replace(strrchr($filename, '.'), '', $filename);
        $name = $dirname.'_'.$timestamp.'.zip';
        $save_dir = $this->save_dir.$dirname.'/';
        $unzip_dir = $this->unzip_dir.$dirname.'/';
        if(!is_dir($save_dir)){
            if(is_writeable($save_dir)){

            }
            mkdir($save_dir, 0777,true);
            chmod($save_dir,0777);
        }else{
            if(file_exists($save_dir)){
                rmdirs($save_dir);
            }
        }
        if(!is_dir($unzip_dir)){
            mkdir($unzip_dir, 0777,true);
            chmod($unzip_dir,0777);
        }else{
            if(file_exists($unzip_dir)){
                rmdirs($unzip_dir);
            }
        }
        $save_file = $save_dir.$name;
        try{
            $file = new Download();
            $file->setSpeed(100);
            $file->download($path, $save_file,false);
        }catch(\Exception $e){
            $this->ajaxReturn(200, '下载失败，没有找到对应的升级包',500);
        }

        $fp = @fopen($save_dir.'finish.lock', 'wb+');
        fwrite($fp, 'OK');
        fclose($fp);

        $this->ajaxReturn(200, '下载完成',200);
    }
    /**
     * 获取下载进度
     */
    public function speedProgress(){
        $timestamp = input('get.timestamp/s', '', 'trim');
        $path = input('get.path/s', '', 'trim,urldecode');
        $path_size = input('get.size/d', 0, 'intval');
        $filename = basename($path);
        $dirname = str_replace(strrchr($filename, '.'), '', $filename);
        $save_dir = $this->save_dir.$dirname.'/';
        $name = $dirname.'_'.$timestamp.'.zip';
        $save_file = $save_dir.$name;
        if(!file_exists($save_file)){
            $this->ajaxReturn(200, '下载进度',0);
        }
        $save_file_size =intval(filesize($save_file));
        $couter = $save_file_size/$path_size;
        $progress = floatval(sprintf("%.2f",round($couter,2)));
        $nums = $progress*80;
        if($nums<80 && !file_exists($save_dir.'finish.lock')){
            $nums = 79;
        }
        $this->ajaxReturn(200, '升级进度',$nums);
    }
    /**
     * 解压
     */
    public function unzip(){
        $timestamp = input('get.timestamp/s', '', 'trim');
        $path = input('get.path/s', '', 'trim,urldecode');
        $filename = basename($path);
        $dirname = str_replace(strrchr($filename, '.'), '', $filename);
        $save_dir = $this->save_dir.$dirname.'/';
        $unzip_dir = $this->unzip_dir.$dirname.'/';
        $name = $dirname.'_'.$timestamp.'.zip';
        $save_file = $save_dir.$name;
        //解压
        $unzip_dir_name = ZipHandle::unzip($save_file,$unzip_dir.'file');//解压文件
        $cover_dir = $unzip_dir_name.'/cover';
        if(!is_dir($cover_dir)){
            $this->ajaxReturn(500, '解压失败');
        }
        $fp = @fopen($unzip_dir.'finish.lock', 'wb+');
        fwrite($fp, 'OK');
        fclose($fp);
        $this->ajaxReturn(200, '解压完成');

    }

    /**
     * 解压并更新代码
     */
    public function update(){
        $path = input('get.path/s', '', 'trim,urldecode');
        $filename = basename($path);
        $dirname = str_replace(strrchr($filename, '.'), '', $filename);
        $unzip_dir = $this->unzip_dir.$dirname.'/file';
        $mysql_file = $unzip_dir.'/upgrade/index.php';
        //判断是否有mysql执行文件，有的话需要执行sql
        if(is_file($mysql_file)){
            include($mysql_file);
            $run_result = mysql_run(config('database'));
            if($run_result['err']==1){
                $this->ajaxReturn(500, 'sql升级失败');
            }
        }
        $cover_dir = $unzip_dir.'/cover';
        if(!is_dir($cover_dir)){
            $this->ajaxReturn(500, '覆盖文件失败，没有检测到覆盖包文件');
        }
        //如果覆盖包里包含admin文件夹，检查是否需要把admin替换成别的名称
        $admin_dirname = $this->getCurrentAdminDirname();
        if(is_dir($cover_dir.'/public/admin')){
            if($admin_dirname!='admin'){
                rename($cover_dir.'/public/admin',$cover_dir.'/public/'.$admin_dirname);
            }
        }

        //覆盖cover文件
        $num = $this->copy_merge($cover_dir,PUBLIC_PATH.'../');
        //清空压缩包目录和解压目录
        rmdirs($this->save_dir);
        rmdirs($this->unzip_dir);
        $this->ajaxReturn(200, '升级完成，共更新'.$num.'个文件');
    }

    /**
     * 获取当前admin目录的目录名
     * @return mixed|string
     */
    protected function getCurrentAdminDirname(){
        //定义后台目录名变量
        $admin_dirname = 'admin';
        //列出白名单目录
        $dir_white_list = [
            'assets',
            'baiduxml',
            'adminm',
            'install',
            'static',
            'tpl',
            'upload'
        ];
        // 扫描$con目录下的所有文件
        $public_dirname_list = scandir(PUBLIC_PATH);
        foreach($public_dirname_list as $k=>$v){
            if($v!="." && $v!=".." && is_dir($v) && !in_array($v,$dir_white_list)){
                $child_dir_list = scandir(PUBLIC_PATH.$v);
                if(in_array('.marker',$child_dir_list)){
                    $admin_dirname = $v;
                    break;
                }
                if(in_array('ueditor',$child_dir_list)){
                    $admin_dirname = $v;
                    break;
                }
            }
        }
        //在admin目录下生成标识
        if(!file_exists(PUBLIC_PATH.$admin_dirname.'/.marker')){
            file_put_contents(PUBLIC_PATH.$admin_dirname.'/.marker','');
        }
        return $admin_dirname;
    }

    /**
     * 合并目录
     * @param $source
     * @param $target
     * @return int 处理的文件数7a686964616fe78988e69d8331333332643261
     */
    protected function copy_merge($source, $target) {
        // 路径处理
        $source = preg_replace ( '#/\\\\#', DIRECTORY_SEPARATOR, $source );
        $target = preg_replace ( '#\/#', DIRECTORY_SEPARATOR, $target );
        $source = rtrim ( $source, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
        $target = rtrim ( $target, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
        // 记录处理了多少文件
        $count = 0;
        // 如果目标目录不存在，则创建。
        if (! is_dir ( $target )) {
            mkdir ( $target, 0777, true );
            chmod($target,0777);
            $count ++;
        }
        // 搜索目录下的所有文件
        foreach ( glob ( $source . '*' ) as $filename ) {
            if (is_dir ( $filename )) {
                // 如果是目录，递归合并子目录下的文件。
                $count += $this->copy_merge ( $filename, $target . basename ( $filename ) );
            } elseif (is_file ( $filename )) {
                // 如果是文件，判断当前文件与目标文件是否一样，不一样则拷贝覆盖。
                // 这里使用的是文件md5进行的一致性判断，可靠但性能低，应根据实际情况调整。
                if (! file_exists ( $target . basename ( $filename ) ) || md5 ( file_get_contents ( $filename ) ) != md5 ( file_get_contents ( $target . basename ( $filename ) ) )) {
                    copy ( $filename, $target . basename ( $filename ) );
                    $count ++;
                }
            }
        }
        // 返回处理了多少个文件
        return $count;
    }
    protected function getsize($size)
    {
        $mb_size=1024*1024;
        $kb_size=1024;
        if ($size>=$mb_size)
        {
            $s[0]=$size/$mb_size;
            $s[0]=round($s[0],2);
            $s[1]='MB';
            return $s;
        }
        else
        {
            $s[0]=$size/$kb_size;
            $s[0]=round($s[0],0);
            $s[1]='KB';
            return $s;
        }
    }
}
