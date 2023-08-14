<template>
  <div v-loading="loading" class="clientInfo_wrapper">
    <el-row class="main">
      <el-col :span="12">
        <div class="clientInfo_box">
          <!--          <div class="item">-->
          <!--            <div class="info">-->
          <!--              <span class="label">简历标签：</span>-->
          <!--              <div class="text overflow">-->
          <!--                <span v-if="details.tag_text_arr != ''">-->
          <!--                  <span v-for="item in details.tag_text_arr" :key="item.id" class="tag">-->
          <!--                    {{ item }}-->
          <!--                  </span>-->
          <!--                </span>-->
          <!--                <span v-else />-->
          <!--              </div>-->
          <!--              <div class="btn">-->
          <!--                <el-button size="small" type="primary" plain @click="locking(details)">设置</el-button>-->
          <!--              </div>-->
          <!--            </div>-->
          <!--          </div>-->
          <div class="item">
            <div class="info">
              <span class="label">用户名：</span>
              <span class="text">
                {{ details.username ? details.username : '-' }}
              </span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="locking(details)">{{ details.member_status == 1 ? "锁定" : "解锁" }}</el-button>
              <el-button size="small" type="primary" plain @click="handleEdit('password')">改密</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">会员手机：</span>
              <span class="text">{{ details.member_mobile ? details.member_mobile : '-' }}</span>
              <div class="dial" title="拨打会员联系号码">
                <div class="disal_img" @click="clickDial(details.member_mobile, details.fullname )" />
              </div>
              <span class="smalltip">
                <i class="el-icon-info" />
                会员登录手机号
              </span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('member_mobile')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">简历手机：</span>
              <span class="text">{{ details.resume_mobile ? details.resume_mobile : '-' }}</span>
              <div class="dial" title="拨打简历联系号码">
                <div class="disal_img" @click="clickDial(details.resume_mobile, details.fullname )" />
              </div>
              <span class="smalltip">
                <i class="el-icon-info" />
                简历资料手机号
              </span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('resume_mobile')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">公开状态：</span>
              <span v-if="details.is_display == 1" class="text">开启</span>
              <span v-if="details.is_display == 0" class="text">关闭</span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('is_display')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">简历等级：</span>
              <span v-if="details.high_quality == 0" class="text">普通简历</span>
              <span v-if="details.high_quality == 1" class="text">优质简历</span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('high_quality')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">绑定邮箱：</span>
              <span class="text">{{ details.email ? details.email : '-' }}</span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('email')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">审核状态：</span>
              <span v-if="details.audit == 0" class="text">待审核</span>
              <span v-if="details.audit == 1" class="text">已通过</span>
              <span v-if="details.audit == 2" class="text">已拒绝</span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('audit')">审核</el-button>
            </div>
          </div>
          <div class="styles">
            <div class="item" style="border: none">
              <div class="info">
                <span class="label">简历作品：</span>
              </div>
              <div class="btn">
                <el-button v-if="details.resume_img_arr && details.resume_img_arr == ''" disabled size="small" type="primary" plain>审核</el-button>
                <el-button v-else size="small" type="primary" plain @click="handleEdit('examine')">审核</el-button>
                <el-button v-if="details.resume_img_arr && details.resume_img_arr == ''" disabled size="small" type="danger" plain>删除</el-button>
                <el-button v-else size="small" type="danger" plain @click="delResumeImg()">删除</el-button>
              </div>
            </div>
            <div :class="details.resume_img_arr != '' ? 'item_img' : 'item_img_empty'">
              <div v-for="item in details.resume_img_arr" class="company_img">
                <el-image
                  style="width: 100px; height: 100px"
                  :src="item.img_src"
                  :preview-src-list="[item.img_src]"
                />
                <div v-if="item.audit == 1" class="state" style="color: #67c23a;">审核通过</div>
                <div v-if="item.audit == 0" class="state" style="color: #e6a23c;">待审核</div>
                <div v-if="item.audit == 2" class="state" style="color: #f56c6c;">审核未通过</div>
                <div class="check" @change="checkClick(item.id)">
                  <input type="checkbox">
                </div>
              </div>
              <div v-if="details.resume_img_arr == ''" class="empty_texts">
                <img src="../../../../../assets/images/company/crm/zanwei.png" alt="">
                <div>暂未上传简历作品</div>
              </div>
            </div>
          </div>
