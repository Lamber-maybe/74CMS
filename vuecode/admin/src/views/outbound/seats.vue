<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>坐席信息</span>
      </div>
      <div style="float:right;z-index: 1;position: relative;top: 14px;color: #999;">
        <span class="used">如需开通坐席或者充值呼叫包，请 <span class="b" @click="renew">联系客服</span></span>
        <!--        <el-button size="small" type="primary" @click="binding">-->
        <!--          绑定坐席-->
        <!--        </el-button>-->
        <!--        <el-button size="small" type="primary" @click="renew">-->
        <!--          开通呼叫包-->
        <!--        </el-button>-->
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          style="width: 268px"
          placeholder="搜索手机号"
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
          :data="tableData"
          :height="tabelHeight"
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
                  <!-- 坐席状态-->
                  <div v-if="items.field == 'seat_status'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in status" class="screenStyle">
                        <el-radio :key="item.id" v-model="statusScreen" :label="item.value">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 坐席状态-->
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div v-if="items.field == 'seat_status'" slot="reference" class="drop_down" />
                  <!-- 创建人-->
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'seat_number'">
                {{ scope.row.seat_number }}
              </div>
              <div v-if="items.field == 'staff_name'">
                <span v-if="scope.row.staff_name != '' ">{{ scope.row.staff_name }}</span>
                <span v-else>未绑定</span>
              </div>
              <div v-if="items.field == 'seat_status'">
                <span v-if="scope.row.seat_status != ''">  {{ scope.row.seat_status }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'start_time'">
                <span v-if="scope.row.start_time != ''">  {{ scope.row.start_time | timeFilter }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'setmeal_duration'">
                <span v-if="scope.row.setmeal_duration != ''">{{ scope.row.setmeal_duration }}</span>
                <span v-else>0</span>
              </div>
              <div v-if="items.field == 'setmeal_surplus_duration'">
                <span v-if="scope.row.setmeal_surplus_duration != ''">{{ scope.row.setmeal_surplus_duration }}</span>
                <span v-else>0</span>
              </div>
              <div v-if="items.field == 'setmeal_endtime'">
                <span v-if="scope.row.setmeal_endtime !=''"> {{ scope.row.setmeal_endtime | timeFilter }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'seat_addtime'">
                <span v-if="scope.row.seat_addtime != ''">  {{ scope.row.seat_addtime | timeFilter }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'seat_mobile'">
                <span v-if="scope.row.seat_mobile != ''">{{ scope.row.seat_mobile }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'seat_endtime'">
                <span v-if="scope.row.seat_endtime != ''">  {{ scope.row.seat_endtime | timeFilter }}</span>
                <span v-else>-</span>
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
                  <!-- 坐席状态-->
                  <div v-if="items.field == 'seat_status'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in status" class="screenStyle">
                        <el-radio :key="item.id" v-model="statusScreen" :label="item.value">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 坐席状态-->
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div v-if="items.field == 'seat_status'" slot="reference" class="drop_down" />
                  <!-- 创建人-->
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
              </div>

            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'seat_number'">
                {{ scope.row.seat_number }}
              </div>
              <div v-if="items.field == 'staff_name'">
                <span v-if="scope.row.staff_name != '' ">{{ scope.row.staff_name }}</span>
                <span v-else>未绑定</span>
              </div>
              <div v-if="items.field == 'seat_status'">
                <span v-if="scope.row.seat_status != ''">  {{ scope.row.seat_status }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'start_time'">
                <span v-if="scope.row.start_time != ''">  {{ scope.row.start_time | timeFilter }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'setmeal_duration'">
                <span v-if="scope.row.setmeal_duration != ''">{{ scope.row.setmeal_duration }}</span>
                <span v-else>0</span>
              </div>
              <div v-if="items.field == 'setmeal_surplus_duration'">
                <span v-if="scope.row.setmeal_surplus_duration != ''">{{ scope.row.setmeal_surplus_duration }}</span>
                <span v-else>0</span>
              </div>
              <div v-if="items.field == 'setmeal_endtime'">
                <span v-if="scope.row.setmeal_endtime !=''"> {{ scope.row.setmeal_endtime | timeFilter }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'seat_addtime'">
                <span v-if="scope.row.seat_addtime != ''">  {{ scope.row.seat_addtime | timeFilter }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'seat_mobile'">
                <span v-if="scope.row.seat_mobile != ''">{{ scope.row.seat_mobile }}</span>
                <span v-else>-</span>
              </div>
              <div v-if="items.field == 'seat_endtime'">
                <span v-if="scope.row.seat_endtime != ''">  {{ scope.row.seat_endtime | timeFilter }}</span>
                <span v-else>-</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            label="操作"
            fixed="right"
            width="160"
          >
            <template slot-scope="scope">
              <span v-if=" scope.row.seat_status == '未开通' || scope.row.seat_mobile == '' ">
                <el-button class="operation" type="text" size="small" disabled @click="binding(scope.row.seat_id)">绑定坐席</el-button>
              </span>
              <span v-else>
                <el-button v-if="scope.row.staff_name != '' " class="operation" type="text" size="small" @click="unbinding(scope.row.seat_id)">解绑坐席</el-button>
                <el-button v-if="scope.row.staff_name == '' " class="operation" type="text" size="small" @click="binding(scope.row.seat_id,scope.row.seat_number)">绑定坐席</el-button>
              </span>
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
    <div class="customer">
      <el-dialog
        :visible.sync="dialogRenew"
        width="16.5%"
        :before-close="handleCloseRenew"
      >
        <div class="customer-box">
          <div class="title">开通坐席或充值呼叫包，请联系客服</div>
          <div class="code">
            <img src="../../assets/images/outbound/renew.png" alt="">
          </div>
          <div class="sweep">
            — 扫一扫添加客服 —
          </div>
          <div class="telephone">
            <div class="img">
              <img src="../../assets/images/outbound/30001.png" alt="">
            </div>
            <div class="t">182 3404 5230</div>
            <div style="clear:both" />
          </div>
        </div>
      </el-dialog>
    </div>
    <el-dialog
      v-if="dialogVisible"
      title=""
      :visible.sync="dialogVisible"
      width="26%"
      :before-close="handleClose"
    >
      <div class="binding">
        <div class="binding-header">绑定座席</div>
        <!--        <div class="binding-title">您当前还能开通 {{ not_unbind_total }} 个座席，如需更多请联系客服。</div>-->
      </div>
      <div class="table">
        <el-form ref="form" :rules="rules" :model="form" label-width="120px">
          <el-form-item label="坐席编号" prop="seat_number">
            <el-select v-model="form.seat_id" placeholder="请选择坐席编号" disabled>
              <el-option :label="form.seat_number" :value="form.seat_id" />
            </el-select>
          </el-form-item>
          <el-form-item label="坐席员工" prop="admin_id">
            <el-select v-model="form.admin_id" placeholder="请选择坐席员工">
              <el-option v-for="items in seatsName" :label="items.username" :value="items.id" />
            </el-select>
          </el-form-item>
          <!--          <div class="titles">以上项目保存后暂不支持自主修改操作，请认真核对</div>-->
          <el-form-item>
            <el-button type="primary" @click="onSubmit('form')">绑定坐席</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { parseTime } from '@/utils'
import { UnbindList, customLndex, customLndexAdd, outboundSeat, outboundStaffList, bindSeat, unbindSeat } from '@/api/outbound'

export default {
  name: 'Seats',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data(){
    return {
      rules: {
        seat_number: [
          { required: true, message: '请选择坐席编号', trigger: 'change' }
        ],
        admin_id: [
          { required: true, message: '请选择坐席员工', trigger: 'change' }
        ]
      },
      loading: false,
      tableData: [],
      fieldData: [
        { 'name': '坐席编号', 'field': 'seat_number', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 130, 'is_sortable': false },
        { 'name': '坐席员工', 'field': 'staff_name', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 120, 'is_sortable': false },
        { 'name': '绑定手机号', 'field': 'seat_mobile', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 130, 'is_sortable': false },
        { 'name': '坐席状态', 'field': 'seat_status', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 130, 'is_sortable': false },
        { 'name': '坐席有效期至', 'field': 'seat_endtime', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': 'custom' },
        { 'name': '云呼叫包套餐时长', 'field': 'setmeal_duration', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false },
        { 'name': '云呼叫包剩余时长', 'field': 'setmeal_surplus_duration', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false },
        { 'name': '呼叫包有效期至', 'field': 'setmeal_endtime', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false },
        { 'name': '创建时间', 'field': 'seat_addtime', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 180, 'is_sortable': false }
      ],
      menu_icon: 'menu',
      keyword: '',
      currentPage: 1,
      pagesize: 10,
      total: 0,
      currentNav: '',
      dialogVisible: false,
      form: {
        'seat_number': '',
        'admin_id': '',
        'seat_id': 0
      },
      dialogRenew: false,
      not_unbind_total: 0,
      statusScreen: '',
      status: [
        {
          'value': 1,
          'name': '已开通'
        },
        {
          'value': 2,
          'name': '未开通'
        }
      ],
      visiblepop: false,
      bind: [
        {
          'value': 1,
          'name': '已绑定'
        },
        {
          'value': 2,
          'name': '未绑定'
        }
      ],
      is_bind: '',
      order_by: '',
      seatsName: [],
      seatNumber: [],
      tabelHeight: 0
    }
  },
  created() {
    this.getData()
    this.getFieldData()
    this.getOutboundStaffList()
    this.getUnbindList()
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
    unbinding(id){
      this.$confirm('是否确认解绑?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        unbindSeat({ 'seat_id': id }).then(res => {
          this.$message.success(res.message)
          this.getOutboundStaffList()
          this.getData()
        }).catch(() => {
        })
      }).catch(() => {
      })
    },
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          bindSeat(this.form).then(res => {
            this.$message.success(res.message)
            this.dialogVisible = false
            this.getData()
            this.getOutboundStaffList()
            this.form.seat_number = ''
            this.form.admin_id = ''
            this.form.seat_id = 0
          }).catch(() => {
          })
        } else {
          return false
        }
      })
    },
    getUnbindList(){
      UnbindList().then(res => {
        this.seatNumber = res.data
      }).catch(() => {

      })
    },
    getOutboundStaffList(){
      outboundStaffList({
        'is_bind': 2
      }
      ).then(res => {
        this.seatsName = res.data
      }).catch(() => {

      })
    },
    sortTable({ column, order }){
      if (order == 'ascending'){
        this.order_by = 'asc'
      } else if (order == 'descending'){
        this.order_by = 'desc'
      } else {
        this.order_by = ''
      }
      if (column.property == 'seat_endtime'){
        this.order_key = 1
      } else {
        this.order_key = ''
      }
      this.getData()
    },
    getFieldData(){
      customLndex({
        'name': 'seats'
      }
      ).then(res => {
        if (res.data != ''){
          this.fieldData = JSON.parse(res.data)
        }
      }).catch(() => {

      })
    },
    getData(){
      this.loading = true
      outboundSeat({
        'seat_mobile': this.keyword,
        'page': this.currentPage,
        'per_page': this.pagesize,
        'seat_status': this.statusScreen,
        'order_by': this.order_by,
        'order_key': this.order_key
      }
      ).then(res => {
        this.currentPage = res.data.current_page
        this.total = res.data.total
        this.tableData = res.data.data
        this.not_unbind_total = res.data.not_unbind_total
        this.loading = false
      }).catch(() => {

      })
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
    reset(field){
      if (field == 'seat_status'){
        this.statusScreen = ''
      }
      this.getData()
    },
    confirm(){
      this.getData()
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
        'name': 'seats',
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
      this.getData()
    },
    handleSizeChange(size){
      this.pagesize = size
      this.getData()
    },
    handleCurrentChange(page){
      this.currentPage = page
      this.getData()
    },
    binding(id, seat_number){
      this.form.seat_id = id
      this.form.seat_number = seat_number
      this.dialogVisible = true
    },
    handleClose(){
      this.dialogVisible = false
    },
    renew(){
      this.dialogRenew = true
    },
    handleCloseRenew(){
      this.dialogRenew = false
    }
  }

}
</script>

<style scoped lang="scss">
.customer-box{
  width: 100%;
  height: 392px;
  background: url('../../assets/images/outbound/customer.png') no-repeat;
  background-size: 100%;
  text-align: center;
  padding-top: 20px;
  .title{
    font-size: 15px;
    font-family: Microsoft YaHei;
    font-weight: 400;
    color: #FFFFFF;
    line-height: 44px;
  }
  .code{
    width: 143px;
    height: 143px;
    padding:15px 15px;
    background: #FFFFFF;
    border-radius: 8px;
    margin:0 auto;
    margin-top: 86px;
    box-shadow: 0px 0px 13px 0px rgba(220,220,220,0.34);
    img{
      width:100%;
      height:100%;
    }
  }
  .sweep{
    margin-top: 15px;
    font-size: 12px;
    font-family: Microsoft YaHei;
    font-weight: 400;
    color: #999999;
  }
  .telephone{
    width: 150px;
    margin: 0 auto;
    margin-top: 20px;
    .img{
      width: 16px;
      height: 16px;
      display: inline-block;
      float:left;
      margin-right: 6px;
      img{
        width: 100%;
        height: 100%;
        margin-top: 2px;
      }
    }
    .t{
      display: inline-block;
      float:left;
      color:#333333;
      font-size: 17px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
    }
  }
}
.customer{
  ::v-deep .el-dialog__header{
    padding: 0px 0px 0px 0px;
  }
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
  ::v-deep .el-dialog__headerbtn{
    top: 6px;
    right: 12px;
    color:#FFFFFF;
  }
}
::v-deep .el-dialog__body{
  padding: 0px 25px 25px;
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
.screen_s{
  max-height: 220px;
  overflow: auto
}
.table{
  .titles{
    font-size: 13px;
    color: red;
    margin-bottom: 20px;
    margin-left: 120px;
  }
  ::v-deep .el-select{
    width: 80%;
  }
  ::v-deep .phone .el-input{
    width: 80%;
  }
  margin-top: 36px;
}
.binding{
    .binding-header{
       font-size: 18px;
       font-weight: bold;
       display: inline-block;
       margin-top: 14px;
    }
    .binding-title{
      font-size: 13px;
      display: inline-block;
      float: right;
      color:#b1aeae;
      margin-top: 20px;
    }
}
.used{
  font-size: 14px;
  .b{
    color: #409EFF;
    cursor: pointer;
  }
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

