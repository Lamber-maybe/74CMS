<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="账号设置" :lazy="true">
        <accountSettings></accountSettings>
      </el-tab-pane>
      <el-tab-pane label="客服名片" :lazy="true">
        <customerService></customerService>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import accountSettings from './components/accountSettings.vue'
import customerService from './components/customerService.vue'
export default {
  name: 'Index',
  components: {
    accountSettings,
    customerService
  }
}
</script>

<style scoped>

</style>
