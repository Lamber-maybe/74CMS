<template>
  <div class="app-container">
    <el-row>
      <edu_bar height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <age_bar height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <exp_bar height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <jobcategory_bar height="300px" />
    </el-row>
  </div>
</template>

<script>
import edu_bar from './components/edu_bar.vue'
import age_bar from './components/age_bar.vue'
import exp_bar from './components/exp_bar.vue'
import jobcategory_bar from './components/jobcategory_bar.vue'

export default {
  components: {
    edu_bar,
    age_bar,
    exp_bar,
    jobcategory_bar
  }
}
</script>
