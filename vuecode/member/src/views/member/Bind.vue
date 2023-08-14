<template>
    <div class="bind_group">
      <div class="bl">
        <div class="ava_box"><img :src="avatar"></div>
        <div class="l_txt">您已登录{{bindType=='weixin'?'微信':'QQ'}}账号</div>
      </div>
      <div class="br">
        <div class="reg_group">
          <div class="g_menu">
            <div class="gm_i">关联新账号</div>
          </div>
          <div class="g_input">
            <el-select
              :style="{'width':'320px'}"
              v-model="utype"
              placeholder="请选择">
              <el-option label="企业招聘会员" :value="1"></el-option>
              <el-option label="求职者会员" :value="2"></el-option>
            </el-select>
            <el-input placeholder="请输入手机号" clearable v-model="mobile"></el-input>
            <el-input placeholder="请输入验证码" clearable v-model="code"></el-input>
            <div class="for_position"><el-button type="text" @click="sendSms">{{ $store.state.sendSmsBtnText }}</el-button></div>
          </div>
          <div class="g_agree">
            <el-checkbox class="for_after" v-model="checked"></el-checkbox>
            已阅读并同意 <span @click="showAgreement('agreement')">《用户协议》</span>和 <span @click="showAgreement('privacy')">《隐私政策》</span>
          </div>
          <el-button class="g_btn" type="primary" @click="handleSubmit">关联网站会员</el-button>
        </div>
      </div>
      <div class="clear"></div>
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
    name: 'Bind',
    components: {
      Captcha
    },
    data() {
      return {
        bindType:'weixin',
        showTextTitle: '注册协议',
        showTextContent: '',
        showText: false,
        checked: false,
        utype:1,
        mobile:'',
        code:'',
        regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
        openid:'',
        unionid:'',
        nickname:'',
        avatar:''
      }
    },
    created(){
      this.openid = this.$route.query.openid
      this.unionid = this.$route.query.unionid
      this.nickname = this.$route.query.nickname
      this.avatar = this.$route.query.avatar
      this.bindType = this.$route.query.bindType
      this.$store.commit('clearCountDownFun')
    },
    methods:{
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
      redirectTo () {
          if (this.utype === 1) {
            this.$router.push('/company')
          } else {
            this.$router.push('/personal')
          }
      },
      handleSubmit () {
        if (!this.checked) {
          this.$message.error('请同意用户协议和隐私政策')
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
        let postData = {
          mobile: this.mobile,
          code: this.code,
          utype:this.utype,
          openid:this.openid,
          unionid:this.unionid,
          nickname:this.nickname,
          avatar:this.avatar
        }
        let apiUrl = this.bindType=='weixin'?api.bind_weixin:api.bind_qq
        http
          .post(apiUrl, postData)
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
              url: api.sendsms_auth_mobile_nocheck,
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
  .agreement {
    max-height: 500px;
    overflow-y: scroll;
  }
  .bind_group {
    width: 1200px;height: 660px;background-color: #fff;margin-top: 15px;padding-top: 30px;
    .bl {
      float: left;width: 440px;text-align: center;height: 600px;border-right: 1px solid #eeeeee;padding-top: 220px;
      .ava_box {
        width: 100px;height: 100px;border-radius: 100%;margin: 0 auto 15px;
        img {width: 100px;height: 100px;border: 0;border-radius: 100%;}
      }
      .l_txt {
        font-size: 13px;color: #999;
      }
    }
    .br{
      float: left;width: 760px;
      .reg_group {
        width: 320px; margin: 0 auto;
        .g_menu {
          display: flex;
          .gm_i {
            flex: 1;text-align: left;font-size: 18px;color: #333;padding: 30px 0 35px;cursor: pointer;
            &.active {
              font-weight: 700;color: #1787fb;
            }
          }
        }
        .g_input {
          .el-input,.el-select {
            &:not(:last-child) { margin-bottom: 24px; }
          }
          .el-select {
            .el-input {margin-bottom: 0;}
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
          text-align: center; padding:0 0 20px;
          span{cursor:pointer;color: #1787fb;}
          span:hover{text-decoration:underline;color:#1787fb}
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
  }
</style>
