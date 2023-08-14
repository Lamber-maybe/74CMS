<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ title }}</span>
      </div>
      <div style="float:right;z-index: 1;position: relative;">
        <el-button size="small" type="primary" @click="reset('all')">
          重置筛选
        </el-button>
        <el-button size="small" type="primary" @click="goto('/user/company/add')">
          新增企业
        </el-button>
      </div>
      <div class="list-search">
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
            <el-option label="公司名称" value="1" />
            <el-option label="手机号码" value="2" />
          </el-select>
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="funSearchKeyword"
          />
        </el-input>
      </div>
      <div style="position: relative;">
        <div class="setField">
          <el-popover
            v-model="visiblepop"
            placement="bottom-start"
            width="220"
            trigger="manual"
          >
            <div class="setField_h">
              <div v-for="item in fieldData" class="setFields">
                <input v-if="item.select == true" type="checkbox" :name="item.name" :value="item.field" checked @change="select(item.field)">
                <input v-if="item.select == false" type="checkbox" :name="item.name" :value="item.field" @change="select(item.field)">
                <span style="margin-left: 10px;">{{ item.name }}</span>
              </div>
            </div>
            <el-button slot="reference" :class="menu_icon == 'menu'?'field_s':'field_s_select'" @click="menu">
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
          :header-cell-style="{background:'#f9f9f9', 'border-right': '1px solid #e4e6eb'}"
          border
          stripe
          :default-sort="{prop: 'addtime'}"
          style="width: 100%;"
          @sort-change="sortTable"
          @selection-change="handleSelectionChange"
        >
          <el-table-column
            fixed
            type="selection"
            width="55"
          />
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == true && items.select == true"
            fixed
            :sortable="items.is_sortable"
            :prop="items.field"
            :width="items.width"
          >
            <template slot="header" slot-scope="scope">
              <div :class="items.is_sortable == 'custom' ? 'sotrTime':''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span v-if="items.field == 'not_following_day' ">{{ items.name }}(天)</span>
                <span v-else>{{ items.name }}</span>
                <el-popover
                  placement="bottom"
                  trigger="hover"
                >
                  <!-- 等级-->
                  <div v-if="items.field == 'life_cycle_txt'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in gradeScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="gradeScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 等级-->
                  <!-- 审核-->
                  <div v-if="items.field == 'is_display'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in examineScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="examineScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 审核-->
                  <!-- 套餐-->
                  <div v-if="items.field == 'setmeal_name'" class="screen_s">
                    <el-checkbox-group style="height: 250px;">
                      <div v-for="item in mealScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="mealScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 套餐-->
                  <!-- 未跟进-->
                  <div v-if="items.field == 'not_following_day'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in followupScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="followupScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 未跟进-->
                  <!-- 在招职位-->
                  <div v-if="items.field == 'jobs_num'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in positionScreenData" class="screenStyle">
                        <el-radio v-model="positionScreen" :label="item.id">{{ item.name }}</el-radio>
                        <!--                        <el-checkbox :key="item.id" :label="item.id">{{ item.name }}</el-checkbox>-->
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 在招职位-->
                  <!-- 认证状态-->
                  <div v-if="items.field == 'audit'" class="screen_s">
                    <el-checkbox-group v-model="authenticationScreen">
                      <div v-for="item in authenticationScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="authenticationScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 认证状态-->
                  <!-- 销售-->
                  <div v-if="items.field == 'admin_username'" class="screen_s">
                    <el-checkbox-group v-model="saleScreen">
                      <div v-for="item in saleScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="saleScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <div style="margin-top: 10px">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm">确认</el-button>
                  </div>
                  <!-- 销售-->
                  <div v-if="items.field == 'life_cycle_txt' || items.field == 'is_display' || items.field == 'setmeal_name' || items.field == 'not_following_day' || items.field == 'jobs_num' || items.field=='audit' || items.field =='admin_username' " slot="reference" class="drop_down" @click.stop="funPopover(items.field)" />
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''"> 未完善企业资料 </span>
                <span v-else> {{ scope.row.id }} </span>
              </div>
              <div v-if="items.field == 'companyname'">
                <span v-if="scope.row.companyname == ''"> - </span>
                <span v-else> {{ scope.row.companyname }} </span>
              </div>
              <div v-if="items.field == 'life_cycle_txt'">
                <span v-if="scope.row.life_cycle_txt == ''"> - </span>
                <span v-else> {{ scope.row.life_cycle_txt }} </span>
              </div>
              <div v-if="items.field == 'contact'">
                <span v-if="scope.row.contact == ''"> - </span>
                <span v-else> {{ scope.row.contact }} </span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="scope.row.mobile == ''"> - </span>
                <span v-else> {{ scope.row.mobile }} </span>
              </div>
              <div v-if="items.field == 'jobs_num'">
                <span v-if="scope.row.jobs_num == ''"> 0 </span>
                <span v-else> {{ scope.row.jobs_num }} </span>
              </div>
              <div v-if="items.field == 'job_apply_count'">
                <span v-if="scope.row.job_apply_count == ''"> 0 </span>
                <span v-else> {{ scope.row.job_apply_count }} </span>
              </div>
              <div v-if="items.field == 'setmeal_name'">
                <span v-if="scope.row.setmeal_name == ''"> - </span>
                <span v-else> {{ scope.row.setmeal_name }} </span>
              </div>
              <div v-if="items.field == 'refreshtime'">
                <span v-if="scope.row.refreshtime == ''"> - </span>
                <span v-else> {{ scope.row.refreshtime }} </span>
              </div>
              <div v-if="items.field == 'addtime'">
                <span v-if="scope.row.addtime == ''"> - </span>
                <span v-else> {{ scope.row.addtime }} </span>
              </div>
              <div v-if="items.field == 'admin_username'">
                <span v-if="scope.row.admin_username == ''"> - </span>
                <span v-else> {{ scope.row.admin_username }} </span>
              </div>
              <div v-if="items.field == 'audit'">
                <span v-if="scope.row.audit == ''"> - </span>
                <span v-else> {{ scope.row.audit }} </span>
              </div>
              <div v-if="items.field == 'is_display'">
                <span v-if="scope.row.is_display == ''"> - </span>
                <span v-else> {{ scope.row.is_display }} </span>
              </div>
              <div v-if="items.field == 'last_visit_time'">
                <span v-if="scope.row.last_visit_time == ''"> - </span>
                <span v-else> {{ scope.row.last_visit_time }} </span>
              </div>
              <div v-if="items.field == 'not_following_day'">
                <span v-if="scope.row.not_following_day == -1"> - </span>
                <span v-else-if="scope.row.not_following_day == 0 ">0</span>
                <span v-else> {{ scope.row.not_following_day }} </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == false && items.select == true"
            :sortable="items.is_sortable"
            :prop="items.field"
            :width="items.width"
          >
            <template slot="header" slot-scope="scope">
              <div :class="items.is_sortable == 'custom' ? 'sotrTime':''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span v-if="items.field == 'not_following_day' ">{{ items.name }}(天)</span>
                <span v-else>{{ items.name }}</span>
                <el-popover
                  placement="bottom"
                  trigger="hover"
                >
                  <!-- 等级-->
                  <div v-if="items.field == 'life_cycle_txt'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in gradeScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="gradeScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 等级-->
                  <!-- 审核-->
                  <div v-if="items.field == 'is_display'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in examineScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="examineScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 审核-->
                  <!-- 套餐-->
                  <div v-if="items.field == 'setmeal_name'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in mealScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="mealScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 套餐-->
                  <!-- 未跟进-->
                  <div v-if="items.field == 'not_following_day'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in followupScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="followupScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 未跟进-->
                  <!-- 在招职位-->
                  <div v-if="items.field == 'jobs_num'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in positionScreenData" class="screenStyle">
                        <el-radio v-model="positionScreen" :label="item.id">{{ item.name }}</el-radio>
                        <!--                        <el-checkbox :key="item.id" :label="item.id">{{ item.name }}</el-checkbox>-->
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 在招职位-->
                  <!-- 认证状态-->
                  <div v-if="items.field == 'audit'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in authenticationScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="authenticationScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 认证状态-->
                  <!-- 销售-->
                  <div v-if="items.field == 'admin_username'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in saleScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="saleScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <div style="margin-top: 10px">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm">确认</el-button>
                  </div>
                  <!-- 销售-->
                  <div v-if="items.field == 'life_cycle_txt' || items.field == 'is_display' || items.field == 'setmeal_name' || items.field == 'not_following_day' || items.field == 'jobs_num' || items.field=='audit' || items.field =='admin_username' " slot="reference" class="drop_down" @click.stop="funPopover(items.field)" />
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''"> - </span>
                <span v-else> {{ scope.row.id }} </span>
              </div>
              <div v-if="items.field == 'companyname'">
                <span v-if="scope.row.companyname == ''"> 未完善企业资料 </span>
                <span v-else> {{ scope.row.companyname }} </span>
              </div>
              <div v-if="items.field == 'life_cycle_txt'">
                <span v-if="scope.row.life_cycle_txt == ''"> - </span>
                <span v-else> {{ scope.row.life_cycle_txt }} </span>
              </div>
              <div v-if="items.field == 'contact'">
                <span v-if="scope.row.contact == ''"> - </span>
                <span v-else> {{ scope.row.contact }} </span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="scope.row.mobile == ''"> - </span>
                <span v-else> {{ scope.row.mobile }} </span>
              </div>
              <div v-if="items.field == 'jobs_num'">
                <span v-if="scope.row.jobs_num == ''"> 0 </span>
                <span v-else> {{ scope.row.jobs_num }} </span>
              </div>
              <div v-if="items.field == 'job_apply_count'">
                <span v-if="scope.row.job_apply_count == ''"> 0 </span>
                <span v-else> {{ scope.row.job_apply_count }} </span>
              </div>
              <div v-if="items.field == 'setmeal_name'">
                <span v-if="scope.row.setmeal_name == ''"> - </span>
                <span v-else> {{ scope.row.setmeal_name }} </span>
              </div>
              <div v-if="items.field == 'refreshtime'">
                <span v-if="scope.row.refreshtime == ''"> - </span>
                <span v-else> {{ scope.row.refreshtime }} </span>
              </div>
              <div v-if="items.field == 'addtime'">
                <span v-if="scope.row.addtime == ''"> - </span>
                <span v-else> {{ scope.row.addtime }} </span>
              </div>
              <div v-if="items.field == 'admin_username'">
                <span v-if="scope.row.admin_username == ''"> - </span>
                <span v-else> {{ scope.row.admin_username }} </span>
              </div>
              <div v-if="items.field == 'audit'">
                <span v-if="scope.row.audit == ''"> - </span>
                <span v-else> {{ scope.row.audit }} </span>
              </div>
              <div v-if="items.field == 'is_display'">
                <span v-if="scope.row.is_display == ''"> - </span>
                <span v-else> {{ scope.row.is_display }} </span>
              </div>
              <div v-if="items.field == 'last_visit_time'">
                <span v-if="scope.row.last_visit_time == ''"> - </span>
                <span v-else> {{ scope.row.last_visit_time }} </span>
              </div>
              <div v-if="items.field == 'not_following_day'">
                <span v-if="scope.row.not_following_day == -1"> - </span>
                <span v-else-if="scope.row.not_following_day == 0 ">0</span>
                <span v-else> {{ scope.row.not_following_day }} </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            label="操作"
            fixed="right"
            width="160"
          >
            <template slot-scope="scope">
              <el-button class="operation" type="text" size="small" @click="see(scope)">查看</el-button>
              <el-button v-if="currentNav == 'myClient'" class="operation" type="text" size="small" @click="release(scope.row.uid)">释放</el-button>
              <el-button v-if="currentNav == 'myClient'" class="operation" type="text" size="small" @click="see(scope)">跟进</el-button>
              <el-button v-if="currentNav == 'pubilceClient'" class="operation" type="text" size="small" @click="receive(scope.row.uid)">领取</el-button>
              <el-button v-if="currentNav == 'pubilceClient'" class="operation" type="text" size="small" @click="del(scope.row.id)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="bortton-page">
        <el-col :span="12">
          <el-button size="small" type="primary" @click="choose">
            全选/反选
          </el-button>
          <el-button v-if="currentNav == 'myClient'" size="small" type="primary" @click="funRelease">
            释放
          </el-button>
          <el-button v-if="currentNav == 'pubilceClient'" size="small" type="primary" @click="funReceive">
            领取
          </el-button>
          <el-button size="small" type="primary" @click="funAuditBatch">
            认证
          </el-button>
          <el-button size="small" type="primary" @click="displayBatch">
            显示
          </el-button>
          <el-button size="small" type="primary" @click="handlerRefreshJobBatch">
            刷新职位
          </el-button>
          <el-button size="small" type="primary" @click="funExport">
            导出
          </el-button>
        </el-col>
        <el-col :span="12" style="text-align: right">
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
      </div>
    </el-card>
    <el-dialog
      title="显示状态设置"
      :visible.sync="dialogIsDispay"
      width="25%"
    >
      <div class="isDispayBox">
        显示状态：
        <el-radio v-model="is_display" label="0">不显示</el-radio>
        <el-radio v-model="is_display" label="1">显示</el-radio>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogIsDispay = false">取 消</el-button>
        <el-button type="primary" @click="funIsDisplayBtn"> 确 定 </el-button>
      </div>
    </el-dialog>
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
    <el-drawer
      v-if="drawer"
      size="75%"
      :show-close="false"
      :with-header="false"
      :wrapper-closable="false"
      :visible.sync="drawer"
      :before-close="handleClose"
    >
      <clientShow :row-id="rowId" />
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
  </div>
