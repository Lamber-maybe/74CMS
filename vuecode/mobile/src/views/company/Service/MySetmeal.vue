<template>
  <div id="app" class="my_app">
    <Head :bg="'tran'">我的套餐</Head>
    <div class="my_container">
      <div class="box_1">
        <van-skeleton avatar title :row="3" :loading="top_loading">
          <div class="top">
            <img :src="companyInfo.logo_src" alt="logo" class="logo" />
            <div class="name substring">{{ companyInfo.companyname }}</div>
            <div class="type">
              我的套餐：<span>{{ mySetmeal.name }}</span>
            </div>
            <div class="date" v-if="mySetmeal.deadline == 0">
              有效期至：无限期
            </div>
            <div class="date" v-else>
              有效期至：{{ mySetmeal.deadline | timeFilter }}
            </div>
          </div>
        </van-skeleton>
        <div class="bottom" @click="$router.push('/member/order/list')">
          服务订单
          <div class="tip_text">查看全部订单</div>
        </div>
      </div>
      <div class="box_title">
        我的资源
        <div
          class="tip_text"
          @click="$router.push('/member/company/setmeallog')"
        >
          使用明细
        </div>
      </div>
      <div class="box_2">
        <div class="item">
          <div class="ico"></div>
          <div class="text">
            职位并发 <span>{{ mySetmeal.jobs_meanwhile }}</span>
          </div>
          <div class="link" @click="handlerJobadd">发职位</div>
        </div>
        <div class="item right">
          <div class="ico"></div>
          <div class="text">
            简历点 <span>{{ mySetmeal.download_resume_point }}</span>
          </div>
          <div class="link" @click="$router.push('/resumelist')">搜简历</div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="box_title">
        我的特权
        <div
          class="tip_text orange"
          @click="$router.push('/member/order/add/common?type=setmeal')"
        >
          升级会员畅享更多特权
        </div>
      </div>
      <div class="box_3">
        <div class="item i1">
          免费刷新职位次数
          <div class="tip_text">
            {{ mySetmeal.refresh_jobs_free_perday }}次/天
          </div>
        </div>
        <div class="item i2">
          下载简历数上限
          <div class="tip_text">
            {{ mySetmeal.download_resume_max_perday }}份/天
          </div>
        </div>
        <div class="item i3">
          增值服务专享折扣
          <div
            class="tip_text orange"
            v-if="mySetmeal.service_added_discount > 0"
          >
            {{ mySetmeal.service_added_discount }} 折
          </div>
          <div class="tip_text gray" v-else>
            无折扣
          </div>
        </div>
        <div class="item i4">
          收到的简历免费查看
          <div
            class="tip_text"
            :class="mySetmeal.show_apply_contact == 1 ? 'orange' : 'gray'"
          >
            {{ mySetmeal.show_apply_contact == 1 ? "支持" : "暂不支持" }}
          </div>
        </div>
        <div class="item i5">
          微海报
          <div
            class="tip_text"
            :class="mySetmeal.enable_poster == 1 ? 'orange' : 'gray'"
          >
            {{ mySetmeal.enable_poster == 1 ? "支持" : "暂不支持" }}
          </div>
        </div>
        <div class="item i6">
          视频面试
          <div
            class="tip_text"
            :class="mySetmeal.enable_video_interview == 1 ? 'orange' : 'gray'"
          >
            {{ mySetmeal.enable_video_interview == 1 ? "支持" : "暂不支持" }}
          </div>
        </div>
        <div class="item i7">
          会员专属标识
          <div class="tip_text">会员标识</div>
        </div>
        <div class="item i8">
          专属金牌客服
          <div class="tip_text">客服快速响应</div>
        </div>
      </div>
      <div class="box_4">
        <div
          class="up_btn"
          @click="$router.push('/member/order/add/common?type=setmeal')"
        >
          升级套餐
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'MySetmeal',
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data () {
    return {
      type: '',
      top_loading: true,
      mySetmeal: {},
      companyInfo: {}
    }
  },
  created () {
    this.fetchSetmeal()
  },
  methods: {
    handlerJobadd () {
      http
        .get(api.company_check_jobadd_num, {})
        .then(res => {
          if (res.data.enable_addjob_num <= 0) {
            this.$dialog
              .confirm({
                title: '系统提示',
                message:
                  '您当前是' +
                  res.data.setmeal_name +
                  '，当前可发布职位数为0，建议您立即升级套餐或将暂时不招聘的职位设为关闭！',
                confirmButtonText: '升级套餐',
                messageAlign: 'left'
              })
              .then(() => {
                this.$router.push('/member/order/add/common?type=setmeal')
              })
              .catch(() => {
                // on cancel
              })
            return false
          } else {
            this.$router.push('/member/company/jobadd')
          }
        })
        .catch(() => {})
    },
    fetchSetmeal () {
      http
        .get(api.member_setmeal, {})
        .then(res => {
          this.mySetmeal = res.data.info
          this.companyInfo = res.data.company_info
          this.top_loading = false
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.my_container {
  .box_4 {
    .up_btn {
      width: 305px;
      margin: 0 auto;
      text-align: center;
      font-size: 17px;
      font-weight: bold;
      color: #ffffff;
      padding: 9px 0;
      background: linear-gradient(to right, #cca467 0%, #eecf8e 100%);
      border-radius: 42px;
    }
    padding: 35px 0;
  }
  .box_3 {
    .item {
      &.i8 {
        background: url("../../../assets/images/my_setitem_ic8.png") 3.5px
          center no-repeat;
        background-size: 15.5px 13px;
      }
      &.i7 {
        background: url("../../../assets/images/my_setitem_ic7.png") 4.5px
          center no-repeat;
        background-size: 13px 13px;
      }
      &.i6 {
        background: url("../../../assets/images/my_setitem_ic6.png") 4.5px
          center no-repeat;
        background-size: 13px 13px;
      }
      &.i5 {
        background: url("../../../assets/images/my_setitem_ic5.png") 5.5px
          center no-repeat;
        background-size: 11.5px 11.5px;
      }
      &.i4 {
        background: url("../../../assets/images/my_setitem_ic4.png") 6.5px
          center no-repeat;
        background-size: 10px 13px;
      }
      &.i3 {
        background: url("../../../assets/images/my_setitem_ic3.png") 4.5px
          center no-repeat;
        background-size: 13.5px 11.5px;
      }
      &.i2 {
        background: url("../../../assets/images/my_setitem_ic2.png") 5px center
          no-repeat;
        background-size: 14px 12px;
      }
      &.i1 {
        background: url("../../../assets/images/my_setitem_ic1.png") 2.5px
          center no-repeat;
        background-size: 15px 13px;
      }
      .tip_text {
        position: absolute;
        right: 2px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #333333;
        &.orange {
          color: #ff6600;
        }
        &.gray {
          color: #999999;
        }
      }
      position: relative;
      padding: 16px 0 16px 23px;
      font-size: 14px;
      color: #333333;
      border-bottom: 1px solid #f3f3f3;
    }
    width: 100%;
    border-top: 1px solid #f3f3f3;
  }
  .box_2 {
    .item {
      .link {
        display: block;
        text-decoration: none;
        width: 70px;
        margin: 0 auto;
        font-size: 12px;
        color: #ffffff;
        padding: 6px 0;
        background: linear-gradient(to right, #4fa5fa 0%, #2590fa 100%);
        border-radius: 29px;
      }
      .text {
        padding: 10px 0 15px;
        font-size: 14px;
        color: #333333;
        span {
          color: #fa685c;
        }
      }
      .ico {
        width: 30px;
        height: 30px;
        margin: 0 auto;
        background: url("../../../assets/images/my_setmeal_ic2.png") 0 0
          no-repeat;
        background-size: 30px 30px;
      }
      float: left;
      text-align: center;
      width: 160px;
      height: 155px;
      border-radius: 6px;
      box-shadow: 0 0 5px #c2c2c2;
      padding-top: 28px;
      &.right {
        .ico {
          width: 35px;
          height: 30px;
          margin: 0 auto;
          background: url("../../../assets/images/my_setmeal_ic3.png") 0 0
            no-repeat;
          background-size: 35px 30px;
        }
        float: right;
      }
    }
    width: 100%;
    margin-bottom: 10px;
  }
  .box_1 {
    .bottom {
      &::after {
        content: "";
        position: absolute;
        right: 11px;
        top: 21px;
        width: 7px;
        height: 7px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
      }
      .tip_text {
        position: absolute;
        right: 21px;
        top: 50%;
        transform: translate(0, -50%);
        color: #c9c9c9;
      }
      position: relative;
      padding: 15px 0 15px 29px;
      border-top: 1px solid #f3f3f3;
      font-size: 14px;
      color: #333333;
      background: url("../../../assets/images/my_setmeal_ic1.png") 13px center
        no-repeat;
      background-size: 11px 13px;
    }
    .top {
      .date {
        font-size: 12px;
        color: #999999;
        padding-bottom: 20px;
      }
      .type {
        span {
          color: #fa7445;
        }
        font-size: 12px;
        margin-bottom: 8px;
      }
      .name {
        font-size: 18px;
        font-weight: bold;
        color: #333333;
        padding: 21px 15px 0 0;
        margin-bottom: 10px;
      }
      .logo {
        position: absolute;
        top: 37px;
        left: 20px;
        width: 55px;
        height: 55px;
        border: 0;
        border-radius: 5px;
      }
      position: relative;
      padding-left: 93px;
    }
    background-color: #ffffff;
    border-radius: 7px;
    overflow: hidden;
    box-shadow: 0 1px 5px #c2c2c2;
    margin-bottom: 10px;
  }
  .box_title {
    .tip_text {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 14px;
      color: #999999;
      &.orange {
        color: #ff6600;
      }
    }
    &::after {
      content: "";
      position: absolute;
      right: 5px;
      top: 20px;
      width: 7px;
      height: 7px;
      border-top: 1px solid #666666;
      border-right: 1px solid #666666;
      transform: rotate(45deg);
    }
    position: relative;
    font-size: 18px;
    font-weight: bold;
    color: #333333;
    padding: 12px 0;
  }
  *{line-height:normal}
  width:340px;margin:0 auto;
}
.my_app {
  background: url("../../../assets/images/com_setmeal_top_bg.png") 0 0 no-repeat;
  background-size: 375px 176px;
}
</style>
