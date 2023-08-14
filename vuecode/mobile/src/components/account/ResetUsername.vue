<template>
  <div id="app">
    <Head
      :goback_custom="true"
      @gobackCustomMethod="$emit('closePopout', username)"
    >
      修改用户名
    </Head>
    <div class="box_title">用户名修改后，登录用户名将同步修改哦！</div>
    <div class="log_wrapper">
      <div class="field_cell_group">
        <div class="field_cell">
          <label>
            <input
              class="log_field"
              type="text"
              placeholder="user-363527235"
              v-model="username"
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
  name: 'ResetUsername',
  props: ['username_original'],
  data () {
    return {
      username: ''
    }
  },
  methods: {
    initCB () {
      this.username = this.username_original
    },
    handleSubmit () {
      let that = this
      if (!this.username) {
        this.$notify('请填写用户名')
        return false
      }
      if (this.username_original == this.username) {
        this.$notify('新用户名与旧用户名相同')
        return false
      }
      http
        .post(api.reset_username, {
          username: this.username
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            setTimeout(() => {
              that.$emit('closePopout', this.username)
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
.box_title {
  font-size: 12px;
  color: #999999;
  width: 100%;
  background-color: #f3f3f3;
  padding: 15px 16px;
  margin-bottom: 15px;
}
.log_wrapper {
  width: 323px;
  margin: 0 auto;
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
</style>
