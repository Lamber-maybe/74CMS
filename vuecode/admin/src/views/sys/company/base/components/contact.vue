<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="web端查看联系方式条件" required>
        <el-radio-group v-model="form.showjobcontact">
          <el-radio label="0">游客</el-radio>
          <el-radio label="1">已登录</el-radio>
          <el-radio label="2">已登录有简历</el-radio>
          <el-radio label="3">投递后显示</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="移动端查看联系方式条件" required>
        <el-radio-group v-model="form.showjobcontact_mobile">
          <el-radio label="0">游客</el-radio>
          <el-radio label="1">已登录</el-radio>
          <el-radio label="2">已登录有简历</el-radio>
          <el-radio label="3">投递后显示</el-radio>
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
        showjobcontact: 1,
        showjobcontact_mobile: 1
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
          const { showjobcontact, showjobcontact_mobile } = {
            ...response.data
          }
          this.form = {
            showjobcontact,
            showjobcontact_mobile
          }
          this.infoLoading = false
        })
        .catch(() => {})
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
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
