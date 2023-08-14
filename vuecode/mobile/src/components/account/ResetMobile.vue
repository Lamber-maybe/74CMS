<template>
  <div id="app">
    <Head
      :goback_custom="true"
      @gobackCustomMethod="$emit('closePopout', mobile_original)"
    >
      手机认证
    </Head>
    <div class="form_split_10"></div>
    <div class="notice_bar" v-if="mobile_original != ''">
      当前手机号已验证，修改手机后您的登录手机号将同步修改
    </div>
    <div class="log_wrapper">
      <div class="field_cell_group">
        <div class="field_cell">
          <label>
            <input
              class="log_field"
              type="number"
              placeholder="请输入新手机号"
              v-model="newMobile"
              autocomplete="off"
            />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="log_field"
              type="number"
              placeholder="请输入验证码"
              v-model="code"
              autocomplete="off"
            />
          </label>
          <button class="log_get_btn" @click="sendSms">
            {{ $store.state.sendSmsBtnText }}
          </button>
        </div>
        <div class="btn_group">
          <van-button
            class="btn_mb"
            type="info"
            size="large"
            round
            @click="handleSubmit"
          >
            提交
          </van-button>
        </div>
      </div>
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
  props: ['mobile_original'],
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
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/
    }
  },
  methods: {
    initCB () {},
    // 提交
    handleSubmit () {
      let that = this
      if (!this.newMobile) {
        this.$notify('请填写手机号')
        return false
      }
      if (!this.regularMobile.test(this.newMobile)) {
        this.$notify('手机号格式不正确')
        return false
      }
      if (this.newMobile === this.mobile_original) {
        this.$notify('新手机号与原手机号相同')
        return false
      }
      if (!this.code) {
        this.$notify('请填写验证码')
        return false
      }
      http
        .post(api.reset_mobile, {
          mobile: this.newMobile,
          code: this.code
        })
        .then(res => {
          if (res.code === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.$store.commit('setLoginState', {
              whether: that.$store.state.LoginOrNot,
              utype: that.$store.state.LoginType,
              token: that.$store.state.userToken,
              mobile: that.$store.state.userMobile,
              userIminfo: that.$store.state.userIminfo
            })
            setTimeout(() => {
              that.$emit('closePopout', this.newMobile)
            }, 1500)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 发送验证码
    sendSms () {
      if (this.$store.state.sendSmsBtnDisabled) {
        return false
      }
      if (!this.newMobile) {
        this.$notify('请输入手机号')
        return false
      }
      if (!this.regularMobile.test(this.newMobile)) {
        this.$notify('手机号格式不正确')
        return false
      }
      if (this.mobile_original == this.newMobile) {
        this.$notify('新手机号与旧手机号相同')
        return false
      }
      this.$refs.captcha.show(res => {
        this.$store
          .dispatch('sendSmsFun', {
            url: api.sendsms_auth_mobile,
            mobile: this.newMobile,
            type: this.$store.state.LoginType,
            captcha: res
          })
          .then(response => {
            if (response.code == 200) {
              this.$notify({
                type: 'success',
                message: this.$store.state.sendSmsMessage
              })
            } else {
              this.$notify(this.$store.state.sendSmsMessage)
              return false
            }
          })
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.split_bar {
  width: 100%;
  height: 9px;
  background-color: #f3f3f3;
}
.notice_bar {
  width: 100%;
  padding: 14px 0 14px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
  margin-bottom: 15px;
}
.log_wrapper {
  width: 323px;
  margin: 20px auto;
}
.tx1 {
  padding: 55px 0 48px;
  font-size: 26px;
  color: #333333;
}
.field_cell {
  width: 100%;
  border-bottom: 1px solid #eeeeee;
  margin-bottom: 21px;
  position: relative;
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
  bottom: 0;
  border: 0;
  height: 100%;
  font-size: 15px;
  color: #c9c9c9;
  padding: 0;
  background-color: #ffffff;
}
</style>
