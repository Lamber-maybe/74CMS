<?php
/**
 * 生成推广海报
 */
namespace app\common\lib;

use app\common\model\shortvideo\SvCompanyVideo;
use app\common\model\shortvideo\SvPersonalVideo;
use app\common\model\shortvideo\Video;

class Poster
{
    protected $error='';
    protected $fontPath;

    public function __construct()
    {
        $this->fontPath = API_LIB_PATH.'poster/font.ttf';
    }

    public function create($config=array(),$filename="")
    {
        //如果要看报什么错，可以先注释调这个header
        if(empty($filename)) header("content-type: image/png");
        $imageDefault = array(
            'left'=>0,
            'top'=>0,
            'right'=>0,
            'bottom'=>0,
            'width'=>100,
            'height'=>100,
            'opacity'=>100
        );
        $textDefault = array(
            'text'=>'',
            'left'=>0,
            'top'=>0,
            'fontSize'=>32,       //字号
            'fontColor'=>'255,255,255', //字体颜色
            'angle'=>0,
        );
        $background = $config['background'];//海报最底层得背景
        //背景方法
        $backgroundInfo = getimagesize($background);
        $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFun($background);
        $backgroundWidth = imagesx($background);  //背景宽度
        $backgroundHeight = imagesy($background);  //背景高度
        $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        // imageColorTransparent($imageRes, $color);  //颜色透明
        imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
        //处理了图片
        if(!empty($config['image'])){
            foreach ($config['image'] as $key => $val) {
                $val = array_merge($imageDefault,$val);
                $info = getimagesize($val['url']);
                $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
                if($val['stream']){   //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
                //建立画板 ，缩放图片至指定尺寸
                $canvas=imagecreatetruecolor($val['width'], $val['height']);

                // 圆角处理
                if(isset($val['radius']) && $val['radius']==1){
                    $bg = imagecolorallocatealpha($canvas, 255, 255, 255,127);
                    imagecolortransparent($canvas,$bg);
                    imagefill($canvas, 0, 0, $bg);
                }else{
                    imagefill($canvas, 0, 0, $color);
                }



                //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
                //放置图像
                imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
            }
        }
        //处理文字
        if(!empty($config['text'])){
            foreach ($config['text'] as $key => $val) {
                $val = array_merge($textDefault,$val);
                list($R,$G,$B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
                $val['text'] = $this->toEntities($val['text']);
                $rect = imagettfbbox($val['fontSize'],$val['angle'],$val['fontPath'],$val['text']);
                $minX = min(array($rect[0],$rect[2],$rect[4],$rect[6]));
                $maxX = max(array($rect[0],$rect[2],$rect[4],$rect[6]));
                $minY = min(array($rect[1],$rect[3],$rect[5],$rect[7]));
                $maxY = max(array($rect[1],$rect[3],$rect[5],$rect[7]));
                if($val['center_x']==1){
                    $val['left'] = ($backgroundWidth - ($maxX - $minX))/2;
                }else if(isset($val['float_right']) && $val['float_right']==1){
                    $val['left'] = -($maxX - $minX + 80);
                }
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];


                if($val['center_y']==1){
                    $val['top'] = ($backgroundHeight - ($maxY - $minY))/2;
                }else{
                    $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
                }

                imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
            }
        }
        //生成图片
        if(!empty($filename)){
            $res = imagepng($imageRes,$filename,5); //保存到本地
            imagedestroy($imageRes);
            if(!$res){
                return false;
            }
            return $filename;
        }else{
            imagejpeg($imageRes);     //在浏览器上显示
            imagedestroy($imageRes);
            exit;
        }
    }
    protected function toEntities($string){
        $len = strlen($string);
        $buf = "";
        for($i = 0; $i < $len; $i++){
            if (ord($string[$i]) <= 127){
                $buf .= $string[$i];
            } else if (ord ($string[$i]) <192){
                //unexpected 2nd, 3rd or 4th byte
                $buf .= "&#xfffd";
            } else if (ord ($string[$i]) <224){
                //first byte of 2-byte seq
                $buf .= sprintf("&#%d;",
                    ((ord($string[$i + 0]) & 31) << 6) +
                    (ord($string[$i + 1]) & 63)
                );
                $i += 1;
            } else if (ord ($string[$i]) <240){
                //first byte of 3-byte seq
                $buf .= sprintf("&#%d;",
                    ((ord($string[$i + 0]) & 15) << 12) +
                    ((ord($string[$i + 1]) & 63) << 6) +
                    (ord($string[$i + 2]) & 63)
                );
                $i += 2;
            } else {
                //first byte of 4-byte seq
                $buf .= sprintf("&#%d;",
                    ((ord($string[$i + 0]) & 7) << 18) +
                    ((ord($string[$i + 1]) & 63) << 12) +
                    ((ord($string[$i + 2]) & 63) << 6) +
                    (ord($string[$i + 3]) & 63)
                );
                $i += 3;
            }
        }
        return $buf;
    }
    public function getError(){
        return $this->error;
    }
    /**
     * 职位海报
     */
    public function makeJobPoster($index,$id){
        $info = model('Job')
            ->where('id', 'eq', $id)
            ->field('uid',true)
            ->find();
        if($info===null){
            $this->error = '没有找到职位信息';
            return false;
        }
        $companyinfo = model('Company')->where('id', 'eq', $info['company_id'])->find();
        if($companyinfo===null){
            $this->error = '没有找到企业信息';
            return false;
        }
        $filename = $id.'_'.$info['updatetime'].'_'.$index.'.jpg';
        $save_dir = SYS_UPLOAD_PATH.'poster/job/'.($id%10).'/';
        if(!is_dir($save_dir)){
            mkdir($save_dir,0777,true);
        }
        $save_path = $save_dir.$filename;
        $show_path = config('global_config.sitedomain').config('global_config.sitedir').SYS_UPLOAD_DIR_NAME.'/poster/job/'.($id%10).'/'.$filename;
        if(file_exists($save_path)){
            return $show_path;
        }

        $category_district_data = model('CategoryDistrict')->getCache();
        $info['wage_text'] = model('BaseModel')->handle_wage(
            $info['minwage'],
            $info['maxwage'],
            $info['negotiable']
        );
        $info['nature_text'] = isset(
            model('Job')->map_nature[$info['nature']]
        )
        ? model('Job')->map_nature[$info['nature']]
        : '全职';
        $info['education_text'] = isset(
            model('BaseModel')->map_education[$info['education']]
        )
        ? model('BaseModel')->map_education[$info['education']]
        : '学历不限';
        $info['experience_text'] = isset(
            model('BaseModel')->map_experience[$info['experience']]
        )
        ? model('BaseModel')->map_experience[$info['experience']]
        : '经验不限';
        $info['district_text'] = isset(
            $category_district_data[$info['district']]
        )
        ? $category_district_data[$info['district']]
        : '';

        $locationUrl = config('global_config.mobile_domain').'job/'.$info['id'];
        $info['qrcode'] = config('global_config.sitedomain').config('global_config.sitedir').'v1_0/home/qrcode/index?alias=subscribe_job&url='.$locationUrl.'&jobid='.$info['id'];
        switch($index){
            case 1:
                $config = [
                    'image'=>[
                        [
                            'url'=>$info['qrcode'],//二维码
                            'stream'=>0,
                            'left'=>560,
                            'top'=>-180,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>140,
                            'height'=>140,
                            'opacity'=>100
                        ]
                    ],
                    'text'=>[
                        [
                            'text'=>cut_str($companyinfo['companyname'],18,0,'...'),
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>1210,
                            'center_y'=>0,
                            'fontSize'=>26,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['jobname'],
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>1000,
                            'center_y'=>0,
                            'fontSize'=>40,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['wage_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>1076,
                            'center_y'=>0,
                            'fontSize'=>38,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['nature_text'].' | '.$info['education_text'].' | '.$info['experience_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>1140,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>100,
                            'center_y'=>0,
                            'fontSize'=>30,       //字号
                            'fontColor'=>'255,255,255', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/job'.$index.'.jpg' //背景图
                ];
                break;
            case 2:
                $config = [
                    'image'=>[
                      [
                        'url'=>$info['qrcode'],//二维码
                        'stream'=>0,
                        'left'=>530,
                        'top'=>-150,
                        'right'=>0,
                        'bottom'=>0,
                        'width'=>140,
                        'height'=>140,
                        'opacity'=>100
                      ]
                    ],
                    'text'=>[
                        [
                            'text'=>cut_str($companyinfo['companyname'],12,0,'...'),
                            'fontPath'=>$this->fontPath,
                            'left'=>80,
                            'center_x'=>0,
                            'top'=>1090,
                            'center_y'=>0,
                            'fontSize'=>26,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['jobname'],
                            'fontPath'=>$this->fontPath,
                            'left'=>100,
                            'center_x'=>0,
                            'top'=>880,
                            'center_y'=>0,
                            'fontSize'=>40,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['wage_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>100,
                            'center_x'=>0,
                            'top'=>800,
                            'center_y'=>0,
                            'fontSize'=>38,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['nature_text'].' | '.$info['education_text'].' | '.$info['experience_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>100,
                            'center_x'=>0,
                            'top'=>950,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>1230,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'153,153,153', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/job'.$index.'.jpg' //背景图
                ];
                break;
            default:
                $config = [
                    'image'=>[
                        [
                            'url'=>$info['qrcode'],//二维码
                            'stream'=>0,
                            'left'=>310,
                            'top'=>-210,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>140,
                            'height'=>140,
                            'opacity'=>100
                        ]
                    ],
                    'text'=>[
                        [
                            'text'=>cut_str($companyinfo['companyname'],16,0,'...'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>590,
                            'center_y'=>0,
                            'fontSize'=>26,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['jobname'],
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>680,
                            'center_y'=>0,
                            'fontSize'=>40,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['wage_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>760,
                            'center_y'=>0,
                            'fontSize'=>38,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['nature_text'].' · '.$info['education_text'].' · '.$info['experience_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>830,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['district_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>880,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>1290,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,255,255', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/job'.$index.'.jpg' //背景图
                ];
                break;
        }

        $result = $this->create($config,$save_path);
        if($result===false){
            $this->error = '生成海报失败';
            return false;
        }else{
            return $show_path;
        }
    }
    /**
     * 简历海报
     */
    public function makeResumePoster($index,$id){
        $info = model('Resume')
            ->where('id', 'eq', $id)
            ->field('uid',true)
            ->find();
        if($info===null){
            $this->error = '没有找到简历信息';
            return false;
        }
        $info = $info->toArray();

        $filename = $id.'_'.$info['updatetime'].'_'.$index.'.jpg';
        $save_dir = SYS_UPLOAD_PATH.'poster/resume/'.($id%10).'/';
        if(!is_dir($save_dir)){
            mkdir($save_dir,0777,true);
        }
        $save_path = $save_dir.$filename;
        $show_path = config('global_config.sitedomain').config('global_config.sitedir').SYS_UPLOAD_DIR_NAME.'/poster/resume/'.($id%10).'/'.$filename;
        if(file_exists($save_path)){
            return $show_path;
        }
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        if ($info['display_name'] == 0) {
            if ($info['sex'] == 1) {
                $info['fullname'] = cut_str(
                    $info['fullname'],
                    1,
                    0,
                    '先生'
                );
            } elseif ($info['sex'] == 2) {
                $info['fullname'] = cut_str(
                    $info['fullname'],
                    1,
                    0,
                    '女士'
                );
            } else {
                $info['fullname'] = cut_str(
                    $info['fullname'],
                    1,
                    0,
                    '**'
                );
            }
        }
        $info['sex_text'] = model('Resume')->map_sex[$info['sex']];
        $info['education_text'] = isset(
            model('BaseModel')->map_education[$info['education']]
        )
            ? model('BaseModel')->map_education[$info['education']]
            : '';
        $info['experience_text'] =
            $info['enter_job_time'] == 0
                ? '无经验'
                : format_date($info['enter_job_time']) . '经验';
        $info['current_text'] = isset(
            $category_data['QS_current'][$info['current']]
        )
            ? $category_data['QS_current'][$info['current']]
            : '';

        //求职意向
        $intention_data = model('ResumeIntention')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $info['id']]])
            ->select();
        $intention_list = [];
        foreach ($intention_data as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['category_text'] = isset(
                $category_job_data[$value['category']]
            )
                ? $category_job_data[$value['category']]
                : '';
            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $info['intention_jobs_text'][] = $tmp_arr['category_text'];
            $info['intention_district_text'][] = $tmp_arr['district_text'];
            $intention_list[] = $tmp_arr;
        }
        if(!empty($info['intention_jobs_text'])){
            $info['intention_jobs_text'] = array_unique($info['intention_jobs_text']);
            $info['intention_jobs_text'] = implode(",",$info['intention_jobs_text']);
        }
        if(!empty($info['intention_district_text'])){
            $info['intention_district_text'] = array_unique($info['intention_district_text']);
            $info['intention_district_text'] = implode(",",$info['intention_district_text']);
        }

        $info['photo_img_src'] = model('Uploadfile')->getFileUrl(
            $info['photo_img']
        );
        $info['photo_img_src'] = $info['photo_img_src']?$info['photo_img_src']:default_empty('photo');


        $locationUrl = config('global_config.mobile_domain').'resume/'.$info['id'];
        $info['qrcode'] = config('global_config.sitedomain').config('global_config.sitedir').'v1_0/home/qrcode/index?alias=subscribe_resume&url='.$locationUrl.'&resumeid='.$info['id'];

        switch($index){
            case 1:
                $config = [
                    'image'=>[
                        [
                            'url'=>$info['qrcode'],//二维码
                            'stream'=>0,
                            'left'=>530,
                            'top'=>-110,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ],
                        [
                            'url'=>$info['photo_img_src'],
                            'radius'=>1,
                            'stream'=>0,
                            'left'=>74,
                            'top'=>-514,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ],
                        [
                            'url'=>API_LIB_PATH.'poster/radius.png',
                            'radius'=>1,
                            'stream'=>0,
                            'left'=>74,
                            'top'=>-514,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ]
                    ],
                    'text'=>[
                        [
                            'text'=>$info['fullname'],
                            'fontPath'=>$this->fontPath,
                            'left'=>220,
                            'center_x'=>0,
                            'top'=>740,
                            'center_y'=>0,
                            'fontSize'=>28,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['sex_text'].' · '.$info['education_text'].' · '.$info['experience_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>220,
                            'center_x'=>0,
                            'top'=>800,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>'想找 '.$info['intention_jobs_text'].' 的工作',
                            'fontPath'=>$this->fontPath,
                            'left'=>80,
                            'center_x'=>0,
                            'top'=>960,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>'想在 '.$info['intention_district_text'].' 工作',
                            'fontPath'=>$this->fontPath,
                            'left'=>80,
                            'center_x'=>0,
                            'top'=>1010,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>-80,
                            'center_y'=>0,
                            'fontSize'=>20,       //字号
                            'fontColor'=>'153,153,153', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/resume'.$index.'.jpg' //背景图
                ];
                break;
            case 2:
                $config = [
                    'image'=>[
                        [
                            'url'=>$info['qrcode'],//二维码
                            'stream'=>0,
                            'left'=>530,
                            'top'=>-140,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ],
                        [
                            'url'=>$info['photo_img_src'],
                            'radius'=>1,
                            'stream'=>0,
                            'left'=>74,
                            'top'=>-546,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ],
                        [
                            'url'=>API_LIB_PATH.'poster/radius.png',
                            'radius'=>1,
                            'stream'=>0,
                            'left'=>74,
                            'top'=>-546,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ]
                    ],
                    'text'=>[
                        [
                            'text'=>$info['fullname'],
                            'fontPath'=>$this->fontPath,
                            'left'=>220,
                            'center_x'=>0,
                            'top'=>710,
                            'center_y'=>0,
                            'fontSize'=>28,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['sex_text'].' · '.$info['education_text'].' · '.$info['experience_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>220,
                            'center_x'=>0,
                            'top'=>770,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>'想找 '.$info['intention_jobs_text'].' 的工作',
                            'fontPath'=>$this->fontPath,
                            'left'=>84,
                            'center_x'=>0,
                            'top'=>860,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>'想在 '.$info['intention_district_text'].' 工作',
                            'fontPath'=>$this->fontPath,
                            'left'=>84,
                            'center_x'=>0,
                            'top'=>920,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>-50,
                            'center_y'=>0,
                            'fontSize'=>20,       //字号
                            'fontColor'=>'255,255,255', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/resume'.$index.'.jpg' //背景图
                ];
                break;
            default:
                $config = [
                    'image'=>[
                        [
                            'url'=>$info['qrcode'],//二维码
                            'stream'=>0,
                            'left'=>567,
                            'top'=>-38,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ],
                        [
                            'url'=>$info['photo_img_src'],
                            'radius'=>1,
                            'stream'=>0,
                            'left'=>558,
                            'top'=>-392,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>140,
                            'height'=>140,
                            'opacity'=>100
                        ],
                        [
                            'url'=>API_LIB_PATH.'poster/radius.png',
                            'radius'=>1,
                            'stream'=>0,
                            'left'=>558,
                            'top'=>-392,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>140,
                            'height'=>140,
                            'opacity'=>100
                        ]
                    ],
                    'text'=>[
                        [
                            'text'=>$info['fullname'],
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>864,
                            'center_y'=>0,
                            'fontSize'=>40,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['sex_text'].' · '.$info['education_text'].' · '.$info['experience_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>940,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['current_text'],
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>1000,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>'想找 '.$info['intention_jobs_text'].' 的工作',
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>1070,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>'想在 '.$info['intention_district_text'].' 工作',
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>1130,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,102,11', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>80,
                            'center_y'=>0,
                            'fontSize'=>30,       //字号
                            'fontColor'=>'255,255,255', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/resume'.$index.'.jpg' //背景图
                ];
                break;
        }

        $result = $this->create($config,$save_path);
        if($result===false){
            $this->error = '生成海报失败';
            return false;
        }else{
            return $show_path;
        }
    }

    /**
     * 企业海报
     */
    public function makeCompanyPoster($index,$id){
        $info = model('Company')
            ->where('id', 'eq', $id)
            ->field('uid',true)
            ->find();
        if($info===null){
            $this->error = '没有找到企业信息';
            return false;
        }
        $filename = $id.'_'.$info['updatetime'].'_'.$index.'.jpg';
        $save_dir = SYS_UPLOAD_PATH.'poster/company/'.($id%10).'/';
        if(!is_dir($save_dir)){
            mkdir($save_dir,0777,true);
        }
        $save_path = $save_dir.$filename;
        $show_path = config('global_config.sitedomain').config('global_config.sitedir').SYS_UPLOAD_DIR_NAME.'/poster/company/'.($id%10).'/'.$filename;
        if(file_exists($save_path)){
            return $show_path;
        }
        $category_district_data = model('CategoryDistrict')->getCache();
        $info['logo_src'] = model('Uploadfile')->getFileUrl($info['logo']);
        $info['logo_src'] = $info['logo_src'] ? $info['logo_src'] : default_empty('logo');
        $job_list = model('Job')
                ->field('id,jobname,minwage,maxwage,negotiable,district,nature,education,experience')
                ->where('company_id', 'eq', $info['id'])
                ->where('is_display', 1)
                ->where('audit', 1)
                ->limit(3) //最多展示3条职位信息
                ->select();
        foreach ($job_list as $key => $value) {
            $job_list[$key]['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $job_list[$key]['district_text'] = isset(
                $category_district_data[$value['district']]
            )
            ? $category_district_data[$value['district']]
            : '';
            $job_list[$key]['nature_text'] = isset(
                model('Job')->map_nature[$value['nature']]
            )
            ? model('Job')->map_nature[$value['nature']]
            : '全职';

            $job_list[$key]['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
            ? model('BaseModel')->map_education[$value['education']]
            : '学历不限';
            $job_list[$key]['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
            ? model('BaseModel')->map_experience[$value['experience']]
            : '经验不限';
        }
        $info['jobnum'] = count($job_list);

        $locationUrl = config('global_config.mobile_domain').'company/'.$info['id'];
        $info['qrcode'] = config('global_config.sitedomain').config('global_config.sitedir').'v1_0/home/qrcode/index?alias=subscribe_company&url='.$locationUrl.'&comid='.$info['id'];
        switch($index){
            case 1:
                $config = [
                    'image'=>[
                      [
                        'url'=>$info['qrcode'],//二维码
                        'stream'=>0,
                        'left'=>524,
                        'top'=>-186,
                        'right'=>0,
                        'bottom'=>0,
                        'width'=>130,
                        'height'=>130,
                        'opacity'=>100
                      ],
                      [
                        'url'=>$info['logo_src'],
                        'stream'=>0,
                        'left'=>560,
                        'top'=>-600,
                        'right'=>0,
                        'bottom'=>0,
                        'width'=>110,
                        'height'=>110,
                        'opacity'=>100
                      ]
                    ],
                    'text'=>[
                        [
                            'text'=>cut_str($info['companyname'],12,0,'...'),
                            'fontPath'=>$this->fontPath,
                            'left'=>80,
                            'center_x'=>0,
                            'top'=>660,
                            'center_y'=>0,
                            'fontSize'=>26,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['jobnum'].'个职位正在招聘',
                            'fontPath'=>$this->fontPath,
                            'left'=>80,
                            'center_x'=>0,
                            'top'=>720,
                            'center_y'=>0,
                            'fontSize'=>22,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>1220,
                            'center_y'=>0,
                            'fontSize'=>20,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/company'.$index.'.jpg' //背景图
                ];

                $job_index = 0;
                foreach ($job_list as $key => $value) {
                    if($job_index==2){
                        break;
                    }
                    $left_plus = $job_index * 320;
                    $config['text'][] = [
                        'text'=>cut_str($value['jobname'],7,0,'...'),
                        'fontPath'=>$this->fontPath,
                        'left'=>80+$left_plus,
                        'center_x'=>0,
                        'top'=>850,
                        'center_y'=>0,
                        'fontSize'=>24,       //字号
                        'fontColor'=>'0,0,0', //字体颜色
                        'angle'=>0,
                    ];
                    $config['text'][] = [
                        'text'=>$value['wage_text'],
                        'fontPath'=>$this->fontPath,
                        'left'=>80+$left_plus,
                        'center_x'=>0,
                        'top'=>900,
                        'center_y'=>0,
                        'fontSize'=>22,       //字号
                        'fontColor'=>'255,102,0', //字体颜色
                        'angle'=>0,
                    ];
                    $config['text'][] = [
                        'text'=>$value['district_text'],
                        'fontPath'=>$this->fontPath,
                        'left'=>80+$left_plus,
                        'center_x'=>0,
                        'top'=>950,
                        'center_y'=>0,
                        'fontSize'=>22,       //字号
                        'fontColor'=>'102,102,102', //字体颜色
                        'angle'=>0,
                    ];
                    $job_index++;
                }
                break;
            case 2:
                $config = [
                    'image'=>[
                      [
                        'url'=>$info['qrcode'],//二维码
                        'stream'=>0,
                        'left'=>562,
                        'top'=>-116,
                        'right'=>0,
                        'bottom'=>0,
                        'width'=>130,
                        'height'=>130,
                        'opacity'=>100
                      ]
                    ],
                    'text'=>[
                        [
                            'text'=>cut_str($info['companyname'],17,0,'...'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>720,
                            'center_y'=>0,
                            'fontSize'=>26,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['jobnum'].'个职位正在招聘',
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>776,
                            'center_y'=>0,
                            'fontSize'=>22,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>50,
                            'center_x'=>0,
                            'top'=>80,
                            'center_y'=>0,
                            'fontSize'=>28,       //字号
                            'fontColor'=>'255,255,255', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/company'.$index.'.jpg' //背景图
                ];
                $job_index = 0;
                foreach ($job_list as $key => $value) {
                    if($job_index==2){
                        break;
                    }
                    $left_plus = $job_index * 320;
                    $config['text'][] = [
                        'text'=>cut_str($value['jobname'],7,0,'...'),
                        'fontPath'=>$this->fontPath,
                        'left'=>80+$left_plus,
                        'center_x'=>0,
                        'top'=>880,
                        'center_y'=>0,
                        'fontSize'=>24,       //字号
                        'fontColor'=>'0,0,0', //字体颜色
                        'angle'=>0,
                    ];
                    $config['text'][] = [
                        'text'=>$value['wage_text'],
                        'fontPath'=>$this->fontPath,
                        'left'=>80+$left_plus,
                        'center_x'=>0,
                        'top'=>930,
                        'center_y'=>0,
                        'fontSize'=>22,       //字号
                        'fontColor'=>'255,102,0', //字体颜色
                        'angle'=>0,
                    ];
                    $config['text'][] = [
                        'text'=>$value['nature_text'].'·'.$value['education_text'].'·'.$value['experience_text'],
                        'fontPath'=>$this->fontPath,
                        'left'=>80+$left_plus,
                        'center_x'=>0,
                        'top'=>980,
                        'center_y'=>0,
                        'fontSize'=>20,       //字号
                        'fontColor'=>'102,102,102', //字体颜色
                        'angle'=>0,
                    ];
                    $job_index++;
                }
                break;
            default:
                $config = [
                    'image'=>[
                        [
                            'url'=>$info['qrcode'],//二维码
                            'stream'=>0,
                            'left'=>540,
                            'top'=>-120,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>130,
                            'height'=>130,
                            'opacity'=>100
                        ],
                        [
                            'url'=>$info['logo_src'],
                            'stream'=>0,
                            'left'=>324,
                            'top'=>-616,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>110,
                            'height'=>110,
                            'opacity'=>100
                        ]
                    ],
                    'text'=>[
                        [
                            'text'=>$info['companyname'],
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>780,
                            'center_y'=>0,
                            'fontSize'=>28,       //字号
                            'fontColor'=>'0,0,0', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>$info['jobnum'].'个职位正在招聘',
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>830,
                            'center_y'=>0,
                            'fontSize'=>22,       //字号
                            'fontColor'=>'102,102,102', //字体颜色
                            'angle'=>0,
                        ],
                        [
                            'text'=>config('global_config.sitename'),
                            'fontPath'=>$this->fontPath,
                            'left'=>0,
                            'center_x'=>1,
                            'top'=>1300,
                            'center_y'=>0,
                            'fontSize'=>24,       //字号
                            'fontColor'=>'255,255,255', //字体颜色
                            'angle'=>0,
                        ]
                    ],
                    'background'=>API_LIB_PATH.'poster/company'.$index.'.jpg' //背景图
                ];
                $job_index = 0;
                foreach ($job_list as $key => $value) {
                    $top_plus = $job_index * 64;
                    $config['text'][] = [
                        'text'=>$value['jobname'],
                        'fontPath'=>$this->fontPath,
                        'left'=>80,
                        'center_x'=>0,
                        'top'=>920+$top_plus,
                        'center_y'=>0,
                        'fontSize'=>26,       //字号
                        'fontColor'=>'102,102,102', //字体颜色
                        'angle'=>0,
                    ];
                    $config['text'][] = [
                        'text'=>$value['wage_text'],
                        'fontPath'=>$this->fontPath,
                        'left'=>0,
                        'center_x'=>0,
                        'float_right'=>1,
                        'top'=>920+$top_plus,
                        'center_y'=>0,
                        'fontSize'=>26,       //字号
                        'fontColor'=>'255,102,0', //字体颜色
                        'angle'=>0,
                    ];
                    $job_index++;
                }
                break;
        }

        $result = $this->create($config,$save_path);
        if($result===false){
            $this->error = '生成海报失败';
            return false;
        }else{
            return $show_path;
        }
    }

    public function makeVideoPoster($index, $id, $vtype){
        $m = new SvCompanyVideo();
        $locationUrl = config('global_config.mobile_domain').'shortvideo/videoplay?id='.$id.'&videotype='.$vtype;
        if($vtype == 2){
            $m = new SvPersonalVideo();
        }
        $locationUrl .= '&gointype=playlist';
        $locationUrl = urlencode($locationUrl);
        $info = $m->detail($id);
        if($id<Video::AUDIT_LIMIT){
            if($info['audit']!= Video::AUDIT_YES){
                $this->error = '只有已审核的视频可对外分享';
            }
            if($info['is_public'] != Video::PUBLIC_YES){
                $this->error = '本视频不对外展示,不可分享';
            }
            return false;
        }
        $filename = $id.'_'.$info['fid'].'_'.$index.'.png';
        $save_dir = SYS_UPLOAD_PATH.'poster/shortvideo/'.($id%10).'/';
        if(!is_dir($save_dir)){
            mkdir($save_dir,0777,true);
        }
        $save_path = $save_dir.$filename;
        $show_path = config('global_config.sitedomain').config('global_config.sitedir').SYS_UPLOAD_DIR_NAME.'/poster/shortvideo/'.($id%10).'/'.$filename;
        if(file_exists($save_path)){
            //unlink($save_path);
            return $show_path;
        }

        $info['qrcode'] = config('global_config.sitedomain').config('global_config.sitedir').'v1_0/home/qrcode/index?alias=subscribe_shortvideo&url='.$locationUrl.'&vid='.$id.'&vtype='.$vtype;
        $config = [
            'image'=>[
                [
                    'url'=>$info['qrcode'],//二维码
                    'stream'=>0,
                    'left'=>451,
                    'top'=>1097,
                    'right'=>0,
                    'bottom'=>0,
                    'width'=>190,
                    'height'=>190,
                    'opacity'=>100
                ],
                [
                    'url'=>$info['video_src'].'?vframe/jpg/offset/1',
                    //'url' => 'http://qiniu.weisns.com.cn/2021-06-30-60dc332c71d21.mp4?vframe/jpg/offset/1',
                    'stream'=>0,
                    'left'=>96,
                    'top'=>88,
                    'right'=>0,
                    'bottom'=>0,
                    'width'=>530,
                    'height'=>868,
                    'opacity'=>100
                ],
                [
                    'url' => config('global_config.sitedomain').config('global_config.sitedir').'assets/images/shortvideo_play.png',
                    'stream'=>0,
                    'left'=>300,
                    'top'=>460,
                    'right'=>0,
                    'bottom'=>0,
                    'radius' => 1,
                    'width'=>126,
                    'height'=>126,
                    'opacity'=>60
                ]
            ],
            'text'=>[
                [
                    'text'=>mb_substr($info['title'],0,15),
                    'fontPath'=>$this->fontPath,
                    'left'=>100,
                    'center_x'=>0,
                    'top'=>1015,
                    'center_y'=>0,
                    'fontSize'=>26,       //字号
                    'fontColor'=>'0,0,0', //字体颜色
                    'angle'=>0,
                ]
            ],
            'background'=>API_LIB_PATH.'poster/shortvideo'.$index.'.png' //背景图
        ];
        if(mb_strlen($info['title'])>15){
            $config['text'][] =  [
                'text'=>mb_strlen($info['title'])>28 ? mb_substr($info['title'],15,13). '...':  mb_substr($info['title'],15,13),
                'fontPath'=>$this->fontPath,
                'left'=>100,
                'center_x'=>0,
                'top'=>1070,
                'center_y'=>0,
                'fontSize'=>26,       //字号
                'fontColor'=>'0,0,0', //字体颜色
                'angle'=>0,
            ];
        }

        $result = $this->create($config,$save_path);
        if($result===false){
            $this->error = '生成海报失败';
            return false;
        }else{
            return $show_path;
        }
    }
}
