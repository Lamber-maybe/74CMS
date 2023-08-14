<template>
  <div id="app">
    <div class="box_pay_pop">
      <div class="pay_num"><span class="for">￥</span>{{ amount }}</div>
      <van-radio-group v-model="payment">
        <div class="pay_item wx" @click="choosePayment('wxpay')">
          微信支付
          <van-radio
            class="self_check"
            name="wxpay"
            icon-size="18px"
          ></van-radio>
        </div>
        <div class="pay_item ali" @click="choosePayment('alipay')" v-if="$store.state.showAlipay">
          支付宝支付
          <van-radio
            class="self_check"
            name="alipay"
            icon-size="18px"
          ></van-radio>
        </div>
      </van-radio-group>
      <div class="pay_btn_wrapper">
        <van-button type="info" round block @click="submit"
          >立即支付</van-button
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PopupPayment',
  props: ['amount'],
  data () {
    return {
      payment: 'wxpay'
    }
  },
  mounted () {},
  methods: {
    submit () {
      this.$emit('handlerSubmit', this.payment)
    },
    choosePayment (payment) {
      this.payment = payment
    }
  }
}
</script>

<style lang="scss" scoped>
.box_pay_pop {
  .pay_btn_wrapper {
    padding: 30px 37px;
  }
  .pay_item {
    &.wx {
      background: url("../../assets/images/wx_pay_ico.svg") 3px center no-repeat;
      background-size: 18px;
    }
    &.ali {
      background: url("../../assets/images/ali_pay_ico.svg") 3px center
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
  .pay_num {
    .for {
      font-size: 20px;
    }
    padding: 35px 0 25px;
    text-align: center;
    color: #ff6600;
    font-size: 27px;
  }
}
</style>
