<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>管理员列表</span>
      </div>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column align="center" label="ID" width="95">
          <template slot-scope="scope">
            {{ scope.row.id }}
          </template>
        </el-table-column>
        <el-table-column label="登录名" prop="username" />
        <el-table-column label="角色" prop="role_name" />
        <el-table-column align="center" prop="created_at" label="创建时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" prop="created_at" label="最后登录时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.last_login_time | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="最后登录IP"
          prop="last_login_ip"
        />
        <el-table-column fixed="right" label="操作" width="400">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
              编辑
            </el-button>
            <el-button size="small" @click="funLog(scope.row, 1)">
              登录日志
            </el-button>
            <el-button size="small" @click="funLog(scope.row)">
              操作日志
            </el-button>
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
          <el-button size="small" type="primary" @click="goTo('add')">
            添加
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right;">
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
      <el-dialog
        v-if="dialogVisible"
        :title="dialogTitle"
        :visible.sync="dialogVisible"
        width="60%"
        :close-on-click-modal="false"
        @close="closeDialog"
      >
        <dialist
          :admin-id="adminId"
          :is-login="isLogin"
          @setDialogFormVisible="closeDialog"
        />
      </el-dialog>
    </el-card>
  </div>
</template>

<script>
import dialist from './loglist.vue'
import { apiList, apiDelete } from '@/api/admin'
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
      dialogTitle: '登录日志',
      admiId: 0,
      isLogin: 0,
      dialogVisible: false,
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
      apiList(param).then(response => {
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
    funEdit(index, row) {
      this.$router.push({
        path: '/sys/admin/list/edit',
        query: {
          id: row.id
        }
      })
    },
    funDelete(index, row) {
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
          apiDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    goTo(target) {
      this.$router.push('/sys/admin/list/' + target)
    },
    funLog(row, isLogin = 0) {
      this.adminId = row.id
      this.isLogin = isLogin
      this.dialogVisible = true
      if (isLogin === 0){
        this.dialogTitle = '操作日志'
      } else {
        this.dialogTitle = '登录日志'
      }
    },
    closeDialog() {
      this.dialogVisible = false
    }
  }
}
</script>
