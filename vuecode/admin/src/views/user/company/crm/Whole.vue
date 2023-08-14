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
        <el-button size="small" type="primary" @click="goto('/user/company/crm/wholeAdd')">
          新增线索
        </el-button>
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          style="width: 440px;"
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
            <el-option label="线索名称" value="1" />
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
          style="width: 100%;"
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
            :prop="items.field"
            :width="items.width"
          >
            <template slot="header" slot-scope="scope">
              <div @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover
                  placement="bottom"
                  trigger="hover"
                >
                  <!-- 销售-->
                  <div v-if="items.field == 'admin_username'">
                    <el-checkbox-group style="height: 250px;">
                      <div v-for="item in saleScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="saleScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 销售-->
                  <!-- 行业-->
                  <div v-if="items.field == 'trade'">
                    <el-checkbox-group>
                      <div v-for="item in industryScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="industryScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 行业-->
                  <!-- 地区-->
                  <div v-if="items.field == 'district'">
                    <el-cascader-panel
                      v-model="regionScreen"
                      :props="props"
                      clearable
                      :options="regionScreenData"
                    />
                  </div>
                  <!-- 地区-->
                  <!-- 创建人-->
                  <div v-if="items.field == 'creat_username'">
                    <el-checkbox-group>
                      <div v-for="item in createdByScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="createdByScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div v-if="items.field == 'admin_username' || items.field == 'trade' || items.field == 'district' || items.field == 'creat_username'" slot="reference" class="drop_down" />
                <!-- 创建人-->
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''"> - </span>
                <span v-else> {{scope.row.id}} </span>
              </div>
              <div v-if="items.field == 'name'">
                <span v-if="scope.row.name == ''"> - </span>
                <span v-else> {{scope.row.name}} </span>
              </div>
              <div v-if="items.field == 'admin_username'">
                <span v-if="scope.row.admin_username == ''"> - </span>
                <span v-else> {{scope.row.admin_username}} </span>
              </div>
              <div v-if="items.field == 'contacts'">
                <span v-if="scope.row.contacts == ''"> - </span>
                <span v-else> {{scope.row.contacts}} </span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="scope.row.mobile == ''"> - </span>
                <span v-else> {{scope.row.mobile}} </span>
              </div>
              <div v-if="items.field == 'weixin'">
                <span v-if="scope.row.weixin == ''"> - </span>
                <span v-else> {{scope.row.weixin}} </span>
              </div>
              <div v-if="items.field == 'trade'">
                <span v-if="scope.row.trade == ''"> - </span>
                <span v-else> {{scope.row.trade}} </span>
              </div>
              <div v-if="items.field == 'district'">
                <span v-if="scope.row.district == ''"> - </span>
                <span v-else> {{scope.row.district}} </span>
              </div>
              <div v-if="items.field == 'address'">
                <span v-if="scope.row.address == ''"> - </span>
                <span v-else> {{scope.row.address}} </span>
              </div>
              <div v-if="items.field == 'updatetime'">
                <span v-if="scope.row.updatetime == ''"> - </span>
                <span v-else> {{scope.row.updatetime}} </span>
              </div>
              <div v-if="items.field == 'follow_count'">
                <span v-if="scope.row.follow_count == ''"> 0 </span>
                <span v-else> {{scope.row.follow_count}} </span>
              </div>
              <div v-if="items.field == 'creat_username'">
                <span v-if="scope.row.creat_username == ''"> - </span>
                <span v-else> {{scope.row.creat_username}} </span>
              </div>
              <div v-if="items.field == 'remark'">
                <span v-if="scope.row.remark == ''"> - </span>
                <span v-else> {{scope.row.remark}} </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == false && items.select == true"
            :prop="items.field"
            :width="items.width"
          >
            <template slot="header" slot-scope="scope">
              <div @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover
                  placement="bottom"
                  trigger="hover"
                >
                  <!-- 销售-->
                  <div v-if="items.field == 'admin_username'">
                    <el-checkbox-group>
                      <div v-for="item in saleScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="saleScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 销售-->
                  <!-- 行业-->
                  <div v-if="items.field == 'trade'">
                    <el-checkbox-group>
                      <div v-for="item in industryScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="industryScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 行业-->
                  <!-- 地区-->
                  <div v-if="items.field == 'district'">
                    <el-cascader-panel
                      v-model="regionScreen"
                      :props="props"
                      clearable
                      :options="regionScreenData"
                    />
                  </div>
                  <!-- 地区-->
                  <!-- 创建人-->
                  <div v-if="items.field == 'creat_username'">
                    <el-checkbox-group>
                      <div v-for="item in createdByScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="createdByScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div v-if="items.field == 'creat_username' || items.field == 'admin_username' || items.field == 'trade' || items.field == 'district'" slot="reference" class="drop_down" />
                  <!-- 创建人-->
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
              </div>

            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''"> - </span>
                <span v-else> {{scope.row.id}} </span>
              </div>
              <div v-if="items.field == 'name'">
                <span v-if="scope.row.name == ''"> - </span>
                <span v-else> {{scope.row.name}} </span>
              </div>
              <div v-if="items.field == 'admin_username'">
                <span v-if="scope.row.admin_username == ''"> - </span>
                <span v-else> {{scope.row.admin_username}} </span>
              </div>
              <div v-if="items.field == 'contacts'">
                <span v-if="scope.row.contacts == ''"> - </span>
                <span v-else> {{scope.row.contacts}} </span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="scope.row.mobile == ''"> - </span>
                <span v-else> {{scope.row.mobile}} </span>
              </div>
              <div v-if="items.field == 'weixin'">
                <span v-if="scope.row.weixin == ''"> - </span>
                <span v-else> {{scope.row.weixin}} </span>
              </div>
              <div v-if="items.field == 'trade'">
                <span v-if="scope.row.trade == ''"> - </span>
                <span v-else> {{scope.row.trade}} </span>
              </div>
              <div v-if="items.field == 'district'">
                <span v-if="scope.row.district == ''"> - </span>
                <span v-else> {{scope.row.district}} </span>
              </div>
              <div v-if="items.field == 'address'">
                <span v-if="scope.row.address == ''"> - </span>
                <span v-else> {{scope.row.address}} </span>
              </div>
              <div v-if="items.field == 'updatetime'">
                <span v-if="scope.row.updatetime == ''"> - </span>
                <span v-else> {{scope.row.updatetime}} </span>
              </div>
              <div v-if="items.field == 'follow_count'">
                <span v-if="scope.row.follow_count == ''"> 0 </span>
                <span v-else> {{scope.row.follow_count}} </span>
              </div>
              <div v-if="items.field == 'creat_username'">
                <span v-if="scope.row.creat_username == ''"> - </span>
                <span v-else> {{scope.row.creat_username}} </span>
              </div>
              <div v-if="items.field == 'remark'">
                <span v-if="scope.row.remark == ''"> - </span>
                <span v-else> {{scope.row.remark}} </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            label="操作"
            width="140"
            fixed="right"
          >
            <template slot-scope="scope">
              <el-button class="operation" type="text" size="small" @click="see(scope.row.id)">查看</el-button>
              <el-button v-if="list_type == 2" class="operation" type="text" size="small" @click="release(scope.row.id)">释放</el-button>
              <el-button v-if="list_type == 2" class="operation" type="text" size="small" @click="see(scope.row.id)">跟进</el-button>
              <el-button v-if="list_type == 1" class="operation" type="text" size="small" @click="funAuditBatch('single',scope.row.id)">领取</el-button>
              <el-button v-if="list_type == 1" class="operation" type="text" size="small" @click="wholeDel('single',scope.row.id)">删除</el-button>
            </template>
          </el-table-column>

        </el-table>
      </div>
      <div class="bortton-page">
        <el-col :span="12">
          <el-button size="small" type="primary" @click="choose">
            全选/反选
          </el-button>
          <el-button v-if="list_type == 1" size="small" type="primary" @click="funAuditBatch('all')">
            领取
          </el-button>
          <el-button v-if="list_type == 1 " size="small" type="primary" @click="wholeDel('all')">
            删除
          </el-button>
          <el-button size="small" type="primary" @click="funExport">
            导出
          </el-button>
        </el-col>
        <el-col :span="12" style="text-align: right">
          <el-pagination
            background
            destroy-on-close
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
    <el-drawer
      v-if="drawer"
      size="75%"
      :show-close="false"
      :with-header="false"
      :wrapper-closable="false"
      :visible.sync="drawer"
      :before-close="handleClose"
    >
      <show :row-id="rowId" />
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
  </div>
