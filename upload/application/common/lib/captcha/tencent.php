<?php
namespace app\common\lib\captcha;
class tencent
{
    protected $verify_url = 'https://ssl.captcha.qq.com/ticket/verify';
    private $Appid;
    private $AppSecretKey;
    private $_error;
    public function __construct()
    {
        $config = config('global_config');
        $this->Appid = $config['captcha_tencent_appid'];
        $this->AppSecretKey = $config['captcha_tencent_appsecret'];
    }
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    private static function txcurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 关闭SSL验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === false) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
    public function validate($Ticket, $Randstr)
    {
        $params = array(
            'aid' => $this->Appid,
            'AppSecretKey' => $this->AppSecretKey,
            'Ticket' => $Ticket,
            'Randstr' => $Randstr,
            'UserIP' => get_client_ip()
        );
        $paramstring = http_build_query($params);
        $content = self::txcurl($this->verify_url, $paramstring);
        $result = json_decode($content, true);
        if ($result) {
            if ($result['response'] == 1) {
                return true;
            } else {
                $this->_error = $result['response'] . ':' . $result['err_msg'];
                return false;
            }
        } else {
            $this->_error = '请求失败';
            return false;
        }
    }
    public function get_config()
    {
        return array(
            'vid' => $this->Appid, // 验证单元的VID
            'verify_type' => 'tencent'
        );
    }
    public function getError()
    {
        return $this->_error;
    }
}
