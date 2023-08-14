<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function _initialize() {
        if(file_exists(MAIN_PROJECT_PATH.'public/upload/install.lock') && ACTION_NAME!='step4')
		{
			exit('您已经安装过本系统，如果想重新安装，请删除public/upload目录下install.lock文件');
		}
		$this->assign('assets_path',__ROOT__.'/install/Public');
	}
	protected function ajaxReturn($code = 200, $message = '', $data = [])
    {
        $return = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
	}
	
    protected function checkDirs($dirs,&$error)
	{
	    $checked_dirs = array();
	    foreach ($dirs AS $k=> $dir)
	    {
			$checked_dirs[$k]['name'] = $dir;
	        if (!file_exists(MAIN_PROJECT_PATH.$dir))
	        {
	            $checked_dirs[$k]['read'] = '×目录不存在';
				$checked_dirs[$k]['write'] = '×目录不存在';
				$checked_dirs[$k]['read_pass'] = 0;
				$checked_dirs[$k]['write_pass'] = 0;
				!$error && $error = 1;
	        }
			else
			{		
	        if (is_readable(MAIN_PROJECT_PATH.$dir))
	        {
	            $checked_dirs[$k]['read'] = '√可读';
				$checked_dirs[$k]['read_pass'] = 1;
	        }else{
	            $checked_dirs[$k]['read'] = '×不可读';
				$checked_dirs[$k]['read_pass'] = 0;
				!$error && $error = 1;
	        }
	        if(is_writable(MAIN_PROJECT_PATH.$dir)){
	        	$checked_dirs[$k]['write'] = '√可写';
				$checked_dirs[$k]['write_pass'] = 1;
	        }else{
	        	$checked_dirs[$k]['write'] = '×不可写';
				$checked_dirs[$k]['write_pass'] = 0;
				!$error && $error = 1;
	        }
			}
	    }
	    return $checked_dirs;
	}
    protected function getNeedCheckDirs(){
    	return [
    		'/runtime/',
			'/public/upload/',
			'/public/baiduxml/',
			'/public/admin/static/config.js',
            '/public/adminm/static/config.js',
			'/public/tpl/member/static/config.js',
			'/public/tpl/mobile/static/config.js',
    		'/application/database.php',
    		'/application/extra/sys.php',
        ];
    }
    public function index(){
    	$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    	$location = strstr($php_self,'install.php');
    	$site_dir = str_replace($location, "", $php_self);
    	$this->display();
    }
    public function step1(){
    	$check_env = [
            [
                'name'=>'操作系统',
                'current'=>PHP_OS,
                'require'=>'LINUX',
                'check_pass'=>1
            ],
            [
                'name'=>'WEB环境',
                'current'=>$_SERVER['SERVER_SOFTWARE'],
                'require'=>'nginx',
                'check_pass'=>1
            ],
            [
                'name'=>'PHP版本',
                'current'=>PHP_VERSION,
                'require'=>'5.5及以上',
                'check_pass'=>version_compare(PHP_VERSION, '5.5.0', '>=')?1:0
            ],
            [
                'name'=>'MYSQL版本',
                'current'=>'-',
                'require'=>'5.7.6及以上',
                'check_pass'=>1
            ],
        ];
        $check_ext = [
            [
                'name'=>'php_curl',
                'check_pass'=>extension_loaded('curl')?1:0
            ],
            [
                'name'=>'php_gd2',
                'check_pass'=>extension_loaded('gd')?1:0
            ],
            [
                'name'=>'php_mbstring',
                'check_pass'=>extension_loaded('mbstring')?1:0
            ],
            [
                'name'=>'php_openssl',
                'check_pass'=>extension_loaded('openssl')?1:0
            ],
            [
                'name'=>'php_pdo_mysql',
                'check_pass'=>extension_loaded('pdo_mysql')?1:0
            ],
            [
                'name'=>'php_mysqli',
                'check_pass'=>extension_loaded('mysqli')?1:0
            ],
        ];
        
        $check_ext = [
            [
                'name'=>'php_curl',
                'check_pass'=>extension_loaded('curl')?1:0
            ],
            [
                'name'=>'php_gd2',
                'check_pass'=>extension_loaded('gd')?1:0
            ],
            [
                'name'=>'php_mbstring',
                'check_pass'=>extension_loaded('mbstring')?1:0
            ],
            [
                'name'=>'php_openssl',
                'check_pass'=>extension_loaded('openssl')?1:0
            ],
            [
                'name'=>'php_pdo_mysql',
                'check_pass'=>extension_loaded('pdo_mysql')?1:0
            ],
            [
                'name'=>'php_mysqli',
                'check_pass'=>extension_loaded('mysqli')?1:0
            ],
		];
        $error = 0;
        foreach ($check_env as $key => $value) {
            if($value['check_pass']==0){
                $error = 1;
                break;
            }
        }
        if($error==0){
            foreach ($check_ext as $key => $value) {
                if($value['check_pass']==0){
                    $error = 1;
                    break;
                }
            }
        }
		$need_check_dirs = $this->getNeedCheckDirs();
        $check_dir = $this->checkDirs($need_check_dirs,$error);
        $this->assign('check_env',$check_env);
        $this->assign('check_ext',$check_ext);
        $this->assign('check_dir',$check_dir);
        $this->assign('error',$error);
    	$this->display();
    }
    public function step2(){
    	$this->display();
	}
	public function test(){
        $host = I('post.host/s','','trim');
        $port = I('post.port/s','','trim');
        $dbuser = I('post.dbuser/s','','trim');
        $dbpwd = I('post.dbpwd/s','','trim');
        $dbname = I('post.dbname/s','','trim');
        $dbprefix = I('post.dbprefix/s','','trim');
        $cover = I('post.cover/d',1,'intval');
        try{
            //非3306端口安装修改 tapd:197
            $conn = mysqli_connect($host, $dbuser, $dbpwd, $dbname, $port);
            if(!$conn){
                throw new \Exception('连接数据库错误，请核对信息是否正确');
            }
            if($cover==0){
                if(mysqli_select_db($conn,$dbname)){
                    throw new \Exception('该数据库已存在，如需覆盖请选择覆盖数据库');
                }
            }
        }catch(\Exception $e){
            $this->ajaxReturn(500,$e->getMessage());
        }
        $this->ajaxReturn(200,'连接成功');
    }
    public function step3(){
        ini_set('max_execution_time','0');
    	$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $location = strstr($php_self,'install.php');
        $site_dir = str_replace($location, "", $php_self);
		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
		$site_domain = $http_type.$_SERVER['HTTP_HOST'];
		session('site_dir',$site_dir);
		session('site_domain',$site_domain);
        $dbhost = isset($_POST['host']) ? trim($_POST['host']) : '';
        $dbname = isset($_POST['dbname']) ? trim($_POST['dbname']) : '';
        $dbuser = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : '';
        $dbpass = isset($_POST['dbpwd']) ? trim($_POST['dbpwd']) : '';
        $dbport = isset($_POST['port']) ? intval($_POST['port']) : 3306;
        $pre  = isset($_POST['dbprefix']) ? trim($_POST['dbprefix']) : QSCMS_PRE;
        $cover = isset($_POST['cover']) ? intval($_POST['cover']) : 1;
        $dbcharset = 'utf8mb4';
        $admin_name = isset($_POST['admin_name']) ? trim($_POST['admin_name']) : '';
        $admin_pwd = isset($_POST['admin_pwd']) ? trim($_POST['admin_pwd']) : '';
        $admin_pwd1 = isset($_POST['admin_pwd1']) ? trim($_POST['admin_pwd1']) : '';
        if($dbhost == '' || $dbname == ''|| $dbuser == ''|| $admin_name == ''|| $admin_pwd == '' || $admin_pwd1 == '')
        {
			$this->error('您填写的信息不完整，请核对');
        }
        if($admin_pwd != $admin_pwd1)
        {
			$this->error('您两次输入的密码不一致');
        }
        try{
            $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname, $dbport);
            if(!$conn){
                throw new \Exception('连接数据库错误，请核对信息是否正确');
            }
            if($cover==0){
                if(mysqli_select_db($conn,$dbname)){
                    throw new \Exception('该数据库已存在，如需覆盖请选择覆盖数据库');
                }
            }
            
        }catch(\Exception $e){
			$this->error($e->getMessage());
        }
        if (mysqli_get_server_info($conn)<5.7) {
			$this->error('安装失败，请使用mysql5.7.6及以上版本');
        }
		session('admin_name',$admin_name);
		session('admin_pwd',$admin_pwd);
    	$this->display();
        mysqli_query($conn,"START TRANSACTION");
        try{
            mysqli_query($conn,"CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET ".$dbcharset);
            if(!mysqli_select_db($conn,$dbname))
            {
                throw new \Exception('选择数据库错误，请检查是否拥有权限或存在此数据库');
            }
            mysqli_query($conn,"SET NAMES '".$dbcharset."',character_set_client=binary,sql_mode='';");
            ob_end_clean();
            $html ="";
            $html.= "<script type=\"text/javascript\">\n";
            $html.= "$('#installing').append('<p>数据库创建成功！...</p>');\n";
            $html.= "var div = document.getElementById('installing');";
            $html.= "div.scrollTop = div.scrollHeight;";
            $html.= "</script>";
            echo $html;
            ob_flush();
			flush();


			$admin_config_js = file_get_contents(MAIN_PROJECT_PATH . 'public/admin/static/config.js');
			if(!$admin_config_js){
                throw new \Exception('打开配置文件失败');
			}
			$admin_config_js = str_replace("{RequestBaseUrl}",$site_domain.$site_dir,$admin_config_js);
            $fp = @fopen(MAIN_PROJECT_PATH . 'public/admin/static/config.js', 'wb+');
            if (!$fp)
            {
                throw new \Exception('打开配置文件失败');
            }
            if (!@fwrite($fp, $admin_config_js))
            {
                throw new \Exception('写入配置文件失败');
            }
			@fclose($fp);
			

			$mobile_config_js = file_get_contents(MAIN_PROJECT_PATH . 'public/tpl/mobile/static/config.js');
			if(!$mobile_config_js){
                throw new \Exception('打开配置文件失败');
			}
			$mobile_config_js = str_replace("{RequestBaseUrl}",$site_domain.$site_dir,$mobile_config_js);
            $fp = @fopen(MAIN_PROJECT_PATH . 'public/tpl/mobile/static/config.js', 'wb+');
            if (!$fp)
            {
                throw new \Exception('打开配置文件失败');
            }
            if (!@fwrite($fp, $mobile_config_js))
            {
                throw new \Exception('写入配置文件失败');
            }
			@fclose($fp);
			

			
			$member_config_js = file_get_contents(MAIN_PROJECT_PATH . 'public/tpl/member/static/config.js');
			if(!$member_config_js){
                throw new \Exception('打开配置文件失败');
			}
			$member_config_js = str_replace("{RequestBaseUrl}",$site_domain.$site_dir,$member_config_js);
            $fp = @fopen(MAIN_PROJECT_PATH . 'public/tpl/member/static/config.js', 'wb+');
            if (!$fp)
            {
                throw new \Exception('打开配置文件失败');
            }
            if (!@fwrite($fp, $member_config_js))
            {
                throw new \Exception('写入配置文件失败');
            }
            @fclose($fp);

            $adminm_config_js = file_get_contents(MAIN_PROJECT_PATH . 'public/adminm/static/config.js');
			if(!$adminm_config_js){
                throw new \Exception('打开配置文件失败');
			}
			$adminm_config_js = str_replace("{RequestBaseUrl}",$site_domain.$site_dir,$adminm_config_js);
            $fp = @fopen(MAIN_PROJECT_PATH . 'public/adminm/static/config.js', 'wb+');
            if (!$fp)
            {
                throw new \Exception('打开配置文件失败');
            }
            if (!@fwrite($fp, $adminm_config_js))
            {
                throw new \Exception('写入配置文件失败');
            }
			@fclose($fp);
			


            $safecode = randstr(16);
            $config_sys_file = MAIN_PROJECT_PATH . 'application/extra/sys.php';
            $config_sys_arr = require $config_sys_file;
            $config_sys_arr['safecode'] = $safecode;
            $content = "<?php\n";
            $content .= "return ";
            $content .= var_export($config_sys_arr,true);
            $content .= ";";
            $fp = @fopen(MAIN_PROJECT_PATH . 'application/extra/sys.php', 'wb+');
            if (!$fp)
            {
                throw new \Exception('打开配置文件失败');
            }
            if (!@fwrite($fp, $content))
            {
                throw new \Exception('写入配置文件失败');
            }
            @fclose($fp);
    
            $database_config_file = MAIN_PROJECT_PATH . 'application/database.php';
            $database_config_arr = require $database_config_file;
            $database_config_arr['type'] = 'mysql';
            $database_config_arr['hostname'] = $dbhost;
            $database_config_arr['database'] = $dbname;
            $database_config_arr['username'] = $dbuser;
            $database_config_arr['password'] = $dbpass;
            $database_config_arr['hostport'] = $dbport;
            $database_config_arr['prefix'] = $pre;
    
            $content = "<?php\n";
            $content .= "return ";
            $content .= var_export($database_config_arr,true);
            $content .= ";";
            $fp = @fopen(MAIN_PROJECT_PATH . 'application/database.php', 'wb+');
            if (!$fp)
            {
                throw new \Exception('打开配置文件失败');
            }
            if (!@fwrite($fp, trim($content)))
            {
                throw new \Exception('写入配置文件失败');
            }
			@fclose($fp);
            if(is_writable(MAIN_PROJECT_PATH.'public/upload'))
            {
                $fp = @fopen(MAIN_PROJECT_PATH.'public/upload/install.lock', 'wb+');
                fwrite($fp, 'OK');
                fclose($fp);
            }
    
    
    
    
    
    
    
            if(!$fp = @fopen(QSCMS_DATA_PATH.'sql-structure.sql','rb'))
            {
                throw new \Exception('打开文件sql-structure.sql出错，请检查文件是否存在');
            }
            $query = '';
            while(!feof($fp))
            {
                $line = rtrim(fgets($fp,1024)); 
                if(strstr($line,'||-_-||')!=false) {
                    $line = ltrim(rtrim($line,"||-_-||"),"||-_-||");
                    $line = str_replace(QSCMS_PRE,$pre,$line);
                    $html ="";
                    $html.= "<script type=\"text/javascript\">\n";
                    $html.= "$('#installing').append('<p>建立数据表 ".$line."...成功</p>');\n";
                    $html.= "var div = document.getElementById('installing');";
                    $html.= "div.scrollTop = div.scrollHeight;";
                    $html.= "</script>";
                    echo $html;
                    ob_flush();
                    flush();
                }else{
                    if(preg_match('/;$/',$line)) 
                    {
                        $query .= $line."\n";
                        $query = str_replace(QSCMS_PRE,$pre,$query);
                        mysqli_query($conn,$query);
                        $query='';
                     }
                     else if(!preg_match('/^(\/\/|--)/',$line))
                     {
                         $query .= $line;
                     }
                }
            }
            @fclose($fp);	
            $query = '';
            if(!$fp = @fopen(QSCMS_DATA_PATH.'sql-data.sql','rb'))
            {
                throw new \Exception('打开文件sql-data.sql出错，请检查文件是否存在');
            }
            while(!feof($fp))
            {
                $line = rtrim(fgets($fp,1024));
                if(preg_match('/;$/',$line)) 
                {
                    $query .= $line."\n";
                    $query = str_replace(QSCMS_PRE,$pre,$query);
                    mysqli_query($conn,$query);
                    $query='';
                }
                else if(!preg_match('/^(\/\/|--)/',$line))
                {
                    $query .= $line;
                }
            }
            @fclose($fp);	
            $html ="";
            $html.= "<script type=\"text/javascript\">\n";
            $html.= "$('#installing').append('<p>基础数据添加成功！...</p>');\n";
            $html.= "var div = document.getElementById('installing');";
            $html.= "div.scrollTop = div.scrollHeight;";
            $html.= "</script>";
            echo $html;
            ob_flush();
            flush();
            $query = '';
            if(!$fp = @fopen(QSCMS_DATA_PATH.'sql-hrtools.sql','rb'))
            {
                throw new \Exception('打开文件sql-hrtools.sql出错，请检查文件是否存在');
            }
            while(!feof($fp))
            {
                $line = rtrim(fgets($fp,1024));
                if(preg_match('/;$/',$line)) 
                {
                    $query .= $line."\n";
                    $query = str_replace(QSCMS_PRE,$pre,$query);
                    mysqli_query($conn,$query);
                    $query='';
                }
                else if(!preg_match('/^(\/\/|--)/',$line))
                {
                    $query .= $line;
                }
            }
            @fclose($fp);
            $html ="";
            $html.= "<script type=\"text/javascript\">\n";
            $html.= "$('#installing').append('<p>hr工具箱数据添加成功！...</p>');\n";
            $html.= "var div = document.getElementById('installing');";
            $html.= "div.scrollTop = div.scrollHeight;";
            $html.= "</script>";
            echo $html;
            ob_flush();
            flush();	
            $query = '';
            if(!$fp = @fopen(QSCMS_DATA_PATH.'sql_category_district.sql','rb'))
            {
                throw new \Exception('打开文件sql_category_district.sql出错，请检查文件是否存在');
            }
            while(!feof($fp))
            {
                $line = rtrim(fgets($fp,1024));
                if(preg_match('/;$/',$line)) 
                {
                    $query .= $line."\n";
                    $query = str_replace(QSCMS_PRE,$pre,$query);
                    mysqli_query($conn,$query);
                    $query='';
                }
                else if(!preg_match('/^(\/\/|--)/',$line))
                {
                    $query .= $line;
                }
            }
            @fclose($fp);	
            $html ="";
            $html.= "<script type=\"text/javascript\">\n";
            $html.= "$('#installing').append('<p>地区数据添加成功！...</p>');\n";
            $html.= "var div = document.getElementById('installing');";
            $html.= "div.scrollTop = div.scrollHeight;";
            $html.= "</script>";
            echo $html;
            ob_flush();
            flush();
            $query = '';
            if(!$fp = @fopen(QSCMS_DATA_PATH.'sql-hotword.sql','rb'))
            {
                throw new \Exception('打开文件sql-hotword.sql出错，请检查文件是否存在');
            }
            while(!feof($fp))
            {
                $line = rtrim(fgets($fp,1024));
                if(preg_match('/;$/',$line)) 
                {
                    $query .= $line."\n";
                    $query = str_replace(QSCMS_PRE,$pre,$query);
                    mysqli_query($conn,$query);
                    $query='';
                }
                else if(!preg_match('/^(\/\/|--)/',$line))
                {
                    $query .= $line;
                }
            }
            @fclose($fp);
            $html ="";
            $html.= "<script type=\"text/javascript\">\n";
            $html.= "$('#installing').append('<p>热门关键词数据添加成功！...</p>');\n";
            $html.= "var div = document.getElementById('installing');";
            $html.= "div.scrollTop = div.scrollHeight;";
            $html.= "</script>";
            echo $html;
            ob_flush();
            flush();	
            mysqli_query($conn,"UPDATE `{$pre}config` SET value = '{$site_dir}' WHERE name = 'sitedir'");
            mysqli_query($conn,"UPDATE `{$pre}config` SET value = '{$site_domain}' WHERE name = 'sitedomain'");
            mysqli_query($conn,"UPDATE `{$pre}config` SET value = '{$site_domain}{$site_dir}m/' WHERE name = 'mobile_domain'");
            mysqli_query($conn,"UPDATE `{$pre}config` SET value = '{$site_domain}{$site_dir}app.apk' WHERE name = 'app_android_download_url'");
            $pwd_hash=randstr();
            $admin_md5pwd=md5(md5($admin_pwd).$pwd_hash.$safecode);
            $timestamp = time();
            $admin_email = '';
            mysqli_query($conn,"INSERT INTO `{$pre}admin` (id,username,password,pwd_hash, role_id,addtime, last_login_time, last_login_ip,last_login_ipaddress,openid) VALUES (1, '$admin_name', '$admin_md5pwd', '$pwd_hash', 1, '$timestamp', '$timestamp', '','','')");
            unset($dbhost,$dbuser,$dbpass,$dbname);
            rmdirs(MAIN_PROJECT_PATH . 'runtime/cache/');
            rmdirs(MAIN_PROJECT_PATH . 'runtime/log/');
            mysqli_query($conn,"COMMIT");
        }catch(\Exception $e){
            mysqli_query($conn,"ROLLBACK");
            @unlink(MAIN_PROJECT_PATH.'public/upload/install.lock');
			$this->error(addslashes($e->getMessage()));
        }
        $html ="";
        $html.= "<script type=\"text/javascript\">\n";
        $html.= "window.location.href='".U('step4')."';";
        $html.= "</script>";
        echo $html;
    }
    public function step4(){
    	$this->assign('admin_name',session('admin_name'));
    	$this->assign('admin_pwd',session('admin_pwd'));
    	$this->assign('home_url',session('site_domain').session('site_dir'));
    	$this->assign('admin_url',session('site_domain').session('site_dir').'admin');
    	$this->display();
    }
}