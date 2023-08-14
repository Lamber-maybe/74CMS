<template>
  <div v-loading="loading" class="popup_contacts">
    <el-row :gutter="20">
      <el-col v-for="(item, index) in linkmanList" :key="index" :span="6">
        <div class="contacts_item">
          <div class="content">
            <div class="top">
              <div class="title">
                <span class="name">
                  <span v-if="item.type=='company' || item.type=='crm_contact'">
                    <span v-if="item.contact">{{ item.contact }}</span>
                    <span v-else>-</span>
                  </span>
                  <span v-if="item.type=='member'">会员账号</span>
                  <span v-if="item.sex" class="sex">
                    <i v-if="item.sex == 1" class="el-icon-male" />
                    <i v-if="item.sex == 2" class="el-icon-female" />
                  </span>
                </span>
                <!--                <span class="job_name">{{ item.contact }}</span>-->
                <span v-if="item.type=='company'" class="main">企业联系方式</span>
                <span v-if="item.type=='member'" class="main">会员联系方式</span>
              </div>
              <div class="phone">
                {{ item.mobile ? item.mobile : '-' }}
                <div class="dial" title="拨打联系人号码" v-if="item.mobile != '' && item.mobile != null">
                  <div class="disal_img" @click="clickDial(item.mobile, item.contact )" />
                </div>
              </div>
            </div>
            <div class="bottom">
              <div class="line">
                <span class="lable">座机：</span>
                <span>{{ item.telephone ? item.telephone : '-' }}</span>
              </div>
              <div class="line">
                <span class="lable">QQ：</span>
                <span>{{ parseInt(item.qq) ? item.qq : '-' }}</span>
              </div>
              <div class="line">
                <span class="lable">邮箱：</span>
                <span>{{ item.email ? item.email : '-' }}</span>
              </div>
            </div>
          </div>
          <div class="btns">
            <div />
            <div v-if="item.type!='company' && item.type!='member'">
              <el-button size="mini" type="primary" plain @click="editLinkman(item)">修改</el-button>
              <el-button size="mini" type="danger" plain @click="delContacts(item)">删除</el-button>
            </div>
          </div>
        </div>
      </el-col>

      <el-col v-if="linkmanList.length < 8" :span="6" @click.native="hadleContactsEdit('add')">
        <div class="add_contacts">
          <i class="add_icon el-icon-circle-plus" />
          <span class="text">添加联系人</span>
        </div>
      </el-col>
    </el-row>
    <el-dialog :title="dialogTitle" :visible.sync="isDialog" width="600px" :append-to-body="true">
      <div class="add_contacts_from">
        <el-form ref="parmas" :model="parmas" label-width="80px" :rules="rules">
          <el-form-item prop="contact">
            <span slot="label">
              <span>姓名:</span>
            </span>
            <el-input v-model="parmas.contact" placeholder="请输入联系人姓名" />
          </el-form-item>

          <el-form-item label="性别:">
            <el-radio v-model="parmas.sex" label="1">男</el-radio>
            <el-radio v-model="parmas.sex" label="2">女</el-radio>
          </el-form-item>

          <el-form-item>
            <span slot="label">
              <span>手机号:</span>
            </span>
            <el-input v-model="parmas.mobile" placeholder="请输入手机号" />
          </el-form-item>

          <el-form-item label="公司座机:">
            <el-input v-model="parmas.telephone" placeholder="请输入公司座机号" />
          </el-form-item>

          <el-form-item label="邮箱:">
            <el-input v-model="parmas.email" placeholder="请输入邮箱" />
          </el-form-item>

          <el-form-item label="QQ:">
            <el-input v-model="parmas.qq" placeholder="请输入QQ" />
          </el-form-item>

          <el-form-item>
            <div>
              <el-button type="primary" @click="addLinkman('parmas')">保存</el-button>
              <el-button v-if="type == 'add'" @click="resetLinkman">重置</el-button>
            </div>
          </el-form-item>
        </el-form>
      </div>

    </el-dialog>
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
import { addContact, companyContactList, delContact, editContact } from '@/api/company_crm'
import { validMobile } from '@/utils/validate'
import { outboundCall } from '@/api/outbound'

