<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="qq互联" :lazy="true">
        <qq />
      </el-tab-pane>
      <el-tab-pane label="腾讯rtc" :lazy="true">
        <trtc />
      </el-tab-pane>
      <el-tab-pane label="微信开放平台" :lazy="true">
        <weixinopen />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import qq from './components/qq.vue'
import trtc from './components/trtc.vue'
import weixinopen from './components/weixinopen.vue'
export default {
  components: {
    qq,
    trtc,
    weixinopen
  }
}
</script>
