<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>计划任务列表</span>
      </div>
      <div class="tip">
        <p>
          计划任务是一项使系统在规定时间自动执行某些特定任务的功能，在需要的情况下，您也可以方便的将其用于功能的扩展。
        </p>
        <p>
          计划任务是与系统核心紧密关联的功能特性，不当的设置可能造成功能的隐患，严重时可能导致网站无法正常运行。
        </p>
      </div>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="名称" prop="name" min-width="160" />
        <el-table-column align="center" label="类型" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.is_sys == 1" class="font_info">
              系统内置
            </span>
            <span v-if="scope.row.is_sys == 0" class="font_warning">
              自定义
            </span>
          </template>
        </el-table-column>
        <el-table-column label="执行间隔" min-width="140">
          <template slot-scope="scope">
            <span>{{ scope.row.rule }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="上次执行时间" min-width="200">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.last_execute_time | timeFilter }}</span>
            <span
              style="cursor:pointer;color:#3a8ee6;font-size:12px;"
              @click="funLog(scope.row)"
            >
              [详情]
            </span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="下次执行时间" min-width="180">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.next_execute_time | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="状态" min-width="80">
          <template slot-scope="scope">
            <el-tag :type="scope.row.status | colorFilter">
              {{ scope.row.status == 1 ? '可用' : '停用' }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="380">
          <template slot-scope="scope">
            <el-button size="small" @click="funRun(scope.row, scope.$index)">
              执行
            </el-button>
            <el-button size="small" @click="funCopy(scope.row)">
              复制链接
            </el-button>
            <el-button size="small" type="primary" @click="funEdit(scope.row)">
              修改
            </el-button>
            <el-button
              v-if="scope.row.status == 1"
              size="small"
              type="info"
              @click="funStatus(scope.row, 0, scope.$index)"
            >
              停用
            </el-button>
            <el-button
              v-if="scope.row.status == 0"
              size="small"
              type="success"
              @click="funStatus(scope.row, 1, scope.$index)"
            >
              启用
            </el-button>
            <el-button size="small" type="danger" @click="funDelete(scope.row)">
              删除
            </el-button>

          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="funAdd">
            添加计划任务
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
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaform
        :item-info="item_info"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
    <el-dialog
      v-if="dialogListVisible"
      title="任务执行记录"
      :visible.sync="dialogListVisible"
      width="50%"
      :close-on-click-modal="false"
      @close="closeListDialog"
    >
      <dialist
        :cron-id="cronId"
        @setDialogFormVisible="closeListDialog"
      />
    </el-dialog>
  </div>
</template>

<script>
import dialist from './loglist.vue'
import diaform from './form.vue'
import { cronList, cronSet, cronRun, cronDelete } from '@/api/cron'
import { parseTime } from '@/utils/index'

export default {
  components: {
    diaform,
    dialist
  },
  filters: {
    colorFilter(val) {
      if (val == 1) {
        return 'success'
      } else {
        return 'danger'
      }
    },
    timeFilter(timestamp) {
      if (timestamp == 0) {
        return '从未执行'
      }
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      cronId: 0,
      item_info: null,
      dialogTitle: '',
      dialogFormVisible: false,
      dialogListVisible: false,
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
    funLog(row) {
      this.cronId = row.id
      this.dialogListVisible = true
    },
    fetchData() {
      this.listLoading = true
      const param = {
        page: this.currentPage,
        pagesize: this.pagesize
      }
      cronList(param).then(response => {
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
    closeDialog() {
      this.dialogFormVisible = false
    },
    closeListDialog() {
      this.dialogListVisible = false
    },
    funAdd() {
      this.item_info = null
      this.dialogTitle = '添加'
      this.dialogFormVisible = true
    },
    funEdit(row) {
      this.item_info = row
      this.dialogTitle = '编辑'
      this.dialogFormVisible = true
    },
    funDelete(row) {
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
          cronDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funRun(row, index) {
      var that = this
      that
        .$confirm('确定执行该计划任务吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          cronRun(param).then(response => {
            that.$message.success(response.message)
            that.list[index].last_execute_time = response.data.last_execute_time
            that.list[index].next_execute_time = response.data.next_execute_time
            return true
          })
        })
        .catch(() => {})
    },
    funStatus(row, status, index) {
      var that = this
      const param = {
        id: row.id,
        status
      }
      cronSet(param).then(response => {
        that.$message.success(response.message)
        that.list[index].status = status
        return true
      })
    },
    funCopy(row){
      const that = this
      const copyMessage = row.runUrl
      this.$copyText(copyMessage).then(function (e) {
        that.$message.success('链接已复制到剪切板！')
      }, function (e) {
        that.$message.error('抱歉，复制失败！')
      })
    }
  }
}
</script>
