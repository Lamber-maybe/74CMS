<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="套餐管理" :lazy="true">
        <setmeallist />
      </el-tab-pane>
      <el-tab-pane label="开通记录" :lazy="true">
        <openlog />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import setmeallist from './setmeallist.vue'
import openlog from './openlog.vue'
export default {
  components: {
    setmeallist,
    openlog
  }
}
</script>
