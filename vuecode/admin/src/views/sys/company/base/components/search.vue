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
      <el-form-item label="搜索职位登录限制" required>
        <el-switch v-model="form.job_search_login" />
      </el-form-item>
      <el-form-item label="搜索职位登录条数限制" prop="job_search_login_num">
        <el-input v-model="form.job_search_login_num" />
        <span class="smalltip">
          <i class="el-icon-info" />
          未登录会员可以搜索职位条数
        </span>
      </el-form-item>
      <!--      【ID1000392】【新增】职位、简历搜索页列表展现排序-->
      <el-form-item label="搜索结果排序方式">
        <el-radio-group v-model="form.job_search_order">
          <el-radio label="1">
            按信息关联度排序
            <el-tooltip
              class="item"
              effect="dark"
              content="从职位类型、描述等多维度的数据匹配，关联度越高信息展现越靠前"
              placement="top-start"
            >
              <i class="el-icon-question" />
            </el-tooltip>
          </el-radio>
          <el-radio label="2">
            按信息时间线排序
            <el-tooltip
              class="item"
              effect="dark"
              content="搜索结果按职位刷新时间排序，时间越靠近展现越靠前"
              placement="top-start"
            >
              <i class="el-icon-question" />
            </el-tooltip>
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <!--      【ID1000392】【新增】职位、简历搜索页列表展现排序-->
      <!--      【ID1000546】【新增】搜索关键词配置项，全文搜，模糊搜方式可选 yx-2023.02.17-->
      <el-form-item label="搜索方式">
        <el-radio-group v-model="form.job_search_engine">
          <el-radio label="1">
            全文搜索
            <el-tooltip
              class="item"
              effect="dark"
              content="使用全文搜索可能会导致部分搜索关键词没有结果，影响搜索数量，但系统搜索性能更佳"
              placement="top-start"
            >
              <i class="el-icon-question" />
            </el-tooltip>
          </el-radio>
          <el-radio label="2">
            模糊搜索
            <el-tooltip
              class="item"
              effect="dark"
              content="使用模糊搜索在网站数据量大的情况下会导致网站搜索卡顿，但对于关键词搜索结果更佳"
              placement="top-start"
            >
              <i class="el-icon-question" />
            </el-tooltip>
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <!--      【ID1000546】【新增】搜索关键词配置项，全文搜，模糊搜方式可选 yx-2023.02.17-->
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
        job_search_login: false,
        job_search_login_num: 0,
        job_search_order: 1,
        job_search_engine: 1
      },
      rules: {
        job_search_login_num: [
          {
            required: true,
            message: '请输入搜索职位登录条数限制',
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
            job_search_login,
            job_search_login_num,
            job_search_order,
            job_search_engine
          } = { ...response.data }
          this.form = {
            job_search_login: job_search_login == 1,
            job_search_login_num,
            job_search_order,
            job_search_engine
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.job_search_login = insertData.job_search_login === true ? 1 : 0
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
