<template>
  <div>
    <el-card>
      <div class="payment_wrapper">
        <div class="payment_title">
          <h3>{{ info.service_name }}</h3>
          <span>{{ shortDesc }}</span>
        </div>
        <div class="payment_img">
          <img
            v-if="info.status == 0"
            src="../../../assets/images/payment_icon_1.png"
          />
          <img
            v-if="info.status == 1"
            src="../../../assets/images/payment_icon_2.png"
          />
          <img
            v-if="info.status == 2"
            src="../../../assets/images/payment_icon_3.png"
          />
        </div>
      </div>
    </el-card>

    <el-card>
      <div>
        <div class="payment_con">
          <div class="con_title">订单内容</div>
          <ul class="con_list">
            <li class="con_width" v-if="info.extra.jobname !== undefined">
              <span>推广职位：</span>{{ info.extra.jobname }}
            </li>
            <li class="con_width" v-if="info.extra.tag_text !== undefined">
              <span>使用标签：</span>{{ info.extra.tag_text }}
            </li>
            <li><span>服务内容：</span>{{ info.service_type_text }}</li>
            <li><span>服务金额：</span>{{ info.service_amount }} 元</li>
            <li v-if="info.extra.preferential_info !== undefined">
              <span>限时特惠：</span
              >{{ info.extra.preferential_info.amount }} 元
              <span
                >({{ info.extra.preferential_info.starttime | dateFilter }}至{{
                  info.extra.preferential_info.endtime | dateFilter
                }})</span
              >
            </li>
            <li v-if="info.extra.coupon_info !== undefined">
              <span>优惠券：</span>{{ info.extra.coupon_info.name }}
            </li>
            <li v-if="info.extra.coupon_info !== undefined">
              <span>优惠券折扣：</span>-{{ info.extra.coupon_info.face_value }}
              元
            </li>
            <li
              v-if="
                info.extra.service_added_discount !== undefined &&
                info.extra.service_added_discount > 0
              "
            >
              <span>专享折扣：</span>增值服务{{
                info.extra.service_added_discount
              }}
              折
            </li>
            <li
              v-if="
                info.extra.service_added_discount !== undefined &&
                info.extra.service_added_discount > 0
              "
            >
              <span>折扣金额：</span>-{{ info.service_amount_after_discount }}
              元
            </li>
            <li v-if="info.deduct_points > 0">
              <span>{{ $store.state.config.points_byname }}抵扣：</span>-{{
                info.deduct_amount
              }}
              元
            </li>
            <li v-if="info.service_type == 'points'">
              <span>充值{{ $store.state.config.points_byname }}：</span
              >{{ info.extra.add_points }}
              {{ $store.state.config.points_byname }}
            </li>
            <li>
              <span>应付金额：</span
              ><span class="con_font_color">{{ info.amount }}</span> 元
            </li>
          </ul>
          <div class="con_title">订单信息</div>
          <ul class="con_list">
            <li><span>订单编号：</span>{{ info.oid }}</li>
            <li><span>创建时间：</span>{{ info.addtime | timeFilter }}</li>
            <li v-if="info.status == 0"><span>付款时间：</span>待付款</li>
            <li v-if="info.status == 1">
              <span>付款时间：</span>{{ info.paytime | timeFilter }}
            </li>
            <li>
              <span>支付渠道：</span
              ><span>{{
                info.payment_text == "" ? "无" : info.payment_text
              }}</span>
            </li>

            <li class="payment_type" v-if="info.status == 0">
              <span>支付方式：</span>
              <Payment @choosePayment="choosePayment"></Payment>
            </li>
            <li class="payment_btn">
              <el-button
                type="primary"
                v-if="info.status == 0"
                @click="handlerSubmit"
                >继续支付</el-button
              >
              <el-button v-if="info.status == 0" @click="handlerCancel"
                >取消订单</el-button
              >
              <el-button
                v-if="info.status == 2"
                class="single"
                @click="handlerDel"
                >删除订单</el-button
              >
            </li>
          </ul>
        </div>
      </div>
    </el-card>
    <PaySubmit
      ref="paySubmit"
      :showWaiting="true"
      :payment="payment"
      successUrl="/company/service/order"
      failUrl="/company/service/order"
    ></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from "@/components/member/service/PaySubmit";
import Payment from "@/components/member/service/Payment";
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'CompanyOrderDetail',
  components: {
    PaySubmit,
    Payment
  },
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    },
    dateFilter(timestamp) {
      return parseTime(timestamp, '{m}月{d}日')
    }
  },
  data() {
    return {
      order_id: 0,
      info: {
        extra: {}
      },
      payment: 'alipay',
      shortDesc: '',
      showWaitingPay: false,
    }
  },
  created() {
    this.order_id = this.$route.params.id
    this.fetchData()
  },
  methods: {
    choosePayment(payment) {
      this.payment = payment
    },
    fetchData() {
      http
        .get(api.company_orderdetail, { id: this.order_id })
        .then(res => {
          this.info = { ...res.data }
          this.shortDesc = this.filterDesc(this.info)
        })
        .catch(() => { })
    },
    filterDesc(info) {
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
      }
      return return_str
    },
    handlerSubmit() {
      this.$refs.paySubmit.handlerSubmit(api.company_repay, { id: this.order_id, payment: this.payment })
    },
    handlerCancel() {
      this.$confirm('确定取消该订单吗？', '系统提示', { type: 'warning' })
        .then(() => {
          http
            .post(api.company_order_cancel, { id: this.order_id })
            .then(res => {
              this.$message({ type: 'success', message: res.message })
              this.$router.push('/company/service/order')
            })
            .catch(() => { })
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerDel() {
      this.$confirm('确定删除该订单吗？', '系统提示', { type: 'warning' })
        .then(() => {
          http
            .post(api.company_order_del, { id: this.order_id })
            .then(res => {
              this.$message({ type: 'success', message: res.message })
              this.$router.push('/company/service/order')
            })
            .catch(() => { })
        })
        .catch(() => {
          // on cancel
        })
    }
  }
}
</script>

<style>
.el-card {
  margin-bottom: 10px;
}
.payment_wrapper {
  display: flex;
  padding: 16px 20px;
}
.payment_title {
  flex: 7;
}
.payment_title h3 {
  font-size: 30px;
  margin-bottom: 18px;
}
.payment_img {
  flex: 1;
}
.payment_img img {
  width: 84px;
  height: 74px;
  margin-left: 10px;
}
.payment_title span {
  color: #999999;
  font-size: 14px;
}
.con_title {
  padding: 20px 0px;
  margin: 0 20px;
  border-bottom: 1px solid #ebeef5;
  font-size: 18px;
  color: #333333;
}
.con_list {
  display: flex;
  flex-wrap: wrap;
  margin: 0 20px;
  padding-top: 30px;
}
.con_list li {
  width: 700px;
  margin-bottom: 32px;
  font-size: 14px;
  color: #666666;
}
.con_font_color {
  color: #ff704f;
}
.payment_type {
  display: flex;
  line-height: 44px;
}
.con_list .payment_btn {
  width: 100%;
  padding: 30px 0 0 70px;
}
.payment_btn .el-button.single {
  width: 260px;
}
.con_list .con_width {
  width: 100%;
}
</style>
