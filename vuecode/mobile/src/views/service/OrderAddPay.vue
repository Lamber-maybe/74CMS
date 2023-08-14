<template>
  <div>
  </div>
</template>

<script>
// 调用微信JS api 支付
import { isWeiXin } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'

export default {
  name: 'OrderAddPay',
  data () {
    return {
      url:'',
      payment:'',
      successUrl:'',
      paydata:{},
      code: ''
    }
  },
  computed: {
    config () {
      return this.$store.state.config
    }
  },
  created () {
    this.url = localStorage.getItem('payUrl')
    this.payment = localStorage.getItem('payPayment')
    this.successUrl = localStorage.getItem('paySuccessUrl')
    let payData = localStorage.getItem('payData')
    this.paydata = JSON.parse(payData)
    this.paydata.openid = localStorage.getItem('weixinOpenid')
    let location_href = window.location.href
    let param_str = location_href.split('?')[1]
    if (param_str !== undefined) {
      let cs_arr = param_str.split('&')
      let cs = {}
      for (let i = 0; i < cs_arr.length; i++) {
        cs[cs_arr[i].split('=')[0]] = cs_arr[i].split('=')[1]
      }
      if(cs.code!==undefined){
        this.code = cs.code
      }
    }
    this.handlerSubmit(this.url,this.data)
  },
  methods: {
    handlerSubmit (url, data, callback) {
      let that = this
      //微信环境下选择微信支付，并且没有openid，则需要先获取openid
      if(this.payment == 'wxpay' && isWeiXin() && (this.paydata.openid===undefined || this.paydata.openid=='' || !this.paydata.openid)){
        this.initWeixinPaymentOpenid()
      }else{
        this.doRequest()
      }

    },
    doRequest(){
      let that = this
      http
        .post(that.url, that.paydata)
        .then(res => {
          if (res.data.pay_status == 1) {
            this.$notify({ type: 'success', message: '支付成功' })
            localStorage.removeItem('payUrl')
            localStorage.removeItem('payPayment')
            localStorage.removeItem('paySuccessUrl')
            localStorage.removeItem('payData')
            setTimeout(() => {
              if (that.$route.path === that.successUrl) {
                location.reload()
              } else {
                that.$router.push(that.successUrl)
              }
            }, 1500)
          } else {
            this.handlerPay(res.data)
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
    handlerPay (data) {
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
    },
    initWeixinPaymentOpenid () {
      if (this.code !== '') {
        http.post(api.get_weixin_openid, {code:this.code}).then(res => {
          localStorage.setItem('weixinOpenid', res.data)
          this.paydata.openid = res.data
          this.doRequest()
        }).catch(() => {})
      } else {
        let appid = this.config.payment_wechat_appid
        let redirect_uri = encodeURI(location.href)
        let state = 'STATE'
        let redirect_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + appid + '&redirect_uri=' + redirect_uri + '&response_type=code&scope=snsapi_base&state=' + state + '#wechat_redirect'
        location.href = redirect_url
      }
    }

  }
}
</script>
<style lang="scss" scoped>
</style>
