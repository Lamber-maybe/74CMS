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
          // this.jsApiParameters = data.parameter.jsApiParameters
          this.$router.push({name: 'JsapiPay', params: {jsApiParameters: data.parameter.jsApiParameters, successUrl: this.successUrl}})
          // this.callpay()
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
