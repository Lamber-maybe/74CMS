<template>
  <div class="my_personal_menu">
    <div class="sign_in_box">
      <div class="tx1">{{points}}</div>
      <div class="tx2">{{$store.state.config.points_byname}}</div>
      <div class="sign not" @click="Signin" v-if="hasSign===false">未签到</div>
      <div class="sign already" @click="Signin" v-else>已签到</div>
    </div>
    <el-menu
      :default-active="activeName"
      router
      class="el-menu-vertical-demo"
      background-color="#fff"
      text-color="#617083"
      active-text-color="#1688fb"
    >
      <el-menu-item class="i1" index="/personal">
        <span slot="title">会员首页</span>
      </el-menu-item>
      <el-menu-item class="i2" index="/personal/resume">
        <span slot="title">我的简历</span>
      </el-menu-item>
      <el-menu-item class="i10" :class="$store.state.imUnreaded?'point':''" index="/personal/im">
        <span slot="title">我的职聊</span>
      </el-menu-item>
      <el-submenu class="i3" index="/personal/job">
        <template slot="title">
          <span  class="unread_prompt" :class="this.is_look_interview===1?'interview_new':''">求职管理</span>
        </template>
        <el-menu-item index="/personal/job/jobapply">我的投递</el-menu-item>
        <el-menu-item index="/personal/job/interview" class="unread_prompt" :class="this.is_look_interview===1?'new':''">面试邀请</el-menu-item>
        <el-menu-item index="/personal/job/interview_video">视频面试</el-menu-item>
        <el-menu-item index="/personal/job/attention_me">对我感兴趣</el-menu-item>
        <el-menu-item index="/personal/job/viewjob">我的足迹</el-menu-item>
      </el-submenu>
      <el-submenu class="i4" index="/personal/fav">
        <template slot="title">
          <span>收藏关注</span>
        </template>
        <el-menu-item index="/personal/fav/favjob">收藏的职位</el-menu-item>
        <el-menu-item index="/personal/fav/attention_company">关注的企业</el-menu-item>
        <el-menu-item index="/personal/fav/subscribe">职位订阅</el-menu-item>
      </el-submenu>
      <el-menu-item class="i5" index="/personal/recommend">
        <span slot="title">智能推荐</span>
      </el-menu-item>
      <el-menu-item class="i6" index="/personal/service">
        <span slot="title">增值服务</span>
      </el-menu-item>
      <el-menu-item class="i7" index="/personal/points">
        <span slot="title">我的积分</span>
      </el-menu-item>
      <el-menu-item class="i8" index="/personal/privacy">
        <span slot="title">隐私设置</span>
      </el-menu-item>
      <el-menu-item class="i9" index="/personal/account">
        <span slot="title">账号管理</span>
      </el-menu-item>
    </el-menu>
    <div class="sp_20_f3"></div>
    <div class="qr_box">
      <img :src="$store.state.config.wechat_qrcode" class="img">
      <div class="tx1">手机找工作</div>
      <div class="tx2">微信扫一扫，入职更快速</div>
    </div>

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import WeChatQrcode from '@/components/WeChatQrcode'

  export default {
    name: 'SideNav',
    components: {
      WeChatQrcode
    },
    data () {
      return {
        'is_look_interview': 0
      }
    },
    mounted(){
      this.fetchUserData()
    },
    created() {
      http.get(api.isLookApply).then(res => {
        this.is_look_interview = res.data.is_look_interview
      }).catch(() => {})
      this.userSignin()
    },
    computed: {
      hasSign(){
        return this.$store.state.userSignin
      },
      points(){
        return this.$store.state.userPoints
      },
      activeName () {
        const route = this.$route
        const { meta, path } = route
        // if set path, the sidebar will highlight the path you set
        if (meta.activeMenu) {
          return meta.activeMenu
        }
        return path
      },
    },
    methods: {
      fetchUserData () {
        http.get(api.member_points).then(res => {
          const { points, task } = res.data
          this.$store.commit('setUserPoints',{
            points:points
          })
          for (const iterator of task) {
            if (iterator.alias == 'sign_in' && iterator.is_done == 1) {
              this.$store.commit('setUserSignin', {
                signin: true
              })
              break
            }
          }
        })
      },
      Signin () {
        http.get(api.member_signin).then(res => {
          this.$store.commit('setUserSignin', {
            signin: true
          })
          this.$store.commit('setUserPoints',{
            points:res.data.points
          })
          this.$message({ type: 'success', message: res.message })
          /**
           * 【ID1000719】
           * 【新增】公众号引导弹窗场景（签到）
           * cy 2023-7-17
           */
          this.popupWechatQrcodeWindow('user_pc_sign_in')
        }).catch(() => {})
      },
      userSignin()
      {
        http.get(api.member_user_ignin).then(res => {
          if (res.data > 0)
          {
            this.$store.commit('setUserSignin', {
              signin: true
            })
          }else {
            this.$store.commit('setUserSignin', {
              signin: false
            })
          }
        }).catch(() => {})
      },
      // 弹出微信二维码弹框
      popupWechatQrcodeWindow(val) {
        this.$refs.weChatQrcodeRef.handleOpen(val, '扫码绑定公众号，求职快人一步')
      }
    }
  }
</script>

