<template>
  <div>
    <div class="poster_content" ref="imageDom" v-if="makePicDone===false">
      <div class="box_1">—{{$store.state.config.sitename}}—</div>
      <div class="box_2"><img :src="info.logo"></div>
      <div class="box_3 substring">{{info.companyname}}</div>
      <div class="box_4">正在招聘</div>
      <div class="box_5 substring">{{info.jobname}}</div>
      <div class="box_6">{{info.wage}}/月</div>
      <div class="box_7">任职要求</div>
      <div class="box_8 substring">{{info.district}} · {{info.education}} · {{info.experience}}</div>
      <div class="box_9">福利待遇</div>
      <div class="box_10">
        <div class="tag_item" v-for="(item,index) in info.tag_arr" :key="index">{{item}}</div>
      </div>
      <div class="box_11">
        <div class="qr_box"><img :src="qrcode"></div>
        <div class="qr_text">长按查看职位信息</div>
      </div>
    </div>
    <div class="final-pic" v-else>
      <img :src="imgUrl" />
    </div>
  </div>
</template>

<script>
import api from '@/api'
import {generatePicture} from '@/utils/index'
export default {
  name: 'ShareJob',
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
    this.qrcode = window.global.RequestBaseUrl + api.get_qrcode + '?alias=subscribe_job&url=' + location.href + '&jobid=' + this.info.id
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
    width: 330px;background: #fff url("../../assets/images/share/poster_job_bg.png") center 0 no-repeat;
    background-size: 330px 175px;padding-bottom: 80px;position: relative;
    // &.pic{
    //   font-size:0;
    // }
    .box_1 {
      font-size: 12px;color: rgba(255,255,255,0.4);text-align: center; padding: 9px 0;
    }
    .box_2 {
      width: 55px;height: 55px;margin: 0 auto 9px;
      img {width: 55px;height: 55px;border: 0;border-radius: 4px;}
    }
    .box_3 {font-size: 17px;font-weight: bold;color: #fff;text-align: center;padding: 0 15px;margin-bottom: 7px;}
    .box_4 {font-size: 14px;color: #fff;text-align: center;margin-bottom: 32px;}
    .box_5 {font-size: 18px;font-weight: bold;color: #333;text-align: center;padding: 0 15px;margin-bottom: 4px;}
    .box_6 {font-size: 15px;font-weight: bold;color: #ff6600;text-align: center;margin-bottom: 18px;}
    .box_7 {font-size: 14px;color: #333;text-align: center;margin-bottom: 4px;}
    .box_8 {font-size: 14px;text-align: center;padding: 0 15px;margin-bottom: 38px;}
    .box_9 {font-size: 14px;color: #333;padding-left: 18px;margin-bottom: 10px;}
    .box_10 {
      width: 265px;padding-left: 18px;overflow: auto;
      .tag_item {
        float: left;margin-bottom: 7px;font-size: 13px;color: #005ffe;border: 1px solid #005ffe;padding: 2px 11px;
        border-radius: 24px;
        &:not(:last-child){margin-right: 5px;}
      }
    }
    .box_11 {
      position: absolute;bottom: 10px; right: 17px;
      .qr_box {
        width: 65px;height: 65px;margin: 0 auto 5px;
        img {width: 65px;height: 65px;border: 0;}
      }
      .qr_text {font-size: 12px;color: #999;}
    }
  }
  .final-pic{
    font-size:0;
    img{width:330px;}
  }
</style>
