<template>
  <el-dialog
    title=""
    width="300px"
    :visible.sync="showWeChatQrcode"
    :close-on-press-escape="false"
    :close-on-click-modal="false"
    @close="closeWeChatQrcode"
  >
    <div class="wx_img">
      <div>
        <img v-if="scanQrcodeImg" :src="scanQrcodeImg" width="215" height="215"/>
      </div>
      <p class="wx_p1">
        <span class="wx_p1_span1"></span>
        <span class="wx_p1_span2">微信扫一扫</span>
      </p>
      <p class="wx_p2">
        {{ weChatQrcodeContent }}
      </p>
    </div>
  </el-dialog>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {whetherPopupWeChatQrcodeWindow} from '@/utils'

export default {
  data() {
    return {
      showWeChatQrcode: false,
      weChatQrcodeContent: '',
      scanQrcodeImg: '',
      bind_timer:''
    }
  },
  methods: {
    // 开启弹窗
    handleOpen(val, content) {
      val = typeof val === 'string' ? val : ''
      if (!val || true === whetherPopupWeChatQrcodeWindow(val)) {
        var that = this
        http.post(api.member_account, {})
          .then(res => {
            if (parseInt(res.data.bind_weixin) === 0) {
              http.get(api.get_qrcode, {type: 'bind_weixin'}).then(res => {
                if (res.data) {
                  that.scanQrcodeImg = res.data
                  that.showWeChatQrcode = true
                  that.weChatQrcodeContent = content
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
    closeWeChatQrcode(){
      this.showWeChatQrcode = false
      clearInterval(this.bind_timer);
    },
    // 查看微信是否已绑定，绑定后自动关闭二维码
    loopCheckBind() {
      http.get(api.im_check_bind, {})
        .then(res => {
          if (res.code == 200) {
            if (res.data == 1) {
              this.showWeChatQrcode = false
              clearInterval(this.bind_timer);
              this.$message({type: 'success', message: '绑定成功'})
              this.$emit('closeWeChatQrcode')
            }
          }
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.el-dialog__wrapper{
  line-height: initial;
}
.wx_img {
  text-align: center;
}

.wx_img > div {
}

.wx_img {
  .wx_p1 {
    font-size: 14px;
    color: #4CBF00;
    display: flex;
    align-items: center;
    justify-content: center;

    .wx_p1_span1 {
      background: url('../assets/images/weixin_ico.png') center center no-repeat;
      display: inline-block;
      width: 16px;
      height: 16px;
      background-size: 100% 100%;
      margin-right: 8px;
    }
  }

  .wx_p2 {
    font-size: 16px;
    color: #000000;
    box-sizing: border-box;
    padding: 15px 0;
  }
}

::v-deep .el-dialog {
  border-radius: 15px;
}

::v-deep .el-dialog__header {
  background-color: #ffffff !important;
  border-radius: 15px;
}

::v-deep .el-dialog__body {
  padding: 0;
}
</style>
