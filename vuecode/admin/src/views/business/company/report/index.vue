<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>实地认证</span>
      </div>
      <div class="list-search">
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
            <el-option label="企业ID" value="1" />
            <el-option label="企业名称" value="2" />
            <el-option label="会员UID" value="3" />
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
      >
        <el-table-column label="企业名称" prop="companyname" min-width="200" />
        <el-table-column label="联系人" min-width="200">
          <template slot-scope="scope">
            {{ scope.row.contact }}({{ scope.row.mobile }})
          </template>
        </el-table-column>
        <el-table-column align="center" label="认证时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="240">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="funEdit(scope.$index, scope.row)">
              编辑
            </el-button>
            <el-button
              size="small"
              type="primary"
              @click="funPreview(scope.row)"
            >
              预览
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
import { companyReportList, companyReportDelete } from '@/api/company_report'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data() {
    return {
      dialogFormVisible: false,
      dialogContent: {
        oid: '',
        service_name: '',
        service_amount: '',
        service_amount_after_discount: '',
        amount: '',
        deduct_amount: '',
        payment: '',
        addtime: '',
        paytime: '',
        status: '',
        amount_detail: ''
      },
      status: '',
      payment: '',
      service_type: '',
      add_settr: '',
      pay_settr: '',
      options_status: [],
      options_payment: [],
      options_service_type: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      companyReportList(param)
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => {})
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
    goTo(target) {
      this.$router.push('/business/company/report/' + target)
    },
    funEdit(index, row) {
      this.$router.push({
        path: '/business/company/report/edit',
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
          companyReportDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funPreview(row){
      window.open(row.preview_link)
    }
  }
}
</script>
<style scoped>
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
</style>
