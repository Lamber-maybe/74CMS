<?php
/**
 * 外呼系统接口
 */

namespace app\common\lib\outbound;

use app\common\lib\outbound\Exception\ServerException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class Outbound
{
    /**
     * 外呼配置信息
     * @var array|mixed
     */
    protected $account_config;

    /**
     * 销荐客外呼根URL
     * @var string
     */
    protected $_base_url = 'https://www.xiaojianke.cn/v2/openapi/'; //基础类短信请求地址

    protected $http;

    protected $param = [];

    protected $errorCode;

    protected $errorMessage;


    public function __construct($param = [], $config = [])
    {
        $this->account_config = empty($config)
            ? config('global_config.account_outbound')
            : $config;

        if (empty($this->account_config)) {
            throw new ServerException('外呼配置错误', '20001');
        }

        if (!isset($this->account_config['app_id']) || empty($this->account_config['app_id'])) {
            throw new ServerException('外呼配置错误,缺少【app_id】', '20002');
        }
        if (!isset($this->account_config['app_secret']) || empty($this->account_config['app_secret'])) {
            throw new ServerException('外呼配置错误,缺少【app_secret】', '20003');
        }

        if (!is_array($param)) {
            throw new ServerException('接口请求参数错误', '10001');
        }
        $this->param = $this->buildParam($param);

        $this->http = new Client();

    }

    protected function callPost($url, $data = [])
    {
        try {
            $response = $this->http->request(
                'POST',
                $url,
                ['json' => $data]);
            $res = $response->getBody()->getContents();
        } catch (ClientException $e) {
            $this->errorCode = 500;
            $this->errorMessage = $e->getMessage();
            return false;
        }

        $data = json_decode($res, true);

        if (!isset($data['code'])) {
            throw new ServerException('外呼API调用失败', '30001');
        }

        if (200 === $data['code']) {
            return $data;
        } else {
            $this->errorCode = $data['code'];
            $this->errorMessage = $data['msg'];
            return false;
        }
    }

    protected function buildParam($param)
    {
        $param['app_id'] = !empty($param['app_id']) ? $param['app_id'] : $this->account_config['app_id'];
        $param['app_secret'] = !empty($param['app_secret']) ? $param['app_secret'] : $this->account_config['app_secret'];
        ksort($param);
        // 移除sign
        $requestData = http_build_query($param);
        // 生成当前的签名
        $currentSign = md5($requestData);

        $param['sign'] = $currentSign;

        return $param;
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