<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ title }}</span>
      </div>
      <div style="float:right;z-index: 1;position: relative;top: 10px;right: 21px;font-size: 14px;">
        <span>共 {{ total }} 条记录</span>
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          style="width: 268px"
          placeholder="请输入被叫号码"
          class="input-with-select"
          @keyup.enter.native="funSearchKeyword"
        >
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
              <img v-if="menu_icon == 'menu'" src="../../assets/images/company/crm/menu.png" alt="">
              <img v-if="menu_icon == 'menu_select'" src="../../assets/images/company/crm/menu1.png" alt="">
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
          :height="tabelHeight"
          :data="tableData"
          tooltip-effect="dark"
          :header-cell-style="{background:'#f9f9f9', 'border-right': '1px solid #e4e6eb'}"
          border
          stripe
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
              <div :class="items.is_sortable == 'custom' ? 'sotrTime':''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover
                  placement="bottom"
                  trigger="hover"
                >
                  <!-- 通话时长-->
                  <div v-if="items.field == 'call_duration_name'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in conversation" class="screenStyle">
                        <el-radio :key="item.id" v-model="conversationScreen" :label="item.value">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 通话时长-->
                  <!-- 呼叫结果-->
                  <div v-if="items.field == 'call_duration'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in call" class="screenStyle">
                        <el-radio :key="item.id" v-model="callScreen" :label="item.value">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 呼叫结果-->
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div v-if="items.field == 'staff_name' || items.field == 'call_duration_name' || items.field == 'call_duration'" slot="reference" class="drop_down" />
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'call_duration'">
                <span v-if="scope.row.call_duration > 0">已接通</span>
                <span v-else> 未接通 </span>
              </div>
              <div v-if="items.field == 'contact_mobile'">
                {{ scope.row.contact_mobile }}
              </div>
              <div v-if="items.field == 'call_count'">
                {{ scope.row.call_count }}
                <span style="color: #409EFF;cursor: pointer" @click="details(scope.row.group_id,scope.row.staff_name)">(详情)</span>
              </div>
              <div v-if="items.field == 'start_date'">
                {{ scope.row.start_date | timeFilter }}
              </div>
              <div v-if="items.field == 'start_time'">
                {{ scope.row.start_time | timeFilter }}
              </div>
              <div v-if="items.field == 'end_time'">
                {{ scope.row.end_time | timeFilter }}
              </div>
              <div v-if="items.field == 'mobile_addr'">
                {{ scope.row.mobile_addr }}
              </div>
              <div v-if="items.field == 'call_duration_name'">
                {{ scope.row.call_duration_name }}
              </div>
              <div v-if="items.field == 'seat_number'">
                {{ scope.row.seat_number }}
              </div>
              <div v-if="items.field == 'staff_mobile'">
                {{ scope.row.staff_mobile }}
              </div>
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
              <div :class="items.is_sortable == 'custom' ? 'sotrTime':''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover
                  placement="bottom"
                  trigger="hover"
                >
                  <!-- 通话时长-->
                  <div v-if="items.field == 'call_duration_name'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in conversation" class="screenStyle">
                        <el-radio :key="item.id" v-model="conversationScreen" :label="item.value">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 通话时长-->
                  <!-- 呼叫结果-->
                  <div v-if="items.field == 'call_duration'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in call" class="screenStyle">
                        <el-radio :key="item.id" v-model="callScreen" :label="item.value">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 呼叫结果-->
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div v-if="items.field == 'staff_name' || items.field == 'call_duration_name' || items.field == 'call_duration'" slot="reference" class="drop_down" />
                  <!-- 创建人-->
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
              </div>

            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'call_duration'">
                <span v-if="scope.row.call_duration > 0">已接通</span>
                <span v-else> 未接通 </span>
              </div>
              <div v-if="items.field == 'contact_mobile'">
                {{ scope.row.contact_mobile }}
              </div>
              <div v-if="items.field == 'call_count'">
                {{ scope.row.call_count }}
                <span style="color: #409EFF;cursor: pointer" @click="details(scope.row.group_id,scope.row.staff_name)">(详情)</span>
              </div>
              <div v-if="items.field == 'start_date'">
                {{ scope.row.start_date | timeFilter }}
              </div>
              <div v-if="items.field == 'start_time'">
                {{ scope.row.start_time | timeFilter }}
              </div>
              <div v-if="items.field == 'end_time'">
                {{ scope.row.end_time | timeFilter }}
              </div>
              <div v-if="items.field == 'mobile_addr'">
                {{ scope.row.mobile_addr }}
              </div>
              <div v-if="items.field == 'call_duration_name'">
                {{ scope.row.call_duration_name }}
              </div>
              <div v-if="items.field == 'seat_number'">
                {{ scope.row.seat_number }}
              </div>
              <div v-if="items.field == 'staff_mobile'">
                {{ scope.row.staff_mobile }}
              </div>
            </template>
          </el-table-column>
          <el-table-column
            label="操作"
            width="120"
            fixed="right"
          >
            <template slot-scope="scope">
              <el-button class="operation" type="text" size="small" @click="details(scope.row.group_id,scope.row.staff_name)">查看</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="bortton-page">
        <el-col :span="24" style="text-align: right">
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
      title="外呼记录"
      :wrapper-closable="false"
      :visible.sync="drawer"
      :before-close="handleClose"
    >
      <outboundDetails :group-id="group_id" :staff-name="staff_name" />
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
  </div>
