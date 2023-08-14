<?php
namespace app\common\behavior;

class CrossDomain
{
    public function run(&$params)
    {
        //跨域访问的时候才会存在此字段
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        header('Access-Control-Allow-Origin:' . $origin);
        header('Access-Control-Allow-Methods:POST,OPTIONS,GET');
        header('Access-Control-Allow-Credentials:true');
        header(
            'Access-Control-Allow-Headers:x-requested-with,content-type,x-token,safecode,sessionid,admintoken,user-token,platform'
        );
    }
}
