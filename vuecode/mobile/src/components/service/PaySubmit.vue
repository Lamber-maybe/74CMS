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
      if (data.payment == 'alipay' && !this.$store.state.config.account_alipay_appid) {
        this.$dialog.alert({
          message: '暂不支持支付宝付款，请选择其他付款方式'
        }).then(() => {
          // on close
        })
        return false
      }
      if (data.payment == 'wxpay' && !this.$store.state.config.payment_wechat_appid) {
        this.$dialog.alert({
          message: '暂不支持微信付款，请选择其他付款方式'
        }).then(() => {
          // on close
        })
        return false
      }
      localStorage.setItem('payUrl',url)
      localStorage.setItem('payPayment',data.payment)
      localStorage.setItem('paySuccessUrl',this.successUrl)
      localStorage.setItem('payData',JSON.stringify(data))
      this.$router.push('/member/order/addpay')
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
