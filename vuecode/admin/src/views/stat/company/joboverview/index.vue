<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="6">
        <job_edu height="240px" />
      </el-col>
      <el-col :span="6">
        <job_exp height="240px" />
      </el-col>
      <el-col :span="6">
        <job_nature height="240px" />
      </el-col>
      <el-col :span="6">
        <job_wage height="240px" />
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <job_district height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <job_category height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <job_add_line
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
import job_edu from './components/job_edu.vue'
import job_exp from './components/job_exp.vue'
import job_nature from './components/job_nature.vue'
import job_wage from './components/job_wage.vue'
import job_district from './components/job_district.vue'
import job_category from './components/job_category.vue'
import job_add_line from './components/job_add_line.vue'
import active_line from './components/active_line.vue'

export default {
  components: {
    job_edu,
    job_exp,
    job_nature,
    job_wage,
    job_district,
    job_category,
    job_add_line,
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
