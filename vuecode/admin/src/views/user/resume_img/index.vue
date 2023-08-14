<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>照片/作品</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="audit"
          placeholder="不限审核状态"
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
      </div>
      <div class="spaceline" />
      <el-table
        v-loading="loading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="42" />
        <el-table-column label="作品" show-overflow-tooltip min-width="120">
          <template slot-scope="scope">
            <el-popover placement="right" trigger="hover">
              <img
                :src="scope.row.img_src"
                style="max-width: 500px; max-height: 400px"
              >
              <span slot="reference">
                <img :src="scope.row.img_src" width="50" height="50">
              </span>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="所属个人" min-width="100">
          <template slot-scope="scope">
            <el-link type="primary" target="_blank" :href="scope.row.link_url">{{ scope.row.fullname }}</el-link>
          </template>
        </el-table-column>
        <el-table-column label="联系方式" min-width="150">
          <template slot-scope="scope">
            {{ scope.row.mobile }}
          </template>
        </el-table-column>
        <el-table-column label="审核状态" min-width="80">
          <template slot-scope="scope">
            <el-tag :type="scope.row.audit | auditFilter">
              {{ options_audit[scope.row.audit] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="created_at"
          label="上传时间"
          min-width="150"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="150">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funAudit(scope.row)"
            >
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
          <el-button size="small" type="primary" @click="funAuditBatch">
            审核
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right">
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
      title="将所选图片设置为"
      :visible.sync="dialogFormVisible"
      width="25%"
    >
      <el-radio-group
        v-model="setAuditVal"
        style="margin-top:10px;margin-left:10px;"
      >
        <el-radio
          v-for="(item, index) in options_audit"
          :key="index"
          :label="index"
        >
          {{ item }}
        </el-radio>
      </el-radio-group>
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
import { resumeImgList, resumeImgAudit, resumeImgDel } from '@/api/resume'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    },
    auditFilter(audit) {
      const auditMap = {
        0: 'warning',
        1: 'success',
        2: 'danger'
      }
      return auditMap[audit]
    }
  },
  data() {
    return {
      loading: false,
      auditSubmitLoading: false,
      setAuditVal: 0,
      auditId: 0,
      dialogFormVisible: false,
      audit: '',
      options_audit: [],
      list: [],
      total: 0,
      currentPage: 1,
      pagesize: 10,
      tableIdarr: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.loading = true
      getClassify({
        type: 'resumeImgAudit'
      })
        .then(response => {
          const options_audit_arr = [...response.data]
          options_audit_arr.forEach(el => {
            this.options_audit[el.id] = el.name
          })
          const params = {
            audit: this.audit,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return resumeImgList(params)
        })
        .then(response => {
          this.list = [...response.data.items]
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
          this.loading = false
        })
    },
    funSearch() {
      this.fetchData()
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    funAudit(row) {
      this.setAuditVal = row.audit
      this.auditId = [row.id]
      this.dialogFormVisible = true
    },
    funAuditBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要审核的信息')
        return false
      }
      this.auditId = this.tableIdarr
      this.setAuditVal = 0
      this.dialogFormVisible = true
    },
    fun_set_audit() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      const params = {
        id: this.auditId,
        audit: this.setAuditVal
      }
      resumeImgAudit(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.auditSubmitLoading = false
          this.fetchData()
          this.closeDialog()
          return true
        } else {
          this.auditSubmitLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    funDelete(index, row) {
      var that = this
      that
        .$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: [row.id]
          }
          resumeImgDel(param).then(response => {
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
        that.$message.error('请选择要删除的信息')
        return false
      }
      that
        .$confirm('此操作将永久删除选中的信息, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          resumeImgDel(param).then(response => {
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
          this.tableIdarr.push(item.id)
        }
      }
    }
  }
}
</script>
