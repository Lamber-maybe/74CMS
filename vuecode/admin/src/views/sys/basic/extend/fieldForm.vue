<template>
  <div class="app-container">
    <el-form
      v-loading="infoLoading"
      class="common-form"
      label-width="0"
      :inline-message="true"
    >
      <el-form-item>
        <el-table border :data="list">
          <el-table-column label="字段名">
            <template slot-scope="scope">
              <span v-if="false === scope.row.is_custom">
                {{ scope.row.field_cn }}
              </span>
              <el-form-item v-else>
                <el-input v-model="scope.row.field_cn" style="width:120px" maxlength="4" />
              </el-form-item>
            </template>
          </el-table-column>
          <el-table-column align="center" label="是否显示">
            <template slot-scope="scope">
              <div v-if="false === scope.row.enable_close">
                <span style="color:#67C23A">显示</span>
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="系统不允许关闭此项"
                  placement="top-start"
                >
                  <i class="el-icon-question" />
                </el-tooltip>
              </div>
              <el-switch v-else v-model="scope.row.is_display" />
            </template>
          </el-table-column>
          <el-table-column align="center" label="是否必填">
            <template slot-scope="scope">
              <el-switch v-model="scope.row.is_require" />
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
import { setFieldRule } from '@/api/configuration'

export default {
  props: ['listtype'],
  data() {
    return {
      infoLoading: true,
      list: []
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const that = this
      const params = {
        type: this.listtype
      }

      setFieldRule(params, 'get')
        .then(response => {
          const data = [...response.data]
          data.forEach(function(val, index, arr) {
            val.is_display = val.is_display == 1
            val.is_require = val.is_require == 1
            val.enable_close = val.enable_close == 1
            val.is_custom = val.is_custom == 1
            that.list.push(val)
          })
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      let hasError = false
      const insertData = []
      this.list.forEach(function(val, index, arr) {
        const tmp_val = {
          id: val.id,
          field_cn: val.field_cn,
          is_display: val.is_display === true ? 1 : 0,
          is_require: val.is_require === true ? 1 : 0
        }
        if (tmp_val.field_cn == '') {
          that.$message.error('字段名不能为空')
          hasError = true
          return false
        }
        insertData[index] = tmp_val
      })

      if (hasError === true) {
        return false
      }
      setFieldRule(insertData)
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
</style>
