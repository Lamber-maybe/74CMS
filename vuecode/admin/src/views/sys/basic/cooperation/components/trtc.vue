<template>
  <div class="app-container">
    <div class="tip">
      <p>
        视频面试集成了腾讯云trtc技术，该服务是收费的，费用由腾讯云收取。现在就去
        <a style="color:#1E88E5" href="https://cloud.tencent.com/product/trtc" target="_blank">
          [申请]
        </a>
      </p>
      <p>
        为保证视频面试的流畅性和可用性，建议您提前配置https，并引导客户使用谷歌内核浏览器参与视频面试
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
        <el-input v-model="form.account_trtc_appid" class="middle" />
      </el-form-item>
      <el-form-item label="SecretKey">
        <el-input v-model="form.account_trtc_secretkey" class="middle" />
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
        account_trtc_appid: '',
        account_trtc_secretkey: ''
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
          const { account_trtc_appid, account_trtc_secretkey } = { ...response.data }
          this.form = { account_trtc_appid, account_trtc_secretkey }
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
