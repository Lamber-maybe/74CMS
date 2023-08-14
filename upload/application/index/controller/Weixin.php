<?php

namespace app\index\controller;

use app\common\model\shortvideo\SvCompanyVideo;
use app\common\model\shortvideo\SvPersonalVideo;
use Think\Exception;
use think\Log;

\think\Loader::import('wechat.wxBizMsgCrypt');

class Weixin extends \app\common\controller\Base
{
    protected $timestamp;
    protected $nonce;
    protected $msg_signature;
    protected $encrypt_type;
    protected $pc;

    public function _initialize()
    {
        parent::_initialize();
        $this->msg_signature = input('param.msg_signature', '', 'trim');
        $this->timestamp = input('param.timestamp', '', 'trim');
        $this->nonce = input('param.nonce', '', 'trim');
        $this->encrypt_type = 'aes' == input('param.encrypt_type', '', 'trim') ? 'aes' : 'raw';
    }

    public function index()
    {
        $echoStr = input('param.echostr', '', 'trim');
        if ($echoStr) {
            $this->validConnectIn($echoStr);
        } else {
            $this->responseMsg();
        }
    }

    /**
     * 微信接入认证
     */
    protected function validConnectIn($echoStr)
    {
        if ($this->checkSignature()) {
            exit($echoStr);
        }
        exit('false');
    }

