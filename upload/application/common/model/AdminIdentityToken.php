<?php

namespace app\common\model;

class AdminIdentityToken extends BaseModel
{

    /**
     * 生成 token
     * @access public
     * @param integer $adminId [管理员ID]
     * @param string $token
     * @author edc
     */
    public function makeToken($adminId, $token)
    {
        $expire = config('global_config.token_expire');
        if (intval($expire) >= 10) {
            $expire = $expire * 60;
        } else {
            // 默认7天有效期
            $expire = 604800;
        }

        $token = md5($token);
        $where = [
            'admin_id' => $adminId
        ];
        $tokenAdminId = $this->where($where)->value('admin_id');
        if (empty($tokenAdminId)) {
            $insertData = [
                'admin_id' => $adminId,
                'token' => $token,
                'updatetime' => time(),
                'expire' => $expire
            ];
            return $this->insertGetId($insertData);
        }
        $updateData = [
            'token' => $token,
            'updatetime' => time(),
            'expire' => $expire
        ];
        $this->where($where)->update($updateData);
    }

    /**
     * 刷新 token
     * @access public
     * @param integer $adminId [管理员ID]
     * @return bool
     * @author edc
     */
    public function refreshToken($adminId)
    {
        $where = [
            'admin_id' => $adminId
        ];
        $field = [
            'admin_id',
            'updatetime',
            'expire',
        ];
        $info = $this->field($field)->where($where)->find();
        if (is_null($info) || empty($info) || ($info['updatetime'] + $info['expire']) < time()) {
            $this->where($where)->delete();
            return false;
        }
        $updateData = [
            'updatetime' => time()
        ];
        $this->where($where)->update($updateData);
        return true;
    }
}
