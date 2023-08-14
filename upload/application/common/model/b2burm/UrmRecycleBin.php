<?php

namespace app\common\model\b2burm;

use app\common\model\BaseModel;

class UrmRecycleBin  extends BaseModel
{


    /**
     * 通过回收站ID删除回收站
     * @param $id
     * @return bool
     */
    public function del($id)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save(
                [
                    'status' => 3
                ],
                [
                    'id' => $id
                ]
            );
        if ($result) {
            return true;
        } else {
            throw new \Exception('删除回收站错误');
        }
    }

    /**
     * 通过回收站ID恢复回收站
     * @param $id
     * @return bool
     */
    public function recover($id)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save(
                [
                    'status' => 2
                ],
                [
                    'id' => $id
                ]
            );
        if ($result) {
            return true;
        } else {
            throw new \Exception('恢复回收站错误');
        }
    }
}