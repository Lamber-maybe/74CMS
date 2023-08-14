<?php
/**
 * 参数过滤 并赋值返回
 * @author chenyang
 * Date Time：2022年3月25日09:11:49
 */

namespace app\common\base;

use think\Request;

abstract class BaseValidate
{
    protected $request_data = [];
    protected $interfaceParam = [];
    protected $control = '';
    protected $action = '';
    public $field = [];
    public $conditionRule = [];
    public $condition = [];
    // 指定类型
    protected $appointType = [
        'is_string',
        'is_numeric',
        'is_array',
        'is_float',
    ];

    public function __construct($data = [])
    {
        $this->initParam($data);
    }

    /**
     * 处理接口参数
     * @param $data
     */
    public function initParam($data)
    {
        $this->control = Request::instance()->controller(); //控制器
        $this->action = Request::instance()->action();//方法
        if (empty($data)) {
            //参数获取
            if (Request::instance()->isPost()) $this->request_data = Request::instance()->post();
            if (Request::instance()->isGet()) $this->request_data = Request::instance()->get();
            $this->request_data = $this->request_data ? $this->request_data : Request::instance()->request();
        } else {
            $this->request_data = $data;
        }

        saveLog('接收到的参数为:' . json_encode($this->request_data));

        //方法名大小写转换
        foreach ($this->interfaceParam as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key => $val) {
                    $this->interfaceParam[$k][strtolower($key)] = $val;
                    if (!$this->checkLower($key)) unset($this->interfaceParam[$k][$key]);
                }
            }
        }

        //参数字段
        $this->field = isset($this->interfaceParam[$this->control][$this->action]['field']) ? $this->interfaceParam[$this->control][$this->action]['field'] : '*';
        //条件组成规则
        if (isset($this->interfaceParam[$this->control][$this->action]['condition'])) {
            $this->conditionRule = $this->interfaceParam[$this->control][$this->action]['condition'];
        }
    }

    /**
     * @param $data
     * @param $control
     * @param $action
     * @return array
     * 获取所有过滤参数
     */
    public function getParamAll($data = [], $control = '', $action = '')
    {
        if (empty($data)) {
            return $this->setAttr($this->interfaceParam[$this->control][$this->action]['validate'], $this->request_data);
        } else {
            if (empty($control) || empty($action)) {
                return [];
            }
            return $this->setAttr($this->interfaceParam[$control][$action]['validate'], $this->request_data);
        }
    }

    /**
     * @param $config
     * @param $requestData
     * @return array
     * 写值
     */
    public function setAttr($config, $requestData)
    {
        $data = [];
        foreach ($config as $rule) {
            if (is_array($rule)) {
                $require = isset($rule['require']) ? $rule['require'] : false;
                $default = isset($rule['default']) ? $rule['default'] : null;
                $type = isset($rule['type']) ? $rule['type'] : 'is_string';
                $long = isset($rule['long']) ? $rule['long'] : null;
                $empty = isset($rule['empty']) ? $rule['empty'] : true;
                // 是否转换
                $isConversion = isset($rule['is_conversion']) ? $rule['is_conversion'] : false;
                if (isset($rule['eq']) && !empty($rule['eq'])) $eq = $rule['eq'];
                $fieldName = $rule['field_name']; //数据字段名
                $name = $rule['name'];//数据备注名
                $requireValue = isset($rule['require_value']) ? $rule['require_value'] : false;//数据必须是这个范围内的值
                //非必传数据且未传输则跳过
                if (!isset($requestData[$fieldName]) && !$require && !isset($default)) {
                    continue;
                }
                //参数是否必传
                if (!isset($requestData[$fieldName]) && $require) {
                    responseJson(400, '缺少:' . $name);
                }
                //参数能否为空
                if (!$empty && empty($requestData[$fieldName])) {
                    responseJson(400, $name . '不能为空');
                }
                // 判断是否是指定范围内的类型
                if (!in_array($type, $this->appointType)) {
                    responseJson(400, '未能识别:' . $name . '-的数据类型');
                }
                // 判断是否设置默认值
                if (!is_null($default)) {
                    if (!isset($requestData[$fieldName])) {
                        $data[$fieldName] = $default;
                    } else {
                        $data[$fieldName] = isset($requestData[$fieldName]) && empty($requestData[$fieldName]) ? $default : $requestData[$fieldName];
                    }
                } else {
                    $data[$fieldName] = isset($requestData[$fieldName]) ? $requestData[$fieldName] : '';
                }

                $data[$fieldName] = !is_array($data[$fieldName]) ? trim($data[$fieldName]) : $data[$fieldName];

                //数据类型判断
                if (isset($data[$fieldName]) && $data[$fieldName]) {
                    if (!$type($data[$fieldName])) {
                        responseJson(400, $name . '数据类型错误');
                    }
                }
                // 校验数组类型的数据
                if ($type == 'is_array' && isset($rule['deep']) && !empty($rule['deep'])) {
                    $data[$fieldName] = $this->getArrayData($rule['deep'], $data[$fieldName]);
                }
                // 当设置的是int，强制转换数据类型
                if ($type == 'is_numeric' && is_numeric($data[$fieldName])) {
                    $data[$fieldName] = intval($data[$fieldName]);
                }
                //判断数据长度
                if (isset($data[$fieldName]) && !is_null($long) && iconv_strlen($data[$fieldName]) > $long) {
                    responseJson(400, $name . '长度不可超过' . $long . '位');
                }
                //如果有规定必须大于某个数字，但是却小于等于某个数字
                if (isset($rule['gt']) && $data[$fieldName] <= $rule['gt']) {
                    responseJson(400, $name . '不能小于等于' . $rule['gt']);
                }
                //如果有规定必须大于或等于某个数字，但是却小于某个数字
                if (isset($rule['egt']) && $data[$fieldName] < $rule['egt']) {
                    responseJson(400, $name . '不能小于' . $rule['egt']);
                }
                //如果有规定必须小于某个数字，但是却大于等于某个数字
                if (isset($rule['lt']) && $data[$fieldName] >= $rule['lt']) {
                    responseJson(400, $name . '不能大于等于' . $rule['lt']);
                }
                //如果有规定必须小于等于某个数字，但是却大于某个数字
                if (isset($rule['elt']) && $data[$fieldName] > $rule['elt']) {
                    responseJson(400, $name . '不能大于' . $rule['elt']);
                }
                //其中一个字段等于另外一个字段的值
                if (isset($requestData[$fieldName]) && isset($eq) && !empty($eq)) {
                    if ($requestData[$fieldName] != $requestData[$eq]) {
                        responseJson(400, '两次输入必须一致');
                    }
                }
                //数据必须是这个范围内的值
                if ($requireValue && !in_array($requestData[$fieldName], $requireValue)) {
                    responseJson(400, $name . '必须是' . json_encode($requireValue) . '范围内的数据');
                }
                // 时间类型数据 转换为时间戳
                if (strstr($fieldName, 'time') !== false && $isConversion) {
                    if (empty($data[$fieldName])) {
                        continue;
                    }
                    // 取出 - 存在的次数
                    $time = array_count_values(str_split($data[$fieldName]));
                    // 如果小于两次 则不是时间格式
                    if (!isset($time['-']) || $time['-'] < 2) {
                        responseJson(400, $name . '数据类型错误');
                    }
                    $data[$fieldName] = strtotime($data[$fieldName]);
                }
            }
        }
        return $data;
    }

    /**
     * @param $str
     * @return bool
     * 检查是否纯小写字母
     */
    protected function checkLower($str)
    {
        for ($i = 0; $i < strlen($str); $i++) {
            if ((ord($str[$i]) >= ord('A')) && (ord($str[$i]) <= ord('Z'))) {
                return false;
            }
        }
        return true;

    }

    /**
     * 获取字段
     * @param $key
     * @return mixed
     */
    public function getField($key = '')
    {
        return empty($key) ? $this->field : $this->field[$key];
    }

    /**
     * array 验证
     * @param array $deepData
     * @param string $data
     * @return array
     */
    public function getArrayData($deepData = [], $data = [])
    {
        $arrayData = [];
        if (is_array($data)) {
            foreach ($data as $v) {
                if (is_array($v)) {
                    $requestData = $v;
                    $arrayData[] = $this->setAttr($deepData, $requestData);
                } else {
                    $requestData = $data;
                    $arrayData = $this->setAttr($deepData, $requestData);
                    break;
                }
            }
        }
        return $arrayData;
    }

}
