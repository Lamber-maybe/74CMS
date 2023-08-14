<template>
  <div id="app">
    <Head>合作账号登录</Head>
    <div class="log_wrapper">
      <div class="tx1">{{bindType=='weixin'?'微信':'QQ'}}账号绑定</div>
      <div class="tx2">您已登录：<span>{{nickname}}</span></div>
      <div class="tx3">请关联您的{{$store.state.config.sitename}}账号，以便您下次直接登录</div>
      <div class="field_cell_group">
        <div class="field_cell choose-utype" style="border:0;">
          <div class="t_box">
            <router-link class="swb" :class="$route.query.utype==2?'active':''" :to="genCurUrl(2)">我要找工作</router-link>
            <router-link class="swb" :class="$route.query.utype==1?'active':''" :to="genCurUrl(1)">我要招聘</router-link>
          </div>
        </div>
        <div class="field_cell">
          <input
            v-model="mobile"
            class="log_field"
            type="tel"
            placeholder="请输入手机号"
            autocomplete="off"
          />
        </div>
        <div class="field_cell">
          <input
            v-model="code"
            class="log_field"
            type="number"
            placeholder="请输入验证码"
            autocomplete="off"
          />
          <button class="log_get_btn" @click="sendSms">{{ $store.state.sendSmsBtnText }}</button>
        </div>
        <div class="btn_group" style="margin-top:10px;">
          <van-button class="btn_mb" type="info" size="large" round @click="handleSubmit"
            >绑定网站账号</van-button
          >
          <div class="g_agree">
            <p>* 如果您没有注册过账号将自动帮您注册新账号</p>
            <p>继续绑定即表示您已同意 <span @click="showAgreement('agreement')">《用户协议》</span>和 <span @click="showAgreement('privacy')">《隐私政策》</span></p>
          </div>
        </div>
      </div>
    </div>
    <van-popup
      :lazy-render="false"
      v-model="showText"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <Head
        :goback_custom="true"
        @gobackCustomMethod="showText = false"
        :show_right="'false'"
      >{{ showTextTitle }}</Head
      >
      <div class="text_content">
        <span style="white-space: pre-line;" v-html="showTextContent"></span>
      </div>
    </van-popup>
    <Captcha ref="captcha"></Captcha>
  </div>
</template>

<script>
import { handlerHttpError } from '@/utils/error'
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
export default {
  name: 'AccountBinding',
  components: {
    Captcha
  },
  data () {
    return {
      bindType: 'weixin',
      utype: 2,
      mobile: '',
      code: '',
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
      openid: '',
      unionid: '',
      nickname: '',
      avatar: '',
      showTextTitle: '用户协议',
      showTextContent: '',
      showText: false
    }
  },
  created () {
    this.utype = this.$route.query.utype ? parseInt(this.$route.query.utype) : 2
    this.openid = this.$route.query.openid
    this.unionid = this.$route.query.unionid
    this.nickname = this.$route.query.nickname
    this.avatar = this.$route.query.avatar
    this.bindType = this.$route.query.bindType
    this.$route.query.utype = this.utype
  },
  methods: {
    genCurUrl (utype) {
      let avatar = encodeURIComponent(this.avatar)
      return `/member/bind?openid=${this.openid}&unionid=${this.unionid}&nickname=${this.nickname}&avatar=${avatar}&bindType=${this.bindType}&utype=${utype}`
    },
    showAgreement (alias) {
      this.showTextContent = ''
      this.showText = true
      this.showTextTitle = alias === 'agreement' ? '用户协议' : '隐私政策'
      http
        .get(api.agreement, {})
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.showTextContent =
              alias === 'agreement' ? res.data.agreement : res.data.privacy
          } else {
            this.$notify(res.message)
          }
        })
        .catch(() => {})
    },
    redirectTo () {
      if (this.utype === 1) {
        this.$router.push('/member/company/index')
      } else {
        this.$router.push('/member/personal/index')
      }
    },
    handleSubmit () {
      if (!this.mobile) {
        this.$notify('请输入手机号')
        return false
      }
      if (!this.regularMobile.test(this.mobile)) {
        this.$notify('手机号格式不正确')
        return false
      }
      if (!this.code) {
        this.$notify('请输入验证码')
        return false
      }
      let postData = {
        mobile: this.mobile,
        code: this.code,
        utype: this.utype,
        openid: this.openid,
        unionid: this.unionid,
        nickname: this.nickname,
        avatar: this.avatar
      }
      let apiUrl = this.bindType == 'weixin' ? api.bind_weixin : api.bind_qq
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
            this.$notify(response.message)
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
        this.$notify('请输入手机号')
        return false
      }
      if (!this.regularMobile.test(this.mobile)) {
        this.$notify('手机号格式不正确')
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
              _this.$notify({type: 'success', message: _this.$store.state.sendSmsMessage})
            } else {
              _this.$notify(_this.$store.state.sendSmsMessage)
            }
          })
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.t_box {
  display: flex;flex-wrap: wrap;justify-content: space-between;
  .swb {
    width: 145px;font-size: 14px;padding: 6px 0 6px 47px;border-radius: 33px;color: #999999;
    background: #f0f0f0 url("../../assets/images/sw_fw_ic1.png") 20px center no-repeat;background-size: 17px;
    &.active {
      background: #ebf5ff url("../../assets/images/sw_fw_ic2.png") 20px center no-repeat;background-size: 17px;color: #3388ff;
    }
  }
}
.log_wrapper {
  width: 323px;
  margin: 0 auto;
}
.tx1 {
  padding: 55px 0 28px;
  font-size: 26px;
  color: #333333;
}
.field_cell {
  width: 100%;
  border-bottom: 1px solid #eeeeee;
  position: relative;
  padding-top: 20px;
}
.log_field {
  border: 0;
  width: 100%;
  height: 100%;
  color: #333333;
  font-size: 15px;
  &::placeholder {
    color: #c9c9c9;
  }
}
.log_get_btn {
  position: absolute;
  right: 0;
  bottom: -18px;
  border: 0;
  height: 100%;
  font-size: 15px;
  color: #c9c9c9;
  padding: 0;
  background-color: #ffffff;
}
.tx2 {
  font-size: 16px;
  color: #999999;
  span {
    color: #333333;
  }
}
.tx3 {
  font-size: 13px;
  color: #ff6060;
  padding-top: 10px ;
}
.btn_mb {
  margin-bottom: 16px;
}

.g_agree {
  margin-top:10px;
  color:#999;
  font-size: 13px;
  text-align: center;
  padding:0 0 20px;
  line-height:25px;
  span{cursor:pointer;color: #1787fb;}
  span:hover{text-decoration:underline;color:#1787fb}
  .el-checkbox__label {
     color: #999;
    span { color: #1787fb; }
  }
}
.van-radio{
  font-size:14px;
}

.choose-utype{
  margin-top:0px;

}
</style>
