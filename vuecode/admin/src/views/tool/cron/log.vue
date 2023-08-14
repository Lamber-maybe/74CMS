<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>执行日志</span>
      </div>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="任务名称" prop="cron_name" />
        <el-table-column align="center" label="执行时间">
          <template slot-scope="scope">
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="耗费时长(秒)">
          <template slot-scope="scope">{{ scope.row.seconds }}</template>
        </el-table-column>
        <el-table-column align="center" label="类型">
          <template slot-scope="scope">
            <span v-if="scope.row.is_auto == 1" class="font_success">
              自动执行
            </span>
            <span v-else class="font_warning">手动执行</span>
          </template>
        </el-table-column>
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
    </el-card>
  </div>
</template>

<script>
import { cronLog } from '@/api/cron'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    }
  },
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        page: this.currentPage,
        pagesize: this.pagesize
      }
      cronLog(param).then(response => {
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
