<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="生成推文" :lazy="true">
        <toolmarketingcreate />
      </el-tab-pane>
      <el-tab-pane label="模板管理" :lazy="true">
        <toolmarketingtemplate />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import toolmarketingcreate from './create'
import toolmarketingtemplate from './template/template'
export default {
  components: {
    toolmarketingcreate,
    toolmarketingtemplate
  }
}
</script>
