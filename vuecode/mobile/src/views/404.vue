<template>
  <div id="app">
    <van-empty
      class="custom-image"
      :image="require('@/assets/images/404.png')"
      :description="message"
      ><van-button
        round
        type="info"
        class="bottom-button"
        @click="$router.push('/')"
      >
        返回首页
      </van-button>
    </van-empty>
  </div>
</template>

<script>
export default {
  name: 'Page404',
  computed: {
    message () {
      return '该链接地址不存在'
    }
  }
}
</script>

<style lang="scss" scoped>
.custom-image {
  margin-top: 100px;
}
.bottom-button {
  width: 260px;
  height: 40px;
}
</style>
