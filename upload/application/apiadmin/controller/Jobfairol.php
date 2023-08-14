<?php
namespace app\apiadmin\controller;
class Jobfairol extends \app\common\controller\Backend{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $where = [];
        $settr = input('get.settr/d', 0, 'intval');
        $key_type = input('get.key_type/d', 1, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['title'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['id'] = $keyword;
                    break;
            }
        }
        if ($settr) $where['addtime'] = ['gt',strtotime("-".$settr." day")];
        $total = model('JobfairOnline')->where($where)->count();
        $timestamp = time();
        $field =
            'id,title,thumb,starttime,endtime,click,addtime,enable_setmeal_id,CASE 
        WHEN starttime<=' .
            $timestamp .
            ' AND endtime>'.$timestamp.' THEN 2
        WHEN starttime>' .
            $timestamp .
            ' THEN 1
        ELSE 0
        END AS score';
        $list = model('JobfairOnline')->where($where)->field($field)->order('score desc')->page($current_page, $pagesize)->select();
        $participate_company = $participate_personal = $jobfair_id_arr = $thumb_arr = $thumb_id_arr = [];
        foreach ($list as $key => $value) {
            $jobfair_id_arr[] = $value['id'];
            $value['thumb'] > 0 && ($thumb_id_arr[] = $value['thumb']);
        }
        if (!empty($thumb_id_arr)) {
            $thumb_arr = model('Uploadfile')->getFileUrlBatch($thumb_id_arr);
        }
        if (!empty($jobfair_id_arr)) {
            $participate_company = model('JobfairOnlineParticipate')->where('jobfair_id','in',$jobfair_id_arr)->where('utype',1)->group('jobfair_id')->column('jobfair_id,count(id)');
            $participate_personal = model('JobfairOnlineParticipate')->where('jobfair_id','in',$jobfair_id_arr)->where('utype',2)->group('jobfair_id')->column('jobfair_id,count(id)');
        }
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['title'] = $value['title'];
            $tmp_arr['thumb_src'] = isset($thumb_arr[$value['thumb']])?$thumb_arr[$value['thumb']]:default_empty('jobfair_thumb');
            $tmp_arr['starttime'] = $value['starttime'];
            $tmp_arr['endtime'] = $value['endtime'];
            $tmp_arr['addtime'] = $value['addtime'];
            $tmp_arr['setmeal_id'] = $value['enable_setmeal_id'];
            $tmp_arr['click'] = $value['click'];
            $tmp_arr['score'] = $value['score'];
            $tmp_arr['total_company'] = isset($participate_company[$value['id']])?$participate_company[$value['id']]:0;
            $tmp_arr['total_personal'] = isset($participate_personal[$value['id']])?$participate_personal[$value['id']]:0;
            $tmp_arr['jobfair_link'] = url('index/jobfairol/show', ['id' => $value['id']]);

