<template>
  <div id="app">
    <div class="share_wrapper">
      <div class="share_title">长按保存图片，分享给朋友</div>
      <div class="content">
        <ShareCompany v-if="type === 'company'" :info="info"></ShareCompany>
        <ShareJob v-if="type === 'job'" :info="info"></ShareJob>
        <ShareResume v-if="type === 'resume'" :info="info"></ShareResume>
      </div>
      <div class="share_bottom" @click="closePoster">知道了</div>
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
  methods: {
    // 关闭海报
    closePoster () {
      this.$emit('closePoster')
    }
  }
}
</script>

<style lang="scss" scoped>
  .share_wrapper {
    width: 330px;background-color: #fff;border-radius: 6px;
    .share_title {font-size: 14px;color: #999;padding: 16px 0; text-align: center;}
    .content {}
    .share_bottom {font-size: 14px;color: #333;padding: 16px 0; text-align: center;border-top: 1px solid #f3f3f3;}
  }
</style>
