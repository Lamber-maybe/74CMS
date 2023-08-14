<template>
  <div class="box_follow">
    <img :src="$store.state.config.square_logo" class="logo" />
    <div class="tx1">{{ $store.state.config.wechat_name?$store.state.config.wechat_name:$store.state.config.sitename }}</div>
    <div class="tx2">及时获取最新招聘岗位信息</div>
    <van-button type="info" size="small" round @click="showWeixinQrcode=true">关注</van-button>
    <van-dialog v-model="showWeixinQrcode" title="关注公众号" confirm-button-text="知道了">
      <div class="dialog_wx_qr">
        <div class="tip orange">截屏保存二维码图片，关注公众号</div>
        <div class="qr_img"><img :src="$store.state.config.wechat_qrcode"/></div>
        <div class="tip_text" style="text-align:center;">微信内长按图片识别关注公众号。</div>
      </div>
    </van-dialog>
  </div>
</template>

<script>
export default {
  name: 'Subscribe',
  data () {
    return {
      showWeixinQrcode: false
    }
  },
  mounted () {},
  created () {},
  computed: {},
  methods: {
  }
}
</script>

<style lang="scss" scoped>
.box_follow {
  .van-button {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translate(0, -50%);
  }
  .tx2 {
    max-width: 210px;
    font-size: 13px;
    color: #999999;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .tx1 {
    margin-bottom: 6px;
    font-size: 17px;
    font-weight: bold;
    color: #333333;
    max-width: 210px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .logo {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translate(0, -50%);
    max-width: 45px;
    max-height: 45px;
    border: 0;
  }
  position: relative;
  width: 100%;
  background-color: #ffffff;
  padding: 13.5px 0 15px 73px;
}
</style>
