<?php
/**
 * 外呼坐席绑定表
 */

namespace app\common\model\outbound;

use app\common\model\BaseModel;

class OutbountSeats extends BaseModel
{
    /**
     * 绑定坐席
     * @param $param
     * @return int|string
     */
    public function bindSeat($param)
    {
        $arr = [
            'seat_id' => $param['seat_id'],
            'seat_number' => $param['seat_number'],
            'admin_id' => $param['admin_id'],
            'bind_time' => time()
        ];
        return $this->insert($arr);
    }


    /**
     * 绑定坐席
     * @param $seatId
     * @return int|string
     */
    public function unbindSeat($seatId)
    {
        return $this->where('seat_id', $seatId)
            ->delete();
    }


    /**
     * 通过`admin_id`获取坐席信息
     * @param $adminId
     * @return OutbountSeats|array|bool|mixed|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findSeatByAdminId($adminId)
    {
        $seatInfo = $this->where('admin_id', $adminId)
            ->find();

        return $seatInfo;
    }


    /**
     * 通过`seat_id`获取坐席信息
     * @param $seatId
     * @return OutbountSeats|array|bool|mixed|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findSeatBySeatId($seatId)
    {
        $seatInfo = $this->where('seat_id', $seatId)
            ->find();

        return $seatInfo;
    }
}