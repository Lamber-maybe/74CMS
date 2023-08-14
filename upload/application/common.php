<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function time_format($value){
    if ($value <= 0) {
        return '';
    } elseif ($value > 0 && $value < 60) {
        // $return = $value . '秒前';
        $return = $value;
    } elseif ($value >= 60 && $value < 3600) {
        $min = intval($value / 60);
        $return = $min . '分钟';
    } elseif ($value >= 3600 && $value < 86400) {
        $h = intval($value / 3600);
        $return = $h . '小时';
    } elseif ($value >= 86400) {
        $d = intval($value / 86400);
        $return = $d . '天';
    }
    return $return;
}
/**
 * 时间格式变换
 */
function daterange($endtime, $starttime, $format = 'Y-m-d', $range = 3)
{
    $value = $endtime - $starttime;
    if ($value < 0) {
        return '';
    } elseif ($value >= 0 && $value < 60) {
        // $return = $value . '秒前';
        $return = '刚刚';
    } elseif ($value >= 60 && $value < 3600) {
        $min = intval($value / 60);
        $return = $min . '分钟前';
    } elseif ($value >= 3600 && $value < 86400) {
        $h = intval($value / 3600);
        $return = $h . '小时前';
    } elseif ($value >= 86400) {
        $d = intval($value / 86400);
        if ($d > $range) {
            return date($format, $starttime);
        } else {
            $return = $d . '天前';
        }
    }
    return $return;
}
function daterange_format($addtime, $refreshtime)
{
    $time = $refreshtime - $addtime;
    $time1 = time() - $refreshtime;
    if ($time < 0 || $time1 < 0) {
        return '';
    } elseif ($time1 < 600) {
        if ($time < 300) {
            $return = '新发布';
        } else {
            $return = '刚刚';
        }
    } else {
        return daterange(time(), $refreshtime, 'Y-m-d');
    }
    return $return;
}
function contact_hide($data, $IsWhat = 2)
{
    if ($IsWhat == 1) {
        return preg_replace(
            '/([0[0-9]{2,3}[-]?[1-9]]|[1-9])[0-9]{2,4}([0-9]{3}[-]?[0-9]?)/i',
            '$1****$2',
            $data
        );
    } elseif ($IsWhat == 2) {
        return preg_replace(
            '/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i',
            '$1****$2',
            $data
        );
    } elseif ($IsWhat == 3) {
        $email_array = explode('@', $data);
        $n = mb_strlen($email_array[0], 'utf-8');
        return str_pad(substr($email_array[0], 0, intval($n / 2)), $n, '*') .
            $email_array[1];
    } else {
        $n = mb_strlen($data, 'utf-8');
        $str = str_pad('', intval($n / 2), '*');
        return str_replace(
            substr($data, intval($n / 4), intval($n / 2)),
            $str,
            $data
        );
    }
}
//截取字符
function cut_str($sourcestr, $cutlength, $start = 0, $dot = '')
{
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr);
    $mb_str_length = mb_strlen($sourcestr, 'utf-8');
    while ($n < $cutlength && $i <= $str_length) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = ord($temp_str);
        if ($ascnum >= 224) {
            $returnstr = $returnstr . substr($sourcestr, $i, 3);
            $i = $i + 3;
            $n++;
        } elseif ($ascnum >= 192) {
            $returnstr = $returnstr . substr($sourcestr, $i, 2);
            $i = $i + 2;
            $n++;
        } elseif ($ascnum >= 65 && $ascnum <= 90) {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;
            $n++;
        } else {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;
            $n = $n + 0.5;
        }
    }
    if ($mb_str_length > $cutlength) {
        $returnstr = $returnstr . $dot;
    }
    return $returnstr;
}
function https_request($url, $data = null) {
    if (function_exists('curl_init')) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    } else {
        return false;
    }
}
/**
 * 删除目录
 */
function rmdirs($dir, $rm_self = false)
{
    $d = dir($dir);
    while (false !== ($child = $d->read())) {
        if ($child != '.' && $child != '..') {
            if (is_dir($dir . '/' . $child)) {
                rmdirs($dir . '/' . $child, true);
            } else {
                unlink($dir . '/' . $child);
            }
        }
    }
    $d->close();
    $rm_self && rmdir($dir);
}
/**
 * 获取随机字符串
 */
