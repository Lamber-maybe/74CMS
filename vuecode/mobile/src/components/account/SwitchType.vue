<template>
  <div id="app">
    <Head
      :goback_custom="true"
      @gobackCustomMethod="$emit('closeSwitchType')"
    ></Head>
    <div class="top_head">切换身份</div>
    <div class="box_1" v-if="target_utype === 2 && showLoginContent">
      <div class="box_ava"></div>
      <div class="box_content">
        <div class="tx1">求职者</div>
        <div class="tx2">免费创建简历</div>
        <div class="tx2">职位海量淘</div>
        <div class="tx2">简历轻松投</div>
      </div>
      <div class="box_btn_wrapper">
        <van-button
          class="btn_mb"
          type="info"
          size="large"
          round
          @click="showLoginFun"
        >
          切换到求职者
        </van-button>
        <van-button
          type="info"
          size="large"
          round
          plain
          hairline
          @click="$emit('closeSwitchType')"
        >
          返回企业会员中心
        </van-button>
      </div>
    </div>
    <div class="box_1" v-if="target_utype === 1 && showLoginContent">
      <div class="box_ava"></div>
      <div class="box_content">
        <div class="tx1">招聘者</div>
        <div class="tx2">发布招聘信息</div>
        <div class="tx2">寻找优秀人才</div>
        <div class="tx2">获取更多资源</div>
      </div>
      <div class="box_btn_wrapper">
        <van-button
          class="btn_mb"
          type="info"
          size="large"
          round
          @click="showLoginFun"
        >
          切换到企业
        </van-button>
        <van-button
          type="info"
          size="large"
          round
          plain
          hairline
          @click="$emit('closeSwitchType')"
        >
          返回求职者会员中心
        </van-button>
      </div>
    </div>
    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <Login
        :utype="target_utype"
        :single_login="true"
        @afterLogin="afterLogin"
        after_login_data=""
        :goback_custom="true"
        @gobackCustomMethod="closeLogin"
      ></Login>
    </van-popup>
  </div>
</template>

<script>
import Login from '@/components/Login'
export default {
  name: 'SwitchType',
  props: ['target_utype'],
  components: {
    Login
  },
  data () {
    return {
      showLogin: false,
      showLoginContent: true
    }
  },
  methods: {
    showLoginFun () {
      this.showLogin = true
      this.showLoginContent = false
    },
    closeLogin () {
      this.showLogin = false
      this.showLoginContent = true
    },
    afterLogin (data) {
      if (this.target_utype == 1) {
        this.$router.push('/member/company/index')
      } else {
        this.$router.push('/member/personal/index')
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.box_btn_wrapper {
  width: 270px;
  margin: 0 auto;
  .btn_mb {
    margin-bottom: 10px;vertical-align: top;
  }
}
.top_head {
  padding: 18px 11px;
  text-align: center;
  font-size: 30px;
  color: #333333;
  background: url("../../assets/images/switch_member_top_bg.png") center 0
    no-repeat;
  background-size: 227.5px 41.5px;
}
.box_1 {
  width: 350.5px;
  height: 480px;
  margin: 0 auto;
  position: relative;
  padding-top: 90px;
  background: url("../../assets/images/switch_wrapper_bg.png") 0 no-repeat;
  background-size: 350.5px 480px;
  .box_ava {
    position: absolute;
    left: 50%;
    top: 48px;
    width: 100px;
    height: 100px;
    transform: translate(-50%, 0);
    background: url("../../assets/images/switch_member_a1.png") 0 no-repeat;
    background-size: 100px;
  }
  .box_content {
    width: 260px;
    background: #ffffff;
    margin: 0 auto 16px;
    text-align: center;
    box-shadow: 0 1px 5px #c2c2c2;
    border-radius: 10px;
    padding: 74px 0 18px;
    .tx1 {
      font-size: 22px;
      color: #333333;
      display: inline-block;
      padding: 0 45px;
      position: relative;
      margin-bottom: 10px;
      &::before {
        content: " ";
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translate(0, -50%);
        width: 35px;
        height: 10.5px;
        background: url("../../assets/images/switch_member_title_bg1.png") 0
          no-repeat;
        background-size: 35px 10.5px;
      }
      &::after {
        content: " ";
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translate(0, -50%);
        width: 35px;
        height: 10.5px;
        background: url("../../assets/images/switch_member_title_bg2.png") 0
          no-repeat;
        background-size: 35px 10.5px;
      }
    }
    .tx2 {
      font-size: 15px;
      color: #999999;
      &:not(:last-child) {
        margin-bottom: 11px;
      }
    }
  }
}
</style>
