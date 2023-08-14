<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">企业下载量排行榜TOP100</div>
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
          prop="companyname"
          label="企业名称"
          width="220"
        />
        <el-table-column prop="nature" label="企业性质" />
        <el-table-column prop="scale" label="企业规模" />
        <el-table-column prop="trade" label="所属行业" />
        <el-table-column prop="district" label="所在地区" />
        <el-table-column prop="total" label="下载简历数" />
      </el-table>
    </el-card>
  </div>
</template>

<script>
import { comHotDown } from '@/api/stat'
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
      comHotDown(params).then(response => {
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
