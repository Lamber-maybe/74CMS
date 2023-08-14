<?php
/**
 * 外呼系统
 * -- 坐席
 */

namespace app\common\lib\outbound\xiaojianke;

use app\common\lib\outbound\Outbound;

class CallSeat extends Outbound
{

    /**
     * 获取坐席列表
     * @var string
     */
    protected $get_seat_list_api = 'seat/getSeatList';

    /**
     * 获取所有坐席列表
     * @var string
     */
    protected $get_all_seat_list_api = 'seat/getAllSeatList';

    /**
     * 绑定坐席
     * @var string
     */
    protected $bind_seat_api = 'seat/bindSeat';


    public function __construct($param = [], $config = [])
    {
        parent::__construct($param, $config);
    }


    /**
     * 获取坐席列表
     * @return false|mixed
     * @throws ServerException
     */
    public function getSeatList()
    {
        $url = $this->_base_url . $this->get_seat_list_api;
        return $this->callPost($url, $this->param);
    }


    /**
     * 获取所有坐席列表
     * @return false|mixed
     * @throws \app\common\lib\outbound\Exception\ServerException
     */
    public function getAllSeatList()
    {
        $url = $this->_base_url . $this->get_all_seat_list_api;
        return $this->callPost($url, $this->param);
    }


    /**
     * 绑定坐席
     * @return false|mixed
     * @throws ServerException
     */
    public function seatBind()
    {
        $url = $this->_base_url . $this->bind_seat_api;
        return $this->callPost($url, $this->param);
    }
}
