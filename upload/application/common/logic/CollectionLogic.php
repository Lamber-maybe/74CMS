<?php
/**
 * 采集 Logic
 * @author chenyang
 * Date Time：2022年3月25日14:40:05
 */
namespace app\common\logic;

use app\common\model\CollectionSeting;

class CollectionLogic
{
    // 当前时间
    protected $_currentTime;
    // 默认手机号
    protected $_defaultMobile = '13000000000';
    // 默认密码
    protected $_defaultPassword = '123456';
    // 职位Model
    protected $_jobModel;
    // 采集设置
    protected $_seting;
    // 职位采集设置
    protected $_jobSeting;
    // 企业采集设置
    protected $_companySeting;
    // 账号采集设置
    protected $_accountSeting;

    public function __construct(){
        // 验证采集功能是否开启
        $this->_verifyIsOpen();
    }

    /**
     * 保存职位信息
     * @access public
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月25日14:41:53
     */
    public function saveJob($params){
        #################### 验证用户身份 ####################
        $verifyResult = $this->_verifyUserIdentity($params);
        if ($verifyResult['status'] === false) {
            return callBack(false, $verifyResult['msg']);
        }

        $this->_currentTime = time();

        $this->_jobModel = model('job');
        // 开启事务
        $this->_jobModel->startTrans();
        try {
            #################### 验证企业信息 ####################
            $verifyResult = $this->_verifyCompanyInfo($params);
            if ($verifyResult['status'] === false) {
                // 注册会员和企业
                $params = $this->_registerMemberAndCompany($params);
            }else{
                $params['uid']        = $verifyResult['data']['uid'];
                $params['company_id'] = $verifyResult['data']['company_id'];
                // 修改企业信息
                $this->_updateCompanyData($params);
            }

            #################### 处理职位信息 ####################
            $handleResult = $this->_handleJobData($params);

            // 提交事务
            $this->_jobModel->commit();
        } catch (\Exception $e) {
            // 回滚事务
            $this->_jobModel->rollback();
            saveLog('保存职位失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存职位失败');
        }

        return callBack(true, $handleResult['msg'], ['jobid' => $handleResult['jobid']]);
    }

    /**
     * 验证用户身份
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月25日16:02:55
     */
    private function _verifyUserIdentity($params){
        $adminModel = model('Admin');
        $adminInfo = $adminModel->where('username', $params['username'])->find();
        if (empty($adminInfo) || $adminInfo === null) {
            return callBack(false, '未找到用户信息！');
        }
        // 验证密码
        if ($adminModel->makePassword($params['password'], $adminInfo->pwd_hash) !== $adminInfo->password) {
            return callBack(false, '身份信息有误！');
        }
        return callBack(true, 'SUCCESS');
    }

    /**
     * 验证企业信息
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月25日16:31:03
     */
    private function _verifyCompanyInfo($params){
        // 校验企业信息是否已存在
        $companyModel = model('Company');
        $companyInfo = $companyModel->where(['companyname' => $params['company_name']])->field('id,uid')->find();
        if (empty($companyInfo) || $companyInfo === null) {
            return callBack(false, '未查询到企业信息');
        }
        $memberInfo = model('Member')->where(['uid' => $companyInfo['uid']])->field('uid')->find();

        $data = [
            'uid'        => $memberInfo['uid'],
            'company_id' => $companyInfo['id'],
        ];
        return callBack(true, 'SUCCESS', $data);
    }

    /**
     * 注册会员和企业
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月25日16:35:58
     */
    private function _registerMemberAndCompany($params){
        // 注册会员信息
        $uid = $this->_registerMember();
        $params['uid'] = $uid;
        // 注册企业信息
        $companyId = $this->_registerCompany($params);
        $params['company_id'] = $companyId;
        return $params;
    }

    /**
     * 注册会员信息
     * @access private
     * @author chenyang
     * @return integer
     * Date Time：2022年3月25日16:49:31
     */
    private function _registerMember(){
        // 生成会员信息
        $memberData = $this->_generateMemberData();
        // 保存会员信息
        $uid = model('member')->insertGetId($memberData);
        if (empty($uid)) {
            throw new \Exception('新增会员信息失败，请求SQL为：'.model('member')->getLastSql());
        }
        return $uid;
    }

