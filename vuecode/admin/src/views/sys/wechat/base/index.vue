<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="公众号基础信息" :lazy="true">
        <config-base />
      </el-tab-pane>
      <el-tab-pane label="自定义关键词" :lazy="true">
        <config-keyword />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import configBase from './components/base.vue'
import configKeyword from './components/keyword.vue'
export default {
  components: {
    configBase,
    configKeyword
  }
}
</script>
