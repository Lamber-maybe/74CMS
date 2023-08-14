<template>
  <div class="client_job_wrapper">
    <div class="box">
      <div class="button">
        <div :class="type=='recruit' ? 'selectButton' : ''" @click="switchTemplate('recruit')">招聘中职位</div>
        <div :class="type=='examine' ? 'selectButton' : ''" @click="switchTemplate('examine')">审核中职位</div>
        <div :class="type=='offline' ? 'selectButton' : ''" @click="switchTemplate('offline')">已下线职位</div>
      </div>
    </div>
    <div class="tables">
      <el-table
        v-loading="loading"
        :header-cell-style="{background:'#f9f9f9'}"
        :data="tableData"
        tooltip-effect="dark"
        style="width: 100%"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          type="selection"
          width="55"
        />
        <el-table-column
          label="职位名称"
          width="180"
        >
          <template slot-scope="scope">
            <div style="color: #409EFF;">
              <el-link
                :href="scope.row.job_link"
                target="_blank"
                type="primary"
              >
                {{ scope.row.jobname }}
              </el-link>
            </div>
            <div>{{ scope.row.district1_text }}{{ scope.row.district2_text }}{{ scope.row.district3_text }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="职位要求"
          width="180"
        >
          <template slot-scope="scope">
            <div>{{ scope.row.experience_text }}</div>
            <div>{{ scope.row.education_text }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="wage_text"
          label="薪资待遇"
        />
        <el-table-column
          prop="amount"
          label="招聘人数"
        />
        <el-table-column
          prop="mobile"
          label="联系方式"
        >
          <template slot-scope="scope">
            <span>{{ scope.row.mobile }}</span>
            <div v-if="scope.row.mobile !='' && scope.row.mobile !=null" class="dial" title="拨打联系人号码">
              <div class="disal_img" @click="clickDial(scope.row.mobile, scope.row.companyname )" />
            </div>
          </template>
        </el-table-column>
        <el-table-column
          label="刷新时间"
        >
          <template slot-scope="scope">
            <span>{{ scope.row.refreshtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="address"
          label="职位状态"
        >
          <template slot-scope="scope">
            <div v-if="scope.row.audit == 1">审核通过</div>
            <div v-if="scope.row.audit == 0">未审核</div>
            <div v-if="scope.row.audit == 2">审核未通过</div>
            <div v-if="scope.row.is_display == 0">不显示</div>
            <div v-if="scope.row.is_display == 1">显示中</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="address"
          label="收到简历"
        >
          <template slot-scope="scope">
            <div>收到{{ scope.row.job_apply_count }}份简历</div>
            <div>{{ scope.row.click }}次浏览</div>
          </template>
        </el-table-column>
        <el-table-column
          label="操作"
          width="180"
        >
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="funEdit(scope.row.id)">编辑</el-button>
            <el-button v-if="type!='examine'" type="text" size="small" @click="funCopy(scope.row)">复制</el-button>
            <el-button v-if="type=='examine'" type="text" size="small" @click="auditJob(scope.row)">审核</el-button>
            <el-button v-if="scope.row.is_display == 1 && type!='examine'" type="text" size="small" @click="close(scope.row)">关闭</el-button>
            <el-button v-if="scope.row.is_display == 0 && type!='examine'" type="text" size="small" @click="close(scope.row)">开启</el-button>
            <el-button v-if="type!='examine'" type="text" size="small" @click="funPoster(scope.row.id)">海报</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="bortton-page">
      <el-col :span="8">
        <el-button size="small" type="primary" @click="funAuditBatch('all')">
          审核
        </el-button>
        <el-button v-if="type == 'recruit'" size="small" type="primary" @click="funRefresh">
          刷新
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
    </div>
    <el-drawer
      v-if="innerDrawer"
      size="65%"
      :modal="false"
      title="职位编辑"
      append-to-body
      :visible.sync="innerDrawer"
    >
      <jobEdit :props_id="edit_job_id" @handleCloseJob="handleCloseJob" />
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
    <el-dialog
      title="审核"
      append-to-body
      :visible.sync="dialogFormVisible"
      width="25%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="认证状态">
          <el-radio-group v-model="setAuditVal">
            <el-radio
              v-for="(item, index) in options_audit"
              :key="index"
              :label="index"
            >
              {{ item }}
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="setAuditVal == 2" label="原因">
          <el-input v-model="setAuditReason" type="textarea" rows="3" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_audit"> 确 定 </el-button>
      </div>
    </el-dialog>
    <Poster
      v-if="showPoster"
      :poster-id="posterId"
      :poster-type="posterType"
      @closeDialog="showPoster = false"
    />
    <el-dialog
      :visible.sync="callDialogVisible"
      width="30%"
      append-to-body
      class="call"
      :before-close="callHandleClose"
    >
      <div class="box-content">
        <div class="content">
          <div class="title1">您还尚未开通云呼叫服务</div>
          <div class="title2">扫码添加客服</div>
          <div class="title3">快速开通呼叫业务</div>
        </div>
        <div class="code">
          <img src="../../../../../assets/images/outbound/renew.png" alt="">
        </div>
        <div style="clear:both" />
        <div class="slogan">一键发起云呼叫，自动录音，助力提升沟通效率！</div>
        <div class="advantage">
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">免硬件设备</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">录音清晰无杂音</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">外显销售手机号</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">招聘行业专用线路</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">稳定性更高</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">防封强、接通率高</div>
          </div>
        </div>
      </div>
    </el-dialog>
    <el-dialog
      :visible.sync="meetDialogVisible"
      width="30%"
      class="meet"
      append-to-body
      :before-close="meetHandleClose"
    >
      <div class="box-content">
        <div id="animation" class="img">
          <!--          <img src="../../../../../assets/images/outbound/meet.png" alt="">-->
        </div>
        <div class="hu">正在呼叫客户</div>
        <div class="telephone">{{ dialPhone }}</div>
        <div class="company">{{ dialName }}</div>
        <div class="tips">请您留意手机来电接听</div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { companyJob, setDisplay } from '@/api/company_crm'
import Poster from '@/components/Poster'
import jobEdit from '@/views/user/job/edit'
import { getClassify } from '@/api/classify'
import { jobAudit, jobList, jobRefresh } from '@/api/job'
import { parseTime } from '@/utils'
import { outboundCall } from '@/api/outbound'
export default {
  name: 'Job',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    Poster,
    jobEdit
  },
  props: {
    comid: {
      default: ''
    }
  },
  data(){
    return {
      loading: false,
      innerDrawer: false,
      setAuditVal: '',
      setAuditReason: '',
      multipleSelection: [],
      options_audit: [],
      posterId: '',
      posterType: '',
      showPoster: false,
      dialogFormVisible: false,
      currentPage: 1,
      pagesize: 10,
      total: 0,
      type: 'recruit',
      tableData: [],
      tableUidarr: [],
      edit_job_id: 0,
      audit_id: 0,
      callDialogVisible: false,
      meetDialogVisible: false,
      dialPhone: '',
      dialName: ''
    }
  },
  created() {
    this.recruit(this.comid)
    this.fetchData()
  },
  methods: {
    handleClose(){
      this.innerDrawer = false
    },
    funEdit(id){
      this.edit_job_id = id
      this.innerDrawer = true
    },
    fun_set_audit() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      var params = ''
      if (this.audit_id == 0){
        params = {
          id: this.multipleSelection,
          audit: this.setAuditVal,
          reason: this.setAuditReason
        }
      } else {
        params = {
          id: this.audit_id,
          audit: this.setAuditVal,
          reason: this.setAuditReason
        }
      }
      jobAudit(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          if (this.type == 'recruit'){
            this.recruit(this.comid)
          }
          if (this.type == 'offline'){
            this.offline(this.comid)
          }
          if (this.type == 'examine'){
            this.examine(this.comid)
          }
          this.dialogFormVisible = false
          this.auditSubmitLoading = false
          return true
        } else {
          this.$message.error(response.message)
          if (this.type == 'recruit'){
            this.recruit(this.comid)
          }
          if (this.type == 'offline'){
            this.offline(this.comid)
          }
          if (this.type == 'examine'){
            this.examine(this.comid)
          }
          this.dialogFormVisible = false
          this.auditSubmitLoading = false
          return false
        }
      })
    },
    fetchData() {
      this.listLoading = true
      getClassify({ type: 'jobAudit' })
        .then(response => {
          const options_audit_arr = [...response.data]
          options_audit_arr.forEach(el => {
            this.options_audit[el.id] = el.name
          })
        })
        .then(response => {

        })
    },
    auditJob(row) {
      // this.auditIdarr = this.tableIdarr
      this.audit_id = row.id
      this.setAuditReason = ''
      this.setAuditVal = 0
      this.dialogFormVisible = true
    },
    funAuditBatch() {
      if (this.multipleSelection.length == 0) {
        this.$message.error('请选择要审核的职位')
        return false
      }
      // this.auditIdarr = this.tableIdarr
      this.setAuditReason = ''
      this.setAuditVal = 0
      this.dialogFormVisible = true
    },
    goto(target) {
      this.$router.push(target)
    },
    recruit(id){
      this.loading = true
      companyJob({ 'company_id': id, 'type': 1, 'page': this.currentPage, 'pagesize': this.pagesize }).then(res => {
        this.tableData = res.data.items
        this.total = res.data.total
        this.currentPage = res.data.current_page
        this.loading = false
      }).catch(() => {
        this.loading = false
      })
    },
    offline(id){
      this.loading = true
      companyJob({ 'company_id': id, 'type': 3, 'page': this.currentPage, 'pagesize': this.pagesize }).then(res => {
        this.tableData = res.data.items
        this.total = res.data.total
        this.currentPage = res.data.current_page
        this.loading = false
      }).catch(() => {
        this.loading = false
      })
    },
    examine(id){
      this.loading = true
      companyJob({ 'company_id': id, 'type': 2, 'page': this.currentPage, 'pagesize': this.pagesize }).then(res => {
        this.tableData = res.data.items
        this.total = res.data.total
        this.currentPage = res.data.current_page
        this.loading = false
      }).catch(() => {
        this.loading = false
      })
    },
    switchTemplate(type){
      this.type = type
      this.currentPage = 1
      if (type == 'recruit'){
        this.recruit(this.comid)
      }
      if (type == 'offline'){
        this.offline(this.comid)
      }
      if (type == 'examine'){
        this.examine(this.comid)
      }
    },
    handleSizeChange(val){
      this.pagesize = val
      if (this.type == 'recruit'){
        this.recruit(this.comid)
      }
      if (this.type == 'offline'){
        this.offline(this.comid)
      }
      if (type == 'examine'){
        this.examine(this.comid)
      }
    },
    handleSelectionChange(val) {
      var id_arr = []
      var tableUidarr = []
      val.forEach(row => {
        id_arr.push(row.id)
        tableUidarr.push(row.uid)
      })
      this.multipleSelection = id_arr
      this.tableUidarr = tableUidarr
    },
    handleCurrentChange(val){
      this.currentPage = val
      if (this.type == 'recruit'){
        this.recruit(this.comid)
      }
      if (this.type == 'offline'){
        this.offline(this.comid)
      }
      if (type == 'examine'){
        this.examine(this.comid)
      }
    },
    funPoster(id) {
      this.showPoster = true
      this.posterId = id
      this.posterType = 'job'
    },
    funRefresh() {
      var that = this
      if (that.multipleSelection.length == 0) {
        that.$message.error('请选择要刷新的职位')
        return false
      }
      const param = {
        id: that.multipleSelection,
        uid: that.tableUidarr
      }
      jobRefresh(param).then(response => {
        that.$message.success(response.message)
        that.fetchData()
      })
    },
    handleCloseJob(){
      this.innerDrawer = false
      if (this.type == 'recruit'){
        this.recruit(this.comid)
      }
      if (this.type == 'offline'){
        this.offline(this.comid)
      }
      if (this.type == 'examine'){
        this.examine(this.comid)
      }
    },
    // 一键复制
    funCopy(row) {
      const that = this
      const copyMessage = `${row.companyname}
招聘：${row.jobname}
要求：工作经验${row.experience_text}、学历要求${row.education_text}
工资：${row.wage_text}
查看联系方式：${row.job_link}
-招聘求职就上${row.sitename}-`
      this.$copyText(copyMessage).then(function (e) {
        that.$message.success('内容已复制到剪切板！')
      }, function (e) {
        that.$message.error('抱歉，复制失败！')
      })
    },
    close(row){
      var is_display = ''
      if (row.is_display == 0){
        is_display = 1
      } else {
        is_display = 0
      }
      setDisplay({ 'uid': row.uid, 'id': row.id, 'is_display': is_display }).then(response => {
        this.$message.success(response.message)
        if (this.type == 'recruit'){
          this.recruit(this.comid)
        }
        if (this.type == 'offline'){
          this.offline(this.comid)
        }
      })
    },
    clickDial(phone, name){
      this.$confirm('是否对【' + name + '】' + phone + ' 发起呼叫？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        outboundCall(
          { 'mobile': phone }
        ).then(res => {
          if (res.code == 200){
            this.meetDialogVisible = true
            this.dialPhone = phone
            this.dialName = name
          }
          if (res.code == 4040){
            this.callDialogVisible = true
          }
        }).catch((res) => {
        })
      }).catch(() => {

      })
    },
    callHandleClose(){
      this.callDialogVisible = false
    },
    meetHandleClose(){
      this.meetDialogVisible = false
    }
  }
}
</script>

