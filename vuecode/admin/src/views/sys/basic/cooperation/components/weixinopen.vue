<template>
  <div class="app-container">
    <div class="tip">
      <p>
        网站接入微信帐号登录后，用户只需要使用微信账号扫码就可登录，简化用户注册流程，更有效率的提高转化用户流量
      </p>
      <p>
        接入微信登录前，网站需首先进行申请，获得对应的AppID与AppSecret，以保证后续流程中可正确对网站与用户进行验证与授权。现在就去
        <a style="color:#1E88E5" href="https://open.weixin.qq.com/" target="_blank">
          [申请]
        </a>
      </p>
    </div>
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
    >
      <el-form-item label="AppId">
        <el-input v-model="form.wechat_open_appid" class="middle" />
      </el-form-item>
      <el-form-item label="AppSecret">
        <el-input v-model="form.wechat_open_appsecret" class="middle" />
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
        wechat_open_appid: '',
        wechat_open_appsecret: ''
      }
    }
  },
  mounted() {},
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const { wechat_open_appid, wechat_open_appsecret } = { ...response.data }
          this.form = { wechat_open_appid, wechat_open_appsecret }
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
