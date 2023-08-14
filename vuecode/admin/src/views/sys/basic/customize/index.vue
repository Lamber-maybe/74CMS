<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="自定义数据" :lazy="true">
        <custbase />
      </el-tab-pane>
      <el-tab-pane label="热门关键词" :lazy="true">
        <custhotword />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import custbase from './components/base.vue'
import custhotword from './components/hotword.vue'
export default {
  components: {
    custbase,
    custhotword
  }
}
</script>

