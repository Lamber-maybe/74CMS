<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>举报信息</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="type"
          placeholder="举报类型：职位"
          class="list-options"
          @change="funSearchChangeType"
        >
          <el-option label="举报类型：职位" value="1" />
          <el-option label="举报类型：简历" value="2" />
        </el-select>
        <el-select
          v-model="reason"
          placeholder="不限举报原因"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限举报原因" value="" />
          <el-option v-for="(item,index) in optionsReason" :key="index" :label="item.name" :value="item.id" />
        </el-select>
        <el-select
          v-model="status"
          placeholder="不限核实情况"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限核实情况" value="" />
          <el-option label="属实" value="1" />
          <el-option label="不属实" value="2" />
          <el-option label="未核实" value="0" />
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
        <el-table-column type="selection" min-width="30" />
        <el-table-column label="举报对象" min-width="200">
          <template slot-scope="scope">
            <el-link :href="scope.row.link" target="_blank" type="primary">
              {{ scope.row.target }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="核实情况" min-width="100">
          <template slot-scope="scope">
            <el-tag :type="scope.row.status | statusFilter">
              {{ scope.row.status == 1 ? '属实' : (scope.row.status==2?'不属实':'未核实') }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="举报原因" min-width="250">
          <template slot-scope="scope">
            {{ scope.row.reason_cn }}
          </template>
        </el-table-column>
        <el-table-column label="举报内容" show-overflow-tooltip>
          <template slot-scope="scope">
            <span>{{ scope.row.content }}</span>
          </template>
        </el-table-column>
        <el-table-column label="举报者" min-width="200">
          <template slot-scope="scope">
            {{ scope.row.mobile }} 【uid：{{ scope.row.uid }}】
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="addtime"
          label="举报时间"
          min-width="200"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="220">
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
            核实
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
        <el-form-item label="核实情况">
          <el-radio-group v-model="setStatusVal">
            <el-radio label="0">未核实</el-radio>
            <el-radio label="1">属实</el-radio>
            <el-radio label="2">不属实</el-radio>
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
import { tipoffList, tipoffDelete, tipoffHandler } from '@/api/tipoff'
import { parseTime } from '@/utils/index'
import { getClassify } from '@/api/classify'

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger',
        2: 'info'
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
      optionsReason: [],
      type: '1',
      status: '',
      reason: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      var classify_type = 'tipoffJob'
      if (this.type == 2){
        classify_type = 'tipoffResume'
      }
      getClassify({ type: classify_type }).then(res => {
        this.optionsReason = res.data
        const param = {
          status: this.status,
          type: this.type,
          reason: this.reason,
          page: this.currentPage,
          pagesize: this.pagesize
        }
        return tipoffList(param)
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
    funSearchChangeType(){
      this.reason = ''
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
          tipoffDelete(param).then(response => {
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
          tipoffDelete(param).then(response => {
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
      this.$router.push('/content/feedback/tipoff/' + target)
    },
    funHandler() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要核实的数据')
        return false
      }
      this.dialogVisible = true
    },
    funSetStatus() {
      const params = {
        id: this.tableIdarr,
        status: this.setStatusVal
      }
      tipoffHandler(params, 'post').then(response => {
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
