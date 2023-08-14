<?php
/**
 * 【企业】
 * 完善企业资料接口API
 */

namespace app\v1_0\controller\app;

use app\v1_0\controller\common\Base;
use think\Db;

class Company extends Base
{
    /**
     * 企业基本资料
     * @var array[]
     */
    private $basic_perfect = [
        // 企业LOGO
        'logo' => [
            'modifier' => '/d',
            'default' => 0,
            'filter' => 'intval',
            'model' => 'Company'
        ],
        // 企业简称
        'short_name' => [
            'modifier' => '/s',
            'default' => '',
            'filter' => 'trim,badword_filter',
            'model' => 'Company'
        ],
        // 企业性质
        'nature' => [
            'modifier' => '/d',
            'default' => 0,
            'filter' => 'intval',
            'model' => 'Company'
        ],
        // 所属行业
        'trade' => [
            'modifier' => '/d',
            'default' => 0,
            'filter' => 'intval',
            'model' => 'Company'
        ],
        // 企业规模
        'scale' => [
            'modifier' => '/d',
            'default' => 0,
            'filter' => 'intval',
            'model' => 'Company'
        ],
        // 企业规模
        'tag' => [
            'modifier' => '/a',
            'default' => [],
            'filter' => '',
            'model' => 'Company'
        ],
    ];

    /**
     * 企业信息
     * @var array
     */
    private $info_perfect = [
        // 企业介绍
        'website' => [
            'modifier' => '/s',
            'default' => '',
            'filter' => 'trim,badword_filter',
            'model' => 'CompanyInfo'
        ]
    ];

    /**
     * `company_info`表字段
     * @var array
     */
    private $company_info_field = [
        'website' => '',
        'short_desc' => '',
        'content' => '',
        'address' => ''
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
    }


    /**
     * 修改企业基本资料
     * @return void
     */
    public function perfect()
    {
        $type = input('post.type/s', '', 'trim');
        $field = input('post.field/s', '', 'trim');

        if (empty($field)) {
            $this->ajaxReturn(500, '请选择要完善的企业资料项');
        }

        switch ($type) {
            case 'basic':
                $this->_basicPerfect($field);
                break;

            case 'info':
                $this->_infoPerfect($field);
                break;

            default:
                $this->ajaxReturn(500, '错误的企业资料项');
        }

    }


    /**
     * 修改`company_info`表
     * @param $field
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _basicPerfect($field)
    {
        if (!array_key_exists($field, $this->basic_perfect)) {
            $this->ajaxReturn(500, '错误的企业资料项');
        }

        $company_profile = model('Company')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();
        if (null === $company_profile) {
            $this->ajaxReturn(500, '企业信息不存在');
        }

        $field_config = $this->basic_perfect[$field];
        $info_data = [
            $field => input("post.value{$field_config['modifier']}", $field_config['default'], $field_config['filter'])
        ];

        Db::startTrans();
        try {
            //修改企业资料，根据配置赋值审核状态
            if (1 == config('global_config.audit_edit_com')) {
                $info_data['audit'] = 0;
            }

            /**
             * 企业福利【tag】需要特殊处理
             */
            if ('tag' === $field) {
                $info_data['tag'] = !empty($info_data['tag'])
                    ? implode(',', $info_data['tag'])
                    : '';
            }

            $company_result = model('Company')
                ->validate('Company.' . $field)
                ->allowField(true)
                ->isUpdate(true)
                ->save($info_data,
                    ['uid' => $this->userinfo->uid,]);

            if (false === $company_result) {
                throw new \Exception(model('Company')->getError());
            }

            if ($company_profile['audit'] == 1 && config('global_config.audit_edit_com') == 1) {
                model('AdminNotice')->send(
                    4,
                    '企业认证审核',
                    '企业UID:【' . $company_profile['id'] . '】' . "\r\n" .
                    '企业名称:【' . $company_profile['companyname'] . '】' . "\r\n" .
                    '联系电话:【' . $this->userinfo->mobile . '】',
                    '企业认证审核，请及时跟进（后台->用户->企业信息管理->待审核企业）'
                );
            }

