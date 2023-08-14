<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/29
 * Time: 11:31
 */

namespace app\common\model;


class AliAxb extends BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type = [
        'id' => 'integer',
        'addtime' => 'integer',
    ];
    protected $insert = ['addtime'];
    protected function setAddtimeAttr()
    {
        return time();
    }

    public function bind($a, $b, $x, $subid){
        return $this->insert([
            'a' => $a,
            'b' => $b,
            'x' => $x,
            'sub_id' => $subid,
            'addtime' => time()
        ]);
    }

    public function unbind($a, $b){
        $row1 = $this->where(['a'=>$a, 'b'=>$b])->find();
        $row2 = $this->where(['b'=>$a, 'a'=>$b])->find();
        $row = $row1 ? $row1: ($row2 ? $row2: null);
        if($row){
            $this->where(['id'=>$row['id']])->delete();
        }
        return $row;
    }
}
