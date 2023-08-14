<template>
  <div class="app-container">
    <el-card class="box-card box-card-top">
      <div style="position: relative;">
        <div v-loading="topLoading" class="statictistics-box">
          <div class="statictistics-box1">
            <div class="line1">
              <div class="line_lf" @click="jumpPath('/user/company/crm/allClient', '暂无权限查看，请联系超级管理员', '')">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_1.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">累计总客户</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.total_company }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_11.png" alt="">
                  </p>
                </div>
              </div>
              <div class="line_lf" @click="jumpPath('/user/company/crm/allClient', '暂无权限查看，请联系超级管理员', 'today_company')">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_2.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">今日新增客户</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.today_company }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_22.png" alt="">
                  </p>
                </div>
              </div>
            </div>
            <div class="line1 line2">
              <div class="line_lf" @click="jumpPath('/user/company/crm/pubilceClient', '暂无权限查看，请联系超级管理员', '')">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_3.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">客户公海</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.international_company }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_33.png" alt="">
                  </p>
                </div>
              </div>
              <div class="line_lf" @click="jumpPath('/user/company/crm/allClient', '暂无权限查看，请联系超级管理员', 'today_follow')">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_4.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">今日跟进的客户</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.follow_up_company }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_44.png" alt="">
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="statictistics-box1 statictistics-box2">
            <div class="line1">
              <div class="line_lf">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_1.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">累计总线索</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.total_clue }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_11.png" alt="">
                  </p>
                </div>
              </div>
              <div class="line_lf">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_2.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">今日新增线索</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.today_clue }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_22.png" alt="">
                  </p>
                </div>
              </div>
            </div>
            <div class="line1 line2">
              <div class="line_lf">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_3.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">线索公海</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.international_clue }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_33.png" alt="">
                  </p>
                </div>
              </div>
              <div class="line_lf">
                <img class="lineLfIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_4.png" alt="">
                <div class="lineLfTxt">
                  <p class="txt1">累计线索转客户</p>
                  <p class="txt2">
                    <span class="txt3">{{ statisticsData.total_turn_clue }}</span>
                    <img class="txtIcon" src="../../../../assets/images/company/crm/JobStatistics/lf_44.png" alt="">
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="statictistics-box3">
            <p class="title">
              <span class="line" />
              <span class="txt">信息提醒</span>
            </p>
            <div class="content">
              <div class="content-line1">
                <div class="content-line-minibox" @click="jumpPath('/user/company/crm/allClient', '暂无权限查看，请联系超级管理员', 'expiring_soon')">
                  <p class="p1">{{ informationReminderData.expire_company }}</p>
                  <p class="p2">到期未续费客户</p>
                </div>
                <div class="content-line-minibox" @click="jumpPath('/user/company/crm/allClient', '暂无权限查看，请联系超级管理员', 'expire_company')">
                  <p class="p1">{{ informationReminderData.expiring_soon }}</p>
                  <p class="p2">即将到期客户</p>
                </div>
              </div>
              <div class="content-line1">
                <div class="content-line-minibox" @click="jumpPath('/user/company/crm/allClient', '暂无权限查看，请联系超级管理员', 'never_follow_up_company')">
                  <p class="p1">{{ informationReminderData.never_follow_up_company }}</p>
                  <p class="p2">从未跟进客户</p>
                </div>
                <div class="content-line-minibox" @click="jumpPath('/user/company/crm/whole', '暂无权限查看，请联系超级管理员', 'never_follow_up_clue')">
                  <p class="p1">{{ informationReminderData.never_follow_up_clue }}</p>
                  <p class="p2">从未跟进线索</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </el-card>
    <el-card class="box-card">
      <div style="position: relative;" class="table-box">
        <p class="title">
          <span class="line" />
          <span class="txt">销售统计</span>
        </p>
        <div style="position:relative;">
          <div class="setField_s">
            <el-popover v-model="visiblepop" placement="bottom-start" width="220" trigger="manual">
              <div class="setField_h">
                <div v-for="item in fieldData" class="setFields">
                  <input v-if="item.select == true" type="checkbox" :name="item.name" :value="item.field" checked @change="select(item.field)">
                  <input v-if="item.select == false" type="checkbox" :name="item.name" :value="item.field" @change="select(item.field)">
                  <span style="margin-left: 10px;">{{ item.name }}</span>
                </div>
              </div>
              <el-button slot="reference" :class="menu_icon == 'menu' ? 'field_s' : 'field_s_select'" @click="menu">
                <img v-if="menu_icon == 'menu'" src="../../../../assets/images/company/crm/menu.png" alt="">
                <img v-if="menu_icon == 'menu_select'" src="../../../../assets/images/company/crm/menu1.png" alt="">
              </el-button>
              <div style="margin-top:10px;margin-right: 10px;">
                <el-button class="filterOperation" type="text" size="small" @click="setFieldClose()">关闭</el-button>
                <el-button class="filterOperation" type="text" size="small" @click="setFieldConfirm()">确认</el-button>
              </div>
            </el-popover>
          </div>
          <el-table
            ref="multipleTable"
            v-loading="loading"
            :data="tableData"
            tooltip-effect="dark"
            :header-cell-style="{ background: '#f9f9f9', 'border-right': '1px solid #e4e6eb' }"
            border
            stripe
            :default-sort="{ prop: 'date' }"
            style="width: 100%;"
            @sort-change="sortTable"
          >
            <el-table-column
              v-for="items in fieldData"
              v-if="items.is_locking == true && items.select == true"
              fixed
              :sortable="items.is_sortable"
              :prop="items.field"
              :width="items.width"
            >
              <template slot="header" slot-scope="scope">
                <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                  <span>{{ items.name }}</span>
                  <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
                  <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
                </div>
              </template>
              <template slot-scope="scope">
                <div v-if="items.field == 'user_name'" class="username">{{ scope.row.username }}</div>
                <div v-if="items.field == 'company_total'">{{ scope.row.company_total }}</div>
                <div v-if="items.field == 'today_num'">{{ scope.row.today_num }}</div>
                <div v-if="items.field == 'expiring_soon'">{{ scope.row.expiring_soon }}</div>
                <div v-if="items.field == 'no_renewal'">{{ scope.row.no_renewal }}</div>
                <div v-if="items.field == 'new_customers'">{{ scope.row.new_customers }}</div>
                <div v-if="items.field == 'mature_num'">{{ scope.row.mature_num }}</div>
                <div v-if="items.field == 'maintain_num'">{{ scope.row.maintain_num }}</div>
                <div v-if="items.field == 'stable_num'">{{ scope.row.stable_num }}</div>
                <div v-if="items.field == 'loss_num'">{{ scope.row.loss_num }}</div>
                <div v-if="items.field == 'deal_num'">{{ scope.row.deal_num }}</div>
                <div v-if="items.field == 'follow_up_today'">{{ scope.row.follow_up_today }}</div>
                <div v-if="items.field == 'seven_days_no_follow_up'">{{ scope.row.seven_days_no_follow_up }}</div>
                <div v-if="items.field == 'fifteen_days_no_follow_up'">{{ scope.row.fifteen_days_no_follow_up }}</div>
                <div v-if="items.field == 'no_follow_up'" class="no_follow_up">{{ scope.row.no_follow_up }}</div>
              </template>
            </el-table-column>
            <el-table-column
              v-for="items in fieldData"
              v-if="items.is_locking == false && items.select == true"
              :prop="items.field"
              :sortable="items.is_sortable"
              :width="items.width"
            >
              <template slot="header" slot-scope="scope">
                <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                  <span>{{ items.name }}</span>
                  <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
                  <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
                </div>
              </template>
              <template slot-scope="scope">
                <div v-if="items.field == 'user_name'" class="username">{{ scope.row.username }}</div>
                <div v-if="items.field == 'company_total'">{{ scope.row.company_total }}</div>
                <div v-if="items.field == 'today_num'">{{ scope.row.today_num }}</div>
                <div v-if="items.field == 'expiring_soon'">{{ scope.row.expiring_soon }}</div>
                <div v-if="items.field == 'no_renewal'">{{ scope.row.no_renewal }}</div>
                <div v-if="items.field == 'new_customers'">{{ scope.row.new_customers }}</div>
                <div v-if="items.field == 'mature_num'">{{ scope.row.mature_num }}</div>
                <div v-if="items.field == 'maintain_num'">{{ scope.row.maintain_num }}</div>
                <div v-if="items.field == 'stable_num'">{{ scope.row.stable_num }}</div>
                <div v-if="items.field == 'loss_num'">{{ scope.row.loss_num }}</div>
                <div v-if="items.field == 'deal_num'">{{ scope.row.deal_num }}</div>
                <div v-if="items.field == 'follow_up_today'">{{ scope.row.follow_up_today }}</div>
                <div v-if="items.field == 'seven_days_no_follow_up'">{{ scope.row.seven_days_no_follow_up }}</div>
                <div v-if="items.field == 'fifteen_days_no_follow_up'">{{ scope.row.fifteen_days_no_follow_up }}</div>
                <div v-if="items.field == 'no_follow_up'" class="no_follow_up">{{ scope.row.no_follow_up }}</div>
              </template>
            </el-table-column>
          </el-table>
          <div class="bortton-page">
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
          </div>
        </div>
      </div>
    </el-card>
    <el-row style="margin-top:20px;" class="chartTableBox">
      <el-card>
        <el-col :span="18">
          <el-tabs v-model="chartTabSelected" class="statisticsTab">
            <el-tab-pane label="新增客户统计" name="0" />
            <el-tab-pane label="客户跟进统计" name="1" />
          </el-tabs>
        </el-col>
        <el-col :span="6" />
        <life_customer_statistics v-if="chartTabSelected == '0'" height="400px" :daterange="daterange" :platform-options="saleScreenData" />
        <new_company_followStatistics v-if="chartTabSelected == '1'" height="400px" :daterange="daterange" :platform-options="saleScreenData" />
      </el-card>
    </el-row>
    <el-row style="margin-top:20px;" class="chartTableBox">
      <el-card>
        <el-col :span="18">
          <el-tabs v-model="chartTabSelectedNewclue" class="statisticsTab">
            <el-tab-pane label="新增线索统计" name="0" />
            <el-tab-pane label="线索跟进统计" name="1" />
          </el-tabs>
        </el-col>
        <el-col :span="6" />
        <newclue_statistics v-if="chartTabSelectedNewclue == '0'" height="400px" :daterange="daterange" :platform-options="saleScreenData" />
        <newclue_follow_statistics v-if="chartTabSelectedNewclue == '1'" height="400px" :daterange="daterange" :platform-options="saleScreenData" />
      </el-card>
    </el-row>
    <el-row style="margin-top:20px;"><work_life_cycle height="300px" :daterange="daterange" :platform-options="saleScreenData" /></el-row>
    <el-drawer v-if="drawer" size="75%" :show-close="false" :with-header="false" :wrapper-closable="false" :visible.sync="drawer" :before-close="handleClose">
      <clientShow :row-id="rowId" />
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose"><i class="el-icon-close" /></div>
    </el-drawer>
  </div>