function randstr($length = 6, $special = true)
{
    $hash = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $special && ($chars .= '@#!~?:-=');
    $max = strlen($chars) - 1;
    mt_srand((float) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}
/**
 * 获取子目录
 */
function getsubdirs($dir)
{
    $subdirs = array();
    if (!($dh = opendir($dir))) {
        return $subdirs;
    }
    while ($f = readdir($dh)) {
        if ($f == '.' || $f == '..') {
            continue;
        }
        $path = $dir . '/' . $f; //如果只要子目录名, path = $f;
        $subdir = $f;
        if (is_dir($path)) {
            $subdirs[] = $subdir;
        }
    }
    closedir($dh);
    return $subdirs;
}
/**
 * 生成excel
 */
function create_excel($top_str, $data)
{
    header('Content-Type: application/vnd.ms-execl');
    header('Content-Disposition: attachment; filename=myExcel.xls');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo $top_str;
    foreach ($data as $k => $v) {
        foreach ($v as $k1 => $v1) {
            echo $v1;
            echo $k1 + 1 < count($v) ? "\t" : '';
        }
        echo "\t\n";
    }
}
/**
 *计算坐标点周围某段距离的正方形的四个点
 * @param lng float 经度
 * @param lat float 纬度
 * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
 * @return array 正方形的四个点的经纬度坐标
 */
function square_point($lng, $lat, $distance = 0.5)
{
    $earth_radius = 6378.138;
    $dlng = 2 * asin(sin($distance / (2 * $earth_radius)) / cos(deg2rad($lat)));
    $dlng = rad2deg($dlng);
    $dlat = $distance / $earth_radius;
    $dlat = rad2deg($dlat);
    return array(
        'lt' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
        'rt' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
        'lb' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
        'rb' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng),
    );
}
/**
 * 地址转换
 * from:
 * 1：GPS设备获取的角度坐标，WGS84坐标;
 * 2：GPS获取的米制坐标、sogou地图所用坐标;
 * 3：google地图、soso地图、aliyun地图、mapabc地图和amap地图所用坐标，国测局（GCJ02）坐标;
 * 4：3中列表地图坐标对应的米制坐标;
 * 5：百度地图采用的经纬度坐标;
 * 6：百度地图采用的米制坐标;
 * 7：mapbar地图坐标;
 * 8：51地图坐标

 * to:
 * 3：国测局（GCJ02）坐标;
 * 4：3中对应的米制坐标;
 * 5：bd09ll(百度经纬度坐标);
 * 6：bd09mc(百度米制经纬度坐标)
 */
function change_coords($location_arr, $from, $to)
{
    $url = 'http://api.map.baidu.com/geoconv/v1/?coords=';
    $coords_arr = array();
    foreach ($location_arr as $key => $value) {
        $coords_arr[] = $value;
    }
    $url .= implode(';', $coords_arr);
    $url .=
    '&from=' .
    $from .
    '&to=' .
    $to .
    '&ak=' .
    config('global_config.map_server_ak');
    $http = new \app\common\lib\Http();
    $result = $http->get($url);
    $result_arr = json_decode($result, 1);
    if ($result_arr['status'] == 0) {
        return array('status' => 1, 'data' => $result_arr['result']);
    } else {
        return array('status' => 0);
    }
}
/**
 * 生成随机浮点数
 */
function random_float($min = 0, $max = 1)
{
    $rand = $min + (mt_rand() / mt_getrandmax()) * ($max - $min);
    return round($rand, 2);
}
/**
 * 生成上传文件预览地址
 */
function make_file_url($path, $type = 'default')
{
    if ($path == '') {
        return '';
    }
    $path = str_replace("\\", "/", $path);
    switch ($type) {
        case 'default':
            return config('global_config.sitedomain') .
            config('global_config.sitedir') .
                SYS_UPLOAD_DIR_NAME .
                '/' .
                $path;
            break;
        case 'qiniu':
            $qiniu_config = config('global_config.account_qiniu');
            return $qiniu_config['protocol'] . $qiniu_config['domain'] . '/' . $path;
            break;
        case 'original':
            return $path;
            break;
        default:
            return config('global_config.sitedomain') .
            config('global_config.sitedir') .
                SYS_UPLOAD_DIR_NAME .
                '/' .
                $path;
            break;
    }
}
/**
 * 获取默认图片
 */
function default_empty($alias)
{
    switch ($alias) {
        case 'thumb':
            $filename = 'empty_thumb.jpg';
            break;
        case 'photo':
            $filename = 'empty_photo.png';
            break;
        case 'logo':
            $filename = 'empty_logo.jpg';
            break;
        case 'jobfair_thumb':
            $filename = 'empty_jobfair_thumb.jpg';
            break;
        default:
            $filename = '';
            break;
    }
    return config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME.'/resource/' . $filename;
}
/**
 * Mb转换为字节
 */
