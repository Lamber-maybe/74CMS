<template>

  <el-card>
    <personal-title class="personal-title-box">
      <div>求职管理</div>
      <div v-if="showWeChatQrcode" class="follow_box" @click="popupWechatQrcodeWindow">
        <span class="follow_box_left"></span>
        <span class="follow_box_right">扫码绑定,随时查收投递信息</span>
      </div>
    </personal-title>
     <warning :title="title"></warning>
    <menu-nav :active-tab="$route.path" :list="navList"></menu-nav>
    <router-view></router-view>

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef" @closeWeChatQrcode="closeWeChatQrcode"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
  </el-card>

</template>

<script>
  import warning from '@/components/Warning'
  import WeChatQrcode from '@/components/WeChatQrcode'
  import http from "@/utils/http";
  import api from "@/api";
  export default {
    components:{
      warning,
      WeChatQrcode
    },
    data() {
      return {
        navList: [
          { label: '我的投递', href: '/personal/job/jobapply', name: '/personal/job/jobapply', active: false },
          { label: '面试邀请', href: '/personal/job/interview', name: '/personal/job/interview', active: true },
          { label: '视频面试', href: '/personal/job/interview_video', name: '/personal/job/interview_video', active: false },
          { label: '对我感兴趣', href: '/personal/job/attention_me', name: '/personal/job/attention_me', active: false },
          { label: '我的足迹', href: '/personal/job/viewjob', name: '/personal/job/viewjob', active: false },
        ],
        title:'安全提醒：招聘企业无权收取任何费用，求职中请加强自我保护，避免上当受骗！',
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