</template>
<script>

import clientShow from '@/views/user/company/crm/components/ClientShow'
import {
  classify,
  clueAdminLists,
  crmCompanyList,
  crmCustomList,
  crmCustomListEdit,
  lifeCycle,
  companyCrmAudit,
  refreshCrmJob,
  exportCrmCompanyById,
  companyReceive,
  companyRelease,
  companySetDisplay,
  putRecycleBin
} from '@/api/company_crm'
import { export_json_to_excel } from '@/excel/Export2Excel'
import { parseTime } from '@/utils'

export default {
  name: 'Client',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    clientShow
  },
  data () {
    return {
      loading: true,
      dialogIsDispay: false,
      visiblepop: false,
      setAuditVal: '',
      setAuditReason: '',
      form_options_audit: [],
      dialogFormVisible: false,
      menu_icon: 'menu',
      drawer: false,
      title: '全部企业',
      key_type: '1',
      keyword: '',
      fieldData: '',
      authenticationScreen: '',
      authenticationScreenData: [], // 认证
      positionScreen: '',
      positionScreenData: [
        { 'id': 1, 'name': '无' },
        { 'id': 2, 'name': '有' }
      ], // 在招职位
      followupScreen: '',
      followupScreenData: [
        { 'id': 0, 'name': '全部' },
        { 'id': 1, 'name': '今日跟进' },
        { 'id': 2, 'name': '30天内跟进过' },
        { 'id': 3, 'name': '7天内未跟进' },
        { 'id': 4, 'name': '15天内未跟进' },
        { 'id': 5, 'name': '30天内未跟进' },
        { 'id': 6, 'name': '从未跟进' },
        { 'id': 7, 'name': '即将转公客' }
      ],
      examineScreen: '',
      examineScreenData: [
        { 'id': 1, 'name': '显示中' },
        { 'id': 0, 'name': '不显示' }
      ], // 审核
      mealScreen: '',
      mealScreenData: [], // 套餐
      gradeScreen: '',
      gradeScreenData: [], // 等级
      saleScreen: '',
      saleScreenData: [], // 销售
      pagesize: 10,
      total: 0,
      currentPage: 1,
      saleKey: [
        { text: 'aa', value: 'aa' },
        { text: 'bb', value: 'bb' },
        { text: 'cc', value: 'cc' },
        { text: 'dd', value: 'dd' }
      ],
      tableData: [],
      currentNav: '',
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
      exportData: []
    }
  },
  computed: {
    clueTime() {
      var clueTime = this.$route.query.time
      return clueTime
    }
  },
  watch: {
    clueTime(){
      var clue_type = localStorage.getItem('clue_type')
      var clue_company_id = localStorage.getItem('clue_company_id')
      if (clue_type != '' && clue_type == 'follow'){
        this.drawer = true
        this.rowId = clue_company_id
        localStorage.setItem('clue_company_id', '')
        localStorage.setItem('clue_type', '')
      }
    }
  },
  created () {
    this.currentNav = this.$route.name
    if (this.currentNav == 'allClient'){
      this.title = '全部客户'
      this.list_type = 0
    } else if (this.currentNav == 'myClient'){
      this.title = '我的客户'
      this.list_type = 2
    } else if (this.currentNav == 'pubilceClient'){
      this.title = '客户公海'
      this.list_type = 1
    }
    this.crmCustomList()
    this.clueList()
    this.classify()
    this.lifeCycle()
    this.clueAdminLists()
    var clue_type = localStorage.getItem('clue_type')
    var clue_company_id = localStorage.getItem('clue_company_id')
    if (clue_type != '' && clue_type == 'follow'){
      this.drawer = true
      this.rowId = clue_company_id
      localStorage.setItem('clue_company_id', '')
      localStorage.setItem('clue_type', '')
    }
  },
  // mounted() {
  //   document.addEventListener('click', this.bodyCloseSelected)
  // },
  methods: {
    // bodyCloseSelected() {
    //   for (var i = 0; i <= this.fieldData.length - 1; i++){
    //     this.fieldData[i].is_popover = false
    //   }
    // },
    del(id){
      this.$confirm('确定将该企业放置回收站？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        putRecycleBin({ 'company_id': id }).then(response => {
          if (response.code == 200){
            this.$message.success(response.message)
            this.clueList()
          } else {
            this.$message.error(response.message)
          }
        })
      }).catch(() => {

      })
    },
    funIsDisplayBtn(){
      companySetDisplay({ 'id': this.multipleSelection, 'is_display': this.is_display }).then(response => {
        if (response.code == 200){
          this.$message.success(response.message)
          this.dialogIsDispay = false
          this.clueList()
        } else {
          this.$message.error(response.message)
        }
      })
    },
    funReceive() {
      var that = this
      if (that.multipleSelection.length == 0) {
        that.$message.error('请选择要领取的企业')
        return false
      }
      this.receive(that.uid_arr)
    },
    funRelease() {
      var that = this
      if (that.multipleSelection.length == 0) {
        that.$message.error('请选择要释放的企业')
        return false
      }
      this.release(that.uid_arr)
    },
    release(id){
      this.$confirm('确定要释放该企业', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        companyRelease({ 'uid': id }).then(response => {
          if (response.code == 200){
            this.$message.success(response.message)
            this.clueList()
          } else {
            this.$message.error(response.message)
          }
        })
      }).catch(() => {
      })
    },
    receive(id){
      this.$confirm('确定要领取该企业吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        companyReceive({ 'uid': id }).then(response => {
          if (response.code == 200){
            this.$message.success(response.message)
            this.clueList()
          } else {
            this.$message.error(response.message)
          }
        })
      }).catch(() => {

      })
    },
    goto(target) {
      this.$router.push(target)
    },
    sortTable({ column, order }){
      if (order == 'ascending'){
        this.sortType = 'asc'
      } else if (order == 'descending'){
        this.sortType = 'desc'
      } else {
        this.sortType = ''
      }
      this.sort_type = column.property
      this.sort = this.sortType
      this.clueList()
    },
    funExport() {
      var that = this
      if (that.multipleSelection.length == 0) {
        that.$message.error('请选择要导出的企业')
        return false
      }
      var exportData = this.exportData
      for (var i = 0; i <= exportData.length - 1; i++){
        if (exportData[i].not_following_day == -1){
          exportData[i].not_following_day = ''
        }
      }
      that.exportExcel(this.exportData)
      // exportCrmCompanyById(param).then(response => {
      //   if (response.code == 200) {
      //     console.log(response.data.items)
      //     that.exportExcel(response.data.items)
      //   } else {
      //     that.$message.error(response.message)
      //     that.waiting = false
      //   }
      //   return true
      // })
    },
    exportExcel(list) {
      require.ensure([], () => {
        const { export_json_to_excel } = require('@/excel/Export2Excel')
        const tHeader = this.export_name // 上面设置Excel的表格第一行的标题
        const filterVal = this.export_field
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
    handlerRefreshJobBatch() {
      if (this.multipleSelection.length <= 0){
        this.$message({ type: 'error', message: '请选择要刷新的企业' })
        return false
      }
      this.$confirm('确定要批量刷新所选企业的所有职位？', '系统提示', {
        type: 'warning'
      })
        .then(() => {
          var companyIdstr = this.multipleSelection.join(',')
          refreshCrmJob({ company_id: companyIdstr, is_batch: 1 })
            .then(res => {
              if (res.code == 200) {
                this.$message({ type: 'success', message: res.message })
                this.clueList()
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
    displayBatch(){
      if (this.multipleSelection.length == 0) {
        this.$message.error('请选择要设置的企业')
        return false
      }
      this.dialogIsDispay = true
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    fun_set_audit() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      const params = {
        id: this.multipleSelection,
        audit: this.setAuditVal,
        reason: this.setAuditReason
      }
      companyCrmAudit(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.auditSubmitLoading = false
          this.clueList()
          this.closeDialog()
          return true
        } else {
          this.auditSubmitLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    choose(){
      this.tableData.forEach(row => {
        this.$refs.multipleTable.toggleRowSelection(row)
      })
    },
    handleSelectionChange(val) {
      var id_arr = []
      var uid_arr = []
      val.forEach(row => {
        id_arr.push(row.id)
        uid_arr.push(row.uid)
      })
      this.uid_arr = uid_arr
      this.multipleSelection = id_arr
      this.exportData = val
    },
    funAuditBatch() {
      if (this.multipleSelection.length == 0) {
        this.$message.error('请选择要认证的企业')
        return false
      }
      this.setAuditVal = 0
      this.dialogFormVisible = true
    },
    setFieldClose(){
      this.menu_icon = 'menu'
      this.visiblepop = false
    },
    reset(field){
      if (field == 'all'){
        this.authenticationScreen = []
        this.positionScreen = ''
        this.followupScreen = []
        this.examineScreen = []
        this.mealScreen = []
        this.gradeScreen = []
        this.saleScreen = []
        this.key_type = '1'
        this.keyword = ''
      }
      if (field == 'audit'){
        this.authenticationScreen = []
      }
      if (field == 'jobs_num'){
        this.positionScreen = ''
      }
      if (field == 'not_following_day'){
        this.followupScreen = []
      }
      if (field == 'is_display'){
        this.examineScreen = []
      }
      if (field == 'setmeal_name'){
        this.mealScreen = []
      }
      if (field == 'life_cycle_txt'){
        this.gradeScreen = []
      }
      if (field == 'admin_username'){
        this.saleScreen = []
      }
      this.clueList()
    },
    confirm(){
      this.clueList()
      this.crmCustomList()
    },
    funSearchKeyword(){
      this.clueList()
    },
    classify(){
      classify({ 'type': 'setmealList,companyAudit' }).then(res => {
        this.mealScreenData = res.data.setmealList
        const options_audit_arr = [...res.data.companyAudit]
        options_audit_arr.forEach(el => {
          this.authenticationScreenData[el.id] = el.name
        })
        this.form_options_audit = [...this.authenticationScreenData]

        this.authenticationScreenData = res.data.companyAudit
        this.authenticationScreenData.push({ 'name': '未认证', 'id': 3 })
      }).catch(() => {

      })
    },
    lifeCycle(){
      lifeCycle().then(res => {
        this.gradeScreenData = res.data
      }).catch(() => {

      })
    },
    clueList(){
      if (localStorage.getItem('clue_audit') && localStorage.getItem('clue_audit') == 1) {
        this.authenticationScreen = 0
        localStorage.setItem('clue_audit', '')
      }
      crmCompanyList({
        'page': this.currentPage,
        'pagesize': this.pagesize,
        'list_type': this.list_type,
        'key_type': this.key_type,
        'keyword': this.keyword,
        'life_cycle_id': this.gradeScreen,
        'job_count': this.positionScreen,
        'setmeal_id': this.mealScreen,
        'audit': this.authenticationScreen,
        'is_display': this.examineScreen,
        'admin_id': this.saleScreen,
        'sort_type': this.sort_type,
        'sort': this.sort,
        'search_type': this.followupScreen
      }
      )
        .then(res => {
          if (res.data.items.length >= 0){
            this.tableData = res.data.items
            this.total = res.data.total
            this.currentPage = res.data.current_page
          } else {
            this.tableData = []
            this.total = 0
            this.currentPage = 1
          }
          this.loading = false
        }).catch(() => {

        })
    },
    clueAdminLists(){
      clueAdminLists().then(res => {
        this.saleScreenData = res.data
      }).catch(() => {

      })
    },
    handleSizeChange(val){
      this.pagesize = val
      this.clueList()
    },
    handleCurrentChange(val){
      this.currentPage = val
      this.clueList()
    },
    setFieldConfirm(){
      var list_type = this.list_type
      var list_type_z = 0
      if (list_type == 0){
        list_type_z = 1
      }
      if (list_type == 1){
        list_type_z = 2
      }
      if (list_type == 2){
        list_type_z = 3
      }
      crmCustomListEdit({ 'menu': list_type_z, 'type': 2, 'value': JSON.stringify(this.fieldData) }, 'post')
        .then(res => {
          if (res.code == 200){
            this.$message.success(res.message)
          } else {
            this.$message.error(res.message)
          }
          this.visiblepop = false
          this.menu_icon = 'menu'
        }).catch(() => {

        })
    },
    crmCustomList(){
      var list_type = this.list_type
      var list_type_z = 0
      if (list_type == 0){
        list_type_z = 1
      }
      if (list_type == 1){
        list_type_z = 2
      }
      if (list_type == 2){
        list_type_z = 3
      }
      crmCustomList({ 'menu': list_type_z, 'type': 2 })
        .then(res => {
          this.fieldData = JSON.parse(res.data)
          for (var i = 0; i <= this.fieldData.length - 1; i++){
            if (this.fieldData[i].field == 'addtime' || this.fieldData[i].field == 'refreshtime' || this.fieldData[i].field == 'last_visit_time'){
              this.fieldData[i].is_sortable = 'custom'
            } else {
              this.fieldData[i].is_sortable = false
            }
            this.fieldData[i].is_popover = false
            this.export_name.push(this.fieldData[i].name)
            this.export_field.push(this.fieldData[i].field)
          }
        }).catch(() => {

        })
    },
    locking(field){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_locking == true){
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
    select(field){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].select == true){
            this.fieldData[i].select = false
          } else {
            this.fieldData[i].select = true
          }
        }
      }
    },
    menu(){
      if (this.menu_icon == 'menu') {
        this.visiblepop = true
        this.menu_icon = 'menu_select'
      } else {
        this.visiblepop = false
        this.menu_icon = 'menu'
      }
    },
    see(value){
      this.drawer = true
      this.rowId = value.row.id
    },
    handleClose(){
      this.drawer = false
      this.clueList()
    },
    funPopover(field){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_popover == true){
            this.fieldData[i].is_popover = false
          } else {
            this.fieldData[i].is_popover = true
          }
        }
      }
    },
    locks(field){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_lock_display == true){
            this.fieldData[i].is_lock_display = false
          } else {
            this.fieldData[i].is_lock_display = true
          }
        }
      }
    },
    add(){
      this.$router.push('/user/company/add')
    }
  }
}
</script>

