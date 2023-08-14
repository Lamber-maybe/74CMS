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
      code: ''
    }
  },
  created () {

  },
  methods: {
    handlerSubmit (url, data, callback) {
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
                if (that.$route.path === that.successUrl) {
                  location.reload()
                } else {
                  that.$router.push(that.successUrl)
                }
              }, 1500)
            }
            return false
          } else {
            this.handlerPay(res.data, callback)
          }
        })
        .catch(() => {
          setTimeout(() => {
            if (that.$route.path === that.successUrl) {
              location.reload()
            } else {
              that.$router.push(that.successUrl)
            }
          }, 1500)
        })
    },
    handlerPay (data, callback) {
      if (this.payment == 'wxpay') {
        if (isWeiXin()) {
          let locationUrl = this.$store.state.config.mobile_domain + 'pay/jsapi?appId=' + data.parameter.jsApiParameters.appId + '&timeStamp=' + data.parameter.jsApiParameters.timeStamp + '&nonceStr=' + data.parameter.jsApiParameters.nonceStr + '&package=' + data.parameter.jsApiParameters.package + '&signType=' + data.parameter.jsApiParameters.signType + '&paySign=' + data.parameter.jsApiParameters.paySign + '&successUrl=' + this.successUrl
          window.location.href = locationUrl
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
