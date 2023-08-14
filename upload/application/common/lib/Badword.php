<?php

/**
 * 关键词过滤
 *
 * @author
 */

namespace app\common\lib;

class Badword
{
    public static function filter($content)
    {
        $list = model('Badword')->getCache();
        $find = [];
        $replace = [];
        foreach ($list as $key => $value) {
            $find[] = $value['name'];
            $replace[] = $value['replace_text'];
        }
        if(!empty($find)){
            $content = str_replace($find,$replace,$content);
        }
        return $content;
    }
}
