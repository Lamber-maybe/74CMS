<template>
    <div>
    </div>
</template>

<script>
// 调用微信JS api 支付
import { isWeiXin } from '@/utils/index'
import http from '@/utils/http'

export default {
  name: 'PaySubmit',
  props: ['payment', 'successUrl'],
  data () {
    return {
      code: '',
      jsApiParameters: {}
    }
  },
  created () {

  },
  methods: {
    jsApiCall () {
      let that = this
      window.WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        that.jsApiParameters,
        function (res) {
          // alert(res.err_code + res.err_desc + res.err_msg)
          that.$router.push(that.successUrl)
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
    },
    handlerSubmit (url, data, callback) {
      let that = this
      data.openid = localStorage.getItem('weixinOpenid')
      http
        .post(url, data)
        .then(res => {
          if (res.data.pay_status == 1) {
            this.$notify({ type: 'success', message: '支付成功' })
            if (typeof callback === 'function') {
              callback()
              return false
            } else {
              setTimeout(() => {
                that.$router.push(that.successUrl)
              }, 1500)
            }
            return false
          } else {
            this.handlerPay(res.data, callback)
          }
        })
        .catch(() => {})
    },
    handlerPay (data, callback) {
      if (this.payment == 'wxpay') {
        if (isWeiXin()) {
          this.jsApiParameters = data.parameter.jsApiParameters
          this.callpay()
        } else {
          window.location.href = data.parameter
        }
      } else {
        window.location.href = data.parameter
      }
      if (typeof callback === 'function') {
        callback()
        return false
      }
    }

  }
}
</script>
<style lang="scss" scoped>
</style>
