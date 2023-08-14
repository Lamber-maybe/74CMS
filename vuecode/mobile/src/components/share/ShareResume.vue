<template>
  <div id="app">
    <div class="poster_content" ref="imageDom" v-if="makePicDone===false">
      <div class="box_1">
        <div class="box_up">
          <img :src="info.photo" class="avatar">
          <div class="name">{{info.fullname}}</div>
          <div class="info substring">{{info.age}}岁·{{info.education}}·{{info.experience}}</div>
        </div>
        <div class="box_down">
          <div class="int_line substring">我想找&nbsp;&nbsp;<span class="black">{{info.intention_jobs}}</span>&nbsp;&nbsp;工作</div>
          <div class="int_line substring">我想在&nbsp;&nbsp;<span class="black">{{info.intention_district}}</span>&nbsp;&nbsp;工作</div>
          <div class="int_line substring">求职状态：&nbsp;&nbsp;<span class="black">{{info.current}}</span>&nbsp;&nbsp;</div>
        </div>
      </div>
      <div class="box_2">
        <div class="site_name">{{$store.state.config.sitename}}</div>
        <div class="site_des">关注“{{$store.state.config.wechat_name}}”公众号，免费登记简历信息，找本地靠谱好工作！</div>
        <div class="box_qr">
          <div class="qr_box"><img :src="qrcode"></div>
          <div class="qr_text">长按查看简历信息</div>
        </div>
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
  name: 'ShareResume',
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
    this.qrcode = window.global.RequestBaseUrl + api.get_qrcode + '?alias=subscribe_resume&url=' + location.href + '&resumeid=' + this.info.id
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
    width: 330px;background: #fff url("../../assets/images/share/poster_resume_bg.png") center 0 no-repeat;
    background-size: 330px 140px;padding-top:30px;
    .box_1 {
      width: 340px;margin: 0 auto 27px;border-radius: 4px;background-color: #fff;box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.1);
      .box_up {
        position: relative;padding: 29px 0 30px 86px;
        .avatar {position: absolute;left: 21px;top: 26px;width: 55px;height: 55px;border-radius: 55px;}
        .name {font-size: 17px;font-weight: bold;color: #333;margin-bottom: 7px;}
        .info {font-size: 14px;padding-right: 15px;}
      }
      .box_down {
        border-top: 1px dashed #ededed;padding: 16px 0 20px 25px;
        .int_line {
          padding-right: 25px;font-size: 14px;color: #999;
          .black {color: #333;}
          &:not(:last-child) {margin-bottom: 7px;}
        }
      }
    }
    .box_2 {
      position: relative;background-color: #f3f3f3;padding: 30px 0 40px 18px;
      .site_name {font-size: 16px;font-weight: bold;color: #333;margin-bottom: 10px;}
      .site_des {font-size: 13px;color: #999;line-height: 1.7;word-break: break-all;width: 63%;}
      .box_qr {
        position: absolute;bottom: 10px; right: 17px;
        .qr_box {
          width: 85px;height: 85px;margin: 0 auto 5px;padding: 5px;background-color: #fff;
          img {width: 75px;height: 75px;border: 0;}
        }
        .qr_text {font-size: 12px;color: #999;}
      }
    }
  }
  .final-pic{
    font-size:0;
    img{width:330px;}
  }
</style>