<!--          <div class="item">-->
<!--            <div class="info">-->
<!--              <span class="label">客服评价：</span>-->
<!--              <span class="text">{{ details.comment ? details.comment : '-' }}</span>-->
<!--            </div>-->
<!--            <div class="btn">-->
<!--              <el-button size="small" type="primary" plain @click="handleEdit('comment')">填写</el-button>-->
<!--            </div>-->
<!--          </div>-->
          <div class="item">
            <div class="info" style="padding: 15px 0px 15px 6px; ">
              <span class="label" style="width: 108px;">客服评价：</span>
            </div>
            <div class="text overflow" style="padding:10px 0px;">
              {{ details.comment ? details.comment : '-' }}
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('comment')">填写</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info" style="padding: 15px 0px 15px 6px; ">
              <span class="label" style="width: 108px;">备注信息：</span>
            </div>
            <div class="text overflow" style="padding:10px 0px;">
              {{ details.remark ? details.remark : '-' }}
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('remark')">修改</el-button>
            </div>
          </div>
<!--          <div class="item">-->
<!--            <div class="info">-->
<!--              <span class="label">备注信息：</span>-->
<!--              <span class="text">{{ details.remark ? details.remark : '-' }}</span>-->
<!--            </div>-->
<!--            <div class="btn">-->
<!--              <el-button size="small" type="primary" plain @click="handleEdit('remark')">填写备注</el-button>-->
<!--            </div>-->
<!--          </div>-->
        </div>
      </el-col>
      <el-col :span="12">
        <div class="package_information">
          <div class="titles">
            求职信息情况
            <el-date-picker
              v-model="jobWanted"
              class="package_date"
              type="daterange"
              format="yyyy-MM-dd"
              value-format="yyyy-MM-dd"
              range-separator="-"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              :picker-options="pickerOptions"
              @change="choicJobWanted"
            />
          </div>
          <div class="content"><span class="content_title">浏览职位：</span>{{ details.view_job ? details.view_job : '0' }}次</div>
          <div class="content"><span class="content_title">主动投递：</span>{{ details.delivery_num ? details.delivery_num : '0' }}次</div>
          <div class="content"><span class="content_title">主动刷新：</span>{{ details.refresh ? details.refresh : '0' }}次</div>
          <div class="content"><span class="content_title">被查看：</span>{{ details.viewed ? details.viewed : '0' }}次</div>
          <div class="content"><span class="content_title">被下载：</span>{{ details.downloaded ? details.downloaded : '0' }}次</div>
          <div class="content"><span class="content_title">收到面邀：</span>{{ details.invitation ? details.invitation : '0' }}次</div>
        </div>
        <div class="package_information">
          <div class="titles">会员账号情况</div>
          <div class="content" style="width: 100%"><span class="content_title">最近登录：</span>
            <span v-if="details.last_login_time > 0 ">{{ details.last_login_time | timeFilter }}</span>
            <span v-else>-</span>
          </div>
          <div class="content" style="width: 100%"><span class="content_title">注册时间：</span>
            <span v-if="details.reg_time !=null ">{{ details.reg_time | timeFilter }}</span>
            <span v-else>-</span>
            (注册于{{ details.platform_cn }})</div>
          <div class="content" style="width: 100%"><span class="content_title">最后跟进：</span>
            <span v-if="details.last_visit_time !=null ">{{ details.last_visit_time | timeFilter }}</span>
            <span v-else>-</span>
            (跟进记录：{{ details.follow_up_num }})
          </div>
        </div>
        <div class="package_information">
          <div class="titles">短视频求职</div>
          <div class="item_img" style="margin-left: 25px;">
            <img v-for="item in details.resume_video" :src="item.img_url" alt="" @click="videoPlay(item.video_src)">
            <div v-if="(details && details.resume_video.length <= 0)" class="empty_texts">
              <img src="../../../../../assets/images/company/crm/zanwei.png" alt="">
              <div>暂未上传短视频</div>
            </div>
          </div>
        </div>
      </el-col>
    </el-row>
    <el-dialog
      title="提示"
      append-to-body
      :visible.sync="editDialog"
      width="30%"
    >
      <el-form ref="form" :model="form" label-width="80px" style="text-align: center">
        <div v-if="edit_type == 'password'" class="dia_box">
          修改密码：
          <el-input v-model="form.password" style="width: 45%" show-password />
        </div>
        <div v-if="edit_type == 'member_mobile'" class="dia_box">
          修改会员手机号：
          <el-input v-model="form.member_mobile" style="width: 45%" />
        </div>
        <div v-if="edit_type == 'resume_mobile'" class="dia_box">
          修改简历手机号：
          <el-input v-model="form.resume_mobile" style="width: 45%" />
        </div>
        <div v-if="edit_type == 'is_display'" class="dia_box">
          公开状态：
          <el-radio v-model="form.is_display" label="0">关闭</el-radio>
          <el-radio v-model="form.is_display" label="1">开启</el-radio>
        </div>
        <div v-if="edit_type == 'high_quality'" class="dia_box">
          显示状态：
          <el-radio v-model="form.high_quality" label="0">普通简历</el-radio>
          <el-radio v-model="form.high_quality" label="1">优质简历</el-radio>
        </div>
        <div v-if="edit_type == 'email'" class="dia_box">
          修改邮箱：
          <el-input v-model="form.email" style="width: 45%" />
        </div>
        <div v-if="edit_type == 'remark'" class="dia_box">
          <div class="reason">修改备注：</div>
          <el-input v-model="form.remark" maxlength="100" show-word-limit type="textarea" rows="3" style="width: 300px;" />
        </div>
        <div v-if="edit_type == 'comment'" class="dia_box">
          <div class="reason">客服评价：</div>
          <el-input v-model="form.comment" maxlength="100" show-word-limit type="textarea" rows="3" style="width: 300px;" />
        </div>
        <div v-if="edit_type == 'audit'" class="dia_box">
          审核状态：
          <el-radio-group v-model="form.examine">
            <el-radio
              v-for="(item) in options_audit"
              :key="item.id"
              :label="item.id"
            >
              {{ item.name }}
            </el-radio>
          </el-radio-group>
          <div v-if="form.examine == 2" style="margin-top: 20px;">
            <div class="reason">原因：</div>
            <el-input v-model="setAuditReason" type="textarea" rows="3" style="width: 300px;" />
          </div>
        </div>
        <div v-if="edit_type == 'examine'" class="dia_box">
          审核状态：
          <el-radio-group v-model="form.examine">
            <el-radio
              v-for="(item) in options_audit"
              :key="item.id"
              :label="item.id"
            >
              {{ item.name }}
            </el-radio>
          </el-radio-group>
        </div>
      </el-form>

      <span slot="footer" class="dialog-footer">
        <el-button @click="editDialog = false">取 消</el-button>
        <el-button type="primary" @click="submit(edit_type)">确 定</el-button>
      </span>
    </el-dialog>
    <el-dialog
      title="短视频招聘"
      append-to-body
      :visible.sync="dialogVisible"
      width="30%"
    >
      <div class="video">
        <video style="width: 100%;height:100%" autoplay controls :src="video_url" />
      </div>
    </el-dialog>
    <el-dialog
      class="call"
      append-to-body
      :visible.sync="callDialogVisible"
      width="30%"
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
      append-to-body
      class="meet"
      :visible.sync="meetDialogVisible"
      width="30%"
      :before-close="meetHandleClose"
    >
      <div class="box-content">
        <div id="animation" class="img">
          <!--            <img src="../../../../assets/images/outbound/meet.png" alt="">-->
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
import { resumeDetailsList, updateResume } from '@/api/resume_urm'
import { parseTime } from '@/utils'
import { classify } from '@/api/company_crm'
import { resumeImgDel } from '@/api/resume'
import { memberLock } from '@/api/member'
import { outboundCall } from '@/api/outbound'

