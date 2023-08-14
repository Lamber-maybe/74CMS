<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="12">
        <resume_refresh height="300px" />
      </el-col>
      <el-col :span="12">
        <job_apply height="300px" />
      </el-col>
    </el-row>
    <el-row :gutter="20" style="margin-top:20px;">
      <el-col :span="12">
        <per_login height="300px" />
      </el-col>
      <el-col :span="12">
        <resume_down height="300px" />
      </el-col>
    </el-row>
    <el-row :gutter="20" style="margin-top:20px;">
      <el-col :span="12">
        <view_resume height="300px" />
      </el-col>
      <el-col :span="12" />
    </el-row>
  </div>
</template>

<script>
import resume_refresh from './components/resume_refresh.vue'
import job_apply from './components/job_apply.vue'
import per_login from './components/per_login.vue'
import resume_down from './components/resume_down.vue'
import view_resume from './components/view_resume.vue'

export default {
  components: {
    resume_refresh,
    job_apply,
    per_login,
    resume_down,
    view_resume
  }
}
</script>
