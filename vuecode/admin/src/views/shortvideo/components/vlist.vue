<template>
  <div class="app-container">
    <div class="list-search">
      <el-select
        v-model="form.audit"
        class="list-options"
        placeholder="审核状态"
        @change="funSearch"
      >
        <el-option label="不限审核状态" value="0" />
        <el-option label="未审核" value="1" />
        <el-option label="已审核" value="2" />
        <el-option label="未通过" value="3" />
      </el-select>
      <el-select
        v-model="form.is_public"
        class="list-options"
        placeholder="展示状态"
        @change="funSearch"
      >
        <el-option label="不限展示状态" value="0" />
        <el-option label="不展示" value="1" />
        <el-option label="展示中" value="2" />
      </el-select>
      <el-input
        v-model="form.keyword"
        class="input-with-select"
        placeholder="请输入搜索内容"
      >
        <el-select
          slot="prepend"
          v-model="form.key_type"
          class="input-sel"
          placeholder="请选择"
        >
          <el-option label="标题" value="1" />
          <el-option label="UID" value="2" />
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
      <el-table-column v-if="vtype==1" label="公司名称" prop="companyname" show-overflow-tooltip min-width="150" />
      <el-table-column v-if="vtype==2" label="姓名" prop="fullname" show-overflow-tooltip min-width="150" />
      <el-table-column label="标题" prop="title" show-overflow-tooltip min-width="150" />
      <el-table-column align="center" label="视频预览" prop="video_src" show-overflow-tooltip min-width="100">
        <template slot-scope="scope">
          <span slot="reference" class="more" @click="preview(scope.row)">[预览]</span>
        </template>
      </el-table-column>
      <el-table-column label="浏览量" align="center" show-overflow-tooltip prop="view_count" min-width="80" />
      <el-table-column label="文件大小" align="center" show-overflow-tooltip prop="filesize" min-width="80">
        <template slot-scope="scope">
          <span v-html="fmtSize(scope.row.filesize)" />
        </template>
      </el-table-column>
      <el-table-column align="center" label="审核状态" prop="status" show-overflow-tooltip min-width="100">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.audit==1" type="warning" v-html="auditFmt(scope.row)" />
          <el-tag v-if="scope.row.audit==2" type="success" v-html="auditFmt(scope.row)" />
          <el-tag v-if="scope.row.audit==3" type="danger" v-html="auditFmt(scope.row)" />
        </template>
      </el-table-column>

      <el-table-column label="公开状态" align="center" show-overflow-tooltip prop="is_public" min-width="90">
        <template slot-scope="scope">
          <span v-if="scope.row.is_public==1">关闭</span>
          <span v-if="scope.row.is_public==2">开启</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="发布日期" prop="addtime" min-width="160" show-overflow-tooltip />
      <el-table-column align="center" fixed="right" label="操作" prop="title" min-width="150">
        <template slot-scope="scope">
          <el-button size="small" type="warning" @click="doCheckOne(scope.row)">审核</el-button>
          <el-button size="small" type="danger" @click="doDel(scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-row :gutter="20">
      <el-col :span="8">
        <el-button size="small" type="warning" @click="doCheck">
          审核
        </el-button>
        <el-button size="small" type="danger" @click="doDel">
          删除所选
        </el-button>
      </el-col>
      <el-col :span="16" style="text-align: right;">
        <el-pagination
          :current-page="form.page"
          :page-size="form.pagesize"
          :page-sizes="[10, 20, 50, 100]"
          :total="total"
          background
          layout="total, sizes, prev, pager, next, jumper"
          @current-change="handleCurrentChange"
          @size-change="handleSizeChange"
        />
      </el-col>
    </el-row>
    <el-dialog
      :visible.sync="dialogFormVisible"
      title="将所选信息设置为"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="审核状态">
          <el-radio-group v-model.number="setAuditVal">
            <el-radio
              v-for="(item, index) in options_audit"
              :key="index"
              :label="parseInt(index)"
            >
              {{ item }}
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="setAuditVal == 3" label="原因">
          <el-input v-model="setAuditReason" rows="3" type="textarea" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="doSetAuth">
          确 定
        </el-button>
      </div>
    </el-dialog>
    <el-dialog
      :visible.sync="previewShow"
      width="354px"
    >
      <div slot="title" style="margin-right:20px;" v-html="previewCur.title" />
      <video style="width:314px;max-height:551px;" autoplay :src="previewCur.video_src" controls />
    </el-dialog>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import { videoList, videoAudit, videoDel } from '@/api/shortvideo'

export default {
  props: ['vtype'],
  data() {
    return {
      form: {
        audit: '0',
        is_public: '0',
        type: 1,
        key_type: '1',
        keyword: '',
        page: 1,
        pagesize: 10
      },
      previewShow: false,
      previewCur: {},
      total: 0,
      list: [],
      listLoading: false,
      tableIdarr: [],
      options_audit: {
        1: '未审核',
        2: '已审核',
        3: '审核未通过'
      },
      setAuditVal: 0,
      setAuditReason: '',
      dialogFormVisible: false,
      curId: '',
      auditSubmitLoading: false,
      topSubmitLoading: false
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    preview(video) {
      this.previewShow = true
      this.previewCur = video
    },
    fmtSize(size){
      let s = size / 1024 / 1024
      s = s.toFixed(2)
      return s + 'M'
    },
    doCheck() {
      if (this.tableIdarr.length == 0) {
        return this.$message.error('请选择信息')
      }
      this.curId = false
      this.setAuditVal = 1
      this.dialogFormVisible = true
    },
    doCheckOne(row) {
      this.dialogFormVisible = true
      this.curId = row.id
      this.setAuditVal = 2
    },
    doSetAuth() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      const params = {
        id: this.curId ? [this.curId] : this.tableIdarr,
        type: this.vtype,
        audit: this.setAuditVal,
        reason: this.setAuditReason
      }
      videoAudit(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.auditSubmitLoading = false
          this.getList()
          this.dialogFormVisible = false
          return true
        } else {
          this.auditSubmitLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    doDel(row) {
      if (row) this.curId = row.id
      var that = this
      this.$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          const param = {
            id: (that.curId ? [that.curId] : that.tableIdarr),
            type: this.vtype
          }
          videoDel(param).then(response => {
            that.$message.success(response.message)
            that.getList()
            return true
          })
        })
        .catch(() => {
        })
    },
    handleSizeChange: function (val) {
      this.form.pagesize = val
      this.getList()
    },
    handleCurrentChange: function (val) {
      this.form.page = val
      this.getList()
    },
    dateFmt(time) {
      return time > 0 ? this.timeFilter(time) : '-'
    },
    auditFmt(row) {
      return this.options_audit[row.audit]
    },
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    },
    getList: function () {
      this.form.type = this.vtype
      this.listLoading = true
      const param = Object.assign({}, this.form)
      videoList(param).then(response => {
        this.list = response.data.list
        this.listLoading = false
        this.page = response.data.page
        this.pagesize = response.data.pagesize
        this.total = response.data.total
      })
    },
    funSearch: function () {
      this.getList()
    },
    funSearchKeyword: function () {
      this.currentPage = 1
      this.getList()
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

<style scoped>
  .el-cascader {
    margin-right: 10px;
  }
  .more{
    color: #1787fb;
    cursor: pointer;
  }
</style>