    /**
     * 生成会员信息
     * @access private
     * @author chenyang
     * @return array
     * Date Time：2022年3月25日18:04:31
     */
    private function _generateMemberData(){
        // 生成会员手机号
        $mobile = $this->_generateMemberMobile();
        // 获取前缀
        $prefix = '';
        if (!empty($this->_accountSeting)) {
            $prefix = isset($this->_accountSeting['name_prefix']) && !empty($this->_accountSeting['name_prefix'])
                ? $this->_accountSeting['name_prefix']
                : config('global_config.reg_prefix');
        }
        // 获取后缀
        $suffix = $mobile;
        if (isset($this->_accountSeting['name_rule']) && $this->_accountSeting['name_rule'] == 1) {
            $suffix = randstr(11, false);
        }
        // 生成会员名称
        $username = $prefix . $suffix;
        // 生成密码字符
        $pwdHash = randstr();

        $password = $this->_defaultPassword;
        // 设定密码 密码规则:1|与用户名相同,2|指定密码
        if (isset($this->_accountSeting['pwd_rule']) && $this->_accountSeting['pwd_rule'] == 1) {
            $password = $username;
        }elseif (isset($this->_accountSeting['pwd_rule']) && $this->_accountSeting['pwd_rule'] == 2) {
            $password = !empty($this->_accountSeting['password']) ? $this->_accountSeting['password'] : $this->_defaultPassword;
        }

        // 生成密码
        $password = model('Member')->makePassword($password, $pwdHash);

        $memberData = [
            'utype'                  => 1,
            'mobile'                 => $mobile,
            'username'               => $username,
            'email'                  => '',
            'password'               => $password,
            'pwd_hash'               => $pwdHash,
            'reg_time'               => $this->_currentTime,
            'reg_ip'                 => '',
            'reg_address'            => '',
            'last_login_time'        => $this->_currentTime,
            'last_login_ip'          => '',
            'last_login_address'     => '',
            'status'                 => 1,
            'avatar'                 => 0,
            'robot'                  => 1,
            'platform'               => 'web',
            'nologin_notice_counter' => 0,
            'disable_im'             => 0,
        ];
        return $memberData;
    }

    /**
     * 生成会员手机号
     * @access private
     * @author chenyang
     * @param  integer $mobile [手机号]
     * @return integer
     * Date Time：2022年3月25日18:56:11
     */
    private function _generateMemberMobile($mobile = 0){
        $memberModel = model('Member');
        // 查询最大一条手机号
        $condition = [
            'utype' => 1
        ];
        if ($mobile <= 0) {
            $memberInfo = $memberModel->where($condition)->order(['mobile' => 'desc'])->field('mobile')->find();
            if (empty($memberInfo) || $memberInfo === null) {
                // 没有则返回默认手机号
                return $this->_defaultMobile;
            }
            $mobile = $memberInfo['mobile'];
        }
        $mobile = $mobile + 1;
        // 校验自动生成的手机号是否已存在
        $condition['mobile'] = $mobile;
        $memberInfo = $memberModel->where($condition)->field('mobile')->find();
        if (empty($memberInfo) || $memberInfo === null) {
            // 不存在，证明唯一则返回
            return $mobile;
        }
        return $this->_generateMemberMobile($mobile);
    }

    /**
     * 注册企业信息
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * @return integer
     * Date Time：2022年3月28日09:23:39
     */
    private function _registerCompany($params){
        // 生成企业数据
        $companyData = $this->_generateCompanyData($params);
        $insertData = array_merge($companyData['basic'], $companyData['default']['basic']);

        // 保存企业信息
        $companyId = model('company')->insertGetId($insertData);
        if (empty($companyId)) {
            throw new \Exception('新增企业信息失败，请求SQL为：'.model('company')->getLastSql());
        }

        $companyData['info'] = array_merge($companyData['default']['info'], $companyData['info']);

        // 保存企业详情
        $companyData['info']['comid'] = $companyId;
        $companyInfoId = model('CompanyInfo')->insertGetId($companyData['info']);
        if (empty($companyInfoId)) {
            throw new \Exception('新增企业详情失败，请求SQL为：'.model('CompanyInfo')->getLastSql());
        }

        // 保存企业联系方式
        $companyData['contact']['comid'] = $companyId;
        $companyContactId = model('CompanyContact')->insertGetId($companyData['contact']);
        if (empty($companyContactId)) {
            throw new \Exception('新增企业联系方式失败，请求SQL为：'.model('CompanyContact')->getLastSql());
        }

        // 赠送套餐
        $setmeal = [
            'uid'        => $params['uid'],
            'note'       => '',
            'setmeal_id' => config('global_config.reg_service'),
        ];
        model('member')->setMemberSetmeal($setmeal);

        // 完成注册任务
        model('Task')->doTask($params['uid'], 1, 'reg');
        // 赠送优惠券
        $couponConfig = config('global_config.coupon_config');
        if (
            $couponConfig['is_open'] == 1
            &&
            $couponConfig['is_reg_gift'] == 1
            &&
            count($couponConfig['reg_gift_list']) > 0
        ) {
            // 发放优惠券
            $result = model('Coupon')->send([
                'setmeal_id' => -1,
                'uid'        => $params['uid'],
                'coupon_id'  => $couponConfig['reg_gift_list'],
            ]);
            if ($result === false) {
                throw new \Exception('发放优惠券失败，失败原因：'.model('Coupon')->getError());
            }
        }

        if ($insertData['cs_id'] > 0) {
            $customer_service = model('CustomerService')
                ->where('id', $insertData['cs_id'])
                ->find();
            model('NotifyRule')->notify($params['uid'], 1, 'reg', [
                'sitename' => config('global_config.sitename'),
                'contact' => isset($customer_service['name'])
                    ? $customer_service['name']
                    : '',
                'mobile' => isset($customer_service['mobile'])
                    ? $customer_service['mobile']
                    : '',
                'weixin' => isset($customer_service['weixin'])
                    ? $customer_service['weixin']
                    : '',
            ]);
        }

        return $companyId;
    }

