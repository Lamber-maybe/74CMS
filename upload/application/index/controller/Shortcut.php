<?php
namespace app\index\controller;

class Shortcut extends \app\index\controller\Base
{
    public function index()
    {
        $global_config = config('global_config');
        $Shortcut = "[InternetShortcut]
        URL={$global_config['sitedomain']}{$global_config['sitedir']}
        IDList= 
        IconFile={$global_config['sitedomain']}{$global_config['sitedir']}favicon.ico
        IconIndex=100
        [{000214A0-0000-0000-C000-000000000046}]
        Prop3=19,2";
        header("Content-type: application/octet-stream"); 
        header("Content-Disposition: attachment; filename={$global_config['sitename']}.url;"); 
        exit($Shortcut);
    }
}
