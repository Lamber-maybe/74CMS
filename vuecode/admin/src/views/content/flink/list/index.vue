<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>友情链接</span>
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
            <el-option label="名称" value="1" />
            <el-option label="ID" value="2" />
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
        <el-table-column label="名称" min-width="250">
          <template slot-scope="scope">
            {{ scope.row.name }}
          </template>
        </el-table-column>
        <el-table-column label="网址" min-width="300">
          <template slot-scope="scope">
            {{ scope.row.link_url }}
          </template>
        </el-table-column>
        <el-table-column label="排序" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.sort_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="备注" show-overflow-tooltip min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.notes }}</span>
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
        <el-table-column fixed="right" label="操作" min-width="160">
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
          <el-button size="small" type="primary" @click="goTo('add')">
            添加
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
import { flinkList, flinkDelete, flinkModifyState } from '@/api/flink'

export default {
  filters: {
    displayFilter(is_display) {
      const displayMap = {
        1: 'success',
        0: 'danger'
      }
      return displayMap[is_display]
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
      flinkCategory: [],
      flinkCategoryLoading: true,
      is_display: '',
      key_type: '1',
      keyword: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    modifyState(row){
      const that = this
      const param = {
        display: row.display,
        id: row.id
      }
      flinkModifyState(param).then(response => {
        this.$message.success(response.message)
        setTimeout(function() {
          that.$router.push('/content/flink/list')
        }, 1500)
        return true
      }).catch(() => {
        that.issubmit = false
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
      flinkList(param).then(response => {
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
      this.cid = '0'
      this.currentPage = 1
      this.fetchData()
    },
    funEdit(index, row) {
      this.$router.push({
        path: '/content/flink/list/edit',
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
          flinkDelete(param).then(response => {
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
          flinkDelete(param).then(response => {
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
      this.$router.push('/content/flink/list/' + target)
    }
  }
}
</script>
