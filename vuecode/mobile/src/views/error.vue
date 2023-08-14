<template>
  <div id="app">
    <van-empty
      class="custom-image"
      :image="require('@/assets/images/wh.png')"
      :description="message"
      >
    </van-empty>
  </div>
</template>

<script>
export default {
  name: 'Page404',
  date () {
    return {
      message: ''
    }
  },
  created () {
    this.message = this.$route.query.message
  }
}
</script>

<style lang="scss" scoped>
.custom-image {
  margin-top: 100px;
}
</style>
