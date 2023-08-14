<?php
/**
 * 外呼系统
 * -- 统计
 */

namespace app\common\lib\outbound\xiaojianke;

use app\common\lib\outbound\Outbound;

class Statistics extends Outbound
{
    /**
     * 获取呼叫记录统计
     * @var string
     */
    protected $get_call_record_api = 'statistics/getCallRecord';

    /**
     * 获取坐席呼叫记录统计
     * @var string
     */
    protected $get_seat_call_record_api = 'statistics/getSeatCallRecord';

    /**
     * 获取呼叫记录统计列表
     * @var string
     */
    protected $get_call_record_list_api = 'statistics/getCallRecordList';


    public function __construct($param = [], $config = [])
    {
        parent::__construct($param, $config);
    }


    /**
     * 获取呼叫记录统计
     * @return false|mixed
     * @throws ServerException
     */
    public function getCallRecord()
    {
        $url = $this->_base_url . $this->get_call_record_api;
        return $this->callPost($url, $this->param);
    }

    /**
     * 获取坐席呼叫记录统计
     * @return false|mixed
     * @throws ServerException
     */
    public function getSeatCallRecord()
    {
        $url = $this->_base_url . $this->get_seat_call_record_api;
        return $this->callPost($url, $this->param);
    }

    /**
     * 获取呼叫记录统计列表
     * @return false|mixed
     * @throws ServerException
     */
    public function getCallRecordList()
    {
        $url = $this->_base_url . $this->get_call_record_list_api;
        return $this->callPost($url, $this->param);
    }

}
