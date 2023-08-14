<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane
        label="企业信息"
        :lazy="true"
      >
        <fieldForm listtype="company" />
      </el-tab-pane>
      <el-tab-pane
        label="职位信息"
        :lazy="true"
      >
        <fieldForm listtype="job" />
      </el-tab-pane>
      <el-tab-pane
        label="简历信息"
        :lazy="true"
      >
        <fieldForm listtype="resume" />
      </el-tab-pane>
      <el-tab-pane
        label="简历模块"
        :lazy="true"
      >
        <resumeModule />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import fieldForm from './fieldForm.vue'
import resumeModule from './resumeModule.vue'
export default {
  components: {
    fieldForm,
    resumeModule
  }
}
</script>

