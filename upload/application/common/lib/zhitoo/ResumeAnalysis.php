<?php
/**
 * 智兔 - 简历解析
 */

namespace app\common\lib\zhitoo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ResumeAnalysis
{
    // 接口路由
    const URI = 'https://cv.zhitoo.cn';
    // 配置信息
    protected $_configure;
    // 请求构造
    protected $http;
    protected $appKey;
    protected $appSecret;
    public $errorCode;
    public $errorMessage;

    public function __construct($isVerify = true)
    {
        $flag = false;

        do {
            $this->_configure = config('global_config.account_zhitoo_resume');
            if (!isset($this->_configure) || empty($this->_configure)) {
                $this->errorCode = 400;
                $this->errorMessage = '请先完成【简历解析】配置';
                $flag = true;
                break;
            }

            if (!isset($this->_configure['is_open']) || empty($this->_configure['is_open'])) {
                $this->errorCode = 401;
                $this->errorMessage = '请先完成【简历解析】{开关}配置';
                $flag = true;
                break;
            }
            if (intval($this->_configure['is_open']) != 1) {
                $this->errorCode = 402;
                $this->errorMessage = '【简历解析】已关闭';
                $flag = true;
                break;
            }

            if (!isset($this->_configure['appKey']) || empty($this->_configure['appKey'])) {
                $this->errorCode = 403;
                $this->errorMessage = '请先完成【简历解析】{appKey}配置';
                $flag = true;
                break;
            }

            if (!isset($this->_configure['appSecret']) || empty($this->_configure['appSecret'])) {
                $this->errorCode = 404;
                $this->errorMessage = '请先完成【简历解析】{appSecret}配置';
                $flag = true;
                break;
            }
        } while (0);

        if (true === $isVerify && true === $flag) {
            responseJson(500, $this->errorMessage);
        }

        $this->appKey = $this->_configure['appKey'];
        $this->appSecret = $this->_configure['appSecret'];
        $this->http = new Client();
    }

    public function create($fileUrl)
    {
        if (empty($fileUrl)) {
            $this->errorCode = 501;
            $this->errorMessage = '【附件简历】参数错误';
            return false;
        }

        $pathInfo = pathinfo($fileUrl);

        $ext = $pathInfo['extension'];

        $content = $this->fileToBase64($fileUrl);

        $url = self::URI . '/v1/resume/create';

        // 请求参数：appkey，appsecret，content，ext
        $data = [
            'appkey' => $this->appKey,
            'appsecret' => $this->appSecret,
            'content' => $content,
            'ext' => $ext,
        ];

        try {
            $response = $this->http->request(
                'POST',
                $url,
                ['json' => $data]);
            $res = $response->getBody()->getContents();

            if (is_json($res)) {
                $res = json_decode($res, true);
            } else {
                $this->errorCode = 501;
                $this->errorMessage = '【智兔-简历解析】接口请求失败';
                return false;
            }

            if (intval($res['code']) === 200) {
                return $res['data'];
            } else {
                $this->errorCode = 502;
                $this->errorMessage = !empty($res['message']) ? $res['message'] : '【智兔-简历解析】接口请求失败';
                return false;
            }

        } catch (ClientException $e) {
            $this->errorCode = 600;
            $this->errorMessage = $e->getMessage();
            return false;
        }
    }

    public function check()
    {
        if (empty($this->appKey) || empty($this->appSecret)) {
            return [
                'is_check' => 0,
                'total' => 0,
                'list' => []
            ];
        }

        $url = self::URI . '/v1/resume/check';

        // 请求参数：appkey，appsecret
        $data = [
            'appkey' => $this->appKey,
            'appsecret' => $this->appSecret
        ];

        try {
            $response = $this->http->request(
                'POST',
                $url,
                ['json' => $data]);
            $res = $response->getBody()->getContents();

            if (is_json($res)) {
                $res = json_decode($res, true);
            } else {
                $this->errorCode = 500;
                $this->errorMessage = '【智兔-简历解析】接口请求失败';
                return false;
            }

            if (intval($res['code']) != 200) {
                $this->errorCode = 500;
                $this->errorMessage = !empty($res['message']) ? $res['message'] : '【智兔-简历解析】接口请求失败';
                return false;
            } else {
                $res['data']['is_check'] = 1;
                return $res['data'];
            }

        } catch (ClientException $ce) {
            $this->errorCode = 500;
            $this->errorMessage = $ce->getMessage();
            return false;
        } catch (\Exception $e) {
            $this->errorCode = 500;
            $this->errorMessage = $e->getMessage();
            return false;
        }
    }

    private function fileToBase64($fileUrl)
    {
        $base64_data = base64_encode(file_get_contents($fileUrl));
//        $base64_file = 'data:' . $ext . ';base64,' . $base64_data;
        return $base64_data;
    }

    public function getError()
    {
        return $this->errorMessage;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
