<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>网络招聘会管理</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="settr"
          placeholder="添加时间"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限添加时间" value="" />
          <el-option label="三天内" value="3" />
          <el-option label="一周内" value="7" />
          <el-option label="一月内" value="30" />
          <el-option label="半年内" value="180" />
          <el-option label="一年内" value="360" />
        </el-select>
        <el-input
          v-model="keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
          @keyup.enter.native="funSearchKeyword"
        >
          <el-select
            slot="prepend"
            v-model="key_type"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="标题" value="1" />
            <el-option label="招聘会ID" value="2" />
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
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="42" />
        <el-table-column label="标题" show-overflow-tooltip min-width="200">
          <template slot-scope="scope">
            <el-link :href="scope.row.jobfair_link" target="_blank" type="primary">
              {{ scope.row.title }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="举办时间" show-overflow-tooltip width="260">
          <template slot-scope="scope">
            {{ scope.row.starttime|timeFilter }} 至 {{ scope.row.endtime|timeFilter }}
          </template>
        </el-table-column>
        <el-table-column label="预定状态" width="100">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.score === 2" type="success">预定中</el-tag>
            <el-tag v-else-if="scope.row.score === 1" type="danger">未开始</el-tag>
            <el-tag v-else type="info">已结束</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="显示状态" width="100">
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.display"
              @change="modifyState(scope.row)">
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="created_at"
          label="添加日期"
          width="150"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="允许报名" align="center" width="100">
          <template slot-scope="scope">
            <el-tooltip v-if="scope.row.setmeal === 1" effect="dark" :content="scope.row.setmeal_cn" placement="top-start">
              <el-tag>限制套餐</el-tag>
            </el-tooltip>
            <span v-else>全部套餐</span>
          </template>
        </el-table-column>
        <el-table-column label="参会企业/个人" align="center" width="100">
          <template slot-scope="scope">
            {{ scope.row.total_company }} / {{ scope.row.total_personal }}
          </template>
        </el-table-column>
        <el-table-column fixed="right" align="right" label="操作" width="330">
          <template slot-scope="scope">
            <el-button
              size="mini"
              type="primary"
              @click="funCompany(scope.$index, scope.row)"
            >
              参会企业
            </el-button>
            <el-button
              size="mini"
              type="primary"
              @click="funPersonal(scope.$index, scope.row)"
            >
              参会个人
            </el-button>
            <el-button
              size="mini"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
              修改
            </el-button>
            <el-button
              size="mini"
              type="danger"
              @click="funDelete(scope.$index, scope.row)"
            >
              删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="goAdd">
            添加网络招聘会
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除所选
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right;">
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
import { jobFairOnLineList, jobFairOnLineDelete, jobfairOlModifyState } from '@/api/jobfairol'
import { parseTime } from '@/utils'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      settr: '',
      keyword: '',
      key_type: '1',
      total: 0,
      currentPage: 1,
      pagesize: 10,
      listLoading: !1,
      list: [],
      tableIdarr: [],
      showQrcodeWindow: !1,
      jobfairId: 0
    }
  },
  created() {
    this.getJobFairOnLine()
  },
  methods: {
    modifyState(row){
      const that = this
      const param = {
        display: row.display,
        id: row.id
      }
      jobfairOlModifyState(param).then(response => {
        this.$message.success(response.message)
        setTimeout(function() {
          that.$router.push('/jobfairol/list')
        }, 1500)
        return true
      }).catch(() => {
        that.issubmit = false
      })
    },
    // 获取网络招聘会
    getJobFairOnLine: function(){
      this.listLoading = true
      const param = {
        settr: this.settr,
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      jobFairOnLineList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    // 参会企业
    funCompany: function(index, row){
      this.$router.push({
        path: '/jobfairol/exhibitors/company/list',
        query: {
          jobfair_id: row.id
        }
      })
    },
    // 参会个人
    funPersonal: function(index, row) {
      this.$router.push({
        path: '/jobfairol/exhibitors/personal/list',
        query: {
          jobfair_id: row.id
        }
      })
    },
    // 筛选
    funSearch: function(){
      this.getJobFairOnLine()
    },
    // 关键字筛选
    funSearchKeyword: function(){
      this.currentPage = 1
      this.getJobFairOnLine()
    },
    // 分页
    handleSizeChange: function(val){
      this.pagesize = val
      this.getJobFairOnLine()
    },
    handleCurrentChange: function(val){
      this.currentPage = val
      this.getJobFairOnLine()
    },
    // 列表选中回调事件
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
        }
      }
    },
    goAdd: function(){
      this.$router.push('/jobfairol/list/add')
    },
    // 删除网络招聘会
    funDelete: function(index, row){
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          jobFairOnLineDelete(param).then(response => {
            that.$message.success(response.message)
            that.getJobFairOnLine()
            return true
          })
        })
        .catch(() => {})
    },
    // 批量删除
    funDeleteBatch: function(){
      var that = this
      if (that.tableIdarr.length === 0) {
        that.$message.error('请选择要删除的数据')
        return false
      }
      that
        .$confirm('此操作将永久删除选中的数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          jobFairOnLineDelete(param).then(response => {
            that.$message.success(response.message)
            that.getJobFairOnLine()
            return true
          })
        })
        .catch(() => {})
    },
    // 编辑网络招聘会
    funEdit: function(index, row){
      this.$router.push({
        path: '/jobfairol/list/edit',
        query: {
          id: row.id
        }
      })
    }
  }
}
</script>
