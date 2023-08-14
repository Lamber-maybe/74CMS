<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>简历管理</span>
      </div>
      <div class="list-search">
        <el-select
          v-if="showOptionsAudit===true"
          v-model="audit"
          class="list-options"
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
        <el-select
          v-model="sort"
          class="list-options"
          placeholder="按刷新时间排序"
          @change="funSearch"
        >
          <el-option label="按刷新时间排序" value="" />
          <el-option label="按创建时间排序" value="addtime" />
        </el-select>
        <el-select
          v-model="level"
          class="list-options"
          placeholder="不限等级"
          @change="funSearch"
        >
          <el-option label="不限等级" value="" />
          <el-option label="优质简历" value="1" />
          <el-option label="普通简历" value="0" />
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
            <el-option label="姓名" value="1" />
            <el-option label="简历ID" value="2" />
            <el-option label="会员ID" value="3" />
            <el-option label="手机号" value="4" />
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
        <el-table-column label="姓名" width="150">
          <template slot-scope="scope">
            <div class="namecol">
              <el-popover
                placement="right"
                trigger="hover"
                width="300"
              >
                <img :src="scope.row.photo_img_src" style="max-width:274px;">
                <span slot="reference">
                  <span class="avatar">
                    <el-avatar :src="scope.row.photo_img_src" />
                  </span>
                </span>
              </el-popover>
              <el-link :href="scope.row.link" target="_blank" type="primary" class="text" :underline="false">
                {{ scope.row.fullname }}
              </el-link>
              <img v-if="scope.row.bind_weixin==1" style="vertical-align:middle;margin-left:4px;" :src="require('@/assets/images/wx_icon.png')">
            </div>
          </template>
        </el-table-column>
        <el-table-column label="基本信息" width="300">
          <template slot-scope="scope">
            <span>{{ scope.row.age }}</span>
            /
            <span>{{ scope.row.sex_cn }}</span>
            /
            <span>{{ scope.row.education_cn }}</span>
            /
            <span>{{ scope.row.experience_cn }}</span>
          </template>
        </el-table-column>
        <el-table-column label="简历完整度">
          <template slot-scope="scope">
            <el-progress
              :text-inside="true"
              :stroke-width="16"
              :percentage="scope.row.complete_percent"
              style="width:100px"
            />
          </template>
        </el-table-column>
        <el-table-column label="简历等级">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.high_quality==1">优质</el-tag>
            <el-tag v-if="scope.row.high_quality==0" type="info">普通</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="审核状态">
          <template slot-scope="scope">
            <el-tag :type="scope.row.audit | auditFilter">
              {{ options_audit[scope.row.audit] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="创建时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="刷新时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.refreshtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="280">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="funManagement(scope.row)">
              管理
            </el-button>
            <el-button
              size="small"
              type="primary"
              @click="goto('/user/resume/edit?id=' + scope.row.id)"
            >
              编辑
            </el-button>
            <el-button size="small" type="warning" @click="funAudit(scope.row)">
              审核
            </el-button>
            <el-dropdown trigger="click" style="margin-left: 10px">
              <el-button type="small">···</el-button>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  @click.native="funLog(scope.$index, scope.row)"
                >
                  日志
                </el-dropdown-item>
                <el-dropdown-item
                  @click.native="funComment(scope.$index,scope.row)"
                >
                  点评
                </el-dropdown-item>
                <el-dropdown-item
                  @click.native="funLevel(scope.row)"
                >
                  等级
                </el-dropdown-item>
                <el-dropdown-item
                  @click.native="funDelete(scope.row)"
                >
                  删除
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button
            size="small"
            type="primary"
            @click="goto('/user/resume/add')"
          >
            添加简历
          </el-button>
          <el-button size="small" type="primary" @click="funRefresh">
            刷新
          </el-button>
          <el-button size="small" type="primary" @click="funLevelBatch">
            等级
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
      title="将所选简历设置为"
      :visible.sync="dialogFormVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="审核状态">
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
    <el-dialog
      title="将所选简历设置为"
      :visible.sync="levelVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="简历等级">
          <el-radio-group v-model="setLevelVal">
            <el-radio :label="0">普通简历</el-radio>
            <el-radio :label="1">优质简历</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="levelVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_level">
          确 定
        </el-button>
      </div>
    </el-dialog>
    <el-dialog
      v-if="dialogListVisible"
      title="会员日志"
      :visible.sync="dialogListVisible"
      width="50%"
      :close-on-click-modal="false"
      @close="closeListDialog"
    >
      <MemberLog :uid="listUid" @setDialogFormVisible="closeListDialog" />
    </el-dialog>
    <el-dialog
      title="简历点评"
      :visible.sync="commentVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="点评内容">
          <el-input v-model="commentVal" type="textarea" rows="3" maxlength="100" show-word-limit />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="commentVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_comment">
          确 定
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import MemberLog from '@/components/MemberLog/Index'
import { getClassify } from '@/api/classify'
import { resumeList, resumeAudit, resumeLevel, resumeComment, resumeDelete, resumeRefresh } from '@/api/resume'
import { management } from '@/api/member'
import { parseTime, setMemberLogin } from '@/utils/index'

export default {
  components: {
    MemberLog
  },
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
          return ''
      }
    }
  },
  props: ['listtype', 'showOptionsAudit'],
  data() {
    return {
      auditSubmitLoading: false,
      levelSubmitLoading: false,
      setLevelVal: 0,
      setAuditVal: 0,
      setAuditReason: '',
      levelIdarr: [],
      auditIdarr: [],
      commentVisible: false,
      levelVisible: false,
      dialogFormVisible: false,
      tableIdarr: [],
      tableUidarr: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      audit: '',
      sort: '',
      level: '',
      options_audit: [],
      dialogListVisible: false,
      listUid: 0,
      commentIndex: 0,
      commentId: 0,
      commentVal: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getClassify({ type: 'resumeAudit' })
        .then(response => {
          const options_audit_arr = [...response.data]
          options_audit_arr.forEach(el => {
            this.options_audit[el.id] = el.name
          })
          const list_type = this.listtype
          const param = {
            list_type,
            key_type: this.key_type,
            keyword: this.keyword,
            audit: this.audit,
            sort: this.sort,
            level: this.level,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return resumeList(param)
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
        key_type: this.key_type,
        keyword: this.keyword,
        audit: this.audit,
        sort: this.sort,
        level: this.level,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      resumeList(param).then(response => {
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
    funComment(index, row){
      this.commentIndex = index
      this.commentVal = row.comment
      this.commentId = row.id
      this.commentVisible = true
    },
    funLevel(row) {
      this.setLevelVal = row.high_quality
      this.levelIdarr = [row.id]
      this.levelVisible = true
    },
    funLevelBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要设置的简历')
        return false
      }
      this.setLevelVal = 0
      this.levelIdarr = this.tableIdarr
      this.levelVisible = true
    },
    funAudit(row) {
      this.setAuditReason = ''
      this.setAuditVal = row.audit
      this.auditIdarr = [row.id]
      this.dialogFormVisible = true
    },
    funAuditBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要审核的简历')
        return false
      }
      this.auditIdarr = this.tableIdarr
      this.setAuditVal = 0
      this.setAuditReason = ''
      this.dialogFormVisible = true
    },
    fun_set_comment() {
      const params = {
        id: this.commentId,
        comment: this.commentVal
      }
      resumeComment(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.list[this.commentIndex].comment = this.commentVal
          this.commentVisible = false
          return true
        } else {
          this.$message.error(response.message)
          return false
        }
      })
    },
    fun_set_level() {
      if (this.levelSubmitLoading == true) {
        return false
      }
      this.levelSubmitLoading = true
      const params = {
        id: this.levelIdarr,
        level: this.setLevelVal
      }
      resumeLevel(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.levelSubmitLoading = false
          this.fetchListData()
          this.levelVisible = false
          return true
        } else {
          this.levelSubmitLoading = false
          this.$message.error(response.message)
          return false
        }
      })
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
      resumeAudit(params, 'post').then(response => {
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
    funManagement(row){
      const params = {
        uid: row.uid
      }
      management(params).then(response => {
        if (response.code == 200) {
          setMemberLogin(response.data)
          window.open(this.$store.state.config.sitedomain + this.$store.state.config.sitedir + this.$store.state.config.member_dirname)
          return true
        } else {
          this.$message.error(response.message)
          return false
        }
      })
    },
    funLog(index, row){
      this.listUid = row.uid
      this.dialogListVisible = true
    },
    closeListDialog() {
      this.dialogListVisible = false
    },
    funDelete(row){
      var that = this
      that
        .$confirm('此操作将永久删除该简历, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: [row.uid]
          }
          resumeDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funDeleteBatch(){
      var that = this
      if (that.tableUidarr.length == 0){
        that.$message.error('请选择要删除的简历')
        return false
      }
      that
        .$confirm('此操作将永久删除该简历, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: that.tableUidarr
          }
          resumeDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funRefresh(){
      var that = this
      if (that.tableIdarr.length == 0){
        that.$message.error('请选择要刷新的简历')
        return false
      }
      const param = {
        id: that.tableIdarr
      }
      resumeRefresh(param).then(response => {
        that.$message.success(response.message)
        that.fetchData()
      })
    }
  }
}
</script>
<style scoped>
.namecol{
  position:relative;
  padding-left:40px;
}
.namecol .avatar{
  width:42px;
  height:42px;
  padding:1px;
  display: inline-block;
  border: 0;
  border-radius: 30px;
  position: absolute;
  top: 4px;
  left: -10px;
}
.namecol .text{
  height:50px;
}
</style>
