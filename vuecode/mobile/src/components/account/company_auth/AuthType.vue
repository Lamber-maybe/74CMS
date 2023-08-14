<template>
  <div id="app">
    <Head>
      企业认证
    </Head>
    <div class="box_1">
      <div class="item" @click="handlerLicense">
        <div class="content">
          <div class="tx1">营业执照认证</div>
          <div class="tx2">认证营业执照，开启更多招聘功能</div>
          <div class="tx3">求职者更信赖通过认证的企业</div>
        </div>
        <div class="go">
          <div class="txt">立</div>
          <div class="txt">即</div>
          <div class="txt">认</div>
          <div class="txt">证</div>
        </div>
      </div>
      <div class="item" @click="showLegal = true">
        <div class="content">
          <div class="tx1">负责人身份认证</div>
          <div class="tx2">若没有营业执照，请选择负责人认证</div>
          <div class="tx3">上传身份证照片申请负责人认证</div>
        </div>
        <div class="go">
          <div class="txt">立</div>
          <div class="txt">即</div>
          <div class="txt">认</div>
          <div class="txt">证</div>
        </div>
      </div>
    </div>
    <van-popup
      :lazy-render="false"
      v-model="showLicense"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <AuthLicense
        ref="license"
        @closePopup="showLicense = false"
        @reload="reload"
      ></AuthLicense>
    </van-popup>
    <van-popup
      :lazy-render="false"
      v-model="showLegal"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <AuthLegalPersonal
        ref="legal"
        @closePopup="showLegal = false"
        @reload="reload"
      ></AuthLegalPersonal>
    </van-popup>
  </div>
</template>

<script>
import AuthLicense from '@/components/account/company_auth/AuthLicense'
import AuthLegalPersonal from '@/components/account/company_auth/AuthLegalPersonal'
export default {
  name: 'Auth',
  components: {
    AuthLicense,
    AuthLegalPersonal
  },
  data () {
    return {
      showLicense: false,
      showLegal: false
    }
  },
  methods: {
    initCB () {},
    handlerLicense () {
      this.showLicense = true
      this.$refs.license.initCB()
    },
    reload () {
      this.$emit('reload')
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  .item {
    .go {
      .txt {
        text-align: center;
        font-size: 16px;
        color: #ffffff;
        margin-bottom: 5px;
      }
      position: absolute;
      right: 0;
      top: 0;
      height: 100%;
      background-color: #22a5fb;
      width: 48px;
      padding-top: 13px;
    }
    .content {
      .tx3 {
        font-size: 15px;
        color: #666666;
        padding: 11px 0;
        border-top: 1px solid #f3f3f3;
      }
      .tx2 {
        font-size: 14px;
        color: #999999;
        margin-bottom: 15px;
      }
      .tx1 {
        font-size: 20px;margin-bottom: 9px;
        font-weight: bold;
        color: #333333;
      }
      width: 273px;
    }
    width: 100%;
    background-color: #ffffff;
    border-radius: 7px;
    overflow: hidden;
    padding: 16px 0 0 15px;
    position: relative;
    margin-bottom: 15px;
    border: 1px solid #f3f3f3;
    border-right: 0;
  }
  padding: 15px 15px 0;
}
</style>
