<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="12">
        <com_nature height="240px" />
      </el-col>
      <el-col :span="12">
        <com_scale height="240px" />
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <com_district height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <com_trade height="300px" />
    </el-row>
  </div>
</template>

<script>
import com_nature from './components/com_nature.vue'
import com_scale from './components/com_scale.vue'
import com_district from './components/com_district.vue'
import com_trade from './components/com_trade.vue'

export default {
  components: {
    com_nature,
    com_scale,
    com_district,
    com_trade
  }
}
</script>
