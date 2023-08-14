<template>
  <div id="app">
    <div class="inv-top-menu-bar">
      <div class="top-menu">
        <div class="top-menu-list">
          <div
            class="item"
            :class="{
              active: active_index == index,
              recommend: item.recommend == 1,
              time: item.preferential_open == 1
            }"
            v-for="(item, index) in dataset"
            :key="index"
            @click="changeItem(item, index)"
          >
            <div class="tx1">{{ item.name }}</div>
            <div class="tx2">
              <span class="unit">￥</span>{{ item.expense1 }}.<span
                class="tofix"
                >{{ item.expense2 }}</span
              >
            </div>
            <div class="tx3" v-if="item.preferential_open == 1">限时特惠价</div>
            <div class="tx4" v-if="item.preferential_open == 1">
              {{ item.preferential_expense_start | timeFilter }} 至
              {{ item.preferential_expense_end | timeFilter }}
            </div>
            <div class="tx5" v-if="item.preferential_open == 0">
              时长：{{ item.days }}天
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="box_3">
      <div class="content">
        <div class="tx1">套餐资源</div>
        <div class="tx2">
          <span>职位并发数：{{ setmealDetail.jobs_meanwhile }}</span
          ><span>赠送简历点数：{{ setmealDetail.download_resume_point }}</span>
        </div>
        <div class="tx2">
          <span
            >赠送会员{{ $store.state.config.points_byname }}：{{
              setmealDetail.gift_point
            }}</span
          ><span
            >免费刷新职位：{{
              setmealDetail.refresh_jobs_free_perday
            }}次/天</span
          >
        </div>
        <div class="tx2">
          <span
            >下载简历上限：{{
              setmealDetail.download_resume_max_perday
            }}份/天</span
          >
        </div>
        <div
          class="tx3"
          v-show="openDetail"
          @click="toggleOpen"
          v-if="
            setmealDetail.service_added_discount > 0 ||
              setmealDetail.enable_poster == 1 ||
              setmealDetail.enable_video_interview == 1 ||
              setmealDetail.show_apply_contact == 1
          "
        >
          <div class="hide">展开全部特权</div>
        </div>
        <div
          v-show="!openDetail"
          v-if="
            setmealDetail.service_added_discount > 0 ||
              setmealDetail.enable_poster == 1 ||
              setmealDetail.enable_video_interview == 1 ||
              setmealDetail.show_apply_contact == 1
          "
        >
          <div class="tx1">套餐特权</div>
          <div class="tx2">
            <span class="tq" v-if="setmealDetail.service_added_discount > 0"
              >套餐增值包折扣{{ setmealDetail.service_added_discount }}折</span
            ><span class="tq" v-if="setmealDetail.enable_poster == 1"
              >允许使用微海报</span
            >
            <span class="tq" v-if="setmealDetail.enable_video_interview == 1"
              >允许使用视频面试</span
            ><span class="tq" v-if="setmealDetail.show_apply_contact == 1"
              >收到简历免费查看</span
            >
          </div>
          <div class="tx2" v-if="setmealDetail.note" style="padding-top:0">
            <span class="dec">说明：{{ setmealDetail.note }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="box_4" @click="handlerCouponPicker">
      优惠券
      <div class="right_txt">{{ couponName }}</div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_pay_pop">
      <van-radio-group v-model="submitData.payment">
        <div class="pay_item wx" @click="choosePayment('wxpay')">
          微信支付
          <van-radio
            class="self_check"
            name="wxpay"
            icon-size="18px"
          ></van-radio>
        </div>
        <div class="pay_item ali" @click="choosePayment('alipay')">
          支付宝支付
          <van-radio
            class="self_check"
            name="alipay"
            icon-size="18px"
          ></van-radio>
        </div>
      </van-radio-group>
    </div>
    <div class="box_5">
      <div class="content">
        应付金额：
        <div class="price"><span>￥</span>{{ amount }}</div>
        <van-button
          color="linear-gradient(to right, #f1cd91, #dab577)"
          text-color="#92672c"
          round
          size="small"
          @click="submit"
          >立即支付</van-button
        >
      </div>
    </div>
    <van-popup v-model="showCouponPicker" round position="bottom"
      ><van-picker
        show-toolbar
        :columns="couponList"
        @confirm="chooseCoupon"
        @cancel="showCouponPicker = false"
    /></van-popup>
    <PaySubmit ref="paySubmit" :payment="submitData.payment" success-url="/member/order/list"></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from '@/components/service/PaySubmit'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'BuySetmeal',
  components: {
    PaySubmit
  },
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{m}-{d}')
    }
  },
  data () {
    return {
      showCouponPicker: false,
      active_index: 0,
      dataset: [],
      old_amount: 0.0,
      amount: 0.0,
      couponName: '暂无可用优惠券',
      submitData: {
        service_type: 'setmeal',
        service_id: 0,
        payment: 'wxpay',
        coupon_id: 0
      },
      setmealDetail: {},
      openDetail: true,
      couponList: [{ id: 0, text: '不使用优惠券', name: '不使用优惠券' }]
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    handlerQuery () {
      if (
        this.$route.query.setmeal_id !== undefined &&
        this.$route.query.setmeal_id > 0 &&
        this.$route.query.coupon_id !== undefined &&
        this.$route.query.coupon_id > 0
      ) {
        this.submitData.service_id = this.$route.query.setmeal_id
        this.submitData.coupon_id = this.$route.query.coupon_id
        for (const key in this.dataset) {
          if (this.dataset[key].id == this.submitData.service_id) {
            this.changeItem(this.dataset[key], key)
            for (const k in this.couponList) {
              if (this.couponList[k].id == this.submitData.coupon_id) {
                this.chooseCoupon(this.couponList[k])
                break
              }
            }
            break
          }
        }
      }
    },
    toggleOpen () {
      this.openDetail = !this.openDetail
    },
    fetchData () {
      http
        .get(api.company_setmeallist, {})
        .then(res => {
          this.active_index = 0
          this.dataset = []
          let list = res.data.items
          for (const iterator of list) {
            let expense_arr = iterator.expense.split('.')
            iterator.expense1 = expense_arr[0]
            iterator.expense2 = expense_arr[1]
            this.dataset.push(iterator)
          }
          if (list.length > 0) {
            this.submitData.service_id = list[0].id
            this.old_amount = list[0].expense
            this.amount = list[0].expense
            list[0].couponList.forEach(element => {
              element.text = element.name + '(-￥' + element.face_value + ')'
              this.couponList.push(element)
            })
            if (this.couponList.length > 1) {
              this.couponName = '选择优惠券'
            }
            this.setmealDetail = list[0]
            this.handlerQuery()
          }
        })
        .catch(() => {})
    },
    changeItem (item, index) {
      this.active_index = index
      this.submitData.service_id = item.id
      this.old_amount = item.expense
      this.amount = item.expense
      this.setmealDetail = item
      this.submitData.coupon_id = 0
      this.couponList = [{ id: 0, text: '不使用优惠券', name: '不使用优惠券' }]
      item.couponList.forEach(element => {
        element.text = element.name + '(-￥' + element.face_value + ')'
        this.couponList.push(element)
      })
      if (this.couponList.length > 1) {
        this.couponName = '选择优惠券'
      } else {
        this.couponName = '暂无可用优惠券'
      }
    },
    submit () {
      this.submitData.return_url = this.$store.state.config.mobile_domain + 'member/order/list'
      this.$refs.paySubmit.handlerSubmit(api.company_pay, this.submitData)
    },
    handlerCouponPicker () {
      if (this.couponList.length <= 1) {
        return false
      }
      this.showCouponPicker = true
    },
    chooseCoupon (value) {
      this.amount = this.old_amount
      this.submitData.coupon_id = value.id
      this.couponName = value.name
      this.showCouponPicker = false
      if (value.face_value !== undefined) {
        this.amount = this.amount - value.face_value
        this.amount = this.amount.toFixed(2)
        if (this.amount < 0) {
          this.amount = 0.0
        }
      }
    },
    choosePayment (payment) {
      this.submitData.payment = payment
    }
  }
}
</script>

