<template>
  <div
    class="app-container"
  >
    <el-form
      ref="form"
      class="common-form"
      label-width="240px"
      :inline-message="true"
    >
      <el-form-item label="强制关注公众号">
        <el-switch v-model="bind_weixin" />
      </el-form-item>
      <el-form-item label="个人聊天简历完整度限制" prop="complete_percent">
        <el-input v-model.number="complete_percent">
          <template slot="append">%</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          0表示不限制
        </span>
      </el-form-item>
      <el-form-item label="个人简历状态限制">
        <el-radio-group v-model="audit_status">
          <el-radio label="1">审核通过</el-radio>
          <el-radio label="3">不限</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item
        label="个人发起次数"
        prop="im_per_launch_time"
      >
        <el-input v-model.number="max_per_day">
          <template slot="append">次/天</template>
        </el-input>
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setImRule } from '@/api/im'

export default {
  data() {
    return {
      infoLoading: false,
      bind_weixin: false,
      complete_percent: '',
      audit_status: '',
      max_per_day: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.infoLoading = true
      const param = { utype: 2 }
      setImRule(param, 'get')
        .then(responses => {
          const { bind_weixin, complete_percent, audit_status, max_per_day } = { ...responses.data }
          this.bind_weixin = bind_weixin === '1'
          this.complete_percent = complete_percent
          this.audit_status = audit_status
          this.max_per_day = max_per_day
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        bind_weixin: this.bind_weixin ? '1' : '0',
        complete_percent: this.complete_percent,
        audit_status: this.audit_status,
        max_per_day: this.max_per_day,
        utype: 2
      }
      setImRule(insertData)
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
.el-input,
.el-input-group {
  width: 50%;
}
</style>
