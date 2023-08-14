<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>投诉客服</span>
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
        <el-table-column type="selection" width="30" />
        <el-table-column label="处理情况" width="100">
          <template slot-scope="scope">
            <el-tag :type="scope.row.status | statusFilter">
              {{ scope.row.status == 1 ? '已处理' : '未处理' }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="客服" width="120">
          <template slot-scope="scope">
            {{ scope.row.name }}
          </template>
        </el-table-column>
        <el-table-column label="内容" show-overflow-tooltip>
          <template slot-scope="scope">
            <span>{{ scope.row.content }}</span>
          </template>
        </el-table-column>
        <el-table-column label="投诉人" width="200">
          <template slot-scope="scope">
            {{ scope.row.mobile }} 【uid：{{ scope.row.uid }}】
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="addtime"
          label="投诉时间"
          width="200"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="220">
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
    <el-dialog
      title="将所选信息设置为"
      :visible.sync="dialogVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="处理情况">
          <el-radio-group v-model="setStatusVal">
            <el-radio label="0">未处理</el-radio>
            <el-radio label="1">已处理</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="funSetStatus">
          确 定
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { complaintList, complaintDelete, complaintHandler } from '@/api/complaint'
import { parseTime } from '@/utils/index'
import { getClassify } from '@/api/classify'

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger'
      }
      return statusMap[status]
    },
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      setStatusVal: '0',
      dialogVisible: false,
      tableIdarr: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      optionsType: [],
      type: '',
      status: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getClassify({ type: 'complaint' }).then(res => {
        this.optionsType = res.data
        const param = {
          status: this.status,
          type: this.type,
          page: this.currentPage,
          pagesize: this.pagesize
        }
        return complaintList(param)
      }).then(response => {
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
    funDelete(index, row) {
      var that = this
      that
        .$confirm('此操作将永久删除选中的数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          complaintDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funDeleteBatch(){
      var that = this
      if (that.tableIdarr.length == 0){
        that.$message.error('请选择要删除的信息')
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
          complaintDelete(param).then(response => {
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
      this.$router.push('/content/complaint/complaint/' + target)
    },
    funHandler() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要处理的数据')
        return false
      }
      this.dialogVisible = true
    },
    funSetStatus() {
      const params = {
        id: this.tableIdarr,
        status: this.setStatusVal
      }
      complaintHandler(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.fetchData()
          this.dialogVisible = false
          return true
        } else {
          this.$message.error(response.message)
          return false
        }
      })
    }
  }
}
</script>
