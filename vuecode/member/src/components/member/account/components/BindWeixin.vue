<template>
  <div class="wx_img">
    <div>
      <img v-if="scanQrcodeImg" :src="scanQrcodeImg" width="175" height="175"/>
    </div>
    <p>
      使用微信扫一扫，按提示快速绑定
    </p>
  </div>
</template>


<script>
  import http from '@/utils/http'
  import api from '@/api'

  export default {
    data () {
      return {
        scanQrcodeImg: '',
      }
    },
    mounted () {
      this.initData()
    },
    methods:{
      initData(){
        http.get(api.get_qrcode,{type:'bind_weixin'}).then(res=>{
          this.scanQrcodeImg = res.data
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
  .wx_img{
    text-align: center;
  }
  .wx_img>div{
  }
  .wx_img p{
    margin-top: 22px;
    font-size: 14px;
    color: #030303;
  }
</style>
