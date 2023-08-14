<template>
  <div class="app-container">
    <el-tag
      v-for="(item, index) in list"
      :key="index"
      closable
      :effect="form.id == item.id ? 'dark' : 'plain'"
      @click="changeItem(item)"
      @close="handleClose(item)"
    >
      {{ item.title }}
    </el-tag>
    <el-button class="button-new-tag" size="small" @click="funAdd"
      >+ 添加模板</el-button
    >
    <div class="spaceline"></div>
    <el-form
      v-if="showForm"
      ref="form"
      class="common-form"
      :model="form"
      label-width="80px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="模板名称" prop="title">
        <el-input v-model="form.title" class="middle" />
      </el-form-item>
      <el-form-item label="内容" prop="content">
        <el-input
          type="textarea"
          :rows="8"
          placeholder="请输入内容"
          v-model="form.content"
        >
        </el-input>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {
  categoryJobTemplateList,
  categoryJobTemplateAdd,
  categoryJobTemplateEdit,
  categoryJobTemplateDelete,
} from "@/api/category_job_template"

export default {
  props: ["pid"],
  data() {
    return {
      showForm: false,
      list: [],
      form: {
        id: 0,
        pid: 0,
        title: "",
        content: ""
      },
      rules: {
        title: [
          { required: true, message: "请输入模板名称", trigger: "blur" },
          { max: 30, message: "长度在 1 到 30 个字符", trigger: "blur" }
        ],
        content: [
          { required: true, message: "请输入内容", trigger: "blur" }
        ]
      },
    }
  },
  mounted() { },
  created() {
    this.form.pid = this.pid
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      categoryJobTemplateList({ pid: this.pid }, "get")
        .then((response) => {
          this.list = response.data
        })
        .catch(() => { })
    },
    funAdd() {
      this.form = {
        id: 0,
        pid: this.pid,
        title: "",
        content: ""
      }
      this.showForm = true
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          categoryJobTemplateAdd(insertData)
            .then((response) => {
              this.$message.success(response.message)
              this.form = {
                id: 0,
                pid: this.pid,
                title: "",
                content: ""
              }
              this.showForm = false
              this.fetchInfo()
              return true
            })
            .catch(() => { })
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          categoryJobTemplateEdit(insertData)
            .then((response) => {
              this.$message.success(response.message)
              this.form = {
                id: 0,
                pid: this.pid,
                title: "",
                content: ""
              }
              this.showForm = false
              this.fetchInfo()
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
      const insertData = { ...this.form }
      if (parseInt(insertData.id) > 0) {
        this.editSave(insertData, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        this.addSave(data, formName)
      }
    },
    changeItem(row) {
      this.form = {
        id: row.id,
        pid: row.pid,
        title: row.title,
        content: row.content
      }
      this.showForm = true
    },
    handleClose(row) {
      this.$confirm('确定删除吗？', "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消", type: "warning",
      })
        .then(_ => {
          categoryJobTemplateDelete({ id: row.id })
            .then((response) => {
              this.$message.success(response.message)
              this.form = {
                id: 0,
                pid: this.pid,
                title: "",
                content: ""
              }
              this.showForm = false
              this.fetchInfo()
              return true
            })
            .catch(() => { })
        })
        .catch(_ => { });
    }
  },
}
</script>
<style scoped>
.el-input,
.el-textarea {
  width: 400px;
}
.el-tag {
  margin-right: 10px;
  cursor: pointer;
}
</style>
