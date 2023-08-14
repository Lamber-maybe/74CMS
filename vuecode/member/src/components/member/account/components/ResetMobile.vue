<template>
  <div class="dialog">
    <div>
      <el-form  label-width="90px">
        <el-form-item label="原手机号:">
          {{mobileOriginal}}
        </el-form-item>
        <el-form-item label="新手机号:">
          <el-input class="input_width" v-model="newMobile"></el-input>
        </el-form-item>
        <el-form-item label="验证码:">
            <el-input class="input_width" v-model="code">
              <template slot="append"><el-button  class="el_button" @click="sendSms" :style="'color:'+$store.state.sendSmsBtnTextColor">{{ $store.state.sendSmsBtnText }}</el-button></template>
            </el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="btn">
      <el-button type="primary" @click="handleSubmit">保存</el-button>
      <el-button @click="$emit('closeDialog', mobileOriginal)">取消</el-button>
    </div>
    <Captcha ref="captcha"></Captcha>
  </div>
</template>
<script>
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
export default {
  name: 'ResetMobile',
  props: ['mobileOriginal'],
  components: {
    Captcha
  },
  data () {
    return {
      checked: true,
      btnText: '获取验证码',
      btnDisabled: false,
      newMobile: '',
      code: '',
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
      sendSmsLimit: false
    }
  },
  created () {
    // zdq 360浏览器邮箱绑定兼容
    this.$store.commit('clearCountDownFun')
  },
  methods: {
    // 提交
    handleSubmit () {
      if (!this.newMobile) {
        this.$message.error('请填写手机号')
        return false
      }
      if (!this.regularMobile.test(this.newMobile)) {
        this.$message.error('手机号格式不正确')
        return false
      }
      if (this.newMobile === this.mobile_original) {
        this.$message.error('新手机号与原手机号相同')
        return false
      }
      if (!this.code) {
        this.$message.error('请填写验证码')
        return false
      }
      http
        .post(api.reset_mobile, {
          mobile: this.newMobile,
          code: this.code
        })
        .then(res => {
          if (res.code === 200) {
            this.$message({ type: 'success', message: res.message })
            this.$store.commit('setLoginState', {
              whether: this.$store.state.LoginOrNot,
              utype: this.$store.state.LoginType,
              token: this.$store.state.userToken,
              mobile: this.$store.state.userMobile,
              userIminfo: this.$store.state.userIminfo
            })
            this.$emit('closeDialog', this.newMobile)
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    //zdq 短信验证码重复发送修改
    // 发送验证码
    sendSms () {
      if (this.$store.state.sendSmsBtnDisabled) {
        return false
      }
      if (!this.newMobile) {
        this.$message.error('请输入手机号')
        return false
      }
      if (!this.regularMobile.test(this.newMobile)) {
        this.$message.error('手机号格式不正确')
        return false
      }
      if (this.mobile_original == this.newMobile) {
        this.$message.error('新手机号与旧手机号相同')
        return false
      }
      if (this.sendSmsLimit) {
        return false
      }
      this.sendSmsLimit = true
      this.$refs.captcha.show(res => {
        this.$store
          .dispatch('sendSmsFun', {
            url: api.sendsms_auth_mobile,
            mobile: this.newMobile,
            type: this.$store.state.LoginType,
            captcha: res
          })
          .then(response => {
            this.sendSmsLimit = false
            if (response.code == 200) {
              this.$message({
                type: 'success',
                message: this.$store.state.sendSmsMessage
              })
            } else {
              this.$message.error(this.$store.state.sendSmsMessage)
              return false
            }
          })
      })
    }
  }
}
</script>

<style scoped lang="scss">
  .btn{
    text-align: center;
    margin-top:40px;
  }
  .btn .el-button{
    padding: 11px 39px;
    margin-left: 15px;
  }
  .input_width{
    width: 270px;
  }
  .dialog{
    padding: 0 20px;
  }
  .el_button{
    padding: 12px 14px;
  }
</style>