<style lang="scss" scoped>
.box_5 {
  .content {
    .van-button {
      position: absolute;
      right: 17px;
      top: 50%;
      transform: translate(0, -50%);
      padding: 11px 28px;
      font-size: 16px;
      height: auto;
      line-height: 1;
    }
    .price {
      span {
        font-size: 12px;
        font-weight: normal;
      }
      position: absolute;
      left: 88px;
      top: 50%;
      transform: translate(0, -50%);
      color: #ff6600;
      font-size: 18px;
      font-weight: bold;
    }
    position: relative;
    width: 100%;
    height: 100%;
    padding: 18px 17px;
    font-size: 15px;
    color: #333333;
  }
  width: 100%;
  background-color: #ffffff;
  position: fixed;
  box-shadow: 0 -1px 10px #c2c2c2;
  left: 0;
  right: 0;
  bottom: 0;
}
.box_pay_pop {
  .pay_item {
    &.wx {
      background: url("../../../assets/images/wx_pay_ico.svg") 3px center
        no-repeat;
      background-size: 18px;
    }
    &.ali {
      background: url("../../../assets/images/ali_pay_ico.svg") 3px center
        no-repeat;
      background-size: 20px;
    }
    .self_check {
      position: absolute;
      right: 15px;
      top: 19px;
    }
    position: relative;
    width: 345px;
    margin: 0 auto;
    padding: 16px 0 16px 30px;
    border-bottom: 1px solid #f8f8f8;
    font-size: 16px;
    color: #666666;
  }
  background-color: #ffffff;
  width: 100%;
  padding-bottom: 78px;
}
.box_4 {
  .right_txt {
    position: absolute;
    right: 17px;
    top: 18px;
    font-size: 14px;
    color: #666666;
    padding-right: 17px;
    background: url("../../../assets/images/form_choose_arrow.svg") right center
      no-repeat;
    background-size: 14px;
  }
  padding: 17px 17px 22px;
  width: 100%;
  background-color: #ffffff;
  position: relative;
  font-size: 14px;
  color: #333333;
}
.box_3 {
  .content {
    .tx3 {
      .hide {
        display: inline-block;
        padding: 15px 18px 0 0;
        font-size: 13px;
        color: #2199ff;
        background: url("../../../assets/images/open_more_info.png") right 18px
          no-repeat;
        background-size: 13px;
      }
      text-align: center;
    }
    .tx2 {
      padding-top: 13px;
      font-size: 12px;
      color: #666666;
      span {
        &:first-child {
          width: 153px;
          display: inline-block;
          vertical-align: top;
        }
        &.dec {
          width: 100%;line-height: 1.7;
        }
      }
      .tq {
        width: 49%;
        display: inline-block;
        padding-bottom: 15px;
      }
    }
    .tx1 {
      padding-top: 17px;
      font-size: 15px;
      font-weight: bold;
      color: #333333;
    }
    width: 100%;
    background-color: #f9f9f9;
    border-radius: 5px;
    padding: 0 15px 12px;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;
}
.inv-top-menu-bar {
  background-color: #ffffff;
  padding: 0 17px 20px;
  .top-menu {
    overflow: hidden;
    overflow-x: scroll;
    -webkit-overflow-scrolling: touch;
    &::-webkit-scrollbar {
      display: none;
    }
  }
  .top-menu-list {
    white-space: nowrap;
    .item {
      &.recommend::after {
        content: "推荐";
        position: absolute;
        left: -2px;
        top: -2px;
        background-color: #ff0000;
        color: #ffffff;
        font-size: 12px;
        border-radius: 5px 0 5px 0;
        padding: 3.5px;
      }
      &.time::after {
        content: "限时";
        position: absolute;
        left: -2px;
        top: -2px;
        background-color: #ff0000;
        color: #ffffff;
        font-size: 12px;
        border-radius: 5px 0 5px 0;
        padding: 3.5px;
      }
      .tx5 {
        font-size: 11px;
        color: #999999;
        padding-top: 15px;
      }
      .tx4 {
        font-size: 11px;
        color: #92672c;
        padding-top: 5px;
      }
      .tx3 {
        font-size: 11px;
        color: #92672c;
        padding-top: 9px;
      }
      .tx2 {
        font-size: 20px;
        font-weight: bold;
        color: #92672c;
        padding-top: 10px;
        .unit {
          font-weight: normal;
          font-size: 10px;
        }
        .tofix {
          font-size: 14px;
        }
      }
      .tx1 {
        font-size: 14px;
        font-weight: bold;
        color: #666666;
      }
      position: relative;
      width: 100px;
      height: 135px;
      text-align: center;
      padding-top: 17px;
      background-color: #ffffff;
      border: 2px solid #e2e2e2;
      border-radius: 5px;
      display: inline-block;
      white-space: nowrap;
      vertical-align: top;
      &.active {
        .tx1,
        .tx5 {
          color: #92672c;
        }
        border: 2px solid #dab577;
        background-color: #fffcf5;
      }
      &:not(:first-child) {
        margin-left: 10px;
      }
    }
  }
}
</style>