    /**
     * 生成企业数据
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月25日18:04:31
     */
    private function _generateCompanyData($params){
        // 转换企业性质
        $companyNature = $this->_convertCompanyNature($params['company_nature']);
        // 转换企业所属行业
        $companyTrade = $this->_convertCompanyTrade($params['company_trade']);
        // 转换企业地区
        $district = !empty($params['company_district']) ? $params['company_district'] : $params['company_address'];
        // 避免比较相似度时匹配度过低，只保留十个字符
        if (iconv_strlen($district) > 10) {
            $district = mb_substr($district, 0, 10);
        }
        $district = $this->_convertDistrict($district, 1);
        // 转换企业规模
        $companyScale = $this->_convertScale($params['company_scale']);

        $companyData = [
            'basic' => [
                'nature'      => $companyNature,
                'trade'       => $companyTrade,
                'district1'   => $district['district1'],
                'district2'   => $district['district2'],
                'district3'   => $district['district3'],
                'district'    => $district['district3'],
                'scale'       => $companyScale,
                'updatetime'  => $this->_currentTime,
            ],
            'info' => [
                'uid'        => $params['uid'],
                'website'    => $params['company_website'],
                'address'    => $params['company_address'],
            ],
            'contact' => [
                'uid'       => $params['uid'],
                'contact'   => '',
                'mobile'    => '',
                'weixin'    => '',
                'telephone' => '',
                'qq'        => '',
                'email'     => '',
            ],
            // 默认数据，新增时使用
            'default' => [
                'basic' => [
                    'uid'         => $params['uid'],
                    'companyname' => $params['company_name'],
                    'short_name'  => '',
                    'registered'  => !empty($this->_companySeting) ? $this->_companySeting['registered'] : 0,
                    'currency'    => !empty($this->_companySeting) ? $this->_companySeting['currency'] : 0,
                    'tag'         => '',
                    'map_lat'     => 0.0,
                    'map_lng'     => 0.0,
                    'map_zoom'    => 0,
                    'logo'        => 0,
                    'addtime'     => $this->_currentTime,
                    'refreshtime' => $this->_currentTime,
                    'platform'    => 'web',
                    'is_display'  => !empty($this->_companySeting) ? $this->_companySeting['is_display'] : 1,
                    'audit'       => !empty($this->_companySeting) ? $this->_companySeting['audit_status'] : 1,
                    'robot'       => 1,
                    'cs_id'       => model('member')->distributionCustomerService(), // 分配客服
                ],
                'info' => [
                    'short_desc' => '',
                    'content'    => '',
                ],
            ],
        ];

        // 转换企业简介
        if (isset($params['company_short_desc']) && !empty($params['company_short_desc'])) {
            $companyData['info']['short_desc'] = $this->_convertContent($params['company_short_desc']);
        }
        // 转换企业介绍
        if (isset($params['company_content']) && !empty($params['company_content'])) {
            $companyData['info']['content'] = $this->_convertContent($params['company_content']);
        }

        return $companyData;
    }