</template>
<script>
import { getClassify } from '@/api/classify'
import clientShow from '@/views/user/company/crm/components/ClientShow'
import life_customer_statistics from './components/life_customer_statistics.vue'
import new_company_followStatistics from './components/new_company_followStatistics.vue'
import work_life_cycle from './components/work_life_cycle.vue'
import newclue_statistics from './components/newclue_statistics.vue'
import newclue_follow_statistics from './components/newclue_follow_statistics.vue'
import {
  classify,
  clueAdminLists,
  crmCompanyList,
  crmCustomList,
  crmCustomListEdit,
  lifeCycle,
  companyCrmAudit,
  refreshCrmJob,
  // exportCrmCompanyById,
  companyReceive,
  companyRelease,
  companySetDisplay,
  putRecycleBin,
  // 工作看板
  workData,
  salesStatistics,
  newClueStatistics,
  newClueFollowStatistics,
  workLifeCycle
} from '@/api/company_crm'
import { export_json_to_excel } from '@/excel/Export2Excel'
import { parseTime, setMemberLogin } from '@/utils'
import { outboundCall, customLndex, customLndexAdd } from '@/api/outbound'
import { management } from '@/api/member'
import { checkRoleAuth } from '@/utils/role'

export default {
  name: 'JobStatistics',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    clientShow,
    life_customer_statistics,
    work_life_cycle,
    newclue_statistics,
    new_company_followStatistics,
    newclue_follow_statistics
  },
  data() {
    return {
      loading: false,
      dialogIsDispay: false,
      visiblepop: false,
      setAuditVal: '',
      setAuditReason: '',
      form_options_audit: [],
      dialogFormVisible: false,
      menu_icon: 'menu',
      drawer: false,
      title: '工作看板',
      key_type: '1',
      keyword: '',
      pagesize: 10,
      total: 0,
      currentPage: 1,
      tableData: [],
      currentNav: 'jobStatistics',
      multipleSelection: [],
      uid_arr: [],
      auditSubmitLoading: false,
      export_field: [],
      export_name: [],
      rowId: '',
      sortType: '',
      sort_type: '',
      sort: '',
      is_display: '1',
      exportData: [],
      callDialogVisible: false,
      meetDialogVisible: false,
      dialPhone: '',
      dialName: '',
      weixinFilter: '',
      gradeFilter: '',
      examineFilter: '',
      mealFilter: '',
      followupFilter: '',
      authenticationFilter: '',
      positionFilter: '',
      saleFilter: '',
      deadlineFilter: '',
      // 工作看板
      statisticsData: {},
      informationReminderData: {},
      salesStatisticsData: [],
      chartTabSelected: '0',
      chartTabSelectedNewclue: '0',
      daterange: [],
      topLoading: true,
      saleScreenData: [],
      params: {
        page: 1,
        pagesize: 10,
        order_type: '',
        order: ''
      },
      fieldData: [
        { name: '销售姓名', field: 'user_name', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: false },
        { name: '客户总数', field: 'company_total', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '今日新增', field: 'today_num', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '即将到期', field: 'expiring_soon', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '到期未续费', field: 'no_renewal', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '新客户', field: 'new_customers', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '成熟期客户', field: 'mature_num', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '维持期客户', field: 'maintain_num', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '稳定期客户', field: 'stable_num', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '成交客户', field: 'loss_num', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '流失客户', field: 'deal_num', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '今日跟进', field: 'follow_up_today', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' },
        { name: '7天内未跟进', field: 'seven_days_no_follow_up', select: true, is_locking: false, is_lock_display: false, width: 150, is_sortable: 'custom' },
        { name: '15天内未跟进', field: 'fifteen_days_no_follow_up', select: true, is_locking: false, is_lock_display: false, width: 150, is_sortable: 'custom' },
        { name: '从未跟进', field: 'no_follow_up', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: 'custom' }
      ]
    }
  },
  computed: {
    clueTime() {
      var clueTime = this.$route.query.time
      return clueTime
    },
    // 默认时间
    timeDefault() {
      const date = new Date()
      // 通过时间戳计算
      let defalutStartTime = date.getTime() - 7 * 24 * 3600 * 1000 // 转化为时间戳
      let defalutEndTime = date.getTime()
      const startDateNs = new Date(defalutStartTime)
      const endDateNs = new Date(defalutEndTime)
      // 月，日 不够10补0
      defalutStartTime =
        startDateNs.getFullYear() +
        '-' +
        (startDateNs.getMonth() + 1 >= 10 ? startDateNs.getMonth() + 1 : '0' + (startDateNs.getMonth() + 1)) +
        '-' +
        (startDateNs.getDate() >= 10 ? startDateNs.getDate() : '0' + startDateNs.getDate())
      defalutEndTime =
        endDateNs.getFullYear() +
        '-' +
        (endDateNs.getMonth() + 1 >= 10 ? endDateNs.getMonth() + 1 : '0' + (endDateNs.getMonth() + 1)) +
        '-' +
        (endDateNs.getDate() >= 10 ? endDateNs.getDate() : '0' + endDateNs.getDate())
      return [defalutStartTime, defalutEndTime]
    }
  },
  watch: {
    clueTime() {
      var clue_type = localStorage.getItem('clue_type')
      var clue_company_id = localStorage.getItem('clue_company_id')
      if (clue_type != '' && clue_type == 'follow') {
        this.drawer = true
        this.rowId = clue_company_id
        localStorage.setItem('clue_company_id', '')
        localStorage.setItem('clue_type', '')
      }
    }
  },
  created() {
    this.daterange = this.timeDefault
    this.getFieldData()
    this.initData()
    this.getSalesStatistics()
    this.getClueAdminLists()
  },
  methods: {
    // 顶部数据统计和信息提醒统计
    initData() {
      workData().then(res => {
        if (res.code == 200) {
          this.topLoading = false
          const { statistics, information_reminder } = res.data
          this.statisticsData = statistics
          this.informationReminderData = information_reminder
        } else {
          this.$message.error(res.message)
        }
      })
    },
    // 销售统计
    getSalesStatistics() {
      const params = this.params
      salesStatistics(params).then(res => {
        if (res.code == 200) {
          const { items, total_page, current_page } = res.data
          if (items.length >= 0) {
            this.tableData = items
            this.total = total_page
            this.currentPage = current_page
          } else {
            this.tableData = []
            this.total = 0
            this.currentPage = 1
          }
          this.loading = false
        } else {
          this.$message.error(res.message)
        }
      })
    },
    // 获取销售人员列表
    getClueAdminLists() {
      clueAdminLists().then(res => {
        if (res.code == 200) {
          this.saleScreenData = res.data
        } else {
          this.$message.error(res.message)
        }
      })
    },
    jumpPath(routePath, message, type) {
      if (type == 'today_follow') {
        // 今日跟进客户
        checkRoleAuth(routePath, message, { 'followupScreen': '1' })
      } else if (type == 'today_company') {
        // 新增客户
        checkRoleAuth(routePath, message, { 'add_date': '1' })
      } else if (type == 'expiring_soon') {
        // 到期未续费客户
        checkRoleAuth(routePath, message, { 'setmealDeadline': '2' })
      } else if (type == 'expire_company') {
        // 即将到期客户
        checkRoleAuth(routePath, message, { 'setmealDeadline': '1' })
      } else if (type == 'never_follow_up_company') {
        // 从未跟进客户
        checkRoleAuth(routePath, message, { 'followupScreen': '6' })
      } else if (type == 'never_follow_up_clue') {
        // 从未跟进线索
        checkRoleAuth(routePath, message, { 'followCountScreen': '1' })
      } else {
        checkRoleAuth(routePath, message)
      }
    },
    sortTable({ column, order }) {
      if (order == 'ascending') {
        this.params.order = 'asc'
      } else if (order == 'descending') {
        this.params.order = 'desc'
      } else {
        this.params.order = ''
      }
      this.params.order_type = column.property
      this.getSalesStatistics()
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]))
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    setFieldClose() {
      this.menu_icon = 'menu'
      this.visiblepop = false
    },
    classify() {
      classify({
        type: 'setmealList,companyAudit'
      })
        .then(res => {
          this.mealScreenData = res.data.setmealList
          const options_audit_arr = [...res.data.companyAudit]
          options_audit_arr.forEach(el => {
            this.authenticationScreenData[el.id] = el.name
          })
          this.form_options_audit = [...this.authenticationScreenData]

          this.authenticationScreenData = res.data.companyAudit
          this.authenticationScreenData.push({
            name: '未认证',
            id: 3
          })
        })
        .catch(() => {})
    },
    lifeCycle() {
      lifeCycle()
        .then(res => {
          this.gradeScreenData = res.data
        })
        .catch(() => {})
    },
    clueList() {
      this.loading = true
      if (localStorage.getItem('clue_audit') && localStorage.getItem('clue_audit') == '1') {
        this.authenticationScreen = 0
        localStorage.setItem('clue_audit', '')
      }
      crmCompanyList({
        page: this.currentPage,
        pagesize: this.pagesize,
        list_type: this.list_type,
        key_type: this.key_type,
        keyword: this.keyword,
        life_cycle_id: this.gradeScreen,
        job_count: this.positionScreen,
        setmeal_id: this.mealScreen,
        audit: this.authenticationScreen,
        is_display: this.examineScreen,
        admin_id: this.saleScreen,
        sort_type: this.sort_type,
        sort: this.sort,
        search_type: this.followupScreen,
        setmeal_deadline: this.setmealDeadline,
        weixin: this.weixin
      })
        .then(res => {
          // if (res.data.items.length >= 0) {
          //   this.tableData = res.data.items
          //   this.total = res.data.total
          //   this.currentPage = res.data.current_page
          // } else {
          //   this.tableData = []
          //   this.total = 0
          //   this.currentPage = 1
          // }
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    clueAdminLists() {
      clueAdminLists()
        .then(res => {
          this.saleScreenData = res.data
        })
        .catch(() => {})
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.getSalesStatistics()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.getSalesStatistics()
    },
    getFieldData() {
      customLndex({
        name: this.currentNav
      })
        .then(res => {
          if (res.data != '') {
            this.fieldData = JSON.parse(res.data)
          }
        })
        .catch(() => {})
    },
    setFieldConfirm() {
      customLndexAdd({
        name: this.currentNav,
        value: JSON.stringify(this.fieldData)
      })
        .then(res => {
          if (res.code == 200) {
            this.$message.success(res.message)
          } else {
            this.$message.error(res.message)
          }
          this.visiblepop = false
          this.menu_icon = 'menu'
        })
        .catch(() => {})
    },
    crmCustomList() {
      var list_type = this.list_type
      var list_type_z = 0
      if (list_type == 0) {
        list_type_z = 1
      }
      if (list_type == 1) {
        list_type_z = 2
      }
      if (list_type == 2) {
        list_type_z = 3
      }
      crmCustomList({
        menu: list_type_z,
        type: 2
      })
        .then(res => {
          this.fieldData = JSON.parse(res.data)
          for (var i = 0; i <= this.fieldData.length - 1; i++) {
            if (
              this.fieldData[i].field == 'addtime' ||
              this.fieldData[i].field == 'refreshtime' ||
              this.fieldData[i].field == 'last_visit_time' ||
              this.fieldData[i].field == 'collection_time'
            ) {
              this.fieldData[i].is_sortable = 'custom'
            } else {
              this.fieldData[i].is_sortable = false
            }
            this.fieldData[i].is_popover = false
            this.export_name.push(this.fieldData[i].name)
            this.export_field.push(this.fieldData[i].field)
          }
        })
        .catch(() => {})
    },
    locking(field) {
      for (var i = 0; i <= this.fieldData.length - 1; i++) {
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_locking == true) {
            this.fieldData[i].is_locking = false
            this.fieldData[i].is_lock_display = false
            this.fieldData[i].icon = 'el-icon-lock'
          } else {
            this.fieldData[i].icon = 'el-icon-unlock'
            this.fieldData[i].is_lock_display = false
            this.fieldData[i].is_locking = true
          }
        }
      }
      this.setFieldConfirm()
    },
    select(field) {
      for (var i = 0; i <= this.fieldData.length - 1; i++) {
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].select == true) {
            this.fieldData[i].select = false
          } else {
            this.fieldData[i].select = true
          }
        }
      }
    },
    menu() {
      if (this.menu_icon == 'menu') {
        this.visiblepop = true
        this.menu_icon = 'menu_select'
      } else {
        this.visiblepop = false
        this.menu_icon = 'menu'
      }
    },
    see(value) {
      this.drawer = true
      this.rowId = value.row.id
    },
    handleClose() {
      this.drawer = false
      this.clueList()
    },
    funPopover(field) {
      for (var i = 0; i <= this.fieldData.length - 1; i++) {
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_popover == true) {
            this.fieldData[i].is_popover = false
          } else {
            this.fieldData[i].is_popover = true
          }
        }
      }
    },
    locks(field) {
      for (var i = 0; i <= this.fieldData.length - 1; i++) {
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_lock_display == true) {
            this.fieldData[i].is_lock_display = false
          } else {
            this.fieldData[i].is_lock_display = true
          }
        }
      }
    },
    add() {
      this.$router.push('/user/company/add')
    },
    clickDial(phone, name) {
      this.$confirm('是否对【' + name + '】' + phone + ' 发起呼叫？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          outboundCall({
            mobile: phone
          })
            .then(res => {
              if (res.code == 200) {
                this.meetDialogVisible = true
                this.dialPhone = phone
                this.dialName = name
              }
              if (res.code == 4040) {
                this.callDialogVisible = true
              }
            })
            .catch(res => {})
        })
        .catch(() => {})
    },
    callHandleClose() {
      this.callDialogVisible = false
    },
    meetHandleClose() {
      this.meetDialogVisible = false
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
    }
  }
}
</script>

