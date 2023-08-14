<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>企业注销申请</span>
      </div>
      <div class="tip">
        <p>注销账号后删除会员所有数据，不可恢复。如需留存会员数据，请点击备份，备份会员数据。</p>
      </div>
      <div style="float:right;z-index: 1;position: relative;margin-top: 13px;">
        <span class="switch" @click="goto('/user/company_cancel_backup')">
          <img style="width: 10px;height:10px;margin-left: 14px;" src="../../../../assets/images/switch.png" alt="">
          查看我的备份
        </span>
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          style="width: 440px; float: left"
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
          </el-select>
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="funSearchKeyword"
          />
        </el-input>
      </div>
      <div
        v-if="
          processingStatus != ''
        "
        class="filterCriteria"
      >
        <div style="float:left;display: inline-block;margin-top: 6px;">已选条件：</div>
        <div style="float:left;display: inline-block;width: 80%">
          <div v-if="processingStatus != ''" class="selected">
            <div class="name">处理状态：{{ processingStatus.name }}</div>
            <div class="closes" @click="reset(processingStatus.field)"><i class="el-icon-close" /></div>
          </div>
        </div>
        <div style="float:right;display: inline-block;margin-top: 6px;color:#409eff;font-size: 13px;" @click="reset('all')">
          <i class="el-icon-delete" />
          清空条件
        </div>
        <div style="clear:both;" />
      </div>
      <div style="position: relative;">
        <div
          :class="
            processingStatus != '' ||
              citycategoryFilter != ''
              ? 'setField_s'
              : 'setField'
          "
        >
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
          v-loading="listLoading"
          :data="list"
          border
          stripe
          element-loading-text="Loading"
          fit
          highlight-current-row
          @sort-change="sortTable"
          @selection-change="handleSelectionChange"
        >
          <el-table-column fixed type="selection" width="42" />
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == true && items.select == true"
            fixed
            :width="items.width"
            :prop="items.field"
            :sortable="items.is_sortable"
          >
            <template slot="header" slot-scope="scope">
              <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover placement="bottom" trigger="hover">
                  <!-- 处理状态-->
                  <div v-if="items.field == 'status'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in status" class="screenStyle">
                        <el-radio :key="item.id" v-model="auditScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div
                    v-if="
                      items.field == 'status'
                    "
                    slot="reference"
                    class="drop_down"
                  />
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''">-</span>
                <span v-else>{{ scope.row.id }}</span>
              </div>
              <div v-if="items.field == 'companyname'">
                <span v-if="scope.row.companyname == ''">-</span>
                <span v-else>{{ scope.row.companyname }}</span>
              </div>
              <div v-if="items.field == 'regtime'">
                <span v-if="scope.row.regtime == ''">-</span>
                <span v-else>{{ scope.row.regtime }}</span>
              </div>
              <div v-if="items.field == 'contact'">
                <span v-if="scope.row.contact == ''">-</span>
                <span v-else>{{ scope.row.contact }}</span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="scope.row.mobile == ''">-</span>
                <span v-else>{{ scope.row.mobile }}</span>
              </div>
              <div v-if="items.field == 'status'">
                <span v-if="scope.row.status == 0">
                  <el-tag v-if="scope.row.status==0" type="danger">待处理</el-tag>
                </span>
                <span v-else>
                  <el-tag v-if="scope.row.status==1" type="success">已处理</el-tag>
                </span>
              </div>
              <div v-if="items.field == 'addtime'">
                <span v-if="scope.row.addtime == ''">-</span>
                <span v-else>
                  <i class="el-icon-time" />
                  <span>{{ scope.row.addtime | timeFilter }}</span>
                </span>
              </div>
              <div v-if="items.field == 'handlertime'">
                <span v-if="scope.row.handlertime == ''">-</span>
                <span v-else><i class="el-icon-time" />
                  <span>{{ scope.row.handlertime | timeFilter }}</span></span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == false && items.select == true"
            :width="items.width"
            :prop="items.field"
            :sortable="items.is_sortable"
          >
            <template slot="header" slot-scope="scope">
              <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover placement="bottom" trigger="hover">
                  <!-- 处理状态-->
                  <div v-if="items.field == 'status'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in status" class="screenStyle">
                        <el-radio :key="item.id" v-model="auditScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div
                    v-if="
                      items.field == 'status'
                    "
                    slot="reference"
                    class="drop_down"
                  />
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''">-</span>
                <span v-else>{{ scope.row.id }}</span>
              </div>
              <div v-if="items.field == 'companyname'">
                <span v-if="scope.row.companyname == ''">-</span>
                <span v-else>{{ scope.row.companyname }}</span>
              </div>
              <div v-if="items.field == 'regtime'">
                <span v-if="scope.row.regtime == ''">-</span>
                <span v-else>{{ scope.row.regtime }}</span>
              </div>
              <div v-if="items.field == 'contact'">
                <span v-if="scope.row.contact == ''">-</span>
                <span v-else>{{ scope.row.contact }}</span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="scope.row.mobile == ''">-</span>
                <span v-else>{{ scope.row.mobile }}</span>
              </div>
              <div v-if="items.field == 'status'">
                <span v-if="scope.row.status == 0">
                  <el-tag v-if="scope.row.status==0" type="danger">待处理</el-tag>
                </span>
                <span v-else>
                  <el-tag v-if="scope.row.status==1" type="success">已处理</el-tag>
                </span>
              </div>
              <div v-if="items.field == 'addtime'">
                <span v-if="scope.row.addtime == ''">-</span>
                <span v-else>
                  <i class="el-icon-time" />
                  <span>{{ scope.row.addtime | timeFilter }}</span>
                </span>
              </div>
              <div v-if="items.field == 'handlertime'">
                <span v-if="scope.row.handlertime == ''">-</span>
                <span v-else><i class="el-icon-time" />
                  <span>{{ scope.row.handlertime | timeFilter }}</span></span>
              </div>
            </template>
          </el-table-column>
          <el-table-column fixed="right" label="操作" min-width="230">
            <template slot-scope="scope">
              <el-button v-if="scope.row.is_backups==1 || scope.row.status==1" size="small" type="primary" disabled @click="backups(scope.row)">
                备份
              </el-button>
              <el-button v-else size="small" type="primary" @click="backups(scope.row)">
                备份
              </el-button>
              <el-button v-if="scope.row.status==1" size="small" type="primary" disabled @click="funHandle(scope.row)">
                处理
              </el-button>
              <el-button v-else size="small" type="primary" @click="funHandle(scope.row)">
                处理
              </el-button>
              <el-button size="small" type="danger" @click="funDel(scope.row)">
                删除
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="10">
          <el-button size="small" type="primary" @click="choose">
            全选/反选
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch()">
            删除
          </el-button>
        </el-col>

        <el-col :span="8" />
        <el-col :offset="8" :span="16" style="text-align: right;">
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
  </div>