    /**
     * 处理职位信息
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月29日11:28:11
     */
    private function _handleJobData($params){
        // 生成职位信息
        $jobData = $this->_generateJobData($params);

        // 查询职位是否已存在
        $condition = [
            'uid'     => $params['uid'],
            'jobname' => $params['jobname'],
        ];
        $jobInfo = $this->_jobModel->where($condition)->field('id')->find();
        if (empty($jobInfo) || $jobInfo === null) {
            #################### 新增职位 ####################
            $insertData = array_merge($jobData['basic'], $jobData['default']);
            $jobId = $this->_jobModel->insertGetId($insertData);
            if (empty($jobId)) {
                throw new \Exception('新增职位信息失败，请求SQL为：'.$this->_jobModel->getLastSql());
            }

            // 新增职位联系方式
            $jobData['contact']['jid'] = $jobId;
            $result = model('JobContact')->insertGetId($jobData['contact']);
            if (empty($result)) {
                throw new \Exception('新增职位联系方式失败，请求SQL为：'.model('JobContact')->getLastSql());
            }

            $msg = '新增职位成功';
        }else{
            #################### 更新职位 ####################
            $updateResult = $this->_jobModel->where(['id' => $jobInfo['id']])->update($jobData['basic']);
            if ($updateResult === false) {
                throw new \Exception('更新职位信息失败，请求SQL为：'.$this->_jobModel->getLastSql());
            }

            $msg   = '更新成功，职位已存在';
            $jobId = $jobInfo['id'];
        }

        // 更新职位索引表
        $result = model('Job')->refreshSearch($jobId);
        if ($result === false) {
            throw new \Exception('更新职位索引失败，失败原因：'.model('Job')->getError());
        }

        return [
            'msg'   => $msg,
            'jobid' => $jobId
        ];
    }

    /**
     * 生成职位信息
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月29日11:39:58
     */
    private function _generateJobData($params){
        // 转换工作性质
        $nature = $this->_convertJobNature($params['nature']);
        // 转换薪资
        $salary = $this->_convertSalary($params['salary']);
        // 转换招聘人数
        $recruitNum = $this->_convertRecruit($params['recruit_num']);
        // 转换学历要求
        $education = $this->_convertEducation($params['education']);
        // 转换经验要求
        $experience = $this->_convertExperience($params['experience']);
        // 转换地区
        $district = $this->_convertDistrict($params['district'], 2);
        // 转换岗位福利
        $welfare = $this->_convertWelfare($params['welfare']);
        // 转换职位类别
        $category = $this->_convertCategory($params['jobname']);
        // 获取用户套餐内容
        $setmeal = model('Member')->getMemberSetmeal($params['uid']);
        // 转换内容
        $content = $this->_convertContent($params['content']);

        $jobData = [
            'basic' => [
                'uid'         => $params['uid'],
                'company_id'  => $params['company_id'],
                'jobname'     => $params['jobname'],
                'nature'      => $nature,
                'category1'   => $category['category1'],
                'category2'   => $category['category2'],
                'category3'   => $category['category3'],
                'category'    => $category['category3'],
                'minwage'     => $salary[0],
                'maxwage'     => $salary[1],
                'negotiable'  => $salary[2],
                'education'   => $education,
                'experience'  => $experience,
                'content'     => $content,
                'amount'      => $recruitNum,
                'tag'         => $welfare,
                'district1'   => $district['district1'],
                'district2'   => $district['district2'],
                'district3'   => $district['district3'],
                'district'    => $district['district3'],
                'address'     => $params['address'],
                'updatetime'  => $this->_currentTime,
            ],
            'contact' => [
                'uid'                 => $params['uid'],
                'contact'             => '',
                'mobile'              => '',
                'weixin'              => '',
                'telephone'           => '',
                'qq'                  => '',
                'email'               => '',
                'is_display'          => 1,
                'use_company_contact' => 1,
            ],
            // 默认数据，新增时使用
            'default' => [
                'emergency'      => 0,
                'stick'          => 0,
                'sex'            => 0,
                'minage'         => !empty($this->_jobSeting) ? $this->_jobSeting['minage'] : 0,
                'maxage'         => !empty($this->_jobSeting) ? $this->_jobSeting['maxage'] : 0,
                'age_na'         => !empty($this->_jobSeting) ? $this->_jobSeting['age_na'] : 1,
                'department'     => '',
                'addtime'        => $this->_currentTime,
                'refreshtime'    => $this->_currentTime,
                'setmeal_id'     => $setmeal['setmeal_id'],
                'is_display'     => !empty($this->_jobSeting) ? $this->_jobSeting['recruit_status'] : 1,
                'audit'          => !empty($this->_jobSeting) ? $this->_jobSeting['audit_status'] : 1,
                'click'          => 0,
                'user_status'    => 1,
                'robot'          => 1,
                'map_lat'        => 0,
                'map_lng'        => 0,
                'map_zoom'       => 0,
                'platform'       => 'web',
                'custom_field_1' => '',
                'custom_field_2' => '',
                'custom_field_3' => '',
            ],
        ];
        return $jobData;
    }

    /**
     * 转换工作性质
     * @access private
     * @author chenyang
     * @param  string $nature [工作性质]
     * @return array
     * Date Time：2022年3月28日14:28:37
     */
    private function _convertJobNature($nature){
        // 1 => '全职',
        // 2 => '实习'
        $default = 1;
        if (isset($this->_jobSeting['nature']) && !empty($this->_jobSeting['nature'])) {
            $default = $this->_jobSeting['nature'];
        }

        if (empty($nature)) return $default;

        if (false !== stripos($nature, '全职')) {
            $natureId = 1;
        }elseif (false !== stripos($nature, '实习')) {
            $natureId = 2;
        }
        return $natureId;
    }