<style scoped lang="scss">
.dial {
  position: relative;
  top: 2px;
  width: 14px;
  height: 14px;
  display: inline-block;

  .disal_img {
    cursor: pointer;
    width: 14px;
    height: 14px;
    background: url('../../../../assets/images/outbound/dial.png') no-repeat center;
  }
}

.meet {
  .box-content {
    .img {
      width: 150px;
      height: 150px;
      text-align: center;
      margin-top: 40px;
      margin: 0 auto;
      background: url('../../../../assets/images/outbound/meet.png') no-repeat center;
    }

    .hu {
      text-align: center;
      margin-top: 30px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #949494;
    }

    .telephone {
      text-align: center;
      font-size: 30px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      margin-top: 26px;
    }

    .company {
      text-align: center;
      margin-top: 25px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #666666;
    }

    .tips {
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

.meet {
  ::v-deep .el-dialog__body {
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }

  ::v-deep .el-dialog {
    border-radius: 16px;
  }
}

.call {
  .box-content {
    padding: 20px 30px 35px 30px;

    .advantage {
      .advantage-box {
        display: inline-block;
        width: 170px;

        .title {
          display: inline-block;
          float: left;
        }

        .img {
          display: inline-block;
          float: left;
          margin-right: 8px;
        }
      }

      margin-top: 25px;
    }

    .slogan {
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #ff550a;
      margin-top: 24px;
    }

    .code {
      display: inline-block;
      margin-right: 30px;
      float: right;
      width: 117px;
      height: 117px;
      background: #ffffff;
      box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.14);
      border-radius: 8px;
      padding: 10px 10px;

      img {
        width: 100%;
        height: 100%;
      }
    }

    .content {
      display: inline-block;
      float: left;

      .title1 {
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #ff550a;
      }

      .title2 {
        margin-top: 18px;
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #555555;
      }

      .title3 {
        width: 133px;
        height: 24px;
        background: #ff6929;
        border-radius: 3px;
        text-align: center;
        line-height: 24px;
        font-size: 14px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #ffffff;
        margin-top: 23px;
      }
    }
  }
}

.call {
  ::v-deep .el-dialog__body {
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }

  ::v-deep .el-dialog {
    border-radius: 16px;
  }
}

.screen_s {
  max-height: 220px;
  overflow: auto;
}

.popover_box {
  padding: 15px 15px 15px 15px;
  position: absolute;
  top: 30px;
  right: -15px;
  z-index: 20;
  background: white;
}

.isDispayBox {
  text-align: center;
}

.sotrTime {
  width: 93%;
}

::v-deep .caret-wrapper {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

//::v-deep .el-checkbox-group{
//  overflow-y: scroll;
//}
.list-search {
  z-index: 1;
  position: relative;
}

.filterOperation {
  float: right;
  margin-right: 5px;
  color: #fff;
  background-color: #409eff;
  padding: 4px 6px;
  font-size: 12px;
  border-radius: 3px;
}

::v-deep .el-table__body-wrapper::-webkit-scrollbar {
  width: 10px; // 横向滚动条
  height: 10px; // 纵向滚动条 必写
}

::v-deep .el-table__body-wrapper::-webkit-scrollbar-thumb {
  background-color: #c1c7d0;
  border-radius: 2px;
}

.operation {
  font-size: 14px;
}

.unlock_display {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/unlock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/unlock1.png') no-repeat center center;
  }
}

.unlock {
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/unlock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/unlock1.png') no-repeat center center;
  }
}

.lock_display {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/lock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/lock1.png') no-repeat center center;
  }
}

