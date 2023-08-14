<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="6">
        <sex_pie height="240px" />
      </el-col>
      <el-col :span="6">
        <age_pie height="240px" />
      </el-col>
      <el-col :span="6">
        <edu_pie height="240px" />
      </el-col>
      <el-col :span="6">
        <exp_pie height="240px" />
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <intention_district height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <intention_jobcategory height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <active_line
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
    <el-row style="margin-top:20px;">
      <resume_add_line
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import sex_pie from './components/sex_pie.vue'
import age_pie from './components/age_pie.vue'
import edu_pie from './components/edu_pie.vue'
import exp_pie from './components/exp_pie.vue'
import intention_district from './components/intention_district.vue'
import intention_jobcategory from './components/intention_jobcategory.vue'
import active_line from './components/active_line.vue'
import resume_add_line from './components/resume_add_line.vue'

export default {
  components: {
    sex_pie,
    age_pie,
    edu_pie,
    exp_pie,
    intention_district,
    intention_jobcategory,
    active_line,
    resume_add_line
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
