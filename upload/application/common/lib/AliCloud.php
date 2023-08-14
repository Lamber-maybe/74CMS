<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/18
 * Time: 10:01
 */

namespace app\common\lib;


use app\common\model\AliAxb;

class AliCloud
{
    protected $appid;
    protected $appsecret;
    protected $error;

    public function __construct()
    {
        $this->appid = trim(config('global_config.alicloud_app_key'));
        $this->appsecret = trim(config('global_config.alicloud_appsecret'));
    }

    /**
     * @param $a a号码
     * @param $b b号码
     * @param int $expire 绑定时长(秒),默认600
     * @return bool
     */
    public function bindAxb($a, $b, $expire=600){
        $params = array ();

        // *** 需用户填写部分 ***
        // fixme 必填：是否启用https
        $security = true;

        // fixme 必填: 号池Key
        $params["PoolKey"] = trim(config('global_config.alicloud_pool_key'));

        // fixme 必填: AXB关系中的A号码
        $params["PhoneNoA"] = trim($a);

        // fixme 必填: AXB关系中的B号码
        $params["PhoneNoB"] = trim($b);

        // fixme 可选: 指定X号码进行绑定
        //$params["PhoneNoX"] = "1700000000";

        // fixme 必填: 绑定关系对应的失效时间-不能早于当前系统时间
        $params["Expiration"] = date('Y-m-d H:i:s', time()+$expire);

        // fixme 可选: 是否需要录制音频-默认是"false"
        $params["IsRecordingEnabled"] = "false";

        // fixme 可选: 外部业务自定义ID属性
        $params["OutId"] = "yourOutId";

        $result = $this->request(
            $this->appid,
            $this->appsecret,
            "dyplsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "BindAxb",
                "Version" => "2017-05-25",
            )),
            $security
        );
        if(strtolower($result->Code) == strtolower('isv.NO_AVAILABLE_NUMBER')){
            $this->error = '无可用号码';
        }
        return $result;
    }

    public function bindAxN($a, $b, $expire=600){
        $params = array ();

        // *** 需用户填写部分 ***
        // fixme 必填：是否启用https
        $security = true;

        // fixme 必填: 号池Key
        $params["PoolKey"] = trim(config('global_config.alicloud_pool_key_axn'));

        // fixme 必填: AXB关系中的A号码
        $params["PhoneNoA"] = trim($a);

        // fixme 必填: AXB关系中的B号码
        $params["PhoneNoB"] = trim($b);

        // fixme 可选: 指定X号码进行绑定
        //$params["PhoneNoX"] = "1700000000";

        // fixme 必填: 绑定关系对应的失效时间-不能早于当前系统时间
        $params["Expiration"] = date('Y-m-d H:i:s', time()+$expire);

        // fixme 可选: 是否需要录制音频-默认是"false"
        $params["IsRecordingEnabled"] = "false";

        // fixme 可选: 外部业务自定义ID属性
        $params["OutId"] = "yourOutId";

        $result = $this->request(
            $this->appid,
            $this->appsecret,
            "dyplsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "BindAxn",
                "Version" => "2017-05-25",
            )),
            $security
        );
        if(strtolower($result->Code) == 'ok'){
            return $result->SecretBindDTO->SecretNo;
        }
        $this->error = $result->Code;
        if(strtolower($result->Code) == strtolower('isv.NO_AVAILABLE_NUMBER')){
            $this->error = '无可用号码';
        }
        return false;
    }

    public function unbind($subId, $x){
        $params = array ();
        $security = true;
        // fixme 必填: 号池Key
        $params["PoolKey"] = trim(config('global_config.alicloud_pool_key'));

        // fixme 必填: 必填:对应的产品类型
        //$params["ProductType"] = 'AXB_170';

        // fixme 必填: 绑定关系对应的ID-对应到绑定接口中返回的subsId;
        $params["SubsId"] = trim($subId);

        // fixme 可选: 指定X号码进行绑定
        $params["SecretNo"] = trim($x);

        $result = $this->request(
            $this->appid,
            $this->appsecret,
            "dyplsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "UnbindSubscription",
                "Version" => "2017-05-25",
            )),
            $security
        );
        if(strtolower($result->Code) == 'ok'){
            return true;
        }
        return false;
    }

    public function getError(){
        return $this->error;
    }

    public function bindAxb2($a, $b, $expire=600){
        $model = new AliAxb();
        $res = $model->unbind($a, $b);
        if($res){
            if(!$this->unbind($res['sub_id'], $res['x'])){
                $this->error = sprintf('unbind failed, %s, %s', $res['sub_id'], $res['x']);
            }
        }
        $result = $this->bindAxb($a, $b, $expire);
        if(strtolower($result->Code) == 'ok'){
            $model->bind($a, $b, $result->SecretBindDTO->SecretNo, $result->SecretBindDTO->SubsId);
            return $result->SecretBindDTO->SecretNo;
        }else{
            $this->error = $result->Code;
        }
        return false;
    }
    /**
     * 生成签名并发起请求
     *
     * @param $accessKeyId string AccessKeyId (https://ak-console.aliyun.com/)
     * @param $accessKeySecret string AccessKeySecret
     * @param $domain string API接口所在域名
     * @param $params array API具体参数
     * @param $security boolean 使用https
     * @param $method boolean 使用GET或POST方法请求，VPC仅支持POST
     * @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false
     */
    public function request($accessKeyId, $accessKeySecret, $domain, $params, $security=false, $method='POST') {
        $apiParams = array_merge(array (
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0,0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
        ), $params);
        ksort($apiParams);

        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }

        $stringToSign = "${method}&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));

        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));

        $signature = $this->encode($sign);

        $url = ($security ? 'https' : 'http')."://{$domain}/";

        try {
            $content = $this->fetchContent($url, $method, "Signature={$signature}{$sortedQueryStringTmp}");
            return json_decode($content);
        } catch( \Exception $e) {
            return false;
        }
    }

    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }

    private function fetchContent($url, $method, $body) {
        $ch = curl_init();

        if($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        } else {
            $url .= '?'.$body;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "x-sdk-client" => "php/2.0.0"
        ));

        if(substr($url, 0,5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $rtn = curl_exec($ch);

        if($rtn === false) {
            // 大多由设置等原因引起，一般无法保障后续逻辑正常执行，
            // 所以这里触发的是E_USER_ERROR，会终止脚本执行，无法被try...catch捕获，需要用户排查环境、网络等故障
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);

        return $rtn;
    }
}
