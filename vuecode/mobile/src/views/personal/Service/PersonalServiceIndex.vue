<template>
  <div id="app" class="my_app">
    <Head :bg="'tran'">会员服务</Head>
    <div class="box_1">
      <div class="box_info">
        <div class="box_top">
          <div class="avatar_box">
            <img :src="photo" alt="photo" />
          </div>
          <div class="name">{{ fullname }}</div>
          <div class="point">我的{{ points_byname }}：{{ points }}</div>
          <div class="btn_group">
            <a class="point_btn" href="#task">获取{{ points_byname }}</a>
            <div class="point_btn" @click="$router.push('/member/personal/points_log')">收支明细</div>
            <div class="clear"></div>
          </div>
          <div class="sign_box" @click="handlerSignin">每日签到</div>
        </div>
        <div class="box_bottom" @click="$router.push('/member/order/list')">
          服务订单
          <div class="right_txt">查看全部订单</div>
        </div>
      </div>
    </div>
    <div class="self_box_head">增值服务</div>
    <div class="box_2">
      <div class="item">
        <div class="tx1">简历置顶</div>
        <div class="tx2">提升5倍曝光量</div>
        <div class="tx3">
          <van-button
            round
            type="info"
            size="small"
            @click="handlerJumpService('/member/order/add/stick','stick')"
            >开通</van-button
          >
        </div>
      </div>
      <div class="item right">
        <div class="tx1">醒目标签</div>
        <div class="tx2">是金子就要发光</div>
        <div class="tx3">
          <van-button
            round
            type="info"
            size="small"
            @click="handlerJumpService('/member/order/add/tag','tag')"
            >开通</van-button
          >
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="self_box_head" id="task">我的任务</div>
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
          v-if="item.is_done === 0"
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
import {handlerHttpError} from '@/utils/error'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'PersonalServiceIndex',
  data () {
    return {
      points_byname: '',
      points: 0,
      photo: '',
      fullname: '',
      task: [],
      empty_resume: false
    }
  },
  created () {
    this.points_byname = this.$store.state.config.points_byname
    this.fetchData()
  },
  computed: {
    basic () {
      return this.$store.state.resume.basic
    }
  },
  methods: {
    // 初始化数据
    initInfo () {
      http
        .get(api.editResume)
        .then(res => {
          if (res.data === null) {
            this.empty_resume = true
            // 更新基本资料
            this.$store.dispatch('setBasicInfo', null)
          } else {
          // 更新基本资料
            this.$store.dispatch('setBasicInfo', res.data.basic)
            this.empty_resume = false
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlerJumpService (path, type) {
      if (this.empty_resume === true) {
        handlerHttpError({code: 50007, message: '请先添加一份简历'})
      } else {
        if (type == 'stick' && this.basic.stick === 1) {
          this.$notify('该简历已经在推广状态，不能重复推广')
          return false
        }
        if (type == 'tag' && this.basic.service_tag !== '') {
          this.$notify('该简历已经在推广状态，不能重复推广')
          return false
        }
        this.$router.push(path)
      }
    },
    fetchData () {
      http
        .get(api.member_points, {})
        .then(res => {
          const { points, photo, fullname, task } = { ...res.data }
          this.points = points
          this.photo = photo
          this.fullname = fullname
          this.task = task
        })
        .catch(() => {})
    },
    handlerJump (item) {
      switch (item.alias) {
        case 'bind_weixin':
        case 'bind_qq':
        case 'bind_sina':
          this.$router.push('/member/personal/account')
          break
        case 'upload_photo':
        case 'percent60':
        case 'percent90':
          this.$router.push('/member/personal/resume')
          break
        case 'refresh_resume':
          this.$router.push('/member/personal/index')
          break
        case 'apply_job':
        case 'report_job':
          this.$router.push('/joblist')
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
        background: url("../../../assets/images/task_ico_account.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.report_job {
        background: url("../../../assets/images/task_ico_jb.png") 0 0 no-repeat;
        background-size: 33px;
      }
      &.apply_job {
        background: url("../../../assets/images/task_ico_td_resume.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.percent90 {
        background: url("../../../assets/images/task_ico_percent_90.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.percent60 {
        background: url("../../../assets/images/task_ico_percent_60.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.upload_photo {
        background: url("../../../assets/images/task_ico_avatar.png") 0 0
          no-repeat;
        background-size: 33px;
      }
      &.refresh_resume {
        background: url("../../../assets/images/task_ico_refresh.png") 0 0
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
      padding: 7px 0 10px;
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
.box_2 {
  .item {
    .tx2 {
      font-size: 12px;
      color: #999999;
      padding: 9px 0 13px;
    }
    .tx1 {
      font-size: 18px;
      color: #333333;
      font-weight: bold;
    }
    float: left;
    width: 160px;
    border-radius: 4px;
    box-shadow: 0 0 5px #c2c2c2;
    text-align: center;
    padding: 63px 0 22px;
    background: url("../../../assets/images/service_top_ico.png") center 27px
      no-repeat;
    background-size: 24.5px;
    &.right {
      background: url("../../../assets/images/service_tag_ico.png") center 27px
        no-repeat;
      float: right;
      background-size: 26px 25.5px;
    }
  }
  margin-bottom: 10px;
  padding: 0 15px;
}
.self_box_head {
  font-size: 18px;
  color: #333333;
  font-weight: bold;
  padding: 12.5px 15px;
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
      }
      &::after {
        position: absolute;
        right: 17px;
        top: 23px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #999999;
        border-right: 1px solid #999999;
        transform: rotate(45deg);
        content: " ";
      }
      font-size: 14px;
      color: #333333;
      padding: 16px 0 16px 29px;
      border-top: 1px solid #f3f3f3;
      background: url("../../../assets/images/serve_index_tb_bg.png") 13px
        center no-repeat;
      background-size: 11px 13px;
      position: relative;
    }
    .box_top {
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
        padding: 6.5px 12px 6.5px 15px;
        background: linear-gradient(to right, #ffa140, #fa7445);
        border-radius: 30px 0 0 30px;
        z-index: 3;
      }
      .avatar_box {
        position: absolute;
        left: 22px;
        top: 30px;
        overflow: hidden;
        width: 46px;
        height: 46px;
        border-radius: 100%;
        img {
          width: 46px;
          height: 46px;
          border-radius: 100%;
          border: 1px solid #a0c3f8;
        }
      }
      .btn_group {
        .point_btn{
          float: left;margin-right: 20px;font-size: 13px;color: #fa7445;font-weight: bold;padding: 4px 12px;
          border: 1px solid #ffd1a1;border-radius: 26px;
        }
      }
      .point {
        margin-bottom: 15px;
        font-size: 14px;
        color: #666666;
      }
      .name {
        margin-bottom: 8px;
        font-size: 18px;
        color: #333333;
        font-weight: bold;
      }
      width: 100%;
      padding:23px 0 15px 87px;
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
.my_app {
  background: url("../../../assets/images/serve_index_top_bg.png") center 0
    no-repeat;
  background-size: 375px 191px;
}
</style>
