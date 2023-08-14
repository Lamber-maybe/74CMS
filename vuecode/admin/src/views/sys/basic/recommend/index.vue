<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="推荐职位" :lazy="true">
        <clist listtype="job" />
      </el-tab-pane>
      <el-tab-pane label="推荐简历" :lazy="true">
        <clist listtype="resume" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import clist from './components/list.vue'
export default {
  components: {
    clist
  }
}
</script>
