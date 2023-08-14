<template>
    <div class="reg_box">
      <div class="reg_group">
        <div class="g_title">{{`Hi~，欢迎注册${title}会员`}}</div>
        <div class="g_input">
          <el-input v-model="companyname" placeholder="请输入企业名称" clearable v-if="utype === 1"></el-input>
          <el-input v-model="contact" placeholder="请输入联系人姓名" clearable v-if="utype === 1"></el-input>
          <el-input v-model="mobile" placeholder="请输入手机号" clearable></el-input>
          <el-input v-model="code" placeholder="请输入验证码" clearable></el-input>
          <div class="for_position"><el-button type="text" @click="sendSms" :style="'color:'+$store.state.sendSmsBtnTextColor">{{ $store.state.sendSmsBtnText }}</el-button></div>
          <el-input placeholder="请设置账号密码" v-model="password" show-password></el-input>
          <el-input placeholder="请重复输入密码" v-model="repeatPassword" show-password></el-input>
        </div>
        <div class="g_agree">
          <el-checkbox class="for_after" v-model="checked"></el-checkbox>
          已阅读并同意 <span @click="showAgreement('agreement')">《用户协议》</span>和 <span @click="showAgreement('privacy')">《隐私政策》</span>
        </div>
        <el-button class="g_btn" type="primary" @click="handleSubmit">{{`注册${title}会员`}}</el-button>
        <div class="g_sw_login">已有账号？ <span @click="handlerLogin">立即登录</span></div>
      </div>
      <el-dialog :title="showTextTitle" :visible.sync="showText">
          <div class="agreement"><span style="white-space: pre-line;" v-html="showTextContent"></span></div>
          <div slot="footer" class="dialog-footer">
            <el-button type="primary" @click="showText = false">关 闭</el-button>
          </div>
      </el-dialog>
    <Captcha ref="captcha"></Captcha>
    </div>
</template>

<script>
import { handlerHttpError } from '@/utils/error'
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
  export default {
    name: 'Register',
    props:['utype'],
    components: {
      Captcha
    },
    data () {
      return {
        showTextTitle: '注册协议',
        showTextContent: '',
        showText: false,
        checked: true,
        companyname: '',
        contact: '',
        mobile: '',
        code: '',
        password: '',
        repeatPassword: '',
        title: '求职者',
        regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/
      }
    },
    mounted () {
      this.title = this.utype === 1 ? '企业招聘' : '求职者'
      this.$store.commit('clearCountDownFun')
    },
    methods:{
      handlerLogin(){
        if(this.utype==1){
          this.$router.push('/login/company')
        }else{
          this.$router.push('/login')
        }
      },
      showAgreement (alias) {
        this.showTextContent = ''
        this.showText = true
        this.showTextTitle = alias == 'agreement' ? '注册协议' : '隐私政策'
        http
          .get(api.agreement, {})
          .then(res => {
            if (parseInt(res.code) === 200) {
              this.showTextContent =
                alias == 'agreement' ? res.data.agreement : res.data.privacy
            } else {
              this.$message.error(res.message)
            }
          })
          .catch(() => {})
      },
      handleSubmit () {
        if (!this.checked) {
          this.$message.error('请同意用户协议和隐私政策')
          return false
        }
        if (this.utype==1 && !this.companyname) {
          this.$message.error('请输入企业名称')
          return false
        }
        if (this.utype==1 && !this.contact) {
          this.$message.error('请输入联系人')
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
        if (!this.code) {
          this.$message.error('请输入验证码')
          return false
        }
        if (!this.password) {
          this.$message.error('请输入密码')
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
          code: this.code,
          password: this.password
        }
        let apiUrl = api.reg_personal
        if(this.utype==1){
          postData.companyname = this.companyname
          postData.contact = this.contact
          apiUrl = api.reg_company
        }

        http
          .post(apiUrl, postData)
          .then(res => {
            if (parseInt(res.code) === 200) {
              this.$message({ type: 'success', message: res.message })
              this.$store.commit('clearCountDownFun')
              this.$store.commit('setLoginState', {
                whether: true,
                utype: res.data.utype,
                token: res.data.token,
                mobile: res.data.mobile,
                userIminfo: res.data.user_iminfo
              })
              if (res.data.next_code != 200) {
                handlerHttpError({ code: res.data.next_code, message: '' })
              }
            } else {
              this.$message.error(res.message)
            }
          })
          .catch(() => {})
      },
      // 发送验证码
      sendSms () {
        let _this = this
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
              url: api.get_reg_code,
              mobile: this.mobile,
              type: this.utype,
              captcha: res
            })
            .then(response => {
              if (response.code === 200) {
                _this.$message({type: 'success', message: _this.$store.state.sendSmsMessage})
              } else {
                _this.$message.error(_this.$store.state.sendSmsMessage)
              }
            })
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
.agreement{
  max-height:500px;
  overflow-y:scroll;
}
  .reg_box {
    width: 1200px; background-color: #fff; margin-top: 15px;
    .reg_group {
      width: 320px; margin: 0 auto;
      .g_title { font-size: 24px; color: #333; text-align: center; padding: 50px 0 40px; }
      .g_input {
        .el-input {
          &:not(:last-child) { margin-bottom: 24px; }
        }
      }
      .for_position {
        position: relative;
        .el-button {
          position: absolute; right: 0; top: -64px; padding: 12px 10px;
          &::after {
            content: ''; position: absolute; left: 0; top: 10px; height: 20px; width: 1px; border-left: 1px solid #f3f3f3;
          }
        }
      }
      .g_agree {
        text-align: center; padding: 20px 0;
        span{cursor:pointer;color:#1787fb;}
        span:hover{text-decoration:underline;color:#1787fb;}
        .el-checkbox__label {
          font-size: 12px; color: #999;
          span { color: #1787fb; }
        }
      }
      .g_btn { width: 100% }
      .g_sw_login {
        text-align: center; padding: 18px 0 60px; color: #999;
        span {
          cursor: pointer; color: #1787fb;
        }
      }
    }
  }
</style>
