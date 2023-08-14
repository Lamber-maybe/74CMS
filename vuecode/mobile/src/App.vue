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
import http from '@/utils/http'
import api from '@/api'
export default {
  created () {
    // 刷新页面时把config置空，保证系统配置信息的时效性
    window.addEventListener('beforeunload', () => {
      this.$store.state.config = ''
      this.$store.state.sendSmsBtnText = '获取验证码'
      this.$store.state.sendSmsInterval = 60
      localStorage.setItem('vuex', JSON.stringify(this.$store.state))
    })
    this.$store.commit('setPlatform', isWeiXin() === true ? 'wechat' : 'mobile')
    if (this.$route.query.scene_uuid !== undefined) {
      localStorage.setItem('scene_uuid', this.$route.query.scene_uuid)
      http.post(api.scene_record, {scene_uuid: this.$route.query.scene_uuid}).then(() => {}).catch(() => {})
    }
  }
}
</script>

<style lang="scss"></style>
