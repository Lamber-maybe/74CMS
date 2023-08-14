<template>
  <div class="app-container">
    <div v-if="listtype == 'invalid'" class="tip">
      <p>
        无效会员指个人会员注册后未创建简历、企业会员未完善企业资料的会员信息
      </p>
    </div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>会员列表</span>
      </div>
      <div class="list-search">
        <el-select
          v-if="showOptionsUtype === true"
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

        <el-select
          v-model="is_openid"
          class="list-options"
          placeholder="不限微信绑定"
          @change="funSearch"
        >
          <el-option label="不限微信绑定" value="" />
          <el-option label="已绑定微信" value="1" />
          <el-option label="未绑定微信" value="2" />
        </el-select>

        <el-select
          v-model="is_email"
          class="list-options"
          placeholder="不限邮箱绑定"
          @change="funSearch"
        >
          <el-option label="不限邮箱绑定" value="" />
          <el-option label="已绑定邮箱" value="1" />
          <el-option label="未绑定邮箱" value="2" />
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
            <el-option label="UID" value="1" />
            <el-option label="用户名" value="2" />
            <el-option label="手机号" value="3" />
            <el-option
              v-if="listtype == 'company'"
              label="企业名称"
              value="4"
            />
            <el-option v-if="listtype == 'personal'" label="姓名" value="4" />
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
        <el-table-column align="center" label="UID" prop="uid" width="80" />
        <el-table-column
          v-if="listtype == 'company'"
          label="企业名称"
          prop="companyname"
          min-width="280"
        />
        <el-table-column label="用户名" prop="username" min-width="150" />
        <el-table-column
          v-if="listtype == 'invalid'"
          align="center"
          label="身份类型"
          min-width="100"
        >
          <template slot-scope="scope">
            <span v-if="scope.row.utype == 1">企业会员</span>
            <span v-if="scope.row.utype == 2">个人会员</span>
          </template>
        </el-table-column>
        <el-table-column label="手机号" prop="mobile" min-width="120" />
        <el-table-column align="center" label="注册时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.reg_time | timeFilter }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="最近登录" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span v-if="scope.row.last_login_time == 0">从未登录</span>
            <span v-else>{{ scope.row.last_login_time | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="会员状态" width="100">
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.display"
              @change="modifyState(scope.row)"
            />
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="270">
          <template slot-scope="scope">
            <el-button size="small" @click="funManagement(scope.row)">
              管理
            </el-button>
            <el-button
              size="small"
              type="primary"
              @click="funDetail(scope.$index, scope.row)"
            >
              查看
            </el-button>
            <el-button
              size="small"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
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
                  {{ scope.row.status == 1 ? "锁定" : "解锁" }}
                </el-dropdown-item>
                <el-dropdown-item
                  @click.native="funIm(scope.row)"
                >
                  {{ scope.row.disable_im == 1 ? "解除禁聊" : "禁聊" }}
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
          <el-button size="small" type="primary" @click="funAdd">
            添加会员
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除所选
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right">
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
        :listtype="listtype"
        @setDialogFormVisible="closeDialog"
        @setDialogFormVisibleCompany="closeDialogCompany"
        @setDialogFormVisiblePersonal="closeDialogPersonal"
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
    <el-dialog
      title="将所选用户设置为禁聊"
      :visible.sync="dialogImFormVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="原因">
          <el-input v-model="setImReason" type="textarea" rows="3" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogImFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="funSetIm"> 确 定 </el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import MemberLog from '@/components/MemberLog/Index'
import { getClassify } from '@/api/classify'
import diaform from './form.vue'
import detail from './detail.vue'
import { memberList, memberLock, memberDelete, management, memberIm, memberModifyState } from '@/api/member'
import { parseTime, setMemberLogin } from '@/utils/index'

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
  props: ['listtype', 'showOptionsUtype'],
  data() {
    return {
      setImVal: 0,
      platformOptions: [],
      detailInfo: null,
      detailUid: 0,
      dialogWidth: '35%',
      dialogContent: 'form',
      tableIdarr: [],
      ImUid: '',
      childId: 0,
      dialogTitle: '',
      dialogFormVisible: false,
      dialogImFormVisible: false,
      setImReason: '',
      status: '',
      regtime: '',
      is_openid: '',
      is_email: '',
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
    modifyState(row){
      if (row.display === false) {
        this.$confirm('确定锁定该会员吗? 锁定后该会员将无法登录。', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.saveState(row)
        }).catch(() => {
          this.fetchData()
          return false
        })
      } else {
        this.saveState(row)
      }
    },
    saveState(row){
      const param = {
        status: row.display,
        uid: row.uid
      }
      memberLock(param).then(response => {
        this.$message.success(response.message)
        this.fetchData()
        return true
      }).catch(() => {
      })
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
    fetchData() {
      this.listLoading = true
      const list_type = this.listtype
      const param = {
        list_type,
        status: this.status,
        regtime: this.regtime,
        is_openid: this.is_openid,
        is_email: this.is_email,
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
    fetchPlatformOptions() {
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
    closeDialogCompany() {
      this.dialogFormVisible = false
      this.$confirm('该会员还没有完善企业资料，您可在无效会员列表查看', '提示', {
        confirmButtonText: '进入无效会员列表',
        cancelButtonText: '留在当前页',
        type: 'warning'
      }).then(() => {
        this.$router.push('/user/member/invalid')
      }).catch(() => {
      })
    },
    closeDialogPersonal() {
      this.dialogFormVisible = false
      this.$confirm('该会员还没有填写简历信息，您可在无效会员列表查看', '提示', {
        confirmButtonText: '进入无效会员列表',
        cancelButtonText: '留在当前页',
        type: 'warning'
      }).then(() => {
        this.$router.push('/user/member/invalid')
      }).catch(() => {
      })
    },
    funAdd(index, row) {
      this.childId = 0
      this.dialogWidth = '35%'
      if (this.listtype == 'company') {
        this.dialogTitle = '添加【企业】会员'
      } else if (this.listtype == 'personal') {
        this.dialogTitle = '添加【个人】会员'
      } else {
        this.dialogTitle = '添加【无效】会员'
      }
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
    funLog(index, row) {
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
        .catch(() => { })
    },
    funIm(row) {
      if (row.disable_im == 1){
        const tips = '确定将该会员解除禁聊吗？'
        const disable_im = 0
        this
          .$confirm(tips, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          })
          .then(() => {
            const param = {
              uid: row.uid,
              disable_im
            }
            memberIm(param).then(response => {
              this.$message.success(response.message)
              this.fetchData()
              return true
            })
          })
          .catch(() => { })
      } else {
        this.setImReason = ''
        this.setImVal = row.audit
        this.ImUid = row.uid
        this.dialogImFormVisible = true
      }
    },
    funSetIm() {
      const param = {
        uid: this.ImUid,
        disable_im: 1,
        reason: this.setImReason
      }
      memberIm(param).then(response => {
        this.$message.success(response.message)
        this.fetchData()
        this.dialogImFormVisible = false
        return true
      })
    },
    funDelete(row) {
      var that = this
      const msg = row.utype == 1 ? '删除企业会员将删除此会员的一切信息，包括企业资料、在招职位、下载的简历等，删除后不可恢复。是否继续？' : '删除个人会员将删除此会员的一切信息，包括简历、投递记录等信息，删除后不可恢复。是否继续？'
      that
        .$confirm(msg, '提示', {
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
        .catch(() => { })
    },
    funDeleteBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要删除的会员')
        return false
      }
      var that = this
      let msg = '删除企业会员将删除选中会员的一切信息，删除后不可恢复。是否继续？'
      if (that.listtype == 'company') {
        msg = '删除企业会员将删除选中会员的一切信息，包括企业资料、在招职位、下载的简历等，删除后不可恢复。是否继续？'
      } else if (that.listtype == 'personal') {
        msg = '删除个人会员将删除选中会员的一切信息，包括简历、投递记录等信息，删除后不可恢复。是否继续？'
      }
      that
        .$confirm(msg, '提示', {
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
        .catch(() => { })
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