            $setmeal_count = model('Setmeal')->count();
            $setmeal_cn = model('Setmeal')->column('id,name');
            $setmeal_id = explode(',',$value['enable_setmeal_id']);
            $s_count = count($setmeal_id);
            $title = '';
            if($setmeal_count > $s_count){
                $tmp_arr['setmeal'] = 1;
                foreach ($setmeal_id as $key => $value) {
                    if(isset($setmeal_cn[$value])){
                        $title .= $setmeal_cn[$value].',';
                    }
                }
                $title = trim($title,',');
                $tmp_arr['setmeal_cn'] = $title;
                unset($title);
            } else {
                $tmp_arr['setmeal'] = 0;
            }
            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    // 添加网络招聘会
    public function add(){
        $input_data = [
            'title' => input('post.title/s', '', 'trim'),
            'thumb' => input('post.thumb/d', 0, 'intval'),
            'mobile_header_logo' => input('post.mobile_header_logo/d', 0, 'intval'),
            'pc_header_logo' => input('post.pc_header_logo/d', 0, 'intval'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'endtime' => input('post.endtime/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim'),
            'enable_setmeal_id' => input('post.enable_setmeal_id/s', '', 'trim'),
            'must_company_audit' => input('post.must_company_audit/d', 0, 'intval'),
            'min_complete_percent' => input('post.min_complete_percent/d', 0, 'intval'),
            'click' => input('post.click/d', 0, 'intval'),
            'qrcode' => input('post.qrcode/d', 0, 'intval')
        ];
        $reg = model('JobfairOnline')->jobfairOnlineAdd($input_data,$this->admininfo);
        if (!$reg['state']) $this->ajaxReturn(500, $reg['msg']);
        model('AdminLog')->record(
            '发布网络招聘会。招聘会ID【' .
            $reg['data']['id'] .
            '】;网络招聘会标题【' .
            $input_data['title'] .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    // 编辑网络招聘会
    public function edit(){
        if (request()->isPost()){
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'thumb' => input('post.thumb/d', 0, 'intval'),
                'mobile_header_logo' => input('post.mobile_header_logo/d', 0, 'intval'),
                'pc_header_logo' => input('post.pc_header_logo/d', 0, 'intval'),
                'starttime' => input('post.starttime/s', '', 'trim'),
                'endtime' => input('post.endtime/s', '', 'trim'),
                'content' => input('post.content/s', '', 'trim'),
                'enable_setmeal_id' => input('post.enable_setmeal_id/s', '', 'trim'),
                'must_company_audit' => input('post.must_company_audit/d', 0, 'intval'),
                'min_complete_percent' => input('post.min_complete_percent/d', 0, 'intval'),
                'click' => input('post.click/d', 0, 'intval'),
                'qrcode' => input('post.qrcode/d', 0, 'intval')
            ];
            $reg = model('JobfairOnline')->jobfairOnlineEdit($input_data,$this->admininfo);
            if (!$reg['state']) $this->ajaxReturn(500, $reg['msg']);
            model('AdminLog')->record(
                '编辑网络招聘会。招聘会ID【' . $input_data['id'] . '】;招聘会标题【' . $input_data['title'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, $reg['msg']);
        }else{
            $id = input('get.id/d', 0, 'intval');
            $info = model('JobfairOnline')->find($id);
            if (!$info) $this->ajaxReturn(500, '数据获取失败');
            $info = $info->toArray();
            $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
            $imgs = $imageUrl = [];
            if($info['thumb']) $imgs[] = $info['thumb'];
            if($info['qrcode']) $imgs[] = $info['qrcode'];
            if($info['pc_header_logo']) $imgs[] = $info['pc_header_logo'];
            if($info['mobile_header_logo']) $imgs[] = $info['mobile_header_logo'];
            if(!empty($imgs)){
                $imageUrl = model('Uploadfile')->getFileUrlBatch($imgs);
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'thumbUrl' => isset($imageUrl[$info['thumb']])?$imageUrl[$info['thumb']]:'',
                'qrcodeUrl' => isset($imageUrl[$info['qrcode']])?$imageUrl[$info['qrcode']]:'',
                'pc_header_logo_url' => isset($imageUrl[$info['pc_header_logo']])?$imageUrl[$info['pc_header_logo']]:'',
                'mobile_header_logo_url' => isset($imageUrl[$info['mobile_header_logo']])?$imageUrl[$info['mobile_header_logo']]:''
            ]);
        }
    }
    // 删除网络招聘会
    public function delete(){
        $id = input('post.id/a');
        if (!$id) $this->ajaxReturn(500, '请选择网络招聘会');
        $reg = model('JobfairOnline')->jobfairOnlineDelete($id,$this->admininfo);
        $this->ajaxReturn($reg['state']?200:500, $reg['msg']);
    }
    // 参会企业列表
    public function companyList() {
        $where = [];
        $jobfair_id = input('get.jobfair_id/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $audit = input('get.audit/d', '');
        $setmeal_id = input('get.setmeal_id/d', '');
        $source = input('get.source/d', '');
        $stick = input('get.stick/d', '');
        $key_type = input('get.key_type/d', '');
        $keyword = input('get.keyword/s', '', 'trim');
        // 排序规则
        $orderKey = input('get.order_key/d', '');
        if ($audit !== '') $where['a.audit'] = $audit;
        if ($source !== '') $where['a.source'] = $source;
        if ($stick !== '') $where['a.stick'] = $stick;
        $where['jobfair_id'] = $jobfair_id;
        $where['utype'] = 1;
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->field('a.*,b.companyname,b.setmeal_id,b.audit c_audit,c.contact,c.mobile,c.telephone,b.id as company_id')
            ->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'left')
            ->join(config('database.prefix') . 'company_contact c', 'a.uid=c.uid', 'left')
            ->where($where);
        if ($setmeal_id !== '') {
            $list = $list->where('b.setmeal_id',$setmeal_id);
        }
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $list = $list->where('b.companyname','like','%'.$keyword.'%');
                    break;
                case 2:
                    $list = $list->where('c.mobile','like','%'.$keyword.'%');
                    break;
            }
        }

        // 排序规则:默认|参会状态,1|添加时间,2|刷新时间 chenyang 2022年3月21日18:47:17
        if ($orderKey == 1) {
            // 按添加时间排序
            $list = $list->order(['a.addtime' => 'desc']);
        }elseif ($orderKey == 2) {
            // 按刷新时间排序
            $list = $list->order(['b.refreshtime' => 'desc']);
        }else{
            // 按参会状态排序 默认排序
            // 参会状态:0|待审核,1|已通过,2|未通过
            // 按照参会状态 待审核-已通过-未通过 进行排序
            $list = $list->orderRaw('FIELD(a.audit,0,1,2) asc');
            $list = $list->order(['a.addtime' => 'desc']);
        }

        $list = $list->page($current_page, $pagesize)->select();
        foreach ($list as $key => $val) {
            $val['setmeal_cn'] = model('Setmeal')->where('id', $val['setmeal_id'])->value('name');
            $val['mobile'] = $val['mobile'] ? $val['mobile'] : $val['telephone'];
            $val['add_status'] = $val['qrcode'] ? 1 : 0;
            $val['audit'] = intval($val['audit']);
            $val['c_audit'] = intval($val['c_audit']);
            if (!empty($val['qrcode'])) {
                $qr = model('Uploadfile')->where('id', $val['qrcode'])->field('id,addtime')->find();
                $val['add_day'] = ceil((time()-$qr['addtime'])/86400);
                $val['qrcode_url'] = model('Uploadfile')->getFileUrl($qr['id']);
            }
            $val['link'] = url('index/company/show', ['id' => $val['company_id']]);
            $list[$key] = $val;
        }
        $total = model('JobfairOnlineParticipate')->alias('a')
            ->where($where)
            ->count();
        $setmeal = model('Setmeal')->field('id,name')->select();
        $return['items'] = $list;
        $return['setmeal'] = $setmeal;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    // 参会个人列表
    public function personalList() {
        $where = [];
        $jobfair_id = input('get.jobfair_id/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $audit = input('get.audit/d', '');
        $source = input('get.source/d', '');
        $key_type = input('get.key_type/d', '');
        $keyword = input('get.keyword/s', '', 'trim');
        // 排序规则
        $orderKey = input('get.order_key/d', '');

        if ($audit !== '') $where['a.audit'] = $audit;
        if ($source !== '') $where['a.source'] = $source;
        $where['jobfair_id'] = $jobfair_id;
        $where['utype'] = 2;
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->field('a.id jid,a.uid juid,a.jobfair_id,a.utype,a.audit jaudit,a.qrcode,a.addtime jaddtime,a.source jsource,a.stick jstick,a.note,b.*,c.mobile,b.is_display')
            ->join(config('database.prefix') . 'resume b', 'a.uid=b.uid', 'left')
            ->join(config('database.prefix') . 'resume_contact c', 'a.uid=c.uid', 'left')
            ->where('b.id is not null')
            ->where($where);
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $list = $list->where('b.fullname','like','%'.$keyword.'%');
                    break;
                case 2:
                    $list = $list->where('c.mobile','like','%'.$keyword.'%');
                    break;
            }
        }

        // 排序规则:默认|参会状态,1|添加时间,2|刷新时间 chenyang 2022年3月17日14:48:42
        if ($orderKey == 1) {
            // 按添加时间排序
            $list = $list->order(['a.addtime' => 'desc']);
        }elseif ($orderKey == 2) {
            // 按刷新时间排序
            $list = $list->order(['b.refreshtime' => 'desc']);
        }else{
            // 按参会状态排序 默认排序
            // 参会状态:0|待审核,1|已通过,2|未通过
            // 按照参会状态 待审核-已通过-未通过 进行排序
            $list = $list->orderRaw('FIELD(a.audit,0,1,2) asc');
            $list = $list->order(['a.addtime' => 'desc']);
        }

        $list = $list->page($current_page, $pagesize)->select();
        $ridarr = [];
        $complete_list = [];
        $thumb_arr = [];
        $thumb_id_arr = [];
        foreach ($list as $key => $value) {
            $ridarr[] = $value['id'];
            $thumb_id_arr[] = $value['photo_img'];
        }
        if (!empty($ridarr)) {
            $complete_list = model('Resume')->countCompletePercentBatch(
                $ridarr
            );
        }
        if (!empty($thumb_id_arr)) {
            $thumb_arr = model('Uploadfile')->getFileUrlBatch($thumb_id_arr);
        }
        foreach ($list as $key => $value) {
            $value['age'] =
                intval($value['birthday']) == 0
                    ? '年龄未知'
                    : date('Y') - intval($value['birthday']) . '岁';
            $value['sex_cn'] = isset(model('Resume')->map_sex[$value['sex']]) ? model('Resume')->map_sex[$value['sex']] : '性别未知';
            $value['education_cn'] = isset(model('BaseModel')->map_education[$value['education']])
                ? model('BaseModel')->map_education[$value['education']]
                : '学历未知';
            $value['experience_cn'] = $value['enter_job_time'] == 0 ? '无经验' : format_date($value['enter_job_time']);
            $value['complete'] = isset($complete_list[$value['id']]) ? $complete_list[$value['id']] : 0;
            $value['r_audit'] = $value['audit'];
            $value['photo_url'] = isset($thumb_arr[$value['photo_img']])?$thumb_arr[$value['photo_img']]:default_empty('photo');
            $value['link'] = url('index/resume/show', ['id' => $value['id']]);
            $list[$key] = $value;
        }
        $total = model('JobfairOnlineParticipate')->alias('a')->where($where)->count();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    // 设置微信直面为客服
    public function qrService () {
        $jobfair_id = input('post.jobfair_id/d', 0, 'intval');
        $uid = input('post.uid/a');
        if (empty($jobfair_id)) {
            $this->ajaxReturn(500, '请选择网络招聘会');
        }
        model('JobfairOnline')->qrService($jobfair_id,$uid,$this->admininfo);
        $this->ajaxReturn(200, '设置成功');
    }
    // 置顶
    public function setSticky() {
        $jobfair_id = input('post.jobfair_id/d', 0, 'intval');
        $uid = input('post.uid/a');
        $stick = input('post.stick/d', 0, 'intval');
        if (empty($jobfair_id)) {
            $this->ajaxReturn(500, '请选择网络招聘会');
        }
        model('JobfairOnline')->setSticky($jobfair_id,$uid,$stick,$this->admininfo);
        $this->ajaxReturn(200, '设置成功');
    }
    // 微信直面
    public function setQrcode() {
        $jobfair_id = input('post.jobfair_id/d', 0, 'intval');
        $uid = input('post.uid/a');
        $qrcode = input('post.qrcode/d', 0, 'intval');
        $note = input('post.note/s', '', 'trim');
        if (empty($jobfair_id)) {
            $this->ajaxReturn(500, '请选择网络招聘会');
        }
        model('JobfairOnline')->setQrcode($jobfair_id,$uid,$qrcode,$note,$this->admininfo);
        model('AdminLog')->record(
            '网络招聘会添加微信直面。网络招聘会ID【' .
            $jobfair_id .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '设置成功');
    }
    // 添加参会企业获取企业列表
    public function getCompany(){
        $where = [];
        $key = input('get.key/s','','trim');
        $type = input('get.type/s','','trim');
        switch($type){
            case 'companyname':
                $where['companyname'] = ['like','%'.$key.'%'];
                break;
            case 'uid':
                $where['uid'] = intval($key);
                break;
        }
        $list = model('Company')->field('id,uid,companyname,addtime,refreshtime')->where($where)->limit(30)->select();
        foreach($list as $key=>$val){
            $list[$key]['company_link'] = url('index/company/show', ['id' => $val['id']]);
        }
        $this->ajaxReturn(200, '获取成功',['items'=>$list]);
    }
    // 添加参会个人获取个人列表
    public function getPersonal() {
        $where = [];
        $key = input('get.key/s','','trim');
        $type = input('get.type/s','','trim');
        switch($type){
            case 'fullname':
                $where['fullname'] = ['like','%'.$key.'%'];
                break;
            case 'uid':
                $where['uid'] = intval($key);
                break;
        }
        $list = model('Resume')->field('id,uid,fullname,addtime,refreshtime')->where($where)->limit(30)->select();
        foreach($list as $key=>$val){
            $list[$key]['personal_link'] = url('index/personal/show', ['id' => $val['id']]);
        }
        $this->ajaxReturn(200, '获取成功',['items'=>$list]);
    }
    // 设置参会状态
    public function setStatus() {
        $jobfair_id = input('post.jobfair_id/d', 0, 'intval');
        $uid = input('post.uid/a');
        $audit = input('post.audit/d', 0, 'intval');
        if (empty($jobfair_id)) {
            $this->ajaxReturn(500, '请选择网络招聘会');
        }
        $data = model('JobfairOnlineParticipate')
            ->where('jobfair_id',$jobfair_id)
            ->where(['uid' => ['in', $uid]])
            ->field('utype,uid')
            ->group('uid')
            ->select();
        $title = model('JobfairOnline')->where(['id'=>$jobfair_id])->value('title');
        if (empty($title))
        {
            $this->ajaxReturn(500, '网络招聘会编号错误');
        }
        $company = [];
        $member = [];
        foreach($data as $k=>$v)
        {
            if ($v['utype'] == 1)
            {
                $company[] = $v['uid'];
            }else
            {
                $member[] = $v['uid'];
            }
        }
        model('JobfairOnline')->setStatus($jobfair_id,$uid,$audit,$this->admininfo);
        

        $this->ajaxReturn(200, '设置成功');
    }
    // 删除参会企业或个人
    public function participateDelete() {
        $jobfair_id = input('post.jobfair_id/d', 0, 'intval');
        $uid = input('post.uid/a');
        if (empty($jobfair_id)) {
            $this->ajaxReturn(500, '请选择网络招聘会');
        }
        model('JobfairOnline')->participateDelete($jobfair_id,$uid,$this->admininfo);
        $this->ajaxReturn(200, '删除成功');
    }
    // 添加参会企业、个人
    public function participateAdd(){
        $data['jobfair_id'] = input('post.jobfair_id/d',0,'intval');
        $data['uid'] = input('post.uid/d',0,'intval');
        $data['utype'] = input('post.utype/d',1,'intval');
        $data['source'] = 1;
        $data['audit'] = 1;
        $data['qrcode'] = 0;
        $data['stick'] = 0;
        $data['addtime'] = time();
        $reg = model('JobfairOnline')->participateAdd($data);
        $this->ajaxReturn($reg['state']?200:500, $reg['msg']);
    }
    // 批量添加企业
    public function companyBatchAdd() {
        $where = [];
        $jobfair_id = input('post.jobfair_id/d',0,'intval');
        if (empty($jobfair_id)) {
            $this->ajaxReturn(500, '请选择网络招聘会');
        }
        $settr = input('post.settr/d',0,'intval');
        if($settr){
            $where['a.refreshtime']=array('gt',strtotime("-".$settr." day"));
        }
        $audit = input('post.audit/d',0,'intval');
        if($audit){
            $where['a.audit'] = $audit;
        }
        $setmeal_id = input('post.setmeal_id/d',0,'intval');
        if($setmeal_id){
            $where['a.setmeal_id'] = $setmeal_id;
            $where['b.deadline'] = [['gt', time()],['eq', 0],'or'];
        }
        $limit = 100;
        $page = input('post.page/d',1,'intval');
        $pagesize = input('post.pagesize/d',1000,'intval');
        if($page==1){
            $total = model('Company')
                    ->alias('a')
                    ->join(config('database.prefix').'member_setmeal b', 'a.uid=b.uid')
                    ->where('a.uid','NOT IN',function($query) use ($jobfair_id){
                        $query->table(config('database.prefix').'jobfair_online_participate')->where('jobfair_id',$jobfair_id)->field('uid');
                    })
                    ->where($where)
                    ->count();
            if($total==0){
                $this->ajaxReturn(500, '没有符合条件的数据');
            }
        }
        $list = model('Company')
                ->alias('a')
                ->join(config('database.prefix').'member_setmeal b', 'a.uid=b.uid')
                ->where('a.uid','NOT IN',function($query) use ($jobfair_id){
                    $query->table(config('database.prefix').'jobfair_online_participate')->where('jobfair_id',$jobfair_id)->field('uid');
                })
                ->where($where)
                ->field('a.uid')
                ->order('a.id asc')
                ->page($page,$pagesize)
                ->select();
        if(empty($list)){
            $this->ajaxReturn(200, '添加完成',1);
        }
        $counter = 0;
        $insert_data = [];
        foreach ($list as $key => $value) {
            $counter++;
            $post_data['uid'] = $value['uid'];
            $post_data['jobfair_id'] = $jobfair_id;
            $post_data['source'] = 1;
            $post_data['utype'] = 1;
            $post_data['audit'] = 1;
            $post_data['qrcode'] = 0;
            $post_data['stick'] = 0;
            $post_data['addtime'] = time();
            $post_data['note'] = '';
            $insert_data[] = $post_data;
        }
        if(!empty($insert_data)){
            model('JobfairOnlineParticipate')->saveAll($insert_data);
        }
        $this->ajaxReturn(200, '已成功添加了'.$counter.'个企业！',0);
    }
    // 批量添加个人
    public function personalBatchAdd() {
        $where = [];
        $jobfair_id = input('post.jobfair_id/d',0,'intval');
        if (empty($jobfair_id)) {
            $this->ajaxReturn(500, '请选择网络招聘会');
        }
        $settr = input('post.settr/d',0,'intval');
        if($settr){
            $where['refreshtime']=array('gt',strtotime("-".$settr." day"));
        }
        $education = input('post.education/d',0,'intval');
        if($education){
            $where['education'] = $education;
        }
        $experience = input('post.experience/d',0,'intval');
        if ($experience) {
            $tmp_str = '';
            switch ($experience) {
                case 1: //无经验/应届生
                    $tmp_str .= ' or enter_job_time=0';
                    break;
                case 2:
                    $tmp_str .=
                        ' or enter_job_time>' . strtotime('-2 year');
                    break;
                case 3:
                    $tmp_str .=
                        ' or (enter_job_time<=' .
                        strtotime('-2 year') .
                        ' and enter_job_time>' .
                        strtotime('-3 year') .
                        ')';
                    break;
                case 4:
                    $tmp_str .=
                        ' or (enter_job_time<=' .
                        strtotime('-3 year') .
                        ' and enter_job_time>' .
                        strtotime('-4 year') .
                        ')';
                    break;
                case 5:
                    $tmp_str .=
                        ' or (enter_job_time<=' .
                        strtotime('-3 year') .
                        ' and enter_job_time>' .
                        strtotime('-5 year') .
                        ')';
                    break;
                case 6:
                    $tmp_str .=
                        ' or (enter_job_time<=' .
                        strtotime('-5 year') .
                        ' and enter_job_time>' .
                        strtotime('-10 year') .
                        ')';
                    break;
                case 7:
                    $tmp_str .=
                        ' or enter_job_time<=' . strtotime('-10 year');
                    break;
                default:
                    break;
            }
            $tmp_str = trim($tmp_str);
            if (substr($tmp_str, 0, 2) === 'or') {
                $tmp_str = substr($tmp_str, 2);
            }
            $where['enter_job_time'] = $tmp_str;
        }
        $limit = 100;
        $page = input('post.page/d',1,'intval');
        $pagesize = input('post.pagesize/d',1000,'intval');
        if($page==1){
            $total = model('ResumeSearchRtime')->where($where)->count();
            if($total==0){
                $this->ajaxReturn(500, '没有符合条件的数据');
            }
        }
        $list = model('ResumeSearchRtime')->where($where)->field('uid')->order('id asc')->page($page,$pagesize)->select();
        if(empty($list)){
            $this->ajaxReturn(200, '添加完成',1);
        }
        $counter = 0;
        $insert_data = [];
        foreach ($list as $key => $value) {
            $counter++;
            $post_data['uid'] = $value['uid'];
            $post_data['jobfair_id'] = $jobfair_id;
            $post_data['source'] = 1;
            $post_data['utype'] = 2;
            $post_data['audit'] = 1;
            $post_data['qrcode'] = 0;
            $post_data['stick'] = 0;
            $post_data['addtime'] = time();
            $post_data['note'] = '';
            $insert_data[] = $post_data;
        }
        if(!empty($insert_data)){
            model('JobfairOnlineParticipate')->saveAll($insert_data);
        }
        $this->ajaxReturn(200, '已成功添加了'.$counter.'份简历！',0);
    }
    // 网络招聘会导出
    public function getJobfairOnAll(){
        $jobfair = model('JobfairOnline')->field('id,title')->order(['addtime' => 'desc'])->select();
        $this->ajaxReturn(200, '获取数据成功',['items'=>$jobfair]);
    }
}
