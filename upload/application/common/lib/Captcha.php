<?php
/**
 * 验证码类
 *
 * @author
 */
namespace app\common\lib;
class Captcha
{
    protected $_error;
    protected $class;
    protected $engine;
    public function __construct($type='')
    {
        $this->engine = $type?$type:config('global_config.captcha_type');
        $class_name = '\\app\\common\\lib\\captcha\\' . $this->engine;
        $this->class = new $class_name();
    }
    /**
     * 获取前端接入参数
     */
    public function getData()
    {
        switch ($this->engine) {
            case 'picture':
                $data = $this->class->get_config();
                break;
            case 'vaptcha':
                $data = $this->class->get_config('invisible', '', true);
                break;
            case 'tencent':
                $data = $this->class->get_config();
                break;
            default:
                $this->_error = '参数错误';
                return false;
                break;
        }
        if ($data) {
            return $data;
        } else {
            $this->_error = $this->class->getError();
            return false;
        }
    }
    /**
     * 执行验证
     */
    public function verify($data)
    {
        //app验证需要把字符串转为数组
        if(is_string($data['captcha'])){
            $data['captcha'] = htmlspecialchars_decode($data['captcha']);
            $data['captcha'] = json_decode($data['captcha'],1);
        }
        switch ($this->engine) {
            case 'picture':
                $result = $this->class->validate(
                    $data['captcha']['code'],
                    $data['captcha']['secret_str']
                );
                break;
            case 'vaptcha':
                $result = $this->class->validate($data['captcha']['code']);
                break;
            case 'tencent':
                $result = $this->class->validate(
                    $data['captcha']['ticket'],
                    $data['captcha']['randstr']
                );
                break;
            default:
                $this->_error = '验证码二次验证失败';
                return false;
                break;
        }
        if (false === $result) {
            $this->_error = $this->class->getError();
            return false;
        }
        return true;
    }
    public function getError()
    {
        return $this->_error;
    }
}