function mb2bytes($size)
{
    $mb = intval($size);
    $bytes = $mb * 1024 * 1024;
    return $bytes;
}
/**
 * 下划线转驼峰
 */
function camelize($uncamelized_words, $separator = '_')
{
    $uncamelized_words =
    $separator .
    str_replace($separator, ' ', strtolower($uncamelized_words));
    return ltrim(str_replace(' ', '', ucwords($uncamelized_words)), $separator);
}
/**
 * 驼峰命名转下划线命名
 */
function uncamelize($camelCaps, $separator = '_')
{
    return strtolower(
        preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camelCaps)
    );
}
/**
 * 获取拼音首字母
 */
function getfirstchar($s0)
{
    $fchar = ord($s0[0]);
    if ($fchar >= ord('A') and $fchar <= ord('z')) {
        return strtoupper($s0[0]);
    }
    $s1 = iconv('UTF-8', 'gb2312//IGNORE', $s0);
    $s2 = iconv('gb2312', 'UTF-8//IGNORE', $s1);
    if ($s2 == $s0) {
        $s = $s1;
    } else {
        $s = $s0;
    }
    $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
    if ($asc >= -20319 and $asc <= -20284) {
        return 'A';
    }
    if ($asc >= -20283 and $asc <= -19776) {
        return 'B';
    }
    if ($asc >= -19775 and $asc <= -19219) {
        return 'C';
    }
    if ($asc >= -19218 and $asc <= -18711) {
        return 'D';
    }
    if ($asc >= -18710 and $asc <= -18527) {
        return 'E';
    }
    if ($asc >= -18526 and $asc <= -18240) {
        return 'F';
    }
    if ($asc >= -18239 and $asc <= -17923) {
        return 'G';
    }
    if ($asc >= -17922 and $asc <= -17418) {
        return 'H';
    }
    if ($asc >= -17922 and $asc <= -17418) {
        return 'I';
    }
    if ($asc >= -17417 and $asc <= -16475) {
        return 'J';
    }
    if ($asc >= -16474 and $asc <= -16213) {
        return 'K';
    }
    if ($asc >= -16212 and $asc <= -15641) {
        return 'L';
    }
    if ($asc >= -15640 and $asc <= -15166) {
        return 'M';
    }
    if ($asc >= -15165 and $asc <= -14923) {
        return 'N';
    }
    if ($asc >= -14922 and $asc <= -14915) {
        return 'O';
    }
    if ($asc >= -14914 and $asc <= -14631) {
        return 'P';
    }
    if ($asc >= -14630 and $asc <= -14150) {
        return 'Q';
    }
    if ($asc >= -14149 and $asc <= -14091) {
        return 'R';
    }
    if ($asc >= -14090 and $asc <= -13319) {
        return 'S';
    }
    if ($asc >= -13318 and $asc <= -12839) {
        return 'T';
    }
    if ($asc >= -12838 and $asc <= -12557) {
        return 'W';
    }
    if ($asc >= -12556 and $asc <= -11848) {
        return 'X';
    }
    if ($asc >= -11847 and $asc <= -11056) {
        return 'Y';
    }
    if ($asc >= -11055 and $asc <= -10247) {
        return 'Z';
    }
    return '';
}
/**
 * 去除特殊符号
 */
