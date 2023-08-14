<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class CrmCustomList extends Backend
{
    protected $customlist = '[{"name":"id","field":"id","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":70,"sort":0},{"name":"线索名称","field":"name","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"所属销售","field":"admin_username","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"联系人","field":"contacts","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"联系电话","field":"mobile","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"联系微信","field":"weixin","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"客户行业","field":"trade","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"所在地区","field":"district","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"详细地址","field":"address","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"更新时间","field":"updatetime","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"跟进记录","field":"follow_count","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"创建人","field":"creat_username","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"备注","field":"remark","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0}]';

    protected $my_customlist = '[{"name":"id","field":"id","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":70,"sort":0},{"name":"线索名称","field":"name","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"联系人","field":"contacts","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"联系电话","field":"mobile","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"联系微信","field":"weixin","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"客户行业","field":"trade","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"所在地区","field":"district","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"详细地址","field":"address","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"更新时间","field":"updatetime","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"跟进记录","field":"follow_count","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"创建人","field":"creat_username","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0},{"name":"备注","field":"remark","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"sort":0}]';

    protected $companycustomlist = '[{"name":"企业id","field":"id","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":100,"is_sortable":false,"is_popover":false},{"name":"企业名称","field":"companyname","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"客户等级","field":"life_cycle_txt","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"联系人","field":"contact","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"联系方式","field":"mobile","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"在招职位","field":"jobs_num","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"收到简历","field":"job_apply_count","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"企业套餐","field":"setmeal_name","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"刷新时间","field":"refreshtime","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":"custom","is_popover":false},{"name":"注册时间","field":"addtime","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":"custom","is_popover":false},{"name":"所属销售","field":"admin_username","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"认证状态","field":"audit","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"显示状态","field":"is_display","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"最后跟进","field":"last_visit_time","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":"custom","is_popover":false},{"name":"未跟进时长","field":"not_following_day","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false}]';

    protected $my_companycustomlist = '[{"name":"企业id","field":"id","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":100,"is_sortable":false,"is_popover":false},{"name":"企业名称","field":"companyname","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"客户等级","field":"life_cycle_txt","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"联系人","field":"contact","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"联系方式","field":"mobile","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"在招职位","field":"jobs_num","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"收到简历","field":"job_apply_count","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"企业套餐","field":"setmeal_name","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"刷新时间","field":"refreshtime","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":"custom","is_popover":false},{"name":"注册时间","field":"addtime","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":"custom","is_popover":false},{"name":"认证状态","field":"audit","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"显示状态","field":"is_display","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false},{"name":"最后跟进","field":"last_visit_time","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":"custom","is_popover":false},{"name":"未跟进时长","field":"not_following_day","select":true,"icon":"el-icon-lock","is_locking":false,"is_lock_display":false,"width":200,"is_sortable":false,"is_popover":false}]';


    public function index()
    {
        $menu = input('get.menu/d',0,'intval');
        $type = input('get.type/d',0,'intval');
        try {
            if ($menu > 0 && $type > 0)
            {
                $view = model('b2bcrm.CrmCustomList')
                    ->where([
                        'menu'=>$menu,
                        'type'=>$type,
                        'admin_id'=>$this->admininfo->id
                    ])
                    ->value('value');
                if (empty($view))
                {
                    if ($type == 1)
                    {
                        if ($menu > 1)
                        {
                            $view = $this->my_customlist;
                        }else{
                            $view = $this->customlist;
                        }
                    }else
                    {
                        if ($menu > 1)
                        {
                            $view = $this->my_companycustomlist;
                        }else
                        {
                            $view = $this->companycustomlist;
                        }
                    }
                }
                $this->ajaxReturn(200, '获取数据成功',$view);
            }
            else
            {
                $data_input = [
                    'menu' => input('post.menu/d',0,'intval'),
                    'type' => input('post.type/d',0,'intval'),
                    'value' => input('post.value/s','',null),
                    'admin_id' => $this->admininfo->id
                ];
                $crm_custom_list = model('b2bcrm.CrmCustomList')
                    ->where([
                        'menu'=>$data_input['menu'],
                        'type'=>$data_input['type'],
                        'admin_id'=>$this->admininfo->id
                    ])->find();

                if (empty($crm_custom_list))
                {
                    $save = model('b2bcrm.CrmCustomList')
                        ->allowField(true)
                        ->isUpdate(false)
                        ->save($data_input);
                }else
                {
                    $save = model('b2bcrm.CrmCustomList')
                        ->allowField(true)
                        ->isUpdate(true)
                        ->save($data_input,[
                            'menu'=>$data_input['menu'],
                            'type'=>$data_input['type'],
                            'admin_id'=>$this->admininfo->id
                        ]);
                }

                if (false === $save)
                {
                    $this->ajaxReturn(500, model('b2bcrm.CrmCustomList')->getError());
                }
                $this->ajaxReturn(200, '保存成功');
            }
        }catch (\Exception $e)
        {
            $this->ajaxReturn(500, $e->getMessage());
        }
    }
}