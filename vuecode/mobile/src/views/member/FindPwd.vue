<template>
  <div id="app">
    <Head></Head>
    <div v-if="!showResult">
      <FindPwdByMobile
        v-if="method == 'mobile'"
        :utype="utype"
        @changeMethod="changeMethod"
        @handlerFinish="handlerFinish"
      ></FindPwdByMobile>
      <FindPwdByEmail
        v-if="method == 'email'"
        :utype="utype"
        @changeMethod="changeMethod"
        @handlerFinish="handlerFinish"
      ></FindPwdByEmail>
    </div>
    <div class="bottom_tip" v-if="!showResult">
      <div class="tip_title">上面的方式都不可用？</div>
      <div class="tip_txt">
        你还可以进行
        <span class="tel" @click="$router.push('/member/appeal')"
          >账号申诉</span
        >
        或 电话联系我们
        <span class="tel">{{ $store.state.config.contact_tel }}</span>
      </div>
    </div>
    <div class="result_wrapper" v-if="showResult">
      <div class="res_ico">重置密码成功</div>
      <div class="res_txt">下次登录请使用新密码登录</div>
    </div>
    <div class="reg_wrapper_res" v-if="showResult">
      <router-link
        :to="'/member/login/' + (utype == 1 ? 'company' : 'personal')"
      >
        <van-button type="info" size="large" round>重新登录</van-button>
      </router-link>
    </div>
  </div>
</template>

<script>
import FindPwdByMobile from '@/components/FindPwdByMobile'
import FindPwdByEmail from '@/components/FindPwdByEmail'
export default {
  name: 'FindPwd',
  components: {
    FindPwdByMobile,
    FindPwdByEmail
  },
  data () {
    return {
      method: 'mobile',
      utype: 1,
      showResult: false
    }
  },
  watch: {
    $route (to, from) {
      this.utype = to.params.utype
    }
  },
  created () {
    this.utype = this.$route.params.utype
  },
  methods: {
    changeMethod (method) {
      this.method = method
    },
    handlerFinish () {
      this.showResult = true
    }
  }
}
</script>

<style lang="scss" scoped>
.bottom_tip {
  margin: 60px auto 0;
  width: 323px;
  font-size: 13px;
  .tip_title {
    color: #666666;
  }
  .tip_txt {
    color: #999999;
    margin-top: 5px;
  }
  .tel {
    color: #3388ff;
  }
}
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
.reg_wrapper_res {
  width: 323px;
  margin: 65px auto 0;
}
</style>
