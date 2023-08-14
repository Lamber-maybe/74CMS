<template>
  <div class="app-container">
    <el-form
      v-loading="infoLoading"
      class="common-form"
      label-width="0"
      :inline-message="true"
    >
      <el-form-item>
        <el-table
          border
          :data="list"
        >
          <el-table-column label="通知内容" min-width="120">
            <template slot-scope="scope">
              {{ scope.row.title }}
            </template>
          </el-table-column>
          <el-table-column label="通知类型">
            <template slot-scope="scope">
              {{ scope.row.type_cn }}
            </template>
          </el-table-column>
          <el-table-column label="通知内容" min-width="450">
            <template slot-scope="scope">
              {{ scope.row.content }}
            </template>
          </el-table-column>
          <el-table-column align="center" label="站内信">
            <template slot-scope="scope">
              <el-switch
                v-if="scope.row.open_message != -1"
                v-model="scope.row.open_message"
              />
              <span v-else>-</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="短信">
            <template slot-scope="scope">
              <el-switch
                v-if="scope.row.open_sms != -1"
                v-model="scope.row.open_sms"
              />
              <span v-else>-</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="邮件">
            <template slot-scope="scope">
              <el-switch
                v-if="scope.row.open_email != -1"
                v-model="scope.row.open_email"
              />
              <span v-else>-</span>
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
import { setNotifyRule, getNotifyRule } from '@/api/configuration'

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
        utype: that.listtype == 'company' ? 1 : 2
      }

      getNotifyRule(params, 'get')
        .then(response => {
          const data = [...response.data]

          data.forEach(function(val, index, arr) {
            val.open_message =
              val.open_message == -1
                ? -1
                : val.open_message == 1
            val.open_sms =
              val.open_sms == -1 ? -1 : val.open_sms == 1
            val.open_email =
              val.open_email == -1 ? -1 : val.open_email == 1
            that.list.push(val)
          })
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = []
      this.list.forEach(function(val, index, arr) {
        const tmp_val = {
          id: val.id,
          open_message:
            val.open_message == -1 ? -1 : val.open_message === true ? 1 : 0,
          open_sms: val.open_sms == -1 ? -1 : val.open_sms === true ? 1 : 0,
          open_email:
            val.open_email == -1 ? -1 : val.open_email === true ? 1 : 0
        }
        insertData[index] = tmp_val
      })

      setNotifyRule(insertData)
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
