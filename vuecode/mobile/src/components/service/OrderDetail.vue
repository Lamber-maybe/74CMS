<template>
  <div id="app">
    <Head>订单详情</Head>
    <div class="form_split_10"></div>
    <van-skeleton
      title
      avatar
      :row="10"
      :loading="mainLoading"
      style="margin-top:10px;"
    >
      <div class="box_wrapper">
        <div class="box_1">
          <div class="item">
            <div
              class="tx1"
              :class="$store.state.LoginType == 2 ? ' personal' : ''"
            >
              <div
                class="order_ico"
                :class="
                  info.service_type +
                    ($store.state.LoginType == 2 ? ' personal' : '')
                "
              ></div>
              <div class="name">{{ info.service_name }}</div>
              <div class="des">{{ shortDesc }}</div>
            </div>
          </div>
        </div>
        <div
          class="form_split_10"
          v-if="info.extra.jobname !== undefined"
        ></div>
        <div class="box_2" v-if="info.extra.jobname !== undefined">
          <div class="item">
            推广职位
            <div class="right_txt">{{ info.extra.jobname }}</div>
          </div>
        </div>
        <div
          class="form_split_10"
          v-if="info.extra.tag_text !== undefined"
        ></div>
        <div class="box_2" v-if="info.extra.tag_text !== undefined">
          <div class="item">
            使用标签
            <div class="right_txt">{{ info.extra.tag_text }}</div>
          </div>
        </div>
        <div class="form_split_10"></div>
        <div class="box_2">
          <div class="item">
            服务内容
            <div class="right_txt">{{ info.service_type_text }}</div>
          </div>
          <div class="item">
            服务金额
            <div class="right_txt">
              <span class="for">￥</span>
              {{ info.service_amount }}
            </div>
          </div>
          <div class="item" v-if="info.extra.preferential_info !== undefined">
            限时特惠
            <div class="right_txt">
              <span class="for">￥</span>
              {{ info.extra.preferential_info.amount }}
              <span style="color:#999999">
                ({{ info.extra.preferential_info.starttime | dateFilter }} 至
                {{ info.extra.preferential_info.endtime | dateFilter }})
              </span>
            </div>
          </div>
          <div class="item" v-if="info.extra.coupon_info !== undefined">
            优惠券
            <div class="right_txt">{{ info.extra.coupon_info.name }}</div>
          </div>
          <div class="item" v-if="info.extra.coupon_info !== undefined">
            优惠券抵扣
            <div class="right_txt">
              -
              <span class="for">￥</span>
              {{ info.extra.coupon_info.face_value }}
            </div>
          </div>
          <div
            class="item"
            v-if="
              info.extra.service_added_discount !== undefined &&
                info.extra.service_added_discount > 0
            "
          >
            专享折扣
            <div class="right_txt">
              增值服务{{ info.extra.service_added_discount }}折
            </div>
          </div>
          <div
            class="item"
            v-if="
              info.extra.service_added_discount !== undefined &&
                info.extra.service_added_discount > 0
            "
          >
            折扣金额
            <div class="right_txt">
              <span class="for">￥</span>
              {{ info.service_amount_after_discount }}
            </div>
          </div>
          <div class="item" v-if="info.deduct_points > 0">
            {{ $store.state.config.points_byname }}抵扣
            <div class="right_txt">
              -
              <span class="for">￥</span>
              {{ info.deduct_amount }}
            </div>
          </div>
          <div class="item" v-if="info.service_type == 'points'">
            充值{{ $store.state.config.points_byname }}
            <div class="right_txt">
              {{ info.extra.add_points }}{{ $store.state.config.points_byname }}
            </div>
          </div>
          <div class="item">
            应付金额
            <div class="right_txt">
              <span class="color_or">
                <span class="for">￥</span>
                {{ info.amount }}
              </span>
            </div>
          </div>
        </div>
        <div class="form_split_10"></div>
        <div class="box_5">
          <div class="box_head">订单信息</div>
          <div class="item">
            订单编号：
            <span class="txt">{{ info.oid }}</span>
          </div>
          <div class="item">
            创建时间：
            <span class="txt">{{ info.addtime | timeFilter }}</span>
          </div>
          <div class="item" v-if="info.status != 2">
            付款时间：
            <span class="txt" v-if="info.status == 0">待付款</span>
            <span class="txt" v-else>{{ info.paytime | timeFilter }}</span>
          </div>
          <div class="item">
            支付渠道：
            <span class="txt">
              {{ info.payment_text == "" ? "无" : info.payment_text }}
            </span>
          </div>
        </div>
        <div class="form_split_10"></div>
        <div class="box_3">
          <van-button
            type="info"
            round
            block
            v-if="info.status == 0"
            @click="showPayment = true"
          >
            继续支付
          </van-button>
          <van-button
            class="cancel"
            round
            block
            plain
            v-if="info.status == 0"
            @click="handlerCancel"
          >
            取消订单
          </van-button>
          <van-button
            class="cancel"
            round
            block
            plain
            v-if="info.status == 2"
            @click="handlerDel"
          >
            删除订单
          </van-button>
        </div>
      </div>
    </van-skeleton>
    <van-popup v-model="showPayment" closeable position="bottom">
      <PopupPayment
        :amount="info.amount"
        @handlerSubmit="handlerSubmit"
      ></PopupPayment>
    </van-popup>
    <PaySubmit ref="paySubmit" :payment="payment" success-url="/member/order/list"></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from '@/components/service/PaySubmit'
