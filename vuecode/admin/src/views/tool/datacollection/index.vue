<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="采集设置" :lazy="true">
        <seting />
      </el-tab-pane>
      <el-tab-pane label="职位设置" :lazy="true">
        <job_seting />
      </el-tab-pane>
      <el-tab-pane label="企业设置" :lazy="true">
        <company_seting />
      </el-tab-pane>
      <el-tab-pane label="资讯设置" :lazy="true">
        <article_seting />
      </el-tab-pane>
      <el-tab-pane label="账号设置" :lazy="true">
        <account_seting />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import seting from './seting.vue'
import job_seting from './job_seting.vue'
import company_seting from './company_seting.vue'
import account_seting from './account_seting.vue'
import article_seting from './article_seting.vue'
export default {
  components: {
    seting,
    job_seting,
    company_seting,
    account_seting,
    article_seting
  }
}
</script>
