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
  name: 'OauthWeixin',
  data () {
    return {
      code:''
    }
  },
  created () {
    this.init()
  },
  methods: {
      doLogin(user){
        http
            .post(api.oauth_weixin_login, user)
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
      getUserinfo(code){
        let postData = {
          code
        }
        http
            .post(api.oauth_weixin_getuserinfo, postData)
            .then(res => {
              this.doLogin(res.data)
            })
            .catch(() => {})
      },
    init () {
      let code = this.$route.params.code
      if(code!==undefined){
        this.getUserinfo(code)
        return false
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