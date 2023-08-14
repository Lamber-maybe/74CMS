<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <!--职位企业简历详情模板 zch 2022/8/4 -->
      <el-tab-pane label="PC端模板" :lazy="true">
        <pctpl />
      </el-tab-pane>
      <el-tab-pane label="触屏H5模板" :lazy="true">
        <mobiletpl />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import pctpl from './pc'
import mobiletpl from './mobile'
export default {
  components: {
    pctpl,
    mobiletpl
  }
}
</script>
