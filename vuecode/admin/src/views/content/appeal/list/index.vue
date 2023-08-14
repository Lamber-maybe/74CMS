<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>账号申诉</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="status"
          placeholder="不限处理状态"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限处理状态" value="" />
          <el-option label="已处理" value="1" />
          <el-option label="未处理" value="0" />
        </el-select>
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
        <el-table-column label="真实姓名" width="200">
          <template slot-scope="scope">
            {{ scope.row.realname }}
          </template>
        </el-table-column>
        <el-table-column label="处理状态" width="150">
          <template slot-scope="scope">
            <el-tag :type="scope.row.status | statusFilter">
              {{ scope.row.status == 1 ? '已处理' : '未处理' }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="申诉描述" show-overflow-tooltip>
          <template slot-scope="scope">
            {{ scope.row.description }}
          </template>
        </el-table-column>
        <el-table-column align="center" label="联系手机" width="120">
          <template slot-scope="scope">
            {{ scope.row.mobile }}
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="created_at"
          label="添加时间"
          width="200"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="100">
          <template slot-scope="scope">
            <el-button
              size="small"
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
          <el-button size="small" type="primary" @click="funHandler">
            处理
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除
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
import { appealList, appealHandler, appealDelete } from '@/api/appeal'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    statusFilter(status) {
      const displayMap = {
        1: 'success',
        0: 'danger'
      }
      return displayMap[status]
    },
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      tableIdarr: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      status: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        status: this.status,
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      appealList(param).then(response => {
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
    funHandler(index, row) {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要处理的数据')
        return false
      }
      that
        .$confirm('确定设为已处理吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          appealHandler(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funDeleteBatch() {
      var that = this
      if (that.tableIdarr.length == 0) {
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
          appealDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
        }
      }
    },
    goTo(target) {
      this.$router.push('/content/appeal/list/' + target)
    }
  }
}
</script>
