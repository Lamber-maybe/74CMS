<template>
  <div class="bottom_group">
    <div class="bottom_bar_wrapper">
      <router-link class="bar_item index" to="/index">网站首页</router-link>
      <router-link class="bar_item job" to="/shortvideo/companylist">视频招聘</router-link>
      <div class="bar_item release" @click="linkrelease"></div>
      <router-link class="bar_item resume"  to="/shortvideo/personalList">视频求职</router-link>
      <div class="bar_item my" @click="myvideo">我的视频</div>
    </div>
  </div>
</template>
<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'BottomNav',
  data () {
    return {
      mineTo: '/member/login'
    }
  },
  mounted () {
    // 根据登录会员类型，处理导航显示
    if (this.$store.state.LoginOrNot) {
      if (parseInt(this.$store.state.LoginType) == 1) {
        // 企业
        this.mineTo = '/member/company/index'
      } else {
        // 个人
        this.mineTo = '/member/personal/index'
      }
    }
  },
  methods: {
    linkrelease () {
      if (parseInt(this.$store.state.config.shortvideo_enable) == 1) {
        if (this.$store.state.LoginOrNot == true) {
          if (this.$store.state.LoginType == 1) {
            http.get(api.shortvideo_upload).then(res => {
              if (res.code == 200) {
                this.$router.push('/shortvideo/release')
              } else {
                this.$notify(res.message)
              }
            }).catch(() => {})
          } else {
            this.$router.push('/shortvideo/release')
          }
        } else {
          this.$router.push('/member/login')
        }
      } else {
        var that = this
        that.$dialog
          .confirm({
            title: '系统提示',
            message: '抱歉，暂不支持发布视频'
          })
          .then(() => {
          })
          .catch(() => {})
      }
    },
    myvideo () {
      if (this.$store.state.LoginOrNot == true) {
        this.$router.push('/shortvideo/videoManage')
      } else {
        this.$router.push('/member/login')
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bottom_group {
  position: relative;
  width: 100%;
  height: 55px;
  background-color: #f2f4f3;
  .bottom_bar_wrapper {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    display: flex;
    z-index: 9;
    box-shadow: 0px 0px 10px 1px #d5d5d5;
    &::after {
      position: fixed; box-sizing: border-box; content: ' '; pointer-events: none; right: 0; bottom: 55px; left: 0;
      border-bottom: 1PX solid #d5d5d5; -webkit-transform: scaleY(.5); transform: scaleY(.5);
    }
    .bar_item {
      &.index {
        background: url("../../../assets/images/shortvideo/icon_indextwo.png") center 9px no-repeat;
        background-size: 23px 22px;
      }
      &.job {
        background: url("../../../assets/images/shortvideo/icon_jobtwo.png") center 10px no-repeat;
        background-size: 22px 19px;
      }
      &.release {
        background: url("../../../assets/images/shortvideo/release_video.png") center center no-repeat;
        background-size: 54px 54px;
      }
      &.resume {
        background: url("../../../assets/images/shortvideo/icon_resumetwo.png") center 9px no-repeat;
        background-size: 19px 22px;
      }
      &.my {
        background: url("../../../assets/images/shortvideo/icon_metwo.png") center 9px no-repeat;
        background-size: 23px 22px;
      }
      &.router-link-active {
        &.index {
          background: url("../../../assets/images/shortvideo/icon_indexthree.png") center 9px no-repeat;
          background-size: 23px 22px;
        }
        &.job {
          background: url("../../../assets/images/shortvideo/icon_jobthree.png") center 10px no-repeat;
          background-size: 22px 19px;
        }
        &.resume {
          background: url("../../../assets/images/shortvideo/icon_resumethree.png") center 9px no-repeat;
          background-size: 19px 22px;
        }
        &.my {
          background: url("../../../assets/images/shortvideo/icon_methree.png") center 9px no-repeat;
          background-size: 23px 22px;
        }
        color: #376ffd;
      }
      flex: 1;
      font-size: 10px;
      color: #666666;
      padding: 35px 0 6px;
      height: 55px;
      text-align: center;
    }
  }
}
</style>
