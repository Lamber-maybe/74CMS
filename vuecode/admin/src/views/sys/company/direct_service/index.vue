<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="基本设置" :lazy="true">
        <config-basic />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import configBasic from './components/basic.vue'
export default {
  components: {
    configBasic
  }
}
</script>
