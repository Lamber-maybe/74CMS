<template>
  <div class="reg_box">
    <div class="reg_group">
      <div class="g_title">账号申诉</div>
      <div class="g_input">
        <el-input placeholder="请输入姓名" v-model="realname" clearable></el-input>
        <el-input placeholder="请输入手机号" v-model="mobile" clearable></el-input>
        <el-input placeholder="请输入验证码"  v-model="code" clearable></el-input>
        <div class="for_position">
          <el-button type="text" @click="sendSms" :style="'color:'+$store.state.sendSmsBtnTextColor">{{ $store.state.sendSmsBtnText }}</el-button>
        </div>
        <el-input
          v-model="description"
          type="textarea"
          :rows="3"
          placeholder="请输入描述">
        </el-input>
      </div>
      <el-button class="g_btn" type="primary" @click="handleSubmit">提交</el-button>
      <div class="b_t1">申诉提示</div>
      <div class="b_t2">1、申诉后，我们客服人员将尽快联系您</div>
      <div class="b_t2">2、申诉联系电话 <span>{{ $store.state.config.contact_tel }}</span></div>
      <div class="b_t2">3、申诉成功后，我们将把账号密码重置并提供给您</div>
    </div>
    <Captcha ref="captcha"></Captcha>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
  export default {
    name: 'Appeal',
    components: {
      Captcha
    },
    data:function () {
      return {
        realname:'',
        mobile:'',
        code:'',
        description:'',
        regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
        sendSmsLimit:false,
      }
    },
    created:function(){
      this.$store.commit('clearCountDownFun')
    },
    methods: {
      // 发送验证码
      // zdq 获取验证码重复点击修改
      sendSms:function () {
        var that = this
        if (this.$store.state.sendSmsBtnDisabled) {
          return false
        }
        if (!this.mobile) {
          this.$message.error('请输入手机号')
          return false
        }
        if (!this.regularMobile.test(this.mobile)) {
          this.$message.error('手机号格式不正确')
          return false
        }
        if(this.sendSmsLimit){
          return false
        }
        this.sendSmsLimit = true
        this.$refs.captcha.show(function(res) {
          that.$store
            .dispatch('sendSmsFun', {
              url: api.sendsms_auth_mobile_nocheck,
              mobile: that.mobile,
              type: 0,
              captcha: res
            })
            .then(function(response) {
              if (response.code === 200) {
                that.sendSmsLimit = false
                that.$message({
                  type: 'success',
                  message: that.$store.state.sendSmsMessage
                })
              } else {
                that.sendSmsLimit = false
                that.$message.error(that.$store.state.sendSmsMessage)
                return false
              }
            })
        })
      },
      handleSubmit:function () {
        var that = this
        if(!this.realname){
          this.$message.error('请输入姓名')
          return false
        }
        if(!this.mobile){
          this.$message.error('请输入手机号')
          return false
        }
        if (!this.regularMobile.test(this.mobile)) {
          this.$message.error('手机号格式不正确')
          return false
        }
        if (!this.code) {
          this.$message.error('请输入验证码')
          return false
        }
        if (!this.description) {
          this.$message.error('请输入描述')
          return false
        }
        let postData = {
          realname: this.realname,
          mobile: this.mobile,
          code: this.code,
          description:this.description
        }
        http
          .post(api.member_appeal, postData)
          .then(function(res) {
            if (parseInt(res.code) === 200) {
              that.$message({type:'success',message:'提交成功,客服人员将会尽快联系您,请保持电话畅通！'})
              that.$store.commit('clearCountDownFun')
              that.$router.push('/login')
            }
          })
          .catch(function() {})
      }
    },
  }
</script>

<style lang="scss" scoped>
  .reg_box {
    width: 1200px;
    background-color: #fff;
    margin-top: 15px;

    .reg_group {
      width: 320px;
      margin: 0 auto;

      .g_title {
        font-size: 24px;
        color: #333;
        text-align: center;
        padding: 50px 0 40px;
      }

      .g_input {
        .el-input, .el-textarea {
          margin-bottom: 24px;
        }
      }

      .for_position {
        position: relative;

        .el-button {
          position: absolute;
          right: 0;
          top: -64px;
          padding: 12px 10px;

          &::after {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            height: 20px;
            width: 1px;
            border-left: 1px solid #f3f3f3;
          }
        }
      }

      .g_btn {
        width: 100%
      }

      .g_sw_login {
        text-align: center;
        padding: 18px 0 35px;
        color: #999;

        span {
          cursor: pointer;
        }
      }

      .b_t1 {
        font-size: 12px;
        color: #666;
        margin-bottom: 8px;
        padding-top: 50px;
      }

      .b_t2 {
        font-size: 12px;
        color: #999;
        &:not(:last-child) {
          margin-bottom: 5px;
        }
        &:last-child {
          padding-bottom: 20px;
        }

        span, a {
          color: #1787fb
        }
      }
    }
  }
</style>
