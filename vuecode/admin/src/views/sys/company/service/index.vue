<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="职位置顶" :lazy="true">
        <job_stick />
      </el-tab-pane>
      <el-tab-pane label="职位紧急" :lazy="true">
        <job_emergency />
      </el-tab-pane>
      <el-tab-pane label="职位智能刷新" :lazy="true">
        <job_refresh_package />
      </el-tab-pane>
      <el-tab-pane label="简历增值包" :lazy="true">
        <resume_package />
      </el-tab-pane>
      <el-tab-pane label="职聊增值包" :lazy="true">
        <im_package />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import job_stick from './components/job_stick.vue'
import job_emergency from './components/job_emergency.vue'
import job_refresh_package from './components/job_refresh_package.vue'
import resume_package from './components/resume_package.vue'
import im_package from './components/im_package.vue'
export default {
  components: {
    job_stick,
    job_emergency,
    job_refresh_package,
    resume_package,
    im_package
  }
}
</script>