    /**
     * 转换薪资
     * @access private
     * @author chenyang
     * @param  string $salary [薪资]
     * @return array
     * Date Time：2022年3月28日14:56:45
     */
    private function _convertSalary($salary){
        $default = [0, 0, 1];
        if (!empty($this->_jobSeting)) {
            $default = [$this->_jobSeting['minwage'], $this->_jobSeting['maxwage'], 0];
        }

        if (empty($salary) || strtolower(substr($salary, -1)) != 'k') {
            return $default;
        }

        $search = ['K', 'k'];
        $salary = str_replace($search, '', $salary);

        $search = ['～','~','－','——','_','至','到'];
        $salary = str_replace($search, '-', $salary);

        $salaryArr = explode('-', $salary);
        if (empty($salaryArr) || count($salaryArr) != 2) {
            return $default;
        }
        foreach ($salaryArr as &$value) {
            $value = $value * 1000;
        }
        // 有薪资的情况不面议
        array_push($salaryArr, 0);
        return $salaryArr;
    }

    /**
     * 转换招聘人数
     * @access private
     * @author chenyang
     * @param  string $recruitNum [招聘人数]
     * @return integer
     * Date Time：2022年3月28日15:45:15
     */
    private function _convertRecruit($recruitNum){
        if (empty($recruitNum) || mb_substr($recruitNum, -1) != '人') {
            return isset($this->_jobSeting['recruit_num']) ? $this->_jobSeting['recruit_num'] : 0;
        }
        return mb_substr($recruitNum, 0, -1);
    }

    /**
     * 转换学历要求
     * @access private
     * @author chenyang
     * @param  string $education [学历要求]
     * @return integer
     * Date Time：2022年3月28日15:56:58
     */
    private function _convertEducation($education){
        // 0 => '不限',
        // 1 => '初中',
        // 2 => '高中',
        // 3 => '中技',
        // 4 => '中专',
        // 5 => '大专',
        // 6 => '本科',
        // 7 => '硕士',
        // 8 => '博士',
        // 9 => '博后',
        if (empty($education)) {
            // 默认大专学历
            return isset($this->_jobSeting['education']) ? $this->_jobSeting['education'] : 5;
        }
        $mapEducation = model('BaseModel')->map_education;
        foreach ($mapEducation as $key => $value) {
            if (false !== stripos($education, $value)) {
                return $key;
            }
        }
        return 5;
    }

    /**
     * 转换经验要求
     * @access private
     * @author chenyang
     * @param  string $experience [经验要求]
     * @return integer
     * Date Time：2022年3月28日15:56:58
     */
    private function _convertExperience($experience){
        $search = ['经验', '年', '以上'];
        $experience = str_replace($search, '', $experience);
        if (empty($experience)) {
            return isset($this->_jobSeting['experience']) ? $this->_jobSeting['experience'] : 0;
        }

        //     0 => '经验不限',
        //     1 => '应届生',
        //     2 => '1年',
        //     3 => '2年',
        //     4 => '3年',
        //     5 => '3-5年',
        //     6 => '5-10年',
        //     7 => '10年以上',

        switch ($experience) {
            case '应届生':
                $experienceId = 1;
                break;
            // 1年
            case 1:
                $experienceId = 2;
                break;
            // 2年
            case 2:
                $experienceId = 3;
                break;
            // 3年
            case 3:
                $experienceId = 4;
                break;
            // 3-5年
            case 4:
                $experienceId = 5;
                break;
            // 5-10年
            case $experience >= 5 && $experience < 10:
                $experienceId = 6;
                break;
            // 10年以上
            case $experience >= 10:
                $experienceId = 7;
                break;
            // 默认经验不限
            default:
                $experienceId = isset($this->_jobSeting['experience']) ? $this->_jobSeting['experience'] : 0;
                break;
        }
        return $experienceId;
    }

