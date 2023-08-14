<template>
	<div class="browsing_wrapper">
		<p><slot></slot></p>
    <div v-if="showWeChatQrcode" class="follow_box" @click="popupWechatQrcodeWindow">
      <span class="follow_box_left"></span>
      <span class="follow_box_right">扫码绑定,随时查收投递信息</span>
    </div>

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef" @closeWeChatQrcode="closeWeChatQrcode"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
	</div>
</template>

<script>
import http from "@/utils/http";
import api from "@/api";
import WeChatQrcode from '@/components/WeChatQrcode'

export default {
  components: {
    WeChatQrcode
  },
  data() {
    return {
      showWeChatQrcode: false
    }
  },
  mounted() {
    // 验证是否展示
    http.post(api.member_account, {})
      .then(res => {
        if (parseInt(res.data.bind_weixin) === 0) {
          this.showWeChatQrcode = true
        }
      })
      .catch(() => {
      })
  },
  methods:{
    // 弹出微信二维码弹框
    popupWechatQrcodeWindow(val) {
      this.$refs.weChatQrcodeRef.handleOpen(val, '扫码绑定，随时随地接收简历信息')
    },
    closeWeChatQrcode() {
      this.showWeChatQrcode = false
    }
  }
}
</script>

<style lang="scss" scoped>
	.browsing_wrapper{
		display: flex;
		line-height: 40px;
		margin-bottom: 27px;
	}
	.browsing_wrapper>p{
		flex: 1;
		font-size: 17px;
		color: #333333;
		font-weight: bold;
	}

  .follow_box {
    margin-right: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;

    .follow_box_left{
      background: url('../../../../assets/images/weixin_ico.png') center center no-repeat;
      display: inline-block;
      width: 16px;
      height: 16px;
      background-size: 100% 100%;
    }

    .follow_box_right {
      font-size: 13px;
      color: #4CBF00;
      height: 20px;
      line-height: 20px;
      text-align: center;
      font-weight: bold;
      border-radius: 10px;
      margin-left: 3px;
    }
  }
</style>
