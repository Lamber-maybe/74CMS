<template>
  <div class="app-container">
    <el-row>
      <setmeal_bar height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <service_bar height="300px" />
    </el-row>
    <el-row style="margin-top:20px;">
      <down_resume_line
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import setmeal_bar from './components/setmeal_bar.vue'
import service_bar from './components/service_bar.vue'
import down_resume_line from './components/down_resume_line.vue'

export default {
  components: {
    setmeal_bar,
    service_bar,
    down_resume_line
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
