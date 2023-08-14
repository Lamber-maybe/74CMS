<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="广告列表" :lazy="true">
        <ad-list />
      </el-tab-pane>
      <el-tab-pane label="广告位" :lazy="true">
        <ad-cat />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import adList from './list/index.vue'
import adCat from './category/index.vue'

export default {
  components: {
    adList,
    adCat
  }
}
</script>
