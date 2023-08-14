<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>管理员日志</span>
      </div>
      <div class="list-search">
        <div class="filter-item">
          <span class="label">管理员：</span>
          <el-select v-model="admin_id" size="small" placeholder="请选择" @change="funSearch">
            <el-option label="全部" value="">全部</el-option>
            <el-option v-for="(item,index) in adminlist" :key="index" :label="item.username" :value="item.id">{{ item.username }}</el-option>
          </el-select>
        </div>
        <div class="filter-item">
          <span class="label">关键字：</span>
          <el-input
            v-model="keyword"
            size="small"
            placeholder="请输入"
            style="width:200px;"
            @keyup.enter.native="fetchData(true)"
          />
        </div>

        <el-button style="search-btn" size="small" @click="fetchData(true)">查询</el-button>
      </div>
      <div class="spaceline" />
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="时间" width="160">
          <template slot-scope="scope">
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" prop="content" />
        <el-table-column label="管理员" prop="admin_name" width="100" />
        <el-table-column label="IP" prop="ip" width="130" />
        <el-table-column label="IP归属地" prop="ip_addr" width="120" />
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8" />
        <el-col :offset="8" :span="16" style="text-align: right;">
          <el-pagination
            background
            :current-page="currentPage"
            :page-sizes="[10,15, 20, 30, 40]"
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
import { adminLogList, adminAllList } from '@/api/admin'
import { parseTime } from '@/utils/index'

export default {
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
      admin_id: '',
      keyword: '',
      adminlist: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    funSearch() {
      this.currentPage = 1
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    fetchData() {
      this.listLoading = true
      adminAllList({}).then(response => {
        this.adminlist = response.data
      })
      const param = {
        keyword: this.keyword,
        admin_id: this.admin_id,
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
    margin-right:10px;
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
