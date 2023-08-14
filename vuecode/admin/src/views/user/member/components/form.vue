<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item v-if="uid == 0" label="身份">
        <el-radio-group v-model="form.utype">
          <el-radio label="1">企业会员</el-radio>
          <el-radio label="2">个人会员</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="用户名" prop="username">
        <el-input v-model="form.username" class="small" />
      </el-form-item>
      <el-form-item label="手机号" prop="mobile">
        <el-input v-model="form.mobile" class="small" />
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input
          v-model="form.password"
          class="small"
          show-password
          :placeholder="placeholder"
        />
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { memberAdd, memberEdit, memberCheckUnique } from '@/api/member'
import { validMobile, validUsername, validEmail } from '@/utils/validate'

export default {
  props: ['uid', 'listtype'],
  data() {
    var validateUsername = (rule, value, callback) => {
      if (validUsername(value)) {
        callback(new Error('用户名不能是纯数字'))
      } else if (validEmail(value)) {
        callback(new Error('用户名不能是邮箱'))
      } else {
        const param = {
          field: 'username',
          value,
          self: this.uid,
          utype: this.form.utype
        }
        memberCheckUnique(param).then(response => {
          if (response.data == 0) {
            callback(new Error('用户名已被占用'))
          } else {
            callback()
          }
        })
      }
    }
    var validateMobile = (rule, value, callback) => {
      if (!validMobile(value)) {
        callback(new Error('请输入正确的手机号'))
      } else {
        const param = {
          field: 'mobile',
          value,
          self: this.uid,
          utype: this.form.utype
        }
        memberCheckUnique(param).then(response => {
          if (response.data == 0) {
            callback(new Error('手机号已被占用'))
          } else {
            callback()
          }
        })
      }
    }
    var validatePassword = (rule, value, callback) => {
      if (this.uid > 0) {
        callback()
      } else {
        if (value == '') {
          callback(new Error('请输入密码'))
        } else {
          callback()
        }
      }
    }
    return {
      placeholder: '如不修改请留空',
      infoLoading: false,
      options: [],
      form: {
        uid: 0,
        utype: '1',
        username: '',
        password: '',
        mobile: ''
      },
      rules: {
        username: [
          {
            required: true,
            message: '请输入用户名',
            trigger: 'blur'
          },
          {
            min: 6,
            max: 30,
            message: '长度在 6 到 30 个字符',
            trigger: 'blur'
          },
          { validator: validateUsername, trigger: 'blur' }
        ],
        password: [
          { validator: validatePassword, trigger: 'blur' },
          {
            min: 6,
            max: 15,
            message: '长度在 6 到 15 个字符',
            trigger: 'blur'
          }
        ],
        mobile: [
          {
            required: true,
            message: '请输入手机号',
            trigger: 'blur'
          },
          { validator: validateMobile, trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      const uid = this.uid
      if (uid > 0) {
        const param = { uid }
        memberEdit(param, 'get')
          .then(response => {
            this.form = { ...response.data.info }
            this.form.utype = this.form.utype + ''
            this.infoLoading = false
          })
          .catch(() => { })
      } else {
        this.placeholder = ''
        this.infoLoading = false
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          memberAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              if (this.listtype != 'invalid') {
                if (this.form.utype == 1) {
                  this.closeDialogCompany()
                } else {
                  this.closeDialogPersonal()
                }
              } else {
                this.closeDialog()
              }
              this.pageReload()
              return true
            })
            .catch(() => { })
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          memberEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => { })
        } else {
          return false
        }
      })
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      if (parseInt(insertData.uid) > 0) {
        that.editSave(insertData, formName)
      } else {
        that.addSave(insertData, formName)
      }
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    closeDialogCompany() {
      this.$emit('setDialogFormVisibleCompany')
    },
    closeDialogPersonal() {
      this.$emit('setDialogFormVisiblePersonal')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
.el-form.common-form .el-input.small {
  width: 300px;
}
</style>
