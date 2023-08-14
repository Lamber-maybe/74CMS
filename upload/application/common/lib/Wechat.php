<?php
/**
 * 微信相关
 */
namespace app\common\lib;
class Wechat {
    protected $error;
    /*
        pwd_hash
        获取access_token
    */
    public function getAccessToken($reset = false) {
        $access_token = cache('wechat_access_token');
        if ($access_token && !$reset) return $access_token;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . config('global_config.wechat_appid') . "&secret=" . config('global_config.wechat_appsecret');
        $result = $this->apiRequest($url);
        $jsoninfo = json_decode($result, true);
        if(isset($jsoninfo['errcode']) && $jsoninfo['errcode']!==0){
            $this->error = $jsoninfo['errmsg'];
            return false;
        }
        $access_token = $jsoninfo["access_token"];
        //更新数据
        cache('wechat_access_token', $access_token, 7200);
        return $access_token;
    }

    /**
     * [getMenuList 读取微信菜单]
     */
    public function getMenuList() {
        $arr = array();
        $weixin_menu = model('WechatMenu');
        $menu_arr = $weixin_menu->where('pid',0)->order('sort_id desc,id asc')->select();
        foreach ($menu_arr as $key => $value) {
            $sub_menu = $weixin_menu->where('pid',$value['id'])->order('sort_id desc,id asc')->select();
            if (!empty($sub_menu)) {
                $arr[$key]['name'] = urlencode($value['title']);
                foreach ($sub_menu as $sub_key => $sub_value) {
                    $arr[$key]['sub_button'][$sub_key]['type'] = $sub_value['type'];
                    $arr[$key]['sub_button'][$sub_key]['name'] = urlencode($sub_value['title']);
                    if ($sub_value['type'] == "click") {
                        $arr[$key]['sub_button'][$sub_key]['key'] = $sub_value['key'];
                    } else {
                        $sub_value['url'] = str_replace('|appid|', config('global_config.wechat_appid'), $sub_value['url']);
                        $sub_value['url'] = htmlspecialchars_decode($sub_value['url'], ENT_QUOTES);
                        $arr[$key]['sub_button'][$sub_key]['url'] = $sub_value['url'];
                        $weixin_menu->where('id',$sub_value['id'])->setfield('url', $sub_value['url']);
                        $arr[$key]['sub_button'][$sub_key]['url'] = str_replace("{domain}",config('global_config.mobile_domain'),$arr[$key]['sub_button'][$sub_key]['url']);
                    }
                }
            } else {
                $arr[$key]['type'] = $value['type'];
                $arr[$key]['name'] = urlencode($value['title']);
                if ($value['type'] == "click") {
                    $arr[$key]['key'] = $value['key'];
                } else {
                    $value['url'] = str_replace('|appid|', config('global_config.wechat_appid'), $value['url']);
                    $value['url'] = htmlspecialchars_decode($value['url'], ENT_QUOTES);
                    $arr[$key]['url'] = $value['url'];
                    $weixin_menu->where('id',$value['id'])->setfield('url', $value['url']);
                    $arr[$key]['url'] = str_replace("{domain}",config('global_config.mobile_domain'),$arr[$key]['url']);
                }
            }
        }
        $menu['button'] = $arr;
        return urldecode(json_encode($menu,JSON_UNESCAPED_UNICODE));
    }

    /**
     * [menuSync 微信菜单同步]
     */
    public function menuSync() {
        if (!config('global_config.wechat_appid')){
            $this->error = '请配置微信公众号参数！';
            return false;
        }
        $access_token = $this->getAccessToken();
        if($access_token===false){
            return false;
        }
        $menulist = $this->getMenuList();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
        $result = https_request($url, $menulist);
        $result_arr = json_decode($result, true);
        if (isset($result_arr['errcode'])) {
            if($result_arr['errcode'] == 40001){
                $this->getAccessToken(true);
                return $this->menuSync();
            }else if($result_arr['errcode']==0){
                return true;
            }else{
                $this->error = $result_arr['errmsg'] . '(错误代码：' . $result_arr['errcode'] . ')';
                return false;
            }
        } else {
            return true;
        }
    }
    /**
     * [生成微信公众号带参数二维码]
     */
    public function makeQrcode($params = array(),$expire=2592000) {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $access_token;
        $post_data = [
            "expire_seconds"=>$expire,
            "action_name"=>"QR_STR_SCENE",
            "action_info"=>[
                "scene"=>[
                    "scene_str"=>http_build_query($params)
                ]
            ]
        ];
        $result = $this->apiRequest($url, $post_data);
        $result_arr = json_decode($result, true);
        if (isset($result_arr['errcode'])) {
            if($result_arr['errcode'] == 40001){
                $this->getAccessToken(true);
                return $this->makeQrcode($params,$expire);
            }else{
                $this->error = $result_arr['errmsg'] . '(错误代码：' . $result_arr['errcode'] . ')';
                return false;
            }
        } else {
            $ticket = urlencode($result_arr["ticket"]);
            return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $ticket;
        }
    }
    /**
     * 通过openid获取用户信息
     */
    public function getUserinfoByOpenid($openid){
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $result = $this->apiRequest($url);
        $result_arr = json_decode($result, true);
        if (isset($result_arr['errcode'])) {
            if($result_arr['errcode'] == 40001){
                $this->getAccessToken(true);
                return $this->getUserinfoByOpenid($openid);
            }else{
                $this->error = $result_arr['errmsg'] . '(错误代码：' . $result_arr['errcode'] . ')';
                return false;
            }
        } else {
            return $result_arr;
        }
    }
    /*
    * 生成素材库里图片的media_id 和 url
    */
    public function uploadMedia($img_path)
    {
        $path=$_SERVER['DOCUMENT_ROOT'].'/'.SYS_UPLOAD_DIR_NAME.'/'.$img_path;
        $img_info = array(
            'media'=>curl_file_create($path)
        );
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$access_token."&type=image";
        $result = https_request($url , $img_info);
        $result_arr = json_decode($result, true);
        if (isset($result_arr['errcode'])) {
            if($result_arr['errcode'] == 40001){
                $this->getAccessToken(true);
                return $this->uploadMedia($img_path);
            }else{
                $this->error = $result_arr['errmsg'] . '(错误代码：' . $result_arr['errcode'] . ')';
                return false;
            }
        } else {
            return $result_arr;
        }
    }
    public function apiRequest($url, $data = null,$async=false) {
        $http = new \app\common\lib\Http();
        if($data===null){
            if($async===false){
                $result = $http->get($url);
            }else{
                $result = $http->getAsync($url);
            }
        }else{
            if($async===false){
                $result = $http->post($url,$data);
            }else{
                $result = $http->postAsync($url,$data,function(){});
            }
        }
        return $result;
    }
    /**
     * 构建模板消息
     */
    public function buildTplMsg($data) {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $this->apiRequest($url, $data,true);
    }

    /**
     * 错误
     */
    public function getError() {
        return $this->error;
    }
}

