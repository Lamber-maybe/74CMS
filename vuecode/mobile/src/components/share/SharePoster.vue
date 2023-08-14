<template>
  <div id="app">
    <div class="job_popup">
      <div class="job_popup_box">
        <ShareCompany v-if="type === 'company'" :tplIndex="currentTplIndex" :infoid="infoid" @closePoster="closePoster"></ShareCompany>
        <ShareJob v-if="type === 'job'" :tplIndex="currentTplIndex" :infoid="infoid" @closePoster="closePoster"></ShareJob>
        <ShareResume v-if="type === 'resume'" :tplIndex="currentTplIndex" :infoid="infoid" @closePoster="closePoster"></ShareResume>
        <div class="job_btns">
          <div class="job_btn_1" @click="changeTpl">换一个</div>
          <div class="job_btn_2" @click="longTap">长按保存图片</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ShareCompany from './ShareCompany'
import ShareJob from './ShareJob'
import ShareResume from './ShareResume'
export default {
  name: 'SharePoster',
  props: ['type', 'infoid'],
  components: {
    ShareCompany,
    ShareJob,
    ShareResume
  },
  data () {
    return {
      currentTplIndex: 0
    }
  },
  methods: {
    changeTpl () {
      this.currentTplIndex++
      if (this.currentTplIndex >= 3) {
        this.currentTplIndex = 0
      }
    },
    // 关闭海报
    closePoster () {
      this.$emit('closePoster')
    },
    longTap () {
      this.$toast('请长按海报图片保存到相册')
    }
  }
}
</script>

<style lang="scss" scoped>
.job_popup_box{
  position: fixed;
  z-index:4;
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
    background-color: #00d77e;
  }
</style>
