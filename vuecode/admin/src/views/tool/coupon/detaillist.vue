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
      <el-table-column label="优惠券名称" prop="coupon_name" />
      <el-table-column label="发放企业">
        <template slot-scope="scope">
          【uid:{{ scope.row.uid }}】{{ scope.row.companyname }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="发放时间">
        <template slot-scope="scope">
          <span>{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="是否使用">
        <template slot-scope="scope">
          <span v-if="scope.row.usetime > 0" class="font_success">已使用</span>
          <span v-else class="font_warning">未使用</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="使用时间">
        <template slot-scope="scope">
          <span v-if="scope.row.usetime == 0">-</span>
          <span v-else>{{ scope.row.usetime | timeFilter }}</span>
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
  </div>
</template>

<script>
import { couponRecord } from '@/api/coupon'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: ['logId'],
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
        log_id: this.logId,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      couponRecord(param).then(response => {
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
