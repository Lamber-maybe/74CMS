<template>
  <div class="bottom_group">
    <div class="bottom_bar_wrapper">
      <router-link class="bar_item index" to="/index">首页</router-link>
      <router-link class="bar_item job" to="/shortvideo/companylist">招聘</router-link>
      <div class="bar_item release" @click="linkrelease"></div>
      <router-link class="bar_item resume"  to="/shortvideo/personalList">求职</router-link>
      <div class="bar_item my" @click="myvideo">我的</div>
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
  .bottom_bar_wrapper {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba($color: #000000, $alpha: 0);
    display: flex;
    z-index: 9;
    .bar_item {

      &.release {
        background: url("../../../assets/images/shortvideo/release_video.png") center center no-repeat;
        background-size: 54px 54px;
      }
      &.router-link-active {
        color: #376ffd;
      }
      flex: 1;
      font-size: 16px;
      color: #FFFFFF;
      line-height: 55px;
      height: 55px;
      text-align: center;
    }
  }
}
</style>