    /**
     * 转换地区
     * @access private
     * @author chenyang
     * @param  string  $district [地区]
     * @param  integer $type     [1|企业,2|职位]
     * @return array
     * Date Time：2022年3月29日08:58:58
     */
    private function _convertDistrict($district, $type){
        $field = [
            'id',
            'pid',
            'name',
            'level',
        ];
        $districtModel = model('CategoryDistrict');
        $districtList = $districtModel->column($field);
        // 比较相似度
        $result = $this->_compareSimilar($districtList, $district, 'name', $this->_seting['matching_accuracy']);
        if (empty($result)) {
            // 判断是企业还是职位
            if ($type == 1 && !empty($this->_companySeting)) {
                $district1['id'] = $this->_companySeting['district1'];
                $district2['id'] = $this->_companySeting['district2'];
                $district3['id'] = $this->_companySeting['district3'];
            }elseif ($type == 2 && !empty($this->_jobSeting)) {
                $district1['id'] = $this->_jobSeting['district1'];
                $district2['id'] = $this->_jobSeting['district2'];
                $district3['id'] = $this->_jobSeting['district3'];
            }else{
                // 取第一个一级区域
                $district1 = $districtModel->where(['level' => 1])->field($field)->order(['id' => 'asc'])->find();
                // 取第一个二级区域
                $district2 = $districtModel->where(['pid' => $district1['id']])->field($field)->order(['id' => 'asc'])->find();
                // 取第一个三级区域
                $district3 = $districtModel->where(['pid' => $district2['id']])->field($field)->order(['id' => 'asc'])->find();
            }
        }else{
            if ($result['level'] == 1) {
                $district1 = $result;
                // 取第一个二级区域
                $district2 = $districtModel->where(['pid' => $district1['id']])->field($field)->order(['id' => 'asc'])->find();
                // 取第一个三级区域
                $district3 = $districtModel->where(['pid' => $district2['id']])->field($field)->order(['id' => 'asc'])->find();
            }elseif ($result['level'] == 2) {
                $district2 = $result;
                $district1 = $districtModel->where(['id' => $district2['pid']])->field($field)->find();
                // 取第一个三级区域
                $district3 = $districtModel->where(['pid' => $district2['id']])->field($field)->order(['id' => 'asc'])->find();
            }else{
                $district3 = $result;
                $district2 = $districtModel->where(['id' => $district3['pid']])->field($field)->find();
                $district1 = $districtModel->where(['id' => $district2['pid']])->field($field)->find();
            }
        }

        $districtData = [
            'district1' => isset($district1['id']) && !empty($district1['id']) ? $district1['id'] : 0,
            'district2' => isset($district2['id']) && !empty($district2['id']) ? $district2['id'] : 0,
            'district3' => isset($district3['id']) && !empty($district3['id']) ? $district3['id'] : 0,
        ];
        return $districtData;
    }

    /**
     * 比较相似度
     * @access private
     * @author chenyang
     * @param  array   $data
     * @param  string  $str
     * @param  string  $arrKey
     * @param  integer $ratio  [相似比率]
     * @return array
     * Date Time：2022年3月29日09:13:26
     */
    private function _compareSimilar($data, $str, $arrKey, $ratio = 0){
        if (empty($str)) return [];

        foreach ($data as $key => $list) {
            // 计算两个字符串的相似度
            similar_text($list[$arrKey], $str, $percent);
            $od[$percent] = $key;
        }

        // 对数组按照键名逆向排序
        krsort($od);

        // 判断如果达到该相似比率时返回对应的信息
        if ($ratio > 0) {
            foreach ($od as $k => $v) {
                if ($k >= $ratio) {
                    return $data[$v];
                }else{
                    return [];
                }
            }
        }

        // 取出第一个相似度最高的key
        $first = array_shift($od);

        return $data[$first];
    }

    /**
     * 转换岗位福利
     * @access private
     * @author chenyang
     * @param  string $welfare [岗位福利]
     * @return string
     * Date Time：2022年3月29日10:50:31
     */
    private function _convertWelfare($welfare){
        $default = '';
        if (isset($this->_jobSeting['welfare']) && !empty($this->_jobSeting['welfare'])) {
            $default = $this->_jobSeting['welfare'];
        }

        if (empty($welfare)) return $default;

        // 获取系统岗位福利
        $sysWelfare = model('Category')->getCache('QS_jobtag');
        $sysWelfareList = [];
        foreach ($sysWelfare as $key => $value) {
            $sysWelfareList[] = [
                'id'   => $key,
                'name' => $value,
            ];
        }

        $welfareArr = explode(',', $welfare);
        $welfareStr = '';
        foreach ($welfareArr as $value) {
            // 比较相似度
            $result = $this->_compareSimilar($sysWelfareList, $value, 'name', $this->_seting['matching_accuracy']);
            if (!empty($result)) {
                $welfareStr .= ','.$result['id'];
            }
        }

        if (empty($welfareStr)) {
            return $default;
        }

        return trim($welfareStr, ',');
    }

