<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="搜索简历登录限制" required>
        <el-switch v-model="form.resume_search_login" />
      </el-form-item>
      <el-form-item label="搜索简历登录条数限制" prop="resume_search_login_num">
        <el-input v-model="form.resume_search_login_num" />
        <span class="smalltip">
          <i class="el-icon-info" />
          未登录会员可以搜索简历条数
        </span>
      </el-form-item>
      <!--      【ID1000392】【新增】职位、简历搜索页列表展现排序-->
      <el-form-item label="搜索结果排序方式">
        <el-radio-group v-model="form.resume_search_order">
          <el-radio label="1">
            按信息关联度排序
            <el-tooltip
              class="item"
              effect="dark"
              content="从意向类型、工作经验等多维度的数据匹配，关联度越高信息展现越靠前"
              placement="top-start"
            >
              <i class="el-icon-question"/>
            </el-tooltip>
          </el-radio>
          <el-radio label="2">
            按信息时间线排序
            <el-tooltip
              class="item"
              effect="dark"
              content="搜索结果按简历刷新时间排序，时间越靠近展现越靠前"
              placement="top-start"
            >
              <i class="el-icon-question"/>
            </el-tooltip>
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <!--      【ID1000392】【新增】职位、简历搜索页列表展现排序-->
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        resume_search_login: false,
        resume_search_login_num: 0,
        resume_search_order: 1
      },
      rules: {
        resume_search_login_num: [
          {
            required: true,
            message: '请输入搜索简历登录条数限制',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            resume_search_login,
            resume_search_login_num,
            resume_search_order
          } = { ...response.data }
          this.form = {
            resume_search_login: resume_search_login == 1,
            resume_search_login_num,
            resume_search_order
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.resume_search_login = insertData.resume_search_login === true ? 1 : 0
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
<style scoped>
.el-input,
.el-input-group {
  width: 50%;
}
::v-deep .el-icon-question {
  color: #999;
}
</style>
