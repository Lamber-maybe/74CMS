<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="首页模板" :lazy="true">
        <tplindex />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import tplindex from './components/index.vue'
export default {
  components: {
    tplindex
  }
}
</script>
