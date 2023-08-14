<?php
namespace app\common\model;

class Ad extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type = [
        'id' => 'integer',
        'cid' => 'integer',
        'is_display' => 'integer',
        'click' => 'integer',
        'addtime' => 'integer',
        'sort_id' => 'integer',
        'starttime' => 'integer',
        'deadline' => 'integer',
        'uid' => 'integer',
        'imageid' => 'integer'
    ];
    protected $insert = ['addtime'];
    protected function setAddtimeAttr()
    {
        return time();
    }
    public $innerLinks = [
        ['value' => 'index', 'label' => '首页'],
        ['value' => 'joblist', 'label' => '职位列表页'],
        ['value' => 'jobshow', 'label' => '职位详情页'],
        ['value' => 'resumelist', 'label' => '简历列表页'],
        ['value' => 'articlelist', 'label' => '资讯列表页'],
        ['value' => 'articleshow', 'label' => '资讯详情页'],
        ['value' => 'noticeshow', 'label' => '公告详情页']
    ];
    /**
     * 处理链接
     */
    public function handlerWebLink($item,$domain=''){
        if($item['link_url']!=''){
            return $item['link_url'];
        }else if($item['company_id']>0){
            return $domain.url('index/company/show',['id'=>$item['company_id']]);
        }else if($item['inner_link']!=''){
            $path = '';
            switch ($item['inner_link']) {
                case 'index':
                    $path = 'index/index/index';
                    break;
                case 'joblist':
                    $path = 'index/job/index';
                    break;
                case 'jobshow':
                    $path = 'index/job/show';
                    break;
                case 'resumelist':
                    $path = 'index/resume/index';
                    break;
                case 'noticeshow':
                    $path = 'index/notice/show';
                    break;
                case 'articlelist':
                    $path = 'index/article/index';
                    break;
                case 'articleshow':
                    $path = 'index/article/show';
                    break;
                default:
                    $path = '';
                    break;
            }
            if ($path != '') {
                if ($item['inner_link_params'] > 0) {
                    $path = url($path,['id'=>$item['inner_link_params']]);
                }else{
                    $path = url($path);
                }
                return $domain.$path;
            }else{
                return '';
            }
        }
    }
}
