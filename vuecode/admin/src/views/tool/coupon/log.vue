<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>发放记录</span>
      </div>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="发放时间">
          <template slot-scope="scope">
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作管理员" prop="admin_name" />
        <el-table-column label="发放企业数" prop="count_num" />
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="funDetail(scope.row)">
              查看详情
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8" />
        <el-col :offset="8" :span="16" style="text-align: right;">
          <el-pagination
            background
            :current-page="currentPage"
            :page-sizes="[10, 20, 50, 100]"
            :page-size="pagesize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </el-col>
      </el-row>
    </el-card>
    <el-dialog
      v-if="dialogFormVisible"
      title="发放详情"
      :visible.sync="dialogFormVisible"
      width="50%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <dialist :log-id="logId" @setDialogFormVisible="closeDialog" />
    </el-dialog>
  </div>
</template>

<script>
import dialist from './detaillist.vue'
import { couponLog } from '@/api/coupon'
import { parseTime } from '@/utils/index'

export default {
  components: {
    dialist
  },
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      logId: 0,
      dialogFormVisible: false,
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
      couponLog(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    funDetail(row) {
      this.logId = row.id
      this.dialogFormVisible = true
    }
  }
}
</script>
