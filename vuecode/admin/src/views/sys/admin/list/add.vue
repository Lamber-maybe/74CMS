<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加管理员</span>
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
<!--        <el-form-item label="是否是销售" prop="is_sc">-->
<!--          <el-radio-group v-model.number="form.is_sc">-->
<!--            <el-radio :label="1" >是</el-radio>-->
<!--            <el-radio :label="0" >否</el-radio>-->
<!--          </el-radio-group>-->
<!--        </el-form-item>-->
        <el-form-item label="登录名" prop="username">
          <el-input v-model="form.username" class="middle" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="form.password" class="middle" show-password />
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
import { apiAdd, apiRoleOptions } from '@/api/admin'

export default {
  data() {
    return {
      rolelist: [],
      form: {
        username: '',
        password: '',
        role_id: ''
        // is_sc: 0
      },
      rules: {
        role_id: [{ required: true, message: '请选择角色', trigger: 'change' }],
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
          { required: true, message: '请输入密码', trigger: 'blur' },
          {
            max: 15,
            message: '长度在 1 到 15 个字符',
            trigger: 'blur'
          }
        ]
      }
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
      apiRoleOptions(param)
        .then(response => {
          this.rolelist = response.data
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/sys/admin/list')
              }, 1500)
              return true
            })
            .catch(() => {})
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
