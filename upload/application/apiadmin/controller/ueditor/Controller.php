<?php

namespace app\apiadmin\controller\ueditor;

use app\common\controller\Backend;
use app\common\lib\ueditor\Uploader;

//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
date_default_timezone_set("Asia/ShangHai");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

class Controller extends Backend
{
    public function index()
    {
        $action = input('get.action/s', '', 'trim');

        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("ueditor.config.json")), true);

        switch ($action) {
            case 'config':
                $result = $CONFIG;
                break;

            case 'uploadimage':
                $base64 = "upload";
                $config = array(
                    "pathFormat" => $CONFIG['imagePathFormat'],
                    "maxSize" => $CONFIG['imageMaxSize'],
                    "allowFiles" => $CONFIG['imageAllowFiles']
                );
                $fieldName = $CONFIG['imageFieldName'];
                $result = $this->_actionUpload($fieldName, $config, $base64);
                break;

            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $CONFIG['scrawlPathFormat'],
                    "maxSize" => $CONFIG['scrawlMaxSize'],
                    "allowFiles" => $CONFIG['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = "base64";
                $result = $this->_actionUpload($fieldName, $config, $base64);
                break;

            case 'uploadvideo':
                $base64 = "upload";
                $config = array(
                    "pathFormat" => $CONFIG['videoPathFormat'],
                    "maxSize" => $CONFIG['videoMaxSize'],
                    "allowFiles" => $CONFIG['videoAllowFiles']
                );
                $fieldName = $CONFIG['videoFieldName'];
                $result = $this->_actionUpload($fieldName, $config, $base64);
                break;

            case 'uploadfile':
                $base64 = "upload";
                $config = array(
                    "pathFormat" => $CONFIG['filePathFormat'],
                    "maxSize" => $CONFIG['fileMaxSize'],
                    "allowFiles" => $CONFIG['fileAllowFiles']
                );
                $fieldName = $CONFIG['fileFieldName'];
                $result = $this->_actionUpload($fieldName, $config, $base64);
                break;

            /* 列出文件 */
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                $result = $this->_actionList($allowFiles, $listSize, $path);
                break;

            /* 列出图片 */
            case 'listimage':
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
                $result = $this->_actionList($allowFiles, $listSize, $path);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                /* 上传配置 */
                $config = array(
                    "pathFormat" => $CONFIG['catcherPathFormat'],
                    "maxSize" => $CONFIG['catcherMaxSize'],
                    "allowFiles" => $CONFIG['catcherAllowFiles'],
                    "oriName" => "remote.png"
                );
                $fieldName = $CONFIG['catcherFieldName'];

                $result = $this->_catchImage($config, $fieldName);
                break;

            default:
                $result = ['state' => '请求地址出错'];
                break;
        }

        $callback = input('get.callback/s', '', 'trim');

        if (isset($callback) && !empty($callback)) {
            if (preg_match("/^[\w_]+$/", $callback)) {
                exit(htmlspecialchars($callback) . '(' . json_encode($result) . ')');
            } else {
                $result = ['state' => 'callback参数不合法'];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
        } else {
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
    }

    protected function _actionUpload($fieldName, $config, $base64)
    {
        /* 生成上传实例对象并完成上传 */
        try {
            $up = new Uploader($fieldName, $config, $base64);

            /**
             * 得到上传文件所对应的各个参数,数组结构
             * array(
             *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
             *     "url" => "",            //返回的地址
             *     "title" => "",          //新文件名
             *     "original" => "",       //原始文件名
             *     "type" => ""            //文件类型
             *     "size" => "",           //文件大小
             * )
             */

            /* 返回数据 */
            return $up->getFileInfo();

        } catch (\Exception $exception) {
            return ['state' => $exception->getMessage()];
        }
    }


    protected function _actionList($allowFiles, $listSize, $path)
    {
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "" : "/") . $path;
        $files = getfiles($path, $allowFiles);
        if (!count($files)) {
            return [
                'state' => 'no match file',
                'list' => array(),
                'start' => $start,
                'total' => count($files)
            ];
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }

//        // 倒序
//        for ($i = $end, $list = array(); $i < $len && $i < $end; $i++) {
//            $list[] = $files[$i];
//        }

        /* 返回数据 */
        return [
            'state' => 'SUCCESS',
            'list' => $list,
            'start' => $start,
            'total' => count($files)
        ];
    }


    protected function _catchImage($config, $fieldName)
    {
        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return [
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list' => $list
        ];
    }


    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    protected function getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path)) return null;
        if (substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = array(
                            'url' => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime' => filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }
}