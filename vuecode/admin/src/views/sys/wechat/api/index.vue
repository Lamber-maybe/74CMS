<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="微信对接配置" :lazy="true">
        <config-api />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import configApi from './components/api.vue'
export default {
  components: {
    configApi
  }
}
</script>