</template>

<script>
import show from '@/views/user/company/crm/components/Show'
import { clueRelease, clueReceive, clueDelete, crmCustomList, clueList, clueAdminLists, classify, crmCustomListEdit, clueExport } from '@/api/company_crm'
import { export_json_to_excel } from '@/excel/Export2Excel'

export default {
  name: 'Whole',
  components: {
    show
  },
  data(){
    return {
      key_type: '1',
      loading: true,
      props: { multiple: false, checkStrictly: true },
      visiblepop: false,
      menu_icon: 'menu',
      keyword: '',
      drawer: false,
      fieldData: [],
      createdByScreen: '',
      createdByScreenData: [],
      regionScreen: '',
      regionScreenData: [],
      industryScreen: '',
      industryScreenData: [],
      saleScreen: '',
      saleScreenData: [],
      pagesize: 10,
      total: 0,
      currentPage: 1,
      saleKey: [
        { text: 'aa', value: 'aa' },
        { text: 'bb', value: 'bb' },
        { text: 'cc', value: 'cc' },
        { text: 'dd', value: 'dd' }
      ],
      multipleSelection: [],
      exportData: [],
      tableData: [],
      title: '',
      currentNav: '',
      list_type: 0,
      rowId: 0,
      // 拖拽参数
      draggableOption: {
        sort: false,
        scroll: true,
        disabled: true
      },
      export_field: [],
      export_name: []
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
      var clue_id = localStorage.getItem('clue_id')
      if (clue_type != '' && clue_type == 'follow'){
        this.drawer = true
        this.rowId = clue_id
        localStorage.setItem('clue_type', '')
        localStorage.setItem('clue_id', '')
      }
    }
  },
  mounted(){
  },
  created () {
    this.currentNav = this.$route.name
    if (this.currentNav == 'wholeClue'){
      this.title = '全部线索'
      this.list_type = 0
    } else if (this.currentNav == 'wholeMy'){
      this.title = '我的线索'
      this.list_type = 2
    } else if (this.currentNav == 'wholeInternationalWaters'){
      this.title = '线索公海'
      this.list_type = 1
    }
    this.clueList()
    this.crmCustomList()
    this.clueAdminLists()
    this.classify()
    var clue_type = localStorage.getItem('clue_type')
    var clue_id = localStorage.getItem('clue_id')
    if (clue_type != '' && clue_type == 'follow'){
      this.drawer = true
      this.rowId = clue_id
      localStorage.setItem('clue_type', '')
      localStorage.setItem('clue_id', '')
    }
  },
  methods: {
    classify(){
      classify({ 'type': 'citycategory,trade,companyScale' }).then(res => {
        this.regionScreenData = res.data.citycategory
        this.industryScreenData = res.data.trade
      }).catch(() => {

      })
    },
    clueAdminLists(){
      clueAdminLists().then(res => {
        this.saleScreenData = res.data
        this.createdByScreenData = res.data
      }).catch(() => {

      })
    },
    clueList(){
      clueList({
        'page': this.currentPage,
        'pagesize': this.pagesize,
        'list_type': this.list_type,
        'key_type': this.key_type,
        'keyword': this.keyword,
        'trade': this.industryScreen,
        'district': this.regionScreen,
        'creat_id': this.createdByScreen,
        'admin_id': this.saleScreen
      }
      )
        .then(res => {
          this.tableData = res.data.items
          this.total = res.data.total
          this.currentPage = res.data.current_page
          this.loading = false
        }).catch(() => {

        })
    },
    setFieldClose(){
      this.menu_icon = 'menu'
      this.visiblepop = false
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
      crmCustomListEdit({ 'menu': list_type_z, 'type': 1, 'value': JSON.stringify(this.fieldData) }, 'post')
        .then(res => {
          if (res.code == 200){
            this.$message.success(res.message)
          } else {
            this.$message.error(res.message)
          }
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
      crmCustomList({ 'menu': list_type_z, 'type': 1 })
        .then(res => {
          this.fieldData = JSON.parse(res.data)
          for (var i = 0; i <= this.fieldData.length - 1; i++){
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
            this.fieldData[i].is_locking = true
            this.fieldData[i].is_lock_display = false
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
    confirm(){
      this.clueList()
    },
    see(value){
      this.drawer = true
      this.rowId = value
    },
    handleClose(){
      this.clueList()
      this.drawer = false
    },
    handleSizeChange(val){
      this.pagesize = val
      this.clueList()
    },
    handleCurrentChange(val){
      this.currentPage = val
      this.clueList()
    },
    release(id) {
      this.$confirm('您确认释放该线索？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        clueRelease({ clue_id: id }).then(response => {
          if (response.code == 200){
            this.$message.success(response.message)
          } else {
            this.$message.error(response.message)
          }
          this.clueList()
        })
      }).catch(() => {
      })
    },
    funAuditBatch(type, id){
      if (type == 'all'){
        var that = this
        if (that.multipleSelection.length == 0) {
          that.$message.error('请选择要领取的线索')
          return false
        }
        var ids = that.multipleSelection
      } else {
        var ids = id
      }
      this.$confirm('您确认要领取此线索？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        clueReceive({ clue_id: ids }).then(response => {
          if (response.code == 200){
            this.$message.success(response.message)
          } else {
            this.$message.error(response.message)
          }
          this.clueList()
        })
      }).catch(() => {
      })
    },
    wholeDel(type, id){
      if (type == 'all'){
        var that = this
        if (that.multipleSelection.length == 0) {
          that.$message.error('请选择要删除的线索')
          return false
        }
        var ids = that.multipleSelection
      } else {
        var ids = id
      }
      this.$confirm('您确认要删除此线索?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        clueDelete({ clue_id: ids }).then(response => {
          if (response.code == 200){
            this.$message.success(response.message)
          } else {
            this.$message.error(response.message)
          }
          this.clueList()
        })
      }).catch(() => {

      })
    },
    funExport() {
      var that = this
      if (that.multipleSelection.length == 0) {
        that.$message.error('请选择要导出的线索')
        return false
      }
      that.exportExcel(this.exportData)
    },
    exportExcel(list) {
      require.ensure([], () => {
        const { export_json_to_excel } = require('@/excel/Export2Excel')
        const tHeader = this.export_name// 上面设置Excel的表格第一行的标题
        const filterVal = this.export_field
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '线索导出'
        )
      })
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]))
    },
    funSearchKeyword(){
      this.clueList()
    },
    goto(target) {
      this.$router.push(target)
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
    choose(){
      this.tableData.forEach(row => {
        this.$refs.multipleTable.toggleRowSelection(row)
      })
    },
    handleSelectionChange(val) {
      var id_arr = []
      val.forEach(row => {
        id_arr.push(row.id)
      })
      this.exportData = val
      this.multipleSelection = id_arr
    },
    reset(field){
      if (field == 'all'){
        this.saleScreen = []
        this.industryScreen = []
        this.regionScreen = []
        this.createdByScreen = []
        this.key_type = '1'
        this.keyword = ''
      }
      if (field == 'admin_username'){
        this.saleScreen = []
      }
      if (field == 'trade'){
        this.industryScreen = []
      }
      if (field == 'district'){
        this.regionScreen = []
      }
      if (field == 'creat_username'){
        this.createdByScreen = []
      }
      this.clueList()
    }
  }
}
</script>

<style scoped lang="scss">
  ::v-deep .el-popover{
    position: fixed;
  }
  ::v-deep .el-checkbox-group{
    height: 250px;
    overflow-y: scroll;
  }
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
  .operation{
    font-size: 14px;
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
