<template>
  <el-card>
    <company-title>订单详情</company-title>
    <div>
        <div class="order_outline">
          <div class="outline_title">订单概要</div>
          <ul class="outline_list">
              <li><span>订单编号：</span>{{ info.oid }}</li>
              <li><span>订单类型：</span>{{ info.service_name }}</li>
              <li><span>创建时间：</span>{{ info.addtime | timeFilter }}</li>
              <li v-if="info.status == 0"><span>付款时间：</span>待付款</li>
              <li v-if="info.status == 1"><span>付款时间：</span>{{ info.paytime | timeFilter }}</li>
              <li><span>支付渠道：</span><span>{{ info.payment_text == "" ? "无" : info.payment_text }}</span></li>
          </ul>
          <div class="img">
            <img v-if="info.status==0" src="../../../assets/images/personal/payment_1.png" />
            <img v-if="info.status==1" src="../../../assets/images/personal/payment_2.png" />
            <img v-if="info.status==2" src="../../../assets/images/personal/payment_3.png" />
          </div>
        </div>
        <div class="order_con">
          <div class="outline_title">订单内容</div>
          <ul class="outline_list">
              <li><span>服务名称：</span>{{ info.service_name }}</li>
              <li><span>服务内容：</span>{{info.service_type_text}}</li>
              <li v-if="info.extra.tag_text !== undefined"><span>使用标签：</span>{{ info.extra.tag_text }}</li>
              <li><span>服务金额：</span>{{ info.service_amount }} 元</li>
          </ul>
        </div>
        <div class="order_payment" v-if="info.status == 0">
          <div class="outline_title">订单支付</div>
          <ul class="payment">
              <li v-if="info.deduct_points > 0"><span>{{ $store.state.config.points_byname }}抵扣：</span>-{{ info.deduct_amount }} 元</li>
              <li class="payment_type" v-if="info.status == 0">
                <span>支付方式：</span>
                <Payment @choosePayment="choosePayment"></Payment>
              </li>
              <li><span>应付金额：</span><strong class="priceColor">{{ info.amount }}</strong>元 </li>
              <li class="payment_btn">
                <el-button round class="pay" @click="handlerSubmit">继续支付</el-button>
                <el-button round @click="handlerCancel">取消订单</el-button>
              </li>
          </ul>
        </div>
        <div class="remove_payment" v-if="info.status == 2">
          <el-button round plain @click="handlerDel">删除订单</el-button>
        </div>
    </div>
    <PaySubmit ref="paySubmit" :showWaiting="true" :payment="payment" successUrl="/personal/service/order" failUrl="/personal/service/order"></PaySubmit>
  </el-card>
</template>

<script>
import PaySubmit from "@/components/member/service/PaySubmit";
import Payment from "@/components/member/service/Payment";
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
  export default{
    name:'PersonalOrderDetail',
    components:{
      PaySubmit,
      Payment
    },
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    },
    dateFilter (timestamp) {
      return parseTime(timestamp, '{m}月{d}日')
    }
  },
  data () {
    return {
      order_id: 0,
      info: {
        extra: {}
      },
      payment: 'wxpay',
    }
  },
  created () {
    this.order_id = this.$route.params.id
    this.fetchData()
  },
  methods: {
    choosePayment (payment) {
      this.payment = payment
    },
    fetchData () {
      http
        .get(api.personal_orderdetail, { id: this.order_id })
        .then(res => {
          this.info = { ...res.data }
        })
        .catch(() => {})
    },
    handlerSubmit () {
      this.$refs.paySubmit.handlerSubmit(api.personal_repay,{ id: this.order_id, payment: this.payment })
    },
    handlerCancel () {
      this.$confirm('确定取消该订单吗？','系统提示',{type:'warning'})
        .then(() => {
          http
            .post(api.personal_order_cancel, { id: this.order_id })
            .then(res => {
              this.$message({ type: 'success', message: res.message })
              this.$router.push('/personal/service/order')
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerDel () {
      this.$confirm('确定删除该订单吗？','系统提示',{type:'warning'})
        .then(() => {
          http
            .post(api.personal_order_del, { id: this.order_id })
            .then(res => {
              this.$message({ type: 'success', message: res.message })
              this.$router.push('/personal/service/order')
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
  .order_outline{
    position: relative;
  }
  .outline_title{
    background:#f4f5f9 ;
    color: #333333;
    font-size: 14px;
    border-left:3px solid #999999 ;
    padding: 13px 0px 13px 11px;
  }
  .outline_list{
    margin: 29px 10px;
    display:flex;
    flex-wrap:wrap;
  }
  .outline_list li{
    width: 429px;
    margin-bottom: 20px;
    font-size:13px ;
    color: #666666;
  }
  .payment{
    margin: 29px 10px;
  }

  .payment li{
    margin-bottom: 30px;
  }


  .payment_type{
    display: flex;
    line-height: 42px;
  }
  .payment_btn{
    text-align: center;
  }
  .payment_btn .el-button{
    width: 220px;
    height: 44px;
    font-size:16px;
    padding: 15px 80px;
    margin-top: 20px;
    &.pay{
      color: #ffffff;
      background: #ff7252;
      box-shadow: 0px 0px 5px 5px #fff3f0;
    }
  }
  .img{
    width: 84px;
    height: 74px;
    position: absolute;
    right: 5px;
    top: 14px;
  }
  .priceColor{
    color: #ff7c60;
    font-size: 12px;
    font-weight: normal;
  }
  .remove_payment{
    text-align: center;
  }
  .remove_payment .el-button{
    padding: 14px 78px;
    border: 1px solid #e2e2e2;
    color: #b7b7b7;
    font-size: 15px;
  }
</style>
