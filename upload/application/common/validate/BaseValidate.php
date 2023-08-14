<?php
namespace app\common\validate;

use think\Validate;

class BaseValidate extends Validate
{
    protected function initValidateRule($model_name)
    {
        $this->scene['default'] = array_keys($this->rule); //初始化场景验证字段
        $custom_field_rule = model('FieldRule')->getCache($model_name);
        if ($custom_field_rule) {
            foreach ($custom_field_rule as $key => $value) {
                $field_name = $value['field_name'];
                //处理自定义字段
                if ('age' === $field_name) {
                    $this->_foreachHandle('minage', $value);
                    $this->_foreachHandle('maxage', $value);
                } else {
                    $this->_foreachHandle($field_name, $value);
                }
            }
        }
    }
    private function _foreachHandle($field_name, $value)
    {
        if (array_key_exists($field_name, $this->rule)) {
            if ($value['is_require'] == 1 && $value['is_display'] == 1) {
                if (false === stripos($this->rule[$field_name], 'require')) {
                    $this->rule[$field_name] =
                        'require|' . $this->rule[$field_name];
                }
            } else {
                if (false !== stripos($this->rule[$field_name], 'require')) {
                    $this->rule[$field_name] = str_replace(
                        'require',
                        '',
                        $this->rule[$field_name]
                    );
                    $this->rule[$field_name] = trim(
                        $this->rule[$field_name],
                        '|'
                    );
                    if ($this->rule[$field_name] == '') {
                        unset($this->rule[$field_name]);
                        // $scene_key = array_search(
                        //     $field_name,
                        //     $this->scene['default']
                        // );
                        // unset($this->scene['default'][$scene_key]);
                    }
                }
            }
        } else {
            $this->rule[$field_name] = 'require';
            // $this->scene['default'][] = $field_name;
        }
    }
    protected function checkMobile($value, $rule, $data)
    {
        if (fieldRegex($value, 'mobile')) {
            return true;
        } else {
            return '请输入正确的手机号码';
        }
    }
    protected function checkQq($value, $rule, $data)
    {
        if (fieldRegex($value, 'qq')) {
            return true;
        } else {
            return '请输入正确的QQ号码';
        }
    }
    protected function checkEmail($value, $rule, $data)
    {
        if (fieldRegex($value, 'email')) {
            return true;
        } else {
            return '请输入正确的邮箱';
        }
    }
}
