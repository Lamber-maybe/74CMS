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
      <el-form-item label="强制企业认证营业执照" required>
        <el-switch v-model="form.must_com_audit_certificate" />
      </el-form-item>
      <el-form-item label="需要认证项目" required>
        <el-radio-group v-model="form.audit_com_project">
          <el-radio label="0">营业执照</el-radio>
          <el-radio label="1">营业执照+经办人身份证+委托函</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="新注册企业认证状态" required>
        <el-radio-group v-model="form.audit_new_com">
          <el-radio label="0">待认证</el-radio>
          <el-radio label="1">认证通过</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="修改企业资料后认证状态" required>
        <el-radio-group v-model="form.audit_edit_com">
          <el-radio label="0">保持不变</el-radio>
          <el-radio label="1">重新认证</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="未认证企业新发布职位审核状态" required>
        <el-radio-group v-model="form.audit_unverifycom_addjob">
          <el-radio label="0">待审核</el-radio>
          <el-radio label="1">审核通过</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="未认证企业修改职位后审核状态" required>
        <el-radio-group v-model="form.audit_unverifycom_editjob">
          <el-radio label="0">保持不变</el-radio>
          <el-radio label="1">重新审核</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="已认证企业新发布职位审核状态" required>
        <el-radio-group v-model="form.audit_verifycom_addjob">
          <el-radio label="0">待审核</el-radio>
          <el-radio label="1">审核通过</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="已认证企业修改职位后审核状态" required>
        <el-radio-group v-model="form.audit_verifycom_editjob">
          <el-radio label="0">保持不变</el-radio>
          <el-radio label="1">重新审核</el-radio>
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
        must_com_audit_certificate: false,
        audit_com_project: 0,
        audit_new_com: 0,
        audit_edit_com: 0,
        audit_verifycom_addjob: 0,
        audit_verifycom_editjob: 0,
        audit_unverifycom_addjob: 0,
        audit_unverifycom_editjob: 0
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
          const {
            audit_new_com,
            audit_edit_com,
            audit_verifycom_addjob,
            audit_verifycom_editjob,
            audit_unverifycom_addjob,
            audit_unverifycom_editjob,
            must_com_audit_certificate,
            audit_com_project
          } = { ...response.data }

          this.form = {
            audit_new_com,
            audit_edit_com,
            audit_verifycom_addjob,
            audit_verifycom_editjob,
            audit_unverifycom_addjob,
            audit_unverifycom_editjob,
            must_com_audit_certificate:
              must_com_audit_certificate == 1,
            audit_com_project
          }
          this.infoLoading = false
        })
        .catch(() => { })
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.must_com_audit_certificate =
            insertData.must_com_audit_certificate === true ? 1 : 0
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
