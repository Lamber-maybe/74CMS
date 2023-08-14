<template>
  <div id="app">
      <AuthForm
        v-if="showAuth"
        :authinfo="authinfo"
        @reload="reload"
      ></AuthForm>
    <div v-else>
      <Head>
        企业认证
      </Head>
      <div class="form_split_10"></div>
      <div class="box" v-if="authinfo.audit == 1">
        <div class="ico success"></div>
        <div class="text">已通过营业执照认证</div>
      </div>
      <div class="box" v-if="authinfo.audit == 3">
        <div class="ico ing"></div>
        <div class="text_ing">我们将在1个工作日内审核您的资料信息</div>
      </div>
      <div class="box" v-if="authinfo.audit == 2">
        <div class="ico fail"></div>
        <div class="text">营业执照认证未通过审核！</div>
        <div class="text_red">
          原因：{{ authinfo.audit_reason }}，请重新认证。
        </div>
        <div class="btn">
          <van-button type="info" round block @click="handlerAuthRepeat">
            重新认证
          </van-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AuthForm from '@/components/account/company_auth/AuthForm'
export default {
  name: 'AuthResult',
  props: ['authinfo'],
  components: {
    AuthForm
  },
  data () {
    return {
      showAuth: false
    }
  },
  methods: {
    handlerAuthRepeat () {
      this.showAuth = true
    },
    reload () {
      this.showAuth = false
      this.$emit('reload')
    }
  }
}
</script>

<style lang="scss" scoped>
.box {
  .text_ing {
    font-size: 13px;
    color: #666666;
    padding-top: 12px;
  }
  .btn {
    padding: 66px 30px 0 30px;
  }
  .text_red {
    padding-top: 12px;
    font-size: 13px;
    color: #ff0000;
  }
  .text {
    font-size: 17px;
    font-weight: bold;
    color: #333333;
    padding-top: 14px;
  }
  .ico {
    &.ing {
      background: url("../../../assets/images/auth_ico_ing.png") 0 no-repeat;
      background-size: 55px;
    }
    &.success {
      background: url("../../../assets/images/auth_ico_success.png") 0 no-repeat;
      background-size: 55px;
    }
    &.fail {
      background: url("../../../assets/images/auth_ico_fail.png") 0 no-repeat;
      background-size: 55px;
    }
    width: 55px;
    height: 55px;
    margin: 0 auto;
  }
  width: 100%;
  text-align: center;
  padding-top: 96px;
}
</style>
