<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/6/24
 * Time: 15:18
 */

namespace app\common\model\shortvideo;


use app\common\model\BaseModel;
use app\common\model\Company;
use app\common\model\Member;
use app\common\model\Resume;
use app\common\model\Uploadfile;
use app\common\model\ViewJob;

class SvCollect extends BaseModel
{
    const ACTION_COLLECT = 1;
    const ACTION_UNCOLLECT = 0;
    public function collect($userinfo, $id, $type, $action){
        if($userinfo->utype == $type){
            if($userinfo->utype == 1){
                exception('企业不可以收藏招聘视频');
            }else{
                exception('个人不可以收藏求职视频');
            }
        }
        $where = ['uid'=>$userinfo->uid, 'vid'=>$id, 'type'=>$type];
        $row = $this->where($where)->find();

        $m = new SvCompanyVideo();
        if($type == 2){
            $m = new SvPersonalVideo();
        }
        if($action == self::ACTION_COLLECT){
            if($row)return;
            $this->save($where+['addtime'=>time()]);
            $m->where(['id'=>$id])->setInc('like', 1);
        }else{
            if(!$row)return;
            $m->where(['id'=>$id])->setDec('like', 1);
            $this->where($where)->delete();
        }
    }

    public function getList($start, $down, $userinfo, $page, $pageSize){
        $mCom = new SvCompanyVideo();
        $mPers = new SvPersonalVideo();

        if($userinfo->utype == 1){
            $table = $mPers->getTable();
            $table2 = (new Resume())->getTable();
            $fields = 'c.display_name,c.sex,c.fullname,c.enter_job_time,c.birthday,c.education,c.specialty,c.photo_img as logo,c.is_display,c.audit as resume_audit,c.id as resume_id';
        }else{
            $table = $mCom->getTable();
            $table2 = (new Company())->getTable();
            $fields = 'c.companyname,c.id as comid,c.logo,c.audit as com_audit';
        }
        $table3 = (new Member())->getTable();
        $fields .= ',d.avatar';

        $order = 'a.id desc';
        $where = ['a.uid'=>$userinfo->uid];
        if($start){
            if($down){
                $where['a.id'] = ['gt', $start];
                $order = 'a.id asc';
            } else{
                $where['a.id'] = ['elt', $start ];
            }
        }
        $list = $this->alias('a')->join($table.' b', 'a.vid=b.id')
            ->join($table2. ' c', 'b.uid=c.uid')
            ->join($table3. ' d', 'a.uid=d.uid')
            ->where(['a.uid'=>$userinfo->uid])
            ->order($order)
            ->limit(($page-1)*$pageSize, $pageSize)
            ->field('a.id as collect_id,b.id,b.title,b.fid,b.address,b.lon,b.lat,b.uid,b.like,b.view_count,b.addtime,'.$fields)
            ->select();

        if($userinfo->utype == 1){
            $mPers->processList($list, $userinfo, 1);
        }else{
            $mCom->processList($list, $userinfo, 1);
        }

        return $list;
    }
}
