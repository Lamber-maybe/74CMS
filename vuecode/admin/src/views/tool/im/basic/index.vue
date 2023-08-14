<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="基本配置" :lazy="true">
        <toolimbasic />
      </el-tab-pane>
      <el-tab-pane label="个人配置" :lazy="true">
        <toolimper />
      </el-tab-pane>
      <el-tab-pane label="企业配置" :lazy="true">
        <toolimcom />
      </el-tab-pane>
      <el-tab-pane label="未读消息提醒" :lazy="true">
        <toolimunread />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import toolimbasic from './components/basic.vue'
import toolimper from './components/per.vue'
import toolimcom from './components/com.vue'
import toolimunread from './components/unread.vue'
export default {
  components: {
    toolimbasic,
    toolimper,
    toolimcom,
    toolimunread
  }
}
</script>
