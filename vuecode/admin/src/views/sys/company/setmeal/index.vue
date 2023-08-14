<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="基本设置" :lazy="true">
        <config-basic />
      </el-tab-pane>
      <el-tab-pane label="套餐管理" :lazy="true">
        <config-setmeallist />
      </el-tab-pane>
      <el-tab-pane label="简历下载点" :lazy="true">
        <config-downresume />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import configBasic from './components/basic.vue'
import configSetmeallist from './components/setmeal.vue'
import configDownresume from './components/downresume.vue'
export default {
  components: {
    configBasic,
    configSetmeallist,
    configDownresume
  }
}
</script>
