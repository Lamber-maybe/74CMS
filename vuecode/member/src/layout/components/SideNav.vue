<template>
  <div class="my_menu com_menu">
    <el-menu
      :default-active="activeName"
      router
      class="el-menu-vertical-demo"
      background-color="#303439"
      text-color="#fff"
      active-text-color="#fff"
    >
      <el-menu-item class="i1" :style="{'padding-left':'84px'}" index="/company">
        <span slot="title">会员首页</span>
      </el-menu-item>
      <el-menu-item class="i2" :style="{'padding-left':'84px'}" index="/company/joblist">
        <span slot="title">职位管理</span>
      </el-menu-item>
      <el-menu-item class="i10" :class="$store.state.imUnreaded?'point':''" :style="{'padding-left':'84px'}" index="/company/im">
        <span slot="title">我的职聊</span>
      </el-menu-item>
      <el-submenu class="i3" index="/company/resume">
        <template slot="title">
          <span :style="{'padding-left':'64px'}">简历管理</span>
        </template>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/resume/jobapply">收到投递</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/resume/download">我的下载</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/resume/interview">面试邀请</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/resume/interview_video">视频面试</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/resume/fav">我的收藏</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/resume/browsing">浏览记录</el-menu-item>
      </el-submenu>
      <el-submenu class="i4" index="/company/service">
        <template slot="title">
          <span :style="{'padding-left':'64px'}">会员服务</span>
        </template>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/service/setmeal">我的套餐</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/service/increment">增值服务</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/service/order">我的订单</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/service/coupon">优惠券</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/service/point">我的{{$store.state.config.points_byname}}</el-menu-item>
      </el-submenu>
      <el-submenu class="i5" index="/company/manage">
        <template slot="title">
          <span :style="{'padding-left':'64px'}">企业管理</span>
        </template>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/manage/baseinfo">基本资料</el-menu-item>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/manage/img">企业风采</el-menu-item>
      </el-submenu>
      <el-menu-item class="i6" :style="{'padding-left':'84px'}" index="/company/recommend">
        <span slot="title">智能推荐</span>
      </el-menu-item>
      <el-submenu class="i9" index="/company/jobfair/network/index">
        <template slot="title">
          <span :style="{'padding-left':'64px'}">招聘会</span>
        </template>
        <el-menu-item :style="{'padding-left':'84px'}" index="/company/jobfair/network/index">网络招聘会</el-menu-item>
      </el-submenu>
      <el-menu-item class="i8" :style="{'padding-left':'84px'}" index="/company/account">
        <span slot="title">账号管理</span>
      </el-menu-item>
    </el-menu>
  </div>
</template>

<script>
  export default {
    name: 'SideNav',
    data () {
      return {}
    },
    computed: {
      activeName () {
        const route = this.$route
        const { meta, path } = route
        if (meta.activeMenu) {
          return meta.activeMenu
        }
        return path
      },
    },
    methods: {

      jumpTo (path) {
        this.$router.push(path)
      },
    },
  }
</script>

<style lang="scss">
  .com_menu {
    background-color: rgb(48, 52, 57);
    padding-top: 20px;
    height: 100%;

    .el-menu {
      border-right: 0;
    }

    .el-menu:hover {
      background-color: #1787fb !important;
      color: #ffffff !important;
    }

    .el-menu-item {
      font-size: 15px;
      padding-left: 84px;
      position: relative;

      span {
        font-size: 16px;
      }
    }

    //设置鼠标悬停时el-menu-item的样式
    .el-menu-item:hover {
      background-color: #1787fb !important;
      color: #ffffff !important;
    }

    //设置选中el-menu-item时的样式
    .el-menu-item.is-active {
      background-color: #1787fb !important;
      color: #ffffff !important;
    }

    .el-aside {
      background-color: #303439;
    }

    .el-submenu {
      position: relative;

      .el-submenu__title {
        span {
          font-size: 16px;
        }
      }
    }

    .el-submenu__icon-arrow {
      right: 40px;
      font-size: 16px;
    }

    .el-submenu__title i {
      color: #fff;
    }

    .i1 {
      &::after {
        content: '';
        position: absolute;
        left: 51px;
        top: 20px;
        width: 16px;
        height: 16px;
        background: url("../../assets/images/menu/company/1.png") 0 0 no-repeat;
        background-size: 16px 16px;
      }
    }

    .i2 {
      &::after {
        content: '';
        position: absolute;
        left: 51px;
        top: 20px;
        width: 17px;
        height: 15px;
        background: url("../../assets/images/menu/company/2.png") 0 0 no-repeat;
        background-size: 17px 15px;
      }
    }

    .i3 {
      &::after {
        content: '';
        position: absolute;
        left: 52px;
        top: 20px;
        width: 13px;
        height: 16px;
        background: url("../../assets/images/menu/company/3.png") 0 0 no-repeat;
        background-size: 13px 16px;
      }
    }

    .i4 {
      &::after {
        content: '';
        position: absolute;
        left: 50px;
        top: 20px;
        width: 18px;
        height: 16px;
        background: url("../../assets/images/menu/company/4.png") 0 0 no-repeat;
        background-size: 18px 16px;
      }
    }

    .i5 {
      &::after {
        content: '';
        position: absolute;
        left: 50px;
        top: 20px;
        width: 18px;
        height: 16px;
        background: url("../../assets/images/menu/company/5.png") 0 0 no-repeat;
        background-size: 18px 16px;
      }
    }

    .i6 {
      &::after {
        content: '';
        position: absolute;
        left: 50px;
        top: 20px;
        width: 19px;
        height: 18px;
        background: url("../../assets/images/menu/company/6.png") 0 0 no-repeat;
        background-size: 19px 18px;
      }
    }

    .i7 {
      &::after {
        content: '';
        position: absolute;
        left: 51px;
        top: 20px;
        width: 17px;
        height: 15px;
        background: url("../../assets/images/menu/company/7.png") 0 0 no-repeat;
        background-size: 17px 15px;
      }
    }

    .i8 {
      &::after {
        content: '';
        position: absolute;
        left: 52px;
        top: 20px;
        width: 15px;
        height: 15px;
        background: url("../../assets/images/menu/company/8.png") 0 0 no-repeat;
        background-size: 15px 15px;
      }
    }

    .i9 {
      &::after {
        content: '';
        position: absolute;
        left: 50px;
        top: 20px;
        width: 19px;
        height: 19px;
        background: url("../../assets/images/menu/company/9.png") 0 0 no-repeat;
        background-size: 19px 19px;
      }
    }
    .i10 {
      &::after {
        content: '';
        position: absolute;
        left: 50px;
        top: 20px;
        width: 17px;
        height: 17px;
        background: url("../../assets/images/menu/company/10.png") 0 0 no-repeat;
        background-size: 17px 17px;
      }
    }
    .my_menu {
      background-color: #303439;

      .el-collapse {
        border-top: none;
      }

      .el-collapse-item__wrap {
        border-bottom: none;

        .el-collapse-item__content {
          padding-bottom: 0;
        }
      }

      .menu_item {
        font-size: 15px;
        padding: 11px 0 11px 84px;
        display: block;
        background-color: #303439;
        color: #fff;
        cursor: pointer;

        &:hover, &.active {
          background-color: #1787fb;
        }
      }
    }
  }
  .i10.point span:after { content: ''; width: 10px; height: 10px; border-radius: 50%; background-image: linear-gradient(to bottom, #ff420a, #ff420a); position: absolute; right: 72px; top: 12px; z-index: 50 }

</style>
