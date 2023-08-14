<template>
  <div>
    <div class="list-search">
      <el-input
        v-model="keyword"
        placeholder="请输入关键词"
        class="input-with-select"
        size="small"
        @keyup.enter.native="funSearchKeyword"
      >
        <el-button
          slot="append"
          icon="el-icon-search"
          @click="funSearchKeyword"
        />
      </el-input>
    </div>
    <div class="spaceline" />
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      size="small"
      highlight-current-row
    >
      <el-table-column label="时间" width="150">
        <template slot-scope="scope">
          <span>{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" prop="content" />
      <el-table-column label="IP" prop="ip" width="150" />
      <el-table-column label="IP归属地" prop="ip_addr" width="150" />
      <el-table-column label="来源" prop="platform_cn" width="150" />
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
import { memberActionLog } from '@/api/member'
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
      pagesize: 10,
      keyword: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    fetchData() {
      this.listLoading = true
      const param = {
        keyword: this.keyword,
        uid: this.uid,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      memberActionLog(param).then(response => {
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
<style scoped>
.input-with-select{
  width:300px;
  float:none;
}
</style>
