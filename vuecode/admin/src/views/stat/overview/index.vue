<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="6">
        <el-card>入驻企业 {{ total.company }} 家</el-card>
      </el-col>
      <el-col :span="6">
        <el-card>招聘岗位 {{ total.job }} 个</el-card>
      </el-col>
      <el-col :span="6">
        <el-card>岗位需求 {{ total.job_amount }} 人</el-card>
      </el-col>
      <el-col :span="6">
        <el-card>求职者数 {{ total.resume }} 人</el-card>
      </el-col>
    </el-row>
    <el-row :gutter="20" style="margin-top:20px;">
      <el-col :span="12">
        <com_order height="300px" />
      </el-col>
      <el-col :span="12">
        <per_order height="300px" />
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <reg_line height="300px" :platform-options="platformOptions" />
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
import { overviewTotal } from '@/api/stat'
import com_order from './components/com_order.vue'
import per_order from './components/per_order.vue'
import reg_line from './components/reg_line.vue'
import active_line from './components/active_line.vue'

export default {
  components: {
    com_order,
    per_order,
    reg_line,
    active_line
  },
  data() {
    return {
      platformOptions: [],
      total: {
        company: 0,
        job: 0,
        job_amount: 0,
        resume: 0
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      overviewTotal({}).then(response => {
        this.total = { ...response.data }
      })
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