function del_punctuation($str)
{
    if (!$str) {
        return '';
    }
    $char =
        '`·。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）';
    $pattern = array('/[[:punct:]]/i', '/[' . $char . ']/u', '/[ ]{2,}/');
    $str = preg_replace($pattern, '', $str);
    return $str;
}
function get_client_ip()
{
    $request = \think\Request::instance();
    return $request->ip();
}
function get_client_port()
{
    $request = \think\Request::instance();
    return $request->server('REMOTE_PORT');
}
function get_client_ipaddress($ip)
{
    if (!$ip) {
        return '未知';
    }
    if ($ip == '127.0.0.1') {
        return '内网ip';
    }
    if ($return = cache('ip_addr_' . $ip)) {
        return $return;
    }
    $ak = config('global_config.map_server_ak');
    if (!$ak) {
        return '未知';
    }
    $url = 'https://api.map.baidu.com/location/ip?ak=' . $ak . '&ip=' . $ip;
    $result = json_decode(file_get_contents($url));

    if ($result->status != 0) {
        return '未知';
    }
    $return =
    $result->content->address != '' ? $result->content->address : '未知';
    cache('ip_addr_' . $ip, $return, 2592000);
    return $return;
}
function merge_spaces($string)
{
    return preg_replace('/\s(?=\s)/', "\\1", $string);
}
function uuid()
{
    $charid = md5(uniqid(mt_rand(), true));
    $uuid =
    substr($charid, 0, 8) .
    substr($charid, 8, 4) .
    substr($charid, 12, 4) .
    substr($charid, 16, 4) .
    substr($charid, 20, 12);
    return $uuid;
}
function is_json($data = '', $assoc = false)
{
    $data = json_decode($data, $assoc);
    if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
        return true;
    }
    return false;
}
function format_date($timestamp)
{
    $year_minus = date('Y') - date('Y', $timestamp);
    if ($year_minus == 0) {
        $month_minus = date('m') - date('m', $timestamp);
        if ($month_minus == 0) {
            return '无';
        }
        return $month_minus . '个月';
    }
    return $year_minus . '年';
}
function fieldRegex($value, $rule)
{
    $validate = array(
        'require' => '/.+/',
        'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'mobile' => '/^(13|14|15|16|17|18|19)\d{9}$/',
        'tel' => '/^(([0\\+]\\d{2,3}-)?(0\\d{2,3})-)?(\\d{7,8})(-(\\d{3,}))?$/',
        'url' =>
        '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
        'currency' => '/^\d+(\.\d+)?$/',
        'number' => '/^\d+$/',
        'zip' => '/^\d{6}$/',
        'integer' => '/^[-\+]?\d+$/',
        'double' => '/^[-\+]?\d+(\.\d+)?$/',
        'english' => '/^[A-Za-z]+$/',
        'img' => '(.*)\\.(jpg|bmp|gif|ico|pcx|jpeg|tif|png|raw|tga)$/',
        'in' => '/^(\d{1,10},)*(\d{1,10})$/',
        'qq' => '/^[1-9]*[1-9][0-9]*$/',
    );
    // 检查是否有内置的正则表达式
    if (isset($validate[strtolower($rule)])) {
        $rule = $validate[strtolower($rule)];
    }
    return preg_match($rule, $value) === 1;
}
/**
 * 按指定长度分割数组
 */
function splitArray($data, $num = 5)
{
    $arrRet = array();
    if (!isset($data) || empty($data)) {
        return $arrRet;
    }

    $iCount = count($data) / $num;
    if (!is_int($iCount)) {
        $iCount = ceil($iCount);
    } else {
        $iCount += 1;
    }
    for ($i = 0; $i < $iCount; ++$i) {
        $arrInfos = array_slice($data, $i * $num, $num);
        if (empty($arrInfos)) {
            continue;
        }
        $arrRet[] = $arrInfos;
        unset($arrInfos);
    }

    return $arrRet;
}
/**
 * 校验身份证
 */
function is_idcard($id)
{
    $id = strtoupper($id);
    $regx = '/(^\d{15}$)|(^\d{17}([0-9]|X)$)/';
    $arr_split = array();
    if (!preg_match($regx, $id)) {
        return false;
    }
    if (15 == strlen($id)) {
        //检查15位
        $regx = '/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/';

        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth =
            '19' . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) {
            return false;
        } else {
            return true;
        }
    }
    //检查18位
    else {
        $regx = '/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/';
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) {
            //检查生日日期是否正确
            return false;
        } else {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = array(
                7,
                9,
                10,
                5,
                8,
                4,
                2,
                1,
                6,
                3,
                7,
                9,
                10,
                5,
                8,
                4,
                2,
            );
            $arr_ch = array(
                '1',
                '0',
                'X',
                '9',
                '8',
                '7',
                '6',
                '5',
                '4',
                '3',
                '2',
            );
            $sign = 0;
            for ($i = 0; $i < 17; $i++) {
                $b = (int) $id[$i];
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id, 17, 1)) {
                return false;
            }
            //phpfensi.com
            else {
                return true;
            }
        }
    }
}
//返回当前的毫秒时间戳
function msectime()
{
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float) sprintf(
        '%.0f',
        (floatval($msec) + floatval($sec)) * 1000
    );
    return $msectime;
}
/**
 * 计算两坐标点之间的距离
 * 返回友好的距离长度
 *
 * @param   $current_lat     decimal   当前位置纬度
 * @param   $current_lng     decimal   当前位置经度
 * @param   $target_lat      decimal   目标纬度
 * @param   $target_lng      decimal   目标经度
 *
 * @return  decimal   距离
 */
