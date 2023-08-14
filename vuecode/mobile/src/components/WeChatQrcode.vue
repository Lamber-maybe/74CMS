<template>
  <van-popup class="van-popup-box" v-model="showWeChatQrcode">
    <!-- 企业 -->
    <div class="wechat_box_1" v-if="wechatBoxType == 1">
      <div class="qrcode_box">
        <img v-if="scanQrcodeImg" :src="scanQrcodeImg"/>
      </div>
      <div class="txt_box">长按识别二维码 或 长按保存图片扫一扫</div>
      <div class="close_box" @click="showWeChatQrcode = false"></div>
    </div>
    <!-- 个人 -->
    <div class="wechat_box_2" v-if="wechatBoxType == 2">
      <div class="txt_box">长按识别二维码 或 长按保存图片扫一扫</div>
      <div class="qrcode_box">
        <img v-if="scanQrcodeImg" :src="scanQrcodeImg"/>
      </div>
      <div class="close_box" @click="showWeChatQrcode = false"></div>
    </div>
    <!-- 通用 -->
    <div class="wechat_box_3" v-if="wechatBoxType == 3">
      <div class="qrcode_box">
        <div class="txt_box_title">扫码关注公众号，获取最新动态</div>
        <img v-if="scanQrcodeImg" :src="scanQrcodeImg"/>
        <div class="txt_box">长按识别二维码 或 长按保存图片扫一扫</div>
      </div>
      <div class="close_box" @click="showWeChatQrcode = false"></div>
    </div>
  </van-popup>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {whetherPopupWeChatQrcodeWindow} from '@/utils/index'

export default {
  props: ['content'],
  data() {
    return {
      showWeChatQrcode: false,
      scanQrcodeImg: '',
      wechatBoxType: 3,
      bind_timer: ''
    }
  },
  methods: {
    // 开启弹窗
    handleOpen(val, type = 3) {
      // type [弹窗类型：1|企业，2|个人，3|通用]
      type = !type ? 3 : type
      val = typeof val === 'string' ? val : ''
      if (!val || true === whetherPopupWeChatQrcodeWindow(val)) {
        var that = this
        http.post(api.member_account, {})
          .then(res => {
            if (parseInt(res.data.bind_weixin) === 0) {
              http.get(api.get_qrcode, {type: 'bind_weixin'}).then(res => {
                if (res.data) {
                  that.scanQrcodeImg = res.data
                  that.wechatBoxType = type
                  that.showWeChatQrcode = true
                  if (val) {
                    whetherPopupWeChatQrcodeWindow(val, true)
                  }
                  that.bind_timer = setInterval(that.loopCheckBind, 3000);
                }
              })
            }
          })
          .catch(() => {
          })
      }
    },
    // 关闭弹窗
    closeWeChatQrcode() {
      this.showWeChatQrcode = false
      clearInterval(this.bind_timer);
    },
    // 查看微信是否已绑定，绑定后自动关闭二维码
    loopCheckBind() {
      http.get(api.imCheckBind, {})
        .then(res => {
          if (res.code == 200) {
            if (res.data == 1) {
              this.showWeChatQrcode = false
              clearInterval(this.bind_timer);
              this.$notify({type: 'success', message: '绑定成功'})
            }
          }
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.van-popup-box {
  overflow-y: initial;
  background: transparent;
}

.wechat_box_1 {
  width: 270px;
  height: 340px;
  background: url(../assets/images/wechat_window/company.png) center 0 no-repeat;
  border-radius: 0.266667rem;
  background-size: 100% 100%;
  padding-bottom: 0.8rem;
  padding-top: 143px;

  .txt_box {
    width: 100%;
    text-align: center;
    font-size: 12px;
    color: #FF5F36;
    margin-top: 8px;
  }

  .qrcode_box {
    width: 60%;
    margin: 0 auto;
    box-sizing: border-box;
    padding: 5px;
    background: #fff;
    box-shadow: 0 1px 9px 0 rgba(194, 194, 194, 0.48);
    border-radius: 12px;

    img {
      display: block;
      width: 100%;
      height: 100%;
    }
  }
}

.wechat_box_2 {
  width: 270px;
  height: 340px;
  background: url(../assets/images/wechat_window/user.png) center 0 no-repeat;
  border-radius: 0.266667rem;
  background-size: 100% 100%;
  padding-bottom: 0.8rem;
  padding-top: 30px;

  .txt_box {
    width: 100%;
    text-align: center;
    font-size: 12px;
    color: #fff;
    margin-bottom: 15px;
  }

  .qrcode_box {
    width: 60%;
    margin: 0 auto;
    box-sizing: border-box;
    padding: 5px;
    background: #fff;
    box-shadow: 0 1px 9px 0 rgba(194, 194, 194, 0.48);
    border-radius: 12px;

    img {
      display: block;
      width: 100%;
      height: 100%;
    }
  }
}

.wechat_box_3 {
  width: 290px;
  height: 290px;
  background: linear-gradient(-70deg, #DDF6FF, #F9F7FF);
  box-shadow: 0 22px 27px 0 rgba(0, 0, 0, 0.18);
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;

  .qrcode_box {
    width: 90%;
    height: 90%;
    background: #fff;
    box-shadow: 0 1px 9px 0 rgba(194, 194, 194, 0.48);
    border-radius: 3px;
    display: flex;
    align-items: center;
    flex-direction: column;

    img {
      display: block;
      width: 78%;
      height: 78%;
    }

    .txt_box_title{
      width: 100%;
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      color: #333;
      margin-top: 10px;
    }

    .txt_box {
      width: 100%;
      text-align: center;
      font-size: 12px;
      color: #FF5F36;
    }
  }
}

.close_box {
  background: url(../assets/images/wechat_window/close.png) center center no-repeat;
  background-size: 100% 100%;
  position: absolute;
  bottom: -45px;
  left: calc((100% - 30px) / 2);
  width: 30px;
  height: 30px;
}
</style>
