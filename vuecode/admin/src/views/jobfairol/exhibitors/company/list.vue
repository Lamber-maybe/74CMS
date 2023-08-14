<template>
  <div v-loading.fullscreen.lock="fullscreenLoading" :element-loading-text="fullscreenLoading_text" class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>参会企业</span>
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
          v-model="filter.setmeal_id"
          placeholder="套餐类型"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限套餐类型" value="" />
          <el-option
            v-for="(item, index) in options_setmeal"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
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
          v-model="filter.stick"
          placeholder="置顶"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限置顶" value="" />
          <el-option label="未置顶" value="0" />
          <el-option label="已置顶" value="1" />
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
            <el-option label="公司名" value="1" />
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
        <el-table-column label="企业名称" show-overflow-tooltip min-width="180">
          <template slot-scope="scope">
            <el-link :href="scope.row.link" target="_blank" type="primary">
              {{ scope.row.companyname }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="会员套餐" align="center" min-width="110">
          <template slot-scope="scope">
            {{ scope.row.setmeal_cn }}
          </template>
        </el-table-column>
        <el-table-column label="认证状态" align="center" min-width="90">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.c_audit === 0" type="warning">待认证</el-tag>
            <el-tag v-else-if="scope.row.c_audit === 1" type="success">已认证</el-tag>
            <el-tag v-else-if="scope.row.c_audit === 2" type="danger">未通过</el-tag>
            <el-tag v-else type="info">未认证</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="数据来源" align="center" min-width="90">
          <template slot-scope="scope">
            {{ scope.row.source === 1 ? '后台添加' : '自主申请' }}
          </template>
        </el-table-column>
        <el-table-column label="参会状态" align="center" min-width="90">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.audit === 0" type="warning">待审核</el-tag>
            <el-tag v-else-if="scope.row.audit === 1" type="success">已通过</el-tag>
            <el-tag v-else-if="scope.row.audit === 2" type="danger">未通过</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="联系方式" align="center" min-width="160">
          <template slot-scope="scope">
            {{ scope.row.mobile }}({{ scope.row.contact }})
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="created_at"
          label="添加日期"
          min-width="150"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="微信直面" align="center" min-width="120">
          <template slot-scope="scope">
            {{ scope.row.add_status === 1 ? '已添加(' + scope.row.add_day + '天)' : '客服' }}
          </template>
        </el-table-column>
        <el-table-column fixed="right" align="right" label="操作" width="280">
          <template slot-scope="scope">
            <el-button size="mini" type="primary" @click="editCompany(scope.row)">修改</el-button>
            <el-button size="mini" type="primary" @click="setWxQr(scope.row)">直面</el-button>
            <el-button size="mini" type="primary" @click="setStick(scope.row)">置顶</el-button>
            <el-button size="mini" type="danger" @click="deleteCompany(scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="12">
          <el-button size="small" type="primary" @click="goAdd">添加企业</el-button>
          <el-button size="small" type="primary" @click="setStatus">参会状态</el-button>
          <el-button size="small" type="primary" @click="setQrService">微信直面设置为客服</el-button>
          <el-button size="small" type="primary" @click="dialogAddVisible = true">批量添加</el-button>
          <el-button size="small" type="primary" @click="setStickBatch">批量置顶</el-button>
          <el-button size="small" type="primary" @click="deleteCompanyBatch">批量删除</el-button>
        </el-col>
        <el-col :span="12" style="text-align: right;">
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
      title="设置参会企业置顶"
      :visible.sync="dialogStickyVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="认证状态">
          <el-radio-group v-model="stick">
            <el-radio label="1">置顶</el-radio>
            <el-radio label="0">取消置顶</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogStickyVisible = false">取 消</el-button>
        <el-button type="primary" @click="funSetStick">
          确 定
        </el-button>
      </div>
    </el-dialog>
    <el-dialog
      title="设置微信直面二维码"
      :visible.sync="dialogWxVisible"
      width="30%"
    >
      <el-form class="common-form" label-width="100px">
        <el-form-item label="参会企业">
          {{ companyName }}
        </el-form-item>
        <el-form-item label="二维码方式">
          <el-radio-group v-model="qr_mode">
            <el-radio label="1">自主上传</el-radio>
            <el-radio label="0">当场客服</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="qr_mode === '1'" label="">
          <el-upload
            class="thumb-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleQrSuccess"
            :before-upload="beforeThumbUpload"
          >
            <img v-if="qrcode" :src="qrcodeUrl" class="thumb" alt="">
            <i v-else class="el-icon-plus thumb-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="note" type="textarea" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogWxVisible = false">取 消</el-button>
        <el-button type="primary" @click="funSetQr">
          确 定
        </el-button>
      </div>
    </el-dialog>
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
      title="批量添加企业"
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
        <el-form-item label="企业认证">
          <el-select v-model="add.audit" placeholder="请选择" @change="changeOption('audit')">
            <el-option
              v-for="(item, index) in options_audit"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="套餐类型">
          <el-select v-model="add.setmeal_id" placeholder="请选择" @change="changeOption('setmeal')">
            <el-option label="不限" value="" />
            <el-option
              v-for="(item, index) in options_setmeal"
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
import { parseTime } from '@/utils'
import { companyList, companySticky, companyQrcode, participateStatus, participateDelete, setQrService, companyBatchAdd } from '@/api/jobfairol'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
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
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      headers: { admintoken: getToken() },
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
      dialogStickyVisible: false,
      stick: '1',
      auditSubmitLoading: false,
      stickIdarr: [],
      uIdarr: [],
      companyName: '',
      qrcode: '',
      qrcodeUrl: '',
      note: '',
      dialogWxVisible: false,
      fileupload_size: '',
      fileupload_ext: '',
      qr_mode: '1',
      status: '1',
      dialogStatusVisible: false,
      dialogAddVisible: false,
      add: {
        settr: '',
        audit: '',
        setmeal_id: '',
        settr_cn: '不限',
        audit_cn: '不限',
        setmeal_cn: '不限'
      },
      options_settr: [
        { id: '', name: '不限' },
        { id: 3, name: '三天内' },
        { id: 7, name: '一周内' },
        { id: 30, name: '一月内' },
        { id: 180, name: '半年内' },
        { id: 360, name: '一年内' }
      ],
      options_audit: [
        { id: '', name: '不限' },
        { id: 1, name: '已通过' },
        { id: 2, name: '待认证' },
        { id: 3, name: '未通过' },
        { id: 0, name: '未认证' }
      ],
      options_setmeal: [],
      filter: {
        audit: '',
        setmeal_id: '',
        source: '',
        stick: '',
        order_key: '',
        keyword: '',
        key_type: '1'
      },
      fullscreenLoading: false,
      fullscreenLoading_text: ''
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created(){
    this.jobfair_id = this.$route.query.jobfair_id
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.getExhibitors()
  },
  methods: {
    // 获取参会企业
    getExhibitors: function(){
      this.listLoading = true
      const param = {
        jobfair_id: this.jobfair_id,
        audit: this.filter.audit,
        setmeal_id: this.filter.setmeal_id,
        source: this.filter.source,
        stick: this.filter.stick,
        key_type: this.filter.key_type,
        order_key: this.filter.order_key,
        keyword: this.filter.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      companyList(param).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
        this.options_setmeal = response.data.setmeal
        this.listLoading = false
      })
    },
    // 修改参会企业
    editCompany (row) {
      this.uIdarr = []
      this.uIdarr.push(row.uid)
      this.status = row.audit + ''
      this.dialogStatusVisible = true
    },
    setStatus () {
      if (this.tableIdarr.length === 0) {
        this.$message.error('请选择企业')
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
    // 设置微信直面
    setWxQr (row) {
      this.companyName = row.companyname
      this.uIdarr = []
      this.uIdarr.push(row.uid)
      if (row.add_status === 1) {
        this.qrcode = row.qrcode
        this.qrcodeUrl = row.qrcode_url
        this.qr_mode = '1'
      } else {
        this.qrcode = ''
        this.qrcodeUrl = ''
      }
      this.note = row.note
      this.dialogWxVisible = true
    },
    funSetQr () {
      if (this.listLoading === true) {
        return false
      }
      this.listLoading = true
      const params = {
        jobfair_id: this.jobfair_id,
        uid: this.uIdarr,
        qrcode: this.qr_mode==1?this.qrcode:0,
        note: this.note
      }
      companyQrcode(params, 'post').then(response => {
        if (response.code === 200) {
          this.$message.success(response.message)
          this.listLoading = false
          this.dialogWxVisible = false
          this.getExhibitors()
          return true
        } else {
          this.listLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    // 设置微信直面为客服
    setQrService () {
      if (this.tableIdarr.length === 0) {
        this.$message.error('请选择企业')
        return false
      }
      if (this.listLoading === true) {
        return false
      }
      this.listLoading = true
      const params = {
        jobfair_id: this.jobfair_id,
        uid: this.uIdarr
      }
      setQrService(params, 'post').then(response => {
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
    // 置顶
    setStick (row) {
      this.uIdarr = []
      this.uIdarr.push(row.uid)
      this.stick = parseInt(row.stick) === 1 ? '0' : '1'
      this.dialogStickyVisible = true
      console.log(row.stick)
    },
    setStickBatch () {
      if (this.tableIdarr.length === 0) {
        this.$message.error('请选择要置顶的企业')
        return false
      }
      this.stick = '1'
      this.dialogStickyVisible = true
    },
    funSetStick () {
      if (this.listLoading === true) {
        return false
      }
      this.listLoading = true
      const params = {
        jobfair_id: this.jobfair_id,
        uid: this.uIdarr,
        stick: this.stick
      }
      companySticky(params, 'post').then(response => {
        if (response.code === 200) {
          this.$message.success(response.message)
          this.listLoading = false
          this.dialogStickyVisible = false
          this.getExhibitors()
          return true
        } else {
          this.listLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    },
    // 删除参会企业
    deleteCompany (row) {
      const that = this
      that
        .$confirm('此操作将永久删除该企业, 是否继续?', '提示', {
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
    deleteCompanyBatch () {
      if (this.tableIdarr.length === 0) {
        this.$message.error('请选择企业')
        return false
      }
      const that = this
      that
        .$confirm('此操作将永久删除企业, 是否继续?', '提示', {
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
    // 筛选
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
    handleSelectionChange(list) {
      this.tableIdarr = []
      this.uIdarr = []
      if (list.length > 0) {
        for (const item of list) {
          this.tableIdarr.push(item.id)
          this.uIdarr.push(item.uid)
        }
      }
    },
    // 上传图片
    handleQrSuccess(res, file) {
      if (res.code === 200) {
        this.qrcodeUrl = URL.createObjectURL(file.raw)
        this.qrcode = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeThumbUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('上传文件格式不允许')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    },
    // 添加企业
    goAdd: function(){
      this.$router.push({
        path: '/jobfairol/exhibitors/company/add',
        query: {
          id: this.jobfair_id
        }
      })
    },
    // 批量添加
    funAddBatch () {
      const that = this
      const confirmTitle = `确定要批量添加刷新时间为${this.add.settr_cn}、企业认证为${this.add.audit_cn}、套餐类型为${this.add.setmeal_cn}的企业吗？`
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
        audit: that.add.audit,
        setmeal_id: that.add.setmeal_id,
        page: page,
        pagesize: 100
      }
      companyBatchAdd(params, 'post').then(response => {
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
      } else if (type === 'audit') {
        this.add.audit_cn = this.add.audit ? this.options_audit.filter(value => parseInt(value['id']) === parseInt(this.add.audit))[0].name : '不限'
      } else {
        this.add.setmeal_cn = this.add.setmeal_id ? this.options_setmeal.filter(value => parseInt(value['id']) === parseInt(this.add.setmeal_id))[0].name : '不限'
      }
    }
  }
}
</script>
<style lang="scss" scoped>
  .qrcode{vertical-align:middle}
  .thumb-uploader {
    display: inline-block;
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    width: 122px;
    height: 122px;
  }
  .thumb-uploader img {
    width: 120px;
    height: 120px;
    border-radius: 6px;
  }
  .thumb-uploader:hover {
    border-color: #409eff;
  }
  .thumb-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 120px;
    height: 120px;
    line-height: 120px;
    text-align: center;
  }
</style>
