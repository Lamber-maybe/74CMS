<template>
  <div id="app">
    <div class="job_popup">
      <div class="job_popup_box">
        <div class="img" v-if="makePicDone==false">
          <van-overlay z-index="11" :show="makePicDone==false"><van-loading color="#1989fa" class="loading" >加载中...</van-loading></van-overlay>
        </div>
        <img v-if="makePicDone==true" src="../../../assets/images/share/new/close_ico.png" alt="" class="close" @click="closePoster"/>
        <img v-if="makePicDone==true" :src="src" class="img"/>
        <div class="job_btns">
          <div class="job_btn_2" @click="longTap">长按保存图片</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'

export default {
  name: 'SharePoster',
  props: ['id', 'type'],
  data () {
    return {
      makePicDone: false,
      src: ''
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    // 关闭海报
    closePoster () {
      this.$emit('closeShare')
    },
    longTap () {
      this.$toast('请长按海报图片保存到相册')
    },
    fetchData () {
      http
        .get(api.create_poster, {id: this.id, type: 'shortvideo', vtype: this.type})
        .then((res) => {
          this.src = res.data
          this.makePicDone = true
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.job_popup_box{
  position: fixed;
  z-index:10;
  left: 50%;
  top: 50%;
  transform: translate(-50%,-50%);
}
  .job_popup_box .job_btns {
    display: flex;
    justify-content: space-between;
    margin-top:18.5px;
  }

  .job_popup_box .job_btns .job_btn_1,
  .job_popup_box .job_btns .job_btn_2 {
    width: 145.5px;
    height: 41px;
    text-align: center;
    line-height: 41px;
    color: #fff;
    font-size:14px;
    border-radius: 5px;
  }

  .job_popup_box .job_btns .job_btn_1 {
    background-color: #1787fb;
  }

  .job_popup_box .job_btns .job_btn_2 {
    width:321px;
    background-color: #00d77e;
  }
.close{
  width: 0.8rem;
  height: 0.8rem;
  position: absolute;
  right: -0.3rem;
  top: -0.3rem;
  z-index: 55;
}
.img {
  width:321px;
  height:536px;
  .loading{
    text-align: center;
    top: 50%;
  }
}
</style>