            $this->writeMemberActionLog($this->userinfo->uid, '修改企业基本资料【注册资金】');

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        $this->ajaxReturn(200, '保存成功');
    }


    /**
     * 修改`company_info`表
     * @param $field
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _infoPerfect($field)
    {
        if (!array_key_exists($field, $this->info_perfect)) {
            $this->ajaxReturn(500, '错误的企业资料项');
        }

        $company_profile = model('Company')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();
        if (null === $company_profile) {
            $this->ajaxReturn(500, '企业信息不存在');
        }

        $company_info = model('CompanyInfo')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();

        $field_config = $this->info_perfect[$field];
        $info_data = [
            $field => input("post.value{$field_config['modifier']}", $field_config['default'], $field_config['filter'])
        ];

        Db::startTrans();
        try {
            //修改企业资料，根据配置赋值审核状态
            if (1 == config('global_config.audit_edit_com')) {
                // 修改`company`表
                $company_result = model('Company')
                    ->validate('Company.audit')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save(
                        ['audit' => 0],
                        ['uid' => $this->userinfo->uid]);

                if (false === $company_result) {
                    throw new \Exception(model('Company')->getError());
                }
            }

            // 修改`company_info`表
            if (null === $company_info) {
                $info_data['uid'] = $this->userinfo->uid;
                $info_data['comid'] = $company_profile['id'];

                unset($this->company_info_field[$field]);
                $info_data += $this->company_info_field;

                $info_result = model('CompanyInfo')
                    ->validate(true)
                    ->allowField(true)
                    ->isUpdate(false)
                    ->save($info_data);
            } else {
                $info_result = model('CompanyInfo')
                    ->validate('CompanyInfo.' . $field)
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save($info_data,
                        ['uid' => $this->userinfo->uid,]);
            }

            if (false === $info_result) {
                throw new \Exception(model('CompanyInfo')->getError());
            }


            if ($company_profile['audit'] == 1 && config('global_config.audit_edit_com') == 1) {
                model('AdminNotice')->send(
                    4,
                    '企业认证审核',
                    '企业UID:【' . $company_profile['id'] . '】' . "\r\n" .
                    '企业名称:【' . $company_profile['companyname'] . '】' . "\r\n" .
                    '联系电话:【' . $this->userinfo->mobile . '】',
                    '企业认证审核，请及时跟进（后台->用户->企业信息管理->待审核企业）'
                );
            }

            $this->writeMemberActionLog($this->userinfo->uid, '修改企业基本资料【注册资金】');

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        $this->ajaxReturn(200, '保存成功');
    }


    /**
     * 修改联系人
     * @return void
     */
    public function contact()
    {
        if (!request()->isPost()) {
            $this->ajaxReturn(500, '非法请求');
        }
        /**
         * 1.联系方式参数值
         */
        $input_data = [
            'contact' => input('post.contact/s', '', 'trim,badword_filter'),
            'mobile' => input('post.mobile/s', '', 'trim,badword_filter'),
        ];

        if (input('?post.weixin')) {
            $input_data['weixin'] = input(
                'post.weixin/s',
                '',
                'trim,badword_filter');
        }

        if (input('?post.telephone')) {
            $input_data['telephone'] = input(
                'post.telephone/s',
                '',
                'trim,badword_filter'
            );
        }

        if (input('?post.qq')) {
            $input_data['qq'] = input(
                'post.qq/s',
                '',
                'trim,badword_filter'
            );
        }

        if (input('?post.email')) {
            $input_data['email'] = input(
                'post.email/s',
                '',
                'trim,badword_filter'
            );
        }

        $company_profile = model('Company')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();
        if (null === $company_profile) {
            $this->ajaxReturn(500, '企业信息不存在');
        }

        $company_contact = model('CompanyContact')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();

        Db::startTrans();
        try {
            //修改企业资料，根据配置赋值审核状态
            if (1 == config('global_config.audit_edit_com')) {
                // 修改`company`表
                $company_result = model('Company')
                    ->validate('Company.audit')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save(
                        ['audit' => 0],
                        ['uid' => $this->userinfo->uid]);

                if (false === $company_result) {
                    throw new \Exception(model('Company')->getError());
                }
            }

            if (null === $company_contact) {
                $input_data['uid'] = $this->userinfo->uid;
                $input_data['comid'] = $company_profile['id'];
                $result = model('CompanyContact')
                    ->validate(true)
                    ->allowField(true)
                    ->isUpdate(false)
                    ->save($input_data);
            } else {
                $result = model('CompanyContact')
                    ->validate(true)
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save($input_data,
                        ['uid' => $this->userinfo->uid,]);
            }

            if (false === $result) {
                throw new \Exception(model('CompanyContact')->getError());
            }

            if ($company_profile['audit'] == 1 && config('global_config.audit_edit_com') == 1) {
                model('AdminNotice')->send(
                    4,
                    '企业认证审核',
                    '企业UID:【' . $company_profile['id'] . '】' . "\r\n" .
                    '企业名称:【' . $company_profile['companyname'] . '】' . "\r\n" .
                    '联系电话:【' . $this->userinfo->mobile . '】',
                    '企业认证审核，请及时跟进（后台->用户->企业信息管理->待审核企业）'
                );
            }

            $this->writeMemberActionLog($this->userinfo->uid, '修改企业基本资料【企业联系人】');

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        $this->ajaxReturn(200, '保存成功');

    }


    /**
     * 所在地区
     * @return void
     */
    public function location()
    {
        $company_profile = model('Company')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();
        if (null === $company_profile) {
            $this->ajaxReturn(500, '企业信息不存在');
        }

        $company_info = model('CompanyInfo')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();

        Db::startTrans();
        try {
            // 修改`company`表
            $company_data = [
                'district1' => input('post.district1/d', 0, 'intval'),
                'district2' => input('post.district2/d', 0, 'intval'),
                'district3' => input('post.district3/d', 0, 'intval'),
                'address' => input('post.address/s', '', 'trim,badword_filter'),
                'map_lat' => input('post.map_lat/f', 0, 'floatval'),
                'map_lng' => input('post.map_lng/f', 0, 'floatval'),
                'map_zoom' => input('post.map_zoom/d', 0, 'intval')
            ];

            //修改企业资料，根据配置赋值审核状态
            if (1 == config('global_config.audit_edit_com')) {
                $company_data['audit'] = 0;
            }

            $company_data['district'] =
                $company_data['district3'] > 0
                    ? $company_data['district3']
                    : ($company_data['district2'] > 0
                    ? $company_data['district2']
                    : $company_data['district1']);

            $company_result = model('Company')
                ->validate('Company.location')
                ->allowField(true)
                ->isUpdate(true)
                ->save($company_data,
                    ['uid' => $this->userinfo->uid,]);

            if (false === $company_result) {
                throw new \Exception(model('Company')->getError());
            }

            // 修改`company_info`表
            if (null === $company_info) {
                $info_data = [
                    'uid' => $this->userinfo->uid,
                    'comid' => $company_profile['id'],
                    'website' => '',
                    'short_desc' => '',
                    'content' => '',
                    'address' => input('post.address/s', '', 'trim,badword_filter')
                ];
                $info_result = model('CompanyInfo')
                    ->validate(true)
                    ->allowField(true)
                    ->isUpdate(false)
                    ->save($info_data);
            } else {
                $info_data = [
                    'address' => input('post.address/s', '', 'trim,badword_filter')
                ];
                $info_result = model('CompanyInfo')
                    ->validate('CompanyInfo.address')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save($info_data,
                        ['uid' => $this->userinfo->uid,]);
            }

            if (false === $info_result) {
                throw new \Exception(model('CompanyInfo')->getError());
            }

            if ($company_profile['audit'] == 1 && config('global_config.audit_edit_com') == 1) {
                model('AdminNotice')->send(
                    4,
                    '企业认证审核',
                    '企业UID:【' . $company_profile['id'] . '】' . "\r\n" .
                    '企业名称:【' . $company_profile['companyname'] . '】' . "\r\n" .
                    '联系电话:【' . $this->userinfo->mobile . '】',
                    '企业认证审核，请及时跟进（后台->用户->企业信息管理->待审核企业）'
                );
            }

            $this->writeMemberActionLog($this->userinfo->uid, '修改企业基本资料【所属地区】');

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        $this->ajaxReturn(200, '保存成功');
    }


    /**
     * 注册资金
     * @return void
     */
    public function registeredCapital()
    {
        $company_data = [
            'registered' => input('post.registered/d', 0, 'intval'),
            'currency' => input('post.currency/d', 0, 'intval') // 【0:万人民币;1:万美元;】
        ];

        $company_profile = model('Company')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();
        if (null === $company_profile) {
            $this->ajaxReturn(500, '企业信息不存在');
        }

        Db::startTrans();
        try {
            //修改企业资料，根据配置赋值审核状态
            if (1 == config('global_config.audit_edit_com')) {
                $company_data['audit'] = 0;
            }

            // 修改`company`表
            $company_result = model('Company')
                ->validate('Company.registered')
                ->allowField(true)
                ->isUpdate(true)
                ->save($company_data,
                    ['uid' => $this->userinfo->uid,]);

            if (false === $company_result) {
                throw new \Exception(model('Company')->getError());
            }

            if ($company_profile['audit'] == 1 && config('global_config.audit_edit_com') == 1) {
                model('AdminNotice')->send(
                    4,
                    '企业认证审核',
                    '企业UID:【' . $company_profile['id'] . '】' . "\r\n" .
                    '企业名称:【' . $company_profile['companyname'] . '】' . "\r\n" .
                    '联系电话:【' . $this->userinfo->mobile . '】',
                    '企业认证审核，请及时跟进（后台->用户->企业信息管理->待审核企业）'
                );
            }

            $this->writeMemberActionLog($this->userinfo->uid, '修改企业基本资料【注册资金】');

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        $this->ajaxReturn(200, '保存成功');
    }


    /**
     * 企业介绍
     * @return void
     */
    public function introduction()
    {
        $info_data = [
            'short_desc' => input('post.short_desc/s', '', 'trim,badword_filter'), // 企业简介
            'content' => input('post.content/s', '', 'trim,badword_filter') // 企业介绍
        ];

        $company_profile = model('Company')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();
        if (null === $company_profile) {
            $this->ajaxReturn(500, '企业信息不存在');
        }

        $company_info = model('CompanyInfo')
            ->where('uid', 'eq', $this->userinfo->uid)
            ->find();

        Db::startTrans();
        try {
            //修改企业资料，根据配置赋值审核状态
            if (1 == config('global_config.audit_edit_com')) {
                // 修改`company`表
                $company_result = model('Company')
                    ->validate('Company.audit')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save(
                        ['audit' => 0],
                        ['uid' => $this->userinfo->uid]);

                if (false === $company_result) {
                    throw new \Exception(model('Company')->getError());
                }
            }

            // 修改`company_info`表
            if (null === $company_info) {
                $info_data['uid'] = $this->userinfo->uid;
                $info_data['comid'] = $company_profile['id'];
                $info_data += $this->company_info_field;

                $info_result = model('CompanyInfo')
                    ->validate(true)
                    ->allowField(true)
                    ->isUpdate(false)
                    ->save($info_data);
            } else {
                $info_result = model('CompanyInfo')
                    ->validate('CompanyInfo.introduction')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save($info_data,
                        ['uid' => $this->userinfo->uid,]);
            }

            if (false === $info_result) {
                throw new \Exception(model('CompanyInfo')->getError());
            }


            if ($company_profile['audit'] == 1 && config('global_config.audit_edit_com') == 1) {
                model('AdminNotice')->send(
                    4,
                    '企业认证审核',
                    '企业UID:【' . $company_profile['id'] . '】' . "\r\n" .
                    '企业名称:【' . $company_profile['companyname'] . '】' . "\r\n" .
                    '联系电话:【' . $this->userinfo->mobile . '】',
                    '企业认证审核，请及时跟进（后台->用户->企业信息管理->待审核企业）'
                );
            }

            $this->writeMemberActionLog($this->userinfo->uid, '修改企业基本资料【注册资金】');

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        $this->ajaxReturn(200, '保存成功');
    }

}