</template>

<script>
import {
  memberCancelApplyList,
  memberCancelApplyDelete,
  memberCancelApplyHandle,
  memberCancelApplyBackups,
  memberCancelApplyDeleteAll
} from '@/api/member_cancel_apply'
import { parseTime } from '@/utils/index'
import { customLndexAdd, customLndex } from '@/api/outbound'

export default {
  name: 'CommpanyCancelApply',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      status: [
        { id: 1, name: '待处理' },
        { id: 2, name: '已处理' }
      ],
      auditScreen: '',
      fieldData: [
        { name: 'ID', field: 'id', select: true, is_locking: false, is_lock_display: false, width: 100, is_sortable: false },
        { name: '企业名称', field: 'companyname', select: true, is_locking: false, is_lock_display: false, width: 205, is_sortable: false },
        { name: '注册时间', field: 'regtime', select: true, is_locking: false, is_lock_display: false, width: 190, is_sortable: false },
        { name: '联系人', field: 'contact', select: true, is_locking: false, is_lock_display: false, width: 190, is_sortable: false },
        { name: '手机号', field: 'mobile', select: true, is_locking: false, is_lock_display: false, width: 190, is_sortable: false },
        { name: '处理状态', field: 'status', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '申请时间', field: 'addtime', select: true, is_locking: false, is_lock_display: false, width: 200, is_sortable: 'custom' },
        { name: '处理时间', field: 'handlertime', select: true, is_locking: false, is_lock_display: false, width: 200, is_sortable: 'custom' }
      ],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      tableData: [],
      tableIdarr: [],
      sortType: '',
      sort: '',
      processingStatus: '',
      loading: false,
      visiblepop: false,
      menu_icon: 'menu',
      citycategoryFilter: ''
    }
  },
  created() {
    this.fetchData()
    this.getField()
  },
  methods: {
    setFieldClose() {
      this.menu_icon = 'menu'
      this.visiblepop = false
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
    getField(){
      customLndex({
        name: 'commpanyCancelApply'
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
        name: 'commpanyCancelApply',
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
    reset(field) {
      if (field == 'all') {
        this.auditScreen = ''
      }
      if (field == 'status') {
        this.auditScreen = ''
      }
      this.confirm()
    },
    confirm() {
      this.currentPage = 1
      if (this.auditScreen != '') {
        for (var i = 0; i <= this.status.length - 1; i++) {
          if (this.auditScreen == this.status[i].id) {
            this.processingStatus = {
              field: 'status',
              name: this.status[i].name
            }
          }
        }
      } else {
        this.processingStatus = ''
      }
      this.memberCancelApplyList()
    },
    memberCancelApplyList() {
      this.loading = true
      memberCancelApplyList({
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize,
        utype: 1,
        sortType: this.sort_type,
        sort: this.sort,
        status: this.auditScreen
      })
        .then(response => {
          this.tableData = response.data.items
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => {
          this.loading = false
        })
    },
    sortTable({ column, order }) {
      if (order == 'ascending') {
        this.sortType = 'asc'
      } else if (order == 'descending') {
        this.sortType = 'desc'
      } else {
        this.sortType = ''
      }
      this.sort_type = column.property
      this.sort = this.sortType
      this.memberCancelApplyList()
    },
    goto(target) {
      this.$router.push(target)
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
        }
      }
    },
    funDeleteBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要删除的数据')
        return false
      }
      var that = this

      that.$confirm('确定删除吗?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          memberCancelApplyDeleteAll(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => { })
    },
    choose(){
      this.tableData.forEach(row => {
        this.$refs.multipleTable.toggleRowSelection(row)
      })
    },
    fetchData() {
      this.listLoading = true
      if (localStorage.getItem('apply_status') && localStorage.getItem('apply_status') == '1') {
        this.auditScreen = 1
        this.processingStatus = { name: '待处理', field: 'status' }
        localStorage.setItem('apply_status', '')
      }
      const param = {
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize,
        utype: 1,
        sortType: this.sortType,
        sort: this.sort,
        status: this.auditScreen
      }
      memberCancelApplyList(param).then(response => {
        this.tableData = response.data.items
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    funSearch() {
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    funDel(row){
      var that = this
      that
        .$confirm('确定删除吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          memberCancelApplyDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funHandle(row){
      var that = this
      if (row.is_backups === 1) {
        this.cancellation(row)
      } else {
        that
          .$confirm('您在注销之前，可备份保留会员信息，是否备份' + row.companyname + '的会员信息？', '提示', {
            cancelButtonText: '进行处理',
            confirmButtonText: '备份',
            type: 'warning'
          })
          .then(() => {
            const param = {
              id: row.id
            }
            memberCancelApplyBackups(param).then(response => {
              that.$message.success(response.message)
              this.cancellation(row)
              that.fetchData()
              return true
            })
          })
          .catch(() => {
            this.cancellation(row)
          })
      }
    },
    cancellation(row){
      var that = this
      that
        .$confirm('确定注销吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id,
            type: 1
          }
          memberCancelApplyHandle(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    backups(row) {
      var that = this
      that
        .$confirm('确定备份吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          memberCancelApplyBackups(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped lang="scss">
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

.screenStyle {
  margin: 10px 0px;
}
.screen_s {
  max-height: 220px;
  overflow: auto;
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
.el-row {
    margin-bottom: 20px;
&:last-child {
     margin-bottom: 0;
 }
}

.list-search {
  z-index: 1;
  position: relative;
}

.input-with-select {
  float: left;
  margin-bottom: 20px;
}
.switch{
  font-size: 13px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  color: #048CFC;
  cursor:pointer;
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
.filterCriteria {
  border: 1px dashed #eeeeee;
  font-size: 13px;
  color: #999999;
  margin-top: 82px;
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
  ::v-deep .el-popover {
    position: fixed;
  }
}
.sotrTime {
  display: inline-block;
  width: 83%;
}
.setField_h {
  height: 250px;
  overflow: auto;
}
.field_s_select {
  background-color: #344563;
  border: 1px solid transparent;
  padding: 16px 10px;
}

.field_s {
  background: #f4f5f7;
  border: 1px solid transparent;
  padding: 21px 10px;
}
.setFields {
  margin: 15px 0px;
  font-size: 14px;
}
.setField_s {
  top: 0px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}
.setField {
  top: 61px;
  right: 0px;
  position: absolute;
  z-index: 4;
}
</style>
