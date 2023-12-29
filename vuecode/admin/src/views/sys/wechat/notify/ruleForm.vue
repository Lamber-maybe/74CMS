<template>
  <div class="app-container">
    <el-form
      v-loading="infoLoading"
      label-width="0"
      :inline-message="true"
    >
      <el-form-item>
        <el-table border :data="dataset">
          <el-table-column align="center" label="通知内容" min-width="130">
            <template slot-scope="scope">
              {{ scope.row.title }}
            </template>
          </el-table-column>
          <el-table-column align="center" label="模板名称" min-width="130">
            <template slot-scope="scope">
              {{ scope.row.tpl_name }}
            </template>
          </el-table-column>
          <el-table-column align="center" label="所属行业" min-width="180">
            <template slot-scope="scope">
              {{ scope.row.tpl_trade }}
            </template>
          </el-table-column>
          <el-table-column align="center" label="模板编号" min-width="170">
            <template slot-scope="scope">
              {{ scope.row.tpl_number }}
            </template>
          </el-table-column>
          <el-table-column align="center" label="开启/关闭">
            <template slot-scope="scope">
              <el-switch v-model="scope.row.is_open" />
            </template>
          </el-table-column>
          <el-table-column align="center" label="模板ID" min-width="420">
            <template slot-scope="scope">
              <el-input v-model="scope.row.tpl_id" placeholder="请输入模板ID" />
            </template>
          </el-table-column>
        </el-table>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import {
    setWechatNotifyRule,
    getWechatNotifyRule
  } from '@/api/configuration'

  export default {
    props: ['dataset','infoLoading'],
    data() {
      return {
      }
    },
    methods: {
      onSubmit(formName) {
        const insertData = []
        this.dataset.forEach(function(val, index, arr) {
          const tmp_val = {
            id: val.id,
            is_open: val.is_open == -1 ? -1 : val.is_open === true ? 1 : 0,
            tpl_id: val.tpl_id
          }
          insertData[index] = tmp_val
        })

        setWechatNotifyRule(insertData)
          .then(response => {
            this.$message.success(response.message)
            return true
          })
          .catch(() => {})
      }
    }
  }
</script>

<style scoped>
  .el-icon-question {
    font-size: 16px;
  }

  .el-form-item {
    width: 100%;
    max-width: 100%;
  }
</style>
