<?php
/**
 * 搜客宝
 * @author edc
 * Date：2022-11-17
 */
namespace app\common\lib;

class Soukebao
{
    const URI = 'https://qy.zhitoo.cn';
    // 配置信息
    protected $_configure;

    public function __construct($isVerify = true){
        if (true === $isVerify) {
            $this->_configure = config('global_config.account_qy_directory');
            if (!isset($this->_configure['app_key']) || empty($this->_configure['app_key'])) {
                responseJson(400, '请先完成【企业名录】配置');
            }
            if (!isset($this->_configure['app_secret']) || empty($this->_configure['app_secret'])) {
                responseJson(400, '请先完成【企业名录】配置');
            }
        }
    }

    /**
     * 获取企业列表
     * @access public
     * @author edc
     * @param  array $params [请求参数]
     * @return array
     * Date：2022-11-17
     */
    public function getCompanyList($params){
        $requestUrl = self::URI . '/v1/company/search';
        $header = [
            'Content-Type: application/json'
        ];
        $requestParam = [
            'appkey'    => $this->_configure['app_key'],
            'appsecret' => $this->_configure['app_secret'],
            'data'      => $params
        ];
        $requestParam = json_encode($requestParam);
        $requestResult = curl_file_post_contents($requestUrl, $requestParam, $header);
        $requestInfo = json_decode($requestResult, true);
        if ($requestInfo['code'] != 200) {
            return callBack(false, $requestInfo['message']);
        }
        return callBack(true, 'success', $requestInfo['data']);
    }

    /**
     * 获取企业列表
     * @access public
     * @author edc
     * @return array
     * Date：2022-11-23
     */
    public function getAccountBalance(){
        $requestUrl = self::URI . '/v1/company/times';
        $header = [
            'Content-Type: application/json'
        ];
        $requestParam = [
            'appkey'    => $this->_configure['app_key'],
            'appsecret' => $this->_configure['app_secret'],
        ];
        $requestParam = json_encode($requestParam);
        $requestResult = curl_file_post_contents($requestUrl, $requestParam, $header);
        $requestInfo = json_decode($requestResult, true);
        if ($requestInfo['code'] != 200) {
            return callBack(false, $requestInfo['message']);
        }
        return callBack(true, 'success', $requestInfo['data']);
    }

}
