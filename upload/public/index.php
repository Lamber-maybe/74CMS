<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    die('require PHP >= 5.5.0 !');
}
if (!is_file('./upload/install.lock')) {
    header('Location: ./install.php');
    exit();
}
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
defined('API_LIB_PATH') or
    define('API_LIB_PATH', __DIR__ . '/../application/common/lib/');
defined('SYS_UPLOAD_DIR_NAME') or define('SYS_UPLOAD_DIR_NAME', 'upload');
defined('SYS_UPLOAD_PATH') or
    define('SYS_UPLOAD_PATH', __DIR__ . '/' . SYS_UPLOAD_DIR_NAME . '/');
defined('TOKEN_PATH') or
    define('TOKEN_PATH', __DIR__ . '/../runtime/jwttokens/');
defined('STATIC_PATH') or define('STATIC_PATH', __DIR__ . '/static/');
define('PAY_TEST_MODE', false);
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
