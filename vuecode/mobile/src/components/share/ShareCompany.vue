<template>
  <div>
    <div class="poster_content" ref="imageDom" v-if="makePicDone===false">
      <div class="box_1">
        <img :src="info.logo" class="logo">
        <div class="name substring">{{info.companyname}}</div>
        <div class="info substring">{{info.nature}} | {{info.scale}} | {{info.district}}</div>
      </div>
      <div class="box_2">共{{info.jobnum}}个在招职位</div>
      <div class="box_3"><img :src="qrcode" alt="qrcode"></div>
      <div class="box_4">长按二维码查看在招职位</div>
      <div class="box_5">本地找工作，就上{{$store.state.config.sitename}}</div>
    </div>
    <div class="final-pic" v-else>
      <img :src="imgUrl"/>
    </div>
  </div>
</template>

<script>
import api from '@/api'
import {generatePicture} from '@/utils/index'
export default {
  name: 'ShareCompany',
  props: ['info'],
  data () {
    return {
      makePicDone: false,
      imgUrl: '',
      qrcode: ''
    }
  },
  mounted () {
    let that = this
    this.qrcode = window.global.RequestBaseUrl + api.get_qrcode + '?alias=subscribe_company&url=' + location.href + '&comid=' + this.info.id
    window.pageYOffset = 0
    document.documentElement.scrollTop = 0
    document.body.scrollTop = 0
    setTimeout(() => {
      generatePicture(that.$refs.imageDom).then(url => {
        that.imgUrl = url
        that.makePicDone = true
      })
    }, 500)
  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>
  .poster_content {
    width: 330px;background: #fff url("../../assets/images/share/poster_company_bg.png") center 0 no-repeat;
    background-size: 330px 140px;
    .box_1 {
      position: relative;padding: 30px 5px 100px 85px;
      .logo {position: absolute;left: 20px;top: 28px;width: 55px;height: 55px;border-radius: 7px;border: 0;}
      .name {font-size: 17px;font-weight: bold;color: #fff;margin-bottom: 6px;}
      .info {font-size: 14px;color: #fff;}
    }
    .box_2 {
      position: relative;width: 127px;margin: 0 auto 48px;font-size: 14px;color: #0070fd;padding: 4px 0;
      border: 1px solid #0070fd;border-radius: 30px;text-align: center;
      &::before {content: '';position: absolute;left: -75px;top: 50%;transform: translate(0,-50%);width: 65px;
        height: 1px;z-index: 1;background-color: #f3f3f3;}
      &::after {content: '';position: absolute;right: -75px;top: 50%;transform: translate(0,-50%);width: 65px;
        height: 1px;z-index: 1;background-color: #f3f3f3;}
    }
    .box_3 {
      width: 110px;height: 110px;margin: 0 auto 10px;
      img {width: 110px;height: 110px;border: 0;}
    }
    .box_4 {font-size: 13px;color: #999;text-align: center;margin-bottom: 53px;}
    .box_5 {font-size: 14px;color: rgba(255,255,255,0.4);text-align: center;background-color: #005ffe;padding: 5px 0;}
  }
  .final-pic{
    font-size:0;
    img{width:330px;}
  }
</style>
