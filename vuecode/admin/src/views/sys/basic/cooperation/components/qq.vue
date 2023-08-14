<template>
  <div class="app-container">
    <div class="tip">
      <p>
        网站接入QQ登录后，用户只需要使用QQ账号密码就可登录，简化用户注册流程，更有效率的提高转化用户流量
      </p>
      <p>
        接入QQ登录前，网站需首先进行申请，获得对应的appid与appkey，以保证后续流程中可正确对网站与用户进行验证与授权。 现在就去
        <a style="color:#1E88E5" href="https://connect.qq.com/index.html" target="_blank">
          [申请]
        </a>
      </p>
      <p>
        网站接入QQ登录后，为了用户信息同步，请站长在网站应用和移动端应用审核通过后，在各自的应用--应用接口（申请unionid开启），否则用户信息将不会同步给使用者带来不必要的麻烦
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
        <el-input v-model="form.account_qqlogin_appid" class="middle" />
      </el-form-item>
      <el-form-item label="AppKey">
        <el-input v-model="form.account_qqlogin_appkey" class="middle" />
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
        account_qqlogin_appid: '',
        account_qqlogin_appkey: ''
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
          const { account_qqlogin_appid, account_qqlogin_appkey } = { ...response.data }
          this.form = { account_qqlogin_appid, account_qqlogin_appkey }
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
