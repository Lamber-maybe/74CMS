<template>
  <div class="app-container">
    <el-card class="box-card">
      <div v-if="run_loading === false">
        <div slot="header" class="clearfix">
          <span>发送任务列表</span>
        </div>
        <el-table
          v-loading="listLoading"
          :data="list"
          element-loading-text="Loading"
          fit
          highlight-current-row
        >
          <el-table-column label="任务名称" prop="title" min-width="150" />
          <el-table-column align="center" label="生成时间" min-width="150">
            <template slot-scope="scope">
              <i class="el-icon-time" />
              <span>{{ scope.row.addtime | timeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column label="发送对象" min-width="120">
            <template slot-scope="scope">
              <span>
                {{ scope.row.target | targetFilter }}({{ scope.row.total }})
              </span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="内容详情" min-width="100">
            <template slot-scope="scope">
              <el-popover
                placement="bottom"
                width="200"
                trigger="hover"
                :content="scope.row.content"
              >
                <el-button slot="reference" type="text">[详情]</el-button>
              </el-popover>
            </template>
          </el-table-column>
          <el-table-column label="发送渠道" min-width="100">
            <template slot-scope="scope">
              <span>{{ scope.row.send_type | sendTypeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="执行状态" min-width="200">
            <template slot-scope="scope">
              <span v-if="scope.row.status == 1" class="font_info">
                已执行(成功{{ scope.row.success }}，失败{{
                  scope.row.total - scope.row.success
                }})
              </span>
              <span v-if="scope.row.status == 0" class="font_warning">
                未执行
              </span>
            </template>
          </el-table-column>
          <el-table-column fixed="right" label="操作" min-width="160">
            <template slot-scope="scope">
              <el-button
                v-if="scope.row.status == 0"
                size="small"
                type="primary"
                @click="funRun(scope.row, 1, 0)"
              >
                执行
              </el-button>
              <el-button
                v-else
                size="small"
                type="primary"
                disabled
              >
                执行
              </el-button>
              <el-button
                size="small"
                type="danger"
                @click="funDelete(scope.row)"
              >
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
              :page-sizes="[10, 20, 50, 100]"
              :page-size="pagesize"
              layout="total, sizes, prev, pager, next, jumper"
              :total="total"
              @size-change="handleSizeChange"
              @current-change="handleCurrentChange"
            />
          </el-col>
        </el-row>
      </div>
      <div v-if="run_loading === true" class="submiting-block">
        <el-progress
          type="circle"
          :percentage="percentage"
          :stroke-width="18"
          :width="220"
        />
        <p
          style="font-weight:bold;font-size:20px;text-align:center;margin-bottom:50px;color:#333;"
        >
          正在发送
        </p>
        <p>任务总量：{{ run_info.total }} 已经完成 {{ run_info.success }} 条</p>
      </div>
    </el-card>
  </div>
</template>

<script>
import { marketTaskList, marketTaskRun, marketTaskDelete } from '@/api/market'
import { cronSet } from '@/api/cron'
import { parseTime } from '@/utils/index'

export default {
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
    },
    targetFilter(target) {
      switch (target) {
        case 'all':
        case 'uid':
        case 'remind':
          return '全部会员'
        case 'company':
        case 'job':
        case 'setmeal':
          return '企业会员'
        case 'resume':
          return '个人会员'
        default:
          return '全部会员'
      }
    },
    sendTypeFilter(send_type) {
      const return_arr = []
      const arr = send_type.split(',')
      for (let i = 0; i < arr.length; i++) {
        switch (arr[i]) {
          case 'message':
            return_arr.push('站内信')
            break
          case 'sms':
            return_arr.push('短信')
            break
          case 'weixin':
            return_arr.push('微信')
            break
          case 'email':
            return_arr.push('Email')
            break
          default:
            break
        }
      }
      return return_arr.join(',')
    }
  },
  data() {
    return {
      run_loading: false,
      run_info: {
        total: '正在计算',
        success: 0
      },
      percentage: 0,
      cron_id: 0,
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
      this.cron_id = row.id
      this.dialogListVisible = true
    },
    fetchData() {
      this.listLoading = true
      const param = {
        page: this.currentPage,
        pagesize: this.pagesize
      }
      marketTaskList(param).then(response => {
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
          marketTaskDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funRun(row, current_page, total_success) {
      this.run_loading = true
      const param = {
        id: row.id,
        page: current_page
      }
      marketTaskRun(param)
        .then(response => {
          if (response.data.continue == 1) {
            this.run_info.total = response.data.total
            this.run_info.success =
              parseInt(total_success) + parseInt(response.data.success)
            current_page++
            this.percentage =
              (Number(this.run_info.success) / Number(this.run_info.total)) *
              100
            this.percentage = this.percentage.toFixed(2)
            this.percentage = Number(this.percentage)
            this.funRun(row, current_page, this.run_info.success)
          } else {
            this.$message.success(response.message)
            setTimeout(() => {
              this.run_loading = false
              this.funInitRuninfo()
              this.fetchData()
            }, 1500)
          }
        })
        .catch(() => {
          this.fetchData()
          this.run_loading = false
        })
    },
    funInitRuninfo() {
      this.percentage = 0
      this.run_info = {
        total: '正在计算',
        success: 0
      }
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
    }
  }
}
</script>

<style scoped>
.submiting-block {
  width: 100%;
  text-align: center;
}
.submiting-block p {
  width: 350px;
  margin: 20px auto;
  color: #666;
}
</style>
