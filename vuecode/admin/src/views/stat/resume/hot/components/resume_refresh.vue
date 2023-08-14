<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">简历刷新量排行榜TOP100</div>
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
        <el-table-column prop="fullname" label="姓名" />
        <el-table-column prop="sex" label="性别" />
        <el-table-column label="基本信息" min-width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.age }}</span>
            /
            <span>{{ scope.row.education }}</span>
            /
            <span>{{ scope.row.experience }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="complete_percent"
          label="完整度"
        />
        <el-table-column prop="total" label="刷新次数" />
        <el-table-column prop="refreshtime" label="更新时间" />
      </el-table>
    </el-card>
  </div>
</template>

<script>
import { resumeHotRefresh } from '@/api/stat'
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
      resumeHotRefresh(params).then(response => {
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
