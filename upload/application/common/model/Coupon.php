<?php
namespace app\common\model;

class Coupon extends \app\common\model\BaseModel
{
    protected $readonly = ['id'];
    protected $type = [
        'id' => 'integer',
        'bind_setmeal_id' => 'integer',
        'days' => 'integer',
        'addtime' => 'integer'
    ];
    /**
     * 获取可用优惠券列表
     */
    public function enableList($uid)
    {
        $map['uid'] = $uid;
        $map['usetime'] = 0;
        $map['deadline'] = ['gt', time()];
        $list = model('CouponRecord')
            ->field('id,coupon_name,coupon_face_value,coupon_bind_setmeal_id')
            ->where($map)
            ->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr = [];
            $arr['id'] = $value['id'];
            $arr['name'] = $value['coupon_name'];
            $arr['face_value'] = $value['coupon_face_value'];
            $return[$value['coupon_bind_setmeal_id']][] = $arr;
        }
        return $return;
    }
    /**
     * 发放优惠券
     */
    public function send($data, $admininfo = null)
    {
        $coupon_id_arr = $data['coupon_id'];
        if (empty($coupon_id_arr)) {
            $this->error = '请选择优惠券';
            return false;
        }
        $setmeal_id = $data['setmeal_id'];
        $uid_arr = is_array($data['uid'])?$data['uid']:[$data['uid']];
        if ($setmeal_id < 0 && empty($uid_arr)) {
            //如果自定义并且没有选择接收会员
            $this->error = '请选择接收会员';
            return false;
        }
        if ($setmeal_id == 0) {
            //全部
            $uid_arr = model('Member')
                ->where('utype', 1)
                ->column('uid');
        } elseif ($setmeal_id > 0) {
            //指定套餐会员
            $uid_arr = model('MemberSetmeal')
                ->where(['setmeal_id' => ['eq', $setmeal_id]])
                ->column('uid');
        }
        if (empty($uid_arr)) {
            return;
        }
        $timestamp = time();
        \think\Db::startTrans();
        try {
            $log['coupon_id'] = implode(',', $coupon_id_arr);
            $log['addtime'] = $timestamp;
            $log['admin_name'] =
                $admininfo === null ? '系统赠送' : $admininfo->username;
            if (false === model('CouponLog')->save($log)) {
                throw new \Exception(model('CouponLog')->getError());
            }
            $coupon_info_list = model('Coupon')
                ->where('id','in',$coupon_id_arr)
                ->select();
            $insert_data = [];
            $coupon_arr = [];
            foreach ($coupon_info_list as $key => $coupon) {
                $record_info['log_id'] = model('CouponLog')->id;
                $record_info['coupon_name'] = $coupon['name'];
                $record_info['coupon_face_value'] = $coupon['face_value'];
                $record_info['coupon_bind_setmeal_id'] =
                    $coupon['bind_setmeal_id'];
                $record_info['deadline'] =
                    $timestamp + $coupon['days'] * 3600 * 24;
                $record_info['usetime'] = 0;
                $record_info['addtime'] = $timestamp;
                foreach ($uid_arr as $k => $value) {
                    $record_info['uid'] = $value;
                    $insert_data[] = $record_info;
                }
                $coupon_arr[] = [
                    'coupon_name' => $coupon['name'],
                    'overtime' => date('Y-m-d', $record_info['deadline'])
                ];
            }
            //通知
            model('NotifyRule')->notify($uid_arr, 1, 'new_coupon', $coupon_arr);
            if (!empty($insert_data)) {
                model('CouponRecord')
                    ->isUpdate(false)
                    ->saveAll($insert_data);
            }
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        return;
    }
}
