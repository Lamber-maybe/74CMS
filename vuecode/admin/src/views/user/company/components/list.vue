<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>企业管理</span>
      </div>
      <div class="list-search">
        <el-select
          v-if="showAuditOption === true"
          v-model="audit"
          class="list-options"
          placeholder="不限认证状态"
          @change="funSearch"
        >
          <el-option label="不限认证状态" value="" />
          <el-option
            v-for="(item, index) in options_audit"
            :key="index"
            :label="item"
            :value="index"
          />
        </el-select>
        <el-select
          v-model="setmeal"
          class="list-options"
          placeholder="不限套餐类型"
          @change="funSearch"
        >
          <el-option label="不限套餐类型" value="" />
          <el-option
            v-for="(item, index) in options_setmeal"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-select
          v-model="regtime"
          class="list-options"
          placeholder="不限注册时间"
          @change="funSearch"
        >
          <el-option label="不限注册时间" value="" />
          <el-option label="3天内" value="3" />
          <el-option label="7天内" value="7" />
          <el-option label="15天内" value="15" />
          <el-option label="30天内" value="30" />
        </el-select>
        <el-select
          v-model="service"
          class="list-options"
          placeholder="不限所属客服"
          @change="funSearch"
        >
          <el-option label="不限所属客服" value="" />
          <el-option label="未分配" value="0" />
          <el-option
            v-for="(item, index) in options_service"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-select
          v-model="setmeal_overtime"
          class="list-options"
          placeholder="不限过期时间"
          @change="funSearch"
        >
          <el-option label="不限过期时间" value="" />
          <el-option label="套餐已过期" value="1" />
          <el-option label="套餐未过期" value="0" />
        </el-select>
        <el-select
          v-model="is_display"
          class="list-options"
          placeholder="不限显示状态"
          @change="funSearch"
        >
          <el-option label="不限显示状态" value="" />
          <el-option label="显示中" value="1" />
          <el-option label="不显示" value="0" />
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
            <el-option label="公司名称" value="1" />
            <el-option label="公司ID" value="2" />
            <el-option label="会员手机号" value="3" />
            <el-option label="会员ID" value="4" />
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
        <el-table-column label="公司名称" min-width="210">
          <template slot-scope="scope">
            <div v-if="scope.row.companyname != ''">
              <div>
                <el-link :href="scope.row.link" target="_blank" type="primary">
                  {{ scope.row.companyname }}
                </el-link>
              </div>
              <div>
                {{ scope.row.nature_text ? scope.row.nature_text : "未填写" }} ·
                {{
                  scope.row.district_text ? scope.row.district_text : "未填写"
                }}
                ·
                {{ scope.row.trade_text ? scope.row.trade_text : "未填写" }}
              </div>
            </div>
            <span v-else>未完善企业资料</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="认证资料" min-width="100">
          <template slot-scope="scope">
            <span
              v-if="scope.row.has_auth_info == 1"
              class="font_brand"
              style="cursor: pointer"
              @click="fun_show_authinfo(scope.row)"
            >
              [点击查看]
            </span>
            <span v-else>-</span>
          </template>
        </el-table-column>
        <el-table-column label="认证状态" min-width="80">
          <template slot-scope="scope">
            <el-tag :type="scope.row.auth_status | auditFilter">
              {{ options_audit[scope.row.auth_status] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="显示状态" min-width="80">
          <template slot-scope="scope">
            <el-tooltip :content="scope.row.is_display==1?'显示中':'不显示'" placement="top">
              <el-switch
                v-model="scope.row.is_display"
                :active-value="1"
                :inactive-value="0"
                @change="changeDisplay(scope.row)"
              />
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column label="会员/企业手机号" min-width="130">
          <template slot-scope="scope">
            <div title="会员手机号">
              <i class="el-icon-user" />&nbsp;{{ scope.row.mobile }}
            </div>
            <div title="企业联系手机号">
              <i class="el-icon-document" />&nbsp;{{
                scope.row.contact_mobile ? scope.row.contact_mobile : "-"
              }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="创建/刷新时间" min-width="150">
          <template slot-scope="scope">
            <div title="创建时间">
              <i class="el-icon-time" />{{ scope.row.addtime | timeFilter }}
            </div>
            <div title="刷新时间">
              <i class="el-icon-time" />{{ scope.row.refreshtime | timeFilter }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="套餐名称" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.setmeal_name }}</div>
            <div v-if="scope.row.setmeal_overtime == 1" style="color: #ff0b22">
              已过期
            </div>
            <div v-else>{{ scope.row.setmeal_deadline_text }}</div>
          </template>
        </el-table-column>
        <el-table-column align="center" label="在招职位" prop="jobs_num" min-width="70" />
        <el-table-column align="center" label="推广" min-width="80">
          <template slot-scope="scope">
            <el-button
              type="text"
              @click="funPoster(scope.row.id)"
            >[海报]</el-button>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="270">
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
              @click="goto('/user/company/edit?id=' + scope.row.id)"
            >
              编辑
            </el-button>
            <el-button size="small" type="warning" @click="funAudit(scope.row)">
              认证
            </el-button>
            <el-dropdown trigger="click" style="margin-left: 10px">
              <el-button type="small">···</el-button>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item @click.native="handlerRefreshJob(scope.row)">
                  刷新
                </el-dropdown-item>
                <el-dropdown-item
                  @click.native="funLog(scope.$index, scope.row)"
                >
                  日志
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
            @click="goto('/user/company/add')"
          >
            添加企业
          </el-button>
          <el-button size="small" type="primary" @click="funExport">
            导出
          </el-button>
          <el-button size="small" type="primary" @click="funService">
            分配客服
          </el-button>
          <el-button size="small" type="primary" @click="handlerRefreshJobBatch">
            刷新
          </el-button>
          <el-button size="small" type="warning" @click="funAuditBatch">
            认证
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
      title="将所选企业设置为"
      :visible.sync="dialogFormVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="认证状态">
          <el-radio-group v-model="setAuditVal">
            <el-radio
              v-for="(item, index) in form_options_audit"
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
      title="将所选企业的客服设置为"
      :visible.sync="dialogServiceVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="选择客服">
          <el-select v-model="setServiceVal" placeholder="请选择">
            <el-option
              v-for="(item, index) in options_service_enable"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogServiceVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_service"> 确 定 </el-button>
      </div>
    </el-dialog>
    <el-dialog
      title="认证资料"
      :visible.sync="dialogAuthinfoVisible"
      :width="dialogAuthinfoWidth"
    >
      <el-row>
        <el-col
          class="authdialog"
          :span="$store.state.config.audit_com_project == 1 ? 12 : 24"
        >
          <el-image
            v-if="auth_info.license != ''"
            style="width: 200px; height: 150px"
            :src="auth_info.license"
            :preview-src-list="[auth_info.license]"
          />
          <el-image
            v-else
            :src="require('@/assets/images/defult_upload_null.jpg')"
          />
          <span class="img-tit">营业执照</span>
        </el-col>
        <el-col
          v-if="$store.state.config.audit_com_project == 1"
          class="authdialog"
          :span="12"
        >
          <el-image
            v-if="auth_info.proxy != ''"
            style="width: 200px; height: 150px"
            :src="auth_info.proxy"
            :preview-src-list="[auth_info.proxy]"
          />
          <el-image
            v-else
            :src="require('@/assets/images/defult_upload_null.jpg')"
          />
          <span class="img-tit">委托书(函)</span>
        </el-col>
      </el-row>
      <el-row v-if="$store.state.config.audit_com_project == 1">
        <el-col class="authdialog" :span="12">
          <el-image
            v-if="auth_info.legal_person_idcard_front != ''"
            style="width: 200px; height: 150px"
            :src="auth_info.legal_person_idcard_front"
            :preview-src-list="[auth_info.legal_person_idcard_front]"
          />
          <el-image
            v-else
            :src="require('@/assets/images/defult_upload_null.jpg')"
          />
          <span class="img-tit">经办人身份证正面照</span>
        </el-col>
        <el-col class="authdialog" :span="12">
          <el-image
            v-if="auth_info.legal_person_idcard_back != ''"
            style="width: 200px; height: 150px"
            :src="auth_info.legal_person_idcard_back"
            :preview-src-list="[auth_info.legal_person_idcard_back]"
          />
          <el-image
            v-else
            :src="require('@/assets/images/defult_upload_null.jpg')"
          />
          <span class="img-tit">经办人身份证背面照</span>
        </el-col>
      </el-row>
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
    <Poster
      v-if="showPoster"
      :poster-id="posterId"
      :poster-type="posterType"
      @closeDialog="showPoster = false"
    />
  </div>
</template>

<script>
import MemberLog from '@/components/MemberLog/Index'
import { getClassify } from '@/api/classify'
import { companyList, companyAudit, companyDelete, companySetService, companySetDisplay , refreshJob} from '@/api/company'
import { management} from '@/api/member'
import { parseTime, setMemberLogin } from '@/utils/index'
import { exportCompanyById } from '@/api/export'
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
        case 3:
          return 'info'
        default:
          return ''
      }
    }
  },
  props: ['listtype', 'showAuditOption'],
  data() {
    return {
      showPoster: false,
      posterId: '',
      posterType: '',
      dialogAuthinfoWidth: '25%',
      dialogAuthinfoVisible: false,
      auth_info: {},
      auditSubmitLoading: false,
      setAuditVal: 0,
      setAuditReason: '',
      setServiceVal: '',
      auditIdarr: [],
      serviceIdarr: [],
      tableIdarr: [],
      tableUidarr: [],
      dialogFormVisible: false,
      dialogServiceVisible: false,
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      is_display: '',
      audit: '',
      setmeal: '',
      regtime: '',
      service: '',
      setmeal_overtime: '',
      form_options_audit: [],
      options_audit: [],
      options_setmeal: [],
      options_service: [],
      options_service_enable: [],
      dialogListVisible: false,
      listUid: 0
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getClassify({ type: 'companyAudit,setmealList,customerService' })
        .then(response => {
          const options_audit_arr = [...response.data.companyAudit]
          options_audit_arr.forEach(el => {
            this.options_audit[el.id] = el.name
          })
          this.form_options_audit = [...this.options_audit]
          this.options_audit[3] = '未认证'
          this.options_setmeal = [...response.data.setmealList]
          this.options_service = [...response.data.customerService]
          this.options_service.forEach(item => {
            if (item.status == 1) {
              this.options_service_enable.push(item)
            }
          })
          const list_type = this.listtype
          const param = {
            list_type,
            key_type: this.key_type,
            keyword: this.keyword,
            is_display: this.is_display,
            audit: this.audit,
            setmeal: this.setmeal,
            regtime: this.regtime,
            service: this.service,
            setmeal_overtime: this.setmeal_overtime,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return companyList(param)
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
        is_display: this.is_display,
        audit: this.audit,
        setmeal: this.setmeal,
        regtime: this.regtime,
        service: this.service,
        setmeal_overtime: this.setmeal_overtime,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      companyList(param).then(response => {
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
        this.$message.error('请选择要认证的企业')
        return false
      }
      this.auditIdarr = this.tableIdarr
      this.setAuditReason = ''
      this.setAuditVal = 0
      this.dialogFormVisible = true
    },
    funService() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要分配客服的企业')
        return false
      }
      if (this.$store.state.user.access_set_service == 0) {
        this.$message.error('当前管理员没有分配客服权限')
        return false
      }
      this.serviceIdarr = this.tableIdarr
      this.setServiceVal = ''
      this.dialogServiceVisible = true
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
      companyAudit(params, 'post').then(response => {
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
    fun_set_service() {
      if (this.$store.state.user.access_set_service == 0) {
        this.$message.error('当前管理员没有分配客服权限')
        return false
      }
      const params = {
        id: this.serviceIdarr,
        cs_id: this.setServiceVal
      }
      companySetService(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.fetchListData()
          this.dialogServiceVisible = false
          return true
        } else {
          this.$message.error(response.message)
          return false
        }
      }).catch(() => { })
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
    fun_show_authinfo(row) {
      this.auth_info = { ...row }
      this.dialogAuthinfoVisible = true
      if (this.$store.state.config.audit_com_project == 0) {
        this.dialogAuthinfoWidth = '300px'
      } else if (this.auth_info.license != '') {
        this.dialogAuthinfoWidth = '30%'
      } else {
        this.dialogAuthinfoWidth = '40%'
      }
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
        .$confirm('删除企业将删除该企业的一切信息，包括会员账号、企业资料、招聘职位等所属信息，删除后不可恢复, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: [row.uid]
          }
          companyDelete(param).then(response => {
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
        that.$message.error('请选择要删除的企业')
        return false
      }
      that
        .$confirm('删除企业将删除该企业的一切信息，包括会员账号、企业资料、招聘职位等所属信息，删除后不可恢复, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: that.tableUidarr
          }
          companyDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => { })
    },
    funExport() {
      var that = this
      if (that.$store.state.user.access_export == 0) {
        that.$message.error('当前管理员没有导出权限')
        return false
      }
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要导出的企业')
        return false
      }
      const param = {
        id: that.tableIdarr
      }
      exportCompanyById(param).then(response => {
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
          '企业ID',
          '企业名称',
          '企业简称',
          '企业性质',
          '所属行业',
          '所在地区',
          '企业规模',
          '注册资金',
          '联系人',
          '联系手机',
          '联系座机',
          '联系人微信',
          '联系人qq',
          '联系人邮箱',
          '审核状态',
          '注册时间',
          '刷新时间'
        ] // 上面设置Excel的表格第一行的标题
        const filterVal = [
          'number',
          'id',
          'companyname',
          'short_name',
          'nature',
          'trade',
          'district',
          'scale',
          'registered',
          'contact',
          'mobile',
          'telephone',
          'weixin',
          'qq',
          'email',
          'audit',
          'addtime',
          'refreshtime'
        ]
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '企业导出'
        )
      })
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]))
    },
    funPoster(id) {
      this.showPoster = true
      this.posterId = id
      this.posterType = 'company'
    },
    changeDisplay(row){
      const id = row.id
      const is_display = row.is_display
      const params = {
        id,
        is_display
      }
      companySetDisplay(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.fetchListData()
          return true
        } else {
          this.$message.error(response.message)
          return false
        }
      }).catch(() => { })
    },
    /**
     * 刷新职位
     * @author chenyang
     * Date Time：2022年3月11日16:32:40
     */
    handlerRefreshJob (companyData) {
      this.$confirm('确定要刷新所选企业的所有职位？','系统提示',{
        type: 'warning'
      })
        .then(() => {
          refreshJob({company_id:companyData.id,is_batch:0})
            .then(res => {
              if (res.code == 200) {
                this.$message({ type: 'success', message: res.message })
                this.fetchData()
              } else {
                this.$message({ type: 'error', message: res.message })
              }
          })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    /**
     * 批量刷新职位
     * @author chenyang
     * Date Time：2022年3月11日16:47:26
     */
    handlerRefreshJobBatch(){
      if(this.tableIdarr.length<=0){
        this.$message({ type: 'error', message:'请勾选要刷新的企业！'})
        return false
      }
      this.$confirm('确定要批量刷新所选企业的所有职位？','系统提示',{
        type: 'warning'
      })
        .then(() => {
          var companyIdstr =this.tableIdarr.join(',')
          refreshJob({company_id:companyIdstr,is_batch:1})
            .then(res => {
              if (res.code == 200) {
                this.$message({ type: 'success', message: res.message })
                this.fetchData()
              } else {
                this.$message({ type: 'error', message: res.message })
              }
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
.authdialog {
  position: relative;
  margin-bottom: 14px;
  text-align: center;
}
.img-tit {
  position: absolute;
  left: 0;
  bottom: -20px;
  width: 100%;
  text-align: center;
}
.image-error {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 14px;
  color: #c0c4cc;
  vertical-align: middle;
  background: #f5f7fa;
  width: 200px;
  height: 150px;
}
</style>
