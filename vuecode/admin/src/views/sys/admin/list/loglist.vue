<template>
  <div class="app-container">
    <div v-if="isLogin == 0" class="list-search">
      <el-input
        v-model="keyword"
        placeholder="请输入搜索内容"
        class="input-with-select"
        size="small"
        style="width:300px"
      >
        <el-button
          slot="append"
          icon="el-icon-search"
          @click="funSearchKeyword"
        />
      </el-input>
    </div>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      size="small"
      highlight-current-row
      stripe
    >
      <el-table-column label="时间">
        <template slot-scope="scope">
          <span>{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" prop="content" />
      <el-table-column label="IP" prop="ip" />
      <el-table-column label="IP归属地" prop="ip_addr" />
    </el-table>
    <div class="spaceline" />
    <el-row style="text-align: right;">
      <el-pagination
        layout="prev, pager, next"
        background
        :total="total"
        :current-page="currentPage"
        :page-size="pagesize"
        @current-change="handleCurrentChange"
      />
    </el-row>
  </div>
</template>

<script>
import { adminLogList } from '@/api/admin'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    }
  },
  props: ['adminId', 'isLogin'],
  data() {
    return {
      keyword: '',
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
        is_login: this.isLogin,
        keyword: this.keyword,
        admin_id: this.adminId,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      adminLogList(param).then(response => {
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
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    }
  }
}
</script>
