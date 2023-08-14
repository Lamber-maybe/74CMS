<template>
  <div id="app">
    <div class="box_6">
      <div class="head">选择增值服务</div>
      <div class="tag_wrapper">
        <div
          class="tag"
          :class="{ active: params.type == 'jobstick' }"
          @click="changeType('jobstick')"
        >
          职位置顶
        </div>
        <div
          class="tag"
          :class="{ active: params.type == 'emergency' }"
          @click="changeType('emergency')"
        >
          紧急招聘
        </div>
        <div
          class="tag"
          :class="{ active: params.type == 'resume_package' }"
          @click="changeType('resume_package')"
        >
          简历包
        </div>
        <div
          class="tag"
          :class="{ active: params.type == 'job_refresh' }"
          @click="changeType('job_refresh')"
        >
          智能刷新
        </div>
      </div>
    </div>
    <div class="inv-top-menu-bar">
      <div class="top-menu">
        <div class="top-menu-list">
          <div
            class="item"
            :class="{
              active: active_index == index,
              time: item.recommend == 1
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
            <div class="tx5">低至{{ item.expense_avg }}</div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="box_4"
      v-if="params.type != 'resume_package'"
      @click="showPicker = true"
    >
      <div class="content">
        职位名称
        <div class="right_txt">{{ jobname }}</div>
      </div>
    </div>
    <div class="box_4" v-if="mySetmeal.service_added_discount > 0">
      <div class="content">
        专享折扣
        <div class="right_txt no_arrow">
          增值服务{{ mySetmeal.service_added_discount }}折
        </div>
      </div>
    </div>
    <div
      class="box_4"
      v-if="params.type == 'job_refresh'"
      @click="showDatetimePicker = true"
    >
      <div class="content">
        开始刷新时间
        <div class="right_txt no_arrow">
          {{ submitData.starttime == "" ? "请选择" : submitData.starttime }}
        </div>
      </div>
    </div>
    <div
      class="box_4"
      v-if="params.type == 'job_refresh'"
      @click="showTimerangePicker = true"
    >
      <div class="content">
        刷新间隔
        <div class="right_txt no_arrow">
          {{ refresh_timerange }}
        </div>
      </div>
    </div>
    <div class="box_4 mb" v-if="enable_points_deduct == 1">
      <div class="content no_border">
        {{ $store.state.config.points_byname }}抵扣
        <div class="right_txt no_arrow">
          可使用{{ enable_points_deduct_points
          }}{{ $store.state.config.points_byname }}抵用{{
            enable_points_deduct_expense
          }}元
        </div>
        <van-checkbox
          v-model="is_deduct"
          icon-size="14px"
          @change="changeDeduct"
        ></van-checkbox>
      </div>
    </div>
    <div class="form_split_10" v-if="amount > 0"></div>
    <div class="box_pay_pop" v-if="amount > 0">
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
          >{{ amount == 0 ? "立即兑换" : "立即支付" }}</van-button
        >
      </div>
    </div>
    <van-popup v-model="showPicker" round position="bottom"
      ><van-picker
        show-toolbar
        :columns="joblist"
        @confirm="chooseJob"
        @cancel="showPicker = false"
    /></van-popup>
    <van-popup v-model="showDatetimePicker" round position="bottom">
      <van-datetime-picker
        v-model="refresh_starttime"
        type="datetime"
        title="请选择开始刷新时间"
        @confirm="handlerRefreshStarttime"
        :min-date="minDate"
        :formatter="formatter"
      />
    </van-popup>
    <van-popup v-model="showTimerangePicker" round position="bottom">
      <van-picker
        show-toolbar
        :columns="options_timerange"
        @confirm="handlerTimerange"
      />
    </van-popup>
    <PaySubmit ref="paySubmit" :payment="submitData.payment" success-url="/member/order/list"></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from '@/components/service/PaySubmit'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'BuyIncrement',
  props: ['mySetmeal'],
  components: {
    PaySubmit
  },
  data () {
    return {
      options_timerange: [],
      showTimerangePicker: false,
      minDate: '',
      showDatetimePicker: false,
      showPicker: false,
      active_index: 0,
      myPoints: 0,
      dataset: [],
      old_amount: 0.0,
      amount: 0.0,
      is_deduct: false,
      jobname: '',
      enable_points_deduct: 0,
      enable_points_deduct_points: 0,
      enable_points_deduct_expense: 0.0,
      after_deduct_expense: 0.0,
      params: {
        type: 'jobstick'
      },
      joblist: [],
      submitData: {
        service_type: '',
        service_id: 0,
        deduct_points: 0,
        starttime: '',
        timerange: '',
        payment: 'wxpay',
        jobid: 0
      },
      refresh_timerange: '请选择',
      refresh_starttime: '',
      submitLock: false
    }
  },
  watch: {
    amount: function (newVal) {
      if (newVal == 0) {
        this.submitData.payment = 'free'
      } else {
        this.submitData.payment = 'wxpay'
      }
    }
  },
  created () {
    let objDate = new Date()
    this.minDate = new Date(
      objDate.getFullYear(),
      objDate.getMonth(),
      objDate.getDate(),
      objDate.getHours(),
      objDate.getMinutes(),
      0
    )
    this.options_timerange = []
    for (let i = 1; i <= 12; i++) {
      this.options_timerange.push({ text: i + '小时', value: i * 3600 })
    }
    this.options_timerange.push({ text: '24小时', value: 24 * 3600 })
    this.options_timerange.push({ text: '48小时', value: 48 * 3600 })
    this.initDB()
    this.fetchData()
  },
  methods: {
    initDB () {
      if (this.$route.query.service_type !== undefined) {
        this.params.type = this.$route.query.service_type
        if (this.$route.query.jobid !== undefined) {
          this.submitData.jobid = this.$route.query.jobid
        }
      }
    },
    fetchData () {
      http
        .get(api.company_servicelist, this.params)
        .then(res => {
          this.active_index = 0
          this.dataset = []
          let list = res.data.items
          for (const iterator of list) {
            let expense_arr = iterator.expense.split('.')
            iterator.expense1 = expense_arr[0]
            iterator.expense2 = expense_arr[1]
            if (iterator.times !== undefined) {
              iterator.expense_avg = iterator.expense / iterator.times
              iterator.expense_avg = iterator.expense_avg.toFixed(2) + '元/次'
            } else if (iterator.days !== undefined) {
              iterator.expense_avg = iterator.expense / iterator.days
              iterator.expense_avg = iterator.expense_avg.toFixed(2) + '元/天'
            } else {
              iterator.expense_avg =
                iterator.expense / iterator.download_resume_point
              iterator.expense_avg = iterator.expense_avg.toFixed(2) + '元/点'
            }
            this.dataset.push(iterator)
          }
          this.submitData.service_id = list[0].id
          this.myPoints = res.data.member_points
          this.enable_points_deduct = list[0].enable_points_deduct
          this.enable_points_deduct_points =
            list[0].enable_points_deduct_points
          this.enable_points_deduct_expense =
            list[0].enable_points_deduct_expense
          this.after_deduct_expense = list[0].after_deduct_expense
          // 计算最终需要支付的金额
          this.old_amount = list[0].expense
          this.handlerFinalAmount()
          this.joblist = res.data.joblist
          this.joblist.forEach(element => {
            element.text = element.jobname
          })
          if (this.joblist.length == 0) {
            this.jobname = '暂无职位'
          } else if (
            this.submitData.jobid === undefined ||
            !this.submitData.jobid
          ) {
            this.submitData.jobid = this.joblist[0].id
            this.jobname = this.joblist[0].text
          } else {
            for (const key in this.joblist) {
              if (this.joblist[key].id == this.submitData.jobid) {
                this.jobname = this.joblist[key].text
                break
              }
            }
            if (this.jobname == '') {
              this.submitData.jobid = 0
              this.jobname = '请选择'
            }
          }
        })
        .catch(() => {})
    },
    changeDeduct () {
      // 计算最终需要支付的金额
      this.handlerFinalAmount()
    },
    handlerFinalAmount () {
      if (this.is_deduct) {
        this.amount = this.after_deduct_expense
      } else {
        this.amount = this.old_amount
      }
    },
    changeType (type) {
      this.params.type = type
      this.fetchData()
    },
    changeItem (item, index) {
      this.active_index = index
      this.enable_points_deduct = item.enable_points_deduct
      this.enable_points_deduct_points = item.enable_points_deduct_points
      this.enable_points_deduct_expense = item.enable_points_deduct_expense
      this.after_deduct_expense = item.after_deduct_expense
      // 计算最终需要支付的金额
      this.old_amount = item.expense
      this.handlerFinalAmount()
      this.submitData.service_id = item.id
    },
    chooseJob (value) {
      this.submitData.jobid = value.id
      this.jobname = value.text
      this.showPicker = false
    },
    submit () {
      if (this.submitLock) {
        console.log('请勿重复点击')
        return
      }
      this.submitLock = true
      this.submitData.deduct_points = this.is_deduct
        ? this.enable_points_deduct_points
        : 0
      this.submitData.service_type =
        this.params.type == 'job_refresh'
          ? 'refresh_job_package'
          : this.params.type
      if (
        this.submitData.service_type == 'refresh_job_package' &&
        this.submitData.starttime == ''
      ) {
        this.$notify('请选择开始刷新时间')
        return false
      }
      if (
        this.submitData.service_type == 'refresh_job_package' &&
        this.submitData.timerange == ''
      ) {
        this.$notify('请选择刷新时间间隔')
        return false
      }
      this.submitData.return_url = this.$store.state.config.mobile_domain + 'member/order/list'
      this.$refs.paySubmit.handlerSubmit(api.company_pay, this.submitData)
    },
    handlerRefreshStarttime () {
      let t = Date.parse(this.refresh_starttime) / 1000
      t = parseTime(t, '{y}-{m}-{d} {h}:{i}')
      this.submitData.starttime = t
      this.showDatetimePicker = false
    },
    formatter (type, val) {
      if (type === 'year') {
        return `${val}年`
      } else if (type === 'month') {
        return `${val}月`
      } else if (type === 'day') {
        return `${val}日`
      } else if (type === 'hour') {
        return `${val}时`
      } else if (type === 'minute') {
        return `${val}分`
      }
      return val
    },
    handlerTimerange (e) {
      this.submitData.timerange = e.value
      this.refresh_timerange = e.text
      this.showTimerangePicker = false
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
  &.mb {
    margin-bottom: 9px;
  }
  .content {
    .van-checkbox {
      position: absolute;
      right: 2px;
      top: 20px;
    }
    &.no_border {
      border-bottom: 0;
    }
    .right_txt {
      &.no_arrow {
        background: none;
      }
      position: absolute;
      right: 7px;
      top: 17px;
      font-size: 14px;
      color: #666666;
      padding-right: 17px;
      background: url("../../../assets/images/form_choose_arrow.svg") right
        center no-repeat;
      background-size: 14px;
    }
    position: relative;
    font-size: 14px;
    color: #333333;
    padding: 16px 2px;
  }
  padding: 0 17px;
  width: 100%;
  background-color: #ffffff;
}
.inv-top-menu-bar {
  background-color: #ffffff;
  padding: 0 17px 15px;
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
      &.time::after {
        content: "推荐";
        position: absolute;
        left: -2px;
        top: -2px;
        background-color: #ff0000;
        color: #ffffff;
        font-size: 11px;
        border-radius: 5px 0 5px 0;
        padding: 2px 3.5px;
      }
      .tx5 {
        font-size: 11px;
        color: #999999;
        padding-top: 15px;
      }
      .tx4 {
        font-size: 12px;
        color: #92672c;
        padding-top: 5px;
      }
      .tx3 {
        font-size: 12px;
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
      width: 113px;
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
.box_6 {
  .tag_wrapper {
    .tag {
      &.active {
        color: #92672c;
        border: 1px solid #dab577;
        background-color: #fffcf5;
      }
      display: inline-block;
      vertical-align: top;
      margin-left: 8px;
      padding: 4px 12px;
      background-color: #f5f5f5;
      font-size: 12px;
      color: #999999;
      border: 1px solid #f5f5f5;
      border-radius: 26px;
      &:nth-of-type(1) { margin-left: 0; }
    }
    padding: 16px 0 16px;
  }
  .head {
    font-size: 15px;
    font-weight: bold;
    color: #333333;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;
}
</style>
