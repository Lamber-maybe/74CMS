<?php
/**
 * 外呼系统
 * -- 通话记录
 */

namespace app\common\lib\outbound\xiaojianke;

use app\common\lib\outbound\Outbound;

class CallRecord extends Outbound
{
    /**
     * 获取通话记录列表
     * @var string
     */
    protected $record_list_api = 'call_record/getRecordList';

    /**
     * 获取通话记录明细列表
     * @var string
     */
    protected $record_detail_api = 'call_record/getRecordDetailList';

    public function __construct($param = [], $config = [])
    {
        parent::__construct($param, $config);
    }


    /**
     * 获取通话记录列表
     * @return false|mixed
     * @throws ServerException
     */
    public function getRecordList()
    {
        $url = $this->_base_url . $this->record_list_api;
        return $this->callPost($url, $this->param);
    }


    /**
     * 获取通话记录明细列表
     * @return false|mixed
     * @throws ServerException
     */
    public function getRecordDetailList()
    {
        $url = $this->_base_url . $this->record_detail_api;
        return $this->callPost($url, $this->param);
    }

}