export default {
  name: 'MemberInformation',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: ['id', 'uid'],
  data(){
    return {
      pickerOptions: {
        shortcuts: [
          {
            text: '今天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '昨天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              end.setTime(start.getTime() - 3600 * 1000 * 24)
              start.setTime(start.getTime() - 3600 * 1000 * 24)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '本周',
            onClick(picker) {
              const now = new Date()
              var nowYear = now.getYear()
              const end = new Date()
              nowYear += nowYear < 2000 ? 1900 : 0
              const start = new Date(
                nowYear,
                now.getMonth(),
                now.getDate() - now.getDay() + 1
              )
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '本月',
            onClick(picker) {
              const now = new Date()
              var nowYear = now.getYear()
              const end = new Date()
              nowYear += nowYear < 2000 ? 1900 : 0
              const start = new Date(nowYear, now.getMonth(), 1)
              picker.$emit('pick', [start, end])
            }
          }]
      },
      details: '',
      dialogVisible: false,
      video_url: '',
      editDialog: false,
      edit_type: '',
      form: {
        password: '',
        member_mobile: '',
        resume_mobile: '',
        high_quality: '',
        email: '',
        remark: '',
        comment: '',
        examine: 1,
        resume_img: [],
        is_display: ''
      },
      options_audit: [],
      setAuditReason: '',
      dialPhone: '',
      dialName: '',
      meetDialogVisible: false,
      callDialogVisible: false,
      jobWanted: [],
      loading: true
    }
  },
  methods: {
    init(){
      this.classify()
      this.resumeDetails()
    },
    choicJobWanted(){
      this.resumeDetails(this.jobWanted)
    },
    callHandleClose(){
      this.callDialogVisible = false
    },
    meetHandleClose(){
      this.meetDialogVisible = false
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
    submit(type){
      var data = ''
      if (type == 'password'){
        if (this.form.password == ''){
          this.$message.error('密码不能为空')
          return false
        }
        data = { 'password': this.form.password, 'uid': this.uid }
      }
      if (type == 'member_mobile'){
        if (this.form.member_mobile == ''){
          this.$message.error('手机号码不能为空')
          return false
        }
        data = { 'member_mobile': this.form.member_mobile, 'uid': this.uid }
      }
      if (type == 'email'){
        data = { 'email': this.form.email, 'uid': this.uid }
      }
      if (type == 'resume_mobile'){
        if (this.form.resume_mobile == ''){
          this.$message.error('手机号码不能为空')
          return false
        }
        data = { 'resume_mobile': this.form.resume_mobile, 'uid': this.uid }
      }
      if (type == 'high_quality'){
        data = { 'high_quality': this.form.high_quality, 'uid': this.uid }
      }
      if (type == 'remark'){
        data = { 'remark': this.form.remark, 'uid': this.uid }
      }
      if (type == 'comment'){
        data = { 'comment': this.form.comment, 'uid': this.uid }
      }
      if (type == 'audit'){
        data = { 'audit': this.form.examine, 'reason': this.setAuditReason, 'uid': this.uid }
      }
      if (type == 'examine'){
        data = { 'examine': this.form.examine, 'resume_img': this.form.resume_img, 'uid': this.uid }
      }
      if (type == 'is_display'){
        data = { 'is_display': this.form.is_display, 'uid': this.uid }
      }
      updateResume(data)
        .then(res => {
          this.$message.success(res.message)
          this.resumeDetails()
          this.editDialog = false
        }).catch(() => {

        })
    },
    locking(row) {
      var that = this
      let tip = ''
      let status = ''
      if (row.member_status == 1) {
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
            this.resumeDetails()
            return true
          })
        })
        .catch(() => { })
    },
    delResumeImg(){
      if (this.form.resume_img.length <= 0) {
        this.$message.error('请选择删除的作品')
        return false
      }
      this.$confirm('此操作将永久删除选中的信息, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          resumeImgDel({ 'id': this.form.resume_img }).then(response => {
            this.$message.success(response.message)
            this.resumeDetails()
            return true
          }).catch(() => { })
        })
        .catch(() => { })
    },
    classify(){
      classify({ 'type': 'resumeAudit' }).then(res => {
        this.options_audit = res.data
      }).catch(() => {
      })
    },
    videoPlay(url){
      this.video_url = url
      this.dialogVisible = true
    },
    resumeDetails(jobWanted){
      var start = ''
      var end = ''
      if (jobWanted){
        start = jobWanted[0]
        end = jobWanted[1]
      }
      resumeDetailsList({ 'id': this.id, 'start': start, 'end': end })
        .then(res => {
          this.details = res.data
          for (var i = 0; i <= this.details.resume_video.length - 1; i++){
            this.details.resume_video[i].img_url = this.details.resume_video[i].video_src + '?vframe/jpg/offset/0'
          }
          this.loading = false
          this.form.is_display = res.data.is_display.toString()
          this.form.member_mobile = res.data.member_mobile
          this.form.resume_mobile = res.data.resume_mobile
          this.form.high_quality = res.data.high_quality.toString()
          this.form.email = res.data.email
          this.form.remark = res.data.remark
          this.form.comment = res.data.comment
          this.form.examine = res.data.examine.toString()

        }).catch(() => {

        })
    },
    checkClick(id){
      if (this.form.resume_img.indexOf(id) == -1){
        this.form.resume_img.push(id)
      } else {
        this.form.resume_img.splice(this.form.resume_img.indexOf(id), 1)
      }
    },
    handleEdit(type){
      if (type == 'examine'){
        if (this.form.resume_img.length <= 0) {
          this.$message.error('请选择审核的作品')
          return false
        }
      }
      this.edit_type = type
      this.editDialog = true
    }
  }
}
</script>

