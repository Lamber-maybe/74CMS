<template>
  <div id="app" class="my_app" v-wechat-title="this.pageTitle">
    <Head :bg="'tran'">我的{{ points_byname }}</Head>
    <div class="box_1">
      <div class="box_info">
        <div class="box_top" :class="top_loading === true ? 'wait' : ''">
          <van-skeleton avatar :row="4" :loading="top_loading">
            <div class="avatar_box">
              <img :src="logo" alt="Logo" />
            </div>
            <div class="name">{{ companyname }}</div>
            <div class="point">
              我的{{ points_byname }}：
              <span>{{ points }}</span>
            </div>
            <div class="btn_group">
              <van-button
                color="#fa7445"
                plain
                round
                size="small"
                @click="$router.push('/member/order/add/points')"
              >
                购买{{ points_byname }}
              </van-button>
              <van-button
                color="#1787fb"
                plain
                round
                size="small"
                @click="$router.push('/member/company/points_log')"
              >
                收支明细
              </van-button>
            </div>
            <div class="sign_box" @click="handlerSignin">每日签到</div>
          </van-skeleton>
        </div>
        <div class="box_bottom" @click="$router.push('/member/order/list')">
          服务订单
          <div class="right_txt">查看全部订单</div>
        </div>
      </div>
    </div>
    <div class="self_box_head">我的任务</div>
    <div class="box_3">
      <div class="item" v-for="(item, index) in task" :key="index">
        <div class="ico" :class="item.alias"></div>
        <div class="tx1">{{ item.name }}</div>
        <div class="tx2">{{ points_byname }}：{{ item.points }}</div>
        <van-button
          class="btn"
          plain
          type="info"
          round
          size="small"
          v-if="item.is_done == 0"
          @click="handlerJump(item)"
        >
          去完成
        </van-button>
        <div class="down" v-else></div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Points',
  data () {
    return {
      pageTitle: '',
      points_byname: '',
      top_loading: true,
      points: 0,
      logo: '',
      companyname: '',
      task: []
    }
  },
  created () {
    this.points_byname = this.$store.state.config.points_byname
    this.pageTitle = this.$route.meta.title
    this.pageTitle.replace('积分', this.points_byname)
    this.fetchData()
  },
  methods: {
    fetchData () {
      http
        .get(api.member_points, {})
        .then(res => {
          const { points, logo, companyname, task } = { ...res.data }
          this.points = points
          this.logo = logo
          this.companyname = companyname
          this.task = task
          this.top_loading = false
        })
        .catch(() => {})
    },
    handlerJump (item) {
      switch (item.alias) {
        case 'bind_weixin':
        case 'bind_qq':
        case 'bind_sina':
          this.$router.push('/member/company/account')
          break
        case 'upload_logo':
          this.$router.push('/member/company/profile')
          break
        case 'auth':
          this.$router.push('/member/company/auth')
          break
        case 'upload_img':
          this.$router.push('/member/company/manage')
          break
        case 'handle_resume':
          this.$router.push('/member/company/jobapply')
          break
        case 'sign_in':
          this.handlerSignin()
          break
        default:
          break
      }
    },
    handlerSignin () {
      http
        .post(api.member_signin, {})
        .then(res => {
          this.$notify({ type: 'success', message: res.message })
          this.task.forEach(element => {
            if (element.alias === 'sign_in') {
              element.is_done = 1
            }
          })
        })
        .catch(() => {})
    },
    goBack () {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    }
  }
}
</script>