export default {
  name: 'Contacts',
  props: ['uid', 'comid'],
  data(){
    var validateContactMobile = (rule, value, callback) => {
      if (!validMobile(value)) {
        callback(new Error('请输入正确的手机号'))
      } else {
        callback()
      }
    }
    return {
      rules: {
        contact: [
          { required: true, message: '请输入联系人', trigger: 'blur' },
          { max: 6, message: '长度在 1 到 6 个汉字', trigger: 'blur' }
        ]
      },
      type: '',
      isDialog: false,
      dialogTitle: '',
      isDialogBTn: false,
      linkmanList: [],
      loading: false,
      submitLoading: false,
      // 请求参数
      parmas: {
        id: 0,
        comid: 0,
        uid: '',
        contact: '',
        sex: '1',
        mobile: '',
        telephone: '',
        email: '',
        qq: '',
        cid: ''
      },
      callDialogVisible: false,
      meetDialogVisible: false,
      dialPhone: '',
      dialName: ''
    }
  },
  created() {
    this.companyContactList(this.uid)
  },
  methods: {
    companyContactList(value){
      this.loading = true
      companyContactList({ 'uid': value })
        .then(res => {
          this.linkmanList = res.data
          this.loading = false
        }).catch(() => {
          this.loading = true
        })
    },
    resetLinkman(){
      this.parmas.id = ''
      this.parmas.contact = ''
      this.parmas.gender = '1'
      this.parmas.mobile = ''
      this.parmas.telephone = ''
      this.parmas.email = ''
      this.parmas.qq = ''
      this.parmas.comid = ''
      this.parmas.uid = this.uid
      this.parmas.comid = this.comid
    },
    editLinkman(value){
      this.type = 'edit'
      this.dialogTitle = '修改联系人'
      this.parmas.uid = this.uid
      this.parmas.comid = value.comid
      this.parmas.contact = value.contact
      this.parmas.sex = value.sex.toString()
      this.parmas.mobile = value.mobile
      this.parmas.telephone = value.telephone
      this.parmas.email = value.email
      this.parmas.qq = value.qq
      this.parmas.cid = value.cid
      this.parmas.id = value.id
      this.isDialog = true
    },
    addLinkman(formName){
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const insertData = { ...this.parmas }
      if(insertData.mobile == '' && insertData.telephone == ''){
        this.$message.error('请填写手机号或公司座机二选一')
        this.submitLoading = false
        return false
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (this.type == 'add'){
            addContact(insertData)
              .then(res => {
                this.$message.success(res.message)
                this.isDialog = false
                this.submitLoading = false
                this.companyContactList(this.uid)
              }).catch(() => {
                this.submitLoading = false
              })
          }
          if (this.type == 'edit'){
            editContact(insertData)
              .then(res => {
                this.$message.success(res.message)
                this.isDialog = false
                this.submitLoading = false
                this.companyContactList(this.uid)
              }).catch(() => {
                this.submitLoading = false
              })
          }
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    delContacts(value){
      this.$confirm('确定将此联系人删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        delContact({ 'id': value.id })
          .then(res => {
            this.$message.success(res.message)
            this.companyContactList(this.uid)
          }).catch(() => {

          })
      }).catch(() => {

      })
    },
    hadleContactsEdit () {
      this.type = 'add'
      this.dialogTitle = '添加联系人'
      this.isDialog = true
      this.parmas.id = ''
      this.parmas.contact = ''
      this.parmas.gender = '1'
      this.parmas.mobile = ''
      this.parmas.telephone = ''
      this.parmas.email = ''
      this.parmas.qq = ''
      this.parmas.comid = ''
      this.parmas.uid = this.uid
      this.parmas.comid = this.comid
    },
    clickDial(phone, name){
      var company_name = ''
      if (name == '' || name == null){
        company_name = '未完善企业资料'
      } else {
        company_name = name
      }
      this.$confirm('是否对【' + company_name + '】' + phone + ' 发起呼叫？', '提示', {
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

<style lang="scss">
.must_icon{
  color: red;
  margin-right: 3px;
}
</style>

<style lang="scss" scoped>
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
.call{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
.popup_contacts{
  padding: 30px;
  .contacts_item{
    border-radius: 4px;
    border: 1px solid #ebeef5;
    overflow: hidden;
    margin-bottom: 20px;
    .content{
      padding: 20px;
      .top{
        border-bottom: 1px dotted #ebeef5;
        .title{
          height:20px;
          margin-bottom:16px;
          .name{
            font-size: 20px;
            font-weight: 600;
            line-height: 20px;
            color: #303033;
            .sex{
              margin-left: 6px;
              i{
                font-size: 16px;
              }
            }
          }
          .job_name{
            font-size: 14px;
            color: #999;
            font-weight: 500;
            margin-left: 10px;
          }
          .main{
            float: right;
            background-color: #ff6000;
            display: block;
            font-size: 12px;
            color: #fff;
            line-height: 20px;
            padding: 0 8px;
            text-align: center;
            border-radius: 10px;
          }
        }
        .phone{
          height:18px;
          color: #303033;
          margin-bottom: 12px;
          font-size: 14px;
          font-weight: 600;
          .tel_icon{
            cursor: pointer;
            font-size: 16px;
            color: #0fba57;
          }
        }
      }
      .bottom{
        padding: 12px 0 0;
        font-size: 14px;
        line-height: 14px;
        color: #666;
        .line{
          height:16px;
          margin-bottom: 10px;
          &:last-child{
            margin-bottom: 0;
          }
        }
      }
    }
    .btns{
      display: flex;
      justify-content: space-between;
      height: 52px;
      border-radius: 0 0 3px 3px;
      border-top: 1px solid #ebeef5;
      background-color: #f3f3f3;
      padding: 10px 20px;
    }
  }
  .add_contacts{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 234px;
    border-radius: 4px;
    border: 1px dotted #dcdfe6;
    background-color: #fcfcfc;
    cursor: pointer;
    .add_icon{
      background: #fff;
      border-radius: 50%;
      color: #0FBA57;
      font-size: 18px;
    }
    .text{
      margin-left: 8px;
      color: #333;
      font-size: 16px;
      font-weight: 600;
    }
  }
}
.add_contacts_from{
  padding: 0 20px 0 10px;
}
::v-deep .el-dialog__body{
  padding-bottom: 10px;
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
