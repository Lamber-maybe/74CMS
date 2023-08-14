<template>
  <div class="app-container">
    <el-form ref="form" size="medium" :model="form" label-width="160px">
      <el-card :body-style="{ padding: '0 20px 20px' }">
        <div slot="header">
          <span>基础信息</span>
        </div>
        <el-card :body-style="{ padding: '20px 20px 0' }" shadow="never">
          <el-form-item class="flex" label="账户名称：" prop="name">
            <el-input v-model="form.username" disabled />
            <el-button type="text" class="edit" @click="editUsername(form.username)">修改</el-button>
          </el-form-item>
          <el-form-item label="个人头像：">
            <el-upload
              class="avatar-uploader"
              :headers="headers"
              :action="apiUpload"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              :before-upload="beforeAvatarUpload"
            >
              <div v-if="form.avatar" class="avatar">
                <img :src="form.avatar">
                <span class="words">修改</span>
              </div>
              <i v-else class="el-icon-plus avatar-uploader-icon" />
            </el-upload>
          </el-form-item>
        </el-card>
        <el-card class="top10" :body-style="{ padding: '20px 20px 0' }" shadow="never">
          <el-form-item label="最近登录时间：" prop="name">
            <span>{{ parseTime(form.last_login_time, '{y}-{m}-{d} {h}:{i}') }}</span>
          </el-form-item>
        </el-card>

        <div class="padding20">
          <span>安全设置</span>
        </div>
        <el-card :body-style="{ padding: '20px 20px 0' }" shadow="never">
          <el-form-item class="flex" label="登录密码：" prop="name">
            <el-input v-model="password" disabled />
            <el-button type="text" class="edit" @click="editPassword">修改</el-button>
          </el-form-item>
        </el-card>

        <el-card class="top10" :body-style="{ padding: '20px 20px 0' }" shadow="never">
          <el-form-item class="flex" label="绑定手机：" prop="name">
            <el-input v-model="form.mobile" disabled />
            <el-button type="text" class="edit" @click="editPhone">修改</el-button>
          </el-form-item>
        </el-card>

        <el-card class="top10" :body-style="{ padding: '20px 20px 0' }" shadow="never">
          <el-form-item label="绑定微信：" prop="name">

            <span v-if="form.openid==''"><el-button type="text" @click="funBind()">点击绑定</el-button></span>
            <span v-else>已绑定<el-button type="text" @click="funBindCancel()">解绑</el-button></span>
            <!--<span>未绑定</span>-->
            <!--<el-button type="text" class="edit" @click="funBind">立即绑定</el-button>-->
            <span>（扫码绑定微信，创建完成后请用此微信，登录管理后台）</span>
          </el-form-item>
        </el-card>

        <!--<div class="footer">-->
        <!--<el-button size="medium" class="right20" type="primary ">确定</el-button>-->
        <!--<el-button size="medium">取消</el-button>-->
        <!--</div>-->
      </el-card>
    </el-form>

    <!--修改密码-->
    <el-dialog
      title="修改密码"
      :visible.sync="pwDialogVisible"
      width="40%"
    >
      <el-form
        ref="pwRuleForm"
        :model="pwRuleForm"
        :rules="pwRules"
        status-icon
        label-width="100px"
      >
        <el-form-item label="旧密码" prop="old_password">
          <el-input v-model="pwRuleForm.old_password" type="password" autocomplete="off" />
        </el-form-item>
        <el-form-item label="新密码" prop="password">
          <el-input v-model="pwRuleForm.password" type="password" autocomplete="off" />
        </el-form-item>
        <el-form-item label="确认密码" prop="password_confirm">
          <el-input v-model="pwRuleForm.password_confirm" type="password" autocomplete="off" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="pwDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="pwSubmitForm('pwRuleForm')">确 定</el-button>
      </span>
    </el-dialog>

    <!--修改手机-->
    <el-dialog
      title="修改手机号"
      :visible.sync="phoneDialogVisible"
      width="40%"
    >
      <el-form
        ref="phoneRuleForm"
        :model="phoneRuleForm"
        :rules="phoneRules"
        status-icon
        label-width="100px"
      >
        <el-form-item label="手机号" prop="mobile">
          <el-input v-model="phoneRuleForm.mobile" autocomplete="off" />
        </el-form-item>
        <el-form-item label="验证码" prop="code">
          <el-input v-model="phoneRuleForm.code" autocomplete="off">
            <template slot="append">
              <el-button type="primary" @click="handlerSendCode">{{ codeFlag ? '发送验证码' : `${timeNumber}秒后重试` }}</el-button>
            </template>
          </el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="phoneDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="phoneSubmitForm('phoneRuleForm')">确 定</el-button>
      </span>
    </el-dialog>
    <el-dialog
      v-if="showQrcode"
      title="扫码绑定"
      :visible.sync="showQrcode"
      width="15%"
      :close-on-click-modal="false"
      style="text-align:center;"
      @close="showQrcode=false"
    >
      <div style="margin-bottom:10px;"><img :src="qrcodeSrc" width="200" height="200"></div>
    </el-dialog>
    <!--<div v-if="iframeSrc" class="iframe">-->
      <!--<iframe :src="iframeSrc" frameborder="0" style="width: 100%;height: 100%">-->
        <!--&lt;!&ndash;<div id="wx_reg" />&ndash;&gt;-->

      <!--</iframe>-->
    <!--</div>-->

  </div>
