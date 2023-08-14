<template>
  <div id="app">
    <Head
      :goback_custom="true"
      @gobackCustomMethod="$emit('closePopout', email_original)"
    >
      邮箱认证
    </Head>
    <div class="form_split_10"></div>
    <div class="notice_bar" v-if="email_original != ''">
      当前邮箱已验证，修改邮箱后您的登录邮箱将同步修改
    </div>
    <div class="log_wrapper">
      <div class="field_cell_group">
        <div class="field_cell">
          <label>
            <input
              class="log_field"
              type="text"
              placeholder="请输入新邮箱"
              v-model="newEmail"
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
          <button class="log_get_btn" @click="sendEmail">
            {{ $store.state.sendEmailBtnText }}
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
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'ResetEmail',
  props: ['email_original'],
  data () {
    return {
      checked: true,
      newEmail: '',
      code: '',
      regularEmail: /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
    }
  },
  methods: {
    initCB () {},
    // 提交
    handleSubmit () {
      let that = this
      if (!this.newEmail) {
        this.$notify('请填写邮箱')
        return false
      }
      if (!this.regularEmail.test(this.newEmail)) {
        this.$notify('邮箱格式不正确')
        return false
      }
      if (this.newEmail === this.email_original) {
        this.$notify('新邮箱与原邮箱相同')
        return false
      }
      if (!this.code) {
        this.$notify('请填写验证码')
        return false
      }
      http
        .post(api.reset_email, {
          email: this.newEmail,
          code: this.code
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            setTimeout(() => {
              that.$emit('closePopout', this.newEmail)
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
    sendEmail () {
      if (this.$store.state.sendEmailBtnDisabled) {
        return false
      }
      if (!this.newEmail) {
        this.$notify('请输入邮箱')
        return false
      }
      if (!this.regularEmail.test(this.newEmail)) {
        this.$notify('邮箱格式不正确')
        return false
      }
      if (this.email_original == this.newEmail) {
        this.$notify('新邮箱与旧邮箱相同')
        return false
      }
      this.$store
        .dispatch('sendEmailFun', {
          url: api.sendmail_bind,
          email: this.newEmail,
          type: this.$store.state.LoginType
        })
        .then(res => {
          if (res.code == 200) {
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
.notice_bar {
  width: 100%;
  padding: 11px 0 11px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
  margin-bottom: 15px;
}
.log_wrapper {
  width: 323px;
  margin: 17px auto;
}
.tx1 {
  padding: 55px 0 48px;
  font-size: 26px;
  color: #333333;
}
.field_cell {
  width: 100%;
  border-bottom: 1px solid #eeeeee;
  position: relative;
  margin-bottom: 20px;
}
.log_field {
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
.log_get_btn {
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
