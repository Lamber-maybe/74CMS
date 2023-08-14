<template>
  <div id="app">
    <div class="share_wrapper">
      <div class="share_item_box">
        <div class="share_item forward" @click="handleForward">转发给朋友</div>
        <div class="share_item poster" @click="handlePoster">生成海报</div>
      </div>
      <div class="share_bottom" @click="cancelShare">取消分享</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Share',
  data () {
    return {}
  },
  methods: {
    // 转发给朋友
    handleForward () {
      this.cancelShare()
      this.$emit('handleForward')
    },
    // 生成海报
    handlePoster () {
      this.cancelShare()
      this.$emit('handlePoster')
    },
    // 取消分享
    cancelShare () {
      this.$emit('cancelShare')
    }
  }
}
</script>

<style lang="scss" scoped>
  .share_wrapper {
    .share_item_box {
      display: flex;
      .share_item {
        flex: 1;text-align: center;font-size: 14px;padding: 90px 0 18px;
        &.forward {background: url("../../assets/images/share/share_forward_ico.png") center 20px no-repeat;background-size: 60px;}
        &.poster {background: url("../../assets/images/share/share_poster_ico.png") center 20px no-repeat;background-size: 60px;}
      }
    }
    width:100%;background-color: #fff;
    .share_bottom {font-size: 14px;color: #333;text-align: center;padding: 14px 0;background-color: #f0f0f0;}
  }
</style>
