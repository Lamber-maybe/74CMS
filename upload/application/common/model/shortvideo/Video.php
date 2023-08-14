<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/6/25
 * Time: 17:50
 */

namespace app\common\model\shortvideo;


use app\common\model\BaseModel;
use app\common\model\Company;
use app\common\model\Job;
use app\common\model\Member;
use app\common\model\Resume;
use app\common\model\ResumeIntention;
use app\common\model\Uploadfile;
use Think\Db;

class Video extends BaseModel
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'addtime';
    protected $updateTime = 'updatetime';

    const AUDIT_LIMIT = 100000;
    const AUDIT_YES = 2;
    const AUDIT_FAILED = 3;
    const AUDIT_INIT = 1;
    const AUDIT_ALL = 0;

    const TYPE_LATEST = 1;
    const TYPE_HOT = 2;
    const TYPE_NEARBY = 3;

    const PUBLIC_YES = 2;
    const PUBLIC_NO = 1;

    protected $map_audit = [
        1 => '待审核',
        2 => '已通过',
        3 => '未通过'
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'uid', 'uid')->bind(['companyname', 'comid' => 'id', 'logo', 'com_audit' => 'audit']);
    }

    public function personal()
    {
        return $this->hasOne(Resume::class, 'uid', 'uid')->bind(['display_name', 'sex', 'fullname', 'enter_job_time',
            'birthday', 'education', 'specialty', 'logo' => 'photo_img', 'is_display', 'resume_audit' => 'audit', 'resume_id' => 'id']);
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'uid', 'uid')->bind('avatar');
    }

    public function delAll($ids)
    {
        $this->where(['id' => ['in', $ids]])->delete();
    }

    public function del($id, $uid)
    {
        return $this->where(['id' => $id, 'uid' => $uid])->delete();
    }

    public function getAList($audit, $isPublic, $key_type, $keyword, $page, $pagesize)
    {
        $where = [];
        if ($audit) {
            $where['audit'] = $audit;
        }
        if ($isPublic) $where['is_public'] = $isPublic;
        if ($keyword) {
            if ($key_type == 1) $where['title'] = ['like', "%$keyword%"];
            if ($key_type == 2) $where['uid'] = intval($keyword);
        }
        if ($this->type == 1) {
            $with = 'company';
        } else {
            $with = 'personal';
        }
        $total = $this->where($where)->count();
        $list = $this->with($with)->where($where)->order('updatetime desc')->limit(($page - 1) * $pagesize, $pagesize)->select();

        $up = new Uploadfile();
        $up->getFileUrlBatch2($list, 'fid', 'video_src');
        return [
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pagesize' => $pagesize
        ];
    }

    public function getList($start, $down, $lat, $lon, $title, $uid, $type, $audit, $page, $pageSize, $userinfo = null)
    {
        $where = [];
        $field = 'id,fid,title,address,view_count,`like`,uid,addtime,is_public,audit,real_id';
        if ($type == self::TYPE_LATEST) {
            $order = 'id desc';
            // $where['addtime'] = ['gt', strtotime('today')];
        } else if ($type == self::TYPE_HOT) {
            if ($this->type == 1) {
                $where['view_count'] = ['gt', intval(config('global_config.shortvideo_jobing_hot'))];
            } else {
                $where['view_count'] = ['gt', intval(config('global_config.shortvideo_finding_hot'))];
            }

            $order = 'view_count desc';
        } else if ($type == self::TYPE_NEARBY && $lat && $lon) {
            $order = 'juli asc';
            $field .= sprintf(',( 6379 * acos( cos( radians(%s) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians(%s) ) + sin( radians(%s) ) * sin( radians( lat ) ) ) )*1000 AS juli ', $lat, $lon, $lat);
            //$where['juli'] = ['lt', 100];
        } else {
            $order = 'id desc';
        }
        $with = 'company';
        if ($this->type == 2) {
            $with = 'personal';
        }
        $with .= ',member';
        if ($audit == self::AUDIT_YES) {
            $where['id'] = ['gt', self::AUDIT_LIMIT];
            if ($start) {
                if ($down) {
                    $where['id'] = ['gt', $start];
                    $order = 'id asc';
                } else {
                    $where['id'] = ['between', [self::AUDIT_LIMIT, $start]];
                    $order = 'id desc';
                }
            }
        } else if ($audit > 0) {
            $where['id'] = ['lt', self::AUDIT_LIMIT];
        }
        if ($title) {
            $where[] = ['EXP', Db::raw(sprintf("id in (select id from %s where id>%d and title like '%%%s%%')",
                $this->getTable(), self::AUDIT_LIMIT, $title))];
            $list = $this->where($where)->with($with)->order($order)->field($field)->limit(($page - 1) * $pageSize, $pageSize)->select();
        } else {
            if ($uid) $where['uid'] = $uid;

            $list = $this->where($where)->with($with)->order($order)->field($field)->limit(($page - 1) * $pageSize, $pageSize)->select();
        }

        $this->processList($list, $userinfo);

        return $list;
    }

    public function detail($id, $userinfo = null)
    {
        $where = [];
        $field = 'id,fid,title,address,view_count,like,uid,addtime,is_public,audit,real_id';

        $with = 'company';
        if ($this->type == 2) {
            $with = 'personal';
        }
        $with .= ',member';
        $where['id'] = $id;
        $list = $this->where($where)->with($with)->field($field)->select();
        if (!isset($list[0])) {
            exception('视频不存在或已下架');
        }
        if ($list[0]['id'] < self::AUDIT_LIMIT) {
            if (!$userinfo) exception('视频不存在或已下架.');
            if ($userinfo->uid != $list[0]['uid']) {
                exception('视频不存在或已下架!');
            }
        }
        $this->processList($list, $userinfo);

        return $list[0];
    }


    public function processList(&$list, $userinfo = null, $isCollect = false)
    {
        $up = new Uploadfile();
        $up->getFileUrlBatch2($list, 'fid', 'video_src');
        $up->getFileUrlBatch2($list, 'avatar', 'avatar_src');
        $up->getFileUrlBatch2($list, 'logo', 'logo_src');

        $job = new Job();
        $pUids = [];
        $vids = [];
        foreach ($list as &$v) {
            $v['newer'] = $v['hot'] = 0;
            if ($this->type == 2) {
                if ($v['display_name'] == 0) {
                    if ($v['sex'] == 1) {
                        $v['fullname'] = cut_str(
                            $v['fullname'],
                            1,
                            0,
                            '先生'
                        );
                    } elseif ($v['sex'] == 2) {
                        $v['fullname'] = cut_str(
                            $v['fullname'],
                            1,
                            0,
                            '女士'
                        );
                    } else {
                        $v['fullname'] = cut_str(
                            $v['fullname'],
                            1,
                            0,
                            '**'
                        );
                    }
                }
                $v['education_text'] = isset(
                    model('BaseModel')->map_education[$v['education']]
                )
                    ? model('BaseModel')->map_education[$v['education']]
                    : '';
                $v['experience_text'] =
                    $v['enter_job_time'] == 0
                        ? '无经验'
                        : format_date($v['enter_job_time']) . '经验';
            }
            if (is_numeric($v['addtime'])) {
                $v['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
            }
            if (strtotime($v['addtime']) > strtotime('today')) $v['newer'] = 1;
            $v['addtime_fmt'] = daterange(time(), strtotime($v['addtime']));
            if ($v['view_count'] > 200) $v['hot'] = 1;//todo  需要配置
            $pUids[] = $v['uid'];
            $vids[] = $v['id'];
            if (!$v['logo'] || !$v['avatar']) {
                $v['avatar_src'] = default_empty('photo');
            }
        }

        unset($v);
        $tmp = [];
        $favs = [];
        if (count($pUids) > 0) {
            if ($this->type == 1) {
                $jobList = $job->where(['uid' => ['in', $pUids], 'audit' => 1, 'is_display' => 1])->field('id,uid,jobname,minwage,maxwage')->select();
                foreach ($jobList as $j) {
                    if (!isset($tmp[$j['uid']])) $tmp[$j['uid']] = [];
                    $tmp[$j['uid']][] = $j;
                }
                if ($userinfo) {
                    if ($userinfo->utype == 2) {
                        $favs = model('AttentionCompany')
                            ->where('company_uid', 'in', $pUids)
                            ->where('personal_uid', $userinfo->uid)
                            ->column('company_uid,id');
                    }
                }
            } else {
                $intention_data = model('ResumeIntention')
                    ->field('id,rid,uid,category,district')
                    ->where(['uid' => ['in', $pUids]])
                    ->select();
                $category_job_data = model('CategoryJob')->getCache();
                $category_district_data = model('CategoryDistrict')->getCache();
                foreach ($intention_data as $v) {
                    if (!isset($tmp[$v['uid']])) $tmp[$v['uid']] = [];
                    $tmp[$v['uid']][] = [
                        'category_text' => isset($category_job_data[$v['category']]) ? $category_job_data[$v['category']] : '',
                        'district_text' => isset($category_district_data[$v['district']]) ? $category_district_data[$v['district']] : '',
                    ];
                }
                if ($userinfo) {
                    if ($userinfo->utype == 1) {
                        $favs = model('FavResume')
                            ->where('personal_uid', 'in', $pUids)
                            ->where('company_uid', $userinfo->uid)
                            ->column('personal_uid,id');
                    }
                }
            }
        }
        $collect = [];
        if (count($vids) > 0 && $userinfo && !$isCollect) {
            $collect = (new SvCollect())->where(['uid' => $userinfo->uid, 'type' => $this->type, 'vid' => ['in', $vids]])->column('vid,uid');
        }
        foreach ($list as &$v) {
            if ($this->type == 1) {
                $v['job_list'] = isset($tmp[$v['uid']]) ? $tmp[$v['uid']] : [];
            } else {
                $v['intention'] = isset($tmp[$v['uid']]) ? $tmp[$v['uid']] : [];
            }
            $v['has_collect'] = $isCollect ? 1 : (isset($collect[$v['id']]) ? 1 : 0);
            $v['has_fav'] = isset($favs[$v['uid']]) ? 1 : 0;
        }
    }

    public function inspectId($id)
    {
        $count = $this->where(['id' => $id])->count($id);
        if ($count > 0) {
            $id = $this->max('real_id') + rand(5, 10);
            $this->inspectId($id);
        }
        return $id;
    }

    public function saveVideo($id, $fid, $filesize, $title, $lat, $lon, $address, $uid)
    {
        $data = [
            'fid' => $fid,
            'title' => $title,
            'filesize' => $filesize,
            'lat' => $lat,
            'lon' => $lon,
            'address' => $address,
            'uid' => $uid
        ];
        if ($id) {
            $row = $this->find($id);
            if ($data['fid'] == $row['fid']) {
                $data['filesize'] = $row['filesize'];
            }
            $data['is_public'] = $row['is_public'];
            $data['audit'] = $row['audit'];
            if ($this->type == 1) {
                $edit_audit = intval(config('global_config.shortvideo_edited_jobing_audit'));
                if ($edit_audit) $data['audit'] = $edit_audit;
            } else {
                $edit_audit = intval(config('global_config.shortvideo_edited_finding_audit'));
                if ($edit_audit) $data['audit'] = $edit_audit;
            }
            if ($data['audit'] == self::AUDIT_YES && $data['is_public'] == self::PUBLIC_YES) {
                $data['id'] = $row['real_id'];
            } else {
                if ($id > self::AUDIT_LIMIT) {
                    $data['id'] = $this->where(['id' => ['lt', self::AUDIT_LIMIT]])->max('id') + 1;//2022.4.8 zxr
                }
            }
            $this->where(['id' => $id, 'uid' => $uid])->update($data);
        } else {
            $data['is_public'] = self::PUBLIC_YES;
            if ($this->type == 1) {
                $add_audit = intval(config('global_config.shortvideo_new_jobing_audit'));
                $data['audit'] = $add_audit;
                $view_count = intval(config('global_config.shortvideo_jobing_view_init'));
                if ($view_count > 0) {
                    $data['view_count'] = rand(1, $view_count);
                }
            } else {
                $add_audit = intval(config('global_config.shortvideo_new_finding_audit'));
                $data['audit'] = $add_audit;
                $view_count = intval(config('global_config.shortvideo_finding_view_init'));
                if ($view_count > 0) {
                    $data['view_count'] = rand(1, $view_count);
                }
            }
            $max = $this->where(['id' => ['lt', self::AUDIT_LIMIT]])->max('id');
            //2022.4.8 zxr
//            if($max<self::AUDIT_LIMIT){
//                $max = self::AUDIT_LIMIT;
//            }
            $data['id'] = $max + 1;//2022.4.8 zxr
            $data['real_id'] = $this->max('real_id') + 1;//2022.4.8 zxr
            if ($data['audit'] == self::AUDIT_YES && $data['is_public'] == self::PUBLIC_YES) {
                $data['id'] = $data['real_id'];
            }
//            $data['id'] = $this->inspectId($data['id']);//2022.4.8 zxr
            $this->save($data);
        }
    }

    public function addPlayRecord($id)
    {
        $this->where('id', $id)->setInc('view_count', 1, 600);
    }

    public function setPublic($uid, $id, $v)
    {
        $row = $this->find($id);
        if ($uid != $row['uid']) exception('非法操作');
        $data = ['is_public' => $v];
        if ($v == self::PUBLIC_YES) {
            if ($row['audit'] == self::AUDIT_YES) {
                if ($row['real_id'] < self::AUDIT_LIMIT) {
                    $max = $this->max('real_id') + rand(5, 10);
                    if ($max < self::AUDIT_LIMIT) $max += self::AUDIT_LIMIT;
                    $data['id'] = $max;
                    $data['real_id'] = $max;
                } else {
                    $data['id'] = $row['real_id'];
                }
            }
        } else {
            if ($row['id'] > self::AUDIT_LIMIT) {
                $max = $this->where(['id' => ['lt', self::AUDIT_LIMIT]])->max('id');
                $data['id'] = $max + rand(5, 10);
            }
        }
        $this->where(['id' => $id])->update($data);
    }

    public function setAudit($id, $audit, $reason = '', $admininfo)
    {
        $rows = $this->where(['id' => ['in', $id]])->select();
        $data = ['audit' => $audit, 'reason' => $reason];
        $ids = [];
        foreach ($rows as $row) {
            if ($audit == self::AUDIT_YES) {
                if ($row['is_public'] == self::PUBLIC_YES) {
                    if ($row['real_id'] < self::AUDIT_LIMIT) {
                        $max = $this->max('real_id') + rand(5, 10);
                        if ($max < self::AUDIT_LIMIT) $max += self::AUDIT_LIMIT;
                        $data['id'] = $max;
                        $data['real_id'] = $max;
                    } else {
                        $data['id'] = $row['real_id'];
                    }
                }
            } else {
                if ($row['id'] > self::AUDIT_LIMIT) {
                    $max = $this->where(['id' => ['lt', self::AUDIT_LIMIT]])->max('id');
                    $data['id'] = $max + rand(5, 10);
                }
            }
            $this->where(['id' => $row['id']])->update($data);
            $ids[] = $row['real_id'];
        }
        if (!empty($ids)) {
            model('AdminLog')->record(sprintf('将%s视频审核状态变更为【%s】。视频ID【%s】', $this->type == 1 ? '企业' : '个人', $this->map_audit[$audit],
                implode(',', $ids)), $admininfo);
            /*
             * 【新增】视频招聘、视频求职审核通知站内信
             * zch 2022.10.18
             * */
            $this->sendNotify($audit, $rows, $reason);
        }
    }

    public function getValidTotal($uid)
    {
        return $this->where(['uid' => $uid, 'id' => ['gt', self::AUDIT_LIMIT]])->count();
    }

    /*
     * 【新增】视频招聘、视频求职审核通知站内信
     * zch 2022.10.13
     * $audit 审核状态 1-审核通过 -审核拒绝
     * $list 列表
     * $reason 拒绝内容
     * */
    private function sendNotify($audit, $list, $reason)
    {
        switch ($audit) {
            case 2:
                $flag = 'success';
                break;
            case 3:
                $flag = 'fail';
                break;
            default:
                return;
        }

        switch ($this->type) {
            case 1:
                $sign = 'company_video_';
                break;
            case 2:
                $sign = 'personal_video_';
                break;
            default:
                return;
        }

        $alias = $sign . $flag;

        foreach ($list as $row) {
            $reason = !empty($reason) ? $reason : '无';
            model('NotifyRule')->notify(
                $row['uid'],
                $this->type,
                $alias,
                [
                    'video_title' => $row['title'],
                    'reason' => $reason
                ]
            );
        }
    }


}