import PopupPayment from '@/components/service/PopupPayment'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'OrderDetail',
  components: {
    PopupPayment,
    PaySubmit
  },
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    },
    dateFilter (timestamp) {
      return parseTime(timestamp, '{m}-{d}')
    }
  },
  data () {
    return {
      mainLoading: true,
      showPayment: false,
      order_id: 0,
      info: {
        extra: {}
      },
      payment: 'wxpay',
      shortDesc: ''
    }
  },
  created () {
    this.order_id = this.$route.params.id
    this.fetchData()
  },
  methods: {
    fetchData () {
      let url =
        this.$store.state.LoginType == 1
          ? api.company_orderdetail
          : api.personal_orderdetail
      http
        .get(url, { id: this.order_id })
        .then(res => {
          this.info = { ...res.data }
          this.shortDesc = this.filterDesc(this.info)
          this.mainLoading = false
        })
        .catch(() => {})
    },
    filterDesc (info) {
      let return_str = ''
      switch (info.service_type) {
        case 'points':
          return_str =
            '做任务免费得' +
            this.$store.state.config.points_byname +
            '，小' +
            this.$store.state.config.points_byname +
            '大用处'
          break
        case 'setmeal':
          return_str = '套餐服务期：' + info.extra.days + '天'
          break
        case 'jobstick':
          return_str = '锁定好位，提高职位曝光量'
          break
        case 'emergency':
          return_str = '急聘职位优先推荐，专属标识'
          break
        case 'resume_package':
          return_str = '超值简历下载叠加包'
          break
        case 'refresh_job_package':
          return_str = '自动抢占招聘先机'
          break
        case 'single_job_refresh':
          return_str = '单次刷新职位-快捷支付'
          break
        case 'single_resume_down':
          return_str = '单次下载简历-快捷支付'
          break
        case 'stick':
        case 'tag':
          return_str = ''
          break
      }
      return return_str
    },
    handlerSubmit (payment) {
      this.payment = payment
      let url =
        this.$store.state.LoginType == 1
          ? api.company_repay
          : api.personal_repay
      this.$refs.paySubmit.handlerSubmit(url, { id: this.order_id, payment: this.payment })
    },
    handlerCancel () {
      let that = this
      let url =
        this.$store.state.LoginType == 1 ? api.company_order_cancel : api.personal_order_cancel
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定取消该订单吗？'
        })
        .then(() => {
          http
            .post(url, { id: this.order_id })
            .then(res => {
              this.$notify({ type: 'success', message: res.message })
              setTimeout(() => {
                that.$router.push('/member/order/list')
              }, 1500)
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerDel () {
      let that = this
      let url =
        this.$store.state.LoginType == 1 ? api.company_order_del : api.personal_order_del
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定删除该订单吗？'
        })
        .then(() => {
          http
            .post(url, { id: this.order_id })
            .then(res => {
              this.$notify({ type: 'success', message: res.message })
              setTimeout(() => {
                that.$router.push('/member/order/list')
              }, 1500)
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_3 {
  .cancel {
    margin-top: 9px;
  }
  padding: 25px;
}
.box_5 {
  .item {
    .txt {
      margin-left: 16px;
      color: #333333;
    }
    position: relative;
    padding: 0 15px;
    font-size: 14px;
    color: #666666;
    &:not(:last-child) {
      margin-bottom: 15px;
    }
  }
  width: 100%;
  background-color: #ffffff;
  border-radius: 5px;
  padding: 0 0 20px;
}
.box_2 {
  .item {
    .self_check {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
    }
    .right_txt {
      &.check {
        padding-right: 20px;
      }
      .for {
        font-size: 9px;
      }
      .color_or {
        color: #ff6600;
      }
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
      color: #333333;
    }
    position: relative;
    padding: 0 15px;
    font-size: 14px;
    color: #666666;
    &:not(:last-child) {
      margin-bottom: 15px;
    }
  }
  width: 100%;
  background-color: #ffffff;
  border-radius: 5px;
  padding: 17px 0;
}
.box_1 {
  .item {
    .tx1 {
      &.personal {
        padding-left: 95px;
      }
      .order_ico {
        &.personal {
          width: 80px;
          border-radius: 7px;
        }
        &.stick {
          background: url("../../assets/images/order_ico_top.png") 0 no-repeat;
          background-size: 80px 50px;
        }
        &.tag {
          background: url("../../assets/images/order_ico_tag.png") 0 no-repeat;
          background-size: 80px 50px;
        }
        &.points {
          background: url("../../assets/images/company_order_ico_jfcz.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.jlkjxz {
          background: url("../../assets/images/company_order_ico_jlkjxz.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.setmeal {
          background: url("../../assets/images/company_order_ico_tcsj.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.emergency {
          background: url("../../assets/images/company_order_ico_zwjj.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.jobstick {
          background: url("../../assets/images/company_order_ico_zwzd.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.refresh_job_package {
          background: url("../../assets/images/company_order_ico_zwznsx.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.resume_package {
          background: url("../../assets/images/company_order_ico_jlzzb.png") 0
            no-repeat;
          background-size: 50px;
        }
        position: absolute;
        left: 3px;
        top: 10px;
        width: 50px;
        height: 50px;
        border-radius: 100%;
      }
      .des {
        padding: 0 10px 20px 0;
        font-size: 13px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .name {
        padding: 15px 10px 10px 0;
        font-size: 15px;
        color: #666666;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      position: relative;
      padding-left: 65px;
    }
    width: 100%;
    background-color: #ffffff;
    border-radius: 5px;
    padding: 0 15px;
    position: relative;
  }
  width: 100%;
}
.box_head {
  width: 100%;
  background-color: #ffffff;
  border-radius: 5px 5px 0 0;
  padding: 15px 0 15px 15px;
  font-size: 14px;
  color: #333333;
  position: relative;
  &::after {
    content: " ";
    position: absolute;
    width: 2.5px;
    height: 14px;
    background-color: #1787fb;
    left: 8px;
    top: 50%;
    transform: translate(0, -50%);
  }
}
.box_wrapper {
  width: 100%;
}
.notice_bar {
  width: 100%;
  padding: 11px 0 11px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
}
</style>
