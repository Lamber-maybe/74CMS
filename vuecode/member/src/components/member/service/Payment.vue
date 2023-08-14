<template>
    <div class="payment-box">
        <div class="item" :class="[payment=='alipay'?'border':'']" @click="choosePayment('alipay')"><img src="../../../assets/images/payment_icon_4.png"/>支付宝支付<i v-if="payment=='alipay'" class="el-icon-check"></i></div>
        <div class="item" :class="[payment=='wxpay'?'border':'']" @click="choosePayment('wxpay')"><img src="../../../assets/images/payment_icon_5.png" />微信支付 <i v-if="payment=='wxpay'" class="el-icon-check"></i></div>
    </div>
</template>

<script>
  export default {
    name: 'Payment',
    data(){
      return{
        payment:'alipay'
      }
    },
    created(){
      
    },
    methods:{
        choosePayment(payment){
            this.payment = payment
            this.$emit('choosePayment',this.payment)
        }
    }
  }
</script>
<style lang="scss" scoped>
.payment-box{
    display:flex;
    line-height:42px;
    .item{
        width: 150px;
        height: 44px;
        border: 2px solid #e2e2e2;
        margin-right: 20px;
        text-align: center;
        font-size:14px;
        color: #333333;
        cursor: pointer;
        position: relative;
        user-select: none;
        &.border, &:hover{
            border: 2px solid #ff6600;
        }
        img{
            vertical-align: middle;
            margin-right: 3px;
        }
        i{
            width: 0;
            height: 27px;
            border-left:13px solid  transparent;
            border-right:13px solid  #ff6600;
            border-top:13px solid  transparent;
            border-bottom:13px solid  #ff6600;
            color: #fff2ea;
            position: absolute;
            right: 0;
            bottom: 0;
            text-align: right;
            line-height: 13px;
            padding-right: 1px;
        }
    }
}
</style>