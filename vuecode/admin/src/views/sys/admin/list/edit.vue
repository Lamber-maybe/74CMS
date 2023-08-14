<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑管理员</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/sys/admin/list')"
        >
          返回
        </el-button>
        <el-button
          style="float: right; padding: 0;"
          type="text"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </div>
      <el-form
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="100px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="角色" prop="role_id">
          <el-select v-model="form.role_id" placeholder="请选择角色">
            <el-option
              v-for="(item, index) in rolelist"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="账号状态" prop="status" required>
          <el-radio-group v-model.number="form.status" :disabled="form.id === 1">
            <el-radio :label="1">正常</el-radio>
            <el-radio :label="2">锁定</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="登录名" prop="username">
          <el-input v-model="form.username" class="middle" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input
            v-model="form.password"
            class="middle"
            show-password
            placeholder="如不修改请留空"
          />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/sys/admin/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { adminLock, apiEdit, apiRoleOptions, getAdminCrmData } from '@/api/admin'

export default {
  data() {
    return {
      infoLoading: true,
      rolelist: [],
      form: {
        username: '',
        password: '',
        role_id: '',
        status: 1
        // is_sc: 0
      },
      rules: {
        role_id: [{ required: true, message: '请选择角色', trigger: 'change' }],
        status: [{ required: true, message: '请选择账号状态', trigger: 'change' }],
        username: [
          {
            required: true,
            message: '请输入登录名',
            trigger: 'blur'
          },
          {
            max: 15,
            message: '长度在 1 到 15 个字符',
            trigger: 'blur'
          }
        ],
        password: [
          {
            max: 15,
            message: '长度在 1 到 15 个字符',
            trigger: 'blur'
          }
        ]
      },
      current_status: ''
    }
  },
  computed: {},
  mounted() {},
  created() {
    this.fetchRoleData()
  },
  methods: {
    fetchRoleData() {
      const param = {}
      apiRoleOptions(param).then(response => {
        this.rolelist = response.data
        this.fetchInfo()
      })
    },
    fetchInfo() {
      const param = { id: this.$route.query.id }
      apiEdit(param, 'get')
        .then(response => {
          // console.log(response)
          this.form = { ...response.data }
          // this.form.is_sc = parseInt(this.form.is_sc)
          this.infoLoading = false
          this.current_status = this.form.status
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (insertData.status === 1) {
            apiEdit(insertData)
              .then(response => {
                this.$message.success(response.message)
                this.$store.dispatch('crm/getAdminList')
                setTimeout(function() {
                  that.$router.push('/sys/admin/list')
                }, 1500)
                return true
              })
              .catch(() => {})
          } else if (insertData.status === 2) {
            if (this.current_status != 2) {
              getAdminCrmData({ id: insertData.id }).then(response => {
                this
                  .$confirm('当前管理员 ' + insertData.username + ' 有 ' + response.data.total_company + ' 线索, ' + response.data.total_clue + ' 客户,锁定后将自动释放且无法登录管理后台,是否继续？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                  })
                  .then(() => {
                    insertData.is_release = 1
                    apiEdit(insertData)
                      .then(response => {
                        this.$message.success(response.message)
                        this.$store.dispatch('crm/getAdminList')
                        setTimeout(function() {
                          that.$router.push('/sys/admin/list')
                        }, 1500)
                        return true
                      })
                      .catch(() => {})
                  })
                  .catch(() => {})
              }).catch(() => {})
            } else {
              apiEdit(insertData)
                .then(response => {
                  this.$message.success(response.message)
                  this.$store.dispatch('crm/getAdminList')
                  setTimeout(function() {
                    that.$router.push('/sys/admin/list')
                  }, 1500)
                  return true
                })
                .catch(() => {})
            }
          } else {
            this.$message.error('账号状态选择错误')
            return false
          }
        } else {
          return false
        }
      })
    },
    goto(target) {
      this.$router.push(target)
    }
  }
}
</script>
