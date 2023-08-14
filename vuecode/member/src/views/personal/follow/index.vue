<template>
  <el-card>
    <personal-title class="personal-title-box">
      <div>收藏关注</div>
      <div v-if="showWeChatQrcode" class="follow_box" @click="popupWechatQrcodeWindow">
        <span class="follow_box_left"></span>
        <span class="follow_box_right">扫码绑定,随时查收投递信息</span>
      </div>
    </personal-title>
    <menu-nav :active-tab="$route.path" :list="navList"></menu-nav>
    <router-view></router-view>

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef" @closeWeChatQrcode="closeWeChatQrcode"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
  </el-card>
</template>

<script>
  import WeChatQrcode from '@/components/WeChatQrcode'
  import http from "@/utils/http";
  import api from "@/api";
  export default {
    components:{
      WeChatQrcode
    },
    data() {
      return {
        navList: [
          { label: '收藏的职位', href: '/personal/fav/favjob', name: '/personal/fav/favjob', active: true },
          { label: '关注的企业', href: '/personal/fav/attention_company', name: '/personal/fav/attention_company', active: false },
          { label: '职位订阅', href: '/personal/fav/subscribe', name: '/personal/fav/subscribe', active: false }
        ],
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
        this.$refs.weChatQrcodeRef.handleOpen(val, '扫码绑定公众号，求职快人一步')
      },
      closeWeChatQrcode() {
        this.showWeChatQrcode = false
      }
    }
  }
</script>

<style lang="scss" scoped>
::v-deep .personal-title-box{
  .text{
    display: flex;
    align-items: center;
    justify-content: space-between;

    .follow_box {
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;

      .follow_box_left{
        background: url('../../../assets/images/weixin_ico.png') center center no-repeat;
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
  }
}
</style>
