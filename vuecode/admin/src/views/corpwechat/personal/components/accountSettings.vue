<template>
  <div class="box">
    <el-form ref="form" size="medium" :model="form" label-width="160px">
      <el-form-item class="flex" label="账户名称：" prop="name">
        <el-input v-model="form.username" disabled />
        <el-button type="text" class="edit" @click="editUsername(form.username)">修改</el-button>
      </el-form-item>
      <el-form-item label="个人头像：">
        <el-upload
          ref="avatarRef"
          class="upload_box"
          :headers="headers"
          :action="apiUpload"
          :show-file-list="false"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload"
        >
          <div v-if="form.avatar" class="avatar">
            <img :src="form.avatar">
            <div class="avatar_icon">
              <i class="el-icon-upload2 icon_del" />
              <i class="el-icon-delete" @click.stop="delAvatar" />
            </div>
          </div>
          <i v-else class="el-icon-plus avatar-uploader-icon" />
        </el-upload>
      </el-form-item>
      <el-form-item class="flex" label="登录密码：" prop="name">
        <el-input v-model="password" disabled />
        <el-button type="text" class="edit" @click="editPassword">修改</el-button>
      </el-form-item>

      <el-form-item class="flex" label="绑定手机：" prop="name">
        <el-input v-model="form.mobile" disabled />
        <el-button type="text" class="edit" @click="editPhone">修改</el-button>
      </el-form-item>

      <el-form-item label="绑定微信：" prop="name">

        <span v-if="form.openid==''"><el-button class="btn-s" type="text" @click="funBind()">绑定</el-button></span>
        <span v-else><el-button class="btn-sred" type="text" @click="funBindCancel()">解绑</el-button></span>
        <!--<span>未绑定</span>-->
        <!--<el-button type="text" class="edit" @click="funBind">立即绑定</el-button>-->
        <span class="smalltip">
          <i class="el-icon-info" />
          扫码绑定微信，创建完成后请用此微信，登录管理后台
        </span>
      </el-form-item>

      <!--<div class="footer">-->
      <!--<el-button size="medium" class="right20" type="primary ">确定</el-button>-->
      <!--<el-button size="medium">取消</el-button>-->
      <!--</div>-->
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
import { getToken } from '@/utils/auth'
import apiArr from '@/api'
import { parseTime } from '@/utils'
import {
  personal,
  personalAvatar,
  personalBindingCode,
  personalBindingMobile,
  personalPassword,
  personalUsername
} from '@/api/personal'
import { adminBindQrcode, adminBindQrcodeCancel, adminIsBindWechat } from '@/api/admin'

export default {
  name: 'AccountSettings',
  data() {
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
      form: {},
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
      wxLogin: '',
      // 绑定微信定时器
      timer: ''
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
  beforeDestroy() {
    clearInterval(this.timer)
  },
  methods: {
    delAvatar() {
      this.form.avatar = ''
      personalAvatar({ avatar: '' }).then(res1 => {
        if (res1.code == '200'){
          this.$message.success('修改成功！')
          this.getData()
        } else {
          this.$message.error(res1.message)
        }
      })
    },
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
        that.timer = setInterval(this.funBindResult, 3000)
      })
        .catch(() => {})
    },
    // 绑定微信结果
    funBindResult() {
      const that = this
      adminIsBindWechat({}).then(response => {
        if (response.data.is_bind == 1){
          that.showQrcode = false
          that.$message.success('微信绑定成功')
          clearInterval(that.timer)
          that.getData()
        }
      })
        .catch(() => {})
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
            that.getData()
          })
        })
        .catch(() => {})
    }
  }
}
</script>

<style scoped lang="scss">
.upload_box{
  position: relative;
}
.avatar_icon{
  width: 100%;
  height:100%;
  background:rgba(0,0,0,.5);
  color:white;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top:0;
  font-size: 18px;
  .icon_del{
    margin-right: 10px;
  }
}
.box{
  padding:50px 0px;
}
.btn-sred{
  margin-right: 5px;
  padding: 8px 12px;
  color: #F56C6C;
  background: #fef0f0;
  border-color: #fbc4c4;
}
.btn-s{
  margin-right: 5px;
  padding: 8px 12px;
  color: #409EFF;
  background: #ecf5ff;
  border-color: #b3d8ff;
}
::v-deep .el-form-item__content{
  width: 50%;
}
/* 头像 */
::v-deep .el-upload{
  border: 1px dashed #d9d9d9;
}
.avatar-uploader ::v-deep.el-upload {
  border: 1px dashed #d9d9d9;
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
  width: 80px;
  height: 80px;
  line-height: 80px;
  text-align: center;
}
.avatar {
  width: 80px;
  height: 80px;
  line-height: 80px;
  position: relative;
}
.avatar img{
  width: 100%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  display: table-cell;
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
::v-deep.el-card__header{
  border: 0!important;
}
.flex ::v-deep.el-form-item__content{
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
