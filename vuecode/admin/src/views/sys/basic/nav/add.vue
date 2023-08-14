<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加导航</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/sys/basic/nav')"
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
        label-width="120px"
        :inline-message="true"
        :rules="rules"
      >
        <el-form-item label="导航名称" prop="title">
          <el-input v-model="form.title" />
        </el-form-item>
        <el-form-item label="导航类型" prop="link_type">
          <el-radio-group v-model="form.link_type">
            <el-radio :label="1">系统页面</el-radio>
            <el-radio :label="2">站外链接</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="form.link_type==1"
          label="跳转页面"
          prop="page"
          :rules="[
            {
              required: true,
              message: '请选择跳转页面',
              trigger: 'change'
            }
          ]"
        >
          <el-select v-model="form.page" placeholder="请选择">
            <el-option
              v-for="(item,index) in optionsPage"
              :key="index"
              :label="item"
              :value="index"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.link_type==2"
          label="跳转链接"
          prop="url"
          :rules="[
            {
              required: true,
              message: '请输入跳转链接',
              trigger: 'blur'
            },
            {
              max: 200,
              message: '长度在 1 到 200 个字符',
              trigger: 'blur'
            }
          ]"
        >
          <el-input v-model="form.url" />
        </el-form-item>
        <el-form-item label="打开方式" prop="target">
          <el-radio-group v-model="form.target">
            <el-radio label="_self">原窗口</el-radio>
            <el-radio label="_blank">新窗口</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model="form.sort_id" />
        </el-form-item>
        <el-form-item label="是否显示" prop="is_display">
          <el-radio-group v-model="form.is_display">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/sys/basic/nav')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { navAdd } from '@/api/nav'
import { getClassify } from '@/api/classify'

export default {
  data() {
    return {
      form: {
        title: '',
        link_type: 1,
        page: '',
        url: '',
        target: '_self',
        sort_id: 0,
        is_display: 1
      },
      optionsPage: [],
      rules: {
        title: [
          {
            required: true,
            message: '请输入导航名称',
            trigger: 'blur'
          },
          {
            max: 15,
            message: '长度在 1 到 15 个字符',
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
      getClassify({ type: 'navPage' })
        .then(response => {
          this.optionsPage = response.data
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          navAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/sys/basic/nav')
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
<style scoped>
.el-input{
  width: 550px;
}
</style>
