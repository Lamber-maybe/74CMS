<template>
  <div
    v-loading="importLoading"
    class="app-container"
    :element-loading-text="importText"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>简历管理</span>
      </div>
      <div class="list-search">
        <el-select
          v-if="showOptionsAudit === true"
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
          @keyup.enter.native="funSearchKeyword"
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
        <el-table-column label="姓名" width="300">
          <template slot-scope="scope">
            <div class="namecol">
              <el-popover placement="right" trigger="hover" width="300">
                <img :src="scope.row.photo_img_src" style="max-width: 274px" />
                <span slot="reference">
                  <span class="avatar">
                    <el-avatar :src="scope.row.photo_img_src" />
                  </span>
                </span>
              </el-popover>
              <span>
                <div>
                  <el-link
                    :href="scope.row.link"
                    target="_blank"
                    type="primary"
                    class="text"
                    :underline="false"
                  >
                    {{ scope.row.fullname }}
                  </el-link>
                  <img
                    v-if="scope.row.bind_weixin == 1"
                    style="vertical-align: middle; margin-left: 4px"
                    :src="require('@/assets/images/wx_icon.png')"
                  />
                </div>
                <div>
                  <span>{{ scope.row.age }}</span>
                  ·
                  <span>{{ scope.row.sex_cn }}</span>
                  ·
                  <span>{{ scope.row.education_cn }}</span>
                  ·
                  <span>{{ scope.row.experience_cn }}</span>
                </div>
              </span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="会员/简历手机号" width="250">
          <template slot-scope="scope">
            <div title="会员手机号">
              <i class="el-icon-user"></i>&nbsp;{{ scope.row.mobile }}
            </div>
            <div title="简历联系手机号">
              <i class="el-icon-document"></i>&nbsp;{{
                scope.row.contact_mobile
              }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="简历完整度">
          <template slot-scope="scope">
            <el-progress
              :text-inside="true"
              :stroke-width="16"
              :percentage="scope.row.complete_percent"
              style="width: 100px"
            />
          </template>
        </el-table-column>
        <el-table-column label="简历等级">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.high_quality == 1">优质</el-tag>
            <el-tag v-if="scope.row.high_quality == 0" type="info">普通</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="审核状态">
          <template slot-scope="scope">
            <el-tag :type="scope.row.audit | auditFilter">
              {{ options_audit[scope.row.audit] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="创建/刷新时间" width="150">
          <template slot-scope="scope">
            <div title="创建时间">
              <i class="el-icon-time" />{{ scope.row.addtime | timeFilter }}
            </div>
            <div title="刷新时间">
              <i class="el-icon-time" />{{ scope.row.refreshtime | timeFilter }}
            </div>
          </template>
        </el-table-column>
        <el-table-column align="center" label="推广">
          <template slot-scope="scope">
            <el-button type="text" @click="funPoster(scope.row.id)"
              >[海报]</el-button
            >
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="280">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funManagement(scope.row)"
            >
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
                  @click.native="funComment(scope.$index, scope.row)"
                >
                  点评
                </el-dropdown-item>
                <el-dropdown-item @click.native="funLevel(scope.row)">
                  等级
                </el-dropdown-item>
                <el-dropdown-item @click.native="funDelete(scope.row)">
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
          <el-button size="small" type="primary" @click="openDialog">
            导入简历
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
        <el-button type="primary" @click="fun_set_audit"> 确 定 </el-button>
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
        <el-button type="primary" @click="fun_set_level"> 确 定 </el-button>
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
    <el-dialog title="简历点评" :visible.sync="commentVisible" width="25%">
      <el-form class="common-form" label-width="80px">
        <el-form-item label="点评内容">
          <el-input
            v-model="commentVal"
            type="textarea"
            rows="3"
            maxlength="100"
            show-word-limit
          />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="commentVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_comment"> 确 定 </el-button>
      </div>
    </el-dialog>
    <Poster
      v-if="showPoster"
      :poster-id="posterId"
      :poster-type="posterType"
      @closeDialog="showPoster = false"
    />
    <el-dialog
      v-if="showUpload"
      title="导入简历"
      :visible.sync="showUpload"
      :close-on-click-modal="false"
      width="30%"
    >
      <el-form ref="form" label-width="120px" size="small">
        <el-form-item label="上传文件">
          <input
            style="width: 200px"
            class="input-file"
            type="file"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            @change="exportData"
          />
          <span class="smalltip">
            <i class="el-icon-info" />
            <el-button type="text" @click="downTpl">[模板下载]</el-button>
          </span>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="doUpload">确定</el-button>
          <el-button @click="showUpload = false">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
import XLSX from 'xlsx'
import MemberLog from '@/components/MemberLog/Index'
import { getClassify } from '@/api/classify'
import { resumeList, resumeAudit, resumeLevel, resumeComment, resumeDelete, resumeRefresh, resumeImport } from '@/api/resume'
import { management } from '@/api/member'
import { parseTime, setMemberLogin } from '@/utils/index'
import Poster from '@/components/Poster'

export default {
  components: {
    MemberLog,
    Poster
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
      importText: '正在导入，请稍候',
      importLoading: false,
      showUpload: false,
      excelData: [],
      showPoster: false,
      posterId: '',
      posterType: '',
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
    funComment(index, row) {
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
    funManagement(row) {
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
    funLog(index, row) {
      this.listUid = row.uid
      this.dialogListVisible = true
    },
    closeListDialog() {
      this.dialogListVisible = false
    },
    funDelete(row) {
      var that = this
      that
        .$confirm('删除简历将同步删除该会员账号及简历，删除后不可恢复, 是否继续?', '提示', {
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
        .catch(() => { })
    },
    funDeleteBatch() {
      var that = this
      if (that.tableUidarr.length == 0) {
        that.$message.error('请选择要删除的简历')
        return false
      }
      that
        .$confirm('删除简历将同步删除该会员账号及简历，删除后不可恢复, 是否继续?', '提示', {
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
        .catch(() => { })
    },
    funRefresh() {
      var that = this
      if (that.tableIdarr.length == 0) {
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
    },
    funPoster(id) {
      this.showPoster = true
      this.posterId = id
      this.posterType = 'resume'
    },
    exportData(event) {
      if (!event.currentTarget.files.length) {
        return
      }
      const that = this
      // 拿取文件对象
      var f = event.currentTarget.files[0]
      // 用FileReader来读取
      var reader = new FileReader()
      // 重写FileReader上的readAsBinaryString方法
      FileReader.prototype.readAsBinaryString = function (f) {
        var binary = ''
        var wb // 读取完成的数据
        var outdata // 你需要的数据
        var reader = new FileReader()
        reader.onload = function () {
          // 读取成Uint8Array，再转换为Unicode编码（Unicode占两个字节）
          var bytes = new Uint8Array(reader.result)
          var length = bytes.byteLength
          for (let i = 0; i < length; i++) {
            binary += String.fromCharCode(bytes[i])
          }
          // 接下来就是xlsx了，具体可看api
          wb = XLSX.read(binary, {
            type: 'binary',
            cellDates: true
          })
          const sheet2JSONOpts = {
            header: 1,
            /** Default value for null/undefined values */
            defval: ''// 给defval赋值为空的字符串
          }
          const outdataAll = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], sheet2JSONOpts)
          outdata = outdataAll.filter(item => item[0] != '')
          outdata.splice(0, 1)
          that.excelData = []
          for (let i = 0; i < outdata.length; i++) {
            const outputs = {
              'basic': {},
              'intention_list': {},
              'education_list': {},
              'work_list': {},
              'language_list': {},
              'certificate_list': {}
            } // 清空接收数据
            // console.log(ws[i])
            const sheetBasic = {
              'fullname': outdata[i][0],
              'sex': outdata[i][1] === '男' ? 1 : 2,
              'birthday': that.dateFormat('YYYY-mm-dd', outdata[i][2]),
              'residence': outdata[i][17],
              'height': outdata[i][3],
              'marriage': outdata[i][5],
              'education': outdata[i][7],
              'enter_job_time': outdata[i][6],
              'householdaddress': outdata[i][4],
              'specialty': outdata[i][18],
              'addtime': outdata[i][19],
              'platform': outdata[i][20],
              'contact': {
                'mobile': outdata[i][15],
                'email': outdata[i][16]
              }
            }
            let sheetIntentionList = []
            if (outdata[i][8]) {
              sheetIntentionList = sheetIntentionList.concat(that.handleCellData(outdata[i][8], 'intention'))
            }
            if (outdata[i][9]) {
              sheetIntentionList = sheetIntentionList.concat(that.handleCellData(outdata[i][9], 'intention'))
            }
            if (outdata[i][10]) {
              sheetIntentionList = sheetIntentionList.concat(that.handleCellData(outdata[i][10], 'intention'))
            }
            const sheetEducationList = that.handleCellData(outdata[i][11], 'education')
            const sheetWorkList = that.handleCellData(outdata[i][12], 'work')
            const sheetLanguageList = that.handleCellData(outdata[i][13], 'language')
            const sheetCertificateList = that.handleCellData(outdata[i][14], 'certificate')
            outputs.basic = sheetBasic
            outputs.intention_list = sheetIntentionList
            outputs.education_list = sheetEducationList
            outputs.work_list = sheetWorkList
            outputs.language_list = sheetLanguageList
            outputs.certificate_list = sheetCertificateList
            that.excelData.push(outputs)
          }
        }
        reader.readAsArrayBuffer(f)
      }
      reader.readAsBinaryString(f)
    },
    // 根据汉字获取分类id
    // 处理数据
    handleCellData(data, type) {
      const that = this
      const returnArray = []
      if (type === 'intention') {
        const valArray = data.split('|')
        returnArray.push(
          {
            'nature': valArray[0],
            'category': valArray[1],
            'district': valArray[2],
            'minwage': valArray[3].split('-')[0],
            'maxwage': valArray[3].split('-')[1],
            'trade': valArray[4]
          }
        )
      } else {
        if (data != '') {
          const listArray = data.split('【#】')
          listArray.forEach(function (val, index, arr) {
            const valArray = val.split('|')
            if (type === 'education') {
              returnArray.push(
                {
                  'starttime': that.handleDate(valArray[0])[0],
                  'endtime': that.handleDate(valArray[0])[1],
                  'school': valArray[1],
                  'major': valArray[2],
                  'education': valArray[3]
                }
              )
            }
            if (type === 'work') {
              returnArray.push(
                {
                  'starttime': that.handleDate(valArray[0])[0],
                  'endtime': that.handleDate(valArray[0])[1],
                  'companyname': valArray[1],
                  'jobname': valArray[2],
                  'duty': valArray[3]
                }
              )
            }
            if (type === 'language') {
              returnArray.push(
                {
                  'language': valArray[0],
                  'level': valArray[1]
                }
              )
            }
            if (type === 'certificate') {
              returnArray.push(
                {
                  'name': valArray[0],
                  'obtaintime': valArray[1].split('/')[0] + '-' + valArray[1].split('/')[1]
                }
              )
            }
          })
        }
      }
      return returnArray
    },
    // 处理日期
    handleDate(date) {
      const dateArray = date.split('-')
      dateArray[0] = dateArray[0].split('/')[0] + '-' + dateArray[0].split('/')[1]
      dateArray[1] = dateArray[1].split('/')[0] + '-' + dateArray[1].split('/')[1]
      return dateArray
    },
    dateFormat(fmt, date) {
      let ret
      const opt = {
        'Y+': date.getFullYear().toString(), // 年
        'm+': (date.getMonth() + 1).toString(), // 月
        'd+': date.getDate().toString(), // 日
        'H+': date.getHours().toString(), // 时
        'M+': date.getMinutes().toString(), // 分
        'S+': date.getSeconds().toString() // 秒
        // 有其他格式化字符需求可以继续添加，必须转化成字符串
      }
      for (const k in opt) {
        ret = new RegExp('(' + k + ')').exec(fmt)
        if (ret) {
          fmt = fmt.replace(ret[1], (ret[1].length == 1) ? (opt[k]) : (opt[k].padStart(ret[1].length, '0')))
        }
      }
      return fmt
    },
    doUpload() {
      const that = this
      if (that.excelData.length <= 0) {
        that.$message.error('没有读取到数据，请重新核对、上传')
        return false
      }
      const pagesize = 20
      const totalPage = Math.ceil(that.excelData.length / pagesize)
      that.importLoading = true
      that.showUpload = false
      that.loopImport(1, pagesize, totalPage)
    },
    loopImport(page, pagesize, totalPage) {
      const that = this
      const start = (page - 1) * pagesize
      const end = start + pagesize
      const newarr = that.excelData.slice(start, end)
      that.importText = '正在导入第' + start + '-' + end + '条，请稍候'
      resumeImport(newarr).then(response => {
        if (page >= totalPage) {
          that.importLoading = false
          that.$message({ type: 'success', message: '导入成功' })
          that.fetchData(true)
        } else {
          page++
          that.loopImport(page, pagesize, totalPage)
        }
      })
    },
    downTpl() {
      location.href = window.global.RequestBaseUrl + apiArr.downloadImportResumeTpl + '?admintoken=' + getToken()
    },
    openDialog() {
      this.showUpload = true
    }
  }
}
</script>
<style scoped>
.namecol {
  position: relative;
  padding-left: 40px;
}
.namecol .avatar {
  width: 42px;
  height: 42px;
  padding: 1px;
  display: inline-block;
  border: 0;
  border-radius: 30px;
  position: absolute;
  top: 4px;
  left: -10px;
}
</style>
