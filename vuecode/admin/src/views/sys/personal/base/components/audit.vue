<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="220px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="新注册简历审核状态" required>
        <el-radio-group v-model="form.audit_add_resume">
          <el-radio label="1">审核通过</el-radio>
          <el-radio label="0">待审核</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="修改简历后审核状态" required>
        <el-radio-group v-model="form.audit_edit_resume">
          <el-radio label="0">保持不变</el-radio>
          <el-radio label="1">待审核</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        audit_add_resume: 0,
        audit_edit_resume: 0
      },
      rules: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          this.form.audit_add_resume = response.data.audit_add_resume
          this.form.audit_edit_resume = response.data.audit_edit_resume
          this.infoLoading = false
        })
        .catch(() => { })
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => { })
        } else {
          return false
        }
      })
    }
  }
}
</script>
