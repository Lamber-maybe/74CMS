<?php
namespace app\apiadmin\controller;

class SceneQrcode extends \app\common\controller\Backend
{
    public function index()
    {
        $status = input('get.status/s', '', 'trim');
        $type = input('get.type/s', '', 'trim');
        $platform = input('get.platform/s', '', 'trim');
        $keyword = input('get.keyword/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $where = [];
        if ($keyword) {
            $where['title'] = ['like', '%' . $keyword . '%'];
        }
        if ($type!='') {
            $where['type'] = $type;
        }
        if ($platform!='') {
            $where['platform'] = intval($platform);
        }
        if ($status!='') {
            if($status==1){
                $where['deadline'] = ['gt',time()]; 
            }else{
                $where['deadline'] = ['elt',time()]; 
            }
        }
        $total = model('SceneQrcode')->where($where)->count();
        $list = model('SceneQrcode')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $returnlist = [];
        $timestamp = time();
        $scanData = model('SceneQrcodeScanLog')->group('pid')->column('pid,count(*) as num');
        $regData = model('SceneQrcodeRegLog')->group('pid')->column('pid,count(*) as num');
        $subscribeData = model('SceneQrcodeSubscribeLog')->group('pid')->column('pid,count(*) as num');
        foreach ($list as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['uuid'] = $value['uuid'];
            $arr['title'] = $value['title'];
            $arr['type'] = $value['type'];
            $arr['type_cn'] = model('SceneQrcode')->type_arr[$value['type']]['name'];
            $arr['deadline'] = $value['deadline'];
            $arr['platform'] = $value['platform'];
            $arr['platform_cn'] = model('SceneQrcode')->platform_arr[$value['platform']];
            $arr['paramid'] = $value['paramid'];
            $arr['qrcode_src'] = $value['qrcode_src'];
            if($value['platform']!=1){
                $arr['qrcode_src'] = make_file_url($arr['qrcode_src']);
            }
            $arr['total_scan'] = isset($scanData[$value['id']])?$scanData[$value['id']]:0;
            $arr['total_subscribe'] = isset($subscribeData[$value['id']])?$subscribeData[$value['id']]:0;
            $arr['total_reg'] = isset($regData[$value['id']])?$regData[$value['id']]:0;
            $arr['status'] = $value['deadline']>$timestamp?1:0;
            $arr['copy_url'] = config('global_config.mobile_domain').str_replace(":id",$value['paramid'],model('SceneQrcode')->type_arr[$value['type']]['mobile_page']).'?scene_uuid='.$arr['uuid'];
            $returnlist[] = $arr;
        }

        $return['items'] = $returnlist;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function add()
    {
        $input_data = [
            'title' => input('post.title/s', '', 'trim'),
            'deadline' => input('post.deadline/s', 'trim', 'trim'),
            'type' => input('post.type/s', '', 'trim'),
            'platform' => input('post.platform/d', 0, 'intval'),
            'paramid' => input('post.paramid/d', 0, 'intval'),
            'qrcode_src'=>''
        ];
        if($input_data['platform']==0){
            $input_data['deadline'] = strtotime($input_data['deadline']);
        }else{
            $input_data['deadline'] = 0;
        }
        
        $input_data['uuid'] = uuid();
        $result = model('SceneQrcode')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('SceneQrcode')->getError());
        }
        $insertid = model('SceneQrcode')->id;
        $typeinfo = model('SceneQrcode')->type_arr[$input_data['type']];
        if($input_data['platform']==0){
            $expire = $input_data['deadline']-time();
            if($expire<0){
                $expire = 60;
            }
            if($expire>2592000){
                $expire = 2592000;
            }
            $class = new \app\common\lib\Wechat;
            $qrcodeData = $class->makeQrcode(['alias'=>'subscribe_'.$typeinfo['alias'],$typeinfo['offiaccount_param_name']=>$input_data['paramid'],'scene_uuid'=>$input_data['uuid']],$expire);
            $result = file_get_contents($qrcodeData);
            $filename = 'scene_qrcode_wechat_'.$insertid.'.jpg';
            $file_dir_name = 'files/'.date('Ymd/');
            $file_dir = SYS_UPLOAD_PATH.$file_dir_name;
            $file_path = $file_dir.$filename;
            if (!is_dir($file_dir)) {
                mkdir($file_dir, 0755, true);
            }
            file_put_contents($file_path, $result);
            $qrcodeSrc = $file_dir_name.$filename;
        }else{
            $locationUrl = config('global_config.mobile_domain').str_replace(":id",$input_data['paramid'],$typeinfo['mobile_page']).'?scene_uuid='.$input_data['uuid'];
            $locationUrl = urlencode($locationUrl);
            $qrcodeSrc = config('global_config.sitedomain').config('global_config.sitedir').'v1_0/home/qrcode/index?type=normal&url='.$locationUrl;
        }
        model('SceneQrcode')->save(['qrcode_src'=>$qrcodeSrc],['id'=>$insertid]);
        model('AdminLog')->record(
            '添加场景码。场景码ID【' .
                $insertid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('SceneQrcode')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        @unlink($info['qrcode_src']);
        $info->delete();
        model('AdminLog')->record(
            '删除场景码。场景码ID【' .
                $id .
                '】;场景码名称【' .
                $info['title'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function platformList()
    {
        $list = model('SceneQrcode')->platform_arr;
        $return = [];
        foreach ($list as $key => $value) {
            $arr['name'] = $value;
            $arr['value'] = $key;
            $return[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function typeList()
    {
        $list = model('SceneQrcode')->type_arr;
        $return = [];
        foreach ($list as $key => $value) {
            $return[] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function download()
    {
        $id = input('get.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('SceneQrcode')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info['qrcode_src'] = stripos($info['qrcode_src'],'http')===false?(SYS_UPLOAD_PATH.$info['qrcode_src']):$info['qrcode_src'];
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=".$info['title'].".jpg");
        echo file_get_contents($info['qrcode_src']);
    }
    public function searchList(){
        $type = input('get.type/s', '', 'trim');
        $keyword = input('get.keyword/s', '', 'trim');
        switch($type){
            case 'job':
                $this->searchJob($keyword);
                break;
            case 'company':
                $this->searchCompany($keyword);
                break;
            case 'resume':
                $this->searchResume($keyword);
                break;
            case 'notice':
                $this->searchNotice($keyword);
                break;
            case 'jobfairol':
                $this->searchJobfairol($keyword);
                break;
            case 'news':
                $this->searchNews($keyword);
                break;
            default:
                $this->ajaxReturn(500, '参数错误');
        }
    }
    public function searchJob($keyword)
    {
        if (!$keyword) {
            $return = [];
        }else{
            $list = model('Job')
                ->alias('a')
                ->join(config('dababase.prefix') . 'company b','a.uid=b.uid','LEFT')
                ->where('a.audit',1)
                ->where('a.is_display',1)
                ->where(function ($query) use ($keyword) {
                    $query->where('a.id', intval($keyword))->whereOr('a.jobname', 'like','%'.$keyword.'%');
                })->column('a.id,a.jobname,b.companyname');
            $return = [];
            foreach ($list as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['label'] = $value['jobname'];
                $arr['label_small'] = $value['companyname'];
                $return[] = $arr;
            }
            $this->ajaxReturn(200, '获取数据成功', $return);
        }
    }
    public function searchCompany($keyword)
    {
        if (!$keyword) {
            $return = [];
        }else{
            $list = model('Company')
                ->where(function ($query) use ($keyword) {
                    $query->where('id', intval($keyword))->whereOr('companyname', 'like','%'.$keyword.'%');
                })->column('id,uid,companyname');
            $return = [];
            foreach ($list as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['label'] = $value['companyname'];
                $arr['label_small'] = '';
                $return[] = $arr;
            }
            $this->ajaxReturn(200, '获取数据成功', $return);
        }
    }
    public function searchResume($keyword)
    {
        if (!$keyword) {
            $return = [];
        }else{
            $list = model('Resume')
                ->where(function ($query) use ($keyword) {
                    $query->where('id', intval($keyword))->whereOr('fullname', 'like','%'.$keyword.'%');
                })->column('id,uid,fullname');
            $return = [];
            foreach ($list as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['label'] = $value['fullname'];
                $arr['label_small'] = '';
                $return[] = $arr;
            }
            $this->ajaxReturn(200, '获取数据成功', $return);
        }
    }
    public function searchNotice($keyword)
    {
        if (!$keyword) {
            $return = [];
        }else{
            $list = model('Notice')
                ->where(function ($query) use ($keyword) {
                    $query->where('id', intval($keyword))->whereOr('title', 'like','%'.$keyword.'%');
                })->column('id,is_display,title');
            $return = [];
            foreach ($list as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['label'] = $value['title'];
                $arr['label_small'] = '';
                $return[] = $arr;
            }
            $this->ajaxReturn(200, '获取数据成功', $return);
        }
    }
    public function searchNews($keyword)
    {
        if (!$keyword) {
            $return = [];
        }else{
            $list = model('Article')
                ->where(function ($query) use ($keyword) {
                    $query->where('id', intval($keyword))->whereOr('title', 'like','%'.$keyword.'%');
                })->column('id,is_display,title');
            $return = [];
            foreach ($list as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['label'] = $value['title'];
                $arr['label_small'] = '';
                $return[] = $arr;
            }
            $this->ajaxReturn(200, '获取数据成功', $return);
        }
    }
    public function searchJobfairol($keyword)
    {
        if (!$keyword) {
            $return = [];
        }else{
            $list = model('JobfairOnline')
                ->where(function ($query) use ($keyword) {
                    $query->where('id', intval($keyword))->whereOr('title', 'like','%'.$keyword.'%');
                })->column('id,thumb,title');
            $return = [];
            foreach ($list as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['label'] = $value['title'];
                $arr['label_small'] = '';
                $return[] = $arr;
            }
            $this->ajaxReturn(200, '获取数据成功', $return);
        }
    }
}
