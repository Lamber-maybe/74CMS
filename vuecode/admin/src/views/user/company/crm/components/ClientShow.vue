<template>
  <div>
    <div class="popup_box">
      <!-- 基本信息 -->
      <div class="base">
        <div class="logo">
          <img :src="details.logo_url" alt="">
        </div>
        <div class="info">
          <div class="title">
            <div class="name">
              {{ details.companyname ? details.companyname : '未完善企业资料' }}
            </div>

            <div class="user">
              (
              <span>企业ID：
                {{ details.id }}；
              </span>
              <span class="logoin" @click="funManagement(details)">一键登录企业会员中心</span>
              )
            </div>
            <div class="qcc_box">
              <a :href="searchUrl" target="_blank" @click="handleSearchCom">
                <img src="../../../../../assets/images/crm/aiqicha.png" alt="">
              </a>
            </div>
          </div>
          <div class="details">
            <el-row class="line">
              <el-col :span="8">
                <label class="line_label">所属销售：</label>
                <span class="text">
                  {{ details.admin_name ? details.admin_name : '-' }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">会员套餐：</label>
                <span class="text">
                  {{ details.setmeal_name ? details.setmeal_name : '-' }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">跟进记录：</label>
                <span class="text">
                  {{ details.follow_count }}
                </span>
              </el-col>
            </el-row>
            <el-row class="line">
              <el-col :span="8">
                <label class="line_label">客户等级：</label>
                <span class="text">
                  {{ details.crm_life_cycle_name ? details.crm_life_cycle_name : '-' }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">到期时间：</label>
                <span class="text">
<!--                  【bug】套餐到期时间要写到期时间，不能写剩余天数
                      zch 2022.9.21
                      【旧】
                      {{ details.setmeal_deadline_day ? details.setmeal_deadline_day : '-' }}
                     【新】
                     {{ details.deadline ? details.deadline : '-' }}
                  -->
                  {{ details.deadline ? details.deadline : '-' }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">最后跟进：</label>
                <span class="text">
                  <span v-if="details.last_visit_time">
                    {{ details.last_visit_time | timeFilter }}
                  </span>
                  <span v-else>
                    -
                  </span>
                </span>
              </el-col>
            </el-row>
            <el-row class="line">
              <el-col :span="8">
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
        <el-tabs v-model="activeName">
          <el-tab-pane label="跟进记录" name="first">
            <followUpRecord :clue_id="details.clue_id" :uid="details.uid" @companyDetails="companyDetails" />
          </el-tab-pane>
          <el-tab-pane label="客户详情" name="details">
            <Clientinfo v-if="activeName == 'details'" :details="details" :uid="details.uid" @companyDetails="companyDetails" @integral="integral" @resume="resume" />
          </el-tab-pane>
          <el-tab-pane label="企业资料" name="enterpriseInformation">
            <enterpriseInformation v-if="activeName == 'enterpriseInformation'" :details="details" :uid="details.uid" @companyDetails="companyDetails" />
          </el-tab-pane>
          <el-tab-pane label="联系人" name="contacts">
            <Contacts v-if="activeName == 'contacts'" :uid="details.uid" :comid="rowId" @companyDetails="companyDetails" />
          </el-tab-pane>
          <el-tab-pane :label="jobname" name="recruit">
            <Job :comid="rowId" v-if="activeName == 'recruit'" />
          </el-tab-pane>
          <el-tab-pane label="招聘动态" name="dynamic">
            <dynamic v-if="activeName == 'dynamic'"  :comid="rowId" :uid="details.uid" />
          </el-tab-pane>
          <el-tab-pane label="订单记录" name="record">
            <order v-if="activeName == 'record'" :uid="details.uid" />
          </el-tab-pane>
          <el-tab-pane label="积分记录" name="integral">
            <integral v-if="activeName == 'integral'" :prop_type="integralType" :uid="details.uid" />
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
  </div>
</template>

<script>
import followUpRecord from '@/views/user/company/crm/components/FollowUpRecord'
import Clientinfo from '@/views/user/company/crm/components/Clientinfo'
import Contacts from '@/views/user/company/crm/components/Contacts'
import Job from '@/views/user/company/crm/components/Job'
import order from '@/views/user/company/crm/components/order'
import dynamic from '@/views/user/company/crm/components/Dynamic'
import integral from '@/views/user/company/crm/components/Integral'
import log from '@/views/user/company/crm/components/Log'
import EnterpriseInformation from '@/views/user/company/crm/components/EnterpriseInformation'
import { companyDetails, getisWeixinBind, qrcode } from '@/api/company_crm'
import { management } from '@/api/member'
import { parseTime, setMemberLogin } from '@/utils'

export default {
  name: 'ClientShow',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    followUpRecord,
    Clientinfo,
    Contacts,
    Job,
    dynamic,
    order,
    integral,
    log,
    EnterpriseInformation
  },
  props: {
    rowId: {
      default: ''
    }
  },
  data () {
    return {
      integralType: 'integral',
      jobname: '',
      bind_img: '',
      activeName: 'first',
      details: '',
      searchUrl: '',
      dialogBinding: false
    }
  },
  created () {
    this.companyDetails()
  },
  methods: {
    integral(){
      this.activeName = 'integral'
      this.integralType = 'integral'
    },
    resume(){
      this.activeName = 'integral'
      this.integralType = 'resume'
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
    handleClose() {
      this.dialogBinding = false
    },
    binding(){
      qrcode({ 'uid': this.details.uid })
        .then(res => {
          this.bind_img = res.data
          this.dialogBinding = true
        }).catch(() => {

        })
    },
    companyDetails(){
      companyDetails({ 'company_id': this.rowId })
        .then(res => {
          this.details = res.data
          this.jobname = '在招职位(' + res.data.job_count + ')'
        }).catch(() => {

        })
    },
    handleSearchCom () {
      this.searchUrl = 'https://aiqicha.baidu.com/s?q=' + this.details.companyname
    },
    refresh(){
      getisWeixinBind({ 'uid': this.details.uid })
        .then(res => {
          this.companyDetails()
          this.$message.success(res.message)
        }).catch(() => {

        })
    }
  }
}
</script>

<style lang="scss" scoped>
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
      border-radius: 3px;
      img {
        width: 100%;
        height: 100%;
        border-radius: 3px;
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
