<?php
namespace app\common\model;

class CompanyImg extends \app\common\model\BaseModel
{
    public $map_audit = [
        0 => '待审核',
        1 => '审核通过',
        2 => '审核未通过'
    ];
    protected $readonly = ['id', 'comid', 'uid'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'comid' => 'integer',
        'addtime' => 'integer',
        'audit' => 'integer'
    ];
    public function getList($company_id)
    {
        $list = model('CompanyImg')
            ->alias('a')
            ->join(
                config('database.prefix') . 'uploadfile b',
                'a.img=b.id',
                'LEFT'
            )
            ->field('b.save_path,b.platform,a.title')
            ->where('a.comid', $company_id)
            ->where('a.audit', 1)
            ->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['title'] = $value['title'];
            $arr['img_src'] = make_file_url(
                $value['save_path'],
                $value['platform']
            );
            $return[] = $arr;
        }
        return $return;
    }
}
