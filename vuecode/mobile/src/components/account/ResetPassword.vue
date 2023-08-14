<template>
  <div id="app">
    <Head
      :goback_custom="true"
      @gobackCustomMethod="$emit('closePopout', is_set_password)"
    >
      {{ is_set_password == 1 ? "修改密码" : "设置登录密码" }}
    </Head>
    <div class="form_split_10"></div>
    <div class="notice_bar">
      账号密码{{
        is_set_password == 1 ? "修改" : "设置"
      }}成功后，请记住新密码以便下次登录时使用
    </div>
    <div class="log_wrapper">
      <div class="field_cell_group">
        <div class="field_cell" v-if="is_set_password == 1">
          <label>
            <input
              class="log_field"
              type="password"
              placeholder="原密码"
              v-model="old_password"
              autocomplete="off"
            />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="log_field"
              type="password"
              :placeholder="is_set_password == 1 ? '新密码' : '登录密码'"
              v-model="password"
              autocomplete="off"
            />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <input
              class="log_field"
              type="password"
              placeholder="确认密码"
              v-model="password_confirm"
              autocomplete="off"
            />
          </label>
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
  name: 'ResetPassword',
  props: ['is_set_password'],
  data () {
    return {
      old_password: '',
      password: '',
      password_confirm: ''
    }
  },
  methods: {
    initCB () {
      this.old_password = ''
      this.password = ''
      this.password_confirm = ''
    },
    // 提交
    handleSubmit () {
      let that = this
      if (!this.old_password && this.is_set_password == 1) {
        this.$notify('请填写原密码')
        return false
      }
      if (!this.password) {
        this.$notify('请填写新密码')
        return false
      }
      if (this.old_password === this.password && this.is_set_password == 1) {
        this.$notify('新密码与原密码相同')
        return false
      }
      if (!this.password_confirm) {
        this.$notify('请确认密码')
        return false
      }
      if (!Object.is(this.password_confirm, this.password)) {
        this.$notify('两次输入的密码不一致')
        return false
      }
      http
        .post(api.reset_password, {
          old_password: this.old_password,
          password: this.password,
          password_confirm: this.password_confirm
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            setTimeout(() => {
              that.$emit('closePopout', this.is_set_password)
            }, 2000)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
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
  margin-bottom: 20px;
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
