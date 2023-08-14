<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="12">
        <job_refresh height="300px" />
      </el-col>
      <el-col :span="12">
        <company_down height="300px" />
      </el-col>
    </el-row>
    <el-row :gutter="20" style="margin-top:20px;">
      <el-col :span="12">
        <com_login height="300px" />
      </el-col>
      <el-col :span="12">
        <job_apply height="300px" />
      </el-col>
    </el-row>
    <el-row :gutter="20" style="margin-top:20px;">
      <el-col :span="12">
        <view_job height="300px" />
      </el-col>
      <el-col :span="12">
        <view_com height="300px" />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import job_refresh from './components/job_refresh.vue'
import company_down from './components/company_down.vue'
import com_login from './components/com_login.vue'
import job_apply from './components/job_apply.vue'
import view_job from './components/view_job.vue'
import view_com from './components/view_com.vue'

export default {
  components: {
    job_refresh,
    company_down,
    com_login,
    job_apply,
    view_job,
    view_com
  }
}
</script>
