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
    protected $error = '';
    protected $fontPath;

    public function __construct()
    {
        $this->fontPath = API_LIB_PATH . 'poster/font.ttf';
    }

    public function create($config = array(), $filename = "")
    {
        //如果要看报什么错，可以先注释调这个header
        if (empty($filename)) header("content-type: image/jpeg");
        $imageDefault = array(
            'left' => 0,
            'top' => 0,
            'right' => 0,
            'bottom' => 0,
            'width' => 100,
            'height' => 100,
            'opacity' => 100
        );
        $textDefault = array(
            'text' => '',
            'left' => 0,
            'top' => 0,
            'fontSize' => 32,       //字号
            'fontColor' => '255,255,255', //字体颜色
            'angle' => 0,
        );
        $background = $config['background'];//海报最底层得背景
        //背景方法
        $backgroundInfo = getimagesize($background);
        $backgroundFun = 'imagecreatefrom' . image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFun($background);
        $backgroundWidth = imagesx($background);  //背景宽度
        $backgroundHeight = imagesy($background);  //背景高度
        $imageRes = imageCreatetruecolor($backgroundWidth, $backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        // imageColorTransparent($imageRes, $color);  //颜色透明
        imagecopyresampled($imageRes, $background, 0, 0, 0, 0, imagesx($background), imagesy($background), imagesx($background), imagesy($background));
        //处理了图片
        if (!empty($config['image'])) {
            foreach ($config['image'] as $key => $val) {
                $val = array_merge($imageDefault, $val);
                $info = getimagesize($val['url']);
                $function = 'imagecreatefrom' . image_type_to_extension($info[2], false);
                if (isset($val['stream']) && $val['stream'] == 1) {   //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
                //建立画板 ，缩放图片至指定尺寸
                $canvas = imagecreatetruecolor($val['width'], $val['height']);

                // 圆角处理
                if (isset($val['radius']) && $val['radius'] == 1) {
                    $bg = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                    imagecolortransparent($canvas, $bg);
                    imagefill($canvas, 0, 0, $bg);
                } else {
                    imagefill($canvas, 0, 0, $color);
                }


                //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'], $resWidth, $resHeight);
                $val['left'] = $val['left'] < 0 ? $backgroundWidth - abs($val['left']) - $val['width'] : $val['left'];
                $val['top'] = $val['top'] < 0 ? $backgroundHeight - abs($val['top']) - $val['height'] : $val['top'];
                //放置图像
                imagecopymerge($imageRes, $canvas, $val['left'], $val['top'], $val['right'], $val['bottom'], $val['width'], $val['height'], $val['opacity']);//左，上，右，下，宽度，高度，透明度
            }
        }

        //处理文字
        if (!empty($config['text'])) {
            foreach ($config['text'] as $key => $val) {
                $val = array_merge($textDefault, $val);
                list($R, $G, $B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
                $val['text'] = $this->toEntities($val['text']);
                $rect = imagettfbbox($val['fontSize'], $val['angle'], $val['fontPath'], $val['text']);
                $minX = min(array($rect[0], $rect[2], $rect[4], $rect[6]));
                $maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6]));
                $minY = min(array($rect[1], $rect[3], $rect[5], $rect[7]));
                $maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7]));
                if (isset($val['center_x']) && $val['center_x'] == 1) {
                    $val['left'] = ($backgroundWidth - ($maxX - $minX)) / 2;
                } else if (isset($val['float_right']) && $val['float_right'] > 0) {
                    $val['left'] = -($maxX - $minX + $val['float_right']);
                }
                $val['left'] = $val['left'] < 0 ? $backgroundWidth - abs($val['left']) : $val['left'];


                if (isset($val['center_y']) && $val['center_y'] == 1) {
                    $val['top'] = ($backgroundHeight - ($maxY - $minY)) / 2;
                } else {
                    $val['top'] = $val['top'] < 0 ? $backgroundHeight - abs($val['top']) : $val['top'];
                }

                imagettftext($imageRes, $val['fontSize'], $val['angle'], $val['left'], $val['top'], $fontColor, $val['fontPath'], $val['text']);
            }
        }
        //生成图片
        if (!empty($filename)) {
            imagepng($imageRes, $filename, 5); //保存到本地
            $res = imagecreatefrompng($filename);
            $res = imagejpeg($res, $filename);
            imagedestroy($imageRes);
            if (!$res) {
                return false;
            }
            return $filename;
        } else {
            imagejpeg($imageRes);     //在浏览器上显示
            imagedestroy($imageRes);
            exit;
        }
    }

    protected function toEntities($string)
    {
        $len = strlen($string);
        $buf = "";
        for ($i = 0; $i < $len; $i++) {
            if (ord($string[$i]) <= 127) {
                $buf .= $string[$i];
            } else if (ord($string[$i]) < 192) {
                //unexpected 2nd, 3rd or 4th byte
                $buf .= "&#xfffd";
            } else if (ord($string[$i]) < 224) {
                //first byte of 2-byte seq
                $buf .= sprintf("&#%d;",
                    ((ord($string[$i + 0]) & 31) << 6) +
                    (ord($string[$i + 1]) & 63)
                );
                $i += 1;
            } else if (ord($string[$i]) < 240) {
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

    public function getError()
    {
        return $this->error;
    }

    /**
     * 职位海报
     */
    public function makeJobPoster($index, $id)
    {
        /**
         * 【优化】
         * yx - 2023.01.12
         *
         * [逻辑]:
         * 1.先判断默认模板是否存在并开启
         * 存在则使用默认模板
         * 不存在则使用随机开启模板
         */
        $index_tpl = model('Poster')
            ->where('type', 1)
            ->where('is_display', 1)
            ->where('indexid', $index)
            ->find();
        if (!isset($index_tpl) || empty($index_tpl)) {
            $index_id = model('Poster')
                ->where('type', 1)
                ->where('is_display', 1)
                ->order('sort_id DESC, indexid ASC')
                ->value('indexid');
            $index = $index_id;
        }
        if (empty($index)) {
            $index = 1;
        }

        if ($id == 0) {
            $filename = 'preview_' . $index . '.jpg';
            $save_dir = SYS_UPLOAD_PATH . 'poster/job/preview/';
            if (!is_dir($save_dir)) {
                mkdir($save_dir, 0777, true);
            }
            $save_path = $save_dir . $filename;
            $show_path = config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME . '/poster/job/preview/' . $filename;
            $config = [
                'image' => [
                    [
                        'url' => API_LIB_PATH . 'poster/bj.png',//文字背景
                        'left' => 55,
                        'top' => 1350,
                        'width' => 970,
                        'height' => 460,
                        'opacity' => 100
                    ],
                    [
                        'url' => default_empty('logo'),//logo
                        'left' => 116,
                        'top' => 1616,
                        'width' => 120,
                        'height' => 120,
                        'opacity' => 100
                    ],
                    [
                        'url' => config('global_config.wechat_qrcode') ? model('Uploadfile')->getFileUrl(config('global_config.wechat_qrcode')) : make_file_url('resource/weixin_img.jpg'),//二维码
                        'left' => 770,
                        'top' => -210,
                        'width' => 200,
                        'height' => 200,
                        'opacity' => 100
                    ]
                ],
                'text' => [
                    [
                        'text' => '销售经理',
                        'fontPath' => $this->fontPath,
                        'left' => 116,
                        'top' => 1460,
                        'fontSize' => 40,       //字号
                        'fontColor' => '0,0,0' //字体颜色
                    ],
                    [
                        'text' => '5k~8k/月',
                        'fontPath' => $this->fontPath,
                        'float_right' => 120,
                        'top' => 1456,
                        'center_y' => 0,
                        'fontSize' => 32,       //字号
                        'fontColor' => '255,96,0' //字体颜色
                    ],
                    [
                        'text' => '全职 | 本科 | 经验不限',
                        'fontPath' => $this->fontPath,
                        'left' => 116,
                        'top' => 1540,
                        'fontSize' => 30,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => '山西丰火连天网络科技有限公司',
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1656,
                        'fontSize' => 28,       //字号
                        'fontColor' => '0,0,0'//字体颜色
                    ],
                    [
                        'text' => '晋中市',
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1720,
                        'fontSize' => 24,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => '长按识别二维码',
                        'fontPath' => $this->fontPath,
                        'left' => 754,
                        'top' => -170,
                        'fontSize' => 24,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => config('global_config.sitename'),
                        'fontPath' => $this->fontPath,
                        'left' => 50,
                        'center_x' => 1,
                        'top' => -50,
                        'fontSize' => 28,       //字号
                        'fontColor' => '255,255,255' //字体颜色
                    ]
                ],
                'background' => SYS_UPLOAD_PATH . 'resource/poster/job/' . $index . '.jpg' //背景图
            ];
        } else {
            $info = model('Job')
                ->where('id', 'eq', $id)
                ->field('uid', true)
                ->find();
            if ($info === null) {
                $this->error = '没有找到职位信息';
                return false;
            }
            $filename = $id . '_' . $info['updatetime'] . '_' . $index . '.jpg';
            $save_dir = SYS_UPLOAD_PATH . 'poster/job/' . ($id % 10) . '/';
            if (!is_dir($save_dir)) {
                mkdir($save_dir, 0777, true);
            }
            $save_path = $save_dir . $filename;
            $show_path = config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME . '/poster/job/' . ($id % 10) . '/' . $filename;
            if (file_exists($save_path)) {
                return $show_path;
            }

            $companyinfo = model('Company')->where('id', 'eq', $info['company_id'])->find();
            if ($companyinfo === null) {
                $this->error = '没有找到企业信息';
                return false;
            }
            $companyinfo['logo_src'] = model('Uploadfile')->getFileUrl($companyinfo['logo']);
            $companyinfo['logo_src'] = $companyinfo['logo_src'] ? $companyinfo['logo_src'] : default_empty('logo');
            $category_district_data = model('CategoryDistrict')->getCache();
            $companyinfo['district_text'] = isset(
                $category_district_data[$companyinfo['district']]
            )
                ? $category_district_data[$companyinfo['district']]
                : '';

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

            $locationUrl = config('global_config.mobile_domain') . 'job/' . $info['id'];
            $info['qrcode'] = config('global_config.sitedomain') . config('global_config.sitedir') . 'v1_0/home/qrcode/index?alias=subscribe_job&url=' . $locationUrl . '&jobid=' . $info['id'];
            $config = [
                'image' => [
                    [
                        'url' => API_LIB_PATH . 'poster/bj.png',//文字背景
                        'left' => 55,
                        'top' => 1350,
                        'width' => 970,
                        'height' => 460,
                        'opacity' => 100
                    ],
                    [
                        'url' => $companyinfo['logo_src'],//logo
                        'left' => 116,
                        'top' => 1616,
                        'width' => 120,
                        'height' => 120,
                        'opacity' => 100
                    ],
                    [
                        'url' => $info['qrcode'],//二维码
                        'left' => 770,
                        'top' => -210,
                        'width' => 200,
                        'height' => 200,
                        'opacity' => 100
                    ]
                ],
                'text' => [
                    [
                        'text' => cut_str($info['jobname'], 10),
                        'fontPath' => $this->fontPath,
                        'left' => 116,
                        'top' => 1460,
                        'fontSize' => 40,       //字号
                        'fontColor' => '0,0,0' //字体颜色
                    ],
                    [
                        'text' => $info['wage_text'],
                        'fontPath' => $this->fontPath,
                        'float_right' => 120,
                        'top' => 1456,
                        'center_y' => 0,
                        'fontSize' => 32,       //字号
                        'fontColor' => '255,96,0' //字体颜色
                    ],
                    [
                        'text' => $info['nature_text'] . ' | ' . $info['education_text'] . ' | ' . $info['experience_text'],
                        'fontPath' => $this->fontPath,
                        'left' => 116,
                        'top' => 1540,
                        'fontSize' => 30,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => cut_str($companyinfo['companyname'], 12, 0, '...'),
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1656,
                        'fontSize' => 28,       //字号
                        'fontColor' => '0,0,0'//字体颜色
                    ],
                    [
                        'text' => $companyinfo['district_text'],
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1720,
                        'fontSize' => 24,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => '长按识别二维码',
                        'fontPath' => $this->fontPath,
                        'left' => 754,
                        'top' => -170,
                        'fontSize' => 24,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => config('global_config.sitename'),
                        'fontPath' => $this->fontPath,
                        'left' => 50,
                        'center_x' => 1,
                        'top' => -50,
                        'fontSize' => 28,       //字号
                        'fontColor' => '255,255,255' //字体颜色
                    ]
                ],
                'background' => SYS_UPLOAD_PATH . 'resource/poster/job/' . $index . '.jpg' //背景图
            ];
        }


        $result = $this->create($config, $save_path);
        if ($result === false) {
            $this->error = '生成海报失败';
            return false;
        } else {
            return $show_path;
        }
    }

    /**
     * 简历海报
     */
    public function makeResumePoster($index, $id)
    {
        /**
         * 【优化】
         * yx - 2023.01.12
         *
         * [逻辑]:
         * 1.先判断默认模板是否存在并开启
         * 存在则使用默认模板
         * 不存在则使用随机开启模板
         */
        $index_tpl = model('Poster')
            ->where('type', 2)
            ->where('is_display', 1)
            ->where('indexid', $index)
            ->find();
        if (!isset($index_tpl) || empty($index_tpl)) {
            $index_id = model('Poster')
                ->where('type', 2)
                ->where('is_display', 1)
                ->order('sort_id DESC, indexid ASC')
                ->value('indexid');
            $index = $index_id;
        }
        if (empty($index)) {
            $index = 1;
        }

        if ($id == 0) {
            $filename = 'preview_' . $index . '.jpg';
            $save_dir = SYS_UPLOAD_PATH . 'poster/resume/preview/';
            if (!is_dir($save_dir)) {
                mkdir($save_dir, 0777, true);
            }
            $save_path = $save_dir . $filename;
            $show_path = config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME . '/poster/resume/preview/' . $filename;
            $config = [
                'image' => [
                    [
                        'url' => API_LIB_PATH . 'poster/bj.png',//文字背景
                        'left' => 55,
                        'top' => 1350,
                        'width' => 970,
                        'height' => 440,
                        'opacity' => 100
                    ],
                    [
                        'url' => default_empty('photo'),//照片
                        'radius' => 1,
                        'left' => 90,
                        'top' => 1390,
                        'width' => 150,
                        'height' => 150,
                        'opacity' => 100
                    ],
                    [
                        'url' => config('global_config.wechat_qrcode') ? model('Uploadfile')->getFileUrl(config('global_config.wechat_qrcode')) : make_file_url('resource/weixin_img.jpg'),//二维码
                        'left' => 770,
                        'top' => -220,
                        'width' => 190,
                        'height' => 190,
                        'opacity' => 100
                    ],
                    [
                        'url' => API_LIB_PATH . 'poster/radius.png',//圆角
                        'radius' => 1,
                        'left' => 90,
                        'top' => 1390,
                        'width' => 150,
                        'height' => 150,
                        'opacity' => 100
                    ]
                ],
                'text' => [
                    [
                        'text' => '胡依依',
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1450,
                        'fontSize' => 36,       //字号
                        'fontColor' => '0,0,0' //字体颜色
                    ],
                    [
                        'text' => '女 · 本科 · 5年经验',
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1518,
                        'fontSize' => 28,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => '我已离职，可随时到岗',
                        'fontPath' => $this->fontPath,
                        'left' => 105,
                        'top' => 1596,
                        'fontSize' => 24,       //字号
                        'fontColor' => '102,102,102', //字体颜色
                    ],
                    [
                        'text' => '意向岗位：',
                        'fontPath' => $this->fontPath,
                        'left' => 105,
                        'top' => 1664,
                        'fontSize' => 26,       //字号
                        'fontColor' => '102,102,102', //字体颜色
                    ],
                    [
                        'text' => '设计师，产品经理',
                        'fontPath' => $this->fontPath,
                        'left' => 275,
                        'top' => 1664,
                        'fontSize' => 26,       //字号
                        'fontColor' => '255,96,0' //字体颜色
                    ],
                    [
                        'text' => '意向地区：',
                        'fontPath' => $this->fontPath,
                        'left' => 105,
                        'top' => 1724,
                        'fontSize' => 26,       //字号
                        'fontColor' => '102,102,102', //字体颜色
                    ],
                    [
                        'text' => '太原市,晋中市',
                        'fontPath' => $this->fontPath,
                        'left' => 275,
                        'top' => 1724,
                        'fontSize' => 26,       //字号
                        'fontColor' => '255,96,0' //字体颜色
                    ],
                    [
                        'text' => '长按识别二维码',
                        'fontPath' => $this->fontPath,
                        'left' => 760,
                        'top' => -178,
                        'fontSize' => 22,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => config('global_config.sitename'),
                        'fontPath' => $this->fontPath,
                        'left' => 50,
                        'center_x' => 1,
                        'top' => -50,
                        'fontSize' => 28,       //字号
                        'fontColor' => '255,255,255' //字体颜色
                    ]
                ],
                'background' => SYS_UPLOAD_PATH . 'resource/poster/resume/' . $index . '.jpg' //背景图
            ];
        } else {
            $info = model('Resume')
                ->where('id', 'eq', $id)
                ->field('uid', true)
                ->find();
            if ($info === null) {
                $this->error = '没有找到简历信息';
                return false;
            }
            $info = $info->toArray();

            $filename = $id . '_' . $info['updatetime'] . '_' . $index . '.jpg';
            $save_dir = SYS_UPLOAD_PATH . 'poster/resume/' . ($id % 10) . '/';
            if (!is_dir($save_dir)) {
                mkdir($save_dir, 0777, true);
            }
            $save_path = $save_dir . $filename;
            $show_path = config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME . '/poster/resume/' . ($id % 10) . '/' . $filename;
            if (file_exists($save_path)) {
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
            if (!empty($info['intention_jobs_text'])) {
                $info['intention_jobs_text'] = array_unique($info['intention_jobs_text']);
                $info['intention_jobs_text'] = implode(",", $info['intention_jobs_text']);
            }
            if (!empty($info['intention_district_text'])) {
                $info['intention_district_text'] = array_unique($info['intention_district_text']);
                $info['intention_district_text'] = implode(",", $info['intention_district_text']);
            }

            $info['photo_img_src'] = model('Uploadfile')->getFileUrl(
                $info['photo_img']
            );
            $info['photo_img_src'] = $info['photo_img_src'] ? $info['photo_img_src'] : default_empty('photo');


            $locationUrl = config('global_config.mobile_domain') . 'resume/' . $info['id'];
            $info['qrcode'] = config('global_config.sitedomain') . config('global_config.sitedir') . 'v1_0/home/qrcode/index?alias=subscribe_resume&url=' . $locationUrl . '&resumeid=' . $info['id'];

            $config = [
                'image' => [
                    [
                        'url' => API_LIB_PATH . 'poster/bj.png',//文字背景
                        'left' => 55,
                        'top' => 1350,
                        'width' => 970,
                        'height' => 440,
                        'opacity' => 100
                    ],
                    [
                        'url' => $info['photo_img_src'],//照片
                        'radius' => 1,
                        'left' => 90,
                        'top' => 1390,
                        'width' => 150,
                        'height' => 150,
                        'opacity' => 100
                    ],
                    [
                        'url' => $info['qrcode'],//二维码
                        'left' => 770,
                        'top' => -220,
                        'width' => 190,
                        'height' => 190,
                        'opacity' => 100
                    ],
                    [
                        'url' => API_LIB_PATH . 'poster/radius.png',//圆角
                        'radius' => 1,
                        'left' => 90,
                        'top' => 1390,
                        'width' => 150,
                        'height' => 150,
                        'opacity' => 100
                    ]
                ],
                'text' => [
                    [
                        'text' => $info['fullname'],
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1450,
                        'fontSize' => 36,       //字号
                        'fontColor' => '0,0,0' //字体颜色
                    ],
                    [
                        'text' => $info['sex_text'] . ' · ' . $info['education_text'] . ' · ' . $info['experience_text'],
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1518,
                        'fontSize' => 28,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => $info['current_text'],
                        'fontPath' => $this->fontPath,
                        'left' => 105,
                        'top' => 1596,
                        'fontSize' => 24,       //字号
                        'fontColor' => '102,102,102', //字体颜色
                    ],
                    [
                        'text' => '意向岗位：',
                        'fontPath' => $this->fontPath,
                        'left' => 105,
                        'top' => 1664,
                        'fontSize' => 26,       //字号
                        'fontColor' => '102,102,102', //字体颜色
                    ],
                    [
                        'text' => cut_str($info['intention_jobs_text'], 12, 0, '...'),
                        'fontPath' => $this->fontPath,
                        'left' => 275,
                        'top' => 1664,
                        'fontSize' => 26,       //字号
                        'fontColor' => '255,96,0' //字体颜色
                    ],
                    [
                        'text' => '意向地区：',
                        'fontPath' => $this->fontPath,
                        'left' => 105,
                        'top' => 1724,
                        'fontSize' => 26,       //字号
                        'fontColor' => '102,102,102', //字体颜色
                    ],
                    [
                        'text' => cut_str($info['intention_district_text'], 12, 0, '...'),
                        'fontPath' => $this->fontPath,
                        'left' => 275,
                        'top' => 1724,
                        'fontSize' => 26,       //字号
                        'fontColor' => '255,96,0' //字体颜色
                    ],
                    [
                        'text' => '长按识别二维码',
                        'fontPath' => $this->fontPath,
                        'left' => 760,
                        'top' => -178,
                        'fontSize' => 22,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => config('global_config.sitename'),
                        'fontPath' => $this->fontPath,
                        'left' => 50,
                        'center_x' => 1,
                        'top' => -50,
                        'fontSize' => 28,       //字号
                        'fontColor' => '255,255,255' //字体颜色
                    ]
                ],
                'background' => SYS_UPLOAD_PATH . 'resource/poster/resume/' . $index . '.jpg' //背景图
            ];
        }

        $result = $this->create($config, $save_path);
        if ($result === false) {
            $this->error = '生成海报失败';
            return false;
        } else {
            return $show_path;
        }
    }

    /**
     * 企业海报
     */
    public function makeCompanyPoster($index, $id)
    {
        /**
         * 【优化】
         * yx - 2023.01.12
         *
         * [逻辑]:
         * 1.先判断默认模板是否存在并开启
         * 存在则使用默认模板
         * 不存在则使用随机开启模板
         */
        $index_tpl = model('Poster')
            ->where('type', 3)
            ->where('is_display', 1)
            ->where('indexid', $index)
            ->find();
        if (!isset($index_tpl) || empty($index_tpl)) {
            $index_id = model('Poster')
                ->where('type', 3)
                ->where('is_display', 1)
                ->order('sort_id DESC, indexid ASC')
                ->value('indexid');
            $index = $index_id;
        }
        if (empty($index)) {
            $index = 1;
        }

        if ($id == 0) {
            $filename = 'preview_' . $index . '.jpg';
            $save_dir = SYS_UPLOAD_PATH . 'poster/company/preview/';
            if (!is_dir($save_dir)) {
                mkdir($save_dir, 0777, true);
            }
            $save_path = $save_dir . $filename;
            $show_path = config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME . '/poster/company/preview/' . $filename;
            $config = [
                'image' => [
                    [
                        'url' => API_LIB_PATH . 'poster/bj.png',//文字背景
                        'left' => 55,
                        'top' => 1350,
                        'width' => 970,
                        'height' => 440,
                        'opacity' => 100
                    ],
                    [
                        'url' => default_empty('logo'),//logo
                        'radius' => 1,
                        'left' => 90,
                        'top' => 1390,
                        'width' => 150,
                        'height' => 150,
                        'opacity' => 100
                    ],
                    [
                        'url' => config('global_config.wechat_qrcode') ? model('Uploadfile')->getFileUrl(config('global_config.wechat_qrcode')) : make_file_url('resource/weixin_img.jpg'),//二维码
                        'left' => 770,
                        'top' => -220,
                        'width' => 190,
                        'height' => 190,
                        'opacity' => 100
                    ]
                ],
                'text' => [
                    [
                        'text' => '山西丰火连天网络科技有限公司',
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1450,
                        'fontSize' => 36,       //字号
                        'fontColor' => '0,0,0' //字体颜色
                    ],
                    [
                        'text' => '8个职位正在招聘',
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1518,
                        'fontSize' => 28,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => '长按识别二维码',
                        'fontPath' => $this->fontPath,
                        'left' => 760,
                        'top' => -178,
                        'fontSize' => 22,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => config('global_config.sitename'),
                        'fontPath' => $this->fontPath,
                        'left' => 50,
                        'center_x' => 1,
                        'top' => -50,
                        'fontSize' => 28,       //字号
                        'fontColor' => '255,255,255' //字体颜色
                    ],
                    [
                        'text' => '设计师',
                        'fontPath' => $this->fontPath,
                        'left' => 116,
                        'top' => 1620,
                        'fontSize' => 30,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => '5k~8k/月',
                        'fontPath' => $this->fontPath,
                        'float_right' => 350,
                        'top' => 1620,
                        'fontSize' => 30,       //字号
                        'fontColor' => '255,102,0' //字体颜色
                    ],
                    [
                        'text' => '产品经理',
                        'fontPath' => $this->fontPath,
                        'left' => 116,
                        'top' => 1700,
                        'fontSize' => 30,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => '10k~15k/月',
                        'fontPath' => $this->fontPath,
                        'float_right' => 350,
                        'top' => 1700,
                        'fontSize' => 30,       //字号
                        'fontColor' => '255,102,0' //字体颜色
                    ]
                ],
                'background' => SYS_UPLOAD_PATH . 'resource/poster/company/' . $index . '.jpg' //背景图
            ];
        } else {
            $info = model('Company')
                ->where('id', 'eq', $id)
                ->field('uid', true)
                ->find();
            if ($info === null) {
                $this->error = '没有找到企业信息';
                return false;
            }
            $filename = $id . '_' . $info['updatetime'] . '_' . $index . '.jpg';
            $save_dir = SYS_UPLOAD_PATH . 'poster/company/' . ($id % 10) . '/';
            if (!is_dir($save_dir)) {
                mkdir($save_dir, 0777, true);
            }
            $save_path = $save_dir . $filename;
            $show_path = config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME . '/poster/company/' . ($id % 10) . '/' . $filename;
            if (file_exists($save_path)) {
                return $show_path;
            }
            $category_district_data = model('CategoryDistrict')->getCache();
            $info['logo_src'] = model('Uploadfile')->getFileUrl($info['logo']);
            $info['logo_src'] = $info['logo_src'] ? $info['logo_src'] : default_empty('logo');
            $job_list = model('Job')
                ->field('id,jobname,minwage,maxwage,negotiable')
                ->where('company_id', 'eq', $info['id'])
                ->where('is_display', 1)
                ->where('audit', 1)
                ->order('refreshtime', 'desc')
                ->limit(3) //最多展示3条职位信息
                ->select();
            foreach ($job_list as $key => $value) {
                $job_list[$key]['wage_text'] = model('BaseModel')->handle_wage(
                    $value['minwage'],
                    $value['maxwage'],
                    $value['negotiable']
                );
            }
            $info['jobnum'] = model('Job')
                ->field('id,jobname,minwage,maxwage,negotiable')
                ->where('company_id', 'eq', $info['id'])
                ->where('is_display', 1)
                ->where('audit', 1)
                ->count();

            $locationUrl = config('global_config.mobile_domain') . 'company/' . $info['id'];
            $info['qrcode'] = config('global_config.sitedomain') . config('global_config.sitedir') . 'v1_0/home/qrcode/index?alias=subscribe_company&url=' . $locationUrl . '&comid=' . $info['id'];

            $config = [
                'image' => [
                    [
                        'url' => API_LIB_PATH . 'poster/bj.png',//文字背景
                        'left' => 55,
                        'top' => 1350,
                        'width' => 970,
                        'height' => 440,
                        'opacity' => 100
                    ],
                    [
                        'url' => $info['logo_src'],//logo
                        'radius' => 1,
                        'left' => 90,
                        'top' => 1390,
                        'width' => 150,
                        'height' => 150,
                        'opacity' => 100
                    ],
                    [
                        'url' => $info['qrcode'],//二维码
                        'left' => 770,
                        'top' => -220,
                        'width' => 190,
                        'height' => 190,
                        'opacity' => 100
                    ]
                ],
                'text' => [
                    [
                        'text' => cut_str($info['companyname'], 14, 0, '...'),
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1450,
                        'fontSize' => 36,       //字号
                        'fontColor' => '0,0,0' //字体颜色
                    ],
                    [
                        'text' => $info['jobnum'] . '个职位正在招聘',
                        'fontPath' => $this->fontPath,
                        'left' => 260,
                        'top' => 1518,
                        'fontSize' => 28,       //字号
                        'fontColor' => '68,68,68' //字体颜色
                    ],
                    [
                        'text' => '长按识别二维码',
                        'fontPath' => $this->fontPath,
                        'left' => 760,
                        'top' => -178,
                        'fontSize' => 22,       //字号
                        'fontColor' => '119,119,119' //字体颜色
                    ],
                    [
                        'text' => config('global_config.sitename'),
                        'fontPath' => $this->fontPath,
                        'left' => 50,
                        'center_x' => 1,
                        'top' => -50,
                        'fontSize' => 28,       //字号
                        'fontColor' => '255,255,255' //字体颜色
                    ]
                ],
                'background' => SYS_UPLOAD_PATH . 'resource/poster/company/' . $index . '.jpg' //背景图
            ];
            $job_index = 0;
            foreach ($job_list as $key => $value) {
                if ($job_index == 2) {
                    break;
                }
                $top_plus = $job_index * 80;
                $config['text'][] = [
                    'text' => cut_str($value['jobname'], 10, 0, '...'),
                    'fontPath' => $this->fontPath,
                    'left' => 116,
                    'top' => 1620 + $top_plus,
                    'fontSize' => 30,       //字号
                    'fontColor' => '68,68,68' //字体颜色
                ];
                $config['text'][] = [
                    'text' => $value['wage_text'],
                    'fontPath' => $this->fontPath,
                    'float_right' => 350,
                    'top' => 1620 + $top_plus,
                    'fontSize' => 30,       //字号
                    'fontColor' => '255,102,0' //字体颜色
                ];
                $job_index++;
            }
        }


        $result = $this->create($config, $save_path);
        if ($result === false) {
            $this->error = '生成海报失败';
            return false;
        } else {
            return $show_path;
        }
    }

    public function makeVideoPoster($index, $id, $vtype)
    {
        $m = new SvCompanyVideo();
        $locationUrl = config('global_config.mobile_domain') . 'shortvideo/videoplay?id=' . $id . '&videotype=' . $vtype;
        if ($vtype == 2) {
            $m = new SvPersonalVideo();
        }
        $locationUrl .= '&gointype=playlist';
        $locationUrl = urlencode($locationUrl);
        $info = $m->detail($id);
        if ($id < Video::AUDIT_LIMIT) {
            if ($info['audit'] != Video::AUDIT_YES) {
                $this->error = '只有已审核的视频可对外分享';
            }
            if ($info['is_public'] != Video::PUBLIC_YES) {
                $this->error = '本视频不对外展示,不可分享';
            }
            return false;
        }
        $filename = $id . '_' . $info['fid'] . '_' . $index . '.png';
        $save_dir = SYS_UPLOAD_PATH . 'poster/shortvideo/' . ($id % 10) . '/';
        if (!is_dir($save_dir)) {
            mkdir($save_dir, 0777, true);
        }
        $save_path = $save_dir . $filename;
        $show_path = config('global_config.sitedomain') . config('global_config.sitedir') . SYS_UPLOAD_DIR_NAME . '/poster/shortvideo/' . ($id % 10) . '/' . $filename;
        if (file_exists($save_path)) {
            //unlink($save_path);
            return $show_path;
        }

        $info['qrcode'] = config('global_config.sitedomain') . config('global_config.sitedir') . 'v1_0/home/qrcode/index?alias=subscribe_shortvideo&url=' . $locationUrl . '&vid=' . $id . '&vtype=' . $vtype;
        $config = [
            'image' => [
                [
                    'url' => $info['qrcode'],//二维码
                    'stream' => 0,
                    'left' => 451,
                    'top' => 1097,
                    'right' => 0,
                    'bottom' => 0,
                    'width' => 190,
                    'height' => 190,
                    'opacity' => 100
                ],
                [
                    'url' => $info['video_src'] . '?vframe/jpg/offset/1',
                    //'url' => 'http://qiniu.weisns.com.cn/2021-06-30-60dc332c71d21.mp4?vframe/jpg/offset/1',
                    'stream' => 0,
                    'left' => 96,
                    'top' => 88,
                    'right' => 0,
                    'bottom' => 0,
                    'width' => 530,
                    'height' => 868,
                    'opacity' => 100
                ],
                [
                    'url' => config('global_config.sitedomain') . config('global_config.sitedir') . 'assets/images/shortvideo_play.png',
                    'stream' => 0,
                    'left' => 300,
                    'top' => 460,
                    'right' => 0,
                    'bottom' => 0,
                    'radius' => 1,
                    'width' => 126,
                    'height' => 126,
                    'opacity' => 60
                ]
            ],
            'text' => [
                [
                    'text' => mb_substr($info['title'], 0, 15),
                    'fontPath' => $this->fontPath,
                    'left' => 100,
                    'center_x' => 0,
                    'top' => 1015,
                    'center_y' => 0,
                    'fontSize' => 26,       //字号
                    'fontColor' => '0,0,0', //字体颜色
                    'angle' => 0,
                ]
            ],
            'background' => API_LIB_PATH . 'poster/shortvideo' . $index . '.png' //背景图
        ];
        if (mb_strlen($info['title']) > 15) {
            $config['text'][] = [
                'text' => mb_strlen($info['title']) > 28 ? mb_substr($info['title'], 15, 13) . '...' : mb_substr($info['title'], 15, 13),
                'fontPath' => $this->fontPath,
                'left' => 100,
                'center_x' => 0,
                'top' => 1070,
                'center_y' => 0,
                'fontSize' => 26,       //字号
                'fontColor' => '0,0,0', //字体颜色
                'angle' => 0,
            ];
        }

        $result = $this->create($config, $save_path);
        if ($result === false) {
            $this->error = '生成海报失败';
            return false;
        } else {
            return $show_path;
        }
    }
}
