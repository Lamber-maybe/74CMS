<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>分站管理</span>
      </div>
      <div class="tip">
        <p>本分站架构仅支持以地区形式对网站信息内容进行区分显示，如您需要更丰富的分站模式请联系客服为您专业定制开发。</p>
      </div>
      <el-form
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        label-width="120px"
        :inline-message="true"
      >
        <el-form-item label="是否开启">
          <el-radio-group v-model="form.subsite_open">
            <el-radio :label="1">开启</el-radio>
            <el-radio :label="0">不开启</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit()">保存</el-button>
        </el-form-item>
      </el-form>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="分站名称" prop="sitename" min-width="150" />
        <el-table-column label="绑定地区" prop="district_text" min-width="150" />
        <el-table-column label="模板" prop="tpl" min-width="150" />
        <el-table-column label="是否显示" prop="is_display" min-width="100">
          <template slot-scope="scope">
            <el-tag :type="scope.row.is_display | displayFilter">
              {{ scope.row.is_display == 1 ? '显示' : '隐藏' }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="排序" prop="sort_id" min-width="80" />
        <el-table-column fixed="right" label="操作" width="220">
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
          <el-button size="small" type="primary" @click="$router.push('/subsite/add')">
            新增
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
import { setConfig } from '@/api/configuration'
import { subsiteList, subsiteDelete } from '@/api/subsite'
export default {
  filters: {
    displayFilter(is_display) {
      const displayMap = {
        1: 'success',
        0: 'info'
      }
      return displayMap[is_display]
    }
  },
  data () {
    return {
      infoLoading: false,
      form: {
        subsite_open: 0
      },
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10
    }
  },
  created() {
    this.fetchInfo()
    this.fetchData()
  },
  methods: {
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    fetchData () {
      this.listLoading = true
      const param = {
        page: this.currentPage,
        pagesize: this.pagesize
      }
      subsiteList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    funEdit (index, row) {
      this.$router.push({
        path: '/subsite/edit',
        query: {
          id: row.id
        }
      })
    },
    funDelete (index, row) {
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
          subsiteDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    onSubmit () {
      const insertData = { ...this.form }
      setConfig(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    },
    fetchInfo () {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            subsite_open
          } = { ...response.data }
          this.form.subsite_open = parseInt(subsite_open)
          this.infoLoading = false
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped>
</style>

