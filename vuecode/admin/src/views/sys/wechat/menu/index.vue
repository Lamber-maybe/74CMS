<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane
        label="公众号自定义菜单"
        :lazy="true"
      >
        <config-index />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import configIndex from './components/index.vue'
export default {
  components: {
    configIndex
  }
}
</script>