    /**
     * 转换职位类别
     * @access private
     * @author chenyang
     * @param  string $jobname [职位名称]
     * @return array
     * Date Time：2022年3月29日11:03:34
     */
    private function _convertCategory($jobname){
        $field = [
            'id',
            'pid',
            'name',
            'level',
        ];
        $categoryJobModel = model('CategoryJob');
        $categoryList = $categoryJobModel->column($field);
        // 比较相似度
        $result = $this->_compareSimilar($categoryList, $jobname, 'name', $this->_seting['matching_accuracy']);
        if (empty($result)) {
            if (!empty($this->_jobSeting)) {
                $category1['id'] = $this->_jobSeting['category1'];
                $category2['id'] = $this->_jobSeting['category2'];
                $category3['id'] = $this->_jobSeting['category3'];
            }else{
                // 取第一个一级职位类别
                $category1 = $categoryJobModel->where(['level' => 1])->field($field)->order(['id' => 'asc'])->find();
                // 取第一个二级职位类别
                $category2 = $categoryJobModel->where(['pid' => $category1['id']])->field($field)->order(['id' => 'asc'])->find();
                // 取第一个三级职位类别
                $category3 = $categoryJobModel->where(['pid' => $category2['id']])->field($field)->order(['id' => 'asc'])->find();
            }
        }else{
            if ($result['level'] == 1) {
                $category1 = $result;
                // 取第一个二级职位类别
                $category2 = $categoryJobModel->where(['pid' => $category1['id']])->field($field)->order(['id' => 'asc'])->find();
                // 取第一个三级职位类别
                $category3 = $categoryJobModel->where(['pid' => $category2['id']])->field($field)->order(['id' => 'asc'])->find();
            }elseif ($result['level'] == 2) {
                $category2 = $result;
                $category1 = $categoryJobModel->where(['id' => $category2['pid']])->field($field)->find();
                // 取第一个三级职位类别
                $category3 = $categoryJobModel->where(['pid' => $category2['id']])->field($field)->order(['id' => 'asc'])->find();
            }else{
                $category3 = $result;
                $category2 = $categoryJobModel->where(['id' => $category3['pid']])->field($field)->find();
                $category1 = $categoryJobModel->where(['id' => $category2['pid']])->field($field)->find();
            }
        }

        $categoryData = [
            'category1' => isset($category1['id']) && !empty($category1['id']) ? $category1['id'] : 0,
            'category2' => isset($category2['id']) && !empty($category2['id']) ? $category2['id'] : 0,
            'category3' => isset($category3['id']) && !empty($category3['id']) ? $category3['id'] : 0,
        ];
        return $categoryData;
    }

    /**
     * 转换企业性质
     * @access private
     * @author chenyang
     * @param  string $companyNature [企业性质]
     * @return integer
     * Date Time：2022年3月29日15:36:41
     */
    private function _convertCompanyNature($companyNature){
        $natureList = model('category')->where(array('alias'=>'QS_company_type'))->order(['id' => 'asc'])->column('id,name,alias');

        // 获取企业性质默认值
        if (isset($this->_companySeting['nature']) && !empty($this->_companySeting['nature'])) {
            $default = $this->_companySeting['nature'];
        }else{
            $where = [
                'alias' => 'QS_company_type',
                'name'  => ['like', '%其他%'],
            ];
            $whereOr = [
                'name'  => ['like', '%其它%'],
            ];
            $default = model('category')->where($where)->whereOr($whereOr)->field('id')->find();
            $default = !empty($default) ? $default['id'] : reset($natureList)['id'];
        }

        if (empty($companyNature)) {
            return $default;
        }

        // 比较相似度
        $result = $this->_compareSimilar($natureList, $companyNature, 'name', $this->_seting['matching_accuracy']);
        if (empty($result)) {
            return $default;
        }
        return $result['id'];
    }

    /**
     * 转换企业性质
     * @access private
     * @author chenyang
     * @param  string $companyTrade [企业所属行业]
     * @return integer
     * Date Time：2022年3月29日16:09:26
     */
    private function _convertCompanyTrade($companyTrade){
        $tradeList = model('category')->where(array('alias'=>'QS_trade'))->order(['id' => 'asc'])->column('id,name,alias');

        // 获取企业所属行业默认值
        if (isset($this->_companySeting['trade']) && !empty($this->_companySeting['trade'])) {
            $default = $this->_companySeting['trade'];
        }else{
            $where = [
                'alias' => 'QS_trade',
                'name'  => ['like', '%其他%'],
            ];
            $whereOr = [
                'name'  => ['like', '%其它%'],
            ];
            $default = model('category')->where($where)->whereOr($whereOr)->field('id')->find();
            $default = !empty($default) ? $default['id'] : reset($tradeList)['id'];
        }

        if (empty($companyTrade)) {
            return $default;
        }

        // 比较相似度
        $result = $this->_compareSimilar($tradeList, $companyTrade, 'name', $this->_seting['matching_accuracy']);
        if (empty($result)) {
            return $default;
        }
        return $result['id'];
    }

