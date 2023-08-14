<template>
  <div>
    <div class="edit_mobile_pop_wrapper">
      <div class="wap_line">
        <div class="wap_left first">原手机号：</div>
        <div class="wap_right no_border"><div class="number">{{show_mobile}}</div></div>
        <div class="clear"></div>
      </div>
      <div class="wap_line">
        <div class="wap_left">新手机号：</div>
        <div class="wap_right">
          <input name="mobile" v-model="mobile" id="diaLogMobile" type="number" placeholder="请输入新手机号码" class="wap_input">
        </div>
        <div class="clear"></div>
      </div>
      <div class="wap_line">
        <div class="wap_left">验证码：</div>
        <div class="wap_right">
          <input name="verifycode" id="diaLogMobileVc" v-model="code" type="number" placeholder="请输入验证码" class="wap_input">
          <div class="wap_btn" @click="sendSms" :style="'color:'+$store.state.sendSmsBtnTextColor">{{ $store.state.sendSmsBtnText }}</div>
        </div>
        <div class="clear"></div>
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
  name: 'UpdatePhoneNumber',
  props: ['show_mobile'],
  components: {
    Captcha
  },
  data () {
    return {
      mobile: '',
      code: '',
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/
    }
  },
  methods: {
    // 提交之前的验证
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
        code: this.code
      }
      http
        .post(api.auth_mobile, postData)
        .then(res => {
          this.$notify({ type: 'success', message: res.message })
          this.$store.commit('clearCountDownFun')
          this.$parent.enable_close_mobile = true
          this.$emit('closePopup', this.mobile)
        })
        .catch(() => {})
    },
    // 发送验证码
    sendSms () {
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
            type: 2,
            captcha: res
          })
          .then(response => {
            if (response.code === 200) {
              this.$notify({type: 'success', message: this.$store.state.sendSmsMessage})
            } else {
              this.$notify(this.$store.state.sendSmsMessage)
            }
          })
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.edit_mobile_pop_wrapper {
  width: 85%; margin: 0 auto; padding: 15px 0; font-size: 13px;
  .wap_line {
    position: relative; padding-left: 65px; overflow: hidden;
    .wap_left {
      float: left; color: #666666; width: 65px; text-align: left; padding: 12px 0; position: absolute; left: 0;
      top: 3px;
      &.first {
        top: 1px;
      }
    }
    .wap_right {
      float: right; width: 200px; color: #333; text-align: left; position: relative; border-bottom: 1PX solid #e2e2e2;
      &.no_border {
        border-bottom: 0;
      }
      .number {
        padding: 12px 0 12px 3px; color: #333;
      }
    }
    .wap_input {
      border:0; width: 100%; padding: 12px 0 12px 3px; font-size: 13px;
      line-height: 1;
      &::placeholder {
        color: #bcbcbc; font-size: 13px; line-height: 1;
      }
    }
    .wap_btn {
      position: absolute; right: 0; top: 50%; transform: translate(0, -50%);
      padding: .08rem .18rem;
    }
  }
}
</style>
