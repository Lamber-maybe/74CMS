<template>
  <div v-loading="loading">
    <div class="popup_box">
      <!-- 基本信息 -->
      <div class="base">
        <div class="logo">
          <img :src="details.photo_img_src" alt="">
        </div>
        <div class="info">
          <div class="title">
            <div class="name">
              {{ details.fullname }}
            </div>

            <div class="user">
              (
              <span>会员ID:
                {{ details.uid }}；简历ID:{{ details.id }};
              </span>
              <span class="logoin" @click="funManagement(details)">  一键登录个人会员中心</span>
              ;
              <span class="logoin" @click="funPoster(details.id)">生成海报</span>
              )
            </div>
          </div>
          <div class="details">
            <el-row class="line">
              <el-col :span="12">
                <label class="line_label">基础资料：</label>
                <span class="text">
                  {{ details.age }} | {{ details.education_cn }} | {{ details.experience_cn }}
                </span>
              </el-col>
              <el-col :span="10">
                <label class="line_label">账号积分：</label>
                <span class="text">
                  {{ details.points ? details.points : 0 }}
                </span>
              </el-col>
            </el-row>
            <el-row class="line">
              <el-col :span="12">
                <label class="line_label">完整度：</label>
                <div style="display: inline-block;width: 180px;" class="complete_percent">
                  <div v-if="details.complete_percent < 45" style="color:#f51e1e">
                    <el-progress v-if="details.complete_percent < 45" color="#f51e1e" :percentage="details.complete_percent" />
                  </div>
                  <div v-if="details.complete_percent < 60 && details.complete_percent >= 45" style="color:#fb3c11">
                    <el-progress color="#fb3c11" :percentage="details.complete_percent" />
                  </div>
                  <div v-if="details.complete_percent < 80 && details.complete_percent >= 60" style="color:#1db75a">
                    <el-progress color="#1db75a" :percentage="details.complete_percent" />
                  </div>
                  <div v-if="details.complete_percent <= 100 && details.complete_percent >= 80" style="color:#409eff">
                    <el-progress color="#409eff" :percentage="details.complete_percent" />
                  </div>
                </div>
              </el-col>
              <el-col :span="12">
                <label class="line_label">刷新时间：</label>
                <span v-if="details.refreshtime" class="text">
                  {{ details.refreshtime | timeFilter }}
                </span>
                <span v-else>
                  -
                </span>
                <span class="text" style="margin-left: 20px;color: #0078ff;cursor:pointer;" @click="funRefresh(details.uid)">刷新简历</span>
              </el-col>
            </el-row>
            <el-row class="line">
              <el-col :span="12">
                <label class="line_label">服务号绑定状态：</label>
                <div v-if="details.weixin_bind == 0" class="text text_color" @click="binding">
                  <div class="bind_icon">
                    <img src="@/assets/images/wx_icon.png" alt="">
                  </div>
                  邀请绑定
                </div>
                <div v-else class="text text_color">
                  <div class="bind_icon">
                    <img src="@/assets/images/wx_icon.png" alt="">
                  </div>
                  已绑定
                </div>
                <div v-if="details.weixin_bind == 0" class="text text_color" style="margin-left: 10px;" @click="refresh">
                  刷新结果
                </div>
              </el-col>
            </el-row>
          </div>
        </div>
      </div>
      <!-- 切换 -->
      <div class="tab_box">
        <el-tabs v-model="activeName" @tab-click="handleClick">
          <el-tab-pane label="跟进记录" name="first">
            <followUpRecord :uid="details.uid" @resumeDetails="resumeDetails" />
          </el-tab-pane>
          <el-tab-pane label="简历详情" name="resumeDetails">
            <resumeDetails v-if="activeName == 'resumeDetails'" :id="details.id" @resumeDetails="resumeDetails" />
          </el-tab-pane>
          <el-tab-pane label="会员信息" name="memberInformation">
            <memberInformation :id="details.id" ref="memberInformation" :uid="details.uid" />
          </el-tab-pane>
          <el-tab-pane label="求职动态" name="jobSearchTrends">
            <dynamic v-if="activeName == 'jobSearchTrends'" :uid="details.uid" />
          </el-tab-pane>
          <el-tab-pane label="会员积分" name="integral">
            <integral v-if="activeName == 'integral'" :uid="details.uid" />
          </el-tab-pane>
          <el-tab-pane label="操作日志" name="journal">
            <log v-if="activeName == 'journal'" :uid="details.uid" />
          </el-tab-pane>
        </el-tabs>
      </div>
      <el-dialog
        title="邀请绑定"
        append-to-body
        center
        :visible.sync="dialogBinding"
        width="20%"
        :before-close="handleClose"
      >
        <div class="bind">
          <div><img :src="bind_img" alt=""></div>

          <div class="title">可鼠标右击图片使用另存为保存图片</div>
        </div>
      </el-dialog>
    </div>
    <Poster
      v-if="showPoster"
      :poster-id="posterId"
      :poster-type="posterType"
      @closeDialog="showPoster = false"
    />
  </div>
</template>