<style lang="scss" scoped>
.dial{
  position: relative;
  top:2px;
  left: 4px;
  width: 14px;
  height: 14px;
  display: inline-block;
  .disal_img{
    cursor:pointer;
    width: 14px;
    height: 14px;
    background: url('../../../../../assets/images/outbound/dial.png') no-repeat center;
  }
}
.meet{
  .box-content{
    .img{
      width: 150px;
      height:150px;
      text-align: center;
      margin-top: 40px;
      margin:0 auto;
      background: url('../../../../../assets/images/outbound/meet.png') no-repeat center;
    }
    .hu{
      text-align: center;
      margin-top: 30px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #949494;
    }
    .telephone{
      text-align: center;
      font-size: 30px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      margin-top: 26px;
    }
    .company{
      text-align: center;
      margin-top:25px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #666666;
    }
    .tips{
      text-align: center;
      height: 80px;
      line-height: 80px;
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #959595;
    }
  }
}
.meet{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
.call{
  .box-content{
    padding: 20px 30px 35px 30px;
    .advantage{
      .advantage-box{
        display: inline-block;
        width: 170px;
        .title{
          display: inline-block;
          float:left;
        }
        .img{
          display: inline-block;
          float:left;
          margin-right: 8px;
        }
      }
      margin-top: 25px;
    }
    .slogan{
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #FF550A;
      margin-top:24px;
    }
    .code{
      display: inline-block;
      margin-right: 30px;
      float: right;
      width: 117px;
      height: 117px;
      background: #FFFFFF;
      box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.14);
      border-radius: 8px;
      padding: 10px 10px;
      img{
        width: 100%;
        height: 100%;
      }
    }
    .content{
      display: inline-block;
      float: left;
      .title1{
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FF550A;
      }
      .title2{
        margin-top: 18px;
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #555555;
      }
      .title3{
        width: 133px;
        height: 24px;
        background: #FF6929;
        border-radius: 3px;
        text-align: center;
        line-height: 24px;
        font-size: 14px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FFFFFF;
        margin-top: 23px;
      }
    }
  }
}
.call{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
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
::v-deep .el-textarea{
  width: 85%;
}
::v-deep .el-dialog__body{
  z-index: 2500;
}
.tables{
  margin-bottom: 30px;
}
.client_job_wrapper{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding:0px 20px;
}
.box{
  margin: 15px  0px;
  .button{
    flex-shrink: 0;
    div{
      width: 90px;
      height: 30px;
      display: inline-block;
      background-color: #d4d4da30;
      border-radius: 20px;
      text-align: center;
      margin-top: 10px;
      font-size: 13px;
      line-height: 30px;
      cursor: pointer;
      color: #00000087;
      margin-right: 16px;
    }
    .selectButton{
      width: 90px;
      background-color: #0a79eb;
      color: #FFFFFF;
    }
  }
  .bortton-page{
    margin-top: 20px;
    padding-bottom: 40px;
  }
}
#animation {
  animation:pulse 1s .2s ease both infinite;
}
@-webkit-keyframes pulse {
  0% {
    -webkit-transform:scale(1)
  }
  50% {
    -webkit-transform:scale(1.1)
  }
  100% {
    -webkit-transform:scale(1)
  }
}
@-moz-keyframes pulse {
  0% {
    -moz-transform:scale(1)
  }
  50% {
    -moz-transform:scale(1.1)
  }
  100% {
    -moz-transform:scale(1)
  }
}
</style>
