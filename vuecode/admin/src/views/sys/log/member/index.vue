<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ utype == 1 ? "企业" : "个人" }}会员日志</span>
      </div>
      <div class="list-search">
        <div class="filter-item">
          <span class="label">会员uid：</span>
          <el-input
            v-model="uid"
            size="small"
            placeholder="请输入"
            style="width: 200px"
          />
        </div>
        <div class="filter-item">
          <span class="label">关键字：</span>
          <el-input
            v-model="keyword"
            size="small"
            placeholder="请输入"
            style="width: 200px"
            @keyup.enter.native="fetchData(true)"
          />
        </div>

        <el-button style="search-btn" size="small" @click="fetchData(true)"
          >查询</el-button
        >
      </div>
      <div class="spaceline" />
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="时间" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" prop="content" />
        <el-table-column
          label="企业名称"
          prop="companyname"
          width="300"
          v-if="utype == 1"
        />
        <el-table-column
          label="姓名"
          prop="fullname"
          width="150"
          v-if="utype == 2"
        />
        <el-table-column label="IP" prop="ip" width="180" />
        <el-table-column label="IP归属地" prop="ip_addr" width="180" />
        <el-table-column label="来源" prop="platform_cn" width="180" />
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8" />
        <el-col :offset="8" :span="16" style="text-align: right">
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
  </div>
</template>

<script>
import { memberActionLog } from '@/api/member'
import { parseTime } from '@/utils/index'

export default {
  props: ['utype'],
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      uid: '',
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
        utype: this.utype,
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
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    }
  }
}
</script>
<style scoped lang="scss">
.list-search {
  width: 100%;
  .filter-item {
    float: left;
    .label {
      color: #666;
      font-size: 14px;
    }
  }
  .el-select.list-options {
    margin-right: 10px;
    width: 150px;
  }
  .el-input {
    margin-right: 10px;
    width: 150px;
  }
}
</style>
