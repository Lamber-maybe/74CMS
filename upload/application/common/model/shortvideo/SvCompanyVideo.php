<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/6/24
 * Time: 15:16
 */

namespace app\common\model\shortvideo;


use app\common\model\BaseModel;
use app\common\model\Company;
use app\common\model\Setmeal;


class SvCompanyVideo extends Video
{
    protected $type = 1;

    public function checkCanPublish($uid){
        $setMealId = (new Company())->where(['uid'=>$uid])->value('setmeal_id');
        $setMeals = config('global_config.shortvideo_enable_setmeal');
        $setMeals = array_map('intval', $setMeals);
        if(empty($setMeals)){
            exception('当前禁止所有企业上传视频');
        }
        if(!in_array(intval($setMealId), $setMeals)){
            exception('您的套餐暂不支持使用视频招聘功能，建议您立即升级套餐');
        }
    }
}
