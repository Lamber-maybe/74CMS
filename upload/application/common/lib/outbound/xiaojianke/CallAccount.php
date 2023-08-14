<?php

namespace app\common\lib\outbound\xiaojianke;

use app\common\lib\outbound\Outbound;
use GuzzleHttp\Client;

class CallAccount extends Outbound
{
    /**
     * 验证账户密钥
     * @var string
     */
    protected $verify_account_secret_api = 'account/verifyAccountSecret';


    public function __construct($param = [])
    {
        $this->param = $this->buildParam($param);
        $this->http = new Client();
    }


    /**
     * 验证账户密钥
     * @return false|mixed
     * @throws ServerException
     */
    public function verifyAccountSecret()
    {
        $url = $this->_base_url . $this->verify_account_secret_api;
        return $this->callPost($url, $this->param);
    }
}