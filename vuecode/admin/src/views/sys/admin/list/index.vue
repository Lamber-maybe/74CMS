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
        <el-table-column align="center" label="ID" width="80">
          <template slot-scope="scope">
            {{ scope.row.id }}
          </template>
        </el-table-column>
        <el-table-column label="登录名" prop="username" min-width="120" />
        <el-table-column label="状态" prop="status" min-width="100" align="center">
          <template slot-scope="scope">
            <span v-if="parseInt(scope.row.status) === 1">正常</span><span v-else>锁定</span>
          </template>
        </el-table-column>
        <el-table-column label="角色" prop="role_name" min-width="120" />
        <el-table-column label="是否是销售" prop="is_sc" min-width="100" align="center">
          <template slot-scope="scope">
            <span v-if="!!parseInt(scope.row.is_sc)">是</span><span v-else>不是</span>
          </template>
        </el-table-column>
        <el-table-column align="center" prop="openid" label="微信绑定" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.openid==''"><el-button type="text" @click="funBind(scope.row.id)">[点击绑定]</el-button></span>
            <span v-else>已绑定<el-button type="text" @click="funBindCancel(scope.row.id)">[解绑]</el-button></span>
          </template>
        </el-table-column>
        <el-table-column align="center" prop="last_login_time" label="最后登录时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.last_login_time | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="最后登录IP"
          prop="last_login_ip"
          min-width="170"
        />
        <el-table-column fixed="right" label="操作" min-width="330">
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
              v-if="parseInt(scope.row.status) === 1"
              size="small"
              type="danger"
              @click="funLock(scope.$index, scope.row)"
            >
              锁定
            </el-button>
            <el-button
              v-else
              size="small"
              type="danger"
              @click="funDeblocking(scope.$index, scope.row)"
            >
              解锁
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
            :page-sizes="[10, 20, 50, 100]"
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
    <el-dialog
      v-if="showQrcode"
      title="扫码绑定"
      :visible.sync="showQrcode"
      width="15%"
      :close-on-click-modal="false"
      style="text-align:center;"
      @close="showQrcode=false"
    >
      <div style="margin-bottom:10px;"><img :src="qrcodeSrc" width="200" height="200"></div>
    </el-dialog>
  </div>
</template>

<script>
import dialist from './loglist.vue'
import { apiList, apiDelete, adminBindQrcode, adminBindQrcodeCancel, getAdminCrmData, adminLock, adminDeblocking } from '@/api/admin'
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
      pagesize: 10,
      qrcodeSrc: '',
      showQrcode: false,
      total_company: 0,
      total_clue: 0
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
    },
    funBind(id){
      const that = this
      adminBindQrcode({ id: id }).then(response => {
        that.qrcodeSrc = response.data
        that.showQrcode = true
      })
    },
    funBindCancel(id){
      const that = this
      that
        .$confirm('确定解绑吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          adminBindQrcodeCancel({ id: id }).then(response => {
            that.$message.success(response.message)
            that.fetchData()
          })
        })
        .catch(() => {})
    },
    funLock(index, row){
      const username = row.username
      const param = {
        id: row.id
      }
      getAdminCrmData(param).then(response => {
        var that = this
        that
          .$confirm('当前管理员 ' + username + ' 有 ' + response.data.total_company + ' 线索, ' + response.data.total_clue + ' 客户,锁定后将自动释放且无法登录管理后台,是否继续？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          })
          .then(() => {
            adminLock(param).then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
              .catch(() => {})
          })
          .catch(() => {})
      }).catch(() => {})
    },
    funDeblocking(index, row){
      const username = row.username
      var that = this
      that
        .$confirm('此操作将解锁管理员【' + username + '】, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          adminDeblocking(param).then(response => {
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
