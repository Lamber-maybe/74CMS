<?php
/**
 * 智兔 - 简历解析
 */

namespace app\v1_0\controller\personal;

use app\common\lib\zhitoo\ResumeAnalysis;
use app\v1_0\controller\common\Base;
use think\Db;

class Analysis extends Base
{
    private $whiteList = [
        'notify'
    ];
    private $clientIp = [
        '47.96.144.49'   // zhitoo服务器
    ];

    public function _initialize()
    {
        parent::_initialize();
        if (!in_array($this->action_name, $this->whiteList)) {
            $this->checkLogin(2);
        }
    }

    /**
     * @Purpose: 创建简历解析
     *
     * @Method create()
     *
     * @param null
     *
     * @return Jsonp
     *
     * @throws null
     *
     * @link /v1_0/personal/analysis/create
     *
     * @author  Mr.yx
     * @version 1.1
     * @since   2022/12/2 0002
     */
    public function create()
    {
        // 1.接收参数 -
        $enclosure_id = input('post.enclosure_id/d', 0, 'intval');
        if ($enclosure_id <= 0) {
            $this->ajaxReturn(500, '请上传附件简历');
        }

        $file_arr = model('Uploadfile')->getFileUrlBatch($enclosure_id);

        if (!isset($file_arr[$enclosure_id]) || empty($file_arr[$enclosure_id])) {
            $this->ajaxReturn(500, '请先导入附件简历');
        } else {
            $enclosureResume = $file_arr[$enclosure_id];
        }

        // 2.判断是否已经上传附件简历
//        $isAnalysis = model('ResumeAnalysisLog')
//            ->where('uid', $this->userinfo->uid)
//            ->where('file_id', $enclosure_id)
//            ->where('request_id', '>', 0)
//            ->find();
//        if (isset($isAnalysis) && !empty($isAnalysis)) {
//            switch ($isAnalysis['notify_code']) {
//                case 1:
//                    $this->ajaxReturn(500, '已在解析中，请耐心等待......');
//                    break;
//
//                case 0:
//                    $this->ajaxReturn(500, '已完成解析', ['analysis_id' => $isAnalysis['id'], 'request_id' => $isAnalysis['request_id']]);
//                    break;
//
//                default:
//                    $this->ajaxReturn(500, '简历解析状态异常');
//                    break;
//            }
//        }

        $resumeAnalysis = new ResumeAnalysis();
        $result = $resumeAnalysis->create($enclosureResume);

        if (false === $result) {
            $logData = [
                'uid' => $this->userinfo->uid,
                'file_id' => $enclosure_id,
                'code' => $resumeAnalysis->getErrorCode(),
                'result' => $resumeAnalysis->getError(),
                'addtime' => time()
            ];
            model('ResumeAnalysisLog')->save($logData);

            $this->ajaxReturn(500, '解析失败', ['errCode' => $resumeAnalysis->getErrorCode(), 'errMsg' => $resumeAnalysis->getError()]);
        }

        if (!isset($result['result']) || empty($result['result'])) {
            $this->ajaxReturn(500, '解析失败');
        } else {
            $resumeData = $result['result'];
        }

        // 开始解析
        $notify = $this->transformation($resumeData);

        Db::startTrans();
        try {
            $logData = [
                'uid' => $this->userinfo->uid,
                'file_id' => $enclosure_id,
                'code' => 200,
                'result' => json_encode($result['result'], JSON_UNESCAPED_UNICODE),
                'notify' => json_encode($notify, JSON_UNESCAPED_UNICODE),
                'addtime' => time()
            ];
            model('ResumeAnalysisLog')->save($logData);

            $this->writeMemberActionLog($this->userinfo->uid, '导入附件简历');

            Db::commit();
            $this->ajaxReturn(200, '解析成功', $notify);
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function notify()
    {
        // 推送IP
        $client_ip = get_client_ip();

        /*
         * 非法推送IP
         */
        if (!in_array($client_ip, $this->clientIp)) {
            cache('zhitoo_resume_notify_error', 'Err: 403 forbidden,Illegal IP:[' . $client_ip . '] ');
            $this->ajaxReturn(403, 'Err: 403 IP Forbidden');
        }

        $contents = file_get_contents('php://input');

        $notify = json_decode($contents, JSON_UNESCAPED_UNICODE);

        $result = $notify['data'];

        $notifyData = [
            'notify' => json_encode($result['result'], JSON_UNESCAPED_UNICODE),
            'notify_code' => $notify['code'],
            'notify_msg' => $notify['message'],
            'notify_time' => time()
        ];
        model('ResumeAnalysisLog')
            ->isUpdate(true)
            ->allowField(true)
            ->save(
                $notifyData,
                ['request_id' => $result['async_predict_id']]
            );

        $this->ajaxReturn(200, 'SUCCESS');
    }

    public function isNotify()
    {
        // 1.接收参数 -
        $requestId = input('post.request_id/d', 0, 'intval');
        if ($requestId <= 0) {
            $this->ajaxReturn(500, '缺少【requestId】参数');
        }

        // 2.判断是否已经上传附件简历
        $isAnalysis = model('ResumeAnalysisLog')
            ->where('uid', $this->userinfo->uid)
            ->where('request_id', $requestId)
            ->find();
        if (null === $isAnalysis) {
            $this->ajaxReturn(500, '简历解析状态异常');
        }

        switch ($isAnalysis['notify_code']) {
            case 0:
                $return = $this->transformation($isAnalysis['notify']);
                break;

            case 1:
                $this->ajaxReturn(200, '正在解析中，请耐心等待......', ['code' => '500']);
                break;

            default:
                $this->ajaxReturn(500, '简历解析任务状态异常');
                break;
        }

        if (false === $return) {
            $this->ajaxReturn(500, '简历解析失败');
        } else {
            $this->ajaxReturn(200, '简历解析成功', $return);
        }
    }

    private function transformation($notify)
    {
        if (!is_array($notify)) {
            return false;
        } else {
            $result = $notify;
        }

        // 1.简历基本信息
        // 1.1 basic
        $fullname = !empty($result['姓名']) ? $result['姓名'] : '';
        $sex = $this->sexFormat($result['性别']);
        $sex_cn = $this->sexCnFormat($sex);
        $birthday = $this->monthFormat($result['出生日期']);
        $age = $this->getAge($birthday);
        $education = $this->educationFormat($result['最高学历']);
        $education_cn = $this->educationCnFormat($education);
        $householdaddress = !empty($result['籍贯']) ? $result['籍贯'] : '';
        $residence = !empty($result['现居住地']) ? $result['现居住地'] : '';
        $height = !empty($result['身高']) ? $result['身高'] : '';

        // 1.2 contact
        $mobile = !empty($result['手机号']) ? $result['手机号'] : '';
        $email = !empty($result['电子邮箱']) ? $result['电子邮箱'] : '';

        // 自我描述
        $specialty = !empty($result['个人评价']) ? $result['个人评价'] : '';

        // 2.教育经历
        $educationList = $this->resumeEducation($result['教育经历']);

        // 3.工作经历
        $workList = $this->resumeWork($result['工作经历']);

        // 4.语言能力
        $languageItem = [
            'language' => $result['外语种类'],
            '外语考试种类' => $result['外语考试种类'],
            '外语考试分数' => $result['外语考试分数']
        ];
        $languageList = $this->resumeLanguage($languageItem);

        return [
            'basic' => [
                'fullname' => $fullname,
                'sex' => $sex,
                'sex_cn' => $sex_cn,
                'birthday' => $birthday,
                'age' => $age,
                'education' => $education,
                'education_cn' => $education_cn,
                'householdaddress' => $householdaddress,
                'residence' => $residence,
                'height' => $height,
                'specialty' => $specialty
            ],
            'contact' => [
                'mobile' => $mobile,
                'email' => $email
            ],
            'resume_education' => $educationList,
            'resume_work' => $workList,
            'languageList' => $languageList,
        ];
    }

    private function monthFormat($time, $format = 'Y-m')
    {
        if (empty($time)) {
            return '';
        }

        $time_format = date_parse_from_format($format, $time);
        $month = !empty($time_format['month']) ? $time_format['month'] : false;
        $day = !empty($time_format['day']) ? $time_format['day'] : 1;
        $year = !empty($time_format['year']) ? $time_format['year'] : false;
        if ($year === false || $month === false) {
            return '';
        }
        $time_stamp = mktime(0, 0, 0, $month, $day, $year);
        return date($format, $time_stamp);
    }

    private function sexFormat($sex)
    {
        if (empty($sex)) {
            return 0;
        }
        $sexKey = array_search($sex, model('Resume')->map_sex);
        if (false === $sexKey) {
            return 0;
        }
        return $sexKey;
    }

    private function sexCnFormat($sex)
    {
        if (empty($sex)) {
            return '';
        }

        return model('Resume')->map_sex[$sex];
    }

    private function educationFormat($education)
    {
        if (empty($education)) {
            return 0;
        }
        $educationKey = array_search($education, model('BaseModel')->map_education);
        if (false === $educationKey) {
            return 0;
        }
        return $educationKey;
    }

    private function educationCnFormat($education)
    {
        if (empty($education)) {
            return '';
        }

        return model('BaseModel')->map_education[$education];
    }

    private function getAge($birthday)
    {
        if (empty($birthday)) {
            return '';
        }

        return date('Y') - intval($birthday);
    }

    private function resumeEducation($educationList)
    {
        if (!is_array($educationList) || empty($educationList)) {
            return [];
        }

        $return = [];
        foreach ($educationList as $education) {
            $educationID = $this->educationFormat($education['学历']);
            $arrTmp = [
                'school' => !empty($education['毕业院校']) ? $education['毕业院校'] : '',
                'starttime' => $this->monthFormat($education['入学时间']),
                'endtime' => $this->monthFormat($education['毕业时间']),
                'todate' => ($education['毕业时间'] === '至今') ? 1 : 0,
                'education' => $educationID,
                'major' => $education['专业'],
                'education_cn' => $this->educationCnFormat($educationID)
            ];
            $return[] = $arrTmp;
            unset($arrTmp);
        }
        return $return;
    }

    private function resumeWork($workList)
    {
        if (!is_array($workList) || empty($workList)) {
            return [];
        }

        $return = [];
        foreach ($workList as $work) {
            $arrTmp = [
                'companyname' => !empty($work['工作单位']) ? $work['工作单位'] : '',
                'jobname' => !empty($work['岗位名称']) ? $work['岗位名称'] : '',
                'duty' => !empty($work['工作内容']) ? $work['工作内容'] : '',
                'starttime' => $this->monthFormat($work['工作开始时间']),
                'endtime' => $this->monthFormat($work['工作结束时间']),
                'todate' => ($work['工作结束时间'] === '至今') ? 1 : 0
            ];
            $return[] = $arrTmp;
            unset($arrTmp);
        }
        return $return;
    }

    private function resumeLanguage($languageItem)
    {
        if (!is_array($languageItem) || empty($languageItem)) {
            return [
                'language' => 0,
                'language_cn' => '',
                'level' => 0,
                'level_cn' => '',
            ];
        }

        $language = $this->languageFormat($languageItem['language']);
        return [
            'language' => $language,
            'language_cn' => $this->languageCnFormat($language),
            'level' => 0,
            'level_cn' => '',
        ];
    }

    private function languageFormat($language)
    {
        if (empty($language)) {
            return 0;
        }
        $languageKey = array_search($language, model('Category')->getCache('QS_language'));
        if (false === $languageKey) {
            return 0;
        }
        return $languageKey;
    }

    private function languageCnFormat($language)
    {
        if (empty($language)) {
            return '';
        }
        return model('Category')->getCache('QS_language')[$language];
    }

}