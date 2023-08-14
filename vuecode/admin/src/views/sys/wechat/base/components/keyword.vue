<template>
  <div class="app-container">
    <el-table
      :data="list"
    >
      <el-table-column
        label="关键词"
        prop="word"
        min-width="100"
      />
      <el-table-column
        label="返回文字"
        prop="return_text"
        min-width="200"
      />
      <el-table-column
        align="center"
        label="返回图片"
        min-width="110"
      >
        <template slot-scope="scope">
          <img
            :src="scope.row.return_img"
            height="100"
          >
        </template>
      </el-table-column>
      <el-table-column
        label="返回链接"
        prop="return_link"
        min-width="240"
      />
      <el-table-column
        fixed="right"
        label="操作"
        min-width="150"
      >
        <template slot-scope="scope">
          <el-button
            size="small"
            type="primary"
            @click="funEdit(scope.$index, scope.row)"
          >
            编辑
          </el-button>
          <el-button
            size="small"
            type="danger"
            @click="funDelete(scope.$index, scope.row)"
          >
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-row :gutter="20">
      <el-col :span="8">
        <el-button
          size="small"
          type="primary"
          @click="funAdd"
        >
          添加
        </el-button>
        <el-button
          size="small"
          type="danger"
        >
          删除
        </el-button>
      </el-col>
      <el-col
        :span="16"
        style="text-align: right;"
      >
        <el-pagination
          background
          :current-page="currentPage"
          :page-sizes="[10,15, 20, 30, 40]"
          :page-size="pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </el-row>

    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="30%"
      :close-on-click-modal="false"
    >
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="false"
      >
        <el-form-item
          label="关键词"
          prop="word"
        >
          <el-input v-model="form.word" />
        </el-form-item>
        <el-form-item
          label="返回文字"
          prop="return_text"
        >
          <el-input v-model="form.return_text" />
        </el-form-item>
        <el-form-item
          label="返回图片"
          prop="return_img"
        >
          <el-upload
            class="logo-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleSuccess"
            :before-upload="beforeUpload"
          >
            <img
              v-if="form.return_img"
              :src="form.return_img"
              class="logo"
            >
            <i
              v-else
              class="el-icon-plus logo-uploader-icon"
            />
          </el-upload>
          <el-button v-if="form.return_img" type="text" @click="form.return_img='';form.return_img_mediaid=''">[删除]</el-button>
        </el-form-item>
        <el-form-item
          label="返回链接"
          prop="return_link"
        >
          <el-input v-model="form.return_link" />
        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
          <el-button @click="closeDialog">
            取 消
          </el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import {
  wechatKeywordList,
  wechatKeywordDelete,
  wechatKeywordAdd,
  wechatKeywordEdit
} from '@/api/wechat_keyword'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  data() {
    return {
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.uploadWechatMedia,
      dialogFormVisible: false,
      infoLoading: true,
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      form: {
        id: '',
        word: '',
        return_text: '',
        return_img: '',
        return_img_mediaid: '',
        return_link: ''
      },
      rules: {
        word: [
          { required: true, message: '请输入关键词', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ]
      }
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
    this.fetchData()
  },
  methods: {
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    fetchData() {
      this.listLoading = true
      wechatKeywordList({}, 'get')
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => {})
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          wechatKeywordAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.fetchData()
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
          wechatKeywordEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.fetchData()
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
      if (parseInt(insertData.id) > 0) {
        that.editSave(insertData, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        that.addSave(data, formName)
      }
    },
    funAdd() {
      this.form = {
        id: '',
        word: '',
        return_text: '',
        return_img: '',
        return_img_mediaid: '',
        return_link: ''
      }
      this.dialogTitle = '添加关键词'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.form = {
        id: row.id,
        word: row.word,
        return_text: row.return_text,
        return_img: row.return_img,
        return_img_mediaid: row.return_img_mediaid,
        return_link: row.return_link
      }
      this.dialogTitle = '编辑关键词'
      this.dialogFormVisible = true
    },
    funDelete(index, row) {
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          wechatKeywordDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    handleSuccess(res, file) {
      if (res.code === 200) {
        this.form.return_img = res.data.url
        this.form.return_img_mediaid = res.data.media_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeUpload(file) {
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
.el-input,
.el-input-group {
  width: 80%;
}
.logo-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  min-width: 122px;
  height: 122px;
}
.logo-uploader img {
  min-width: 120px;
  height: 120px;
  border-radius: 6px;
}
.logo-uploader:hover {
  border-color: #409eff;
}
.logo-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  min-width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
</style>
