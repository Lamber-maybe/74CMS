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
      <el-form-item label="语种" prop="language">
        <el-select v-model="form.language" placeholder="请选择">
          <el-option
            v-for="(item, index) in options_language"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="熟悉程度" prop="level">
        <el-select v-model="form.level" placeholder="请选择">
          <el-option
            v-for="(item, index) in options_level"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label=" ">
        <el-button
          type="primary"
          :loading="submitLoading"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { getClassify } from '@/api/classify'
import { resumeLanguageAddAndEdit } from '@/api/resume'
export default {
  props: ['itemId', 'rid'],
  data() {
    return {
      submitLoading: false,
      infoLoading: false,
      options_language: [],
      options_level: [],
      form: {
        id: 0,
        rid: 0,
        language: '',
        level: ''
      },
      rules: {
        language: [
          {
            required: true,
            message: '请选择语种',
            trigger: 'change'
          }
        ],
        level: [
          {
            required: true,
            message: '请选择熟悉程度',
            trigger: 'change'
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
      const param = {
        id: this.itemId
      }
      resumeLanguageAddAndEdit(param, 'get')
        .then(response => {
          if (response.data.info != null) {
            this.form = { ...response.data.info }
          } else {
            this.form.rid = this.rid
          }
          return getClassify({ type: 'language,languageLevel' })
        })
        .then(response => {
          this.options_language = [...response.data.language]
          this.options_level = [...response.data.languageLevel]
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const that = this
      const insertData = { ...that.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          resumeLanguageAddAndEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
.el-form.common-form .el-select {
  width: 300px;
}
</style>
