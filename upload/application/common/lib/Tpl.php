<?php
namespace app\common\lib;

class Tpl
{
    public $visitor=null;
    public function __construct($visitor){
        $this->visitor = $visitor;
    }
    public function index($index_tpl=''){
        $index_tpl = $index_tpl?$index_tpl:config('global_config.index_tpl');
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('index');
        $pageAlias = 'index_'.$index_tpl;
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('Page')->getCacheByAlias($pageAlias);
        }else{
            $return = false;
        }
        if (!$return) {
            $class_name = '\\app\\common\\lib\\tpl\\index\\' . $index_tpl;
            $instance = new $class_name($this->visitor);
            $return = $instance->getData($pageCache,$pageAlias,$this->visitor);
        }
        return $return;
    }
}