    /**
     * 转换企业规模
     * @access private
     * @author chenyang
     * @param  string $companyScale [企业规模]
     * @return integer
     * Date Time：2022年3月29日16:58:06
     */
    private function _convertScale($companyScale){
        $scaleList = model('category')->where(array('alias'=>'QS_scale'))->order(['id' => 'asc'])->column('id,name,alias');

        // 获取企业规模默认值
        if (isset($this->_companySeting['scale']) && !empty($this->_companySeting['scale'])) {
            $default = $this->_companySeting['scale'];
        }else{
            $default = reset($scaleList)['id'];
        }

        if (empty($companyScale)) {
            return $default;
        }

        // 比较相似度
        $result = $this->_compareSimilar($scaleList, $companyScale, 'name', $this->_seting['matching_accuracy']);
        if (empty($result)) {
            return $default;
        }
        return $result['id'];
    }

    /**
     * 修改企业信息
     * @access private
     * @author chenyang
     * @param  array $params [请求参数]
     * Date Time：2022年3月29日17:49:35
     */
    private function _updateCompanyData($params){
        // 生成企业数据
        $companyData = $this->_generateCompanyData($params);

        // 更新企业信息
        $updateResult = model('Company')->where(['id' => $params['company_id']])->update($companyData['basic']);
        if ($updateResult === false) {
            throw new \Exception('更新企业信息失败，请求SQL为：'.model('Company')->getLastSql());
        }

        // 更新企业详情
        $updateResult = model('CompanyInfo')->where(['comid' => $params['company_id']])->update($companyData['info']);
        if ($updateResult === false) {
            throw new \Exception('更新企业详情失败，请求SQL为：'.model('CompanyInfo')->getLastSql());
        }

        // 更新企业联系方式
        // 暂不启用，因为获取不到企业联系方式
        // $updateResult = model('CompanyContact')->where(['comid' => $params['company_id']])->update($companyData['contact']);
        // if ($updateResult === false) {
        //     throw new \Exception('更新企业联系方式失败，请求SQL为：'.model('CompanyContact')->getLastSql());
        // }
    }

    /**
     * 转换内容
     * @access private
     * @author chenyang
     * @param  string $content [内容]
     * @return string
     * Date Time：2022年3月30日14:49:19
     */
    private function _convertContent($content){
        if (empty($content)) return '';

        $content = html_entity_decode($content);
        // 去除前后换行标签
        $content = trim($content, '<br/>');
        // 将换行标签 转换为 换行符
        $search = [
            '&lt;br/&gt;',
            '<br/>',
        ];
        $content = str_replace($search, "\n", $content);

        return $content;
    }

    /**
     * 保存企业信息
     * @access public
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年3月30日17:15:50
     */
    public function saveCompany($params){
        #################### 验证用户身份 ####################
        $verifyResult = $this->_verifyUserIdentity($params);
        if ($verifyResult['status'] === false) {
            return callBack(false, $verifyResult['msg']);
        }

        $this->_currentTime = time();

        $companyModel = model('company');
        // 开启事务
        $companyModel->startTrans();
        try {
            #################### 验证企业信息 ####################
            $verifyResult = $this->_verifyCompanyInfo($params);
            if ($verifyResult['status'] === false) {
                // 注册会员和企业
                $params = $this->_registerMemberAndCompany($params);
                $msg = '新增企业成功';
            }else{
                $params['uid']        = $verifyResult['data']['uid'];
                $params['company_id'] = $verifyResult['data']['company_id'];
                // 修改企业信息
                $this->_updateCompanyData($params);
                $msg = '更新成功，企业已存在';
            }

            // 提交事务
            $companyModel->commit();
        } catch (\Exception $e) {
            // 回滚事务
            $companyModel->rollback();
            saveLog('保存企业失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存企业失败');
        }

        return callBack(true, $msg, ['company_id' => $params['company_id']]);
    }

    /**
     * 验证采集功能是否开启
     * @access public
     * @author chenyang
     * @param  array $params [请求参数]
     * @return array
     * Date Time：2022年4月12日11:27:37
     */
    public function _verifyIsOpen(){
        $model = new CollectionSeting();
        $info = $model->getInfo(['id' => 1]);
        if (empty($info) || $info['status'] == 0) {
            responseJson(400, '未开通采集功能');
        }
        $this->_seting = [
            'status'            => $info['status'],
            'matching_accuracy' => $info['matching_accuracy'],
        ];
        $this->_jobSeting     = !empty($info['job_seting']) ? json_decode($info['job_seting'], true) : [];
        $this->_companySeting = !empty($info['company_seting']) ? json_decode($info['company_seting'], true) : [];
        $this->_accountSeting = !empty($info['account_seting']) ? json_decode($info['account_seting'], true) : [];
    }

}