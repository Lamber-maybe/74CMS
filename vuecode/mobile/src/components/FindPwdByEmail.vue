<template>
  <div id="app">
    <div class="reg_wrapper">
      <div class="tx1">邮箱找回密码</div>
      <div class="t_box">
        <router-link class="swb" :class="$route.params.utype==2?'active':''" to="/member/forget/2">求职者用户</router-link>
        <router-link class="swb" :class="$route.params.utype==1?'active':''" to="/member/forget/1">企业招聘用户</router-link>
      </div>
      <div class="field_cell_group">
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="text"
              placeholder="请输入邮箱"
              autocomplete="off"
              v-model="email"
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
          <button class="reg_get_btn" @click="sendEmail" :style="'color:'+$store.state.sendEmailBtnTextColor">
            {{ $store.state.sendEmailBtnText }}
          </button>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="reg_field"
              type="password"
              placeholder="新密码"
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
      <van-button type="info" size="large" round @click="handleSubmit"
      >提交</van-button
      >
      <div class="other_type" @click="$emit('changeMethod', 'mobile')">
        手机找回密码
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'FindPwdByEmail',
  props: ['utype'],
  data () {
    return {
      checked: true,
      email: '',
      code: '',
      password: '',
      repeatPassword: '',
      regularEmail: /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
      sendEmailLimit: false
    }
  },
  mounted () {
    this.$store.commit('clearCountDownFunEmail')
  },
  methods: {
    // 提交之前的验证
    handleSubmit () {
      if (!this.email) {
        this.$notify('请输入邮箱')
        return false
      }
      if (!this.regularEmail.test(this.email)) {
        this.$notify('邮箱格式不正确')
        return false
      }
      if (!this.code) {
        this.$notify('请输入验证码')
        return false
      }
      if (!this.password) {
        this.$notify('请输入新密码')
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
      let postData = {
        email: this.email,
        code: this.code,
        password: this.password,
        utype: this.utype
      }
      http
        .post(api.set_pwd_byemail, postData)
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$store.commit('clearCountDownFunEmail')
            this.$emit('handlerFinish')
          } else {
            this.$notify(res.message)
            return false
          }
        })
        .catch(() => {})
    },
    // 发送验证码
    sendEmail () {
      if (this.$store.state.sendEmailBtnDisabled) {
        return false
      }
      if (!this.email) {
        this.$notify('请输入邮箱')
        return false
      }
      if (!this.regularEmail.test(this.email)) {
        this.$notify('手机号格式不正确')
        return false
      }
      if (this.sendEmailLimit) {
        return false
      }
      this.sendEmailLimit = true
      this.$store
        .dispatch('sendEmailFun', {
          url: api.sendmail_forget,
          email: this.email,
          type: this.utype
        })
        .then(res => {
          this.sendEmailLimit = false
          if (res.code === 200) {
            this.$notify({
              type: 'success',
              message: this.$store.state.sendEmailMessage
            })
          } else {
            this.$notify(this.$store.state.sendEmailMessage)
            return false
          }
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
      background: #f0f0f0 url("../assets/images/sw_fw_ic1.png") 20px center no-repeat;background-size: 17px;
      &.active {
        background: #ebf5ff url("../assets/images/sw_fw_ic2.png") 20px center no-repeat;background-size: 17px;color: #3388ff;
      }
    }
  }
.other_type {
  text-align: center;
  font-size: 16px;
  color: #333333;
  padding-top: 23px;
}
.reg_wrapper {
  width: 323px;
  margin: 0 auto;
}
.tx1 {
  padding: 50px 0 23px;
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
