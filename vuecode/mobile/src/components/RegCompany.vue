<template>
  <div id="app">
    <Head></Head>
    <div class="reg_wrapper">
      <div class="tx1">欢迎注册企业招聘会员</div>
      <div class="field_cell_group">
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="text"
              placeholder="请输入企业名称"
              autocomplete="off"
              v-model="companyname"
            />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="text"
              placeholder="请输入联系人"
              autocomplete="off"
              v-model="contact"
            />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="tel"
              placeholder="请输入手机号"
              autocomplete="off"
              v-model="mobile"
            />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="number"
              placeholder="请输入验证码"
              autocomplete="off"
              v-model="code"
            />
          </label>
          <button class="reg_get_btn" @click="sendSms" :style="'color:'+$store.state.sendSmsBtnTextColor">
            {{ $store.state.sendSmsBtnText }}
          </button>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="password"
              placeholder="账户密码"
              autocomplete="off"
              v-model="password"
            />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="password"
              placeholder="确认密码"
              autocomplete="off"
              v-model="repeatPassword"
            />
          </label>
        </div>
      </div>
      <van-button type="info" size="large" round @click="handleSubmit">
        注册企业招聘会员
      </van-button>
      <div class="has_account">
        已有账号？
        <router-link to="/member/login/company">立即登录</router-link>
      </div>
      <div class="bottom_agree">
        <div class="agree_content">
          <van-checkbox v-model="checked" icon-size="16px">
            同意
            <span class="link" @click.stop="showAgreement('agreement')"
            >《用户协议》</span
            >
            和
            <span class="link" @click.stop="showAgreement('privacy')"
            >《隐私政策》</span
            >
          </van-checkbox>
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
  name: 'RegCompany',
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
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/
    }
  },
  created () {
    this.$store.commit('clearCountDownFun')
  },
  methods: {
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
            this.$notify(res.message)
          }
        })
        .catch(() => {})
    },
    // 提交之前的验证
    handleSubmit () {
      if (!this.checked) {
        this.$notify('请同意用户协议和隐私政策')
        return false
      }
      if (!this.companyname) {
        this.$notify('请输入企业名称')
        return false
      }
      if (!this.contact) {
        this.$notify('请输入联系人')
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
      if (!this.code) {
        this.$notify('请输入验证码')
        return false
      }
      if (!this.password) {
        this.$notify('请输入密码')
        return false
      }
      if (!this.repeatPassword) {
        this.$notify('请再次确认密码')
        return false
      }
      if (this.password !== this.repeatPassword) {
        this.$notify('两次输入的密码不一致')
        return false
      }
      let scene_uuid = localStorage.getItem('scene_uuid')
      let postData = {
        companyname: this.companyname,
        contact: this.contact,
        mobile: this.mobile,
        code: this.code,
        password: this.password,
        scene_uuid: scene_uuid
      }
      http
        .post(api.reg_company, postData)
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
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
            this.$notify(res.message)
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
            url: api.get_reg_code,
            mobile: this.mobile,
            type: 1,
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
.text_content {
  font-size: 14px;
  padding: 20px;
}
.reg_wrapper {
  width: 323px;
  margin: 0 auto;
}
.tx1 {
  padding: 25px 0 15px;
  font-size: 26px;
  color: #333333;
}
.field_cell_group {
  .field_cell:last-child {
    margin-bottom: 30px;
  }
}
.field_cell {
  width: 100%;
  border-bottom: 1px solid #eeeeee;
  position: relative;
  padding-top: 20px;
}
.reg_field {
  border: 0;
  width: 100%;
  display: block;
  color: #333333;
  font-size: 15px;
  padding: 7px 0;
  &::placeholder {
    color: #c9c9c9;
  }
}
.has_account {
  text-align: center;
  padding: 15px 0;
  font-size: 16px;
  color: #999999;
  a {
    color: #333333;
  }
}
.bottom_agree {
  text-align: center;
  font-size: 12px;
  color: #999999;
  padding: 20px 0;
  margin: 0 auto;
  width: fit-content;
  .agree_content {
    display: inline-block;
    a {
      color: #1787fb;
    }
    .link {
      color: #1787fb;
    }
  }
}
.reg_get_btn {
  position: absolute;
  right: 0;
  bottom: 0;
  border: 0;
  padding: 7px 0;
  font-size: 15px;
  color: #c9c9c9;
  background-color: #ffffff;
}
</style>
