<template>
  <div>
    <!-- <router-view></router-view> -->
    <keep-alive>
      <router-view v-if="$route.meta.keepAlive"></router-view>
    </keep-alive>
    <router-view
      v-if="$route.meta.keepAlive === undefined || !$route.meta.keepAlive"
    ></router-view>
  </div>
</template>

<script>
import { isWeiXin } from '@/utils/index'
export default {
  created () {
    // 刷新页面时把config置空，保证系统配置信息的时效性
    window.addEventListener('beforeunload', () => {
      this.$store.state.config = ''
      localStorage.setItem('vuex', JSON.stringify(this.$store.state))
    })
    this.$store.commit('setPlatform', isWeiXin() === true ? 'wechat' : 'mobile')
  }
}
</script>

<style lang="scss"></style>
