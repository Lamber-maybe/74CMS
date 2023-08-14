<?php
namespace app\common\model;

class FieldRule extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'enable_close'];
    protected $type = [
        'id' => 'integer',
        'is_require' => 'integer'
    ];
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_field_rule', null);
        });
        self::event('after_delete', function () {
            cache('cache_field_rule', null);
        });
    }
    public function getCache($model_name = '')
    {
        if (false === ($data = cache('cache_field_rule'))) {
            $list = $this->column(
                'model_name,field_name,is_require,is_display,enable_close,field_cn',
                'id'
            );
            $data = [];
            foreach ($list as $key => $value) {
                $data[$value['model_name']][$value['field_name']] = $value;
            }
            cache('cache_field_rule', $data);
        }
        if ($model_name != '') {
            $data = $data[$model_name];
        }
        return $data;
    }
}
