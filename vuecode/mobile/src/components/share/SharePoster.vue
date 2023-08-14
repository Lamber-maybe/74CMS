<template>
  <div id="app">
    <div class="job_popup">
      <div class="job_popup_box">
        <ShareCompany v-if="type === 'company'" :info="info" :tpl="tplArr[currentTplIndex]" @closePoster="closePoster"></ShareCompany>
        <ShareJob v-if="type === 'job'" :info="info" :tpl="tplArr[currentTplIndex]" @closePoster="closePoster"></ShareJob>
        <ShareResume v-if="type === 'resume'" :info="info" :tpl="tplArr[currentTplIndex]" @closePoster="closePoster"></ShareResume>
        <div class="job_btns">
          <div class="job_btn_1" @click="changeTpl">换一组</div>
          <div class="job_btn_2">长按保存图片</div>
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
  props: ['type', 'info'],
  components: {
    ShareCompany,
    ShareJob,
    ShareResume
  },
  data () {
    return {
      currentTplIndex: 0,
      tplArr: ['one', 'two', 'three']
    }
  },
  methods: {
    changeTpl () {
      this.currentTplIndex++
      if (this.tplArr[this.currentTplIndex] === undefined) {
        this.currentTplIndex = 0
      }
    },
    // 关闭海报
    closePoster () {
      this.$emit('closePoster')
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
