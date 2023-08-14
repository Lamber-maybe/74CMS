<template>
    <div>
    </div>
</template>

<script>

export default {
  name: 'PaySubmit',
  props: ['payment', 'successUrl'],
  data () {
    return {
      code: ''
    }
  },
  created () {

  },
  methods: {
    handlerSubmit (url, data) {
      //  payType 支付类型
      var payType = ''
      // data字段支付类型存在两个字段 payment pay_type 需要判断那个有值 在调用this.$refs.paySubmit.handlerSubmit此方法是传值字段未统一
      if (data.payment == 'alipay' || data.payment == 'wxpay' || data.payment == 'free' || data.payment == 'points') { // 积分支付报错修改 2022.07.11
        payType = data.payment
      } else if (data.pay_type == 'alipay' || data.pay_type == 'wxpay' || data.pay_type == 'free' || data.pay_type == 'points') { // 积分支付报错修改 2022.07.11
        payType = data.pay_type
      } else {
        this.$dialog.alert({
          message: '请选择付款方式'
        }).then(() => {
          // on close
        })
        return false
      }
      if (payType == 'alipay' && !this.$store.state.config.account_alipay_appid) {
        this.$dialog.alert({
          message: '暂不支持支付宝付款，请选择其他付款方式'
        }).then(() => {
          // on close
        })
        return false
      }
      if (payType == 'wxpay' && !this.$store.state.config.payment_wechat_appid) {
        this.$dialog.alert({
          message: '暂不支持微信付款，请选择其他付款方式'
        }).then(() => {
          // on close
        })
        return false
      }
      localStorage.setItem('payUrl', url)
      localStorage.setItem('payPayment', payType)
      localStorage.setItem('paySuccessUrl', this.successUrl)
      localStorage.setItem('payData', JSON.stringify(data))
      this.$router.push('/member/order/addpay')
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
