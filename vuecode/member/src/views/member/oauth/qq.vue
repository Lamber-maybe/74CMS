<template>
  <div class="content">
    <div class="loading-ico">
      <img src="../../../assets/images/oauth_loading.gif"/>
    </div>
    <div class="loading-txt">正在获取第三方授权...</div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'OauthQq',
  data () {
    return {
      type:'login'
    }
  },
  created () {
    this.type = this.$route.params.type
    this.init()
  },
  methods: {
      doLogin(user){
        http
            .post(api.oauth_qq_login, user)
            .then(res => {
              if (parseInt(res.code) === 200) {
                this.$store.commit('clearCountDownFun')
                this.$store.commit('setLoginState', {
                  whether: true,
                  utype: res.data.utype,
                  token: res.data.token,
                  mobile: res.data.mobile,
                  userIminfo: res.data.user_iminfo
                })
                if (res.data.utype === 1) {
                  this.$router.push('/company')
                } else {
                  this.$router.push('/personal')
                }
              } else {
                this.$message.error(res.message)
              }
            })
            .catch(() => {})
      },
      doBind(user){
        http
            .post(api.oauth_qq_bind, user)
            .then(res => {
              if (parseInt(res.code) === 200) {
                if (this.$store.state.LoginType === 1) {
                  this.$router.push('/company')
                } else {
                  this.$router.push('/personal')
                }
              } else {
                this.$message.error(res.message)
              }
            })
            .catch(() => {})
      },
      getUserinfo(accessToken){
        let postData = {
          accessToken
        }
        http
            .post(api.oauth_qq_getuserinfo, postData)
            .then(res => {
              if(this.type=='login'){
                this.doLogin(res.data)
              }else if(this.type=='bind'){
                this.doBind(res.data)
              }
              
            })
            .catch(() => {})
      },
    init () {
      if (window.location.hash.length === 0) {
        let redirect_uri = location.href
        let url =
          'https://graph.qq.com/oauth2.0/authorize?response_type=token&client_id=' +
          this.$store.state.config.account_qqlogin_appid +
          '&redirect_uri=' +
          redirect_uri
        window.location.href = url
      } else {
        let accessTokenArr = this.$route.hash.substring(1)
        if(accessTokenArr!==undefined && accessTokenArr!=''){
          let tmp1 = accessTokenArr.split('&')
          let tmp2 = tmp1[0].split('=')
          let accessToken = tmp2[1]
          this.getUserinfo(accessToken)
          return false
        }
      }
    }
  }
}
</script>
<style scoped lang="scss">
.content{
  margin-top:16px;
  padding-top:180px;
  height:650px;
  background-color:#fff;
  text-align:center;
  .loading-ico{
    img{
      width:170px;
      height:170px;
    }
  }
  .loading-txt{
    color:#999;
  }
}
</style>