<script>
import followUpRecord from '@/views/user/resume/urm/components/FollowUpRecord'
import integral from '@/views/user/resume/urm/components/Integral'
import log from '@/views/user/resume/urm/components/Log'
import dynamic from '@/views/user/resume/urm/components/Dynamic'
import resumeDetails from '@/views/user/resume/urm/components/ResumeDetails'
import memberInformation from '@/views/user/resume/urm/components/MemberInformation'
import { resumeDetailsList } from '@/api/resume_urm'
import { parseTime, setMemberLogin } from '@/utils'
import { management } from '@/api/member'
import { getisWeixinBind, qrcode } from '@/api/company_crm'
import { outboundCall } from '@/api/outbound'
import { resumeRefresh } from '@/api/resume'
import Poster from '@/components/Poster'
export default {
  name: 'UrmShow',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    followUpRecord,
    integral,
    log,
    dynamic,
    resumeDetails,
    memberInformation,
    Poster
  },
  props: {
    rowId: {
      default: ''
    }
  },
  data(){
    return {
      details: [],
      activeName: 'first',
      loading: true,
      dialogBinding: false,
      bind_img: '',
      meetDialogVisible: false,
      callDialogVisible: false,
      showPoster: false,
      posterId: 0,
      posterType: ''
    }
  },
  created () {
    this.resumeDetails()
  },
  methods: {
    handleClick(e){
      if (this.activeName == 'memberInformation'){
        this.$refs[this.activeName].init()
      }
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
    binding(){
      qrcode({ 'uid': this.details.uid })
        .then(res => {
          this.bind_img = res.data
          this.dialogBinding = true
        }).catch(() => {

        })
    },
    handleClose() {
      this.dialogBinding = false
    },
    refresh(){
      getisWeixinBind({ 'uid': this.details.uid })
        .then(res => {
          this.resumeDetails()
          this.$message.success(res.message)
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
    conversion(){
      this.$router.push('/user/company/add?id=' + this.details.id)
    },
    resumeDetails(){
      resumeDetailsList({ 'id': this.rowId })
        .then(res => {
          this.details = res.data
          this.loading = false
        }).catch(() => {

        })
    },
    funRefresh(id){
      var that = this
      const param = {
        uid: id
      }
      resumeRefresh(param).then(response => {
        that.$message.success(response.message)
        this.resumeDetails()
      }).catch(() => {
        this.resumeDetails()
      })
    },
    funPoster(id) {
      this.showPoster = true
      this.posterId = id
      this.posterType = 'resume'
    }
  }
}
</script>

<style lang="scss" scoped>
.complete_percent{
  ::v-deep .el-progress__text{
    color:unset;
  }
}
.bind{
  padding-bottom: 20px;
  text-align: center;
  img{
    width: 256px;
    height: 256px;
  }
  .title{
    margin-top: 20px;
    font-size: 14px;
    color: rgb(156, 156, 156);
  }
}
.bind_icon{
  display: inline-block;
  float: left;
  margin-right: 5px;

}
::v-deep .el-tabs__nav-wrap{
  margin-bottom: 0px;
}
::v-deep .el-tabs__header{
  margin: 0px;
}
::v-deep .el-tabs__nav-scroll{
  line-height: 50px;
  padding-left: 30px;
}
::v-deep .el-tabs__nav-wrap{
  background: #fcfcfc;
  border-top: 1px solid #ebeef5;
  border-bottom: 1px solid #ebeef5;
}
::v-deep .el-tabs__nav-wrap::after {
  background-color:transparent
}
//弹窗
.popup_box {
  height: 100%;
  // 基本信息
  .base {
    padding: 20px 30px;
    display: flex;
    .logo {
      width: 118px;
      height: 118px;
      margin-right: 20px;
      background-color: #ebeef5;
      border: 1px solid #ebeef5;
      img {
        border-radius: 8px;
        width: 100%;
        height: 100%;
      }
    }
    .info {
      flex: 1;
      .title {
        padding: 5px 0 18px;
        display: flex;
        .name {
          font-size: 20px;
          height: 20px;
          color: #303033;
          font-weight: 600;
          margin-right: 10px;
        }
        .user {
          padding-top: 6px;
          font-size: 14px;
          font-weight: 400;
          line-height: 14px;
          color: #777;
          .logoin {
            margin-left: 6px;
            color: #0078ff;
            cursor: pointer;
          }
        }
      }
      .details {
        max-width: 100%;
        .line {
          margin-bottom: 8px;
          .line_label {
            color: #777;
            font-size: 14px;
            line-height: 14px;
            font-weight: normal;
          }
          .text {
            display: inline-block;
            font-size: 14px;
            line-height: 14px;
          }
          .text_color{
            color: #0078ff;
            cursor:pointer ;
          }
        }
      }
    }
    .details{
      .invite_text{
        color: #1787fb;
      }
    }
  }
  .tab_box{
    position: relative;
  }
  .qcc_box{
    width: 63px;
    height: 20px;
    position: absolute;
    right: 30px;
    z-index:30;
    cursor: pointer;
    img{
      width: 100%;
      height: 100%;
    }
  }
  .job_sum{
    color: #f56c6c;
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
}
// 邀请弹窗
::v-deep .el-dialog__body{
  padding: 1px 0;
}
.code_wrapper{
  text-align: center;
  .code_img{
    width: 256px;
    height: 256px;
  }
  .code_text{
    font-size: 12px;
    color: #979797;
  }
  .tip{
    font-size: 14px;
    font-weight: 300;
    color: #f56c6c;
    background-color: #fef0f0;
    margin: 20px auto;
    padding: 10px 20px;
    text-align: center;
  }
}
</style>
