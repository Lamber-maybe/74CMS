<?php
namespace app\common\model;

class MarketTask extends \app\common\model\BaseModel
{
    public function recordQueue($task_id)
    {
        $info = $this->find($task_id);
        $model = $this->parseCondition(
            $info['target'],
            json_decode($info['condition'], true)
        );
        $uid_arr = $model->column('a.uid');
        if (!empty($uid_arr)) {
            $send_type_arr = explode(',', $info['send_type']);
            $member_list = model('Member')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'member_bind b',
                    'a.uid=b.uid',
                    'LEFT'
                )
                ->where('a.uid', 'in', $uid_arr)
                ->column('a.uid,a.mobile,a.email,b.openid,b.is_subscribe');
        } else {
            $member_list = [];
        }
        $setsqlarr = [];
        foreach ($member_list as $key => $value) {
            $arr['task_id'] = $task_id;
            $arr['uid'] = $value['uid'];
            $arr['message'] = in_array('message', $send_type_arr) ? 1 : 0;
            $arr['mobile'] = in_array('sms', $send_type_arr)
                ? ($value['mobile']
                    ? $value['mobile']
                    : '')
                : '';
            $arr['email'] = in_array('email', $send_type_arr)
                ? ($value['email']
                    ? $value['email']
                    : '')
                : '';
            if(in_array('weixin', $send_type_arr) && $value['openid']!='' && $value['is_subscribe']==1){
                $arr['weixin_openid'] = $value['openid'];
            }else{
                $arr['weixin_openid'] = '';
            }
            $setsqlarr[] = $arr;
        }
        model('MarketQueue')->saveAll($setsqlarr);
        return true;
    }
    public function countTotal($target, $condition)
    {
        $model = $this->parseCondition($target, $condition);
        return $model->count('distinct a.uid');
    }
    protected function parseCondition($target, $condition)
    {
        $target = strtolower($target);
        $target = ucfirst($target);
        $fun_name = '_parseConditionOf' . $target;
        $model = $this->$fun_name($condition);
        return $model;
    }
    protected function _parseConditionOfAll($condition)
    {
        $model = model('Member')->alias('a');
        if (
            isset($condition['reg_time']) &&
            intval($condition['reg_time']) > 0
        ) {
            $settr = intval($condition['reg_time']);
            $model = $model->where(
                'a.reg_time',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (
            isset($condition['login_time']) &&
            intval($condition['login_time']) > 0
        ) {
            $settr = intval($condition['login_time']);
            $model = $model->where(
                'a.last_login_time',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (isset($condition['auth_email'])) {
            $auth_email = intval($condition['auth_email']);
            switch ($auth_email) {
                case 0:
                    $model = $model->where('a.email', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.email', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_mobile'])) {
            $auth_mobile = intval($condition['auth_mobile']);
            switch ($auth_mobile) {
                case 0:
                    $model = $model->where('a.mobile', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.mobile', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_weixin'])) {
            $auth_weixin = intval($condition['auth_weixin']);
            switch ($auth_weixin) {
                case 0:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NULL');
                    break;
                case 1:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NOT NULL');
                    break;
                default:
                    break;
            }
        }
        return $model;
    }
    protected function _parseConditionOfResume($condition)
    {
        $model = model('Member')
            ->alias('a')
            ->join(
                config('database.prefix') . 'resume b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->where('a.utype', 2);
        if (
            isset($condition['reg_time']) &&
            intval($condition['reg_time']) > 0
        ) {
            $settr = intval($condition['reg_time']);
            $model = $model->where(
                'a.reg_time',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (
            isset($condition['login_time']) &&
            intval($condition['login_time']) > 0
        ) {
            $settr = intval($condition['login_time']);
            $model = $model->where(
                'a.last_login_time',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (isset($condition['photo']) && intval($condition['photo']) > -1) {
            $photo = intval($condition['photo']);
            switch ($photo) {
                case 0:
                    $model = $model->where('b.photo_img', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('b.photo_img', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['module']) && count($condition['module']) > 0) {
            $model = $model->join(
                config('dababase.prefix') . 'resume_complete c',
                'a.uid=c.uid',
                'LEFT'
            );
            foreach ($condition['module'] as $key => $value) {
                $model = $model->where('c.' . $value, 'eq', 0);
            }
        }
        if (
            (isset($condition['jobcategory']) &&
                count($condition['jobcategory']) > 0) ||
            (isset($condition['district']) && count($condition['district']) > 0)
        ) {
            $model = $model->join(
                config('dababase.prefix') . 'resume_intention d',
                'a.uid=d.uid',
                'LEFT'
            );
            if (
                isset($condition['jobcategory']) &&
                count($condition['jobcategory']) > 0
            ) {
                $tmp_str = '';
                foreach ($condition['jobcategory'] as $key => $value) {
                    $arr_lenth = count($value);
                    $tmp_str .=
                        ' or d.category' .
                        $arr_lenth .
                        '=' .
                        $value[$arr_lenth - 1];
                }
                if ($tmp_str != '') {
                    $tmp_str = trim($tmp_str, ' ');
                    $tmp_str = trim($tmp_str, 'or');
                    $model = $model->where($tmp_str);
                }
            }
            if (
                isset($condition['district']) &&
                count($condition['district']) > 0
            ) {
                $tmp_str = '';
                foreach ($condition['district'] as $key => $value) {
                    $arr_lenth = count($value);
                    $tmp_str .=
                        ' or d.district' .
                        $arr_lenth .
                        '=' .
                        $value[$arr_lenth - 1];
                }
                if ($tmp_str != '') {
                    $tmp_str = trim($tmp_str, ' ');
                    $tmp_str = trim($tmp_str, 'or');
                    $model = $model->where($tmp_str);
                }
            }
        }
        if (
            isset($condition['education']) &&
            count($condition['education']) > 0
        ) {
            $model = $model->where(
                'b.education',
                'in',
                $condition['education']
            );
        }
        if (
            isset($condition['experience']) &&
            count($condition['experience']) > 0
        ) {
            $tmp_str = '';
            foreach ($condition['experience'] as $key => $value) {
                switch ($value) {
                    case 1: //无经验/应届生
                        $tmp_str .= ' or b.enter_job_time=0';
                        break;
                    case 2:
                        $tmp_str .=
                            ' or b.enter_job_time>' . strtotime('-2 year');
                        break;
                    case 3:
                        $tmp_str .=
                            ' or (b.enter_job_time<=' .
                            strtotime('-2 year') .
                            ' and b.enter_job_time>' .
                            strtotime('-3 year') .
                            ')';
                        break;
                    case 4:
                        $tmp_str .=
                            ' or (b.enter_job_time<=' .
                            strtotime('-3 year') .
                            ' and b.enter_job_time>' .
                            strtotime('-4 year') .
                            ')';
                        break;
                    case 5:
                        $tmp_str .=
                            ' or (b.enter_job_time<=' .
                            strtotime('-3 year') .
                            ' and b.enter_job_time>' .
                            strtotime('-5 year') .
                            ')';
                        break;
                    case 6:
                        $tmp_str .=
                            ' or (b.enter_job_time<=' .
                            strtotime('-5 year') .
                            ' and b.enter_job_time>' .
                            strtotime('-10 year') .
                            ')';
                        break;
                    case 7:
                        $tmp_str .=
                            ' or b.enter_job_time<=' . strtotime('-10 year');
                        break;
                    default:
                        break;
                }
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }

        if (isset($condition['auth_email'])) {
            $auth_email = intval($condition['auth_email']);
            switch ($auth_email) {
                case 0:
                    $model = $model->where('a.email', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.email', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_mobile'])) {
            $auth_mobile = intval($condition['auth_mobile']);
            switch ($auth_mobile) {
                case 0:
                    $model = $model->where('a.mobile', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.mobile', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_weixin'])) {
            $auth_weixin = intval($condition['auth_weixin']);
            switch ($auth_weixin) {
                case 0:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NULL');
                    break;
                case 1:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NOT NULL');
                    break;
                default:
                    break;
            }
        }
        return $model;
    }
    protected function _parseConditionOfCompany($condition)
    {
        $model = model('Member')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->where('a.utype', 1);
        if (
            isset($condition['reg_time']) &&
            intval($condition['reg_time']) > 0
        ) {
            $settr = intval($condition['reg_time']);
            $model = $model->where(
                'a.reg_time',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (
            isset($condition['login_time']) &&
            intval($condition['login_time']) > 0
        ) {
            $settr = intval($condition['login_time']);
            $model = $model->where(
                'a.last_login_time',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }

        if (isset($condition['nature']) && count($condition['nature']) > 0) {
            $model = $model->where('b.nature', 'in', $condition['nature']);
        }

        if (isset($condition['trade']) && count($condition['trade']) > 0) {
            $model = $model->where('b.trade', 'in', $condition['trade']);
        }
        if (
            isset($condition['district']) &&
            count($condition['district']) > 0
        ) {
            $tmp_str = '';
            foreach ($condition['district'] as $key => $value) {
                $arr_lenth = count($value);
                $tmp_str .=
                    ' or b.district' .
                    $arr_lenth .
                    '=' .
                    $value[$arr_lenth - 1];
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }

        if (isset($condition['tag']) && count($condition['tag']) > 0) {
            foreach ($condition['tag'] as $key => $value) {
                $model = $model->where('FIND_IN_SET("' . $value . '",b.tag)');
            }
        }
        if (
            isset($condition['setmeal_id']) &&
            count($condition['setmeal_id']) > 0
        ) {
            $model = $model
                ->join(
                    config('dababase.prefix') . 'member_setmeal c',
                    'a.uid=c.uid',
                    'LEFT'
                )
                ->where('b.setmeal_id', 'in', $condition['setmeal_id']);
        }

        if (isset($condition['auth_cominfo'])) {
            $auth_cominfo = intval($condition['auth_cominfo']);
            switch ($auth_cominfo) {
                case 0:
                    $model = $model->where('b.audit', 'eq', 0);
                    break;
                case 1:
                    $model = $model->where('b.audit', 'eq', 1);
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_report'])) {
            $auth_report = intval($condition['auth_report']);
            $model = $model->join(
                config('dababase.prefix') . 'company_report d',
                'a.uid=d.uid',
                'LEFT'
            );
            switch ($auth_report) {
                case 0:
                    $model = $model->where('d.uid', 'NULL');
                    break;
                case 1:
                    $model = $model->where('d.uid', 'NOT NULL');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_email'])) {
            $auth_email = intval($condition['auth_email']);
            switch ($auth_email) {
                case 0:
                    $model = $model->where('a.email', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.email', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_mobile'])) {
            $auth_mobile = intval($condition['auth_mobile']);
            switch ($auth_mobile) {
                case 0:
                    $model = $model->where('a.mobile', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.mobile', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_weixin'])) {
            $auth_weixin = intval($condition['auth_weixin']);
            switch ($auth_weixin) {
                case 0:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NULL');
                    break;
                case 1:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NOT NULL');
                    break;
                default:
                    break;
            }
        }
        return $model;
    }
    protected function _parseConditionOfJob($condition)
    {
        $model = model('Member')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.uid=b.uid', 'LEFT')
            ->where('a.utype', 1);

        if (
            isset($condition['refreshtime']) &&
            intval($condition['refreshtime']) > 0
        ) {
            $settr = intval($condition['refreshtime']);
            $model = $model->where(
                'b.refreshtime',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (
            isset($condition['jobcategory']) &&
            count($condition['jobcategory']) > 0
        ) {
            $tmp_str = '';
            foreach ($condition['jobcategory'] as $key => $value) {
                $arr_lenth = count($value);
                $tmp_str .=
                    ' or b.category' .
                    $arr_lenth .
                    '=' .
                    $value[$arr_lenth - 1];
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }
        if (isset($condition['trade']) && count($condition['trade']) > 0) {
            $model = $model->where('b.trade', 'in', $condition['trade']);
        }

        if (isset($condition['tag']) && count($condition['tag']) > 0) {
            foreach ($condition['tag'] as $key => $value) {
                $model = $model->where('FIND_IN_SET("' . $value . '",b.tag)');
            }
        }

        if (isset($condition['wage']) && count($condition['wage']) > 0) {
            $tmp_str = '';
            foreach ($condition['wage'] as $key => $value) {
                switch ($value) {
                    case 0: //面议
                        $tmp_str .= ' or b.negotiable=1';
                        break;
                    case 15000:
                        $tmp_str .= ' or b.maxwage>=15000';
                        break;
                    default:
                        if (false !== stripos($value, '-')) {
                            $arr = explode('-', $value);
                            $tmp_str .=
                                ' or (b.maxwage>=' .
                                $arr[0] .
                                ' and b.minwage<' .
                                $arr[1] .
                                ')';
                        }
                        break;
                }
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }

        if (
            isset($condition['setmeal_id']) &&
            count($condition['setmeal_id']) > 0
        ) {
            $model = $model
                ->join(
                    config('dababase.prefix') . 'member_setmeal c',
                    'a.uid=c.uid',
                    'LEFT'
                )
                ->where('setmeal_id', 'in', $condition['setmeal_id']);
        }

        if (isset($condition['auth_cominfo'])) {
            $auth_cominfo = intval($condition['auth_cominfo']);
            $model = $model->join(
                config('dababase.prefix') . 'company d',
                'a.uid=d.uid',
                'LEFT'
            );
            switch ($auth_cominfo) {
                case 0:
                    $model = $model->where('d.audit', 'eq', 0);
                    break;
                case 1:
                    $model = $model->where('d.audit', 'eq', 1);
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_report'])) {
            $auth_report = intval($condition['auth_report']);
            $model = $model->join(
                config('dababase.prefix') . 'company_report e',
                'a.uid=e.uid',
                'LEFT'
            );
            switch ($auth_report) {
                case 0:
                    $model = $model->where('e.uid', 'NULL');
                    break;
                case 1:
                    $model = $model->where('e.uid', 'NOT NULL');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_email'])) {
            $auth_email = intval($condition['auth_email']);
            switch ($auth_email) {
                case 0:
                    $model = $model->where('a.email', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.email', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_mobile'])) {
            $auth_mobile = intval($condition['auth_mobile']);
            switch ($auth_mobile) {
                case 0:
                    $model = $model->where('a.mobile', 'eq', '');
                    break;
                case 1:
                    $model = $model->where('a.mobile', 'neq', '');
                    break;
                default:
                    break;
            }
        }
        if (isset($condition['auth_weixin'])) {
            $auth_weixin = intval($condition['auth_weixin']);
            switch ($auth_weixin) {
                case 0:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NULL');
                    break;
                case 1:
                    $model = $model
                        ->join(
                            config('database.prefix') .
                                'member_bind bind_weixin',
                            'a.uid=bind_weixin.uid',
                            'LEFT'
                        )
                        ->where('bind_weixin.type', 'weixin')
                        ->where('bind_weixin.is_subscribe', 1)
                        ->where('bind_weixin.openid', 'NOT NULL');
                    break;
                default:
                    break;
            }
        }
        return $model;
    }
    protected function _parseConditionOfRemind($condition)
    {
        $model = model('Member')->alias('a');
        if (
            isset($condition['no_login']) &&
            intval($condition['no_login']) > 0
        ) {
            $settr = intval($condition['no_login']);
            $model = $model->where(
                'a.last_login_time',
                'lt',
                strtotime('-' . $settr . 'day')
            );
        }
        $utype = isset($condition['utype']) ? intval($condition['utype']) : 0;
        if ($utype == 1) {
            $model = $model->where('a.utype', 1);
            if (
                isset($condition['no_refresh']) &&
                intval($condition['no_refresh']) > 0
            ) {
                $settr = intval($condition['no_refresh']);
                $model = $model
                    ->join(
                        config('database.prefix') . 'company b',
                        'a.uid=b.uid',
                        'LEFT'
                    )
                    ->where(
                        'b.refreshtime',
                        'lt',
                        strtotime('-' . $settr . 'day')
                    );
            }
            if (isset($condition['publish_job'])) {
                $publish_job = intval($condition['publish_job']);
                switch ($publish_job) {
                    case 0:
                        $model = $model
                            ->join(
                                config('database.prefix') . 'job c',
                                'a.uid=c.uid',
                                'LEFT'
                            )
                            ->where('c.id', 'NULL');
                        break;
                    case 1:
                        $model = $model
                            ->join(
                                config('database.prefix') . 'job c',
                                'a.uid=c.uid',
                                'LEFT'
                            )
                            ->where('c.id', 'NOT NULL');
                        break;
                    default:
                        break;
                }
            }
        }
        if ($utype == 2) {
            $model = $model->where('a.utype', 2);
            if (
                isset($condition['no_refresh']) &&
                intval($condition['no_refresh']) > 0
            ) {
                $settr = intval($condition['no_refresh']);
                $model = $model
                    ->join(
                        config('database.prefix') . 'resume b',
                        'a.uid=b.uid',
                        'LEFT'
                    )
                    ->where(
                        'b.refreshtime',
                        'lt',
                        strtotime('-' . $settr . 'day')
                    );
            }
            if (
                isset($condition['module']) &&
                count($condition['module']) > 0
            ) {
                $model = $model->join(
                    config('dababase.prefix') . 'resume_complete c',
                    'a.uid=c.uid',
                    'LEFT'
                );
                foreach ($condition['module'] as $key => $value) {
                    $model = $model->where('c.' . $value, 'eq', 0);
                }
            }
        }
        return $model;
    }
    protected function _parseConditionOfUid($condition)
    {
        $uid = isset($condition['uid']) ? intval($condition['uid']) : 0;
        $model = model('Member')
            ->alias('a')
            ->where('a.uid', 'eq', $uid);
        return $model;
    }
    protected function _parseConditionOfSetmeal($condition)
    {
        $model = model('MemberSetmeal')->alias('a');
        if (
            isset($condition['setmeal_id']) &&
            count($condition['setmeal_id']) > 0
        ) {
            $model = $model->where(
                'a.setmeal_id',
                'in',
                $condition['setmeal_id']
            );
        }
        if (
            isset($condition['overtime']) &&
            intval($condition['overtime']) >= 0
        ) {
            $settr = intval($condition['overtime']);
            if ($settr == 0) {
                $model = $model->where('a.deadline', 'lt', time());
            } else {
                $model = $model->where(
                    'a.deadline',
                    'lt',
                    strtotime('+' . $settr . 'day')
                );
            }
        }

        return $model;
    }
}
