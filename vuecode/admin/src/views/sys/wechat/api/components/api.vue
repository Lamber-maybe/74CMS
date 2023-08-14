<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="是否开启">
        <el-switch v-model="form.wechat_open" />
      </el-form-item>
      <el-form-item label="公众号类型">
        <el-radio-group v-model="form.wechat_type">
          <el-radio label="1">服务号</el-radio>
          <el-radio label="2">订阅号</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="Token">
        <el-input v-model="form.wechat_token" />
      </el-form-item>
      <el-form-item label="AppId">
        <el-input v-model="form.wechat_appid" />
      </el-form-item>
      <el-form-item label="AppSecret">
        <el-input v-model="form.wechat_appsecret" />
      </el-form-item>
      <el-form-item label="EncodingAESKey">
        <el-input v-model="form.wechat_encodingaeskey" />
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
        wechat_open: false,
        wechat_type: 1,
        wechat_token: '',
        wechat_appid: '',
        wechat_appsecret: '',
        wechat_encodingaeskey: ''
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
            wechat_open,
            wechat_type,
            wechat_appid,
            wechat_token,
            wechat_appsecret,
            wechat_encodingaeskey
          } = { ...response.data }
          this.form = {
            wechat_open: wechat_open == 1,
            wechat_type,
            wechat_appid,
            wechat_token,
            wechat_appsecret,
            wechat_encodingaeskey
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.wechat_open = insertData.wechat_open === true ? 1 : 0
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
<style scoped>
.el-input,
.el-input-group {
  width: 50%;
}
</style>