.lock {
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/lock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/lock1.png') no-repeat center center;
  }
}

.drop_down {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/drop-down.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/drop-down1.png') no-repeat center center;
  }
}

.checkboxBtn {
  ::v-deep .el_button {
    padding: 4px 6px;
    font-size: 14px;
    border-radius: 3px;
  }
}

.field_s_select {
  background-color: #344563;
  border: 1px solid transparent;
  padding: 16px 10px;
}

.field_s {
  background: #f4f5f7;
  border: 1px solid transparent;
  padding: 16px 10px;
}

::v-deep .el-drawer {
  overflow: initial;
}

.close {
  width: 40px;
  height: 60px;
  position: absolute;
  left: -40px;
  top: 50%;
  transform: translateY(-50%);
  background: #f5f5f5;
  border-radius: 8px 0 0 8px;
  cursor: pointer;

  .el-icon-close {
    font-size: 30px;
    color: #777;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }
}

.setField_h {
  height: 250px;
  overflow: auto;
}

.setFields {
  margin: 15px 0px;
  font-size: 14px;
}

.setField {
  top: 60px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}

.screenStyle {
  margin: 10px 0px;
}

.bortton-page {
  margin-top: 20px;
  padding-bottom: 40px;
  text-align: right;
}

.input-with-select {
  float: left;
  margin-bottom: 20px;
}

.filterCriteria {
  border: 1px dashed #eeeeee;
  font-size: 13px;
  color: #999999;
  margin-top: 62px;
  margin-bottom: 16px;
  padding: 15px 16px 15px 16px;

  .selected {
    display: inline-block;
    font-size: 12px;
    color: #e6a23c;
    height: 26px;
    line-height: 26px;
    padding: 0 7px 0 7px;
    border: 1px solid #e6a23c;
    position: relative;
    margin-right: 10px;
    margin-bottom: 6px;
    border-radius: 3px;

    .name {
      display: inline-block;
    }

    .closes {
      display: inline-block;
      margin-left: 10px;
    }
  }
}

.setField_s {
  top: 0px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}

#animation {
  animation: pulse 1s 0.2s ease both infinite;
}

