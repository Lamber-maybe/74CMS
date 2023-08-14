<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="简历置顶" :lazy="true">
        <resume_stick />
      </el-tab-pane>
      <el-tab-pane label="简历标签" :lazy="true">
        <resume_tag />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import resume_stick from './components/resume_stick.vue'
import resume_tag from './components/resume_tag.vue'
export default {
  components: {
    resume_stick,
    resume_tag
  }
}
</script>
