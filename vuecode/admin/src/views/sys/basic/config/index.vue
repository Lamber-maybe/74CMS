<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="网站配置" :lazy="true">
        <sysconfigwebsite />
      </el-tab-pane>
      <el-tab-pane label="注册设置" :lazy="true">
        <sysconfigreg />
      </el-tab-pane>
      <el-tab-pane label="电子地图" :lazy="true">
        <sysconfigmap />
      </el-tab-pane>
      <el-tab-pane label="文件上传" :lazy="true">
        <sysconfigupload />
      </el-tab-pane>
      <el-tab-pane label="底部引导条" :lazy="true">
        <sysconfigguide />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import sysconfigwebsite from './components/website.vue'
import sysconfigreg from './components/reg.vue'
import sysconfigmap from './components/map.vue'
import sysconfigupload from './components/upload.vue'
import sysconfigguide from './components/guide.vue'
export default {
  components: {
    sysconfigwebsite,
    sysconfigreg,
    sysconfigmap,
    sysconfigupload,
    sysconfigguide
  }
}
</script>