<style lang="scss" scoped>
.package_date{
  width: 230px;
  margin-top: 10px;
  margin-bottom: 10px;
  height: 26px;
  float:right;
  ::v-deep .el-range-input{
    font-size: 12px;
  }
  ::v-deep .el-range__icon{
    line-height: 20px;
  }
  ::v-deep .el-range-separator{
    line-height: 18px;
  }
  ::v-deep .el-range__close-icon{
    line-height: 20px;
  }
}
.smalltip i {
  margin-right: 0px;
}
.smalltip{
  color:#c9c9c9;
  font-size: 12px;
}
.dial{
  position: relative;
  top:2px;
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
.call {
  ::v-deep .el-dialog__body {
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }

  ::v-deep .el-dialog {
    border-radius: 16px;
  }
}
.overflow{
  display: inline-block;
  width: 68%;
  font-size: 14px;
  word-break: break-all;
  word-wrap: break-word;
}
.reason{
  display: inline-block;
  min-height: 60px;
}
.company_img{
  width: 100px;
  margin-right: 15px;
  height: 125px;
  position: relative;
  display: inline-block;
  .state{
    width: 100px;
    position: absolute;
    height:25px;
    font-size: 14px;
    text-align: center;
    line-height: 25px;
    bottom:15px;
    color: white;
    background: rgba(0,0,0,0.6);
  }
  .icon{
    position: absolute;
    top: 9px;
    right: -1px;
  }
  .check{
    position: absolute;
    top: 2px;
    left: 3px;
  }
}
.package_information{
  margin: 10px 20px 0px 20px;
  background: #fafcff;
  font-size: 14px;
  padding:15px;
  border-radius: 5px;
  .titles{
    margin-bottom: 16px;
    font-size: 16px;
    font-weight: 600;
    line-height: 50px;
  }
  .content{
    width: 27%;
    margin-left: 20px;
    display: inline-block;
    margin-bottom: 15px;
  }
}
.clientInfo_wrapper{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding: 10px 0;
  .title{
    font-size: 14px;
    font-weight: 600;
    line-height: 50px;
    padding: 0 6px;
    border-bottom: 1px dotted #ebeef5;
  }
  .tag{
    margin-top: 5px;
    background: #ecf5ff;
    border-color: #b3d8ff;
    display: inline-block;
    padding: 0 10px;
    height: 24px;
    border-radius: 2px;
    text-align: center;
    line-height: 24px;
    font-size: 12px;
    font-weight: 400;
    margin-right: 10px;
  }
  .clientInfo_box{
    padding:  0 30px;
  }
  .systeminfo_box{
    position: relative;
    padding:  0 30px;
  }
  .items{
    border-bottom: 1px solid #ebeef5;
  }
  .item{
    border-bottom: 1px dotted #ebeef5;
    display: flex;
    justify-content: space-between;
    .img{
      width: 250px;
      height:250px;
    }
    .info_width{
      width: 85%;
    }
    .info{
      display: flex;
      line-height: 20px;
      padding: 15px 6px;
      .auth{
        font-size: 14px;
        font-weight: 500;
        .icon_1 {
          color: #6EACE2;
        }
      }
      .clientinfo_type_1{
        color: #fe6135;
      }
      .label{
        width: 110px;
        color: #999;
        font-size: 14px;
        display: flex;
        align-items: center;
      }
      .text_width{
        width: 85%;
        .el-input{
          width: 100%;
        }
        .el-input__inner{
          padding: 0;
        }
      }
      .text{
        color: #000;
        font-size: 14px;
        .el-input{
          height: 28px;
        }
      }
    }
    .btn{
      display: flex;
      align-items: center;
    }
  }
}
.empty_texts{
  margin-bottom: 20px;
  height:100%;
  text-align: center;
  font-size: 14px;
  color:#d1cfcf;
  line-height: 30px;
}
.item_img_empty{
  margin-bottom: 20px;
  img{
    width: 120px;
    height:120px;
    margin-right: 15px;
    margin-top: 10px;
  }
}
.item_img{
  margin-bottom: 20px;
  margin-left: 116px;
  img{
    width: 120px;
    height:120px;
    margin-right: 15px;
    margin-top: 10px;
  }
}
.styles{
  border-bottom: 1px dotted #ebeef5;
}
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
.item{
  border-bottom: 1px dotted #ebeef5;
  display: flex;
  justify-content: space-between;
  .img{
    width: 250px;
    height:250px;
  }
  .info_width{
    width: 85%;
  }
  .info{
    display: flex;
    line-height: 20px;
    padding: 15px 6px;
    .auth{
      font-size: 14px;
      font-weight: 500;
      .icon_1 {
        color: #6EACE2;
      }
    }
    .clientinfo_type_1{
      color: #fe6135;
    }
    .label{
      width: 110px;
      color: #999;
      font-size: 14px;
      display: flex;
      align-items: center;
    }
    .text_width{
      width: 85%;
      .el-input{
        width: 100%;
      }
      .el-input__inner{
        padding: 0;
      }
    }
    .text{
      color: #000;
      font-size: 14px;
      .el-input{
        height: 28px;
      }
    }
  }
  .btn{
    display: flex;
    align-items: center;
  }
}
.clientInfo_box{
  padding:  0 30px;
}
</style>
