<?php

namespace app\apiadmin\controller;

use think\Db;

class ConfigDistrict extends \app\common\controller\Backend
{
    public function index()
    {
        $pid = input('get.pid/d', 0, 'intval');
        $list = model('CategoryDistrict')
            ->where('pid', $pid)
            ->order('sort_id desc,id asc')
            ->select();
        foreach ($list as $key => $value) {
            $children = model('CategoryDistrict')->getCache($value['id']);
            $list[$key]['hasChildren'] = $children ? true : false;
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    public function options()
    {
        $list = model('CategoryDistrict')->getCache('0');
        $return = [];
        foreach ($list as $key => $value) {
            $arr = [];
            $arr['value'] = $key;
            $arr['label'] = $value;
            $arr['level'] = 1;
            $children = model('CategoryDistrict')->getCache($key);
            if ($children) {
                foreach ($children as $k => $v) {
                    $arr['children'][] = [
                        'value' => $k,
                        'label' => $v,
                        'level' => 2
                    ];
                }
            }
            $return[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function add()
    {
        $input_data = [
            'parentid' => input('post.parentid/a'),
            'name' => input('post.name/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'level' => input('post.level/d', 0, 'intval')
        ];
        $input_data['pid'] =
            isset($input_data['parentid']) && is_array($input_data['parentid'])
                ? (!empty($input_data['parentid'])
                ? end($input_data['parentid'])
                : 0)
                : 0;
        $parentIds = $input_data['parentid'];
        unset($input_data['parentid']);

        try {
            $catName = '顶级';
            if (isset($parentIds[0]) && !empty($parentIds[0])) {
                $catName = model('CategoryDistrict')
                    ->where('id', $parentIds[0])
                    ->value('name');
                if (empty($catName)) {
                    $this->ajaxReturn(500, '上级分类信息异常');
                }
                if (isset($parentIds[1]) && !empty($parentIds[1])) {
                    $pName = model('CategoryDistrict')
                        ->where('id', $parentIds[1])
                        ->value('name');
                    if (empty($pName)) {
                        $this->ajaxReturn(500, '上级分类信息异常');
                    }
                    $catName .= '/' . $pName;
                }
            }

            // 开启事务
            Db::startTrans();

            $result = model('CategoryDistrict')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if (false === $result) {
                throw new \Exception(model('CategoryDistrict')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-地区分类，添加分类，所属分类:' . $catName
                . '；名称:' . $input_data['name']
                . '(ID:' . model('CategoryDistrict')->id
                . ')；排序:' . $input_data['sort_id'];
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('CategoryDistrict')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            if ($info['pid'] > 0) {
                $parent = model('CategoryDistrict')
                    ->where('id', $info['pid'])
                    ->column('pid');
                if ($parent[0] > 0) {
                    $info['parentid'] = [$parent[0], $info['pid']];
                } else {
                    $info['parentid'] = [$info['pid']];
                }
            } else {
                $info['parentid'] = [];
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'parentid' => input('post.parentid/a'),
                'name' => input('post.name/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval'),
                'level' => input('post.level/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $input_data['pid'] =
                isset($input_data['parentid']) &&
                is_array($input_data['parentid'])
                    ? (!empty($input_data['parentid'])
                    ? end($input_data['parentid'])
                    : 0)
                    : 0;
            $parentIds = $input_data['parentid'];
            unset($input_data['parentid']);

            try {
                $info = model('CategoryDistrict')->find($id);
                if (!$info) {
                    $this->ajaxReturn(500, '要修改的地区分类不存在');
                }
                if ($info['pid'] > 0) {
                    $pInfo = model('CategoryDistrict')
                        ->field('id,pid,name')
                        ->find($info['pid']);
                    if (null === $pInfo) {
                        $this->ajaxReturn(500, '上级分类信息异常');
                    }
                    $odlCatName = $pInfo['name'];

                    if ($pInfo['pid'] > 0) {
                        $gpInfo = model('CategoryDistrict')
                            ->field('id,pid,name')
                            ->find($pInfo['pid']);
                        if (null === $gpInfo) {
                            $this->ajaxReturn(500, '上级分类信息异常');
                        }
                        $odlCatName = $gpInfo['name'] . '/' . $odlCatName;
                    }
                } else {
                    $odlCatName = '顶级';
                }

                // 开启事务
                Db::startTrans();

                $result = model('CategoryDistrict')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
                if (false === $result) {
                    throw new \Exception(model('CategoryDistrict')->getError());
                }

                // 日志
                $log_field = '系统-分类配置-地区分类，修改分类，所属分类:' . $odlCatName;

                if ($input_data['pid'] != $info['pid']) {
                    $catName = '顶级';
                    if (isset($parentIds[0]) && !empty($parentIds[0])) {
                        $catName = model('CategoryDistrict')
                            ->where('id', $parentIds[0])
                            ->value('name');
                        if (empty($catName)) {
                            $this->ajaxReturn(500, '上级分类信息异常');
                        }
                        if (isset($parentIds[1]) && !empty($parentIds[1])) {
                            $pName = model('CategoryDistrict')
                                ->where('id', $parentIds[1])
                                ->value('name');
                            if (empty($pName)) {
                                $this->ajaxReturn(500, '上级分类信息异常');
                            }
                            $catName .= '/' . $pName;
                        }
                    }
                    $log_field .= '->' . $catName;
                }
                $log_field .= '；名称:' . $info['name'];

                if ($input_data['name'] != $info['name']) {
                    $log_field .= '->' . $input_data['name'];
                }

                $log_field .= '(ID:' . $info['id'] . ')';
                if ($input_data['sort_id'] != $info['sort_id']) {
                    $log_field .= ')；排序:' . $info['sort_id'] . '->' . $input_data['sort_id'];
                }

                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    3
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $list = model('CategoryDistrict')
                ->whereIn('id', $id)
                ->select();
            if (null === $list) {
                $this->ajaxReturn(500, '没有要删除的地区分类');
            }

            $del_list = [];

            foreach ($list as $one) {
                if ($one['pid'] > 0) {
                    $pInfo = model('CategoryDistrict')
                        ->field('id,pid,name')
                        ->find($one['pid']);
                    if (null === $pInfo) {
                        $this->ajaxReturn(500, '上级分类信息异常');
                    }
                    $catName = $pInfo['name'];

                    if ($pInfo['pid'] > 0) {
                        $gpInfo = model('CategoryDistrict')
                            ->field('id,pid,name')
                            ->find($pInfo['pid']);
                        if (null === $gpInfo) {
                            $this->ajaxReturn(500, '上级分类信息异常');
                        }
                        $catName = $gpInfo['name'] . '/' . $catName;
                    }
                    $del_list[] = '所属分类:' . $catName . ';名称:' . $one['name'] . '(ID:' . $one['id'] . ')';
                } else {
                    $del_list[] = '所属分类:顶级;名称:' . $one['name'] . '(ID:' . $one['id'] . ')';
                }
            }

            $del_total = model('CategoryDistrict')
                ->whereIn('id', $id)
                ->count('id');

            $children = model('CategoryDistrict')
                ->where('pid', 'in', $id)
                ->select();

            // 开启事务
            Db::startTrans();

            if ($children) {
                $child_ids = [];
                foreach ($children as $key => $value) {
                    $child_ids[] = $value['id'];
                }
                $del_total += model('CategoryDistrict')
                    ->where('pid', 'in', $child_ids)
                    ->count('id');
                $c_del = model('CategoryDistrict')
                    ->where('pid', 'in', $child_ids)
                    ->delete();
                if (false === $c_del) {
                    throw new \Exception(model('CategoryDistrict')->getError());
                }

                $del_total += model('CategoryDistrict')
                    ->where('pid', 'in', $id)
                    ->count('id');
                $p_del = model('CategoryDistrict')
                    ->where('pid', 'in', $id)
                    ->delete();
                if (false === $p_del) {
                    throw new \Exception(model('CategoryDistrict')->getError());
                }

            }
            $g_del = model('CategoryDistrict')->destroy($id);
            if (false === $g_del) {
                throw new \Exception(model('CategoryDistrict')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-地区分类，删除分类，' . implode($del_list, '；') . '；共删除' . $del_total . '个分类';
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function tablesave()
    {
        $inputdata = input('post.');
        if (!$inputdata) {
            $this->ajaxReturn(500, '提交数据为空');
        }
        $sqldata = [];
        foreach ($inputdata as $key => $value) {
            if (!$value['id']) {
                continue;
            }
            $arr['id'] = $value['id'];
            $arr['sort_id'] = $value['sort_id'] == '' ? 0 : $value['sort_id'];
            $arr['pid'] = $value['pid'];
            $arr['name'] = $value['name'];
            $arr['level'] = $value['level'];
            $sqldata[] = $arr;
        }
        $validate = \think\Loader::validate('CategoryDistrict');
        foreach ($sqldata as $key => $value) {
            if (!$validate->check($value)) {
                $this->ajaxReturn(500, $validate->getError());
            }
        }

        try {
            Db::startTrans();

            $result = model('CategoryDistrict')
                ->isUpdate()
                ->saveAll($sqldata);
            if (false === $result) {
                throw new \Exception(model('CategoryDistrict')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '系统-分类配置-地区分类，批量保存地区分类',
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }
}
