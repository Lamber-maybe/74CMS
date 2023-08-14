<template>
    <div class="login_box" :class="utype==1?'':'p'">
      <!-- <div class="b_bg" :class="utype==1?'':'p'"></div> -->
      <div class="b_group">
        <div v-if="$store.state.config.wechat_login_open==1" :class="scan ? 'b_sw aco' : 'b_sw'" @click="showScan"></div>
        <div class="login_tips" :class="utype==1 ? 'tips_color_2' : 'tips_color_1'" @click.stop="$router.push(layout.utype_other_login_route)">
          <div class="text_1">{{utype==1?'我是求职者':'我是招聘方'}}</div>
          <div class="text_2" @click.stop="$router.push(layout.utype_other_login_route)">{{utype==1?'个人在此登录':'企业在此登录'}}</div>
        </div>
        <div v-show="!scan">
          <div class="b_title">{{layout.utype_current_text}}登录</div>
          <div class="b_input" v-if="!loginByCode">
            <el-input v-model="formPwd.username" placeholder="请输入手机号/用户名/邮箱" clearable></el-input>
            <el-input placeholder="请输入密码" v-model="formPwd.password" show-password></el-input>
          </div>
          <div class="b_input" v-else>
            <el-input v-model="formCode.mobile" placeholder="请输入手机号" clearable></el-input>
            <el-input placeholder="请输入验证码" v-model="formCode.code" clearable></el-input>
            <div class="for_position"><el-button type="text" @click="sendSms">{{ $store.state.sendSmsBtnText }}</el-button></div>
          </div>
          <div class="b_handle">
            <div class="h_left" @click="loginByCode = !loginByCode">{{loginByCode ? '账号密码登录' : '手机验证码登录'}}</div>
            <div class="h_right"><router-link :to="'/forget/'+utype">忘记密码?</router-link></div>
            <div class="clear"></div>
          </div>
          <el-button class="b_btn" type="primary" @click="handleSubmit">立即登录</el-button>
          <div class="b_reg">没有账号？<router-link :to="'/reg'+(utype==1?'/company':'')">立即注册</router-link></div>
          <div class="b_coop" v-if="$store.state.config.account_qqlogin_open==1 || $store.state.config.wechat_login_open==1">
            <div class="p_title">合作账号登录</div>
            <div class="p_group">
              <div class="p_item" v-if="$store.state.config.account_qqlogin_open==1"><a href="javascript:;" class="p_ico qq" @click="qqLogin"></a></div>
              <div class="p_item" v-if="$store.state.config.wechat_login_open==1"><a href="javascript:;" class="p_ico wx" @click="showScan"></a></div>
            </div>
          </div>
          <!-- <div class="b_sw_txt">如果您是{{layout.utype_other_text}}，请<span @click="$router.push(layout.utype_other_login_route)">点击此处登录>></span></div> -->
        </div>
        <div class="scan_login" v-show="scan" id="wxlogin">
          <div class="l_t1">手机扫码，安全登录</div>
          <div class="scan_box"><div class="img"></div></div>
          <div class="l_t2">打开微信扫一扫，快速登录</div>
        </div>
      </div>
      <div class="clear"></div>
      <Captcha ref="captcha" :mask="true"></Captcha>
    </div>
</template>

