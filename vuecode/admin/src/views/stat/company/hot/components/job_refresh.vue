<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">职位刷新量排行榜TOP100</div>
      <el-radio-group
        v-model="daterange"
        size="mini"
        style="position:absolute;right:18px;top:18px;"
        @change="fetchData"
      >
        <el-radio-button label="">不限</el-radio-button>
        <el-radio-button label="week">周</el-radio-button>
        <el-radio-button label="month">月</el-radio-button>
      </el-radio-group>

      <el-table
        v-loading="listLoading"
        :data="dataset"
        border
        stripe
        size="mini"
        :height="height"
        style="width: 100%"
      >
        <el-table-column
          prop="jobname"
          label="职位名称"
          min-width="220"
        />
        <el-table-column prop="education" label="学历要求" />
        <el-table-column prop="experience" label="经验要求" />
        <el-table-column prop="district" label="工作地区" />
        <el-table-column prop="total" label="刷新次数" />
        <el-table-column prop="refreshtime" label="更新时间" />
      </el-table>
    </el-card>
  </div>
</template>

<script>
import { jobHotRefresh } from '@/api/stat'
export default {
  props: ['height'],
  data() {
    return {
      daterange: '',
      listLoading: false,
      dataset: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const params = {
        daterange: this.daterange
      }
      jobHotRefresh(params).then(response => {
        this.dataset = [...response.data.dataset]
        this.listLoading = false
      })
    }
  }
}
</script>
<style scoped>
.tit {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
}
</style>