<style lang="scss" scoped>
.box_3 {
  .item {
    .ico {
      &.reg {
        background: url("../../../assets/images/task_ico_hfxx.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.handle_resume {
        background: url("../../../assets/images/task_ico_cljl.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.upload_img {
        background: url("../../../assets/images/task_ico_qyfc.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.auth {
        background: url("../../../assets/images/task_ico_yyzz.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.upload_logo {
        background: url("../../../assets/images/task_ico_avatar.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.bind_sina {
        background: url("../../../assets/images/task_ico_bdwb.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.bind_qq {
        background: url("../../../assets/images/task_ico_bdqq.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.bind_weixin {
        background: url("../../../assets/images/task_ico_bdwx.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.sign_in {
        background: url("../../../assets/images/task_ico_sign.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translate(0, -50%);
      width: 33px;
      height: 33px;
    }
    .down {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translate(0, -50%);
      width: 48.5px;
      height: 40px;
      background: url("../../../assets/images/task_ico_down.png") 0 0 no-repeat;
      background-size: 48.5px 40px;
    }
    .btn {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translate(0, -50%);
      padding-left: 15px;
      padding-right: 15px;
    }
    .tx2 {
      font-size: 12px;
      color: #666666;
      width: 200px;
      overflow: hidden;
      white-space: nowrap;
      padding: 3px 0 10px;
      text-overflow: ellipsis;
    }
    .tx1 {
      font-size: 15px;
      color: #333333;
      font-weight: bold;
      width: 200px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding-top: 11px;
    }
    position: relative;
    border: 1px solid #f8f8f8;
    border-radius: 5px;
    padding-left: 62px;
    &:not(:last-child) {
      margin-bottom: 13px;
    }
  }
  margin-bottom: 10px;
  padding: 0 15px;
}
.self_box_head {
  font-size: 18px;
  color: #333333;
  font-weight: bold;
  padding: 12px 15px;
}
.box_1 {
  .box_info {
    .box_bottom {
      .right_txt {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translate(0, -50%);
        padding-right: 17px;
        font-size: 12px;
        color: #c9c9c9;
        background: url("../../../assets/images/form_choose_arrow.svg") right
          2px no-repeat;
        background-size: 14px;
      }
      font-size: 14px;
      color: #333333;
      padding: 15px 0 15px 29px;
      border-top: 1px solid #f3f3f3;
      background: url("../../../assets/images/serve_index_tb_bg.png") 13px
        center no-repeat;
      background-size: 11px 13px;
      position: relative;
    }
    .box_top {
      &.wait {
        padding: 10px 15px 10px 15px;
      }
      .sign_box {
        &::after {
          content: " ";
          position: absolute;
          right: 0;
          bottom: -5.5px;
          width: 8.5px;
          height: 5.5px;
          background: url("../../../assets/images/serve_index_sign_bg.png") 0 0
            no-repeat;
          z-index: 2;
          background-size: 8.5px 5.5px;
        }
        position: absolute;
        right: -8.5px;
        top: 36px;
        font-size: 12px;
        color: #ffffff;
        padding: 7px 12px 7px 15px;
        background: linear-gradient(to right, #ffa140, #fa7445);
        border-radius: 30px 0 0 30px;
        z-index: 3;
      }
      .avatar_box {
        position: absolute;
        left: 20px;
        top: 23px;
        overflow: hidden;
        width: 55px;
        height: 55px;
        box-shadow: 0 0 5px #c2c2c2;
        img {
          width: 55px;
          height: 55px;
          border-radius: 10px;
        }
      }
      .btn_group {
        .van-button--small {
          margin-right: 20px;
          padding-right: 15px;
          padding-left: 15px;
          vertical-align: top;
        }
        padding-bottom: 13px;
      }
      .point {
        span {
          color: #fa7445;
        }
        padding-bottom: 13px;
        font-size: 14px;
        color: #666666;
      }
      .name {
        padding: 22px 88px 8px 0;
        font-size: 18px;
        color: #333333;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      width: 100%;
      padding-left: 90px;
    }
    position: relative;
    width: 340px;
    background-color: #ffffff;
    border-radius: 5px;
    margin: 0 auto;
    box-shadow: 0 1px 5px #c2c2c2;
  }
  position: relative;
  width: 100%;
  margin-bottom: 10px;
}
.my_app{
  background: url("../../../assets/images/serve_index_top_bg.png") center 0
  no-repeat;
  background-size: 375px 191px;
}
</style>