<style lang="scss">
  .my_personal_menu {
    background-color: #ffffff;

    .el-submenu__title:hover {
      background-color: #ffffff !important;
      color: #1688fb !important;
    }

    //设置鼠标悬停时el-menu-item的样式
    .el-menu-item:hover {
      background-color: #ffffff !important;
      color: #1688fb !important;
    }

    //设置选中el-menu-item时的样式
    .el-menu-item.is-active {
      background-color: #ffffff !important;
      color: #1688fb !important;
    }

    .el-submenu {
      .el-submenu__title {
        padding-left: 80px !important;

        span {
          font-size: 16px;
        }
      }

      .el-menu-item {
        font-size: 15px;
      }

      .el-submenu__icon-arrow {
        margin-top: -5px;
        color: #303030;
      }
    }

    .el-menu-item {
      padding-left: 80px !important;
      position: relative;

      span {
        font-size: 16px;
      }
    }

    .el-submenu {
      position: relative;
    }

    .sign_in_box {
      width: 224px;
      height: 74px;
      background: url("../../assets/images/personal/nav_sign_in_bg.png") 0 0 no-repeat;
      position: relative;
      padding: 13px 0 0 83px;

      .tx1 {
        font-size: 24px;
        color: #313131;
        margin-bottom: 7px;
      }

      .tx2 {
        color: #1e1e1e;
      }

      .sign {
        position: absolute;
        right: 0;
        top: 20px;
        width: 66px;
        height: 30px;
        line-height: 30px;
        color: #fff;
        cursor: pointer;
        border-radius: 30px 0 0 30px;
        padding-left: 18px;

        &.already {
          background-image: linear-gradient(to right, #fe721f, #ff9a1d)
        }

        &.not {
          background-image: linear-gradient(to right, #10c96b, #1bceaa)
        }
      }
    }

    .el-submenu__icon-arrow {
      right: 25px;
      font-size: 15px;
    }

    .el-submenu__title i {
      color: #9f9f9f;
    }

    .i1 {
      &::after {
        content: '';
        position: absolute;
        left: 40px;
        top: 20px;
        width: 16px;
        height: 16px;
        background: url("../../assets/images/menu/personal/1.png") 0 0 no-repeat;
        background-size: 16px 16px;
      }
    }

    .i2 {
      &::after {
        content: '';
        position: absolute;
        left: 39px;
        top: 20px;
        width: 21px;
        height: 16px;
        background: url("../../assets/images/menu/personal/2.png") 0 0 no-repeat;
        background-size: 21px 16px;
      }
    }

    .i3 {
      &::after {
        content: '';
        position: absolute;
        left: 44px;
        top: 21px;
        width: 11px;
        height: 17px;
        background: url("../../assets/images/menu/personal/3.png") 0 0 no-repeat;
        background-size: 11px 17px;
      }
      .unread_prompt{
        &.interview_new::after {
          content: '';
          position: absolute;
          left: 146px;
          top: 16px;
          width: 8px;
          height: 8px;
          background-image: linear-gradient(180deg,#ff420a,#ff420a);
          border-radius: 100%;
        }
        &.new::after {
          content: '';
          position: absolute;
          left: 146px;
          top: 16px;
          width: 8px;
          height: 8px;
          background-image: linear-gradient(180deg,#ff420a,#ff420a);
          border-radius: 100%;
        }
      }
    }

    .i4 {
      &::after {
        content: '';
        position: absolute;
        left: 41px;
        top: 20px;
        width: 16px;
        height: 16px;
        background: url("../../assets/images/menu/personal/4.png") 0 0 no-repeat;
        background-size: 16px 16px;
      }
    }

    .i5 {
      &::after {
        content: '';
        position: absolute;
        left: 40px;
        top: 19px;
        width: 19px;
        height: 19px;
        background: url("../../assets/images/menu/personal/5.png") 0 0 no-repeat;
        background-size: 19px 19px;
      }
    }

    .i6 {
      &::after {
        content: '';
        position: absolute;
        left: 39px;
        top: 18px;
        width: 19px;
        height: 19px;
        background: url("../../assets/images/menu/personal/6.png") 0 0 no-repeat;
        background-size: 19px 19px;
      }
    }

    .i7 {
      &::after {
        content: '';
        position: absolute;
        left: 42px;
        top: 18px;
        width: 17px;
        height: 19px;
        background: url("../../assets/images/menu/personal/7.png") 0 0 no-repeat;
        background-size: 17px 19px;
      }
    }

    .i8 {
      &::after {
        content: '';
        position: absolute;
        left: 41px;
        top: 18px;
        width: 17px;
        height: 19px;
        background: url("../../assets/images/menu/personal/8.png") 0 0 no-repeat;
        background-size: 17px 19px;
      }
    }

    .i9 {
      &::after {
        content: '';
        position: absolute;
        left: 40px;
        top: 18px;
        width: 18px;
        height: 19px;
        background: url("../../assets/images/menu/personal/9.png") 0 0 no-repeat;
        background-size: 18px 19px;
      }
    }
    .i10 {
      &::after {
        content: '';
        position: absolute;
        left: 40px;
        top: 20px;
        width: 20px;
        height: 19px;
        background: url("../../assets/images/menu/personal/10.png") 0 0 no-repeat;
        background-size: 20px 19px;
      }
    }

    .qr_box {
      width: 100%;
      background-color: #fff;
      padding: 38px 0;
      text-align: center;

      .img {
        display: block;
        width: 130px;
        height: 130px;
        margin: 0 auto 16px;
      }

      .tx1 {
        font-size: 16px;
        color: #2e425b;
        margin-bottom: 12px;
      }

      .tx2 {
        color: #9aadc3;
      }
    }
  }
    .i10.point span:after { content: ''; width: 8px; height: 8px; border-radius: 50%; background-image: linear-gradient(to bottom, #ff420a, #ff420a); position: absolute; left: 146px; top: 12px; z-index: 50 }

</style>
