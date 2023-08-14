<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>账号注销申请</span>
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
        >
          <el-select
            slot="prepend"
            v-model="key_type"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="公司名称" value="1" />
          </el-select>
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
        highlight-current-row
      >
        <el-table-column label="企业名称" prop="companyname" />
        <el-table-column label="注册时间" prop="regtime" />
        <el-table-column label="联系人" prop="contact" />
        <el-table-column label="手机号" prop="mobile" />
        <el-table-column align="center" label="处理状态">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.status==1" type="success">已处理</el-tag>
            <el-tag v-if="scope.row.status==0" type="danger">待处理</el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="申请时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="处理时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span v-if="scope.row.handlertime!=0">{{ scope.row.handlertime | timeFilter }}</span>
            <span v-else>-</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="funHandle(scope.row)">
              处理
            </el-button>
            <el-button size="small" type="danger" @click="funDel(scope.row)">
              删除
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
import { memberCancelApplyList, memberCancelApplyDelete, memberCancelApplyHandle } from '@/api/member_cancel_apply'
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
      key_type: '1',
      keyword: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      memberCancelApplyList(param).then(response => {
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
    funSearch() {
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    funDel(row){
      var that = this
      that
        .$confirm('确定删除吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          memberCancelApplyDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funHandle(row){
      var that = this
      that
        .$confirm('注销将删除该企业的所有数据，确定注销吗？', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          memberCancelApplyHandle(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped>
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
</style>
