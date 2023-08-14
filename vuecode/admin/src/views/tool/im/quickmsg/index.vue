<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="个人常用语" :lazy="true">
        <toolimquickmsgper />
      </el-tab-pane>
      <el-tab-pane label="企业常用语" :lazy="true">
        <toolimquickmsgcom />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import toolimquickmsgper from './components/per.vue'
import toolimquickmsgcom from './components/com.vue'
export default {
  components: {
    toolimquickmsgper,
    toolimquickmsgcom
  }
}
</script>
