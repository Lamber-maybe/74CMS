<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>职位管理</span>
      </div>
      <div class="list-search">
        <el-select
          v-if="listtype == 'all'"
          v-model="audit"
          placeholder="不限审核状态"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限审核状态" value="" />
          <el-option
            v-for="(item, index) in options_audit"
            :key="index"
            :label="item"
            :value="index"
          />
        </el-select>
        <el-select
          v-model="display"
          placeholder="招聘状态"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限招聘状态" value="" />
          <el-option
            v-for="(item, index) in options_display"
            :key="index"
            :label="item"
            :value="index"
          />
        </el-select>
        <el-select
          v-model="sort"
          placeholder="按添加时间排序"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="按添加时间排序" value="" />
          <el-option label="按刷新时间排序" value="refreshtime" />
        </el-select>
        <el-input
          v-model="keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
        >
          <el-select
            slot="prepend"
            v-model="key_type"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="职位名称" value="1" />
            <el-option label="公司名称" value="2" />
            <el-option label="职位ID" value="3" />
            <el-option label="公司ID" value="4" />
            <el-option label="会员ID" value="5" />
            <el-option label="会员手机号" value="6" />
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
        <el-table-column label="职位名称">
          <template slot-scope="scope">
            <el-link :href="scope.row.job_link" target="_blank" type="primary">
              {{ scope.row.jobname }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="公司名称">
          <template slot-scope="scope">
            <el-link
              :href="scope.row.company_link"
              target="_blank"
              type="primary"
            >
              {{ scope.row.companyname }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column align="center" label="审核状态" width="120">
          <template slot-scope="scope">
            <el-tag :type="scope.row.audit | auditFilter">
              {{ options_audit[scope.row.audit] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="刷新时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.refreshtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="招聘状态" width="120">
          <template slot-scope="scope">
            <el-tag :type="scope.row.is_display | displayFilter">
              {{ options_display[scope.row.is_display] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="goto('/user/job/edit?id=' + scope.row.id)"
            >
              编辑
            </el-button>
            <el-button size="small" type="warning" @click="funAudit(scope.row)">
              审核
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
          <el-button size="small" type="primary" @click="funRefresh">
            刷新
          </el-button>
          <el-button size="small" type="primary" @click="funExport">
            导出
          </el-button>
          <el-button size="small" type="warning" @click="funAuditBatch">
            审核
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
      title="将所选职位设置为"
      :visible.sync="dialogFormVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="认证状态">
          <el-radio-group v-model="setAuditVal">
            <el-radio
              v-for="(item, index) in options_audit"
              :key="index"
              :label="index"
            >
              {{ item }}
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="setAuditVal == 2" label="原因">
          <el-input v-model="setAuditReason" type="textarea" rows="3" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_audit">
          确 定
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { jobList, jobDelete, jobAudit, jobRefresh } from '@/api/job'
import { parseTime } from '@/utils/index'
import { exportJobById } from '@/api/export'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    },
    auditFilter(audit) {
      switch (audit) {
        case 0:
          return 'warning'
        case 1:
          return 'success'
        case 2:
          return 'danger'
        default:
          return 'info'
      }
    },
    displayFilter(display) {
      switch (display) {
        case 0:
          return 'danger'
        case 1:
          return 'success'
        default:
          return 'danger'
      }
    }
  },
  props: ['listtype'],
  data() {
    return {
      auditSubmitLoading: false,
      setAuditVal: 0,
      setAuditReason: '',
      auditIdarr: [],
      dialogFormVisible: false,
      tableIdarr: [],
      tableUidarr: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      display: '',
      audit: '',
      key_type: '1',
      keyword: '',
      sort: '',
      options_audit: [],
      options_display: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getClassify({ type: 'jobAudit,jobDisplay' })
        .then(response => {
          const options_audit_arr = [...response.data.jobAudit]
          options_audit_arr.forEach(el => {
            this.options_audit[el.id] = el.name
          })
          const options_display_arr = [...response.data.jobDisplay]
          options_display_arr.forEach(el => {
            this.options_display[el.id] = el.name
          })
          const list_type = this.listtype
          const param = {
            list_type,
            audit: this.audit,
            sort: this.sort,
            display: this.display,
            key_type: this.key_type,
            keyword: this.keyword,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return jobList(param)
        })
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
    },
    fetchListData() {
      this.listLoading = true
      const list_type = this.listtype
      const param = {
        list_type,
        audit: this.audit,
        display: this.display,
        sort: this.sort,
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      jobList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchListData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchListData()
    },
    funSearch() {
      this.fetchListData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchListData()
    },
    goto(target) {
      this.$router.push(target)
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
          jobDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchListData()
            return true
          })
        })
        .catch(() => {})
    },
    funDeleteBatch() {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要删除的职位')
        return false
      }
      that
        .$confirm('此操作将永久删除选中的职位, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          jobDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchListData()
            return true
          })
        })
        .catch(() => {})
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      this.tableUidarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
          this.tableUidarr.push(item.uid)
        }
      }
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    funAudit(row) {
      this.setAuditReason = ''
      this.setAuditVal = row.audit
      this.auditIdarr = [row.id]
      this.dialogFormVisible = true
    },
    funAuditBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要审核的职位')
        return false
      }
      this.auditIdarr = this.tableIdarr
      this.setAuditReason = ''
      this.setAuditVal = 0
      this.dialogFormVisible = true
    },
    fun_set_audit() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      const params = {
        id: this.auditIdarr,
        audit: this.setAuditVal,
        reason: this.setAuditReason
      }
      jobAudit(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.auditSubmitLoading = false
          this.fetchListData()
          this.closeDialog()
          return true
        } else {
          this.auditSubmitLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    funRefresh(){
      var that = this
      if (that.tableIdarr.length == 0){
        that.$message.error('请选择要刷新的职位')
        return false
      }
      const param = {
        id: that.tableIdarr,
        uid: that.tableUidarr
      }
      jobRefresh(param).then(response => {
        that.$message.success(response.message)
        that.fetchData()
      })
    },
    funExport(){
      var that = this
      if (that.tableIdarr.length == 0){
        that.$message.error('请选择要导出的职位')
        return false
      }
      const param = {
        id: that.tableIdarr
      }
      exportJobById(param).then(response => {
        if (response.code == 200) {
          that.exportExcel(response.data.items)
        } else {
          that.$message.error(response.message)
          that.waiting = false
        }
        return true
      })
    },
    exportExcel(list) {
      require.ensure([], () => {
        const { export_json_to_excel } = require('@/excel/Export2Excel')
        const tHeader = [
          '序号',
          '职位ID',
          '职位名称',
          '企业名称',
          '职位类别',
          '薪资待遇',
          '学历要求',
          '工作经验要求',
          '招聘人数',
          '所属部门',
          '年龄要求',
          '工作地区',
          '详细地址',
          '联系人',
          '联系手机',
          '联系座机',
          '联系人微信',
          '联系人qq',
          '联系人邮箱',
          '添加时间',
          '刷新时间',
          '审核状态',
          '招聘状态',
          '点击量'
        ] // 上面设置Excel的表格第一行的标题
        const filterVal = [
          'number',
          'id',
          'jobname',
          'companyname',
          'category',
          'wage',
          'education',
          'experience',
          'amount',
          'department',
          'age',
          'district',
          'address',
          'contact',
          'mobile',
          'telephone',
          'weixin',
          'qq',
          'email',
          'addtime',
          'refreshtime',
          'audit',
          'is_display',
          'click'
        ]
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '职位导出'
        )
      })
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]))
    }
  }
}
</script>
