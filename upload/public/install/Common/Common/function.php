<?php
/**
 * 获取随机字符串
 */
function randstr($length = 6, $special = true)
{
    $hash = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $special && ($chars .= '@#!~?:-=');
    $max = strlen($chars) - 1;
    mt_srand((float) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}
/**
 * 删除目录
 */
function rmdirs($dir,$rm_self=false)  
{  
    if(!is_dir($dir)) return false;
    $d = dir($dir);  
    while (false !== ($child = $d->read())){  
        if($child != '.' && $child != '..'){  
            if(is_dir($dir.'/'.$child)){
                rmdirs($dir.'/'.$child,true);  
            } 
            else{
                unlink($dir.'/'.$child);  
            } 
        }  
    }  
    $d->close();  
    $rm_self && rmdir($dir);  
}
?>