<?php
/**
 * 企业名录 Logic
 * @author edc
 * Date：2022-11-17
 */

namespace app\common\logic;

use app\common\lib\Soukebao;

class CompanyDirectoryLogic
{

    // 维度
    protected $_dimension = [
        'entName' => '企业名称',
        'opScope' => '经营范围',
        'appName' => '产品',
    ];
    // 营业状态
    protected $_entStatusArr = [
        1 => '在营/存续',
        2 => '吊销/撤销',
        3 => '注销',
        4 => '迁入/迁出',
        8 => '停业',
        9 => '其他',
    ];
    // 联系方式
    protected $_contactArr = [
        1 => '有手机',
        2 => '有固话',
        3 => '有邮箱',
        4 => '有QQ',
        5 => '无联系方式',
    ];

    /**
     * 保存配置
     * @access public
     * @param array $params [请求参数]
     * @param array $adminInfo [用户信息]
     * Date：2022-11-17
     * @author edc
     */
    public function saveConfig($params, $adminInfo)
    {
        if (!empty($params['app_key']) || !empty($params['app_secret'])) {
            if (empty($params['app_key'])) {
                responseJson(400, '请填写AppKey');
            }
            if (empty($params['app_secret'])) {
                responseJson(400, '请填写AppSecret');
            }
        }
        $configModel = model('Config');
        // 开启事务
        $configModel->startTrans();
        try {
            // 修改配置
            $updateWhere = [
                'name' => 'account_qy_directory'
            ];
            $updateData = [
                'value' => json_encode($params)
            ];

            // 获取原先配置
            $config = $configModel->where($updateWhere)->find();
            if (null === $config) {
                responseJson(400, '配置项异常');
            }
            $config = $config->toArray();
            $configValue = json_decode($config['value'], true);

            $result = $configModel->where($updateWhere)->update($updateData);
            if (false === $result) {
                throw new \Exception('保存配置失败-请求SQL为：' . $configModel->getLastSql());
            }


            $log_field = '企业名录，修改基础配置，';
            if ($params['app_key'] != $configValue['app_key']) {
                $log_field .= 'AppKey：' . $configValue['app_key'] . '->' . $params['app_key'] . '；';
            }
            if ($params['app_secret'] != $configValue['app_secret']) {
                $log_field .= 'AppSecret：' . $configValue['app_secret'] . '->' . $params['app_secret'];
            }

            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $adminInfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception('日志记录失败：' . model('AdminLog')->getError());
            }

            // 清理缓存
            rmdirs(RUNTIME_PATH . '/cache/');

            // 提交事务
            $configModel->commit();
        } catch (\Exception $e) {
            // 回滚事务
            $configModel->rollback();
            saveLog('报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            responseJson(400, '保存失败');
        }
    }

    /**
     * 查询缓存是否存在（用来前端是否提示弹窗使用）
     * @access public
     * @param array $params [请求参数]
     * @return array
     * Date：2022-11-30
     * @author edc
     */
    public function queryCacheIsExists($params)
    {
        // 获取查询条件
        $condition = $this->_getSearchCondition($params);
        // 生成查询条件
        $query = $this->_generateQueryCondition($condition);
        $mark = md5($query);
        // 查询缓存中是否存在
        $cacheField = [
            'id',
            'expire_time',
        ];
        $cacheWhere = [
            'mark' => $mark
        ];
        $cacheModel = db('company_directory_cache');
        $cacheInfo = $cacheModel->field($cacheField)->where($cacheWhere)->find();
        // 缓存中没有或已过期就做出提示
        $isExists = 0;
        if (!is_null($cacheInfo) && !empty($cacheInfo) && $cacheInfo['expire_time'] >= time()) {
            $isExists = 1;
        }
        return ['is_exists' => $isExists];
    }

