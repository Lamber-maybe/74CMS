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
      <el-tab-pane label="号码隐私保护" :lazy="true">
        <alicloudpro />
      </el-tab-pane>
      <el-tab-pane label="简历解析" :lazy="true">
        <resumeanalysis />
      </el-tab-pane>
      <el-tab-pane label="在线文档预览" :lazy="true">
        <zhitooview />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import qq from './components/qq.vue'
import trtc from './components/trtc.vue'
import weixinopen from './components/weixinopen.vue'
import alicloudpro from './components/alicloudpro'
import resumeanalysis from './components/resumeanalysis.vue'
import zhitooview from '@/views/sys/basic/cooperation/components/zhitooview'
export default {
  components: {
    qq,
    trtc,
    weixinopen,
    alicloudpro,
    resumeanalysis,
    zhitooview
  }
}
</script>
