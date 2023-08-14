<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class Profile extends Backend
{
    /**
     * 上传企业风采
     */
    public function uploadImg()
    {
        $file = input('file.file');
        $uid = input('post.uid/d',0,'intval');
        $company_id = input('post.company_id/d',0,'intval');
        if (empty($file) || empty($uid) || empty($company_id)) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $count = model('CompanyImg')
            ->where('uid', $uid)
            ->count();
        if ($count >= 6) {
            $this->ajaxReturn(500, '最多上传6张风采');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->upload($file);
        if (false !== $result) {
            $img['uid'] = $uid;
            $img['comid'] = $company_id;
            $img['img'] = $result['file_id'];
            $img['title'] = '';
            $img['addtime'] = time();
            $img['audit'] = 1;
            model('CompanyImg')->save($img);
            $result['audit'] = 1;
            $result['audit_text'] = model('CompanyImg')->map_audit[$result['audit']];
            $result['id'] = model('CompanyImg')->id;
            $img_list = $this->getCompanyImg($company_id);
            cache('scan_upload_result_company_img_'.$uid,json_encode($img_list));

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
        $uid = input('post.uid/d',0,'intval');
        $id = input('post.id/d', 0, 'intval');
        $company_id = input('post.company_id/d',0,'intval');
        if (empty($id) || empty($uid) || empty($company_id)) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CompanyImg')->destroy($id);
        $img_list = $this->getCompanyImg($company_id);
        cache('scan_upload_result_company_img_'.$uid,json_encode($img_list));

        $this->ajaxReturn(200, '删除成功');
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
}