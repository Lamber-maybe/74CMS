<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;

class Company extends BaseModel
{
    public function backendEdit($data)
    {
        $company_id = $data['id'];

        $company_info_before_save = $this->find($company_id);

        //开启事务
        \think\Db::startTrans();
        try {
            if (
                false ===
                model('Company')
                    ->validate('Company.edit')
                    ->allowField(true)
                    ->save($data, ['id' => $company_id])
            ) {
                throw new \Exception(model('Company')->getError());
            }


            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        //更新职位索引表对应字段，如企业名称，企业性质等
        $update_search_table = [];
        if (
            $company_info_before_save['companyname'] !=
            $data['companyname']
        ) {
            $update_search_table['companyname'] = $data['companyname'];
        }
        if ($company_info_before_save['nature'] != $data['nature']) {
            $category_nature = model('Category')->getCache('QS_company_type');
            $update_search_table['company_nature_id'] = $data['nature'];
            $update_search_table['company_nature'] = isset($category_nature[$data['nature']]) ? $category_nature[$data['nature']] : '';
        }
        if (!empty($update_search_table)) {
            model('JobSearchKey')->save($update_search_table, [
                'company_id' => $company_id
            ]);
        }

        return true;
    }
}