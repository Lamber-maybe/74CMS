<template>
  <div id="app">
    <Head></Head>
    <div class="reg_wrapper" v-if="showResult === false">
      <div class="tx1">账号申诉</div>
      <div class="field_cell_group">
        <div class="field_cell">
          <input
            class="reg_field"
            type="text"
            placeholder="请输入您的真实姓名"
            autocomplete="off"
            v-model="form.realname"
          />
        </div>
        <div class="field_cell">
          <input
            class="reg_field"
            type="tel"
            placeholder="请输入手机号"
            autocomplete="off"
            v-model="form.mobile"
          />
        </div>
        <div class="field_cell">
          <input
            class="reg_field"
            type="number"
            placeholder="请输入验证码"
            autocomplete="off"
            v-model="form.code"
          />
          <button class="reg_get_btn" @click="sendSms">
            {{ $store.state.sendSmsBtnText }}
          </button>
        </div>
        <div class="field_cell">
          <input
            class="reg_field"
            type="text"
            placeholder="账号申诉描述"
            autocomplete="off"
            v-model="form.description"
          />
        </div>
      </div>
      <van-button type="info" size="large" round @click="handleSubmit"
        >提交</van-button
      >
    </div>
    <div class="result_wrapper" v-if="showResult === true">
      <div class="res_ico">提交成功</div>
      <div class="res_txt">客服人员将会尽快联系您</div>
      <div class="res_txt">请保持电话畅通！</div>
    </div>
    <div class="bottom_tip">
      <div class="tip_title">申诉提示</div>
      <div class="tip_txt">1、申诉后，我们客服人员将尽快联系您</div>
      <div class="tip_txt">
        2、申诉联系电话
        <span class="tel">{{ $store.state.config.contact_tel }}</span>
      </div>
      <div class="tip_txt">3、申诉成功后，我们将把账号密码重置并提供给您</div>
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
  data () {
    return {
      showResult: false,
      form: {
        realname: '',
        mobile: '',
        code: '',
        description: ''
      },
      checked: true,
      btnText: '获取验证码',
      btnDisabled: false,
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/
    }
  },
  methods: {
    // 发送验证码
    sendSms () {
      if (this.$store.state.sendSmsBtnDisabled) {
        return false
      }
      if (!this.form.mobile) {
        this.$notify('请输入手机号')
        return false
      }
      if (!this.regularMobile.test(this.form.mobile)) {
        this.$notify('手机号格式不正确')
        return false
      }
      this.$refs.captcha.show(res => {
        this.$store
          .dispatch('sendSmsFun', {
            url: api.sendsms_auth_mobile_nocheck,
            mobile: this.form.mobile,
            type: 0,
            captcha: res
          })
          .then(response => {
            if (response.code === 200) {
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
    },
    handleSubmit () {
      if (!this.form.realname) {
        this.$notify('请输入真实姓名')
        return false
      }
      if (!this.form.mobile) {
        this.$notify('请输入手机号')
        return false
      }
      if (!this.regularMobile.test(this.form.mobile)) {
        this.$notify('手机号格式不正确')
        return false
      }
      if (!this.form.code) {
        this.$notify('请输入验证码')
        return false
      }
      if (!this.form.description) {
        this.$notify('请输入账号申诉描述')
        return false
      }
      let postData = { ...this.form }
      http
        .post(api.member_appeal, postData)
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$store.commit('clearCountDownFun')
            this.showResult = true
          } else {
            this.$notify(res.message)
          }
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.result_wrapper {
  padding-top: 115px;
  text-align: center;
  .res_ico {
    display: inline-block;
    font-size: 24px;
    padding: 8px 0 8px 50px;
    color: #333333;
    margin-bottom: 10px;
    background: url("../../assets/images/success.svg") 0 center no-repeat;
    background-size: 40px;
  }
  .res_txt {
    font-size: 15px;
    color: #666666;
    margin-top: 10px;
  }
}
.reg_wrapper {
  width: 323px;
  margin: 0 auto;
}
.bottom_tip {
  width: 323px;
  position: absolute;
  left: 50%;
  bottom: 28px;
  transform: translate(-50%, 0);
  font-size: 13px;
  .tip_title {
    color: #666666;
  }
  .tip_txt {
    color: #999999;
    margin-top: 10px;
  }
  .tel {
    color: #3388ff;
  }
}
.tx1 {
  padding: 55px 0 28px;
  font-size: 26px;
  color: #333333;
}
.field_cell_group {
  .field_cell:last-child {
    margin-bottom: 36px;
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
  height: 100%;
  color: #333333;
  font-size: 15px;
  &::placeholder {
    color: #c9c9c9;
  }
}
.reg_get_btn {
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