    /**
     * 获取企业线索列表
     * @access public
     * @param array $params [请求参数]
     * @param array $adminInfo [用户信息]
     * @return array
     * Date：2022-11-17
     * @author edc
     */
    public function getCompanyClueList($params, $adminInfo)
    {
        // 获取查询条件
        $condition = $this->_getSearchCondition($params);
        // 生成查询条件
        $query = $this->_generateQueryCondition($condition);
        $mark = md5($query);
        // 查询缓存中是否存在
        $cacheField = [
            'id',
            'result',
            'expire_time',
        ];
        $cacheWhere = [
            'mark' => $mark
        ];
        $cacheModel = db('company_directory_cache');
        $cacheInfo = $cacheModel->field($cacheField)->where($cacheWhere)->find();

        // 生成可见的查询条件
        $visibleQuery = $this->_generateVisibleQueryCondition($params);

        // 缓存中没有或已过期就调取接口获取
        if (!is_null($cacheInfo) && !empty($cacheInfo) && $cacheInfo['expire_time'] >= time()) {
            $list = json_decode($cacheInfo['result'], true);
            $cost = 0;
        } else {
            // 调取搜客宝接口获取
            $soukebao = new Soukebao();
            $list = $soukebao->getCompanyList($condition);
            if (false === $list['status']) {
                responseJson(400, $list['msg']);
            }
            $list = $list['data'];
            // 开启事务
            $cacheModel->startTrans();
            try {
                // 添加缓存记录
                $currentTime = time();
                $cacheData = [
                    'result' => json_encode($list),
                    'expire_time' => $currentTime + 86400 * 30,// 过期时间为一个月
                ];
                if (is_null($cacheInfo) || empty($cacheInfo)) {
                    /******************* 新增 *******************/
                    $cacheData['mark'] = $mark;
                    $cacheData['query'] = $query;
                    $cacheData['create_time'] = $currentTime;
                    $result = $cacheModel->insertGetId($cacheData);
                    if (empty($result)) {
                        throw new \Exception('添加缓存记录失败-请求SQL为：' . $cacheModel->getLastSql());
                    }
                } else {
                    /******************* 更新 *******************/
                    $result = $cacheModel->where(['id' => $cacheInfo['id']])->update($cacheData);
                    if (false === $result) {
                        throw new \Exception('更新缓存记录失败-请求SQL为：' . $cacheModel->getLastSql());
                    }
                }
                // 记录接口的查询明细
                if (!empty($list['items'])) {
                    $recordData = [
                        'admin_id' => $adminInfo['id'],
                        'keyword' => $condition['keyword'],
                        'query' => json_encode($visibleQuery),
                        'current_page' => $list['page'],
                        'current_num' => count($list['items']),
                        'total_page' => $list['totalPage'],
                        'total_num' => $list['totalCount'],
                        'create_time' => $currentTime
                    ];
                    $recordModel = db('company_directory_record');
                    $result = $recordModel->insertGetId($recordData);
                    if (false === $result) {
                        throw new \Exception('记录查询明细失败-请求SQL为：' . $recordModel->getLastSql());
                    }
                }
                $cost = 1;
                // 提交事务
                $cacheModel->commit();
            } catch (\Exception $e) {
                // 回滚事务
                $cacheModel->rollback();
                saveLog('报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
                responseJson(400, '获取失败');
            }
        }

        $visibleQueryStr = '';
        foreach ($visibleQuery as $query_field) {
            $visibleQueryStr .= $query_field['title'] . ':' . $query_field['name'] . '；';
        }
        $visibleQueryStr = rtrim($visibleQueryStr, '；');

        $accountBalance = $this->getAccountBalance();
        $balanceTotal = (isset($accountBalance['total']) && !empty($accountBalance['total'])) ? $accountBalance['total'] : 0;

        model('AdminLog')->writeLog(
            '企业名录搜索，搜索词:' . $condition['keyword']
            . '；搜索条件：' . $visibleQueryStr
            . '。扣除查询余额' . $cost
            . '次，剩余' . $balanceTotal . '次查询额度。',
            $adminInfo,
            0,
            1
        );

        // 获取当前线索中通过企业名录导入的数据
        $clueModel = model('b2bcrm.CrmClue');
        $tripartiteIdArr = $clueModel->group('tripartite_id')->column('tripartite_id,id');
        unset($tripartiteIdArr['']);

        if (!empty($list['items'])) {
            foreach ($list['items'] as &$info) {
                $info['es_date'] = getMsecToDate($info['es_date'], 'Y-m-d');
                // 判断是否已存在与线索中
                $info['is_exist'] = 0;
                if (isset($tripartiteIdArr[$info['id']])) {
                    $info['is_exist'] = 1;
                }
            }
        }

        /******************* 计算下一页剩余数量 *******************/
        // 当前结果为空 或 当前页大于等于总页数时下一页为0
        if (empty($list['items']) || $list['page'] >= $list['totalPage']) {
            $nextSurplusNum = 0;
        } else {
            // 计算剩余数量
            $surplusNum = $list['totalCount'] - $list['page'] * count($list['items']);
            if ($surplusNum >= $list['perPage']) {
                $nextSurplusNum = $list['perPage'];
            } else {
                $nextSurplusNum = $surplusNum;
            }
        }
        $list['next_surplus_num'] = $nextSurplusNum;
        return $list;
    }

    /**
     * 获取查询条件
     * @access private
     * @param array $params [请求参数]
     * @return array
     * Date：2022-11-18
     * @author edc
     */
    private function _getSearchCondition($params)
    {
        $dimensionKey = array_keys($this->_dimension);
        // 维度：entName|企业名称, opScope|经营范围, appName|产品
        if (!isset($params['type']) || !in_array($params['type'], $dimensionKey)) {
            responseJson(400, '查询维度参数不正确！');
        }
        $condition['type'] = $params['type'];
        // 关键词
        if (!isset($params['keyword']) || empty($params['keyword'])) {
            responseJson(400, '请填写关键词！');
        }
        if (mb_strlen($params['keyword']) < 2) {
            responseJson(400, '关键词至少两个字！');
        }
        if (in_array($params['keyword'], ['公司', '有限', '集团', '科技', '信息', '技术'])) {
            responseJson(400, '非法关键词！');
        }
        $condition['keyword'] = $params['keyword'];
        // 地区
        if (isset($params['province']) && !empty($params['province'])) {
            // 省
            $condition['province'] = $params['province'];
            if (isset($params['city']) && !empty($params['city'])) {
                // 市
                $condition['city'] = $params['city'];
                if (isset($params['district']) && !empty($params['district'])) {
                    // 区
                    $condition['district'] = $params['district'];
                }
            }
        }
        // 营业状态：1|在营/存续，2|吊销/撤销，3|注销，4|迁入/迁出，8|停业，9|其他
        $entStatusArr = array_keys($this->_entStatusArr);
        if (isset($params['ent_status']) && !empty($params['ent_status'])) {
            $params['ent_status'] = explode(',', trim($params['ent_status'], ','));
            if (!empty(array_diff($params['ent_status'], $entStatusArr))) {
                responseJson(400, '营业状态不正确！');
            }
            $condition['ent'] = $params['ent_status'];
            /**
             *【ID1000464】
             * 【bug】企业名录30天内搜索同一条件时重复扣费(存储排序问题)
             * yx - 2022.12.12
             */
            sort($condition['ent']);
        }
        // 联系方式：1|有手机，2|有固话，3|有邮箱，4|有QQ，5|无联系方式
        $contactArr = array_keys($this->_contactArr);
        if (isset($params['contact']) && !empty($params['contact'])) {
            $params['contact'] = explode(',', trim($params['contact'], ','));
            if (!empty(array_diff($params['contact'], $contactArr))) {
                responseJson(400, '联系方式不正确！');
            }
            $condition['contact'] = $params['contact'];
            /**
             *【ID1000464】
             * 【bug】企业名录30天内搜索同一条件时重复扣费(存储排序问题)
             * yx - .12.12
             */
            sort($condition['contact']);
        }
        // 成立时间
        if ((isset($params['starttime']) && $params['starttime'] && isset($params['endtime']) && $params['endtime'])) {
            $startTime = strtotime($params['starttime']);
            $endTime = strtotime($params['endtime']);
            if ((empty($startTime) || empty($endTime)) && $startTime > $endTime) {
                responseJson(400, '成立时间段查询错误！');
            }
            // 成立时间开始时间
            $condition['starttime'] = $params['starttime'];
            // 成立时间结束时间
            $condition['endtime'] = $params['endtime'];
        }

        $condition['page'] = $params['page'];
        return $condition;
    }

    /**
     * 生成查询条件
     * @access private
     * @param array $params [请求参数]
     * @return string
     * Date：2022-11-17
     * @author edc
     */
    private function _generateQueryCondition($params)
    {
        // 排序
        ksort($params);
        return urldecode(http_build_query($params));
    }

    /**
     * 获取消耗记录列表
     * @access public
     * @param array $params [请求参数]
     * @return array
     * Date：2022-11-21
     * @author edc
     */
    public function getConsumeRecordList($params)
    {
        $field = [
            'a.id',
            'b.username',
            'a.keyword',
            'a.query',
            'a.current_page',
            'a.current_num',
            'a.total_page',
            'a.create_time',
        ];
        $order = ['a.id' => 'desc'];
        $list = db('company_directory_record')
            ->alias('a')
            ->join('admin b', 'a.admin_id = b.id')
            ->field($field)
            ->order($order)
            ->paginate($params['pagesize'])
            ->toArrays();
        if (!empty($list['items'])) {
            foreach ($list['items'] as &$info) {
                $info['query'] = json_decode($info['query'], true);
                $info['create_time'] = date('Y-m-d H:i:s', $info['create_time']);
                $info['content'] = '符合条件的信息共' . $info['total_page'] . '页，您已查看第' . $info['current_page'] . '页，' . $info['current_num'] . '条搜索结果';
                unset($info['total_page'], $info['current_page']);
            }
        }
        return $list;
    }

    /**
     * 导入线索
     * @access public
     * @param array $params [请求参数]
     * @param array $adminInfo [用户信息]
     * @return array
     * Date：2022-11-22
     * @author edc
     */
    public function importClue($params, $adminInfo)
    {
        $clueModel = model('b2bcrm.CrmClue');
        $clueContactModel = db('crm_clue_contact');
        $successSum = 0;
        $currentTime = time();
        $clueList = [];

        foreach ($params['clue_list'] as $clueInfo) {
            // 生成联系方式
            $contactList = $this->_generateContact($clueInfo['contacts']);

            $insertInfo = [];
            $insertInfo['name'] = $clueInfo['companyname'];
            $insertInfo['contacts'] = !empty($contactList['main_contact']) ? $contactList['main_contact']['contact'] : '';
            $insertInfo['mobile'] = !empty($contactList['main_contact']) ? $contactList['main_contact']['mobile'] : '';
            $insertInfo['weixin'] = '';
            $insertInfo['address'] = $clueInfo['address'];
            $insertInfo['creat_id'] = $adminInfo['id'];
            $insertInfo['createtime'] = $currentTime;
            $insertInfo['updatetime'] = $currentTime;
            $insertInfo['remark'] = '';
            $insertInfo['trade'] = 0;
            $insertInfo['tripartite_id'] = $clueInfo['id'];
            // 导入位置：1|我的线索，2|线索公海
            $placeName = '线索公海';
            if ($params['place'] == 1) {
                $insertInfo['admin_id'] = $adminInfo['id'];
                $insertInfo['collection_time'] = $currentTime; // 添加领取时间
                $placeName = '我的线索';
            }

            // 开启事务
            $clueModel->startTrans();
            try {
                // 新增线索
                $clueId = $clueModel->insertGetId($insertInfo);
                if (empty($clueId)) {
                    throw new \Exception('新增线索失败-请求SQL为：' . $clueModel->getLastSql());
                }
                // 新增线索联系人
                if (!empty($contactList['contact_list'])) {
                    foreach ($contactList['contact_list'] as &$contactInfo) {
                        $contactInfo['clue_id'] = $clueId;
                        $contactInfo['addtime'] = $currentTime;
                    }
                    $result = $clueContactModel->insertAll($contactList['contact_list']);
                    if (empty($result)) {
                        throw new \Exception('新增线索联系人失败-请求SQL为：' . $clueContactModel->getLastSql());
                    }
                }

                $clueList[] = '{' . $insertInfo['name'] . '}(线索ID:' . $clueId . ')';

                // 提交事务
                $clueModel->commit();

                // 记录成功数量
                $successSum++;
            } catch (\Exception $e) {
                // 回滚事务
                $clueModel->rollback();
                saveLog('报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
                continue;
            }
        }

        model('AdminLog')->writeLog(
            '企业名录导入线索，导入CRM线索：' . implode($clueList, '；') . '，导入位置：' . $placeName,
            $adminInfo,
            0,
            1
        );

        return ['success_num' => $successSum];
    }

    /**
     * 转换地区
     * @access private
     * @param array $districtList [地区数据]
     * @param string $address [地址]
     * @return array
     * Date：2022-11-22
     * @author edc
     */
    private function _convertDistrict($districtList, $address)
    {
        /**
         * 提高相识度
         * 1.去掉省、市字样
         * 2.保持在八个字内
         */
        $address = mb_substr(str_replace(['省', '市'], '', $address), 0, 8, 'UTF-8');

        $field = [
            'id',
            'pid',
            'name',
            'level',
        ];
        $districtModel = model('CategoryDistrict');
        // 比较相似度
        $result = $this->_compareSimilar($districtList, $address, 'name', 40);
        if (!empty($result)) {
            if ($result['level'] == 1) {
                $district1 = $result;
                // 取第一个二级区域
                $district2 = $districtModel->where(['pid' => $district1['id']])->field($field)->order(['id' => 'asc'])->find();
                // 取第一个三级区域
                $district3 = $districtModel->where(['pid' => $district2['id']])->field($field)->order(['id' => 'asc'])->find();
            } elseif ($result['level'] == 2) {
                $district2 = $result;
                $district1 = $districtModel->where(['id' => $district2['pid']])->field($field)->find();
                // 取第一个三级区域
                $district3 = $districtModel->where(['pid' => $district2['id']])->field($field)->order(['id' => 'asc'])->find();
            } else {
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
     * @param array $data
     * @param string $str
     * @param string $arrKey
     * @param integer $ratio [相似比率]
     * @return array
     * Date：2022-11-22
     * @author edc
     */
    private function _compareSimilar($data, $str, $arrKey, $ratio = 0)
    {
        if (empty($str)) return [];

        foreach ($data as $key => $list) {
            // 计算两个字符串的相似度
            similar_text($list[$arrKey], $str, $percent);
            $od[$percent] = $key;
            // $od[$percent] = $list;
        }

        // 对数组按照键名逆向排序
        krsort($od);

        // 判断如果达到该相似比率时返回对应的信息
        if ($ratio > 0) {
            foreach ($od as $k => $v) {
                if ($k >= $ratio) {
                    return $data[$v];
                } else {
                    return [];
                }
            }
        }

        // 取出第一个相似度最高的key
        $first = array_shift($od);

        return $data[$first];
    }

    /**
     * 生成联系方式
     * @access private
     * @param array $contacts
     * @return array
     * Date：2022-11-22
     * @author edc
     */
    private function _generateContact($contacts)
    {
        $return = [
            'contact_list' => [],
            'main_contact' => [],
        ];
        if (empty($contacts)) {
            return $return;
        }
        foreach ($contacts as $info) {
            $contactInfo = [];
            // 联系人名称
            $contactInfo['contact'] = isset($info['contact']) && !empty($info['contact']) ? $info['contact'] : '未知联系人';
            // 联系方式
            $contactInfo['mobile'] = isset($info['content']) && fieldRegex($info['content'], 'mobile') ? $info['content'] : '';
            $contactInfo['telephone'] = isset($info['type']) && $info['type'] == 2 && isset($info['content']) ? $info['content'] : '';
            $contactInfo['is_main'] = 0;

            // 同时存在联系人及手机号的设为主要联系人
            if (empty($return['main_contact']) && !empty($contactInfo['contact']) && $contactInfo['mobile']) {
                $return['main_contact'] = [
                    'contact' => $contactInfo['contact'],
                    'mobile' => $contactInfo['mobile'],
                ];
                $contactInfo['is_main'] = 1;
            }
            $return['contact_list'][] = $contactInfo;
        }
        // 主要联系人中没有的情况下则取第一个联系方式
        if (empty($return['main_contact'])) {
            $return['main_contact'] = [
                'contact' => $return['contact_list'][0]['contact'],
                'mobile' => $return['contact_list'][0]['mobile'],
            ];
            if (!empty($return['contact_list'][0]['mobile'])) {
                $return['contact_list'][0]['is_main'] = 1;
            }
        }
        return $return;
    }

    /**
     * 获取账户余额
     * @access public
     * @return array
     * Date：2022-11-23
     * @author edc
     */
    public function getAccountBalance()
    {
        // 调取搜客宝接口获取
        $soukebao = new Soukebao();
        $result = $soukebao->getAccountBalance();
        if (false === $result['status']) {
            responseJson(400, $result['msg']);
        }
        $return = $result['data'];
        if (isset($return['list']) && !empty($return['list'])) {
            $list = [];
            foreach ($return['list'] as $info) {
                $list[] = $info['num'] . '次查询余额将在' . date('Y年m月d日', $info['deadline']) . '到期';
            }
            $return['list'] = $list;
        } else {
            $return['list'] = ['暂无有效服务，请联系专属客服开通。'];
        }
        return $return;
    }

    /**
     * 生成可见的查询条件
     * @access public
     * @param array $params [请求参数]
     * @return array
     * Date：2022-11-28
     * @author edc
     */
    private function _generateVisibleQueryCondition($params)
    {
        $visibleQuery = [];
        // 维度：entName|企业名称, opScope|经营范围, appName|产品
        $visibleQuery[] = [
            'title' => '维度',
            'name' => $this->_dimension[$params['type']]
        ];
        // 地区
        $address = '不限';
        if (isset($params['province_name']) && !empty($params['province_name'])) {
            $address = $params['province_name'];
            if (isset($params['city_name']) && !empty($params['city_name'])) {
                $address .= ' / ' . $params['city_name'];
                if (isset($params['district_name']) && !empty($params['district_name'])) {
                    $address .= ' / ' . $params['district_name'];
                }
            }
        }
        $visibleQuery[] = [
            'title' => '地区',
            'name' => $address
        ];
        // 营业状态：1|在营/存续，2|吊销/撤销，3|注销，4|迁入/迁出，8|停业，9|其他
        $entStatusStr = '不限';
        if (isset($params['ent_status']) && !empty($params['ent_status'])) {
            $entStatusArr = explode(',', trim($params['ent_status'], ','));
            $entStatusStr = '';
            foreach ($entStatusArr as $entStatus) {
                $entStatusStr .= !empty($entStatusStr) ? '，' . $this->_entStatusArr[$entStatus] : $this->_entStatusArr[$entStatus];
            }
        }
        $visibleQuery[] = [
            'title' => '营业状态',
            'name' => $entStatusStr
        ];
        // 联系方式：1|有手机，2|有固话，3|有邮箱，4|有QQ，5|无联系方式
        $contactStr = '不限';
        if (isset($params['contact']) && !empty($params['contact'])) {
            $contactArr = explode(',', trim($params['contact'], ','));
            $contactStr = '';
            foreach ($contactArr as $contact) {
                $contactStr .= !empty($contactStr) ? '，' . $this->_contactArr[$contact] : $this->_contactArr[$contact];
            }
        }
        $visibleQuery[] = [
            'title' => '联系方式',
            'name' => $contactStr
        ];
        // 成立年限
        $esDateStr = '不限';
        if ((isset($params['starttime']) && $params['starttime'] && isset($params['endtime']) && $params['endtime'])) {
            $esDateStr = $params['starttime'] . ' ~ ' . $params['endtime'];
        }
        $visibleQuery[] = [
            'title' => '成立年限',
            'name' => $esDateStr
        ];
        return $visibleQuery;
    }

}