</template>

<script>
/*
* 企微用户 by:rxf
*/
import {
  personal,
  personalAvatar,
  personalUsername,
  personalPassword,
  personalBindingCode,
  personalBindingMobile,
  authorizeCode } from '@/api/personal'
import { parseTime } from '@/utils/index'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
import { adminBindQrcode, adminBindQrcodeCancel } from '@/api/admin'

export default {
  name: 'Customer',

  data(){
    var validatePass = (rule, value, callback) => {
      if (value === '' || !value) {
        callback(new Error('请输入密码'))
      } else if (value.length < 6){
        callback(new Error('密码长度不能低于6位'))
      } else {
        if (this.pwRuleForm.password_confirm !== '') {
          this.$refs.pwRuleForm.validateField('password_confirm')
        }
        callback()
      }
    }
    var validatePass2 = (rule, value, callback) => {
      if (value === '' || !value) {
        callback(new Error('请再次输入密码'))
      } else if (value.length < 6){
        callback(new Error('密码长度不能低于6位'))
      } else if (value !== this.pwRuleForm.password) {
        callback(new Error('两次输入密码不一致'))
      } else {
        callback()
      }
    }
    return {
      // 初始值
      form: '',
      // 上传相关
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      // 密码默认值
      password: '*********',

      //  密码绑定弹窗
      pwDialogVisible: false,
      pwRuleForm: {},
      pwRules: {
        old_password: [{ required: true, message: '请输入旧密码', trigger: 'blur' }],
        password: [{ required: true, validator: validatePass, trigger: 'blur' }],
        password_confirm: [{ required: true, validator: validatePass2, trigger: 'blur' }]
      },
      //  手机号绑定弹窗
      phoneDialogVisible: false,
      phoneRuleForm: {},
      phoneRules: {
        mobile: [{ required: true, message: '请输入手机号', trigger: 'blur' }],
        code: [{ required: true, message: '请输入验证码', trigger: 'blur' }]
      },

      // 验证码
      isClick: true,
      codeFlag: true,
      timeNumber: 60,

      //  企业微信绑定
      // iframeSrc: '',

      qrcodeSrc: '',
      showQrcode: false,
      wxLogin: ''
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created(){
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = 'png,gif,jpeg,jpg'
    this.getData()
  },
  mounted(){},
  methods: {
    parseTime,
    // 初始数据
    getData(){
      personal().then(res => {
        const { code, message, data } = res
        if (code == '200'){
          this.form = data
        } else {
          this.$message.error(message)
        }
      })
    },
    //  修改账户名称
    editUsername(){
      this.$prompt('请输入账户名称', '修改', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        closeOnClickModal: false,
        inputValidator: (val) => {
          if (val == '' || !val){
            return '账户名称不能为空'
          } else {
            return true
          }
        }
      }).then(({ value }) => {
        personalUsername({ username: value }).then(res => {
          const { code, message } = res
          if (code == '200'){
            this.$message.success('修改成功！')
            this.getData()
          } else {
            this.$message.error(message)
          }
        })
      }).catch(() => {

      })
    },
    //  头像上传
    handleAvatarSuccess(res, file) {
      const { code, data, message } = res
      if (code == '200') {
        this.form.avatar = data.file_url
        personalAvatar({ avatar: data.file_url }).then(res1 => {
          if (res1.code == '200'){
            this.$message.success('修改成功！')
            this.getData()
          } else {
            this.$message.error(res1.message)
          }
        })
      } else {
        this.$message.error(message)
        return false
      }
    },
    beforeAvatarUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('请上传png,gif,jpeg,jpg的图片格式')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    },

    //  密码修改
    editPassword(){
      this.pwDialogVisible = true
      this.pwRuleForm = {}
    },
    pwSubmitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          personalPassword(this.pwRuleForm).then(res => {
            const { code, message } = res
            if (code == '200'){
              this.$message.success('修改成功！')
              this.pwDialogVisible = false
              this.getData()
            } else {
              this.$message.error(message)
            }
          })
        } else {
          return false
        }
      })
    },

    //  修改手机号
    editPhone(){
      this.phoneDialogVisible = true
      this.phoneRuleForm = {}
      this.isClick = true
      this.codeFlag = true
      this.timeNumber = 60
    },
    // 获取验证码
    handlerSendCode () {
      if (this.isClick) {
        this.isClick = false
        if (!this.phoneRuleForm.mobile) {
          this.$message.error('请填写手机号')
          this.isClick = true
          return
        }

        if (!(/^1[3|4|5|6|7|8|9][0-9]\d{4,8}$/.test(this.phoneRuleForm.mobile))) {
          this.$message.error('手机号填写有误')
          this.isClick = true
          return
        }

        if (!this.codeFlag) {
          this.isClick = true
          return
        }
        const dataJson = {
          mobile: this.phoneRuleForm.mobile
        }
        personalBindingCode(dataJson).then((res) => {
          this.isClick = true
          const { code, message } = res
          if (code == '200') {
            this.$message.success('验证码发送成功，请查收短信')
          } else {
            this.$message.error(message)
          }
          const loop = () => {
            this.codeFlag = false
            this.timeNumber--
            if (this.timeNumber === 0) {
              this.codeFlag = true
              this.timeNumber = 60
            } else {
              setTimeout(() => {
                loop()
              }, 1000)
            }
          }
          loop()
        })
      } else {
        this.isClick = true
        // this.$message.error('请勿重复提交')
      }
    },
    phoneSubmitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          personalBindingMobile(this.phoneRuleForm).then(res => {
            const { code, message } = res
            if (code == '200'){
              this.$message.success('修改成功！')
              this.phoneDialogVisible = false
              this.getData()
            } else {
              this.$message.error(message)
            }
          })
        } else {
          return false
        }
      })
    },
    funBind(){
      const that = this
      adminBindQrcode({ id: this.form.id }).then(response => {
        that.qrcodeSrc = response.data
        that.showQrcode = true
      })
    },
    funBindCancel(){
      const that = this
      that
        .$confirm('确定解绑吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          adminBindQrcodeCancel({ id: this.form.id }).then(response => {
            that.$message.success(response.message)
            that.fetchData()
          })
        })
        .catch(() => {})
    },
    //  绑定企业微信
    corpwechatClick(){
      const that = this
      authorizeCode().then(res => {
        const { code, data, message } = res
        if (code == '200'){
          const iHeight = 680
          const iWidth = 680
          // 获得窗口的垂直位置
          const iTop = (window.screen.availHeight - 30 - iHeight) / 2
          // 获得窗口的水平位置
          const iLeft = (window.screen.availWidth - 10 - iWidth) / 2
          const winObj = window.open(data,
            '_blank',
            'height=' + iHeight + ',innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no'
          )
          const loop = setInterval(function () {
            if (winObj.closed) {
              clearInterval(loop)
              that.getData()
              that.$message.info('操作完成！')
            }
          }, 1000)
        } else {
          this.$message.error(message)
        }
      })
    }
  }
}
</script>

