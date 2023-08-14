<template>
  <div></div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'OauthWeixin',
  data () {
    return {
      globalConfig:{},
    }
  },
  created () {
    this.config()
  },
  methods: {
    config () {
      let that = this
      http
        .get(api.loginConfig, {})
        .then(res => {
          that.globalConfig = res.data
          this.init()
        })
        .catch(() => {})
    },
    doLogin (code) {
      let that = this
      let data = {code}
      http
          .post(api.weixinlogin, data)
          .then(res => {
            if(res.code==200){
              localStorage.setItem('admintoken',res.data.token)
              that.$toast.success(res.message)
            }else{
              that.$toast.fail(res.message)
            }
            setTimeout(() => {
              location.href = location.href.split("#")[0]
            }, 1500);
          })
          .catch(() => {})
    },
    init () {
      let code = this.$route.query.code
      if (code !== undefined) {
        this.doLogin(code)
        return false
      } else {
        let appid = this.globalConfig.wechat_appid
        let redirect_uri = location.href.split("#")[0]
        redirect_uri = encodeURI(redirect_uri)
        let url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + appid + '&redirect_uri=' + redirect_uri + '&response_type=code&scope=snsapi_userinfo&state=oauth#wechat_redirect'
        window.location.href = url
      }
    }
  }
}
</script>
