<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="积分套餐配置" :lazy="true">
        <points_list />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import points_list from './components/points_list.vue'
export default {
  components: {
    points_list
  }
}
</script>