.app-container {
  padding: 20px;
  background: #f5f7f9;
}

.statictistics-box {
  width: 100%;
  overflow: hidden;
  margin-bottom: 15px;
  height: 255px;

  .statictistics-box1 {
    width: 38%;
    float: left;

    .line1 {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;

      .line_lf {
        width: calc(50% - 7.5px);
        background: #fff;
        border-radius: 6px;
        display: flex;
        justify-content: left;
        align-items: center;
        box-sizing: border-box;
        padding: 30px 20px;
        cursor: pointer;

        .lineLfIcon {
          display: inline-block;
          width: 50px;
          height: 50px;
          margin-right: 30px;
        }

        .lineLfTxt {
          .txt1 {
            font-size: 14px;
            color: #8e9ebb;
            margin: 0 0 20px 0;
          }

          .txt2 {
            font-size: 21px;
            color: #242424;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: end;
            margin: 0;

            .txtIcon {
              display: inline-block;
              width: 28px;
              height: 12px;
              margin-left: 12px;
            }
          }
        }
      }
    }

    .line2 {
      margin-bottom: 0;
    }
  }

  .statictistics-box2 {
    float: left;
    margin: 0 15px;
    background: #fff;
    border-radius: 6px;
    .line1 {
      .line_lf {
        cursor: initial;
      }
    }
  }

  .statictistics-box3 {
    width: calc(24% - 30px);
    float: right;
    background: #fff;
    height: 100%;
    border-radius: 6px;
    box-sizing: border-box;
    padding: 30px 0 0 0;

    .title {
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: start;

      .line {
        display: inline-block;
        width: 3px;
        height: 21px;
        background: #417fff;
        margin-right: 16px;
      }

      .txt {
        display: inline-block;
        color: #111111;
        font-size: 18px;
      }
    }

    .content {
      .content-line1 {
        display: flex;
        justify-content: space-between;
        align-items: center;

        .content-line-minibox {
          width: 50%;
          cursor: pointer;

          .p1 {
            text-align: center;
            font-size: 20px;
            color: #242424;
            margin: 31px 0 21px 0;
          }

          .p2 {
            text-align: center;
            font-size: 14px;
            color: #666666;
            margin: 0;
          }
        }
      }
    }
  }
}

.box-card-top {
  background: rgb(254, 254, 254);
  border: none;
  box-shadow: none;
  background: transparent;

  ::v-deep .el-card__body {
    padding: 0 !important;
  }
}

.chartTableBox {
  ::v-deep .el-tabs__nav-wrap::after {
    background-color: initial;
  }
}

.statisticsTab {
  ::v-deep .el-tabs__item {
    font-size: 18px;
    font-weight: bold;
  }
}

.table-box {
  width: 100%;

  .title {
    width: 100%;
    margin: 15px 0 24px;

    .line {
      display: inline-block;
      width: 3px;
      height: 21px;
      background: #417fff;
      margin-right: 16px;
      position: absolute;
      top: -1px;
      left: -20px;
    }

    .txt {
      display: inline-block;
      color: #333;
      font-size: 18px;
    }
  }
}

@-webkit-keyframes pulse {
  0% {
    -webkit-transform: scale(1);
  }

  50% {
    -webkit-transform: scale(1.1);
  }

  100% {
    -webkit-transform: scale(1);
  }
}

@-moz-keyframes pulse {
  0% {
    -moz-transform: scale(1);
  }

  50% {
    -moz-transform: scale(1.1);
  }

  100% {
    -moz-transform: scale(1);
  }
}
</style>