<style scoped>
  /* 头像 */
.avatar-uploader /deep/.el-upload {
  border: 1px dashed #d9d9d9;
  border-radius:50%;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409EFF;
}
.avatar-uploader-icon {
  font-size: 18px;
  color: #8c939d;
  width: 55px;
  height: 55px;
  line-height: 55px;
  text-align: center;
}
.avatar {
  width: 55px;
  height: 55px;
  position: relative;
}
.avatar img{
  width: 100%;
}
.avatar .words{
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding-top: 2px;
  padding-bottom: 2px;
  font-size: 12px;
  transform: scale(0.9);
  background: rgba(0,0,0,0.9);
  line-height: 1;
  color: #ffffff;
}
/* 重置el-card*/
  /deep/.el-card__header{
    border: 0!important;
  }
  .flex /deep/.el-form-item__content{
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }
  /*修改*/
  .edit{
    flex-shrink: 0;
    margin-left: 20px;
  }
  .top10{
    margin-top: 10px  ;
  }
  .padding20{
    padding: 18px 0;
  }
  .right20{
    margin-right: 20px;
  }
  /*按钮组*/
  .footer{
    margin-top: 60px;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }
  /*iframe*/
  .iframe{
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    width: 40%;
    height: 500px;
    z-index: 1000;
    background: #ffffff;
    padding: 50px;
    box-shadow: 0 0 2px 2px rgba(0,0,0,0.2);
  }
</style>
