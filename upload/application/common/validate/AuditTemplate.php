<?php
/**
 * 审核模板 Validate
 * @author edc
 * @date 2023-7-4
 */

namespace app\common\validate;

class AuditTemplate extends BaseValidate
{
    protected $rule = [
        'id' => 'checkEmpty',
        'type' => 'checkEmpty',
        'content' => 'require|checkEmpty',
    ];

    protected $message = [
        'id' => 'ID不存在',
        'type' => '类型缺失',
        'content' => '请填写模板内容',
    ];

    protected $scene = [
        // 获取列表
        'get_list' => [
            'type'
        ],
        // 添加模板
        'add' => [
            'type',
            'content'
        ],
        // 删除模板
        'delete' => [
            'id'
        ],
    ];

    /**
     * 验证非空
     * int型有传0的不要用
     * @access public
     * @author edc
     */
    protected function checkEmpty($value)
    {
        if (isset($value) && !empty($value)) {
            return true;
        }
        return false;
    }
}
