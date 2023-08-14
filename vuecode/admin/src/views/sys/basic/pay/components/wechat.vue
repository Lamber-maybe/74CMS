<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="190px"
    >
      <el-form-item label="商户号(MCHID)">
        <el-input v-model="form.payment_wechat_mchid" class="middle" />
      </el-form-item>
      <el-form-item label="商户支付密钥(KEY)">
        <el-input v-model="form.payment_wechat_key" class="middle" />
      </el-form-item>
      <el-form-item label="绑定支付的APPID">
        <el-input v-model="form.payment_wechat_appid" class="middle" />
      </el-form-item>
      <el-form-item label="公众帐号secert">
        <el-input v-model="form.payment_wechat_appsecret" class="middle" />
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
        payment_wechat_mchid: '',
        payment_wechat_key: '',
        payment_wechat_appid: '',
        payment_wechat_appsecret: ''
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
          const {
            payment_wechat_mchid,
            payment_wechat_key,
            payment_wechat_appid,
            payment_wechat_appsecret
          } = { ...response.data }
          this.form = {
            payment_wechat_mchid,
            payment_wechat_key,
            payment_wechat_appid,
            payment_wechat_appsecret
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName, path = false) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              if (path !== false){
                this.$router.push(path)
                return false
              }
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    saveAndGo(path){
      this.onSubmit('form', path)
    }
  }
}
</script>
