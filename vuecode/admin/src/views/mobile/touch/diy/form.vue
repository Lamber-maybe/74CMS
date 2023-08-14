<template>
  <div class="app-container">
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="菜单名称">
        <el-input :value="itemInfo.title" disabled />
      </el-form-item>
      <el-form-item label="自定义名称" prop="custom_title">
        <el-input
          v-model="form.custom_title"
          placeholder="不填写则显示默认菜单名称"
        />
      </el-form-item>
      <el-form-item label="自定义图标" prop="icon">
        <el-upload
          class="icon-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handleIconSuccess"
          :before-upload="beforeIconUpload"
        >
          <img v-if="form.icon" :src="iconUrl" class="icon">
          <i v-else class="el-icon-plus icon-uploader-icon" />
        </el-upload>
        <a
          v-if="iconUrl != ''"
          href="javascript:;"
          class="font_brand"
          style="cursor:pointer;"
          @click="delIcon"
        >
          [删除]
        </a>
      </el-form-item>
      <el-form-item label="跳转链接" prop="link_url">
        <el-input v-model="form.link_url" placeholder="不填写则跳转默认页面" />
      </el-form-item>
      <el-form-item label="排序" prop="sort_id">
        <el-input v-model="form.sort_id" />
      </el-form-item>
      <el-form-item label="是否显示" prop="is_display">
        <el-switch v-model="form.is_display" />
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { mobileIndexMenuEdit } from '@/api/configuration'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
export default {
  props: ['itemInfo'],
  data() {
    return {
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      iconUrl: '',
      form: {
        id: 0,
        custom_title: '',
        icon: 0,
        link_url: '',
        sort_id: '',
        is_display: true
      },
      rules: {}
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      if (this.itemInfo !== null) {
        this.itemInfo.is_display =
          this.itemInfo.is_display == 1
        this.iconUrl = this.itemInfo.iconUrl
        this.form = {
          ...this.form,
          ...this.itemInfo
        }
      }
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_display = insertData.is_display === true ? 1 : 0
          mobileIndexMenuEdit(insertData)
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
      const insertData = { ...that.form }
      that.editSave(insertData, formName)
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    },
    delIcon() {
      this.iconUrl = ''
      this.form.icon = 0
    },
    handleIconSuccess(res, file) {
      if (res.code == 200) {
        this.iconUrl = URL.createObjectURL(file.raw)
        this.form.icon = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeIconUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('上传文件格式不允许')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    }
  }
}
</script>
<style scoped>
.el-select,
.el-input {
  width: 300px;
}
.icon-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.icon-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.icon-uploader:hover {
  border-color: #409eff;
}
.icon-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
</style>
