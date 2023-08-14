<template>
  <div
    v-loading.fullscreen.lock="fullscreenLoading"
    class="app-container"
    :element-loading-text="fullscreenLoading_text"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>参会个人</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="filter.audit"
          placeholder="审核状态"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限参会状态" value="" />
          <el-option label="待审核" value="0" />
          <el-option label="已通过" value="1" />
          <el-option label="未通过" value="2" />
        </el-select>
        <el-select
          v-model="filter.source"
          placeholder="来源"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限来源" value="" />
          <el-option label="后台添加" value="1" />
          <el-option label="自主申请" value="0" />
        </el-select>
        <el-select
          v-model="filter.order_key"
          placeholder="排序方式"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="按参会状态排序" value="" />
          <el-option label="按添加时间排序" value="1" />
          <el-option label="按刷新时间排序" value="2" />
        </el-select>
        <el-input
          v-model="filter.keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
        >
          <el-select
            slot="prepend"
            v-model="filter.key_type"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="姓名" value="1" />
            <el-option label="电话" value="2" />
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
        <el-table-column label="姓名" min-width="220">
          <template slot-scope="scope">
            <div class="namecol">
              <el-popover placement="right" trigger="hover" width="300">
                <img :src="scope.row.photo_url" style="max-width: 274px">
                <span slot="reference">
                  <span class="avatar">
                    <el-avatar :src="scope.row.photo_url" />
                  </span>
                </span>
              </el-popover>
              <span>
                <div>
                  <el-link
                    :href="scope.row.link"
                    target="_blank"
                    type="primary"
                    class="text"
                    :underline="false"
                  >
                    {{ scope.row.fullname }}
                  </el-link>
                  <img
                    v-if="scope.row.bind_weixin == 1"
                    style="vertical-align: middle; margin-left: 4px"
                    :src="require('@/assets/images/wx_icon.png')"
                  >
                </div>
                <div>
                  <span>{{ scope.row.age }}</span>
                  ·
                  <span>{{ scope.row.sex_cn }}</span>
                  ·
                  <span>{{ scope.row.education_cn }}</span>
                  ·
                  <span>{{ scope.row.experience_cn }}</span>
                </div>
              </span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="简历完整度" min-width="120">
          <template slot-scope="scope">
            <el-progress
              :text-inside="true"
              :stroke-width="16"
              :percentage="scope.row.complete"
              style="width: 100px"
            />
          </template>
        </el-table-column>
        <el-table-column align="center" label="简历状态" min-width="150">
          <template slot-scope="scope">
            <div title="公开状态">
              <span v-if="scope.row.is_display === 0" >不公开</span>
              <span v-else-if="scope.row.is_display === 1">公开</span>
            </div>
            <div title="审核状态">
              <span v-if="scope.row.r_audit === 0" >待审核</span>
              <span v-else-if="scope.row.r_audit === 1">已通过</span>
              <span v-else-if="scope.row.r_audit === 2">未通过</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column align="center" label="刷新/添加时间" min-width="150">
          <template slot-scope="scope">
            <div title="简历刷新时间">
              <i class="el-icon-time" />{{ scope.row.refreshtime | timeFilter }}
            </div>
            <div title="参会时间">
              <span v-if="scope.row.jaddtime>0">
                <i class="el-icon-time" />
                <span>{{ scope.row.jaddtime | timeFilter }}</span>
              </span>
              <span v-else>-</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="参会状态" align="center" min-width="150">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.jaudit === 0" type="warning">待审核</el-tag>
            <el-tag v-else-if="scope.row.jaudit === 1" type="success">已通过</el-tag>
            <el-tag v-else-if="scope.row.jaudit === 2" type="danger">未通过</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="数据来源" align="center" min-width="150">
          <template slot-scope="scope">
            {{ scope.row.jsource === 1 ? '后台添加' : '自主申请' }}
          </template>
        </el-table-column>
        <el-table-column align="right" label="操作" min-width="150">
          <template slot-scope="scope">
            <el-button size="mini" type="primary" @click="editPersonal(scope.row)">修改</el-button>
            <el-button size="mini" type="danger" @click="deletePersonal(scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="goAdd">添加个人</el-button>
          <el-button size="small" type="primary" @click="setStatus">参会状态</el-button>
          <el-button size="small" type="primary" @click="dialogAddVisible = true">批量添加</el-button>
          <el-button size="small" type="primary" @click="deletePersonalBatch">批量删除</el-button>
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
    <!--参会状态弹框-->
    <el-dialog
      title="设置参会状态"
      :visible.sync="dialogStatusVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="参会状态">
          <el-radio-group v-model="status">
            <el-radio label="0">待审核</el-radio>
            <el-radio label="1">已通过</el-radio>
            <el-radio label="2">未通过</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogStatusVisible = false">取 消</el-button>
        <el-button type="primary" @click="funSetStatus">
          确 定
        </el-button>
      </div>
    </el-dialog>
    <!--批量添加弹框-->
    <el-dialog
      title="批量添加个人"
      :visible.sync="dialogAddVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="刷新时间">
          <el-select v-model="add.settr" placeholder="请选择" @change="changeOption('settr')">
            <el-option
              v-for="(item, index) in options_settr"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="学历">
          <el-select v-model="add.edu" placeholder="请选择" @change="changeOption('edu')">
            <el-option label="不限" value="" />
            <el-option
              v-for="(item, index) in options_edu"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="工作经验">
          <el-select v-model="add.exp" placeholder="请选择" @change="changeOption('exp')">
            <el-option label="不限" value="" />
            <el-option
              v-for="(item, index) in options_exp"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAddVisible = false">取 消</el-button>
        <el-button type="primary" @click="funAddBatch">
          确 定
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { parseTime } from '@/utils/index'
import { personalList, participateStatus, participateDelete, personalBatchAdd } from '@/api/jobfairol'
import { getClassify } from '@/api/classify'
export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    },
    auditFilter(audit) {
      switch (audit) {
        case 1:
          return 'success'
        case 2:
          return 'danger'
        case 3:
          return 'info'
        default:
          return ''
      }
    }
  },
  data() {
    return {
      jobfair_id: '',
      audit: '',
      enable_setmeal_id: '',
      jobfair_list: [],
      total: 0,
      currentPage: 1,
      pagesize: 15,
      listLoading: !1,
      list: [],
      tableIdarr: [],
      uIdarr: [],
      status: '1',
      dialogStatusVisible: false,
      dialogAddVisible: false,
      add: {
        settr: '',
        edu: '',
        exp: '',
        settr_cn: '不限',
        edu_cn: '不限',
        exp_cn: '不限'
      },
      options_settr: [
        { id: '', name: '不限' },
        { id: 3, name: '三天内' },
        { id: 7, name: '一周内' },
        { id: 30, name: '一月内' },
        { id: 180, name: '半年内' },
        { id: 360, name: '一年内' }
      ],
      options_edu: [],
      options_exp: [],
      filter: {
        audit: '',
        source: '',
        order_key: '',
        keyword: '',
        key_type: '1'
      },
      fullscreenLoading: false,
      fullscreenLoading_text: ''
    }
  },
  created(){
    this.jobfair_id = this.$route.query.jobfair_id
    this.getExhibitors()
    this.getOptions()
  },
  methods: {
    // 获取参会个人
    getExhibitors: function(){
      this.listLoading = true
      const param = {
        jobfair_id: this.jobfair_id,
        audit: this.filter.audit,
        source: this.filter.source,
        order_key: this.filter.order_key,
        key_type: this.filter.key_type,
        keyword: this.filter.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      personalList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    // 修改
    editPersonal (row) {
      this.uIdarr = []
      this.uIdarr.push(row.uid)
      this.status = row.audit + ''
      this.dialogStatusVisible = true
    },
    setStatus () {
      if (this.tableIdarr.length === 0) {
        this.$message.error('请选择个人')
        return false
      }
      this.status = '1'
      this.dialogStatusVisible = true
    },
    funSetStatus () {
      if (this.listLoading === true) {
        return false
      }
      this.listLoading = true
      const params = {
        jobfair_id: this.jobfair_id,
        uid: this.uIdarr,
        audit: this.status
      }
      participateStatus(params, 'post').then(response => {
        if (response.code === 200) {
          this.$message.success(response.message)
          this.listLoading = false
          this.dialogStatusVisible = false
          this.getExhibitors()
          return true
        } else {
          this.listLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    // 删除参会个人
    deletePersonal (row) {
      var that = this
      that
        .$confirm('此操作将永久删除该个人, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          this.uIdarr = []
          this.uIdarr.push(row.uid)
          that.commitDelete()
        })
        .catch(() => {})
    },
    deletePersonalBatch () {
      if (this.tableIdarr.length === 0) {
        this.$message.error('请选择个人')
        return false
      }
      var that = this
      that
        .$confirm('此操作将永久删除个人, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          that.commitDelete()
        })
        .catch(() => {})
    },
    commitDelete () {
      if (this.listLoading === true) {
        return false
      }
      this.listLoading = true
      const params = {
        jobfair_id: this.jobfair_id,
        uid: this.uIdarr
      }
      participateDelete(params, 'post').then(response => {
        if (response.code === 200) {
          this.$message.success(response.message)
          this.listLoading = false
          this.getExhibitors()
          return true
        } else {
          this.listLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    // 分页
    handleSizeChange: function(val){
      this.pagesize = val
      this.getExhibitors()
    },
    handleCurrentChange: function(val){
      this.currentPage = val
      this.getExhibitors()
    },
    // 删选
    funSearch: function(){
      this.currentPage = 1
      this.getExhibitors()
    },
    // 关键字筛选
    funSearchKeyword: function(){
      this.currentPage = 1
      this.getExhibitors()
    },
    // 列表选中回调事件
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      this.uIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
          this.uIdarr.push(item.uid)
        }
      }
    },
    goAdd: function(){
      this.$router.push({
        path: '/jobfairol/exhibitors/personal/add',
        query: {
          id: this.jobfair_id
        }
      })
    },
    // 批量添加
    funAddBatch () {
      const that = this
      const confirmTitle = `确定要批量添加刷新时间为${this.add.settr_cn}、学历为${this.add.edu_cn}、工作经验为${this.add.exp_cn}的简历吗？`
      that
        .$confirm(confirmTitle, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          that.fullscreenLoading_text = '正在添加数据，请稍候'
          that.fullscreenLoading = true
          that.dialogAddVisible = false
          this.funAddBatchRun(1)
        })
        .catch(() => {})
    },
    funAddBatchRun(page){
      const that = this
      const params = {
        jobfair_id: that.jobfair_id,
        settr: that.add.settr,
        education: that.add.edu,
        experience: that.add.exp,
        page: page,
        pagesize: 100
      }
      personalBatchAdd(params, 'post').then(response => {
        if (response.code === 200) {
          if (response.data == 0){
            that.fullscreenLoading_text = '正在添加数据，' + response.message
            that.funAddBatchRun(parseInt(page) + 1)
          } else {
            that.$message.success(response.message)
            that.fullscreenLoading = false
            that.getExhibitors()
            return true
          }
        } else {
          that.listLoading = false
          that.$message.error(response.message)
          return false
        }
      }).catch(() => {
        that.listLoading = false
      })
    },
    changeOption (type) {
      if (type === 'settr') {
        this.add.settr_cn = this.add.settr ? this.options_settr.filter(value => parseInt(value['id']) === parseInt(this.add.settr))[0].name : '不限'
      } else if (type === 'edu') {
        this.add.edu_cn = this.add.edu ? this.options_edu.filter(value => parseInt(value['id']) === parseInt(this.add.edu))[0].name : '不限'
      } else {
        this.add.exp_cn = this.add.exp ? this.options_exp.filter(value => parseInt(value['id']) === parseInt(this.add.exp))[0].name : '不限'
      }
    },
    getOptions () {
      const that = this
      getClassify({ type: 'education,experience' })
        .then(response => {
          that.options_edu = response.data.education
          that.options_exp = response.data.experience
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped>
  .qrcode{vertical-align:middle}
.namecol {
  position: relative;
  padding-left: 40px;
}
.namecol .avatar {
  width: 42px;
  height: 42px;
  padding: 1px;
  display: inline-block;
  border: 0;
  border-radius: 30px;
  position: absolute;
  top: 4px;
  left: -10px;
}
</style>
