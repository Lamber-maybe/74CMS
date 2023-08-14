<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>企业备份资料</span>
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
          setmealName != ''
        "
        class="filterCriteria"
      >
        <div style="float:left;display: inline-block;margin-top: 6px;">已选条件：</div>
        <div style="float:left;display: inline-block;width: 80%">
          <div v-if="setmealName != ''" class="selected">
            <div class="name">企业套餐：{{ setmealName.name }}</div>
            <div class="closes" @click="reset(setmealName.field)"><i class="el-icon-close" /></div>
          </div>
        </div>
        <div style="float:right;display: inline-block;margin-top: 6px;color:#409eff;font-size: 13px;" @click="reset('all')">
          <i class="el-icon-delete" />
          清空条件
        </div>
        <div style="clear:both;" />
      </div>
      <div class="spaceline" />
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
                <!-- 企业套餐-->
                <div v-if="items.field == 'setmeal_name'" class="screen_s">
                  <el-checkbox-group>
                    <div v-for="item in mealScreenData" class="screenStyle">
                      <el-radio :key="item.id" v-model="setmeal" :label="item.id">{{ item.name }}</el-radio>
                    </div>
                  </el-checkbox-group>
                </div>
                <div style="margin-top: 10px;">
                  <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                  <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                </div>
                <div
                  v-if="
                    items.field == 'setmeal_name'
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
            <div v-if="items.field == 'cid'">
              <span v-if="scope.row.cid == ''">-</span>
              <span v-else>{{ scope.row.cid }}</span>
            </div>
            <div v-if="items.field == 'companyname'">
              <span v-if="scope.row.companyname == ''">-</span>
              <span v-else>{{ scope.row.companyname }}</span>
            </div>
            <div v-if="items.field == 'contact'">
              <span v-if="scope.row.contact == ''">-</span>
              <span v-else>{{ scope.row.contact }}</span>
            </div>
            <div v-if="items.field == 'contact_mobile'">
              <span v-if="scope.row.contact_mobile == ''">-</span>
              <span v-else>{{ scope.row.contact_mobile }}</span>
            </div>
            <div v-if="items.field == 'mobile'">
              <span v-if="scope.row.mobile == ''">-</span>
              <span v-else>{{ scope.row.mobile }}</span>
            </div>
            <div v-if="items.field == 'jobs_num'">
              <span v-if="scope.row.jobs_num == ''">0</span>
              <span v-else>{{ scope.row.jobs_num }}</span>
            </div>
            <div v-if="items.field == 'job_apply_count'">
              <span v-if="scope.row.job_apply_count == ''">0</span>
              <span v-else>{{ scope.row.job_apply_count }}</span>
            </div>
            <div v-if="items.field == 'download_resume_num'">
              <span v-if="scope.row.download_resume_num == ''">0</span>
              <span v-else>{{ scope.row.download_resume_num }}</span>
            </div>
            <div v-if="items.field == 'setmeal_name'">
              <span v-if="scope.row.setmeal_name == ''">-</span>
              <span v-else>{{ scope.row.setmeal_name }}</span>
            </div>
            <div v-if="items.field == 'reg_time'">
              <span v-if="scope.row.reg_time == ''">-</span>
              <span v-else>{{ scope.row.reg_time | timeFilter }}</span>
            </div>
            <div v-if="items.field == 'cancel_apply_time'">
              <span v-if="scope.row.cancel_apply_time == ''">-</span>
              <span v-else>{{ scope.row.cancel_apply_time | timeFilter }}</span>
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
                <!-- 企业套餐-->
                <div v-if="items.field == 'setmeal_name'" class="screen_s">
                  <el-checkbox-group>
                    <div v-for="item in mealScreenData" class="screenStyle">
                      <el-radio :key="item.id" v-model="setmeal" :label="item.id">{{ item.name }}</el-radio>
                    </div>
                  </el-checkbox-group>
                </div>
                <div style="margin-top: 10px;">
                  <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                  <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                </div>
                <div
                  v-if="
                    items.field == 'setmeal_name'
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
            <div v-if="items.field == 'cid'">
              <span v-if="scope.row.cid == ''">-</span>
              <span v-else>{{ scope.row.cid }}</span>
            </div>
            <div v-if="items.field == 'companyname'">
              <span v-if="scope.row.companyname == ''">-</span>
              <span v-else>{{ scope.row.companyname }}</span>
            </div>
            <div v-if="items.field == 'contact'">
              <span v-if="scope.row.contact == ''">-</span>
              <span v-else>{{ scope.row.contact }}</span>
            </div>
            <div v-if="items.field == 'contact_mobile'">
              <span v-if="scope.row.contact_mobile == ''">-</span>
              <span v-else>{{ scope.row.contact_mobile }}</span>
            </div>
            <div v-if="items.field == 'mobile'">
              <span v-if="scope.row.mobile == ''">-</span>
              <span v-else>{{ scope.row.mobile }}</span>
            </div>
            <div v-if="items.field == 'jobs_num'">
              <span v-if="scope.row.jobs_num == ''">0</span>
              <span v-else>{{ scope.row.jobs_num }}</span>
            </div>
            <div v-if="items.field == 'job_apply_count'">
              <span v-if="scope.row.job_apply_count == ''">0</span>
              <span v-else>{{ scope.row.job_apply_count }}</span>
            </div>
            <div v-if="items.field == 'download_resume_num'">
              <span v-if="scope.row.download_resume_num == ''">0</span>
              <span v-else>{{ scope.row.download_resume_num }}</span>
            </div>
            <div v-if="items.field == 'setmeal_name'">
              <span v-if="scope.row.setmeal_name == ''">-</span>
              <span v-else>{{ scope.row.setmeal_name }}</span>
            </div>
            <div v-if="items.field == 'reg_time'">
              <span v-if="scope.row.reg_time == ''">-</span>
              <span v-else>{{ scope.row.reg_time | timeFilter }}</span>
            </div>
            <div v-if="items.field == 'cancel_apply_time'">
              <span v-if="scope.row.cancel_apply_time == ''">-</span>
              <span v-else>{{ scope.row.cancel_apply_time | timeFilter }}</span>
            </div>
          </template>
        </el-table-column>
      </el-table>

      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="10">
          <el-button size="small" type="primary" @click="choose">
            全选/反选
          </el-button>
          <el-button size="small" type="primary" @click="funExport">
            导出
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
  backupsList,
  backupsDelete,
  exportBackups,
  classify
} from '@/api/member_cancel_apply'
import { parseTime } from '@/utils/index'
import { customLndexAdd, customLndex } from '@/api/outbound'
export default {
  name: 'PersonCancelBackup',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      tableData: [],
      tableIdarr: [],
      fieldData: [
        { name: '企业id', field: 'cid', select: true, is_locking: false, is_lock_display: false, width: 100, is_sortable: false },
        { name: '企业名称', field: 'companyname', select: true, is_locking: false, is_lock_display: false, width: 200, is_sortable: false },
        { name: '联系人', field: 'contact', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '联系方式', field: 'contact_mobile', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '注册手机号', field: 'mobile', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '发布职位', field: 'jobs_num', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '收到简历', field: 'job_apply_count', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '下载简历', field: 'download_resume_num', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '企业套餐', field: 'setmeal_name', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: false },
        { name: '注册时间', field: 'reg_time', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: 'custom' },
        { name: '申请时间', field: 'cancel_apply_time', select: true, is_locking: false, is_lock_display: false, width: 175, is_sortable: 'custom' }
      ],
      mealScreenData: [],
      setmeal: '',
      setmealName: '',
      sort_type: '',
      sort: ''
    }
  },
  created() {
    this.fetchData()
    this.getField()
    this.classify()
  },
  methods: {
    classify() {
      classify({ type: 'setmealList' })
        .then(res => {
          this.mealScreenData = res.data
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
    getField(){
      customLndex({
        name: 'companyCancelBackup'
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
        name: 'companyCancelBackup',
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
        this.setmeal = ''
      }
      if (field == 'setmeal_name') {
        this.setmeal = ''
      }
      this.confirm()
    },
    confirm() {
      this.currentPage = 1
      if (this.setmeal != '') {
        for (var i = 0; i <= this.mealScreenData.length - 1; i++) {
          if (this.setmeal == this.mealScreenData[i].id) {
            this.setmealName = {
              field: 'setmeal_name',
              name: this.mealScreenData[i].name
            }
          }
        }
      } else {
        this.setmealName = ''
      }
      this.backupsList()
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
      this.backupsList()
    },
    backupsList() {
      this.loading = true
      if (localStorage.getItem('resume_audit') && localStorage.getItem('resume_audit') == '1') {
        this.setmeal = '0'
        localStorage.setItem('resume_audit', '')
      }
      backupsList({
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize,
        utype: 1,
        setmeal_id: this.setmeal,
        sort_type: this.sort_type,
        sort: this.sort
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
    goto(target) {
      this.$router.push(target)
    },
    funExport() {
      var that = this
      if (that.$store.state.user.access_export == 0) {
        that.$message.error('当前管理员没有导出权限')
        return false
      }
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要导出的数据')
        return false
      }
      const param = {
        utype: 1,
        id: that.tableIdarr.join(',')
      }
      exportBackups(param).then(response => {
        if (response.code == 200) {
          that.exportExcel(response.data)
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
          '企业注销备份导出'
        )
      })
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]))
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
            id: that.tableIdarr,
            utype: 1
          }
          backupsDelete(param).then(response => {
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
      const param = {
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize,
        utype: 1
      }
      backupsList(param).then(response => {
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
    }
  }
}
</script>
<style scoped lang="scss">
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
.drop_down {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/drop-down.png') no-repeat center center;

&
:hover {
  background: url('../../../../assets/images/company/crm/drop-down1.png') no-repeat center center;
}
  ::v-deep .el-popover {
    position: fixed;
  }
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
.screen_s {
  max-height: 220px;
  overflow: auto;
}
.screenStyle {
  margin: 10px 0px;
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
}
.sotrTime {
  display: inline-block;
  width: 83%;
}
</style>
