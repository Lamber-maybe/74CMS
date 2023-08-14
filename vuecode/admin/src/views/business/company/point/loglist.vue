<template>
  <div class="app-container">
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      size="small"
      highlight-current-row
    >
      <el-table-column align="center" label="时间">
        <template slot-scope="scope">
          <span>{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作">
        <template slot-scope="scope">
          <span v-if="scope.row.op == 1" class="font_success">
            增加
          </span>
          <span v-else class="font_danger">减少</span>
        </template>
      </el-table-column>
      <el-table-column label="积分数" prop="points" />
      <el-table-column
        label="备注"
        prop="content"
        width="400"
      />
    </el-table>
    <div class="spaceline" />
    <el-row :gutter="20">
      <el-col :span="20" style="text-align: right;">
        <el-pagination
          layout="prev, pager, next"
          background
          :total="total"
          :current-page="currentPage"
          :page-size="pagesize"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { memberPointsLog } from '@/api/member'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    }
  },
  props: ['uid'],
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 5
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        uid: this.uid,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      memberPointsLog(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    }
  }
}
</script>
