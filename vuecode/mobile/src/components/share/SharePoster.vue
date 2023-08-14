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
import http from '@/utils/http'
import api from '@/api'
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
      currentTplIndex: 0,
      indexlist: []
    }
  },
  created () {
    // 请求列表数据
    this.fetchData()
  },
  methods: {
    fetchData () {
      this.id = this.$route.params.id
      http
        .get(api.poster_tplindex_list, { type: this.type == 'job' ? 1 : (this.type == 'resume' ? 2 : 3) })
        .then(res => {
          this.indexlist = res.data
          this.currentTplIndex = this.indexlist[0]
        })
        .catch(() => {})
    },
    changeTpl () {
      const c_index = this.indexlist.indexOf(this.currentTplIndex)
      let next_index = c_index + 1
      if (this.indexlist[next_index] === undefined) {
        next_index = 0
      }
      this.currentTplIndex = this.indexlist[next_index]
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
