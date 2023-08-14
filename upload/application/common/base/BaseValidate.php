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
    protected $paramData = [];
    protected $interfaceParam = [];
    protected $control = '';
    protected $action = '';
    public  $field = [];
    public  $conditionRule = [];
    public  $condition = [];

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
        if(empty($data)){
            //参数获取
            if (Request::instance()->isPost()) $this->request_data = Request::instance()->post();
            if (Request::instance()->isGet()) $this->request_data = Request::instance()->get();
            $this->request_data = $this->request_data ? $this->request_data : Request::instance()->request();
        } else {
            $this->request_data = $data;
        }

        saveLog('接收到的参数为:'.json_encode($this->request_data));

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
        $this->field = isset($this->interfaceParam[$this->control][$this->action]['field']) ? $this->interfaceParam[$this->control][$this->action]['field'] : '*' ;
        //条件组成规则
        if(isset($this->interfaceParam[$this->control][$this->action]['condition'])){
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
    public function getParamAll($data = [],$control = '',$action = '')
    {
        if(empty($data)){
            return  $this->setAttr($this->interfaceParam[$this->control][$this->action]['validate'],$this->request_data);
        }else {
            if(empty($control) || empty($action)){
                return [];
            }
            return  $this->setAttr($this->interfaceParam[$control][$action]['validate'],$this->request_data);
        }
    }

    /**
     * json 验证
     * @param array $data  验证的参数
     * @param string $key 验证参数的key （field_name）
     * @return array
     */
    public function getJsonData($data = [],$key = ''){
        foreach ($this->interfaceParam[$this->control][$this->action]['validate'] as $rule) {
            if(empty($key)){
                if ($rule['type'] == 'json') {
                    $jsonDataValidate = $rule['rule'];
                }
            } else{
                if ($rule['type'] == 'json' && $rule['field_name'] == $key) {
                    $jsonDataValidate = $rule['rule'];
                }
            }
        }
        if(!isset($jsonDataValidate) || empty($jsonDataValidate)){
            return $data;
        }

        if(is_array($data)){
            $jsonData = [];
            foreach($data as $v){
                if(is_array($v)){
                    $requestData = $v;
                    $jsonData[] = $this->setAttr($jsonDataValidate,$requestData);
                } else {
                    $requestData = $data;
                    $jsonData = $this->setAttr($jsonDataValidate,$requestData);
                    break;
                }
            }
            return $jsonData;
        }
    }
    /**
     * @param $config
     * @param $requestData
     * @return array
     * 写值
     */
    public function setAttr($config,$requestData)
    {
        $data = [];
        foreach ($config as $rule) {
            if (is_array($rule)) {
                $require = isset($rule['require']) ? $rule['require'] : false;
                $default = isset($rule['default']) ? $rule['default'] : null;
                $type = isset($rule['type']) ? $rule['type'] : 'is_string';
                $long = isset($rule['long']) ? $rule['long'] : null;
                $empty = isset($rule['empty']) ? $rule['empty'] : true;
                //是否转换
                $isConversion = isset($rule['is_conversion']) ? $rule['is_conversion'] : true;
                if (isset($rule['eq']) && !empty($rule['eq'])) $eq = $rule['eq'];
                $fieldName = $rule['field_name']; //数据字段名
                $name = $rule['name'];//数据备注名
                $requireValue = isset($rule['require_value']) ? $rule['require_value'] : false;//数据必须是这个范围内的值
                //非必传数据且未传输则跳过
                if (!isset($requestData[$fieldName]) && !$require && !isset($default)) {
                    continue;
                }
                //是否允许该参数传输
                if (!isset($this->paramData[$fieldName])) {
                    responseJson(400, '未能识别的参数信息:' . $fieldName);
                }
                //参数是否必传
                if (!isset($requestData[$fieldName]) && $require) {
                    responseJson(400, '缺少:' . $name);
                }
                //参数能否为空
                if (!$empty && empty($requestData[$fieldName])) {
                    responseJson(400, $name . '不能为空');
                }
                // 判断是否设置默认值
                 if (!is_null($default)) {
                     if(!isset($requestData[$fieldName])){
                         $data[$fieldName] = $default;
                     } else{
                         $data[$fieldName] = trim(isset($requestData[$fieldName]) && empty($requestData[$fieldName]) ? $default : $requestData[$fieldName]);
                     }
                 }else{
                     $data[$fieldName] = isset($requestData[$fieldName]) ? $requestData[$fieldName] : '';
                 }
                //数据类型判断
                if ($type != 'is_string' && $type != 'json') {
                    if (isset($data[$fieldName]) && $data[$fieldName]) {
                        if(!$type($data[$fieldName])){
                            responseJson(400, $name . '数据类型错误');
                        }
                    }
                } else if ($type == 'json') {
                    $arrData = $this->checkJson($data[$fieldName],$rule);
                    $data[$fieldName] = $this->getJsonData($arrData,$fieldName);
                } else {
                    if (isset($data[$fieldName]) && !is_string($data[$fieldName])) {
                        responseJson(400, '未能识别:' . $name . '-的数据类型');
                    }
                }
                // 当设置的是int，强制转换数据类型
                if ($type == 'is_numeric') {
                    $data[$fieldName] = intval($data[$fieldName]);
                }
                //判断数据长度
                if (isset($data[$fieldName]) && !is_null($long) && iconv_strlen($data[$fieldName]) > $long) {
                    responseJson(400, $name . '长度不可超过' . $long . '位');
                }
                //如果有规定必须大于某个数字，但是却小于等于某个数字
                if(isset($rule['gt']) && $data[$fieldName] <= $rule['gt']){
                    responseJson(400, $name . '不能小于等于'.$rule['gt']);
                }
                //如果有规定必须大于或等于某个数字，但是却小于某个数字
                if(isset($rule['egt']) && $data[$fieldName] < $rule['egt']){
                    responseJson(400, $name . '不能小于'.$rule['egt']);
                }
                //如果有规定必须小于某个数字，但是却大于等于某个数字
                if(isset($rule['lt']) && $data[$fieldName] >= $rule['lt']){
                    responseJson(400, $name . '不能大于等于'.$rule['lt']);
                }
                //如果有规定必须小于等于某个数字，但是却大于某个数字
                if(isset($rule['elt']) && $data[$fieldName] > $rule['elt']){
                    responseJson(400, $name . '不能大于'.$rule['elt']);
                }

                //其中一个字段等于另外一个字段的值
                if (isset($requestData[$fieldName]) && isset($eq) && !empty($eq)) {
                    if ($requestData[$fieldName] != $requestData[$eq]) {
                        responseJson(400, '两次输入必须一致');
                    }
                }
                //数据必须是这个范围内的值
                if($requireValue && !in_array($requestData[$fieldName],$requireValue)){
                    responseJson(400, $name . '必须是'.json_encode($requireValue).'范围内的数据');
                }
            }
        }
        return $data;
    }

    /**
     * 检查是否是json字符串
     * @param $string
     * @param $rule
     * @return array|mixed|void
     */
    public function checkJson($string,$rule = [])
    {

        if ($string !== '') {
            $array = json_decode($string, true);
            if (!is_array($array)) {
                responseJson(400, $rule['name'].'不是正确的数据类型');
            }
        } else {
            $array = [];
        }
        return $array;
    }

    /**
     * 检查int类型
     *
     * @param $name
     * @param $value
     *
     * @return int
     */
    public function checkInt($name, $value)
    {
        $res = 0;
        if (preg_match('/^-?\d{1,}$/', $value)) {
            $res = (int)$value;
        } else {
            responseJson(400, $name .'不是int类型：' . $value);
        }

        return $res;
    }

    /**
     * 检查float类型
     *
     * @param $name
     * @param $value
     *
     * @return float
     */
    public function checkFloat($name, $value)
    {
        $res = 0.00;
        if (!is_float($value)) {
            responseJson(400, $name . '不是float类型：' . $value);
        } else {
            $res = $value;
        }
        return $res;
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
     * @param $params
     * @return array
     * 获取条件
     */
    public  function getCondition($params){
        //条件规则
        if(is_string($params) || empty($this->conditionRule) ){
            $this->condition = ['1=1'];
        }
        if(is_array($params)){
            foreach($params as $k=>$v){
                if(!isset($this->conditionRule[$k])) continue;
                $field = $k;
                $value = $v;
                $rule = $this->conditionRule[$k]['rule'];
                $this->assemblyCondition($field,$value,$rule);
            }
        }
        //删除
        if(isset($this->conditionRule['is_del'])){
            $this->condition['is_del'] = 0;
        }
        //是否被禁用
        if(isset($this->conditionRule['is_disabled'])){
            $this->condition['is_disabled'] = 0;
        }
        return $this->condition;
    }
    /**
     * @param $field
     * @param $value
     * @param string $rule
     * @return array
     * 组装条件
     */
    public function assemblyCondition($field,$value,$rule = '')
    {
        switch ($rule) {
            case 'like':
                $this->like($field,$value);
                break;
            case 'not in':
                $this->not_in($field,$value);
                break;
            case 'in':
                $this->in($field,$value);
                break;
            case 'egt':
                $this->egt($field,$value);
                break;
            case 'elt':
                $this->elt($field,$value);
                break;
            case 'gt':
                $this->gt($field,$value);
                break;
            case 'lt':
                $this->lt($field,$value);
                break;
            case 'between':
                $this->between($field,$value[0],$value[1]);
                break;
            case 'not between':
                $this->not_between($field,$value[0],$value[1]);
                break;
            default:
                $this->condition[$field] = $value;
                break;
        }
    }
    private function like($param,$value){
        if(is_string($value)){
            $this->condition[$param] = ['like','%'.$value.'%'];
        }
    }
    private function not_in($param,$value){
        if(is_string($value)) {
            $this->condition[$param] = ['not in', $value];
        }
    }
    private function in($param,$value){
        if(is_string($value)) {
            $this->condition[$param] = ['in', $value];
        }
    }
    private function egt($param,$value){
        $this->condition[$param] = ['egt', $value];
    }
    private function elt($param,$value){
        $this->condition[$param] = ['elt', $value];
    }
    private function gt($param,$value){
        $this->condition[$param] = ['gt', $value];
    }
    private function lt($param,$value){
        $this->condition[$param] = ['lt', $value];
    }
    private function between($param,$value1,$value2){
        $this->condition[$param] = ['between',[$value1,$value2]];
    }
    private function not_between($param,$value1,$value2){
        $this->condition[$param] = ['not between',[$value1,$value2]];
    }

    /**
     * 获取字段
     * @param $key
     * @return mixed
     */
    public function getField($key = ''){
        return empty($key) ? $this->field : $this->field[$key];
    }
}