<script>
import { isMobile } from '@/utils/index'
import { handlerHttpError } from '@/utils/error'
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
  export default {
    name: 'Login',
    components: {
      Captcha
    },
    data () {
      return {
        regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
        loginByCode: false,
        scan: false,
        layout: {
          utype_current_text: '企业招聘会员',
          utype_other_text: '求职者会员',
          utype_other_login_route: '',
          utype_current_reg_route: ''
        },
        formPwd: {
          username: '',
          password: ''
        },
        formCode: {
          mobile: '',
          code: ''
        },
        utype:2,
        redirect:'',
      }
    },
    watch: {
      utype: {
        immediate: true,
        handler () {
          this.initLayout()
        }
      },
    },
    created () {
      let code = this.$route.query.code
      if(code!==undefined){
        this.$router.push('/oauth/weixin/'+code)
        return false
      }
      if(this.$route.params.utype_alias==='company'){
        this.utype = 1
      }else if(this.$route.params.utype_alias==='personal'){
        this.utype = 2
      }else{
        this.utype = 2
      }
      if(isMobile()===true){
        location.href=this.$store.state.config.mobile_domain+'member/login'
        return false
      }

      if (window.WxLogin === undefined) {
        let script = document.createElement('script')
        let head = document.getElementsByTagName('head')[0]
        script.type = 'text/javascript'
        script.charset = 'UTF-8'
        script.src = 'https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js'
        head.appendChild(script)
      }
      this.$store.commit('clearCountDownFun')
    },
    methods:{
      showScan(){
        let that = this
        this.scan = !this.scan
        if(this.scan===true){
          new window.WxLogin({
            id:"wxlogin",
            appid: that.$store.state.config.wechat_open_appid,
            scope: "snsapi_login",
            redirect_uri: location.href,
            state: "",
            style: "",
            href: "data:text/css;base64,LmltcG93ZXJCb3ggLnFyY29kZSB7DQogIHdpZHRoOiAyMDBweDsNCn0NCi5pbXBvd2VyQm94IC5pbmZvIHsNCiAgd2lkdGg6IDIwMHB4Ow0KfQ0KLmltcG93ZXJCb3ggLnN0YXR1cyB7DQogIHRleHQtYWxpZ246IGNlbnRlcjsNCn0="
          });
        }
      },
      initLayout () {
        this.redirect =
          this.$route.query.redirect !== undefined
            ? this.$route.query.redirect
            : ''
        this.layout.utype_current_text = this.utype === 1 ? '企业招聘会员' : '求职者会员'
        this.layout.utype_other_text = this.utype === 1 ? '求职者会员' : '企业招聘会员'
        this.layout.utype_other_login_route =
          this.utype === 1 ? '/login' : '/login/company'
        this.layout.utype_current_reg_route =
          this.utype === 1 ? '/reg/company' : '/reg'
        if (this.redirect) {
          this.layout.utype_other_login_route =
            this.layout.utype_other_login_route + '?redirect=' + this.redirect
          this.layout.utype_current_reg_route =
            this.layout.utype_current_reg_route + '?redirect=' + this.redirect
        }
      },
      redirectTo () {
        if (this.redirect !== '') {
          if(this.redirect.indexOf("http")==0){
            location.href = this.redirect
          }else{
            this.$router.push(this.redirect)
          }
        } else {
          if (this.utype === 1) {
            this.$router.push('/company')
          } else {
            this.$router.push('/personal')
          }
        }
      },
      doSubmitPwd () {
        if (!this.formPwd.username) {
          this.$message.error('请输入手机号/用户名/邮箱')
          return false
        }
        if (!this.formPwd.password) {
          this.$message.error('请输入密码')
          return false
        }
        let postData = { ...this.formPwd }
        postData.utype = this.utype
        http
            .get(api.login_pwd, postData)
            .then(check_res => {
              let setShow = false
              if (check_res.data == 1) {
                setShow = true
              }
              this.$refs.captcha.show(res => {
                if (res !== undefined) {
                  postData.captcha = res
                }
                http
                  .post(api.login_pwd, postData)
                  .then(response => {
                    if (parseInt(response.code) === 200) {
                      this.$store.commit('clearCountDownFun')
                      this.$store.commit('setLoginState', {
                        whether: true,
                        utype: response.data.utype,
                        token: response.data.token,
                        mobile: response.data.mobile,
                        userIminfo: response.data.user_iminfo
                      })
                      if (response.data.next_code != 200) {
                        handlerHttpError({ code: response.data.next_code, message: '' })
                      } else {
                        this.redirectTo()
                      }
                    } else {
                      this.$message.error(response.message)
                    }
                  })
                  .catch(() => {})
              }, setShow)
            }).catch(() => {})
        
      },
      doSubmitCode () {
        if (!this.formCode.mobile) {
          this.$message.error('请输入手机号')
          return false
        }
        if (!this.regularMobile.test(this.formCode.mobile)) {
          this.$message.error('手机号格式不正确')
          return false
        }
        let postData = { ...this.formCode }
        postData.utype = this.utype
        http
            .get(api.login_code, postData)
            .then(check_res => {
              let setShow = false
              if (check_res.data == 1) {
                setShow = true
              }
              this.$refs.captcha.show(res => {
              if (res !== undefined) {
                postData.captcha = res
              }
              http
                .post(api.login_code, postData)
                .then(response => {
                  if (parseInt(response.code) === 200) {
                    this.$store.commit('clearCountDownFun')
                    this.$store.commit('setLoginState', {
                      whether: true,
                      utype: response.data.utype,
                      token: response.data.token,
                      mobile: response.data.mobile,
                      userIminfo: response.data.user_iminfo
                    })
                    if (response.data.next_code != 200) {
                      handlerHttpError({ code: response.data.next_code, message: '' })
                    } else {
                      this.redirectTo()
                    }
                  } else {
                    this.$message.error(response.message)
                  }
                })
                .catch(() => {})
            }, setShow)
        }).catch(() => {})
        
      },
      // 提交之前的验证
      handleSubmit () {
        if (!this.loginByCode) {
          this.doSubmitPwd()
        } else {
          this.doSubmitCode()
        }
      },
      // 发送验证码
      sendSms () {
        if (this.$store.state.sendSmsBtnDisabled) {
          return false
        }
        if (!this.formCode.mobile) {
          this.$message.error('请输入手机号')
          return false
        }
        if (!this.regularMobile.test(this.formCode.mobile)) {
          this.$message.error('手机号格式不正确')
          return false
        }
        this.$refs.captcha.show(res => {
          this.$store
            .dispatch('sendSmsFun', {
              url: api.get_login_code,
              mobile: this.formCode.mobile,
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
              }
            })
        })
      },
      qqLogin(){
        this.$router.push('/oauth/qq/login')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .login_box {
    width: 1200px; padding: 97px 0 ;
    background: url('../../assets/images/member/login_2.jpg') no-repeat center center;
      &.p {
        background: url("../../assets/images/member/login_1.jpg") no-repeat center center;
      }
    .b_group {
      width: 400px; height: 500px; background-color: #fff; position: relative; padding: 0 40px; left: 764px; box-shadow: 0px 0px 6px 5px rgba(0, 0, 0, 0.03);
      .b_sw {
        position: absolute; left: 13px; top: 13px; width: 38px; height: 38px; cursor: pointer; transform: rotate(-90deg);
        background: url("../../assets/images/member/3.png") 0 0 no-repeat;
        &.aco {background: url("../../assets/images/member/9.png") 0 0 no-repeat;}
      }
      .login_tips{
        width: 100px;
        height: 60px;
        border-radius: 0 0 15px 15px;
        position: absolute;
        right: 25px;
        top: -6px;
        text-align: center;
        color: #fff;
        padding: 0 2px;
        cursor: pointer;
        &::before{
          content: '';
          border-style: solid;
          border-width: 3px;
          position:absolute;
          left: -6px;
          top: 0;
        }
        &::after{
          content: '';
          border-style: solid;
          border-width: 3px;
          position: absolute;
          right: -6px;
          top: 0;
        }
        .text_1{
          line-height: 33px;
          border-bottom: 1px dashed #fff;
          font-size: 15px;
        }
        .text_2{
          line-height: 25px;
          font-size:12px ;
          cursor: pointer;
        }
      }
      .tips_color_1{
        background-color:#fea407; 
        &::before{
          border-color: transparent #fea407 #fea407 transparent;
        }
        &::after{
          border-color: transparent transparent #fea407 #fea407;
        }
      }
      .tips_color_2{
        background-color:#39c2cf; 
        &::before{
          border-color: transparent #39c2cf #39c2cf transparent;
        }
        &::after{
          border-color: transparent transparent #39c2cf #39c2cf;
        }
      }
      .scan_login {
        text-align: center; padding-top: 93px;
        .l_t1 {font-size: 18px;color: #333;margin-bottom: 35px;}
        .scan_box {
          width: 174px;height: 174px;background-color: #fff;border-radius: 3px;padding: 10px;margin: 0 auto 26px;
          box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
          .img{width: 154px;height: 154px;border: 0;}
        }
        .l_t2 {font-size: 14px;color: #333;}
      }
      .for_position {
        position: relative;
        .el-button {
          position: absolute; right: 0; top: -40px; padding: 12px 10px;
          &::after {
            content: ''; position: absolute; left: 0; top: 10px; height: 20px; width: 1px; border-left: 1px solid #f3f3f3;
          }
        }
      }
      .b_title { font-size: 18px; color: #333; padding: 40px 0; font-weight: 700; }
      .b_input {
        .el-input {
          &:first-child { margin-bottom: 22px; }
        }
      }
      .b_handle {
        padding: 23px 0;
        .h_left {
          float: left;color: #1787fb;cursor: pointer;
          a { color: #1787fb; }
        }
        .h_right {
          float: right;
          a { color: #999; }
        }
      }
      .b_btn { width: 100% }
      .b_reg {
        text-align: center; padding: 28px 0 12px;
        a { color: #1787fb; }
      }
      .b_coop {
        margin-bottom: 20px;
        .p_title {
          position: relative; width: 104px; margin: 0 auto 22px; color: #999; text-align: center;
          &::before {
            content: ''; position: absolute; left: -94px; top: 50%; transform: translate(0,-50%); width: 90px; height: 1px;
            border-top:1px solid #e2e2e2;
          }
          &::after {
            content: ''; position: absolute; right: -94px; top: 50%; transform: translate(0,-50%); width: 90px; height: 1px;
            border-top:1px solid #e2e2e2;
          }
        }
        .p_group {
          display: flex; width: 202px; margin: 0 auto;
          .p_item {
            flex: 1;
            .p_ico {
              display: block; width: 35px; height: 35px; margin: 0 auto; border-radius: 35px;
              &.qq { background: #0188fb url("../../assets/images/member/qq.png") center center no-repeat; background-size: 25px;}
              &.wx { background: #1aad19 url("../../assets/images/member/wx.png") center center no-repeat; background-size: 20px;}
            }
          }
        }
      }
      .b_sw_txt {
        font-size: 12px; text-align: center; color: #999;
        span { color: #1787fb; cursor: pointer; }
      }
    }
  }

</style>
