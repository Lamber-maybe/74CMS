<?php
/**
 * 外呼系统
 * -- 呼叫
 */

namespace app\common\lib\outbound\xiaojianke;

use app\common\lib\outbound\Outbound;

class Call extends Outbound
{

    /**
     * 拨号
     * @var string
     */
    protected $dialing_api = 'call/dialing';


    public function __construct($param = [], $config = [])
    {
        parent::__construct($param, $config);
    }


    /**
     * 拨号
     * @return false|mixed
     * @throws ServerException
     */
    public function dialing()
    {
        $url = $this->_base_url . $this->dialing_api;
        return $this->callPost($url, $this->param);
    }


}
