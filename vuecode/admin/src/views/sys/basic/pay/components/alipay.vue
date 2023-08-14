<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="180px"
    >
      <el-form-item label="应用APPID">
        <el-input v-model="form.appid" class="middle" />
      </el-form-item>
      <el-form-item label="应用私钥(rsaPrivateKey)">
        <el-input
          v-model="form.privatekey"
          type="textarea"
          :rows="5"
        />
      </el-form-item>
      <el-form-item label="支付宝公钥(alipayrsaPublicKey)">
        <el-input v-model="form.publickey" type="textarea" :rows="5" />
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
        appid: '',
        privatekey: '',
        publickey: ''
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
          const { account_alipay } = { ...response.data }
          this.form = account_alipay

          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        account_alipay: { ...this.form }
      }
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
