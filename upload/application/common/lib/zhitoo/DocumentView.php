<?php
/**
 * 智兔 - 在线文档预览
 */

namespace app\common\lib\zhitoo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class DocumentView
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
            $this->_configure = config('global_config.account_zhitoo_document');
            if (!isset($this->_configure) || empty($this->_configure)) {
                $this->errorCode = 400;
                $this->errorMessage = '请先完成【在线文档预览】配置';
                $flag = true;
                break;
            }

            if (!isset($this->_configure['appKey']) || empty($this->_configure['appKey'])) {
                $this->errorCode = 403;
                $this->errorMessage = '请先完成【在线文档预览】{appKey}配置';
                $flag = true;
                break;
            }

            if (!isset($this->_configure['appSecret']) || empty($this->_configure['appSecret'])) {
                $this->errorCode = 404;
                $this->errorMessage = '请先完成【在线文档预览】{appSecret}配置';
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

    public function check()
    {
        if (empty($this->appKey) || empty($this->appSecret)) {
            return [
                'is_check' => 0,
                'deadline' => 0
            ];
        }

        $url = self::URI . '/v1/document/check';

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
                $this->errorMessage = '【智兔-在线文档预览】接口请求失败';
                return false;
            }

            if (intval($res['code']) != 200) {
                $this->errorCode = 500;
                $this->errorMessage = !empty($res['message']) ? $res['message'] : '【智兔-在线文档预览】接口请求失败';
                return false;
            } else {
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

    public function getError()
    {
        return $this->errorMessage;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
