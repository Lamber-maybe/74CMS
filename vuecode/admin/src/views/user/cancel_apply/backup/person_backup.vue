<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>个人备份资料</span>
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
            <el-option label="简历名称" value="1" />
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
          :width="items.width"
          fixed
          :prop="items.field"
          v-if="items.is_locking == true && items.select == true"
          :sortable="items.is_sortable"
        >
          <template slot="header" slot-scope="scope">
            <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
              <span>{{ items.name }}</span>
              <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
              <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
            </div>
          </template>
          <template slot-scope="scope">
            <div v-if="items.field == 'rid'">
              <span v-if="scope.row.rid == ''">-</span>
              <span v-else>{{ scope.row.rid }}</span>
            </div>
            <div v-if="items.field == 'fullname'">
              <span v-if="scope.row.fullname == ''">-</span>
              <span v-else>{{ scope.row.fullname }}</span>
            </div>
            <div v-if="items.field == 'contact_mobile'">
              <span v-if="scope.row.contact_mobile == ''">-</span>
              <span v-else>{{ scope.row.contact_mobile }}</span>
            </div>
            <div v-if="items.field == 'mobile'">
              <span v-if="scope.row.mobile == ''">-</span>
              <span v-else>{{ scope.row.mobile }}</span>
            </div>
            <div v-if="items.field == 'delivery_num'">
              <span v-if="scope.row.delivery_num == ''">0</span>
              <span v-else>{{ scope.row.delivery_num }}</span>
            </div>
            <div v-if="items.field == 'download_num'">
              <span v-if="scope.row.download_num == ''">0</span>
              <span v-else>{{ scope.row.download_num }}</span>
            </div>
            <div v-if="items.field == 'viewed_num'">
              <span v-if="scope.row.viewed_num == ''">0</span>
              <span v-else>{{ scope.row.viewed_num }}</span>
            </div>
            <div v-if="items.field == 'invitation_num'">
              <span v-if="scope.row.invitation_num == ''">0</span>
              <span v-else>{{ scope.row.invitation_num }}</span>
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
          :width="items.width"
          :prop="items.field"
          v-if="items.is_locking == false && items.select == true"
          :sortable="items.is_sortable"
        >
          <template slot="header" slot-scope="scope">
            <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
              <span>{{ items.name }}</span>
              <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
              <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
            </div>
          </template>
          <template slot-scope="scope">
            <div v-if="items.field == 'rid'">
              <span v-if="scope.row.rid == ''">-</span>
              <span v-else>{{ scope.row.rid }}</span>
            </div>
            <div v-if="items.field == 'fullname'">
              <span v-if="scope.row.fullname == ''">-</span>
              <span v-else>{{ scope.row.fullname }}</span>
            </div>
            <div v-if="items.field == 'contact_mobile'">
              <span v-if="scope.row.contact_mobile == ''">-</span>
              <span v-else>{{ scope.row.contact_mobile }}</span>
            </div>
            <div v-if="items.field == 'mobile'">
              <span v-if="scope.row.mobile == ''">-</span>
              <span v-else>{{ scope.row.mobile }}</span>
            </div>
            <div v-if="items.field == 'delivery_num'">
              <span v-if="scope.row.delivery_num == ''">0</span>
              <span v-else>{{ scope.row.delivery_num }}</span>
            </div>
            <div v-if="items.field == 'download_num'">
              <span v-if="scope.row.download_num == ''">0</span>
              <span v-else>{{ scope.row.download_num }}</span>
            </div>
            <div v-if="items.field == 'viewed_num'">
              <span v-if="scope.row.viewed_num == ''">0</span>
              <span v-else>{{ scope.row.viewed_num }}</span>
            </div>
            <div v-if="items.field == 'invitation_num'">
              <span v-if="scope.row.invitation_num == ''">0</span>
              <span v-else>{{ scope.row.invitation_num }}</span>
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
            :page-sizes="[10,15, 20, 30, 40]"
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
  exportBackups
} from '@/api/member_cancel_apply'
import { parseTime } from '@/utils/index'
import { customLndex, customLndexAdd } from '@/api/outbound'

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
        { name: '简历id', field: 'rid', select: true, is_locking: false, is_lock_display: false, width: 100, is_sortable: false },
        { name: '简历名称', field: 'fullname', select: true, is_locking: false, is_lock_display: false, width: 201, is_sortable: false },
        { name: '联系方式', field: 'contact_mobile', select: true, is_locking: false, is_lock_display: false, width: 200, is_sortable: false },
        { name: '注册手机号', field: 'mobile', select: true, is_locking: false, is_lock_display: false, width: 200, is_sortable: false },
        { name: '投递数', field: 'delivery_num', select: true, is_locking: false, is_lock_display: false, width: 174, is_sortable: false },
        { name: '被下载数', field: 'download_num', select: true, is_locking: false, is_lock_display: false, width: 174, is_sortable: false },
        { name: '被查看数', field: 'viewed_num', select: true, is_locking: false, is_lock_display: false, width: 174, is_sortable: false },
        { name: '被邀请数', field: 'invitation_num', select: true, is_locking: false, is_lock_display: false, width: 174, is_sortable: false },
        { name: '注册时间', field: 'reg_time', select: true, is_locking: false, is_lock_display: false, width: 200, is_sortable: 'custom' },
        { name: '申请时间', field: 'cancel_apply_time', select: true, is_locking: false, is_lock_display: false, width: 200, is_sortable: 'custom' }
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
  },
  methods: {
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
        name: 'personCancelBackup'
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
        name: 'personCancelBackup',
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
        utype: 2,
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
          '简历ID',
          '姓名',
          '性别',
          '年龄',
          '现居住地',
          '身高',
          '婚姻状况',
          '学历',
          '工作经验',
          '户籍',
          '专业',
          '身份证号',
          '求职状态',
          '意向行业',
          '意向职位',
          '意向薪资',
          '意向工作地区',
          '意向工作性质',
          '联系手机',
          '联系微信',
          '联系qq',
          '联系邮箱',
          '注册时间',
          '刷新时间'
        ] // 上面设置Excel的表格第一行的标题
        const filterVal = [
          'id',
          'fullname',
          'sex',
          'age',
          'residence',
          'height',
          'marriage',
          'education',
          'experience',
          'householdaddress',
          'major',
          'idcard',
          'current',
          'intention_trade',
          'intention_jobs',
          'intention_wage',
          'intention_district',
          'intention_nature',
          'mobile',
          'weixin',
          'qq',
          'email',
          'addtime',
          'refreshtime'
        ]
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '个人注销备份导出'
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
            utype: 2
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
        utype: 2
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
        utype: 2,
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
.switch{
  font-size: 13px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  color: #048CFC;
  cursor:pointer;
}
.sotrTime {
  display: inline-block;
  width: 83%;
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
</style>
