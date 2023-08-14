<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class CrmCompany extends Backend
{
    /**
     * @Purpose:
     * 企微客户客户管理-首页
     * @Method index()
     *
     * @param integer $page_num 当前页
     * @param integer $page_size 每页显示条数
     * @param string $userid 所属员工[企业微信成员UserID]
     * @param string $register 注册状态[3:非平台用户;1:企业用户;2:个人用户;]
     * @param string $keyword 关键字检索[外部联系人名称]
     * @param array $tag_name 客户标签/标签组名称
     * @param array $tag_id 客户标签ID[空:为标签组筛选;非空:标签筛选;]
     *
     * @return Jsonp
     *
     * @throws null
     *
     * @link {domain}corpwechat/external_user/index
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/3/10
     */
    public function index()
    {
        $map = array(); // 查询条件

        //  1.所属员工
        $userid = input('post.userid/s', '', 'trim');
        if (isset($userid) && !empty($userid)) {
            //  所属员工[企业微信成员UserID]
            $map['s.userid'] = ['=', $userid];
        }

        //  2.用户状态 - 注册状态[3:非平台用户;1:企业用户;2:个人用户;]
        $register = input('post.register/d', 0, 'intval');
        switch ($register) {
            case 1:
                // 1:企业用户;
            case 2:
                // 2:个人用户;
                $map['m.utype'] = ['=', $register];
                break;

            case 3:
                // 3:非平台用户
                $map['m.utype'] = ['EXP', Db::raw(' IS NULL ')];
                break;

            case 0:
            default:
                // 全部
                break;
        }

        //  3.客户标签
        $tag_name = input('post.tag_name/s', '', 'trim');
        $tag_id = input('post.tag_id/s', '', 'trim');
        if (isset($tag_name) && !empty($tag_name)) {
            if (isset($tag_id) && !empty($tag_id)) {
                //  标签
                $map['e.tags$."' . $tag_name . '"'] = ['=', $tag_id];

            } else {
                //  标签组
                $map['e.tag_group$."' . $tag_name . '"'] = ['=', $tag_name];
            }

        }


        //  4.关键字
        $keyword = input('post.keyword/s', '', 'trim');
        if (isset($keyword) && !empty($keyword)) {
            //  关键字检索[外部联系人名称]
            $map['u.name'] = ['like', "%{$keyword}%"];
        }

        // 5.排序
        $sort = input('post.sort/s', '', 'trim');
        switch ($sort) {
            case  'create_time':
                // 绑定时间排序
                $order = ["e.{$sort} DESC"];
                break;
            case  'update_time':
                // 更新时间排序
                $order = ["e.{$sort} DESC"];
                break;
            case  'add_time':
                // 添加时间排序
                $order = ["e.{$sort} DESC"];
                break;
            default:
                $order = ['e.create_time DESC'];
                break;
        }

        $page_num = input('post.page_num/d', 1, 'intval');
        $page_size = input('post.page_size/d', 10, 'intval');

        #  获取字段
        $field = 'e.id as external_id,
            e.external_user_id,
            u.name as external_name,
            u.thumb_avatar as external_avatar,
            u.gender as external_gender,
            s.name as staff_name,
            IFNULL(m.utype,3) as register,
            e.add_way,
            e.add_time,
            e.update_time,
            e.tags';
        $list = model('corpwechat.CorpwechatExternalUser')
            ->getList($map, $order, $page_num, $page_size, $field);

        $this->ajaxReturn(200, 'SUCCESS', $list);
    }
}