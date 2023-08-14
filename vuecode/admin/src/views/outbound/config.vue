<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header">外呼配置</div>
      <div>
        <div class="tip">
          <p>
            云外呼服务由第三方(招聘行业专用)线路商独立提供，在使用前请先联系客服开通外呼服务(座席+基础呼叫包)。您需：
          </p>
          <p>
            1. 在开通外呼服务后完成appkey及appSecret 配置；
          </p>
          <p>
            2. 保证您的座席和基础呼叫包在有效期内；
          </p>
          <p>
            3. 基础呼叫包使用完后请联系客服单独购买套外呼叫包。
          </p>
        </div>
        <el-form
          ref="form"
          :model="form"
          label-width="160px"
          class="common-form"
        >
          <el-form-item label="AppKey">
            <el-input v-model="form.app_id" class="small" style="width:50%;" />
          </el-form-item>
          <el-form-item label="AppSecret">
            <el-input v-model="form.app_secret" style="width:50%" class="small" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit()">保存</el-button>
          </el-form-item>
        </el-form>

      </div>
    </el-card>
  </div>
</template>

<script>

import { outboundAccount, outboundAccountSave } from '@/api/outbound'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        app_id: '',
        app_secret: ''
      },
      submit: false
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      outboundAccount().then(res => {
        this.form.app_id = res.data.app_id
        this.form.app_secret = res.data.app_secret
      }).catch(() => {

      })
    },
    onSubmit(){
      if (this.submit){
        return false
      }
      this.submit = true
      outboundAccountSave({
        'app_id': this.form.app_id,
        'app_secret': this.form.app_secret
      }).then(res => {
        this.$message.success(res.message)
        this.submit = false
      }).catch(() => {
        this.submit = false
      })
    }
  }
}
</script>

