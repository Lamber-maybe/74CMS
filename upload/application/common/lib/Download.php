<?php

namespace app\common\lib;

class Download
{
    private $speed = 512;   // 下载速度
    public function download($file, $Save_path,$reload=false){
        set_time_limit(0);
        $fp = fopen($file, 'rb');
//        $file_size = $this->getFileSize($file);
//        $ranges = $this->getRange($file_size);

//        header('cache-control:public');
//        header('content-type:application/octet-stream');
////      header('content-disposition:attachment; filename='.$name);
//        if($reload && $ranges!=null){ // 使用续传
//            header('HTTP/1.1 206 Partial Content');
//            header('Accept-Ranges:bytes');
//
//            // 剩余长度
//            header(sprintf('content-length:%u',$ranges['end']-$ranges['start']));
//
//            // range信息
//            header(sprintf('content-range:bytes %s-%s/%s', $ranges['start'], $ranges['end'], $file_size));
//
//            // fp指针跳到断点位置
//            fseek($fp, sprintf('%u', $ranges['start']));
//        }else{
//            header('HTTP/1.1 200 OK');
//            header('content-length:'.$file_size);
//        }
        while(!feof($fp)){
            $content = fread($fp, round($this->speed*1024,0));
            file_put_contents($Save_path,$content,FILE_APPEND);
//            ob_flush();
            //sleep(1); // 用于测试,减慢下载速度
        }
//        ob_end_clean();
        ($fp!=null) && fclose($fp);
    }
    /** 设置下载速度
     * @param int $speed
     */
    public function setSpeed($speed){
        if(is_numeric($speed) && $speed>16 && $speed<4096){
            $this->speed = $speed;
        }
    }

    /** 获取header range信息
     * @param  int   $file_size 文件大小
     * @return Array
     */
    private function getRange($file_size){
        if(isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE'])){
            $range = $_SERVER['HTTP_RANGE'];
            $range = preg_replace('/[\s|,].*/', '', $range);
            $range = explode('-', substr($range, 6));
            if(count($range)<2){
                $range[1] = $file_size;
            }
            $range = array_combine(array('start','end'), $range);
            if(empty($range['start'])){
                $range['start'] = 0;
            }
            if(empty($range['end'])){
                $range['end'] = $file_size;
            }
            return $range;
        }
        return null;
    }
    /**
     * 获取文件大小
     */
    public function getFileSize($url){
        $url = parse_url($url);
        if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error)){
            fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n");
            fputs($fp,"Host:$url[host]\r\n\r\n");
            while(!feof($fp)){
                $tmp = fgets($fp);
                if(trim($tmp) == ''){
                    break;
                }else if(preg_match('/Content-Length:(.*)/si',$tmp,$arr)){
                    return trim($arr[1]);
                }
            }
            return null;
        }else{
            return null;
        }
    }
}
