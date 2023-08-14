<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="6">
        <com_nature height="240px" />
      </el-col>
      <el-col :span="6">
        <com_scale height="240px" />
      </el-col>
      <el-col :span="6">
        <com_audit height="240px" />
      </el-col>
      <el-col :span="6">
        <com_setmeal height="240px" />
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <com_district height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <com_trade height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <com_add_line
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
    <el-row style="margin-top:20px;">
      <active_line
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import com_nature from './components/com_nature.vue'
import com_scale from './components/com_scale.vue'
import com_audit from './components/com_audit.vue'
import com_setmeal from './components/com_setmeal.vue'
import com_district from './components/com_district.vue'
import com_trade from './components/com_trade.vue'
import com_add_line from './components/com_add_line.vue'
import active_line from './components/active_line.vue'

export default {
  components: {
    com_nature,
    com_scale,
    com_audit,
    com_setmeal,
    com_district,
    com_trade,
    com_add_line,
    active_line
  },
  data() {
    return {
      platformOptions: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const params = {
        type: 'platform'
      }
      getClassify(params).then(response => {
        this.platformOptions = [...response.data]
      })
    }
  }
}
</script>
