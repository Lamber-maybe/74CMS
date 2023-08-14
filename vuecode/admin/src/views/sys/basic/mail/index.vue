<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="参数配置" :lazy="true">
        <mailbase />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import mailbase from './components/base.vue'
export default {
  components: {
    mailbase
  }
}
</script>