function get_distance($current_lat, $current_lng, $target_lat, $target_lng)
{
    $PI = '3.1415926535898';
    $radLat1 = $current_lat * ($PI / 180);
    $radLat2 = $target_lat * ($PI / 180);
    $a = $radLat1 - $radLat2;
    $b = $current_lng * ($PI / 180) - $target_lng * ($PI / 180);
    $s =
    2 *
    asin(
        sqrt(
            pow(sin($a / 2), 2) +
            cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)
        )
    );
    $s = $s * 6378;
    $s = $s > 1 ? round($s, 1) . 'km' : round($s * 1000) . 'm';
    return $s;
}
/**
 * 路由映射
 */
function url_rewrite($alias, $params = [], $domain = '')
{
    $action = '';
    switch ($alias) {
        case 'QS_index':
            $action = 'index/index/index';
            break;
        case 'QS_joblist':
            $action = 'index/job/index';
            break;
        case 'QS_jobshow':
            $action = 'index/job/show';
            break;
        case 'QS_companylist':
            $action = 'index/company/index';
            break;
        case 'QS_companyshow':
            $action = 'index/company/show';
            break;
        case 'QS_resumelist':
            $action = 'index/resume/index';
            break;
        case 'QS_resumeshow':
            $action = 'index/resume/show';
            break;
        case 'QS_articlelist':
            $action = 'index/article/index';
            break;
        case 'QS_articleshow':
            $action = 'index/article/show';
            break;
        default:
            $action = 'index/index/index';
            break;
    }
    return url($action, $params, '', $domain);
}
/**
 * [P 模板跳转 参数处理]
 * @param array $data [数组]
 */
function P($data = array()) {
    $request = \think\Request::instance();
    $get = $request->param();
    unset($get['page']);
    $params = empty($get)?$data:array_merge($get, $data);
    return url(strtolower($request->module()) . '/' . strtolower($request->controller()) . '/' . strtolower($request->action()), $params);
}
/**
 * [ddate 时间差]
 */
function ddate($s, $e) {
    $starttime = strtotime($s);
    $endtime = strtotime($e);
    $startyear = date('Y', $starttime);
    $startmonth = date('m', $starttime);
    $endyear = date('Y', $endtime);
    $endmonth = date('m', $endtime);
    $return = '';
    $return_year = $endyear - $startyear;
    $return_month = $endmonth - $startmonth;
    if ($return_month < 0) {
        $return_month += 12;
        $return_year -= 1;
    }

    if ($return_year > 0) {
        $return .= $return_year . '年';
    }
    if ($return_month > 0) {
        $return .= $return_month . '个月';
    }
    return $return;
}
/**
 * 字符串中提取数字
 */
function findNumByStr($str=''){
    $str=trim($str);
    if(empty($str)){return '';}
    $reg='/(\d+(\.\d+)?)/is';//匹配数字的正则表达式
    preg_match_all($reg,$str,$result);
    if(is_array($result)&&!empty($result)&&!empty($result[1])&&!empty($result[1][0])){
        return $result[1][0];
    }
    return '';
}
/**
 * 判断是否移动端访问
 */
function is_mobile_request()
{
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
    $mobile_browser = '0';
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) $mobile_browser++;
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))     $mobile_browser++;
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))     $mobile_browser++;
    if(isset($_SERVER['HTTP_PROFILE']))     $mobile_browser++;
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
    $mobile_agents = array(       'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',       'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',       'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',       'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',       'newt','noki','oper','palm','pana','pant','phil','play','port','prox',       'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',       'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',       'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',       'wapr','webc','winw','winw','xda','xda-'     );    if(in_array($mobile_ua, $mobile_agents))     $mobile_browser++;
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)     $mobile_browser++;
    // Pre-final check to reset everything if the user is on Windows
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)     $mobile_browser=0;
    // But WP7 is also Windows, with a slightly different characteristic
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)     $mobile_browser++;
    if($mobile_browser>0)
        return true;
    else
        return false;
}
/**
 * 格式化最近登录时间
 */
function format_last_login_time($login_time)
{
    if (date('Y-m-d', $login_time) == date('Y-m-d')) {
        return '今天';
    } elseif (
        date('Y-m-d', $login_time) == date('Y-m-d', strtotime('yesterday'))
    ) {
        return '昨天';
    } elseif (
        date('Y-m-d', $login_time) ==
        date('Y-m-d', strtotime('yesterday') - 1)
    ) {
        return '前天';
    } elseif (time() - $login_time > 7 * 24 * 3600) {
        return '超过一周';
    } else {
        return date('Y-m-d', $login_time);
    }
}

function badword_filter($content){
    return \app\common\lib\Badword::filter($content);
}
