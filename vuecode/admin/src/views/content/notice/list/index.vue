<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>公告管理</span>
      </div>

      <div class="list-search">
        <el-select
          v-model="is_display"
          placeholder="不限显示状态"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限显示状态" value="" />
          <el-option label="显示" value="1" />
          <el-option label="隐藏" value="0" />
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
            <el-option label="公告ID" value="2" />
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
        <el-table-column align="center" label="ID" width="80">
          <template slot-scope="scope">
            {{ scope.row.id }}
          </template>
        </el-table-column>
        <el-table-column label="标题" show-overflow-tooltip min-width="300">
          <template slot-scope="scope">
            <el-link :href="scope.row.link" target="_blank" type="primary">
              {{ scope.row.title }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="排序" min-width="60">
          <template slot-scope="scope">
            <span>{{ scope.row.sort_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="点击" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.click }}</span>
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="created_at"
          label="添加日期"
          min-width="150"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="是否显示" width="100">
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.display"
              @change="modifyState(scope.row)">
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="150">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
              编辑
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
          <el-button size="small" type="primary" @click="goTo">
            添加公告
          </el-button>
          <el-button size="small" type="primary" @click="displayStatus">
            显示状态
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
    <el-dialog title="将显示状态设置为" :visible.sync="dialogFormVisible" width="27%">
      <el-form class="common-form" label-width="80px">
        <el-form-item label="显示状态">
          <el-radio-group v-model="display">
            <el-radio label="1">显示</el-radio>
            <el-radio label="0">隐藏</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_display">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { noticeList, noticeDelete, noticeModifyState } from '@/api/notice'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    displayFilter(is_display) {
      const displayMap = {
        1: 'success',
        0: 'danger'
      }
      return displayMap[is_display]
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
      is_display: '',
      key_type: '1',
      keyword: '',
      dialogFormVisible: false,
      display: '1'
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fun_set_display() {
      const that = this
      const param = {
        display: this.display,
        id: this.tableIdarr
      }
      noticeModifyState(param).then(response => {
        this.$message.success(response.message)
        this.fetchData()
        this.display = '1'
        that.dialogFormVisible = false
        return true
      }).catch(() => {})
    },
    displayStatus() {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要修改的数据')
        return false
      }
      that.dialogFormVisible = true
    },
    modifyState(row){
      const param = {
        display: row.display,
        id: [row.id]
      }
      noticeModifyState(param).then(response => {
        this.$message.success(response.message)
        this.fetchData()
        return true
      }).catch(() => {
      })
    },
    fetchData() {
      this.listLoading = true
      const param = {
        is_display: this.is_display,
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      noticeList(param).then(response => {
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
    funEdit(index, row) {
      this.$router.push({
        path: '/content/notice/list/edit',
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
          noticeDelete(param).then(response => {
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
          noticeDelete(param).then(response => {
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
    goTo() {
      this.$router.push('/content/notice/list/add')
    }
  }
}
</script>
