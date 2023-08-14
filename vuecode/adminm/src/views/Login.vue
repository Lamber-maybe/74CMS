<template>
  <div class="body">
    <div class="log_wrapper">
      <div class="tx1">欢迎登录</div>
      <div class="tx2">{{globalConfig.sitename}}后台管理中心</div>
      <van-form @submit="onSubmit">
      <div class="field_cell_group">
        <div class="field_cell">
          <label>
            <van-field class="log_field" type="text" v-model="username" label="" placeholder="请输入用户名"  :rules="[{ required: true, message: '' }]" />
          </label>
        </div>
        <div class="field_cell">
          <label>
            <van-field class="log_field" type="password" v-model="password" label="" placeholder="请输入登录密码"  :rules="[{ required: true, message: '' }]" />
          </label>
        </div>
        <div class="field_cell for_img">
          <label>
            <van-field class="log_field" type="text" v-model="code" label="" placeholder="请输入验证码"  :rules="[{ required: true, message: '' }]" />
          </label>
          <img :src="captchaSrc" class="img" @click="captcha">
        </div>
      </div>
      <div class="btn_group">
        <van-button
          class="btn_mb"
          type="info"
          size="large"
          round
          native-type="submit"
        >
          管理员登录
        </van-button>
        <van-button
          v-if="checkWeixin"
          size="large"
          round
          plain
          hairline
          color="#1aad19"
          class="for_wx"
          @click="weixinLogin"
        >
          <p class="wx_btn">微信快捷登录</p>
        </van-button>
      </div>
      </van-form>
    </div>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import { isWeiXin } from '@/utils/index'
export default {
  name: 'Login',
  data () {
    return {
      checkWeixin:false,
      globalConfig:{},
      username: '',
      password: '',
      code:'',
      secret_str:'',
      captchaSrc:''
    }
  },
  created () {
    if(isWeiXin()){
      this.checkWeixin = true
    }
    this.config()
    this.captcha()
  },
  methods: {
    config () {
      let that = this
      http
        .get(api.loginConfig, {})
        .then(res => {
          that.globalConfig = res.data
        })
        .catch(() => {})
    },
    captcha () {
      let that = this
      http
        .get(api.captcha, {})
        .then(res => {
          that.secret_str = res.data.secret_str
          that.captchaSrc = res.data.src
        })
        .catch(() => {})
    },
    onSubmit () {
      let that = this
      let data = {username:that.username,password:that.password,code:that.code,secret_str:that.secret_str}
      http
          .post(api.login, data)
          .then(res => {
            that.$toast.success(res.message)
            localStorage.setItem('admintoken',res.data.token)
            location.reload()
          })
          .catch(() => {})
    },
    weixinLogin(){
      this.$router.push('/login/oauth/weixin')
    }
  }
}
</script>

<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .for_wx {
    position: relative;
    .wx_btn {
      background: url("../assets/images/wx_ico.png") 0 no-repeat;background-size: 48px;
      padding-left: 58px;
    }
  }
  .text_content {
    font-size: 28px;
    padding: 40px;
  }
  .log_wrapper {
    width: 646px;
    margin: 0 auto;
  }
  .tx1 {
    padding: 100px 0 10px;
    font-size: 52px;
    color: #333333;
  }
  .tx2 {
    font-size: 28px;margin-bottom: 20px;
    color: #999999;
  }
  .field_cell_group {
    .field_cell:last-child {
      margin-bottom: 60px;
    }
  }
  .field_cell {
    width: 100%;
    border-bottom: 1PX solid #eeeeee;
    position: relative;
    padding-top: 40px;
  }
  .for_img {
    position: relative;
    .img {
      position: absolute;right: 0;bottom: 0;width: 300px;height: 80px;
    }
  }
  .log_field {
    border: 0;
    width: 100%;
    display: block;
    color: #333333;
    font-size: 30px;
    padding: 14px 0;
    &::placeholder {
      color: #c9c9c9;
    }
  }
  .btn_group{margin-bottom: 60px;}
  .btn_mb {
    margin-bottom: 32px;
  }
</style>
