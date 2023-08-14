<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>广告位管理</span>
        <el-link
          href="http://doc.74cms.com/#/se/content?id=pc首页广告位分布图"
          target="_blank"
          style="float: right; margin: 3px 20px"
          type="primary"
        >查看广告位图例位置说明</el-link>
      </div>
      <div class="tip">
        <p>系统内置广告位不能删除！</p>
        <p>自定义广告位调用名不可以以 “QS_”开头</p>
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
        <el-table-column label="广告位名称" min-width="200">
          <template slot-scope="scope">
            [{{ scope.row.platform }}]{{ scope.row.name }}
          </template>
        </el-table-column>
        <el-table-column label="调用名称" min-width="150">
          <template slot-scope="scope">
            {{ scope.row.alias }}
          </template>
        </el-table-column>
        <el-table-column label="建议尺寸" min-width="80">
          <template slot-scope="scope">
            {{ scope.row.width }}*{{ scope.row.height }}
          </template>
        </el-table-column>
        <el-table-column label="广告数量" min-width="80" align="center">
          <template slot-scope="scope">
            {{ scope.row.ad_num }}
          </template>
        </el-table-column>
        <el-table-column label="类型" min-width="90" align="center">
          <template slot-scope="scope">
            <el-tag :type="scope.row.is_sys == 1 ? 'info' : ''">
              {{ scope.row.is_sys | sysFilter }}
            </el-tag>
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
              v-if="scope.row.is_sys == 0"
              size="small"
              type="danger"
              @click="funDelete(scope.$index, scope.row)"
            >
              删除
            </el-button>
            <el-button v-else size="small" type="danger" disabled>
              删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="goTo('add')">
            添加广告位
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除所选
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right">
          <el-pagination
            background
            :current-page="currentPage"
            :page-sizes="[10, 15, 20, 30, 40]"
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
import { categoryList, categoryDelete } from '@/api/ad_category'

export default {
  filters: {
    sysFilter(is_sys) {
      const sysMap = {
        1: '系统内置',
        0: '自定义'
      }
      return sysMap[is_sys]
    }
  },
  data() {
    return {
      tableIdarr: [],
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
      categoryList(param).then(response => {
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
        path: '/content/ad/category/edit',
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
          categoryDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => { })
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
          categoryDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => { })
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          if (item.is_sys == 0) {
            this.tableIdarr.push(item.id)
          }
        }
      }
    },
    goTo(target) {
      this.$router.push('/content/ad/category/add')
    }
  }
}
</script>
