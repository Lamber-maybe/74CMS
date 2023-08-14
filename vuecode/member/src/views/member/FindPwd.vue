<template>
  <div class="reg_box">
    <div class="reg_group" v-if="showForm">
      <div class="g_title">{{type=='mobile'?'手机':'邮箱'}}找回密码</div>
      <div class="sw_b">
        <router-link class="swb" :class="utype==2?'active':''" to="/forget/2">求职者用户</router-link>
        <router-link class="swb r" :class="utype==1?'active':''" to="/forget/1">企业招聘用户</router-link>
        <div class="clear"></div>
      </div>
      <div class="g_input" v-if="type=='mobile'">
        <el-input placeholder="请输入手机号" v-model="mobile" clearable></el-input>
        <el-input placeholder="请输入验证码" v-model="code" clearable></el-input>
        <div class="for_position">
          <el-button type="text" @click="sendSms" :style="'color:'+$store.state.sendSmsBtnTextColor">{{ $store.state.sendSmsBtnText }}</el-button>
        </div>
        <el-input placeholder="新密码" v-model="password" show-password></el-input>
        <el-input placeholder="确认密码" v-model="repeatPassword" show-password></el-input>
      </div>
      <div class="g_input" v-if="type=='email'">
        <el-input placeholder="请输入邮箱" v-model="email" clearable></el-input>
        <el-input placeholder="请输入验证码" v-model="code" clearable></el-input>
        <div class="for_position">
          <el-button type="text" @click="sendEmail" :style="'color:'+$store.state.sendEmailBtnTextColor">{{ $store.state.sendEmailBtnText }}</el-button>
        </div>
        <el-input placeholder="新密码" v-model="password" show-password></el-input>
        <el-input placeholder="确认密码" v-model="repeatPassword" show-password></el-input>
      </div>
      <el-button class="g_btn" type="primary" @click="handleSubmit">提交</el-button>
      <div class="g_sw_login"><span @click="changeMethod">邮箱找回密码</span></div>
      <div class="b_t1">上面的方式都不可用？</div>
      <div class="b_t2">你还可以进行
        <router-link to="/appeal">账号申诉</router-link>
        或 电话联系我们 (<span>{{ $store.state.config.contact_tel }}</span>)
      </div>
    </div>
    <div class="find_result" v-if="!showForm">
      <div class="fr_1">重置密码成功</div>
      <br>
      <div class="fr_2">下次登录请使用新密码登录</div>
      <el-button class="f_btn" type="primary" round @click="$router.push('/login')">登录</el-button>
    </div>
    <Captcha ref="captcha"></Captcha>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
  export default {
    name: 'FindPwd',
    components: {
      Captcha
    },
    data () {
      return {
        utype:1,
        showForm: true,
        type: 'mobile',
        mobile:'',
        email:'',
        code:'',
        password:'',
        repeatPassword:'',
        regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
        regularEmail: /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
      }
    },
    created(){
      this.utype = this.$route.params.utype===undefined?1:this.$route.params.utype
      this.$store.commit('clearCountDownFun')
    },
    methods: {
      changeMethod () {
        if(this.type=='mobile'){
          this.type = 'email'
        }else{
          this.type = 'mobile'
        }
      },
      // 发送验证码
      sendSms () {
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
        this.$refs.captcha.show(res => {
          this.$store
            .dispatch('sendSmsFun', {
              url: api.sendsms_forget,
              mobile: this.mobile,
              type: this.utype,
              captcha: res
            })
            .then(response => {
              if (response.code === 200) {
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
      },
      sendEmail () {
        if (this.$store.state.sendEmailBtnDisabled) {
          return false
        }
        if (!this.email) {
          this.$message.error('请输入邮箱')
          return false
        }
        if (!this.regularEmail.test(this.email)) {
          this.$message.error('手机号格式不正确')
          return false
        }
        this.$store
          .dispatch('sendEmailFun', {
            url: api.sendmail_forget,
            email: this.email,
            type: this.utype
          })
          .then(res => {
            if (res.code === 200) {
              this.$message({
                type: 'success',
                message: this.$store.state.sendEmailMessage
              })
            } else {
              this.$message.error(this.$store.state.sendEmailMessage)
              return false
            }
          })
      },
      handleSubmit () {
        if(this.type=='mobile' && !this.mobile){
          this.$message.error('请输入手机号')
          return false
        }
        if (this.type=='mobile' && !this.regularMobile.test(this.mobile)) {
          this.$message.error('手机号格式不正确')
          return false
        }
        if(this.type=='email' && !this.email){
          this.$message.error('请输入邮箱')
          return false
        }
        if (this.type=='email' && !this.regularEmail.test(this.email)) {
          this.$message.error('邮箱格式不正确')
          return false
        }
        if (!this.code) {
          this.$message.error('请输入验证码')
          return false
        }
        if (!this.password) {
          this.$message.error('请输入新密码')
          return false
        }
        if (!this.repeatPassword) {
          this.$message.error('请再次确认密码')
          return false
        }
        if (this.password !== this.repeatPassword) {
          this.$message.error('两次输入的密码不一致')
          return false
        }
        let postData = {
          mobile: this.mobile,
          email: this.email,
          code: this.code,
          password: this.password,
          utype: this.utype
        }
        let url = this.type=='mobile'?api.set_pwd_bymobile:api.set_pwd_byemail
        http
          .post(url, postData)
          .then(res => {
            if (parseInt(res.code) === 200) {
              this.$store.commit('clearCountDownFun')
              this.$store.commit('clearCountDownFunEmail')
              this.showForm = false
            } else {
              this.$message.error(res.message)
              return false
            }
          })
          .catch(() => {})
      }
    },
  }
</script>

<style lang="scss" scoped>
  .sw_b {
    .swb {
      display: block;float: left;width: 144px;height: 40px;line-height: 40px;border-radius: 4px;border: 1px solid #e2e2e2;
      text-align: center;position: relative;
      &.active,&:hover {
        border: 1px solid #1787fb;
      }
      &:hover {
        text-decoration: none;
      }
      &.active {
        &::after {
          content: '';position: absolute;right: 0;bottom: 0;width: 24px;height: 24px;
          background: url("../../assets/images/sw_fw_ico.png") 0 0 no-repeat;
        }
      }
      &.r {
        float: right;
      }
    }
    margin-bottom: 24px;
  }
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
        .el-input {
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
        margin-bottom: 5px;
      }

      .b_t2 {
        font-size: 12px;
        color: #999;
        padding-bottom: 35px;

        span, a {
          color: #1787fb
        }
      }
    }

    .find_result {
      width: 600px;
      height: 570px;
      margin: 0 auto;
      padding: 170px 0;
      text-align: center;

      .fr_1 {
        padding-left: 42px;
        height: 32px;
        line-height: 32px;
        font-size: 24px;
        color: #333;
        display: inline-block;
        background: url("../../assets/images/member/10.png") 0 center no-repeat;
        margin-bottom: 15px;
      }

      .fr_2 {
        font-size: 14px;
        color: #666;
        margin-bottom: 33px;
      }

      .f_btn {
        width: 220px;
      }
    }
  }
</style>
