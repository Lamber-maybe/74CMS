<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/hrtool/list')"
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
        label-width="80px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="文件名称" prop="filename">
          <el-input v-model="form.filename" />
        </el-form-item>
        <el-form-item label="分类" prop="cid">
          <el-select v-model="form.cid" placeholder="请选择分类">
            <el-option
              v-for="(item, index) in hrtoolCategory"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="文件">
          <el-upload
            class="thumb-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handlerFileSuccess"
            :before-upload="beforeFileUpload"
          >
            <i class="el-icon-plus thumb-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/hrtool/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import { hrtoolAdd } from '@/api/hrtool'
import { getClassify } from '@/api/classify'
import apiArr from '@/api'

export default {
  data() {
    return {
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.uploadHrtool,
      hrtoolCategory: [],
      form: {
        filename: '',
        cid: '',
        fileurl: '',
        sort_id: 0
      },
      rules: {
        filename: [
          {
            required: true,
            message: '请输入文件名称',
            trigger: 'blur'
          },
          {
            max: 100,
            message: '长度在 1 到 100 个字符',
            trigger: 'blur'
          }
        ],
        cid: [
          {
            required: true,
            message: '请选择分类',
            trigger: 'change'
          }
        ],
        fileurl: [
          {
            required: true,
            message: '请上传文件',
            trigger: 'blur'
          }
        ],
        sort_id: [
          {
            type: 'number',
            message: '排序必须为数字',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  mounted() {
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchCategoryData()
  },
  methods: {
    fetchCategoryData() {
      const param = { type: 'hrtoolCategory' }
      getClassify(param)
        .then(response => {
          this.hrtoolCategory = response.data
          this.hrtoolCategoryLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          hrtoolAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/hrtool/list')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
          // console.log('error submit!!')
          return false
        }
      })
    },
    handlerFileSuccess(res, file) {
      if (res.code == 200) {
        this.form.fileurl = res.data.save_path
        this.$message({ type: 'success', message: '上传成功' })
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeFileUpload(file) {
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
    },
    goto(target) {
      this.$router.push(target)
    }
  }
}
</script>
<style scoped>
.thumb-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.thumb-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.thumb-uploader:hover {
  border-color: #409eff;
}
.thumb-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
.el-textarea,
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
}
</style>
