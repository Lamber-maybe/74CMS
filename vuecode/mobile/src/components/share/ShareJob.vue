<template>
  <div>
    <van-overlay z-index="10" :show="makePicDone===false"><van-loading color="#1989fa" class="loading" >加载中...</van-loading></van-overlay>
    <div ref="imageDom" v-if="makePicDone===false">
      <components :info="info" :qrcode="qrcode" :is="tplName"></components>
    </div>
    <div class="final-pic" v-else>
      <img v-if="makePicDone===true" src="../../assets/images/share/new/close_ico.png" alt="" class="close" @click="$emit('closePoster')">
      <img class="final-img" :src="imgUrl"/>
    </div>
  </div>
</template>

<script>
import api from '@/api'
import {generatePicture} from '@/utils/index'
import one from './job/one'
import two from './job/two'
import three from './job/three'
export default {
  name: 'ShareJob',
  props: ['info', 'tpl'],
  components: {
    one,
    two,
    three
  },
  data () {
    return {
      tplName: 'one',
      makePicDone: false,
      imgUrl: '',
      qrcode: ''
    }
  },
  watch: {
    tpl: function (newVal, oldVal) {
      this.tplName = newVal
      this.makePic()
    }
  },
  mounted () {
    this.makePic()
    this.qrcode = window.global.RequestBaseUrl + api.get_qrcode + '?alias=subscribe_job&url=' + location.href + '&jobid=' + this.info.id
  },
  methods: {
    makePic () {
      let that = this
      that.makePicDone = false
      window.pageYOffset = 0
      document.documentElement.scrollTop = 0
      document.body.scrollTop = 0
      setTimeout(() => {
        generatePicture(that.$refs.imageDom).then(url => {
          that.imgUrl = url
          that.makePicDone = true
        })
      }, 500)
    }
  }
}
</script>

<style lang="scss" scoped>
.close{
  width: 0.8rem;
  height: 0.8rem;
  position: absolute;
  right: -0.3rem;
  top: -0.3rem;
  z-index: 55;
}
.loading{
  text-align: center;
  top: 50%;
}
  .final-pic{
    position:relative;
    font-size:0;
    width:321px;
    height:536px;
    .final-img{
      width:321px;
      height:536px;
    }
  }

</style>
