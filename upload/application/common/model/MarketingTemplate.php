<?php
/**
 * @Table : `marketing_template`
 * @Purpose : 公众号营销模板
 */

namespace app\common\model;

class MarketingTemplate extends BaseModel
{
    protected $pk = 'id';

    protected $readonly = [
        'id'
    ];

    protected $type = [
        'id' => 'integer'
    ];

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * @Purpose
     * 头部模板参数[head]
     * @var \string[][]
     */
    public $headOption = [
        ['value' => 'site_name', 'label' => '网站名称', 'item' => 'common', 'flag' => ''],
        ['value' => 'site_domain', 'label' => '网站地址', 'item' => 'common', 'flag' => ''],
        ['value' => 'date', 'label' => '当期日期', 'item' => 'common', 'flag' => '']
    ];

    /**
     * @Purpose
     * 正文模板参数[body]
     * @var \string[][]
     */
    public $bodyOption = [
        // 职位模板
        'job' => [
            ['value' => 'job_name', 'label' => '职位名称', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_name', 'label' => '企业名称', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_link', 'label' => '企业H5链接', 'item' => 'items', 'flag' => ''],
            ['value' => 'job_wage', 'label' => '薪资待遇', 'item' => 'items', 'flag' => ''],
            ['value' => 'job_amount', 'label' => '招聘人数', 'item' => 'items', 'flag' => ''],
            ['value' => 'job_age', 'label' => '年龄要求', 'item' => 'items', 'flag' => ''],
            ['value' => 'experience_text', 'label' => '经验要求', 'item' => 'items', 'flag' => ''],
            ['value' => 'education_text', 'label' => '学历要求', 'item' => 'items', 'flag' => ''],
            ['value' => 'job_address', 'label' => '地址', 'item' => 'items', 'flag' => ''],
            ['value' => 'job_tag', 'label' => '职位福利', 'item' => 'items', 'flag' => 'each'],
            ['value' => 'job_content', 'label' => '职位描述', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_wx_qrcode', 'label' => '企业H5场景码', 'item' => 'items', 'flag' => ''],
            ['value' => 'job_wx_qrcode', 'label' => '职位H5场景码', 'item' => 'items', 'flag' => ''],
            ['value' => 'job_link', 'label' => '职位H5链接', 'item' => 'items', 'flag' => ''],
            ['value' => 'site_name', 'label' => '网站名称', 'item' => 'common', 'flag' => ''],
            ['value' => 'site_domain', 'label' => '网站地址', 'item' => 'common', 'flag' => ''],
            ['value' => 'date', 'label' => '当期日期', 'item' => 'common', 'flag' => '']
        ],

        // 企业模板
        'company' => [
            ['value' => 'company_name', 'label' => '企业名称', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_contact', 'label' => '企业联系人', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_mobile', 'label' => '企业联系电话', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_tag', 'label' => '企业福利', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_address', 'label' => '企业联系地址', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_wx_qrcode', 'label' => '企业H5场景码', 'item' => 'items', 'flag' => ''],
            ['value' => 'company_link', 'label' => '企业H5链接', 'item' => 'items', 'flag' => ''],
            ['value' => 'site_name', 'label' => '网站名称', 'item' => 'common', 'flag' => ''],
            ['value' => 'site_domain', 'label' => '网站地址', 'item' => 'common', 'flag' => ''],
            ['value' => 'date', 'label' => '当期日期', 'item' => 'common', 'flag' => ''],
            ['value' => '', 'label' => '企业职位', 'item' => '', 'flag' => 'list'],
//            ['value' => 'company_website', 'label' => '企业官网', 'item' => 'items', 'flag' => ''],

            // 以下内容仅支持在“企业职位”标签中插入
            ['value' => 'job_name', 'label' => '职位名称', 'item' => 'items.job_list', 'flag' => ''],
            ['value' => 'job_wage', 'label' => '职位薪资', 'item' => 'items.job_list', 'flag' => ''],
            ['value' => 'job_amount', 'label' => '招聘人数', 'item' => 'items.job_list', 'flag' => ''],
            ['value' => 'job_content', 'label' => '职位职责', 'item' => 'items.job_list', 'flag' => ''],
            ['value' => 'education_text', 'label' => '职位学历要求', 'item' => 'items.job_list', 'flag' => ''],
            ['value' => 'experience_text', 'label' => '职位经验要求', 'item' => 'items.job_list', 'flag' => ''],
            ['value' => 'job_wx_qrcode', 'label' => '职位H5场景码', 'item' => 'items.job_list', 'flag' => ''],
            ['value' => 'job_link', 'label' => '职位H5链接', 'item' => 'items.job_list', 'flag' => ''],
        ]

    ];

    /**
     * @Purpose
     * 尾部模板参数[tail]
     * @var \string[][]
     */
    public $tailOption = [
        ['value' => 'site_name', 'label' => '网站名称', 'item' => 'common', 'flag' => ''],
        ['value' => 'site_domain', 'label' => '网站地址', 'item' => 'common', 'flag' => ''],
        ['value' => 'date', 'label' => '当期日期', 'item' => 'common', 'flag' => '']
    ];

    /**
     * @Purpose:
     * 获取数据总条数
     * @Method getDataNum()
     *
     * @param array $map 查询条件
     *
     * @return false|int|string
     */
    public function getDataNum($map)
    {
        if (!is_array($map)) {
            return false;
        }

        return $this->field($this->pk)
            ->where($map)
            ->count($this->pk);
    }


    /**
     * @Purpose:
     * 获取分页列表
     * @Method getList()
     *
     * @param $map
     * @param $order
     * @param $page_num
     * @param $page_size
     * @param string $field
     *
     * @return array|false
     */
    public function getList($map, $order, $page_num, $page_size, $field = '*')
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this->getDataNum($map);
        if (empty($total)) {
            return array();
        }
        if (empty($page_size) || $page_size > 100 || $page_size < 1) {
            $page_size = 10;
            $limit_size = 10;
        } else {
            $limit_size = (int)$page_size;
        }
        $total_page = ceil($total / $page_size);
        if ($page_num > $total_page) {
            return array();
        }
        if (empty($page_num) || $page_num < 1) {
            $page_num = 1;
            $start = 0;
        } else {
            $start = (int)$page_num - 1;
        }
        $limit_start = $start * $limit_size;
        $data = $this->field($field)
            ->where($map)
            ->order($order)
//            ->limit($limit_start, $limit_size)
            ->select();

        if ($data->isEmpty()) {
            return array();
        }
        return [
            'rows' => $data->toArray(),
            'pages' => [
                'now_page' => $page_num,
                'total_page' => $total_page,
                'record_num' => $total
            ]
        ];
    }
}