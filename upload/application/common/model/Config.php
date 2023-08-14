<?php

namespace app\common\model;

class Config extends BaseModel
{
    protected $readonly = [
        'id',
        'name',
        'note'
    ];
    protected $type = [
        'id' => 'integer',
    ];

    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_config_frontend', null);
            cache('cache_config', null);
            cache('cache_config_all', null);
        });
        self::event('after_delete', function () {
            cache('cache_config_frontend', null);
            cache('cache_config', null);
            cache('cache_config_all', null);
        });
    }

    public function getCache($name = '')
    {
        if (false === ($data = cache('cache_config'))) {
            $data = $this->where('is_secret', 0)->column('name,value');
            foreach ($data as $key => $value) {
                if (is_json($value)) {
                    $data[$key] = json_decode($value, true);
                }
            }
            cache('cache_config', $data);
        }
        if ($name != '') {
            $data = $data[$name];
        }
        return $data;
    }

    public function getFrontendCache($name = '')
    {
        if (false === ($data = cache('cache_config_frontend'))) {
            $data = $this->where('is_frontend', 1)->column('name,value');
            foreach ($data as $key => $value) {
                if (is_json($value)) {
                    $data[$key] = json_decode($value, true);
                }
            }
            cache('cache_config_frontend', $data);
        }
        if ($name != '') {
            $data = $data[$name];
        }
        return $data;
    }

    public function getCacheAll($name = '')
    {
        if (false === ($data = cache('cache_config_all'))) {
            $data = $this->column('name,value');
            foreach ($data as $key => $value) {
                if (is_json($value)) {
                    $data[$key] = json_decode($value, true);
                }
            }
            cache('cache_config_all', $data);
        }
        if ($name != '') {
            $data = $data[$name];
        }
        return $data;
    }

    public function saveConfig($config, $adminInfo)
    {
        $configField = config('configField');
        if (!isset($configField) || empty($configField)) {
            throw new \Exception('缺少配置项文件');
        }

        $configList = model('Config')->column('id,name,value', 'name');
        if (empty($configList)) {
            throw new \Exception('系统配置项为空');
        }

        $sqlData = [];
        $logData = [];
        foreach ($config as $config_name => $config_value) {
            if (!isset($configField[$config_name]) || empty($configField[$config_name])) {
                continue;
            }
            if (!isset($configList[$config_name]) || empty($configList[$config_name])) {
                continue;
            }

            $fieldInfo = $configField[$config_name];
            $menu_zh = $fieldInfo['menu_zh'];
            $menu_path = implode($menu_zh, '-');
            $configInfo = $configList[$config_name];
            $old_value = $configInfo['value'];

            if (is_array($config_value)) {
                $new_value = json_encode($config_value, JSON_UNESCAPED_UNICODE);
            } else {
                $new_value = $config_value;
            }
            $sqlData[] = [
                'id' => $configInfo['id'],
                'name' => $config_name,
                'value' => $new_value
            ];

            // 是否记录LOG 0:不记录|1:记录
            $is_log = isset($fieldInfo['is_log']) ? intval($fieldInfo['is_log']) : 0;
            // json格式配置项
            $field_json = !empty($fieldInfo['is_json']) ? $fieldInfo['is_json'] : 0;
            // 数组格式配置项
            $field_foreach = !empty($fieldInfo['is_foreach']) ? $fieldInfo['is_foreach'] : 0;

            if ($new_value != $old_value && $is_log === 1) {
                if ($field_json) {
                    $old_arr = json_decode($old_value, true);
                    $new_arr = json_decode($new_value, true);
                    foreach ($new_arr as $config_key => $config_value) {
                        if (!isset($field_json[$config_key]) || empty($field_json[$config_key])) {
                            continue;
                        }
                        $field_json_config = $field_json[$config_key];
                        $config_old = $old_arr[$config_key];
                        // 配置项单位
                        $unit = !empty($field_json_config['unit']) ? $field_json_config['unit'] : '';
                        if (isset($field_json_config['map']) && !empty($field_json_config['map'])) {
                            $config_value = isset($field_json_config['map'][$config_value]) ? $field_json_config['map'][$config_value] : '未知';
                            $config_old = isset($field_json_config['map'][$config_old]) ? $field_json_config['map'][$config_old] : '未知';
                        }
                        $logData[] = $field_json_config['name'] . '：'
                            . $config_old
                            . '->'
                            . $config_value
                            . $unit;
                    }
                } elseif ($field_foreach) {
                    $old_arr = json_decode($old_value, true);
                    $old_config = array_column($old_arr, null, $field_foreach['pk']);
                    $new_arr = json_decode($new_value, true);
                    $new_config = array_column($new_arr, null, $field_foreach['pk']);

                    $logChild = [];

                    foreach ($new_config as $n_key => $n_config) {
                        if (!isset($old_config[$n_key]) || empty($old_config[$n_key])) {
                            continue;
                        }

                        $o_value = $old_config[$n_key][$field_foreach['value']];
                        $n_value = $n_config[$field_foreach['value']];
                        $unit = !empty($field_foreach['unit']) ? $field_foreach['unit'] : '';

                        if ($n_value != $o_value) {
                            $logChild[] = $n_config[$field_foreach['name']]
                                . ':'
                                . $o_value
                                . '->'
                                . $n_value
                                . $unit;
                        }
                    }

                    $logData[] = $fieldInfo['field_name'] . '：' . implode($logChild, ';');
                } else {
                    // 配置项单位
                    $unit = !empty($fieldInfo['unit']) ? $fieldInfo['unit'] : '';
                    if (isset($fieldInfo['map']) && !empty($fieldInfo['map'])) {
                        $old_value = isset($fieldInfo['map'][$old_value]) ? $fieldInfo['map'][$old_value] : '未知';
                        $new_value = isset($fieldInfo['map'][$new_value]) ? $fieldInfo['map'][$new_value] : '未知';
                    }
                    if (isset($fieldInfo['correlation']) && !empty($fieldInfo['correlation'])) {
                        $correlation = $fieldInfo['correlation'];
                        $old_value = model($correlation['model'])
                            ->where($correlation['pk'], $correlation['condition'], $old_value)
                            ->column($correlation['value']);
                        $old_value = implode($old_value, $correlation['implode']);
                        $new_value = model($correlation['model'])
                            ->where($correlation['pk'], $correlation['condition'], $new_value)
                            ->column($correlation['value']);
                        $new_value = implode($new_value, $correlation['implode']);
                    }
                    $logData[] = $fieldInfo['field_name'] . '：'
                        . $old_value
                        . '->'
                        . $new_value
                        . $unit;
                }
            }
        }

        $save_result = $this
            ->isUpdate()
            ->saveAll($sqlData);
        if (false === $save_result) {
            throw new \Exception($this->getError());
        }

        // 日志
        $log_field = '修改' . $menu_path . '，' . implode($logData, '；');
        $log_result = model('AdminLog')->writeLog(
            $log_field,
            $adminInfo,
            0,
            3
        );
        if (false === $log_result) {
            throw new \Exception(model('AdminLog')->getError());
        }
    }
}
