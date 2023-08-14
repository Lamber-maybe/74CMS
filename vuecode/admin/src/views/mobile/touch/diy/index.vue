<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="模块设计" :lazy="true">
        <mobilemodule />
      </el-tab-pane>
      <el-tab-pane label="菜单设置" :lazy="true">
        <mobilemenu />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import mobilemodule from './module.vue'
import mobilemenu from './menu.vue'
export default {
  components: {
    mobilemodule,
    mobilemenu
  }
}
</script>
