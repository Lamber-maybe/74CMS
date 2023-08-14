<?php

namespace app\v1_0\controller\company;

class Profile extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
    }

    /**
     * 获取企业联系方式
     */
    public function getDetailContact($company_id)
    {
        $company_id = intval($company_id);
        if ($company_id > 0) {
            $where['comid'] = $company_id;
        }
        $return = model('CompanyContact')
            ->field('id,comid,uid', true)
            ->where($where)
            ->find();
        $return['contact'] = isset($return['contact']) ? htmlspecialchars_decode($return['contact'], ENT_QUOTES) : '';
        $return['weixin'] = isset($return['weixin']) ? htmlspecialchars_decode($return['weixin'], ENT_QUOTES) : '';
        $return['telephone'] = isset($return['telephone']) ? htmlspecialchars_decode($return['telephone'], ENT_QUOTES) : '';
        return $return === null ? [] : $return;
    }

    /**
     * 获取企业详细信息
     */
    public function getDetailInfo($company_id)
    {
        $company_id = intval($company_id);
        if ($company_id > 0) {
            $where['comid'] = $company_id;
        }
        $return = model('CompanyInfo')
            ->field('id,comid,uid', true)
            ->where($where)
            ->find();
        $return['address'] = isset($return['address']) ? htmlspecialchars_decode($return['address'], ENT_QUOTES) : '';
        $return['short_desc'] = isset($return['short_desc']) ? htmlspecialchars_decode($return['short_desc'], ENT_QUOTES) : '';
        $return['content'] = isset($return['content']) ? htmlspecialchars_decode($return['content'], ENT_QUOTES) : '';
        return $return === null ? [] : $return;
    }

    /**
     * 获取企业认证信息
     */
    public function getDetailAuth($company_id)
    {
        $company_id = intval($company_id);
        if ($company_id > 0) {
            $where['comid'] = $company_id;
        }
        $return = model('CompanyAuth')
            ->field('id,comid,uid', true)
            ->where($where)
            ->find();
        return $return === null ? [] : $return;
    }

    /**
     * 获取企业风采
     */
    public function getCompanyImg($company_id)
    {
        $company_id = intval($company_id);
        if ($company_id > 0) {
            $where['comid'] = $company_id;
        }
        $img_list = model('CompanyImg')
            ->field('comid,uid', true)
            ->where($where)
            ->select();
        $fileid_arr = $file_arr = [];
        foreach ($img_list as $key => $value) {
            if ($value['img'] > 0) {
                $fileid_arr[] = $value['img'];
            }
        }
        if (!empty($fileid_arr)) {
            $file_arr = model('Uploadfile')->getFileUrlBatch($fileid_arr);
        }
        foreach ($img_list as $key => $value) {
            $value['audit_text'] = isset(
                model('CompanyImg')->map_audit[$value['audit']]
            )
                ? model('CompanyImg')->map_audit[$value['audit']]
                : '待审核';
            $value['img_src'] = isset($file_arr[$value['img']])
                ? $file_arr[$value['img']]
                : '';
            $img_list[$key] = $value;
        }
        return $img_list;
    }

    /**
     * 获取企业资料详情
     */
    public function getDetail($uid)
    {
        $uid = intval($uid);
        $where['uid'] = $uid;
        $basic = model('Company')
            ->where($where)
            ->field('uid', true) //排除字段
            ->find();
        if ($basic === null) {
            return [
                'basic' => [],
                'contact' => [],
                'info' => [],
                'img_list' => [],
                /**
                 * APP跳转完善企业信息页
                 * 如果未完善企业信息，跳转至长页面完善
                 */
                'profile_code' => ['err_code' => 50003]
            ];
        }
        $basic['short_name'] = htmlspecialchars_decode($basic['short_name'], ENT_QUOTES);

        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $basic['nature_text'] = isset(
            $category_data['QS_company_type'][$basic['nature']]
        )
            ? $category_data['QS_company_type'][$basic['nature']]
            : '';
        $basic['trade_text'] = isset(
            $category_data['QS_trade'][$basic['trade']]
        )
            ? $category_data['QS_trade'][$basic['trade']]
            : '';
        $basic['district_text'] = isset(
            $category_district_data[$basic['district']]
        )
            ? $category_district_data[$basic['district']]
            : '';
        $basic['district_text_full'] = '';
        if ($basic['district1']) {
            $basic['district_text_full'] = isset(
                $category_district_data[$basic['district1']]
            )
                ? $category_district_data[$basic['district1']]
                : '';
        } else {
            $basic['district_text_full'] = '';
        }

        if ($basic['district_text_full'] != '' && $basic['district2'] > 0) {
            $basic['district_text_full'] .= isset(
                $category_district_data[$basic['district2']]
            )
                ? $category_district_data[$basic['district2']]
                : '';
        }
        if ($basic['district_text_full'] != '' && $basic['district3'] > 0) {
            $basic['district_text_full'] .= isset(
                $category_district_data[$basic['district3']]
            )
                ? $category_district_data[$basic['district3']]
                : '';
        }
        $basic['scale_text'] = isset(
            $category_data['QS_scale'][$basic['scale']]
        )
            ? $category_data['QS_scale'][$basic['scale']]
            : '';
        $basic['tag'] = $basic['tag'] == '' ? [] : explode(',', $basic['tag']);
        $basic['tag_text'] = '';
        $basic['tag_text_arr'] = [];
        if (!empty($basic['tag'])) {
            $tag_text_arr = [];
            foreach ($basic['tag'] as $k => $v) {
                if (is_numeric($v) && isset($category_data['QS_jobtag'][$v])) {
                    $tag_text_arr[] = $category_data['QS_jobtag'][$v];
                } else {
                    $tag_text_arr[] = $v;
                }
            }
            if (!empty($tag_text_arr)) {
                $basic['tag_text_arr'] = $tag_text_arr;
                $basic['tag_text'] = implode(',', $tag_text_arr);
            }
        }

        $basic['logo_src'] =
            $basic['logo'] > 0
                ? model('Uploadfile')->getFileUrl($basic['logo'])
                : default_empty('logo');
        //联系方式
        $contact = $this->getDetailContact($basic['id']);
        //详细信息
        $info = $this->getDetailInfo($basic['id']);
        //企业风采
        $img_list = model('CompanyImg')
            ->field('comid,uid', true)
            ->where(['comid' => ['eq', $basic['id']]])
            ->select();
        $fileid_arr = $file_arr = [];
        foreach ($img_list as $key => $value) {
            if ($value['img'] > 0) {
                $fileid_arr[] = $value['img'];
            }
        }
        if (!empty($fileid_arr)) {
            $file_arr = model('Uploadfile')->getFileUrlBatch($fileid_arr);
        }
        foreach ($img_list as $key => $value) {
            $value['audit_text'] = isset(
                model('CompanyImg')->map_audit[$value['audit']]
            )
                ? model('CompanyImg')->map_audit[$value['audit']]
                : '待审核';
            $value['img_src'] = isset($file_arr[$value['img']])
                ? $file_arr[$value['img']]
                : '';
            $img_list[$key] = $value;
        }

        return [
            'basic' => $basic,
            'contact' => $contact,
            'info' => $info,
            'img_list' => $img_list,
            'profile_code' => ['err_code' => 0]
        ];
    }

    /**
     * 保存基本信息
     */
    public function index()
    {
        if (request()->isGet()) {
            $field_rule_data = model('FieldRule')->getCache();
            $field_rule = [
                'basic' => $field_rule_data['Company'],
                'contact' => $field_rule_data['CompanyContact'],
                'info' => $field_rule_data['CompanyInfo'],
            ];
            foreach ($field_rule as $key => $rule) {
                foreach ($rule as $field => $field_attr) {
                    $_arr = [
                        'field_name' => $field_attr['field_name'],
                        'is_require' => intval($field_attr['is_require']),
                        'is_display' => intval($field_attr['is_display']),
                        'field_cn' => $field_attr['field_cn'],
                    ];
                    $field_rule[$key][$field] = $_arr;
                }
            }
            $company_profile = $this->getDetail($this->userinfo->uid);
            if ($company_profile === false) {
                $this->ajaxReturn(500, '没有找到企业信息');
            }
            $company_profile['field_rule'] = $field_rule;

            $coordinate = [
                'lng' => 0,
                'lat' => 0,
            ];
            if (!empty($company_profile['basic']['map_lng']) && !empty($company_profile['basic']['map_lat'])) {
                $coordinate = model('Config')->bd09ToWgs84(
                    $company_profile['basic']['map_lng'],
                    $company_profile['basic']['map_lat']
                );
            }
            $company_profile['basic']['map_lng'] = $coordinate['lng'];
            $company_profile['basic']['map_lat'] = $coordinate['lat'];

            $this->ajaxReturn(200, '获取数据成功', $company_profile);
        } else {
            $input_data = [
                'basic' => [
                    'uid' => $this->userinfo->uid,
                    'logo' => input('post.basic.logo/d', 0, 'intval'),
                    'companyname' => input(
                        'post.basic.companyname/s',
                        '',
                        'trim,badword_filter'
                    ),
                    'nature' => input('post.basic.nature/d', 0, 'intval'),
                    'trade' => input('post.basic.trade/d', 0, 'intval'),
                    'scale' => input('post.basic.scale/d', 0, 'intval'),
                    'district1' => input('post.basic.district1/d', 0, 'intval'),
                    'district2' => input('post.basic.district2/d', 0, 'intval'),
                    'district3' => input('post.basic.district3/d', 0, 'intval'),
                    'citycategory_arr' => input('post.basic.citycategory_arr/a'),
                    'map_lat' => input('post.basic.map_lat/f', 0, 'floatval'),
                    'map_lng' => input('post.basic.map_lng/f', 0, 'floatval'),
                    'map_zoom' => input('post.basic.map_zoom/d', 0, 'intval'),
                ],
                'info' => [
                    'uid' => $this->userinfo->uid,
                    'address' => input('post.info.address/s', '', 'trim,badword_filter'),
                ],
                'contact' => [
                    'uid' => $this->userinfo->uid,
                    'contact' => input('post.contact.contact/s', '', 'trim,badword_filter'),
                    'mobile' => input('post.contact.mobile/s', '', 'trim,badword_filter'),
                    'is_secrecy' => input('post.contact.is_secrecy/d', 1, 'intval')
                ]
            ];
            $coordinate = model('Config')->bd09ToWgs84($input_data['basic']['map_lng'],$input_data['basic']['map_lat']);
            $input_data['basic']['map_lng'] = $coordinate['lng'];
            $input_data['basic']['map_lat'] = $coordinate['lat'];

            if (!empty($input_data['basic']['citycategory_arr'])) {
                $input_data['basic']['district1'] = isset($input_data['basic']['citycategory_arr'][0]) ? $input_data['basic']['citycategory_arr'][0] : 0;
                $input_data['basic']['district2'] = isset($input_data['basic']['citycategory_arr'][1]) ? $input_data['basic']['citycategory_arr'][1] : 0;
                $input_data['basic']['district3'] = isset($input_data['basic']['citycategory_arr'][2]) ? $input_data['basic']['citycategory_arr'][2] : 0;
            }

            $company_profile = model('Company')
                ->where('uid', 'eq', $this->userinfo->uid)
                ->find();
            $company_contact = model('CompanyContact')
                ->where('uid', 'eq', $this->userinfo->uid)
                ->find();
            $company_info = model('CompanyInfo')
                ->where('uid', 'eq', $this->userinfo->uid)
                ->find();
            if (input('?post.basic.logo')) {
                $input_data['basic']['logo'] = input(
                    'post.basic.logo/d',
                    0,
                    'intval'
                );
            } elseif ($company_profile === null) {
                $input_data['basic']['logo'] = 0;
            }
            if (input('?post.basic.short_name')) {
                $input_data['basic']['short_name'] = input(
                    'post.basic.short_name/s',
                    '',
                    'trim,badword_filter'
                );
            } elseif ($company_profile === null) {
                $input_data['basic']['short_name'] = '';
            }
            if (input('?post.basic.registered')) {
                $input_data['basic']['registered'] = input(
                    'post.basic.registered/d',
                    0,
                    'intval'
                );
                $input_data['basic']['currency'] = input(
                    'post.basic.currency/d',
                    0,
                    'intval'
                );
            } elseif ($company_profile === null) {
                $input_data['basic']['registered'] = 0;
                $input_data['basic']['currency'] = 0;
            }
            if (input('?post.basic.tag')) {
                $input_data['basic']['tag'] = input('post.basic.tag/a', []);
            } elseif ($company_profile === null) {
                $input_data['basic']['tag'] = [];
            }
            $input_data['basic']['tag'] = !empty($input_data['basic']['tag'])
                ? implode(',', $input_data['basic']['tag'])
                : '';
            $input_data['basic']['district'] =
                $input_data['basic']['district3'] > 0
                    ? $input_data['basic']['district3']
                    : ($input_data['basic']['district2'] > 0
                    ? $input_data['basic']['district2']
                    : $input_data['basic']['district1']);

            if (input('?post.info.website')) {
                $input_data['info']['website'] = input(
                    'post.info.website/s',
                    '',
                    'trim,badword_filter'
                );

                /**
                 * 【bug】企业资料网址设置非必填，前端一直给验证
                 * 【ID1000388】
                 * yx - 2022.10.27
                 */
                if (!empty($input_data['info']['website'])
                    &&
                    !fieldRegex($input_data['info']['website'], 'url')) {
                    $this->ajaxReturn(500, '请填写正确的网址');
                }
            } elseif ($company_info === null) {
                $input_data['info']['website'] = '';
            }
            if (input('?post.info.short_desc')) {
                $input_data['info']['short_desc'] = input(
                    'post.info.short_desc/s',
                    '',
                    'trim,badword_filter'
                );
            } elseif ($company_info === null) {
                $input_data['info']['short_desc'] = '';
            }
            if (input('?post.info.content')) {
                $input_data['info']['content'] = input(
                    'post.info.content/s',
                    '',
                    'trim,badword_filter'
                );
            } elseif ($company_info === null) {
                $input_data['info']['content'] = '';
            }

            if (input('?post.contact.weixin')) {
                $input_data['contact']['weixin'] = input(
                    'post.contact.weixin/s',
                    '',
                    'trim,badword_filter'
                );
            } elseif ($company_contact === null) {
                $input_data['contact']['weixin'] = '';
            }
            if (input('?post.contact.telephone')) {
                $input_data['contact']['telephone'] = input(
                    'post.contact.telephone/s',
                    '',
                    'trim,badword_filter'
                );
            } elseif ($company_contact === null) {
                $input_data['contact']['telephone'] = '';
            }
            if (input('?post.contact.qq')) {
                $input_data['contact']['qq'] = input(
                    'post.contact.qq/s',
                    '',
                    'trim,badword_filter'
                );
            } elseif ($company_contact === null) {
                $input_data['contact']['qq'] = '';
            }
            if (input('?post.contact.email')) {
                $input_data['contact']['email'] = input(
                    'post.contact.email/s',
                    '',
                    'trim,badword_filter'
                );
            } elseif ($company_contact === null) {
                $input_data['contact']['email'] = '';
            }

            \think\Db::startTrans();
            try {
                if ($company_profile === null) {
                    $input_data['basic']['registered'] = isset($input_data['basic']['registered']) ? $input_data['basic']['registered'] : '';
                    $input_data['basic']['currency'] = isset($input_data['basic']['currency']) ? $input_data['basic']['currency'] : 0;
                    //新添加的企业，根据配置赋值审核状态
                    $input_data['basic']['audit'] = config(
                        'global_config.audit_new_com'
                    );
                    //新添加的企业，根据配置赋值显示状态
                    $input_data['basic']['is_display'] = config(
                        'global_config.display_new_com'
                    );
                    $input_data['basic']['addtime'] = time();
                    $input_data['basic']['refreshtime'] =
                        $input_data['basic']['addtime'];
                    $input_data['basic']['click'] = 0;
                    $input_data['basic']['robot'] = 0;
                    $input_data['basic']['platform'] = config('platform');
                    $input_data['basic']['cs_id'] = model('Member')->distributionCustomerService();
                    $member_setmeal = model('MemberSetmeal')->where('uid', $this->userinfo->uid)->find();
                    $input_data['basic']['setmeal_id'] = $member_setmeal === null ? 0 : $member_setmeal->setmeal_id;

                    /**
                     * 【ID1000415】【bug】CRM不自动分配客服
                     * yx - 2022.11.10
                     * [新增]
                     * 自动分配客服逻辑
                     */
                    // 1.判断CRM设置是否开启客户分配销售规则
                    $is_rule = model('b2bcrm.CrmSysConfig')->getConfigByKey('customer_allocation_rule');
                    if (1 === intval($is_rule)) {
                        // 2.查询当前轮训的客服admin_id
                        $crm_auto_assign = model('b2bcrm.CrmAutoAssign')->getAutoAssignAdminId();
                        if (!empty($crm_auto_assign)) {
                            $input_data['basic']['admin_id'] = $crm_auto_assign; // 添加客服ID
                            $input_data['basic']['collection_time'] = time(); // 添加领取时间
                            // 3.分配成功，切换轮训客服
                            model('b2bcrm.CrmAutoAssign')
                                ->where('admin_id', $crm_auto_assign)
                                ->setInc('assign_num');

                            # 设置管理员销售客户总数上限
                            $exceed_result = model('Admin')->setCustomerExceed($crm_auto_assign);
                            if (false === $exceed_result) {
                                throw new \Exception(model('Admin')->getError());
                            }
                        }
                    }

                    $result = model('Company')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['basic']);
                    $company_id = model('Company')->id;

                    if (isset($input_data['basic']['admin_id']) && !empty($input_data['basic']['admin_id'])) {
                        # 设置管理员销售客户总数上限
                        $exceed_result = model('Admin')->setCustomerExceed($crm_auto_assign);
                        if (false === $exceed_result) {
                            throw new \Exception(model('Admin')->getError());
                        }
                    }

                } else {
                    if ($company_profile['district'] == 0) {
                        //新添加的企业，根据配置赋值审核状态
                        $input_data['basic']['audit'] = config(
                            'global_config.audit_new_com'
                        );
                        //新添加的企业，根据配置赋值显示状态
                        $input_data['basic']['is_display'] = config(
                            'global_config.display_new_com'
                        );
                    } else {
                        //修改企业资料，根据配置赋值审核状态
                        if (config('global_config.audit_edit_com') == 1) {
                            $input_data['basic']['audit'] = 0;
                        }
                    }
                    if ($company_profile['companyname'] != '') {
                        $input_data['basic']['companyname'] = $company_profile['companyname'];
                    }
                    $input_data['basic']['uid'] = $company_profile['uid'];
                    if ($company_profile['cs_id'] == 0) {
                        $input_data['basic']['cs_id'] = model('Member')->distributionCustomerService();
                    }
                    $result = model('Company')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['basic'], [
                            'uid' => $this->userinfo->uid,
                        ]);
                    $company_id = $company_profile['id'];
                }

                if (false === $result) {
                    throw new \Exception(model('Company')->getError());
                }
                //完成任务
                if (
                    isset($input_data['basic']['logo']) &&
                    $input_data['basic']['logo'] > 0
                ) {
                    model('Task')->doTask(
                        $this->userinfo->uid,
                        1,
                        'upload_logo'
                    );
                }
                if ($company_contact === null) {
                    $input_data['contact']['uid'] = $this->userinfo->uid;
                    $input_data['contact']['comid'] = $company_id;
                    $result = model('CompanyContact')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['contact']);
                } else {
                    unset(
                        $input_data['contact']['uid'],
                        $input_data['contact']['comid']
                    );
                    $result = model('CompanyContact')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['contact'], [
                            'uid' => $this->userinfo->uid,
                        ]);
                }

                if (false === $result) {
                    throw new \Exception(model('CompanyContact')->getError());
                }

                if ($company_info === null) {
                    $input_data['info']['uid'] = $this->userinfo->uid;
                    $input_data['info']['comid'] = $company_id;
                    $result = model('CompanyInfo')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['info']);
                } else {
                    unset(
                        $input_data['info']['uid'],
                        $input_data['info']['comid']
                    );
                    $result = model('CompanyInfo')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['info'], [
                            'uid' => $this->userinfo->uid,
                        ]);
                }

                if (false === $result) {
                    throw new \Exception(model('CompanyInfo')->getError());
                }


                //提交事务
                \think\Db::commit();
            } catch (\Exception $e) {
                \think\Db::rollBack();
                $this->ajaxReturn(500, $e->getMessage());
            }
            $this->writeMemberActionLog($this->userinfo->uid, '修改企业基本资料');
            $this->ajaxReturn(200, '保存成功');
        }
    }

    /**
     * 上传企业风采
     */
    public function uploadImg()
    {
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $file = input('file.file');
        $extra = input('post.extra/s', '', 'trim');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $count = model('CompanyImg')
            ->where('uid', $this->userinfo->uid)
            ->count();
        if ($count >= 6) {
            $this->ajaxReturn(500, '最多上传6张风采');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->upload($file);
        if (false !== $result) {
            $img['uid'] = $this->userinfo->uid;
            $img['comid'] = $this->company_profile['id'];
            $img['img'] = $result['file_id'];
            $img['title'] = '';
            $img['addtime'] = time();
            $img['audit'] = 0;
            model('CompanyImg')->save($img);
            $result['audit'] = 0;
            $result['audit_text'] = model('CompanyImg')->map_audit[$result['audit']];
            $result['id'] = model('CompanyImg')->id;
            if ($extra == 'company_img') {
                $img_list = $this->getCompanyImg($this->userinfo->uid);
                cache('scan_upload_result_company_img_' . $this->userinfo->uid, json_encode($img_list));
            }
            $this->writeMemberActionLog($this->userinfo->uid,'上传企业风采');
            $this->ajaxReturn(200, '上传成功', $result);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }

    /**
     * 删除企业风采
     */
    public function deleteImg()
    {
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $id = input('post.id/d', 0, 'intval');
        $extra = input('post.extra/s', '', 'trim');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CompanyImg')->destroy($id);
        if ($extra == 'company_img') {
            $img_list = $this->getCompanyImg($this->userinfo->uid);
            cache('scan_upload_result_company_img_' . $this->userinfo->uid, json_encode($img_list));
        }
        $this->writeMemberActionLog($this->userinfo->uid, '删除企业风采【风采id：' . $id . '】');

        $this->ajaxReturn(200, '删除成功');
    }
}
