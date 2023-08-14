<template>
  <ul class="dialog_wrapper">
    <li><span class="margin">服务内容</span>{{info.service_type_text}}</li>
    <li v-if="info.extra.jobname !== undefined"><span class="margin">推广职位</span>{{ info.extra.jobname }}</li>
    <li v-if="info.extra.tag_text !== undefined"><span class="margin">使用标签</span>{{ info.extra.tag_text }}</li>
    <li v-if="info.service_type == 'setmeal'"><span class="margin">所选套餐</span>{{ info.service_name }}</li>
    <li v-if="info.extra.preferential_info !== undefined"><span class="margin">限时特惠</span>{{ info.extra.preferential_info.amount }} 元（({{ info.extra.preferential_info.starttime | dateFilter }}至{{ info.extra.preferential_info.endtime | dateFilter }})）</li>
    <li v-if="info.extra.coupon_info !== undefined"><span class="margin">优惠券</span>-￥{{ info.extra.coupon_info.face_value }}</li>
    <li v-if="info.extra.service_added_discount !== undefined && info.extra.service_added_discount > 0"><span class="margin">折扣金额</span>-￥{{ info.service_amount_after_discount }}</li>
    <li v-if="info.deduct_points > 0"><span class="margin">{{ $store.state.config.points_byname }}抵扣</span>-￥{{ info.deduct_amount }}</li>
    <li v-if="info.service_type == 'points'"><span class="margin">充值{{ $store.state.config.points_byname }}</span>{{ info.extra.add_points }} {{ $store.state.config.points_byname }}</li>
    <li><span class="margin">应付金额</span><span class="color">￥{{info.amount}}</span></li>
    <li class="payment_type">
      <span class="margin">支付方式</span>
      <Payment @choosePayment="choosePayment"></Payment>
    </li>
    <li class="btn">
      <span class="margin"></span>
      <el-button type="primary" @click="handlerSubmit">继续支付</el-button>
    </li>
    <PaySubmit ref="paySubmit" :showWaiting="true" :payment="payment" :successUrl="$store.state.config.LoginType==1?'/company/service/order':'/personal/service/order'" :failUrl="$store.state.config.LoginType==1?'/company/service/order':'/personal/service/order'"></PaySubmit>
  </ul>
</template>

<script>
import PaySubmit from "@/components/member/service/PaySubmit";
import Payment from "@/components/member/service/Payment";
import http from '@/utils/http'
import api from '@/api'
  export default{
    components:{
      PaySubmit,
      Payment
    },
    props:['orderId'],
    data(){
      return{
        info: {
          extra: {}
        },
        payment: 'alipay',
        shortDesc: '',
        showWaitingPay:false,
      }
    },
    created(){
    },
    methods:{
      choosePayment (payment) {
        this.payment = payment
      },
      fetchData () {
        let url =
        this.$store.state.LoginType == 1
          ? api.company_orderdetail
          : api.personal_orderdetail
        http
          .get(url, { id: this.orderId })
          .then(res => {
            this.info = { ...res.data }
          })
          .catch(() => {})
      },
      handlerSubmit () {
        let that = this
        let url =
          this.$store.state.LoginType == 1
            ? api.company_repay
            : api.personal_repay
        // http
        //   .post(url, { id: this.orderId, payment: this.payment })
        //   .then(res => {
        //     if (res.data.pay_status == 1) {
        //       this.$message({ type: 'success', message: '支付成功' })
        //       setTimeout(() => {
        //         if (that.$store.state.LoginType == 1) {
        //           that.$router.push('/company/service/order')
        //         } else {
        //           that.$router.push('/personal/service/order')
        //         }
        //       }, 1500)
        //       return false
        //     } else {
        //       this.handlerPay(res.data)
        //     }
        //   })
        //   .catch(() => {})
        this.$refs.paySubmit.handlerSubmit(url,{ id: this.orderId, payment: this.payment },function(){
          that.$emit('submitPay')
        })
      },
      // handlerPay (data) {
      //   if (this.payment == 'wxpay') {
      //     const {href} = this.$router.resolve({
      //         path: '/wxpay',
      //         query: {
      //             parameter: data.parameter,
      //             oid: this.info.oid,
      //             amount: this.info.amount
      //         }
      //     });
      //     window.open(href);
      //   } else {
      //     window.open(data.parameter)
      //   }
      //   this.$emit('submitPay')
      // }
    }
  }
</script>

<style lang="scss" scoped>
  .dialog_wrapper{
    padding: 0 15px;
  }
  .dialog_wrapper li{
    margin-bottom: 20px;
  }
  .payment_type{
    display: flex;
    line-height: 42px;
  }
  .margin{
    margin-right: 15px;
    color: #666666;
    font-size: 13px;
    width:55px;
    display:inline-block;
  }
  .color{
    color: #ff6600;
    font-size:14px ;
  }
  .color_2{
    color: #333333;
    font-size: 15px;
  }
  .el_select_width{
    width: 270px;
  }
</style>
