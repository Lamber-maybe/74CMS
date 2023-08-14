<template>
  <div class="app-container">
    <div class="tip">
      <p>
        不同的运营阶段您可以选择不同的设置。
      </p>
    </div>
    <el-tabs type="border-card">
      <el-tab-pane label="基本设置" :lazy="true">
        <config-basic />
      </el-tab-pane>
      <el-tab-pane label="联系方式" :lazy="true">
        <config-contact />
      </el-tab-pane>
      <el-tab-pane label="认证/审核" :lazy="true">
        <config-audit />
      </el-tab-pane>
      <el-tab-pane label="搜索" :lazy="true">
        <config-search />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import configBasic from './components/basic.vue'
import configContact from './components/contact.vue'
import configAudit from './components/audit.vue'
import configSearch from './components/search.vue'
export default {
  components: {
    configBasic,
    configContact,
    configAudit,
    configSearch
  }
}
</script>
