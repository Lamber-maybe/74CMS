<template>
    <div>
    </div>
</template>

<script>
export default {
  name: 'JsapiPay',
  data () {
    return {
      jsApiParameters: {},
      successUrl: ''
    }
  },
  created () {
    this.jsApiParameters = {
      appId: this.$route.query.appId,
      timeStamp: this.$route.query.timeStamp,
      nonceStr: this.$route.query.nonceStr,
      package: this.$route.query.package,
      signType: this.$route.query.signType,
      paySign: this.$route.query.paySign
    }
    this.successUrl = this.$route.query.successUrl
    this.callpay()
  },
  methods: {
    jsApiCall () {
      let that = this
      window.WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        that.jsApiParameters,
        function (res) {
          // alert(res.err_code + res.err_desc + res.err_msg)
          that.$router.replace(that.successUrl)
          // window.WeixinJSBridge.log(res.err_msg)
        }
      )
    },
    callpay () {
      let that = this
      if (typeof window.WeixinJSBridge == 'undefined') {
        if (document.addEventListener) {
          document.addEventListener('WeixinJSBridgeReady', that.jsApiCall, false)
        } else if (document.attachEvent) {
          document.attachEvent('WeixinJSBridgeReady', that.jsApiCall)
          document.attachEvent('onWeixinJSBridgeReady', that.jsApiCall)
        }
      } else {
        that.jsApiCall()
      }
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