</template>

<script>
import { parseTime } from '@/utils'
import outboundDetails from '@/views/outbound/outboundDetails'
import { customLndex, customLndexAdd, outboundRecordList, outboundStaffList } from '@/api/outbound'
import { crmCustomListEdit } from '@/api/company_crm'
export default {
  name: 'Outbound',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    outboundDetails
  },
  data(){
    return {
      title: '外呼记录',
      loading: false,
      tableData: [],
      fieldData: [
        { 'name': '被叫号码', 'field': 'contact_mobile', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 170, 'is_sortable': false },
        { 'name': '呼叫记录', 'field': 'call_count', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 170, 'is_sortable': 'custom' },
        { 'name': '归属地', 'field': 'mobile_addr', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 200, 'is_sortable': false },
        { 'name': '通话日期', 'field': 'start_date', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': 'custom' },
        { 'name': '接通时间', 'field': 'start_time', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false },
        { 'name': '结束时间', 'field': 'end_time', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false },
        { 'name': '呼叫结果', 'field': 'call_duration', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false },
        { 'name': '通话时长', 'field': 'call_duration_name', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 170, 'is_sortable': false },
        { 'name': '坐席编号', 'field': 'seat_number', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false }
      ],
      menu_icon: 'menu',
      keyword: '',
      currentPage: 1,
      pagesize: 10,
      total: 0,
      drawer: false,
      currentNav: '',
      conversation: [
        {
          'name': '30秒以内',
          'value': 1
        },
        {
          'name': '30秒~1分钟',
          'value': 2
        },
        {
          'name': '1分钟~3分钟',
          'value': 3
        },
        {
          'name': '3分钟以上',
          'value': 4
        }
      ],
      conversationScreen: '',
      call: [
        {
          'name': '已接通',
          'value': 1
        },
        {
          'name': '未接通',
          'value': 2
        }
      ],
      callScreen: '',
      seats: [],
      seatsScreen: '',
      group_id: 0,
      staff_name: '',
      visiblepop: false,
      order_key: '',
      order_by: '',
      tabelHeight: 0
    }
  },
  created() {
    this.currentNav = this.$route.name
    if (this.currentNav == 'myOutbound'){
      this.title = '我的外呼'
      this.key_type = 'contact_mobile'
      this.myOutbound()
    } else {
      this.title = '外呼记录'
      this.key_type = 'staff_mobile'
      this.allOutbound()
    }
    // this.getOutboundStaffList()
    this.getFieldData()
    if (this.currentNav == 'myOutbound'){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == 'seat_number'){
          this.fieldData.splice([i], 1)
        }
      }
    }
  },
  updated(){
    this.$nextTick(() => {
      this.$refs.multipleTable.doLayout()
      if (!this.loading){
        setTimeout(() => {
          var classStr = this.$refs.multipleTable.$el.className
          var classAry = classStr.split(' ')
          var a = document.querySelectorAll('.el-table__fixed-right')[0]
          var b = document.querySelectorAll('.el-table__empty-block')[0]
          if (b != undefined){
            b.style.width = 100 + '%'
          }
          var isClass = classAry.find(function(value, index, arr){
            return value == 'el-table--scrollable-y'
          })
          if (isClass){
            a.style.right = 10 + 'px'
          } else {
            a.style.right = 0 + 'px'
          }
        }, 100)
      }
    })
  },
  mounted(){
    this.$nextTick(() => {
      var docHeight = document.documentElement.clientHeight
      this.tabelHeight = docHeight - 316 - 60
    })
  },
  methods: {
    sortTable({ column, order }){
      if (order == 'ascending'){
        this.order_by = 'asc'
      } else if (order == 'descending'){
        this.order_by = 'desc'
      } else {
        this.order_by = ''
      }
      if (column.property == 'call_count'){
        this.order_key = 1
      } else if (column.property == 'start_date'){
        this.order_key = 2
      } else {
        this.order_key = ''
      }
      if (this.currentNav == 'myOutbound'){
        this.title = '我的外呼'
        this.myOutbound()
      } else {
        this.title = '外呼记录'
        this.allOutbound()
      }
    },
    getFieldData(){
      customLndex({
        'name': this.currentNav
      }
      ).then(res => {
        if (res.data != ''){
          this.fieldData = JSON.parse(res.data)
        }
      }).catch(() => {

      })
    },
    myOutbound(){
      this.loading = true
      outboundRecordList({
        'list_type': 'mine',
        'page': this.currentPage,
        'per_page': this.pagesize,
        'contact_mobile': this.keyword,
        'is_connect': this.callScreen,
        'call_duration_type': this.conversationScreen,
        'staff_mobile': this.seatsScreen,
        'order_key': this.order_key,
        'order_by': this.order_by
      }
      ).then(res => {
        this.currentPage = res.data.current_page
        this.total = res.data.total
        this.tableData = res.data.data
        this.loading = false
      }).catch(() => {

      })
    },
    allOutbound(){
      this.loading = true
      outboundRecordList({
        'list_type': 'all',
        'page': this.currentPage,
        'per_page': this.pagesize,
        'contact_mobile': this.keyword,
        'is_connect': this.callScreen,
        'call_duration_type': this.conversationScreen,
        'staff_mobile': this.seatsScreen,
        'order_key': this.order_key,
        'order_by': this.order_by
      }
      ).then(res => {
        this.currentPage = res.data.current_page
        this.total = res.data.total
        this.tableData = res.data.data
        this.loading = false
      }).catch(() => {

      })
    },
    // getOutboundStaffList(){
    //   outboundStaffList({
    //     'is_bind': 1
    //   }
    //   ).then(res => {
    //     this.seats = res.data
    //   }).catch(() => {
    //
    //   })
    // },
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
    reset(field){
      if (field == 'call_duration'){
        this.callScreen = ''
      }
      if (field == 'call_duration_name'){
        this.conversationScreen = ''
      }
      if (this.currentNav == 'myOutbound'){
        this.title = '我的外呼'
        this.myOutbound()
      } else {
        this.title = '外呼记录'
        this.allOutbound()
      }
    },
    confirm(){
      if (this.currentNav == 'myOutbound'){
        this.title = '我的外呼'
        this.myOutbound()
      } else {
        this.title = '外呼记录'
        this.allOutbound()
      }
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
    setFieldClose(){
      this.menu_icon = 'menu'
      this.visiblepop = false
    },
    setFieldConfirm(){
      customLndexAdd({
        'name': this.currentNav,
        'value': JSON.stringify(this.fieldData)
      }
      ).then(res => {
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
    funSearchKeyword(){
      this.currentPage = 1
      if (this.currentNav == 'myOutbound'){
        this.title = '我的外呼'
        this.myOutbound()
      } else {
        this.title = '外呼记录'
        this.allOutbound()
      }
    },
    handleSizeChange(size){
      this.pagesize = size
      if (this.currentNav == 'myOutbound'){
        this.title = '我的外呼'
        this.myOutbound()
      } else {
        this.title = '外呼记录'
        this.allOutbound()
      }
    },
    handleCurrentChange(page){
      this.currentPage = page
      if (this.currentNav == 'myOutbound'){
        this.title = '我的外呼'
        this.myOutbound()
      } else {
        this.title = '外呼记录'
        this.allOutbound()
      }
    },
    details(group_id, staff_name){
      this.staff_name = staff_name
      this.group_id = group_id
      this.drawer = true
    },
    handleClose(){
      this.drawer = false
    }
  }
}
</script>

<style scoped lang="scss">
.sotrTime{
  width: 93%;
}
::v-deep .caret-wrapper{
  position: absolute;
  right: 0;
  top:50%;
  transform: translateY(-50%);
}
.screen_s{
  max-height: 220px;
  overflow: auto
}
::v-deep .el-popover{
  position: fixed;
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
  background: url("../../assets/images/company/crm/unlock.png") no-repeat center center;
  &:hover{
    background: url("../../assets/images/company/crm/unlock1.png") no-repeat center center;
  }
}
.unlock{
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../assets/images/company/crm/unlock.png") no-repeat center center;
  &:hover{
    background: url("../../assets/images/company/crm/unlock1.png") no-repeat center center;
  }
}
.lock_display{
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../assets/images/company/crm/lock.png") no-repeat center center;
  &:hover{
    background: url("../../assets/images/company/crm/lock1.png") no-repeat center center;
  }
}
.lock{
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../assets/images/company/crm/lock.png") no-repeat center center;
  &:hover{
    background: url("../../assets/images/company/crm/lock1.png") no-repeat center center;
  }
}
.drop_down{
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../assets/images/company/crm/drop-down.png") no-repeat center center;
  &:hover{
    background: url("../../assets/images/company/crm/drop-down1.png") no-repeat center center;
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

