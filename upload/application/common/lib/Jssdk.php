<?php
namespace app\common\lib;
class Jssdk {
  private $appId;
  private $appSecret;
  private $access_token;
  public function __construct($appId, $appSecret,$access_token) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
    $this->access_token = $access_token;
  }

  public function getSignPackage($url) {
    $jsapiTicket = $this->getJsApiTicket();
    if($jsapiTicket===false){
      return false;
    }
    // 注意 URL 一定要动态获取，不能 hardcode.
    $timestamp = time();
    $nonceStr = $this->createNonceStr();
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
      $access_ticket=cache('wechat_access_ticket');
      if($access_ticket) return $access_ticket;
      $wechat = new \app\common\lib\Wechat;
      $access_token = $wechat->getAccessToken();
      if($access_token===false){
        return false;
      }
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$access_token;
      $res = json_decode($this->httpGet($url));
      $access_ticket = $res->ticket;
      if ($access_ticket) {
        //更新数据
        cache('wechat_access_ticket',$access_ticket,7200);
        return $access_ticket;
      }
  }


  private function httpGet($url) {
    $http = new \app\common\lib\Http();
    $result = $http->get($url);
    return $result;
  }
}