    /**
     * 验名认证
     */
    protected function checkSignature()
    {
        $signature = input('param.signature', '', 'trim');
        $timestamp = input('param.timestamp', '', 'trim');
        $nonce = input('param.nonce', '', 'trim');
        $tmpArr = array(config('global_config.wechat_token'), $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    //检查网站微信接口是否开启
    protected function checkWeixinOpen($object)
    {
        if (!config('global_config.wechat_open')) {
            $this->outputText($object, "网站微信接口已经关闭");
            exit;
        }
    }

    /**
     * 输出前加密
     */
    protected function outputByEncryptMsg($content)
    {
        $exitContent = $content;
        //加密
        if ($this->encrypt_type == 'aes') {
            $encryptMsg = ''; //加密后的密文
            $errCode = $this->pc->encryptMsg($content, $this->timestamp, $this->nonce, $encryptMsg);
            if ($errCode != 0) {
                $exitContent = '';
            } else {
                $exitContent = $encryptMsg;
            }
        }
        echo $exitContent;
    }

    /**
     * 消息类
     */
    protected function responseMsg()
    {
        if (!$this->checkSignature()) exit('false');
        //解密
        if ($postStr = file_get_contents("php://input")) {
            if ($this->encrypt_type == 'aes') {
                $this->pc = new \WXBizMsgCrypt(config('global_config.wechat_token'), config('global_config.wechat_encodingaeskey'), config('global_config.wechat_appid'));
                $decryptMsg = "";//解密后的明文
                $errCode = $this->pc->decryptMsg($this->msg_signature, $this->timestamp, $this->nonce, $postStr, $decryptMsg);
                if ($errCode != 0) {
                    exit('');
                }
                $postStr = $decryptMsg;
            }
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $rxType = trim($postObj->MsgType);
            //消息类型分离
            switch ($rxType) {
                case "event":
                    $this->receiveEvent($postObj);
                    break;
                case "text":
                    $this->receiveText($postObj);
                    break;
                case "voice":
                    $this->receiveVoice($postObj);
                    break;
                default:
                    $this->outputText($postObj, "unknown msg type: " . $rxType);
                    break;
            }
        }
        exit('');
    }

    /**
     * 关注公众号
     */
    protected function subscribe($object)
    {
        $data['openid'] = addslashes($object->FromUserName);
        $data['subscribe_time'] = addslashes($object->CreateTime); // 虚拟关注时间（实际为`关注事件`推送时间）
        $check = model('WechatFans')->where('openid', $data['openid'])->find();
        if ($check === null) {
            model('WechatFans')->save($data);
        }
        $checkBindInfo = model('MemberBind')
            ->where('is_subscribe', 0)
            ->where('openid', $data['openid'])
            ->where('type', 'weixin')
            ->find();
        if ($checkBindInfo !== null) {
            $checkBindInfo->is_subscribe = 1;
            $checkBindInfo->save();
            $member = model('Member')->where('uid', $checkBindInfo['uid'])->find();
            if ($member !== null) {
                model('Task')->doTask($member['uid'], $member['utype'], 'bind_weixin');
            }
//            model('MemberBalance')->moneyRecord(
//                $member['uid'],
//                'follow_red_envelopes',
//                '关注公众号'
//            );
        }

    }

    /**
     * 取消关注公众号
     */
    protected function unsubscribe($object)
    {
        $openid = addslashes($object->FromUserName);
        model('WechatFans')->where('openid', $openid)->delete();
        $checkBindInfo = model('MemberBind')
            ->where('is_subscribe', 1)
            ->where('openid', $openid)
            ->where('type', 'weixin')
            ->find();
        if ($checkBindInfo !== null) {
            $checkBindInfo->is_subscribe = 0;
            $checkBindInfo->save();
        }
    }

    /**
     * 接收事件消息
     */
    protected function receiveEvent($object)
    {
        $this->checkWeixinOpen($object);
        switch ($object->Event) {
            case "subscribe"://用户未关注时，进行关注后的事件推送
                $this->subscribe($object);
                $this->outputWelcome($object);
                if ($object->EventKey) {
                    $this->actionScan($object, 1);
                }
                break;
            case "SCAN":
                $this->actionScan($object);
                break;
            case "CLICK":
                switch ($object->EventKey) {
                    case "binding"://绑定
                        $this->clickBinding($object);
                        break;
                    case "contact"://联系客服
                        $this->clickContact($object);
                        break;
                    case "refreshJob"://一键刷新职位
                        $this->clickRefreshJob($object);
                        break;
                    default:
                        break;
                }
                break;
            case "unsubscribe":
                $this->unsubscribe($object);
                break;
            default:
                $this->subscribe($object);
                break;
        }
    }

    /**
     * 接收文本消息
     */
    protected function receiveText($object)
    {
        $keyword = trim($object->Content);
        $keyword = addslashes($keyword);
        $this->checkWeixinOpen($object);
        //自动回复模式
        $this->enterSearch($object, $keyword);
    }

    /**
     * 接收语音消息
     */
    protected function receiveVoice($object)
    {
        $Recognition = trim($object->Recognition);
        $keyword = rtrim($object->Recognition, '。');
        $this->checkWeixinOpen($object);
        //自动回复模式
        $this->enterSearch($object, $keyword);
    }

    protected function outputKeyword($object, $keywordInfo)
    {
        /**
         * 【ID1000575】
         * 【优化】关键词自动回复带链接时用超链接
         * yx - 2023.03.03
         * 【ID1000595】
         * 【优化】公众号关键词回复链接
         * yx - 2023.03.20
         * [新增]:htmlspecialchars_decode过滤
         * htmlspecialchars_decode($keywordInfo['return_link'])
         */
        if ($keywordInfo['return_text'] != '' && $keywordInfo['return_link'] != '') {
            $content = "<a href='" . htmlspecialchars_decode($keywordInfo['return_link']) . "'>" . $keywordInfo['return_text'] . "</a>";
            $this->outputMessage($object, $content, 'text');
        } else {
            if ($keywordInfo['return_text'] != '') {
                $this->outputMessage($object, $keywordInfo['return_text'], 'text');
            }
            if ($keywordInfo['return_link'] != '') {
                $content = "<a href='" . htmlspecialchars_decode($keywordInfo['return_link']) . "'>" . htmlspecialchars_decode($keywordInfo['return_link']) . "</a>";
                $this->outputMessage($object, $content, 'text');
            }
        }
        if ($keywordInfo['return_img_mediaid'] != '') {
            $this->outputMessage($object, $keywordInfo['return_img_mediaid'], 'image');
        }
    }

    /**
     * 根据关键字返回数据
     */
    protected function enterSearch($object, $keyword)
    {
        if ($keyword == "绑定") {
            $this->clickBinding($object);
        } else if ($keyword == "解绑") {
            $usinfo = $this->getUserInfo($object->FromUserName);
            if ($usinfo) {
                $this->actionUnbind($object);
            } else {
                $this->outputText($object, '您还没有绑定帐号！');
            }
        } else {
            //检查关键词表
            $check_keyword = model('WechatKeyword')->where('word', $keyword)->find();
            if ($check_keyword !== null) {
                $this->outputKeyword($object, $check_keyword);
            } else {
                $params = [
                    'count_total' => 0,
                    'current_page' => 1,
                    'pagesize' => 1,
                    'keyword' => $keyword
                ];
                $instance = new \app\common\lib\JobSearchEngine($params);
                $searchResult = $instance->run();
                if (count($searchResult['items']) > 0) {
                    $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                    $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');
                    $link_url = $this->sub_site_domain_m . 'joblist?keyword=' . $keyword;
                    $content_arr = [
                        'Title' => '符合“' . $keyword . '”的职位',
                        'Description' => '点击查看符合条件的最新信息',
                        'PicUrl' => $wechat_info_img,
                        'Url' => $link_url
                    ];
                    $this->outputArticle($object, $content_arr);
                } else {
                    $this->outputText($object, "没有找到包含关键字 " . $keyword . " 的信息，试试其他关键字");
                }
            }
        }
    }

    /**
     * 读取用户绑定信息
     */
    protected function getUserInfo($fromUsername)
    {
        $fromUsername = addslashes($fromUsername);
        $userBindInfo = model('MemberBind')->where('type', 'weixin')->where('openid', $fromUsername)->where('is_subscribe', 1)->find();
        if ($userBindInfo === null) {
            return null;
        }
        $memberinfo = model('Member')->where('uid', $userBindInfo['uid'])->find();
        return $memberinfo;
    }

    /**
     * 扫描事件
     */
    protected function actionScan($object, $subscribe = 0)
    {
        //用户未关注时，关注后的推送数据包中，EventKey含有“qrscene_”前缀
        $event_key = stripos($object->EventKey, 'qrscene_') === false ? $object->EventKey : ltrim($object->EventKey, 'qrscene_');
        parse_str($event_key, $event);
        $sceneQrcodeInfo = null;
        if (isset($event['scene_uuid'])) {
            $sceneQrcodeInfo = model('SceneQrcode')->where('uuid', $event['scene_uuid'])->find();
            if ($sceneQrcodeInfo !== null) {
                model('SceneQrcodeScanLog')->save(['pid' => $sceneQrcodeInfo['id'], 'addtime' => time()]);
                if ($subscribe) {
                    model('SceneQrcodeSubscribeLog')->save(['pid' => $sceneQrcodeInfo['id'], 'addtime' => time()]);
                }
            }
        }
        if (!isset($event['alias'])) {
            return;
        }
        switch ($event['alias']) {
            case 'mapQrcode':
                $jobid = intval($event['jobid']);
                if ($jobid == 0) {
                    $this->outputText($object, '职位不存在或已删除');
                    break;
                }
                $jobinfo = model('Job')->alias('a')
                    ->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT')
                    ->where('b.id', 'NOT NULL')
                    ->where('a.id', $jobid)
                    ->field('a.jobname,a.map_lat,a.map_lng,a.address,b.companyname')
                    ->find();
                if ($jobinfo === null) {
                    $this->outputText($object, '职位不存在或已删除');
                    break;
                }
                $content = $jobinfo['companyname'] . '高薪诚聘' . $jobinfo['jobname'] . "<a href='" . $this->sub_site_domain_m . 'job/' . $jobid . "'>点击详情</a>";
                $this->outputMessage($object, $content, 'text');
                $url = 'http://api.map.baidu.com/marker?location=' . $jobinfo['map_lat'] . ',' . $jobinfo['map_lng'] . '&title=' . $jobinfo['companyname'] . '&content=' . $jobinfo['address'] . '&output=html';
                $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');
                $content_arr = [
                    'Title' => $jobinfo['companyname'] . '高薪诚聘' . $jobinfo['jobname'],
                    'Description' => '点击查看详细地图',
                    'PicUrl' => $wechat_info_img,
                    'Url' => $url
                ];
                $this->outputArticle($object, $content_arr);
                break;
            case 'subscribe_job':
                $jobid = intval($event['jobid']);
                if ($jobid == 0) {
                    $this->outputText($object, '职位不存在或已删除');
                    break;
                }
                $jobinfo = model('Job')->alias('a')
                    ->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT')
                    ->where('b.id', 'NOT NULL')
                    ->where('a.id', $jobid)
                    ->field('a.id,a.jobname,a.amount,a.minwage,a.maxwage,a.negotiable,a.address,a.content,b.companyname,b.logo')
                    ->find();
                if ($jobinfo === null) {
                    $this->outputText($object, '职位不存在或已删除');
                    break;
                }
                if ($sceneQrcodeInfo !== null) {
                    $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                } else {
                    $mobile_page = $this->sub_site_domain_m . 'job/' . $jobinfo['id'];
                }
                $mobile_page = str_replace(":id", $jobid, $mobile_page);
                if (isset($event['scene_uuid'])) {
                    $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                }
                $jobinfo['wage_text'] = model('BaseModel')->handle_wage(
                    $jobinfo['minwage'],
                    $jobinfo['maxwage'],
                    $jobinfo['negotiable']
                );
                $jobinfo['amount_text'] = $jobinfo['amount'] == 0 ? '若干' : $jobinfo['amount'];
                $jobinfo['logo_src'] = $jobinfo['logo'] > 0 ? model('Uploadfile')->getFileUrl($jobinfo['logo']) : default_empty('logo');
                $content_arr = [
                    "Title" => '【' . $jobinfo['companyname'] . '】招聘 ' . $jobinfo['jobname'] . ' 职位 ' . $jobinfo['amount_text'] . ' 人，待遇 ' . $jobinfo['wage_text'] . ',工作地点：' . $jobinfo['address'],
                    "Description" => $jobinfo['content'],
                    "PicUrl" => $jobinfo['logo_src'],
                    "Url" => $mobile_page
                ];
                $this->outputArticle($object, $content_arr);
                break;
            case 'subscribe_resume':
                $resumeid = intval($event['resumeid']);
                if ($resumeid == 0) {
                    $this->outputText($object, '简历不存在或已删除');
                    break;
                }
                $resumeinfo = model('Resume')->where('id', $resumeid)->find();
                if ($resumeinfo === null) {
                    $this->outputText($object, '简历不存在或已删除');
                    break;
                }
                if ($sceneQrcodeInfo !== null) {
                    $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                } else {
                    $mobile_page = $this->sub_site_domain_m . 'resume/' . $resumeinfo['id'];
                }
                $mobile_page = str_replace(":id", $resumeid, $mobile_page);
                if (isset($event['scene_uuid'])) {
                    $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                }

                $category_data = model('Category')->getCache();
                $category_job_data = model('CategoryJob')->getCache();
                $resumeinfo['fullname'] = $resumeinfo['fullname'];
                if ($resumeinfo['display_name'] == 0) {
                    if ($resumeinfo['sex'] == 1) {
                        $resumeinfo['fullname'] = cut_str(
                            $resumeinfo['fullname'],
                            1,
                            0,
                            '先生'
                        );
                    } elseif ($resumeinfo['sex'] == 2) {
                        $resumeinfo['fullname'] = cut_str(
                            $resumeinfo['fullname'],
                            1,
                            0,
                            '女士'
                        );
                    } else {
                        $resumeinfo['fullname'] = cut_str(
                            $resumeinfo['fullname'],
                            1,
                            0,
                            '**'
                        );
                    }
                }
                $resumeinfo['sex_text'] = model('Resume')->map_sex[$resumeinfo['sex']];
                $resumeinfo['age'] = date('Y') - intval($resumeinfo['birthday']);
                $resumeinfo['education_text'] = isset(
                    model('BaseModel')->map_education[$resumeinfo['education']]
                )
                    ? model('BaseModel')->map_education[$resumeinfo['education']]
                    : '';
                $resumeinfo['experience_text'] =
                    $resumeinfo['enter_job_time'] == 0
                        ? '无经验'
                        : format_date($resumeinfo['enter_job_time']) . '经验';

                $resumeinfo['current_text'] = isset(
                    $category_data['QS_current'][$resumeinfo['current']]
                )
                    ? $category_data['QS_current'][$resumeinfo['current']]
                    : '';
                $resumeinfo['photo_img_src'] = $resumeinfo['photo_img'] > 0
                    ? model('Uploadfile')->getFileUrl($resumeinfo['photo_img'])
                    : default_empty('photo', $resumeinfo['sex']);
                //求职意向
                $intention_data = model('ResumeIntention')
                    ->field('id,rid,uid', true)
                    ->where(['rid' => ['eq', $resumeinfo['id']]])
                    ->select();
                $intention_jobs_text = [];
                foreach ($intention_data as $key => $value) {
                    $category_text = isset($category_job_data[$value['category']]) ? $category_job_data[$value['category']] : '';
                    $intention_jobs_text[] = $category_text;
                }
                if (!empty($intention_jobs_text)) {
                    $intention_jobs_text = array_unique($intention_jobs_text);
                    $resumeinfo['intention_jobs_text'] = implode(",", $intention_jobs_text);
                } else {
                    $resumeinfo['intention_jobs_text'] = '';
                }

                $content_arr = [
                    "Title" => '【' . $resumeinfo['fullname'] . '】在找' . $resumeinfo['intention_jobs_text'] . '相关的工作-' . config('global_config.sitename'),
                    "Description" => $resumeinfo['sex_text'] . '|' . $resumeinfo['age'] . '岁|' . $resumeinfo['education_text'] . '|' . $resumeinfo['experience_text'] . ',' . $resumeinfo['current_text'],
                    "PicUrl" => $resumeinfo['photo_img_src'],
                    "Url" => $mobile_page
                ];
                $this->outputArticle($object, $content_arr);
                break;
            case 'subscribe_company':
                if ($company_id = $event['comid']) $company = model('Company')->find($company_id);
                if (isset($company)) {
                    if ($sceneQrcodeInfo !== null) {
                        $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                    } else {
                        $mobile_page = $this->sub_site_domain_m . 'company/' . $company['id'];
                    }
                    $mobile_page = str_replace(":id", $company_id, $mobile_page);
                    if (isset($event['scene_uuid'])) {
                        $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                    }

                    $companyInfo = model('CompanyInfo')->where('comid', $company_id)->find();
                    $companycontents = $companyInfo['content'];
                    $logo = $company['logo'] > 0 ? model('Uploadfile')->getFileUrl($company['logo']) : default_empty('logo');

                    $content_arr = [
                        "Title" => '【' . $company['companyname'] . '】招聘-' . config('global_config.sitename'),
                        "Description" => $companycontents,
                        "PicUrl" => $logo,
                        "Url" => $mobile_page
                    ];
                    $this->outputArticle($object, $content_arr);
                } else {
                    $this->outputText($object, '企业不存在或已删除');
                }
                break;
            case 'subscribe_index':
                if ($sceneQrcodeInfo !== null) {
                    $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                } else {
                    $mobile_page = $this->sub_site_domain_m;
                }
                if (isset($event['scene_uuid'])) {
                    $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                }

                $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');
                $content_arr = [
                    "Title" => '找工作招人才就上' . config('global_config.sitename'),
                    "Description" => config('global_config.sitename') . '-个人求职、企业招聘专业平台',
                    "PicUrl" => $wechat_info_img,
                    "Url" => $mobile_page
                ];
                $this->outputArticle($object, $content_arr);
                break;
            case 'subscribe_reg_personal':
                if ($sceneQrcodeInfo !== null) {
                    $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                } else {
                    $mobile_page = $this->sub_site_domain_m . 'member/reg/personal';
                }
                if (isset($event['scene_uuid'])) {
                    $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                }

                $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');
                $content_arr = [
                    "Title" => '欢迎注册' . config('global_config.sitename') . '求职会员，好工作职等你挑！',
                    "Description" => '靠谱好工作就上' . config('global_config.sitename') . '(' . config('global_config.sitedomain') . config('global_config.sitedir') . ')！',
                    "PicUrl" => $wechat_info_img,
                    "Url" => $mobile_page
                ];
                $this->outputArticle($object, $content_arr);
                break;
            case 'subscribe_reg_company':
                if ($sceneQrcodeInfo !== null) {
                    $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                } else {
                    $mobile_page = $this->sub_site_domain_m . 'member/reg/company';
                }
                if (isset($event['scene_uuid'])) {
                    $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                }
                $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');
                $content_arr = [
                    "Title" => '欢迎注册' . config('global_config.sitename') . '招聘会员，海量人才等你挑！',
                    "Description" => config('global_config.sitename') . '-本地人才库等你发现！',
                    "PicUrl" => $wechat_info_img,
                    "Url" => $mobile_page
                ];
                $this->outputArticle($object, $content_arr);
                break;
            case 'subscribe_notice':
                if ($notice_id = $event['noticeid']) $notice = model('Notice')->find($notice_id);
                if (isset($notice)) {
                    if ($sceneQrcodeInfo !== null) {
                        $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                    } else {
                        $mobile_page = $this->sub_site_domain_m . 'notice/' . $notice['id'];
                    }
                    $mobile_page = str_replace(":id", $notice_id, $mobile_page);
                    if (isset($event['scene_uuid'])) {
                        $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                    }
                    $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                    $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');

                    $content_arr = [
                        "Title" => $notice['title'] . '-' . config('global_config.sitename'),
                        "Description" => '点击查看 >>',
                        "PicUrl" => $wechat_info_img,
                        "Url" => $mobile_page
                    ];
                    $this->outputArticle($object, $content_arr);
                } else {
                    $this->outputText($object, '公告不存在或已删除');
                }
                break;
            case 'subscribe_news':
                if ($news_id = $event['newsid']) $news = model('Article')->find($news_id);
                if (isset($news_id)) {
                    if ($sceneQrcodeInfo !== null) {
                        $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                    } else {
                        $mobile_page = $this->sub_site_domain_m . 'news/' . $news['id'];
                    }
                    $mobile_page = str_replace(":id", $news_id, $mobile_page);
                    if (isset($event['scene_uuid'])) {
                        $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                    }
                    $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                    $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');

                    $content_arr = [
                        "Title" => $news['title'] . '-' . config('global_config.sitename'),
                        "Description" => '点击查看 >>',
                        "PicUrl" => $wechat_info_img,
                        "Url" => $mobile_page
                    ];
                    $this->outputArticle($object, $content_arr);
                } else {
                    $this->outputText($object, '资讯不存在或已删除');
                }
                break;
            case 'subscribe_jobfairol':
                if ($jobfairol_id = $event['jobfairolid']) $jobfairol = model('JobfairOnline')->find($jobfairol_id);
                if (isset($jobfairol)) {
                    if ($sceneQrcodeInfo !== null) {
                        $mobile_page = $this->sub_site_domain_m . model('SceneQrcode')->type_arr[$sceneQrcodeInfo['type']]['mobile_page'];
                    } else {
                        $mobile_page = $this->sub_site_domain_m . 'jobfairol/' . $jobfairol['id'];
                    }
                    $mobile_page = str_replace(":id", $jobfairol_id, $mobile_page);
                    if (isset($event['scene_uuid'])) {
                        $mobile_page .= '?scene_uuid=' . $event['scene_uuid'];
                    }
                    $thumb = '';
                    if ($jobfairol['thumb']) {
                        $thumb = model('Uploadfile')->getFileUrl($jobfairol['thumb']);
                    }
                    if (!$thumb) {
                        $thumb = default_empty('jobfair_thumb');
                    }

                    $content_arr = [
                        "Title" => $jobfairol['title'] . '-' . config('global_config.sitename'),
                        "Description" => '点击查看>>',
                        "PicUrl" => $thumb,
                        "Url" => $mobile_page
                    ];
                    $this->outputArticle($object, $content_arr);
                } else {
                    $this->outputText($object, '招聘会不存在或已删除');
                }
                break;

            case 'admin_bind':
                if ($admin_id = $event['admin_id']) $admininfo = model('Admin')->find($admin_id);
                if (isset($admininfo)) {
                    if ($admininfo->openid == $object->FromUserName) {
                        $this->outputText($object, '绑定失败，失败原因：当前微信已经绑定过其他管理员');
                        break;
                    }
                    $admininfo->openid = $object->FromUserName;
                    $admininfo->save();
                    $this->outputText($object, '管理员绑定成功');
                } else {
                    $this->outputText($object, '管理员不存在');
                }
                break;
            case 'admin_login':
                $token = $event['token'];
                $openid = $object->FromUserName;
                if (!$openid || !$token) {
                    $this->outputText($object, '扫码失败，请刷新页面重试');
                    break;
                }
                $certinfo = model('AdminScanCert')->where('token', $token)->find();
                if ($certinfo === null) {
                    $this->outputText($object, '扫码失败，请刷新页面重试');
                    break;
                }
                $admininfo = model('Admin')->where('openid', $openid)->find();
                if ($admininfo === null) {
                    $this->outputText($object, '当前微信没有绑定管理员');
                    break;
                }
                $loginReturn = model('Admin')->setLogin($admininfo);
                $certinfo->info = json_encode($loginReturn);
                $certinfo->save();
                $this->outputText($object, '扫码登录成功');
                break;
            case 'subscribe_shortvideo':
                $vid = intval($event['vid']);
                $vtype = intval($event['vtype']);
                if (!$vid || !$vtype) {
                    $this->outputText($object, '扫码失败，请刷新页面重试');
                }
                $m = new SvCompanyVideo();
                if ($vtype == 2) {
                    $m = new SvPersonalVideo();
                }
                try {
                    $info = $m->detail($vid);
                } catch (Exception $e) {
                    $this->outputText($object, $e->getMessage());
                }

                if ($vtype == 1) {
                    $title = sprintf('【视频招聘】%s发布了招聘信息，走过路过不要错过-%s', $info['companyname'], config('global_config.sitename'));
                } else {
                    $title = sprintf('【视频求职】%s发布了视频简历，快来看看吧-%s', $info['fullname'], config('global_config.sitename'));
                }
                $content_arr = [
                    "Title" => $title,
                    "Description" => $info['title'],
                    "PicUrl" => $info['video_src'] . '?vframe/jpg/offset/1/w/100/h/100',
                    "Url" => $this->sub_site_domain_m . 'shortvideo/videoplay?id=' . $vid . '&gointype=playlist&videotype=' . $vtype
                ];
                $this->outputArticle($object, $content_arr);
                break;
            case 'member_bind_weixin':
                $uid = intval($event['uid']);
                $utype = intval($event['utype']);
                if (!$uid) {
                    $this->outputText($object, '绑定失败，请尝试重新扫码');
                }
                $empty_bind = true;
                $bindinfo_where = [
                    'type' => 'weixin'
                ];
                $weixin_userinfo = $this->getWeixinUserinfo($object->FromUserName);
                if ($weixin_userinfo === null) {
                    $this->outputText($object, '获取用户信息失败，请稍候再试');
                } else {
                    $openid = $weixin_userinfo['openid'];
                    $unionid = isset($weixin_userinfo['unionid']) ? $weixin_userinfo['unionid'] : '';
                    $nickname = '';
                    $avatar = '';
                }
                if ($unionid != '') {
                    $bindinfo_where['unionid'] = $unionid;
                } else {
                    $bindinfo_where['openid'] = $openid;
                }
                $bindinfo = model('MemberBind')->where($bindinfo_where)->find();
                //如果该openid或unionid已经绑定过了，查询已绑定的是否是自己的账户；
                //情况一：如果不是，清除掉，继续绑定；
                //情况二：如果是，查询openid一致不一致，不一致说明绑定的是其他端，这次还需要再存一下信息；否则跳过；
                do {
                    if ($bindinfo !== null) {
                        $empty_bind = false;
                        if ($bindinfo['uid'] != $uid) {
                            model('MemberBind')->where($bindinfo_where)->delete();
                            $empty_bind = true;
                            break;
                        }
                        if ($bindinfo['is_subscribe'] == 0) {
                            if ($bindinfo['openid'] != $openid) {
                                $empty_bind = true;
                                break;
                            }
                        }
                    }
                } while (0);

                //检测当前手机号是否绑定过其他账号
                if ($empty_bind === true) {
                    $bindinfo = model('MemberBind')->where('type', 'weixin')->where('uid', $uid)->find();
                    if ($bindinfo !== null && ($bindinfo['unionid'] != $unionid || $bindinfo['openid'] != $openid)) {
                        model('MemberBind')->where('type', 'weixin')->where('uid', $uid)->delete();
                    }
                }

                if ($empty_bind === true) {
                    //5.27  zxr   取消粉丝查询，解决扫码绑定无效问题
//                    $fansCheck = model('WechatFans')->where('openid',$openid)->find();
//                    if($fansCheck===null){
//                        $is_subscribe = 0;
//                    }else{
//                        $is_subscribe = 1;
//                    }
                    $sqlarr['uid'] = $uid;
                    $sqlarr['type'] = 'weixin';
                    $sqlarr['openid'] = $openid;
                    $sqlarr['unionid'] = $unionid;
                    $sqlarr['nickname'] = $nickname;
                    $sqlarr['avatar'] = $avatar;
                    $sqlarr['bindtime'] = time();
                    $sqlarr['is_subscribe'] = 1;
                    model('MemberBind')->save($sqlarr);
//                    if($is_subscribe==1){
                    model('Task')->doTask($uid, $utype, 'bind_weixin');
//                    }
                }
                $this->outputText($object, '绑定成功');
                break;
            /**
             * 【bug】企业详情模板二地图右上角二维码不出
             *  zch 2022.9.21
             * 【新增公众号发送企业地图】
             */
            case 'mapQrcode_company':
                $comid = intval($event['comid']);
                if ($comid == 0) {
                    $this->outputText($object, '企业不存在或已删除');
                    break;
                }
                $companyInfo = model('Company')->where('id', $comid)->field('companyname,map_lat,map_lng')->find();
                if ($companyInfo === null) {
                    $this->outputText($object, '企业不存在或已删除');
                    break;
                }
                $content = $companyInfo['companyname'] . "<a href='" . $this->sub_site_domain_m . 'company/' . $comid . "'>点击详情</a>";
                $this->outputMessage($object, $content, 'text');
                $url = 'http://api.map.baidu.com/marker?location=' . $companyInfo['map_lat'] . ',' . $companyInfo['map_lng'] . '&title=' . $companyInfo['companyname'] . '&output=html';
                $wechat_info_img = model('Uploadfile')->getFileUrl(config('global_config.wechat_info_img'));
                $wechat_info_img = $wechat_info_img ? $wechat_info_img : make_file_url('resource/wechat_info_img.jpg');
                $content_arr = [
                    'Title' => $companyInfo['companyname'],
                    'Description' => '点击查看详细地图',
                    'PicUrl' => $wechat_info_img,
                    'Url' => $url
                ];
                $this->outputArticle($object, $content_arr);
                break;
            default:
                break;
        }
        return;
    }

    /**
     * 获取微信用户信息
     */
    protected function getWeixinUserinfo($openid)
    {
        $instance = new \app\common\lib\Wechat;
        $userinfo = $instance->getUserinfoByOpenid($openid);
        return $userinfo === false ? null : $userinfo;
    }

    /**
     * 绑定事件
     */
    protected function clickBinding($object)
    {
        $memberinfo = $this->getUserInfo($object->FromUserName);
        if ($memberinfo !== null) {
            $content = "您已绑定过" . config('global_config.sitename') . ($memberinfo['utype'] == 1 ? '企业' : '个人') . "帐号【" . $memberinfo['mobile'] . "】,如需解绑,请回复'解绑'";
        } else {
            $weixin_userinfo = $this->getWeixinUserinfo($object->FromUserName);
            if ($weixin_userinfo === null) {
                $content = "获取用户信息失败，请稍候再试";
            } else {
                $openid = $weixin_userinfo['openid'];
                $unionid = isset($weixin_userinfo['unionid']) ? $weixin_userinfo['unionid'] : '';
                $query = '?bindType=weixin&openid=' . $openid . '&unionid=' . $unionid;
                $content = "您还未绑定" . config('global_config.sitename') . "帐号，现在开始绑定：<a href='" . $this->sub_site_domain_m . "member/bind" . $query . "'>点击开始注册/绑定帐号</a>";
            }
        }
        $this->outputText($object, $content);
    }

    /**
     * 联系客服
     */
    protected function clickContact($object)
    {
        $content = '客服电话：' . config('global_config.contact_tel');
        $this->outputText($object, $content);
    }

    /**
     * 一键刷新职位
     */
    protected function clickRefreshJob($object)
    {
        // 1.判断绑定信息
        $userInfo = $this->getUserInfo($object->FromUserName);
        if (null === $userInfo) {
            $weixin_userinfo = $this->getWeixinUserinfo($object->FromUserName);
            if ($weixin_userinfo === null) {
                $content = "获取用户信息失败，请稍候再试";
            } else {
                $openid = $weixin_userinfo['openid'];
                $unionid = isset($weixin_userinfo['unionid']) ? $weixin_userinfo['unionid'] : '';
                $query = '?bindType=weixin&openid=' . $openid . '&unionid=' . $unionid;
                $content = "您还未绑定" . config('global_config.sitename') . "帐号，现在开始绑定：<a href='" . $this->sub_site_domain_m . "member/bind" . $query . "'>点击开始注册/绑定帐号</a>";
            }
            $this->outputText($object, $content);
        } else {
            if (1 != $userInfo['status']) {
                $this->outputText($object, '此账号状态异常，请联系管理员。');
            }
            if (1 != $userInfo['utype']) {
                $this->outputText($object, '本操作需要绑定企业帐号！');
            }
        }

        // 2.查询刷新职位
        $jobIds = model('Job')
            ->where([
                'audit' => 1,
                'uid' => $userInfo['uid'],
                'is_display' => 1
            ])
            ->column('id');
        if (empty($jobIds)) {
            $this->outputText($object, '没有可刷新的职位');
        }

        $refreshParams = [
            'id' => $jobIds,
            'uid' => $userInfo['uid'],
            'platform' => 'wechat',
        ];

        try {
            $result = model('Job')->refreshJobMind($refreshParams, 3);
            if ($result['status'] === false) {
                $this->outputText($object, $result['msg']);
            }
        } catch (\Exception $e) {
            $this->outputText($object, '刷新失败【' . $e->getMessage() . '】');
        }

        $this->outputText($object, '职位一键刷新成功');

    }

    /**
     * 取消关注-解绑
     */
    protected function actionUnbind($object)
    {
        $fromUsername = addslashes($object->FromUserName);
        $bindinfo = model('MemberBind')->where('type', 'weixin')->where('openid', $fromUsername)->find();
        if ($bindinfo !== null) {
            model('MemberBind')->where('type', 'weixin')->where('uid', $bindinfo['uid'])->delete();
            $this->outputText($object, '解绑成功');
        } else {
            $this->outputText($object, '您还没有绑定帐号！');
        }
    }

    /**
     * 输出文字消息
     */
    protected function outputText($object, $content)
    {
        $xmlTpl = '<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
        </xml>';
        $result = sprintf($xmlTpl, addslashes($object->FromUserName), $object->ToUserName, time(), $content);
        $this->outputByEncryptMsg($result);
    }

    /**
     * 输出图片消息
     */
    protected function outputImage($object, $mediaId)
    {
        $xmlTpl = '<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
                <MediaId><![CDATA[%s]]></MediaId>
            </Image>
            </xml>';
        $result = sprintf($xmlTpl, addslashes($object->FromUserName), $object->ToUserName, time(), $mediaId);
        $this->outputByEncryptMsg($result);
    }

    //输出图文消息
    private function outputArticle($object, $article)
    {
        $itemTpl = "<item>
	        <Title><![CDATA[%s]]></Title>
	        <Description><![CDATA[%s]]></Description>
	        <PicUrl><![CDATA[%s]]></PicUrl>
	        <Url><![CDATA[%s]]></Url>
			</item>";
        $article['Description'] = str_replace('%', '', $article['Description']);
        $item_str = sprintf($itemTpl, $article['Title'], $article['Description'], $article['PicUrl'], $article['Url']);
        $xmlTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>%s</ArticleCount>
			<Articles>
			$item_str</Articles>
			</xml>";
        $result = sprintf($xmlTpl, addslashes($object->FromUserName), $object->ToUserName, time(), 1);
        $this->outputByEncryptMsg($result);
    }

    /**
     * 输出客服消息
     */
    protected function outputMessage($object, $content, $type = "text")
    {
        $openid = addslashes($object->FromUserName);
        $instance = new \app\common\lib\Wechat;
        $access_token = $instance->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $access_token;
        $datatext = [];
        if ($type == 'text') {
            $datatext = [
                "touser" => $openid,
                "msgtype" => "text",
                "text" => [
                    "content" => $content
                ]
            ];
        } else if ($type == 'image') {
            $datatext = [
                "touser" => $openid,
                "msgtype" => "image",
                "image" => [
                    "media_id" => $content
                ]
            ];
        }
        if (empty($datatext)) {
            return;
        }
        $datatext = json_encode($datatext, JSON_UNESCAPED_UNICODE);
        https_request($url, $datatext);
    }

    /**
     * 输出欢迎信息
     */
    protected function outputWelcome($object)
    {
        $content = htmlspecialchars_decode(config('global_config.wechat_welcome_text'), ENT_QUOTES);
        $openid = addslashes($object->FromUserName);
        $weixin_userinfo = $this->getWeixinUserinfo($openid);
        if ($weixin_userinfo !== null) {
            $openid = $weixin_userinfo['openid'];
            $unionid = isset($weixin_userinfo['unionid']) ? $weixin_userinfo['unionid'] : '';
        } else {
            $openid = $openid;
            $unionid = '';
        }
        $nickname = '';
        $avatar = '';
        $content = str_replace("{domain}", $this->sub_site_domain_m, $content);
        $content = str_replace("{openid}", $openid, $content);
        $content = str_replace("{unionid}", $unionid, $content);
        $content = str_replace("{nickname}", $nickname, $content);
        $content = str_replace("{avatar}", $avatar, $content);
        $this->outputMessage($object, $content, 'text');
        $this->outputMessage($object, config('global_config.wechat_welcome_img_mediaid'), 'image');
    }
}