<style scoped lang="scss">
.screen_s{
  max-height: 220px;
  overflow: auto
}
.popover_box{
  padding:15px 15px 15px 15px;
  position: absolute;
  top:30px;
  right: -15px;
  z-index: 20;
  background: white;
}
.isDispayBox{
  text-align: center;
}
.sotrTime{
  width: 93%;
}
::v-deep .caret-wrapper{
  position: absolute;
  right: 0;
  top:50%;
  transform: translateY(-50%);
}
//::v-deep .el-checkbox-group{
//  overflow-y: scroll;
//}
.list-search{
  z-index: 1;
  position: relative;
}
.filterOperation{
  float: right;
  margin-right: 5px;
  color: #fff;
  background-color: #409EFF;;
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
.operation{
  font-size: 14px;
}
.unlock_display{
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/unlock.png") no-repeat center center;
  &:hover{
    background: url("../../../../assets/images/company/crm/unlock1.png") no-repeat center center;
  }
}
.unlock{
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/unlock.png") no-repeat center center;
  &:hover{
    background: url("../../../../assets/images/company/crm/unlock1.png") no-repeat center center;
  }
}
.lock_display{
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/lock.png") no-repeat center center;
  &:hover{
    background: url("../../../../assets/images/company/crm/lock1.png") no-repeat center center;
  }
}
.lock{
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/lock.png") no-repeat center center;
  &:hover{
    background: url("../../../../assets/images/company/crm/lock1.png") no-repeat center center;
  }
}
.drop_down{
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/drop-down.png") no-repeat center center;
  &:hover{
    background: url("../../../../assets/images/company/crm/drop-down1.png") no-repeat center center;
  }
}
.checkboxBtn{
  ::v-deep .el_button{
    padding: 4px 6px;
    font-size: 14px;
    border-radius: 3px;
  }
}
.field_s_select{
  background-color: #344563;
  border: 1px solid transparent;
  padding:16px 10px;
}
.field_s{
  background: #f4f5f7;
  border: 1px solid transparent;
  padding:16px 10px;
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
.setField_h{
  height: 250px;
  overflow: auto;
}
.setFields{
  margin:15px 0px;
  font-size: 14px;
}
.setField{
  top:60px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}
.screenStyle{
  margin:10px 0px;
}
.bortton-page{
  margin-top: 20px;
  padding-bottom: 40px;
}
.input-with-select{
  float:left;
  margin-bottom: 20px;
}
</style>

