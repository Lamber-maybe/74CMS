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
          <el-option label="按修改时间排序" value="updatetime" />
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
        <el-table-column label="职位名称" min-width="200">
          <template slot-scope="scope">
            <div>
              <el-link
                :href="scope.row.job_link"
                target="_blank"
                type="primary"
              >
                {{ scope.row.jobname }}
              </el-link>
            </div>
            <div>
              <el-link :href="scope.row.company_link" target="_blank">
                {{ scope.row.companyname }}
              </el-link>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="会员/职位手机号" min-width="130">
          <template slot-scope="scope">
            <div title="会员手机号">
              <i class="el-icon-user" />
              &nbsp;{{ scope.row.member_mobile }}
              <div v-if="scope.row.member_mobile != '' && scope.row.member_mobile != null" class="dial" title="拨打会员手机号">
                <div class="disal_img" @click="clickDial(scope.row.mobile, scope.row.companyname )" />
              </div>
            </div>
            <div title="职位联系手机号">
              <i class="el-icon-document" />
              &nbsp;{{ scope.row.mobile ? scope.row.mobile : "-" }}
              <div v-if="scope.row.mobile != '' && scope.row.mobile != null" class="dial" title="拨打职位联系手机号">
                <div class="disal_img" @click="clickDial(scope.row.mobile, scope.row.companyname )" />
              </div>
            </div>
          </template>
        </el-table-column>
        <el-table-column align="center" label="审核状态" min-width="100">
          <template slot-scope="scope">
            <el-tag :type="scope.row.audit | auditFilter">
              {{ options_audit[scope.row.audit] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="创建/刷新时间" min-width="150">
          <template slot-scope="scope">
            <div title="创建时间">
              <i class="el-icon-time" />{{ scope.row.addtime | timeFilter }}
            </div>
            <div title="刷新时间">
              <i class="el-icon-time" />{{ scope.row.refreshtime | timeFilter }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="职位状态" width="100">
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.display"
              @change="modifyState(scope.row)"
            />
          </template>
        </el-table-column>
        <el-table-column align="center" label="推广" min-width="120">
          <template slot-scope="scope">
            <el-button
              type="text"
              @click="funPoster(scope.row.id)"
            >[海报]</el-button>
            <el-button
              type="text"
              @click="funCopy(scope.row)"
            >[复制]</el-button>

            <!--            新增加职位推广功能，职位推广里面包含海报和复制，职位推广上线时上面的海报和复制按钮需要去掉-->
            <!--            <el-button-->
            <!--              type="text"-->
            <!--              @click="goto('/user/job/list/extension?id=' + scope.row.id)"-->
            <!--            >[职位推广]</el-button>-->
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
      title="将所选职位设置为"
      :visible.sync="dialogFormVisible"
      width="520px"
    >
      <el-form class="common-form" label-width="82px">
        <el-form-item label="认证状态：">
          <el-radio-group v-model="setAuditVal" @change="auditDataChange">
            <el-radio
              v-for="(item, index) in options_audit"
              :key="index"
              :label="index"
            >
              {{ item }}
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="setAuditVal == 2" class="audit_template_select" label="审核模板：">
          <el-select v-model="auditTemplateId" :clearable="true" placeholder="请选择" @change="selectedAuditTemplate">
            <el-option v-for="item in auditTemplateList" :key="item.id" :value="item.id" :label="item.content">
              <span class="multiline-text">{{ item.content }}</span>
              <span v-if="item.id != 0" class="el-icon-delete" @click.stop="deleteAuditTemplate(item.id)" />
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="setAuditVal == 2" class="reason-box" label="原因：">
          <el-input
            v-model="setAuditReason"
            type="textarea"
            placeholder="请填写不通过原因"
            rows="3"
            maxlength="40"
            show-word-limit
          />
        </el-form-item>
        <el-form-item v-if="setAuditVal == 2" class="checkboxAddAuditTemplate">
          <el-checkbox v-model="addAuditTemplate" :disabled="canAddAuditTemplate">
            {{ addAuditTemplateDesc }}
          </el-checkbox>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_audit"> 确 定 </el-button>
      </div>
    </el-dialog>
    <Poster
      v-if="showPoster"
      :poster-id="posterId"
      :poster-type="posterType"
      @closeDialog="showPoster = false"
    />
    <div class="call">
      <el-dialog
        :visible.sync="callDialogVisible"
        width="30%"
        :before-close="callHandleClose"
      >
        <div class="box-content">
          <div class="content">
            <div class="title1">您还尚未开通云呼叫服务</div>
            <div class="title2">扫码添加客服</div>
            <div class="title3">快速开通呼叫业务</div>
          </div>
          <div class="code">
            <img src="../../../../assets/images/outbound/renew.png" alt="">
          </div>
          <div style="clear:both" />
          <div class="slogan">一键发起云呼叫，自动录音，助力提升沟通效率！</div>
          <div class="advantage">
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">免硬件设备</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">录音清晰无杂音</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">外显销售手机号</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">招聘行业专用线路</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">稳定性更高</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">防封强、接通率高</div>
            </div>
          </div>
        </div>
      </el-dialog>
    </div>
    <div class="meet">
      <el-dialog
        :visible.sync="meetDialogVisible"
        width="30%"
        :before-close="meetHandleClose"
      >
        <div class="box-content">
          <div id="animation" class="img">
            <!--            <img src="../../../../assets/images/outbound/meet.png" alt="">-->
          </div>
          <div class="hu">正在呼叫客户</div>
          <div class="telephone">{{ dialPhone }}</div>
          <div class="company">{{ dialName }}</div>
          <div class="tips">请您留意手机来电接听</div>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { jobList, jobDelete, jobAudit, jobRefresh, jobModifyState } from '@/api/job'
import { parseTime, setMemberLogin } from '@/utils/index'
import { exportJobById } from '@/api/export'
import { management } from '@/api/member'
import Poster from '@/components/Poster'
import { outboundCall } from '@/api/outbound'
import { getAuditTemplateList, deleteAuditTemplate } from '@/api/auditTemplate'

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
  components: {
    Poster
  },
  props: ['listtype'],
  data() {
    return {
      showPoster: false,
      posterId: '',
      posterType: '',
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
      options_display: [],
      callDialogVisible: false,
      meetDialogVisible: false,
      dialPhone: '',
      dialName: '',
      auditTemplateId: '',
      auditTemplateList: {},
      addAuditTemplate: 0,
      addAuditTemplateDesc: '',
      canAddAuditTemplate: false
    }
  },
  created() {
    if (localStorage.getItem('job_audit') && localStorage.getItem('job_audit') == '0') {
      this.audit = 0
      localStorage.setItem('job_audit', '')
    }
    this.fetchData()
  },
  methods: {
    modifyState(row){
      if (row.display === false) {
        this.$confirm('确定要把' + row.jobname + '的招聘状态设为暂停招聘吗？ 设为暂停招聘后此职位将无法被用户搜索。', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.saveState(row)
        }).catch(() => {
          this.fetchData()
          return false
        })
      } else {
        this.saveState(row)
      }
    },
    saveState(row){
      const that = this
      const param = {
        is_display: row.display,
        id: row.id
      }
      jobModifyState(param).then(response => {
        this.$message.success(response.message)
        this.fetchData()
        return true
      }).catch(() => {
        that.issubmit = false
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
        .catch(() => { })
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
        .catch(() => { })
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
      this.setAuditVal = row.audit
      this.auditIdarr = [row.id]
      this.dialogFormVisible = true
      // 获取审核模板列表
      this.getAuditTemplateList()
      this.auditTemplateId = ''
      this.setAuditReason = ''
    },
    funAuditBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要审核的职位')
        return false
      }
      this.auditIdarr = this.tableIdarr
      this.setAuditVal = 0
      this.dialogFormVisible = true
      // 获取审核模板列表
      this.getAuditTemplateList()
      this.auditTemplateId = ''
      this.setAuditReason = ''
    },
    fun_set_audit() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      const params = {
        id: this.auditIdarr,
        audit: this.setAuditVal,
        reason: this.setAuditReason,
        template_id: this.auditTemplateId,
        add_template: this.addAuditTemplate ? 1 : 0
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
      }).catch(() => { this.auditSubmitLoading = false })
    },
    funRefresh() {
      var that = this
      if (that.tableIdarr.length == 0) {
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
    funExport() {
      var that = this
      if (that.$store.state.user.access_export == 0) {
        that.$message.error('当前管理员没有导出权限')
        return false
      }
      if (that.tableIdarr.length == 0) {
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
    },
    funPoster(id) {
      this.showPoster = true
      this.posterId = id
      this.posterType = 'job'
    },
    // 一键复制
    funCopy(row) {
      const that = this
      const copyMessage = `${row.companyname}
招聘：${row.jobname}
要求：工作经验${row.experience_text}、学历要求${row.education_text}
工资：${row.wage_text}
查看联系方式：${row.job_link}
-招聘求职就上${row.sitename}-`
      this.$copyText(copyMessage).then(function (e) {
        that.$message.success('内容已复制到剪切板！')
      }, function (e) {
        that.$message.error('抱歉，复制失败！')
      })
    },
    clickDial(phone, name){
      this.$confirm('是否对【' + name + '】' + phone + ' 发起呼叫？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        outboundCall(
          { 'mobile': phone }
        ).then(res => {
          if (res.code == 200){
            this.meetDialogVisible = true
            this.dialPhone = phone
            this.dialName = name
          }
          if (res.code == 4040){
            this.callDialogVisible = true
          }
        }).catch((res) => {
        })
      }).catch(() => {

      })
    },
    callHandleClose(){
      this.callDialogVisible = false
    },
    meetHandleClose(){
      this.meetDialogVisible = false
    },
    // 审核切换事件
    auditDataChange(){
      this.auditTemplateId = ''
      this.setAuditReason = ''
      this.defaultSelectedAuditTemplate()
    },
    // 获取审核模板列表
    getAuditTemplateList() {
      getAuditTemplateList({ type: 3 })
        .then(res => {
          this.auditTemplateList = res.data

          let maxTemplate = 6;
          let currentNum = this.auditTemplateList.length;
          if (currentNum >= maxTemplate) {
            this.addAuditTemplateDesc = '新增模板（最多可添加' + maxTemplate + '条，当前已添加' + currentNum + '条）';
            // 超过最大可添加就不能再添加模板了
            this.canAddAuditTemplate = true
          } else {
            let diffNum = maxTemplate - currentNum;
            this.addAuditTemplateDesc = '新增模板（最多可添加' + maxTemplate + '条，还可添加' + diffNum + '条）';
            this.canAddAuditTemplate = false
          }
          // 最后拼接一个默认选中项
          this.auditTemplateList.push({
            id: 0,
            content: '其他'
          })
          // 默认选中审核模板
          this.defaultSelectedAuditTemplate()
          this.addAuditTemplate = 0
        })
        .catch(() => {
        })
    },
    // 删除审核模板
    deleteAuditTemplate(id) {
      this.$confirm('确定要删除此模板吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          deleteAuditTemplate({ id: id })
            .then(res => {
              // 如果删除的模板当前已被选中就将选中值清空
              if (this.auditTemplateId == id) {
                this.auditTemplateId = ''
              }
              // 获取审核模板列表
              this.getAuditTemplateList()
            })
            .catch(() => {
            })
        })
        .catch(() => {
        })
    },
    // 选中审核模板事件
    selectedAuditTemplate() {
      // 选中模板后将模板内容放入到原因中
      if (this.auditTemplateId > 0 && this.auditTemplateList.length > 0) {
        this.auditTemplateList.forEach((item) => {
          if (item.id == this.auditTemplateId) {
            this.setAuditReason = item.content
            return
          }
        })
      } else {
        this.setAuditReason = ''
      }
    },
    // 默认选中审核模板
    defaultSelectedAuditTemplate(){
      if (this.setAuditVal == 2 && this.auditTemplateList.length > 0) {
        // 默认选中第一个模板
        this.auditTemplateId = this.auditTemplateList[0].id
        if (this.auditTemplateId > 0) {
          this.setAuditReason = this.auditTemplateList[0].content
        }
      }
    }
  }
}
</script>
<style  scoped lang="scss">
.dial{
  position: relative;
  top:2px;
  width: 14px;
  height: 14px;
  display: inline-block;
  .disal_img{
    cursor:pointer;
    width: 14px;
    height: 14px;
    background: url('../../../../assets/images/outbound/dial.png') no-repeat center;
  }
}
.meet{
  .box-content{
    .img{
      width: 150px;
      height:150px;
      text-align: center;
      margin-top: 40px;
      margin:0 auto;
      background: url('../../../../assets/images/outbound/meet.png') no-repeat center;
    }
    .hu{
      text-align: center;
      margin-top: 30px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #949494;
    }
    .telephone{
      text-align: center;
      font-size: 30px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      margin-top: 26px;
    }
    .company{
      text-align: center;
      margin-top:25px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #666666;
    }
    .tips{
      text-align: center;
      height: 80px;
      line-height: 80px;
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #959595;
    }
  }
}
.meet{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
.call{
  .box-content{
    padding: 20px 30px 35px 30px;
    .advantage{
      .advantage-box{
        display: inline-block;
        width: 170px;
        .title{
          display: inline-block;
          float:left;
        }
        .img{
          display: inline-block;
          float:left;
          margin-right: 8px;
        }
      }
      margin-top: 25px;
    }
    .slogan{
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #FF550A;
      margin-top:24px;
    }
    .code{
      display: inline-block;
      margin-right: 30px;
      float: right;
      width: 117px;
      height: 117px;
      background: #FFFFFF;
      box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.14);
      border-radius: 8px;
      padding: 10px 10px;
      img{
        width: 100%;
        height: 100%;
      }
    }
    .content{
      display: inline-block;
      float: left;
      .title1{
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FF550A;
      }
      .title2{
        margin-top: 18px;
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #555555;
      }
      .title3{
        width: 133px;
        height: 24px;
        background: #FF6929;
        border-radius: 3px;
        text-align: center;
        line-height: 24px;
        font-size: 14px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FFFFFF;
        margin-top: 23px;
      }
    }
  }
}
.call{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
#animation {
  animation:pulse 1s .2s ease both infinite;
}
@-webkit-keyframes pulse {
  0% {
    -webkit-transform:scale(1)
  }
  50% {
    -webkit-transform:scale(1.1)
  }
  100% {
    -webkit-transform:scale(1)
  }
}
@-moz-keyframes pulse {
  0% {
    -moz-transform:scale(1)
  }
  50% {
    -moz-transform:scale(1.1)
  }
  100% {
    -moz-transform:scale(1)
  }
}

.audit_template_select{
  .el-select{
    width: 100% !important;
  }
}
.reason-box{
  margin-bottom: 10px;
}
.checkboxAddAuditTemplate{
  margin-bottom: 10px;
  ::v-deep .el-checkbox__inner{
    border-radius: 50%;
  }
}

.multiline-text{
  white-space: normal;
  width: 330px;
}

.el-select-dropdown__item {
  font-size: 14px;
  padding: 0 20px;
  position: relative;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  height: auto;
  line-height: 1.5;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
  &:first-child{
    margin-top: 10px;
  }
}
</style>
