<template>
  <div>
    <van-overlay z-index="10" :show="makePicDone===false"><van-loading color="#1989fa" class="loading" >加载中...</van-loading></van-overlay>
    <div class="final-pic">
      <img v-if="makePicDone===true" src="../../assets/images/share/new/close_ico.png" alt="" class="close" @click="$emit('closePoster')">
      <img v-if="makePicDone===true" class="final-img" :src="imgUrl"/>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'ShareJob',
  props: ['tplIndex', 'infoid'],
  data () {
    return {
      makePicDone: false,
      imgUrl: ''
    }
  },
  watch: {
    tplIndex: function (newVal, oldVal) {
      this.makePicDone = false
      this.makePic()
    }
  },
  mounted () {
    this.makePic()
  },
  methods: {
    makePic () {
      if (parseInt(this.tplIndex) <= 0) {
        return
      }
      http
        .get(api.create_poster, { type: 'job', id: this.infoid, index: this.tplIndex })
        .then(res => {
          this.imgUrl = res.data + '?_=' + Math.random()
          this.makePicDone = true
        })
        .catch(() => {})
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
