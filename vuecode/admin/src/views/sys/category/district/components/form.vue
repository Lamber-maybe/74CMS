<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="所属分类" prop="parentid">
        <el-cascader
          v-model="form.parentid"
          placeholder="顶级"
          :options="options"
          :show-all-levels="false"
          :props="{ checkStrictly: true }"
          clearable
        />
      </el-form-item>
      <el-form-item label="名称" prop="name">
        <el-input v-model="form.name" class="middle" />
      </el-form-item>
      <el-form-item label="排序" prop="sort_id">
        <el-input v-model.number="form.sort_id" class="mini" />
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { apiAdd, apiEdit, apiOptions } from '@/api/config_district'

export default {
  props: ['formId', 'formParentid'],
  data() {
    return {
      infoLoading: false,
      options: [],
      form: {
        id: 0,
        parentid: [],
        name: '',
        sort_id: 0
      },
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        sort_id: [
          { type: 'number', message: '排序必须为数字', trigger: 'blur' }
        ]
      }
    }
  },
  mounted() {},
  created() {
    this.form.parentid = this.formParentid
    this.fetchCategoryData()
  },
  methods: {
    fetchInfo(id) {
      const param = { id }
      apiEdit(param, 'get').then(response => {
        this.form = { ...response.data.info }
        this.infoLoading = false
      })
    },
    fetchCategoryData() {
      this.infoLoading = true
      const param = {}
      apiOptions(param)
        .then(response => {
          this.options = response.data
          if (this.formId) {
            this.fetchInfo(this.formId)
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    onSubmit(formName) {
      const that = this
      // zdq 无法添加一级地区
      const parentid = that.form.parentid.filter((s) => {
        if (s != '' || s != null || s != 'undefined'){
          return s
        }
      })
      const parentid_length = parentid.length
      switch (parentid_length) {
        case 0:
          that.form.level = 1
          break
        case 1:
          that.form.level = 2
          break
        case 2:
          that.form.level = 3
          break
      }
      const insertData = { ...this.form }
      if (parseInt(insertData.id) > 0) {
        this.editSave(insertData, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        this.addSave(data, formName)
      }
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
