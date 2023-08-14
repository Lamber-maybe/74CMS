<template>
  <div class="login-container" :style="serviceBg">
    <el-form
      ref="loginForm"
      :model="loginForm"
      :rules="loginRules"
      class="login-form"
      auto-complete="on"
      label-position="left"
    >
      <div class="title-container">
        <div class="t_wel">欢迎登录</div>
        <div class="t_site">{{ $store.state.config.sitename }}管理中心</div>
      </div>

      <el-form-item prop="username">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input
          ref="username"
          v-model="loginForm.username"
          placeholder="请输入用户名"
          name="username"
          type="text"
          tabindex="1"
          auto-complete="on"
        />
      </el-form-item>

      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :key="passwordType"
          ref="password"
          v-model="loginForm.password"
          :type="passwordType"
          placeholder="请输入密码"
          name="password"
          tabindex="2"
          auto-complete="on"
        />
        <span class="show-pwd" @click="showPwd">
          <svg-icon
            :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'"
          />
        </span>
      </el-form-item>
      <el-form-item prop="code">
        <el-input
          v-model="loginForm.code"
          placeholder="请输入验证码"
          name="code"
          auto-complete="off"
          style="float:left;width:58%"
          @keyup.enter.native="handleLogin"
        />

        <img
          :src="captchaSrc"
          alt="点击刷新"
          class="cap_img"
          @click="refreshCaptcha"
        >
      </el-form-item>

      <el-button
        :loading="loading"
        type="primary"
        @click.native.prevent="handleLogin"
      >
        立即登录
      </el-button>
      <p class="bt_link"><a :href="mobileUrl" class="">使用移动端管理后台</a></p>
    </el-form>
    <div class="login_copyright">{{ $store.state.config.bottom_other }}</div>
  </div>
</template>

<script>
import { getCaptcha } from '@/api/user'

export default {
  name: 'Login',
  data() {
    const validatePassword = (rule, value, callback) => {
      if (value.length < 1) {
        callback(new Error('请输入密码'))
      } else {
        callback()
      }
    }
    return {
      captchaSrc: '',
      loginForm: {
        username: '',
        password: '',
        code: '',
        secret_str: ''
      },
      loginRules: {
        username: [
          { required: true, trigger: 'blur', message: '请输入用户名' }
        ],
        password: [
          {
            required: true,
            trigger: 'blur',
            validator: validatePassword
          }
        ],
        code: [{ required: true, trigger: 'blur', message: '请输入验证码' }]
      },
      loading: false,
      passwordType: 'password',
      redirect: undefined,
      serviceBg: {
        backgroundImage: 'url(' + require('../../assets/images/login/' + this.getRandomInt(1, 5) + '.jpg') + ')',
        backgroundRepeat: 'no-repeat',
        backgroundSize: '100% 100%',
        backgroundPosition: 'center center'
      },
      mobileUrl: ''
    }
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect
      },
      immediate: true
    }
  },
  created() {
    this.refreshCaptcha()
    this.mobileUrl = this.$store.state.config.sitedomain + this.$store.state.config.sitedir + 'adminm'
  },
  methods: {
    getRandomInt(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min
    },
    refreshCaptcha() {
      getCaptcha()
        .then(res => {
          this.loginForm.secret_str = res.data.secret_str
          this.captchaSrc = res.data.src
        })
        .catch(() => {})
    },
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = ''
      } else {
        this.passwordType = 'password'
      }
      this.$nextTick(() => {
        this.$refs.password.focus()
      })
    },
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true
          this.$store
            .dispatch('user/login', this.loginForm)
            .then(() => {
              this.$router.push({ path: this.redirect || '/' })
              this.loading = false
            })
            .catch(() => {
              this.refreshCaptcha()
              this.loading = false
            })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    }
  }
}
</script>

<style lang="scss">
/* 修复input 背景不协调 和光标变色 */
/* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

$bg: #283443;
$light_gray: #fff;
$cursor: #fff;

@supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
  .login-container .el-input input {
    color: $cursor;
  }
}

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    height: 48px;
    width: 85%;
    vertical-align: middle;

    input {
      background: transparent;
      border: 0;
      -webkit-appearance: none;
      border-radius: 0;
      padding: 12px 5px 12px 15px;
      color: #333;
      height: 48px;
      caret-color: #333;

      &:-webkit-autofill {
        box-shadow: 0 0 0 1000px #fff inset !important;
        -webkit-text-fill-color: #333 !important;
      }

      &::placeholder {
        color: #d2d2d2;
      }
    }
  }

  .el-form-item {
    border: 1px solid #dcdfe6;
    border-radius: 5px;
    color: #454545;
    margin-bottom: 25px;
    transition:border-color .2s cubic-bezier(.645,.045,.355,1);
    &:hover {
      border-color:#c0c4cc;
    }
  }

  .el-button--primary {
    padding: 15px 0;font-size: 16px;width: 100%;
  }

  .el-form-item__content {
    line-height: normal;
  }
}
</style>

<style lang="scss" scoped>
$bg: #2d3a4b;
$dark_gray: #889aa4;
$light_gray: #eee;
$color_white: #ffffff;

.login-container {
  min-height: 100%;
  width: 100%;
  overflow: hidden;
  .bt_link {
    text-align: center;padding-top: 5px;
    a {
      color: rgb(102,177,255);font-size: 15px;
      &:hover { color: #469ffc; }
    }
  }

  .login-form {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 540px;
    height: 465px;
    max-width: 100%;
    padding: 40px 70px 0;
    overflow: hidden;
    background-color: $color_white;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, .12), 0 0 6px rgba(0, 0, 0, .04)
  }

  .login_copyright {
    position: absolute;
    left: 0;
    bottom: 36px;
    width: 100%;
    line-height: 1;
    font-size: 16px;
    color: #fff;
    text-align: center;
  }

  .tips {
    font-size: 14px;
    color: #fff;
    margin-bottom: 10px;

    span {
      &:first-of-type {
        margin-right: 16px;
      }
    }
  }

  .svg-container {
    padding: 8px 5px 6px 15px;
    color: #cccccc;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
  }

  .title-container {
    position: relative;text-align: center;
    .t_wel {
      font-size: 26px;line-height: 1;color: #333;margin-bottom: 10px;
    }
    .t_site {
      font-size: 14px;line-height: 1;color: #999;margin-bottom: 26px;
    }
  }

  .show-pwd {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }

  .cap_img {
    float:left;width:42%;height:48px;
  }
}
</style>
