<?php
/**
 * 呼叫中心
 * --坐席
 */

namespace app\apiadmin\controller\outbound;

use app\common\controller\Backend;
use app\common\lib\outbound\xiaojianke\CallSeat;
use app\common\model\CampusAd;
use think\Db;

class Seat extends Backend
{
    /**
     * 外呼API接口类库
     * @var
     */
    private $_outbound;

    /**
     * 外呼配置信息
     * @var array|mixed
     */
    private $account_config = [];

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        $apiConfig = config('global_config.account_outbound');
        if (empty($apiConfig['app_secret']) || empty($apiConfig['app_id'])) {
            switch ($this->action_name) {
                case 'index':
                    $return = [
                        'total' => 0,
                        'per_page' => 10,
                        'current_page' => 1,
                        'last_page' => 0,
                        'data' => [],
                        'not_unbind_total' => 0
                    ];
                    $this->ajaxReturn(200, 'SUCCESS', $return);
                    break;

                case 'unbindlist':
                    $this->ajaxReturn(200, 'SUCCESS', []);
                    break;

                default:
                    break;
            }

        }

        $this->account_config = $apiConfig;
    }

    /**
     * @Purpose:
     * 获取坐席列表
     * @Method POST
     *
     * @param string $seat_number 座席编号
     * @param integer $seat_status 坐席状态：1|已开通，2|未开通
     * @param string $seat_mobile 坐席手机号
     * @param integer $order_key 排序字段：1|坐席到期时间
     * @param string $order_by 排序方式：asc|升序，desc|降序
     * @param integer $page 当前页
     * @param integer $per_page 每页显示条数
     *
     * @return Jsonp
     *
     * @link {domain}outbound/seat/index
     *
     * @author  yx
     * @version 1.1
     * @since   2022/9/21
     */
    public function index()
    {
        $param = [
            'seat_number' => input('post.seat_number/s', '', 'trim'), // 座席编号
            'seat_status' => input('post.seat_status/d', 0, 'intval'), // 坐席状态：1|已开通，2|未开通
            'seat_mobile' => input('post.seat_mobile/s', '', 'trim'), // 坐席手机号
            'order_key' => input('post.order_key/d', 0, 'intval'), // 排序字段：1|坐席到期时间
            'order_by' => input('post.order_by/s', '', 'trim'), // 排序方式：asc|升序，desc|降序
            'page' => input('post.page/d', '', 'trim'), // 当前页
            'per_page' => input('post.per_page/d', 1, 'intval'), // 每页显示数
        ];

        try {
            $callSeat = new CallSeat($param, $this->account_config);
            $result = $callSeat->getSeatList();

            if (false === $result) {
                $this->ajaxReturn($callSeat->getErrorCode(), $callSeat->getError());
            }

            if (!isset($result['data']) || empty($result['data'])) {
                $return = [
                    'total' => 0,
                    'per_page' => 0,
                    'current_page' => 0,
                    'last_page' => 0,
                    'data' => [],
                    'not_unbind_total' => 0
                ];
                $this->ajaxReturn(200, 'SUCCESS', $return);
            }

            $result_data = $result['data'];

            foreach ($result_data['data'] as &$seat) {
                $admin_name = model('outbound.OutbountSeats')
                    ->alias('os')
                    ->join('admin a', 'os.admin_id = a.id', 'LEFT')
                    ->order('os.id', 'DESC')
                    ->where('os.seat_id', '=', $seat['seat_id'])
                    ->value('a.username');
                if (!empty($admin_name)) {
                    $seat['staff_name'] = $admin_name;
                } else {
                    $seat['staff_name'] = '';
                }
            }

            $this->ajaxReturn(200, 'SUCCESS', $result_data);
        } catch (\Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }
    }


    /**
     * @Purpose:
     * 获取未绑定坐席列表
     * @Method POST
     *
     * @param null
     *
     * @return Jsonp
     *
     * @link {domain}outbound/seat/unbind
     *
     * @author  yx
     * @version 1.1
     * @since   2022/9/30
     */
    public function unbindList()
    {
        $bindSeat = model('outbound.OutbountSeats')
            ->column('seat_id');

        if (empty($bindSeat)) {
            $param = [];
        } else {
            $param = [
                'seat_id' => implode($bindSeat, ',')
            ];
        }

        try {
            $callSeat = new CallSeat($param, $this->account_config);
            $result = $callSeat->getAllSeatList();

            if (false === $result) {
                $this->ajaxReturn($callSeat->getErrorCode(), $callSeat->getError());
            }

            if (!isset($result['data']) || empty($result['data'])) {
                $this->ajaxReturn(200, 'SUCCESS', []);
            }

            $this->ajaxReturn(200, 'SUCCESS', $result['data']);
        } catch (\Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }
    }


    /**
     * @Purpose:
     * 绑定坐席
     * @Method POST
     *
     * @param integer $seat_id 坐席ID
     * @param string $seat_number 坐席编号
     * @param integer $admin_id 坐席员工id
     *
     * @return Jsonp
     *
     * @link {domain}outbound/seat/bindSeat
     *
     * @author  yx
     * @version 1.1
     * @since   2022/9/29
     */
    public function bindSeat()
    {
        $param = [
            'seat_id' => input('post.seat_id/d', 0, 'intval'), // 坐席ID
            'seat_number' => input('post.seat_number/s', '', 'trim'), // 坐席编号
            'admin_id' => input('post.admin_id/d', 0, 'intval') // 员工id
        ];

        if (empty($param['seat_id'])) {
            $this->ajaxReturn(500, '请选择坐席编号');
        }

        if (empty($param['seat_number'])) {
            $this->ajaxReturn(500, '请选择坐席编号');
        }

        if (empty($param['admin_id'])) {
            $this->ajaxReturn(500, '请选择坐席员工');
        }

        Db::startTrans();
        try {
            $isBind = model('outbound.OutbountSeats')->findSeatByAdminId($param['admin_id']);
            if ($isBind) {
                throw new \Exception('该员工已绑定坐席');
            }

            $isSet = model('outbound.OutbountSeats')->findSeatBySeatId($param['seat_id']);
            if ($isSet) {
                throw new \Exception('当前坐席已绑定员工');
            }

            $bindSeat = model('outbound.OutbountSeats')->bindSeat($param);
            if (false === $bindSeat) {
                throw new \Exception(model('outbound.OutbountSeats')->getError());
            }

            model('AdminLog')->record(
                '给【adminid：' . $param['admin_id'] . '】绑定坐席。
                坐席【' . $param['seat_id'] . '-' . $param['seat_number'] . '】',
                $this->admininfo
            );

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, '绑定成功');
    }


    /**
     * @Purpose:
     * 解绑坐席
     * @Method POST
     *
     * @param integer $seat_id 坐席ID
     *
     * @return Jsonp
     *
     * @link {domain}outbound/seat/unbindSeat
     *
     * @author  yx
     * @version 1.1
     * @since   2022/9/29
     */
    public function unbindSeat()
    {
        $seat_id = input('post.seat_id/d', 0, 'intval'); // 坐席ID

        if (empty($seat_id)) {
            $this->ajaxReturn(500, '请选择要解绑的坐席');
        }

        Db::startTrans();
        try {
            $isBind = model('outbound.OutbountSeats')->findSeatBySeatId($seat_id);
            if (!$isBind) {
                throw new \Exception('当前坐席未绑定员工');
            }

            $bindSeat = model('outbound.OutbountSeats')->unbindSeat($seat_id);
            if (false === $bindSeat) {
                throw new \Exception(model('outbound.OutbountSeats')->getError());
            }

            model('AdminLog')->record(
                '解绑坐席。
                【坐席：' . $seat_id . '】',
                $this->admininfo
            );

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, '解绑成功');
    }


    /**
     * @Purpose:
     * 获取坐席员工列表
     * @Method GET
     *
     * @param integer $is_bind 是否绑定坐席员工0-全部 1-绑定 2-未绑定
     *
     * @return Jsonp
     *
     * @link {domain}outbound/seat/staffList
     *
     * @author  yx
     * @version 1.1
     * @since   2022/9/29
     */
    public function staffList()
    {
        $is_bind = input('get.is_bind/d', 0, 'intval'); // 是否绑定坐席员工0-全部 1-绑定 2-未绑定
        switch ($is_bind) {
            case 1:
                $where = ['os.id' => ['not null', '']];
                break;
            case 2:
                $where = ['os.id' => ['null', '']];
                break;
            case 0:
            default:
                $where = [];
                break;
        }

        $staffList = model('admin')
            ->alias('a')
            ->join('OutbountSeats os', 'os.admin_id=a.id', 'left')
            ->where($where)
            ->field("a.id,
            a.username,
            IFNULL( os.seat_id,0) as seat_id,
            IFNULL( os.seat_number,'') as seat_number")
            ->select();
        $this->ajaxReturn(200, '获取成功', $staffList);
    }


}