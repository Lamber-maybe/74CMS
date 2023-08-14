<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>会员列表</span>
      </div>
      <div class="list-search">
        <el-select
          v-if="showOptionsUtype===true"
          v-model="utype"
          class="list-options"
          placeholder="不限身份"
          @change="funSearch"
        >
          <el-option label="不限身份" value="" />
          <el-option label="企业会员" value="1" />
          <el-option label="个人会员" value="2" />
        </el-select>
        <el-select
          v-if="showOptionsStatus===true"
          v-model="status"
          class="list-options"
          placeholder="不限会员状态"
          @change="funSearch"
        >
          <el-option label="不限会员状态" value="" />
          <el-option label="已锁定" value="0" />
        </el-select>
        <el-select
          v-model="sort"
          class="list-options"
          placeholder="按注册时间排序"
          @change="funSearch"
        >
          <el-option label="按注册时间排序" value="" />
          <el-option label="按登录时间排序" value="logintime" />
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
          v-model="platform"
          class="list-options"
          placeholder="不限注册来源"
          @change="funSearch"
        >
          <el-option label="不限注册来源" value="" />
          <el-option
            v-for="(item, index) in platformOptions"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
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
            <el-option label="UID" value="1" />
            <el-option label="用户名" value="2" />
            <el-option label="手机号" value="3" />
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
        <el-table-column
          align="center"
          label="UID"
          prop="uid"
          width="80"
        />
        <el-table-column v-if="listtype=='company'" label="企业名称" prop="companyname" />
        <el-table-column label="用户名" prop="username" />
        <el-table-column label="手机号" prop="mobile" />
        <el-table-column align="center" label="注册时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.reg_time | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="最近登录">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span v-if="scope.row.last_login_time == 0">从未登录</span>
            <span v-else>{{ scope.row.last_login_time | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="会员状态">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.status == 1" type="success">正常</el-tag>
            <el-tag v-else type="danger">已锁定</el-tag>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="funDetail(scope.$index, scope.row)">
              查看
            </el-button>
            <el-button size="small" type="primary" @click="funEdit(scope.$index, scope.row)">
              修改
            </el-button>
            <el-dropdown trigger="click" style="margin-left: 10px">
              <el-button type="small">···</el-button>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  @click.native="funLog(scope.$index, scope.row)"
                >
                  日志
                </el-dropdown-item>
                <el-dropdown-item
                  @click.native="funLock(scope.$index, scope.row)"
                >
                  {{ scope.row.status == 1 ? '锁定' : '解锁' }}
                </el-dropdown-item>
                <el-dropdown-item
                  @click.native="funDelete(scope.row)"
                >
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
          <el-button size="small" type="primary" @click="funAdd">
            添加会员
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除所选
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right;">
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
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      :width="dialogWidth"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaform
        v-if="dialogContent == 'form'"
        :uid="childId"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
      <detail
        v-if="dialogContent == 'detail'"
        :uid="detailUid"
        :detail-info="detailInfo"
        @setDialogFormVisible="closeDialog"
      />
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
  </div>
</template>

<script>
import MemberLog from '@/components/MemberLog/Index'
import { getClassify } from '@/api/classify'
import diaform from './form.vue'
import detail from './detail.vue'
import { memberList, memberLock, memberDelete } from '@/api/member'
import { parseTime } from '@/utils/index'

export default {
  components: {
    diaform,
    detail,
    MemberLog
  },
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: ['listtype', 'showOptionsStatus', 'showOptionsUtype'],
  data() {
    return {
      platformOptions: [],
      detailInfo: null,
      detailUid: 0,
      dialogWidth: '35%',
      dialogContent: 'form',
      tableIdarr: [],
      childId: 0,
      dialogTitle: '',
      dialogFormVisible: false,
      status: '',
      regtime: '',
      sort: '',
      platform: '',
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      dialogListVisible: false,
      listUid: 0,
      utype: ''
    }
  },
  created() {
    this.fetchData()
    this.fetchPlatformOptions()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const list_type = this.listtype
      const param = {
        list_type,
        status: this.status,
        regtime: this.regtime,
        sort: this.sort,
        platform: this.platform,
        utype: this.utype,
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      memberList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    fetchPlatformOptions(){
      const params = {
        type: 'platform'
      }
      getClassify(params).then(response => {
        this.platformOptions = [...response.data]
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
    closeDialog() {
      this.dialogWidth = '35%'
      this.dialogContent = 'form'
      this.dialogFormVisible = false
    },
    funAdd(index, row) {
      this.childId = 0
      this.dialogWidth = '35%'
      this.dialogTitle = '添加会员'
      this.dialogFormVisible = true
    },
    funDetail(index, row) {
      this.detailInfo = row
      this.dialogWidth = '35%'
      this.dialogContent = 'detail'
      this.detailUid = row.uid
      this.dialogTitle = '查看会员'
      this.dialogFormVisible = true
    },
    funLog(index, row){
      this.listUid = row.uid
      this.dialogListVisible = true
    },
    closeListDialog() {
      this.dialogListVisible = false
    },
    funEdit(index, row) {
      if (row) {
        this.childId = row.uid
      } else {
        this.childId = 0
      }
      this.dialogWidth = '35%'
      this.dialogTitle = '编辑会员'
      this.dialogFormVisible = true
    },
    funLock(index, row) {
      var that = this
      let tip = ''
      let status = ''
      if (row.status == 1) {
        tip = '确定锁定该会员吗？'
        status = 0
      } else {
        tip = '确定解锁该会员吗？'
        status = 1
      }
      that
        .$confirm(tip, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: row.uid,
            status
          }
          memberLock(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funDelete(row){
      var that = this
      that
        .$confirm('此操作将永久删除该会员, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: [row.uid]
          }
          memberDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funDeleteBatch(){
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要删除的会员')
        return false
      }
      var that = this
      that
        .$confirm('此操作将永久删除该会员, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: that.tableIdarr
          }
          memberDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.uid)
        }
      }
    }
  }
}
</script>
