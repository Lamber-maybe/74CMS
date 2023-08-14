<?php
namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
    public $map_platform = [
        'app' => 'APP',
        'mobile' => '手机浏览器',
        'wechat' => '微信浏览器',
        'web' => '电脑浏览器',
        'system' => '系统',
    ];
    public $map_ad_platform = [
        'app' => 'APP',
        'mobile' => '触屏端',
        'web' => 'pc端',
    ];
    public $map_education = [
        1 => '初中',
        2 => '高中',
        3 => '中技',
        4 => '中专',
        5 => '大专',
        6 => '本科',
        7 => '硕士',
        8 => '博士',
        9 => '博后',
    ];
    public $map_experience = [
        1 => '应届生',
        2 => '1年',
        3 => '2年',
        4 => '3年',
        5 => '3-5年',
        6 => '5-10年',
        7 => '10年以上',
    ];
    public function getColumn()
    {
        $tablename = $this->getTablename();
        if (false === ($columbs = cache('cache_table_column_' . $tablename))) {
            $res = \think\Db::query(
                'SHOW COLUMNS FROM ' . config('database.prefix') . $tablename
            );
            $columbs = array_column($res, 'Field');
            cache('cache_table_column_' . $tablename, $columbs);
        }
        return $columbs;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getTablename()
    {
        return uncamelize($this->name);
    }
    protected function check_spell_repeat($spell, $index = 0, $id = 0)
    {
        $spell = del_punctuation($spell);
        $spell_index = $index == 0 ? $spell : $spell . $index;
        $map['spell'] = array('eq', $spell_index);
        if ($id > 0) {
            $map['id'] = array('neq', $id);
        }
        $has = $this->where($map)->find();
        if ($has) {
            $index++;
            $spell_index = $this->check_spell_repeat($spell, $index, $id);
        }
        return $spell_index;
    }
    /**
     * 处理薪资显示
     */
    public function handle_wage($minwage, $maxwage, $negotiable = 0)
    {
        $wage_unit = config('global_config.wage_unit');
        if ($negotiable == 0) {
            if ($wage_unit == 1) {
                $minwage =
                $minwage % 1000 == 0
                ? $minwage / 1000 . 'K'
                : round($minwage / 1000, 1) . 'K';
                $maxwage = $maxwage
                ? ($maxwage % 1000 == 0
                    ? $maxwage / 1000 . 'K'
                    : round($maxwage / 1000, 1) . 'K')
                : 0;
            } elseif ($wage_unit == 2) {
                if ($minwage >= 10000) {
                    if ($minwage % 10000 == 0) {
                        $minwage = $minwage / 10000 . '万';
                    } else {
                        $minwage = round($minwage / 10000, 1);
                        $minwage = strpos($minwage, '.')
                        ? str_replace('.', '万', $minwage)
                        : $minwage . '万';
                    }
                } else {
                    if ($minwage % 1000 == 0) {
                        $minwage = $minwage / 1000 . '千';
                    } else {
                        $minwage = round($minwage / 1000, 1);
                        $minwage = strpos($minwage, '.')
                        ? str_replace('.', '千', $minwage)
                        : $minwage . '千';
                    }
                }
                if ($maxwage >= 10000) {
                    if ($maxwage % 10000 == 0) {
                        $maxwage = $maxwage / 10000 . '万';
                    } else {
                        $maxwage = round($maxwage / 10000, 1);
                        $maxwage = strpos($maxwage, '.')
                        ? str_replace('.', '万', $maxwage)
                        : $maxwage . '万';
                    }
                } elseif ($maxwage) {
                    if ($maxwage % 1000 == 0) {
                        $maxwage = $maxwage / 1000 . '千';
                    } else {
                        $maxwage = round($maxwage / 1000, 1);
                        $maxwage = strpos($maxwage, '.')
                        ? str_replace('.', '千', $maxwage)
                        : $maxwage . '千';
                    }
                } else {
                    $maxwage = 0;
                }
            }
            if ($maxwage == 0) {
                $return = '面议';
            } else {
                if ($minwage == $maxwage) {
                    $return = $minwage;
                } else {
                    $return = $minwage . '~' . $maxwage;
                }
            }
        } else {
            $return = '面议';
        }
        return $return;
    }
}
