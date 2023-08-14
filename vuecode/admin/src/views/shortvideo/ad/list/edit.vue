<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑广告</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/shortvideo/ad')"
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
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="广告标题" prop="title">
          <el-input v-model="form.title" />
        </el-form-item>
        <el-form-item label="广告位" prop="cid">
          <el-cascader
            v-model="form.cid"
            :options="adCategory"
            :props="{ expandTrigger: 'hover' }"
            @change="handleChange"
          />
        </el-form-item>
        <el-form-item required label="本地上传">
          <el-upload
            class="thumb-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleThumbSuccess"
            :before-upload="beforeThumbUpload"
          >
            <img v-if="form.imageid" :src="imageSrc" class="thumb">
            <i v-else class="el-icon-plus thumb-uploader-icon" />
          </el-upload>
          <span v-if="size_text != ''" class="smalltip">
            <i class="el-icon-info" />
            建议尺寸{{ size_text }}
          </span>
        </el-form-item>
        <el-form-item label="或图片地址" prop="imageurl">
          <el-input v-model="form.imageurl" />
        </el-form-item>
        <el-form-item label="图片说明文字" prop="explain">
          <el-input v-model="form.explain" />
        </el-form-item>
        <el-form-item label="开始时间" prop="starttime">
          <el-date-picker
            v-model="form.starttime"
            type="datetime"
            placeholder="请选择开始时间"
            format="yyyy-MM-dd HH:mm"
          />
        </el-form-item>
        <el-form-item label="结束时间" prop="deadline">
          <el-date-picker
            v-model="form.deadline"
            type="datetime"
            placeholder="请选择结束时间"
            format="yyyy-MM-dd HH:mm"
          />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" />
        </el-form-item>
        <el-form-item label="目标地址" prop="target">
          <el-radio-group v-model="form.target">
            <el-radio label="0">自定义链接</el-radio>
            <el-radio label="1">跳转站内</el-radio>
            <el-radio label="2">绑定企业</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="form.target == 0"
          label="自定义跳转链接"
          prop="link_url"
        >
          <el-input v-model="form.link_url" />
        </el-form-item>
        <el-form-item
          v-if="form.target == 1"
          label="跳转站内链接"
          prop="inner_link"
        >
          <el-select v-model="form.inner_link" placeholder="请选择">
            <el-option
              v-for="item in inner_options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.target == 1"
          label="站内链接参数"
          prop="inner_link_params"
        >
          <el-input
            v-model.number="form.inner_link_params"
            placeholder="如无参数请留空"
          />
          <span class="smalltip">
            <i class="el-icon-info" />
            例如：id=1&keyword=74cms
          </span>
        </el-form-item>
        <el-form-item v-if="form.target == 2" label="企业ID" prop="company_id">
          <el-input v-model.number="form.company_id" />
        </el-form-item>
        <el-form-item label="是否显示">
          <el-switch v-model="form.is_display" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/shortvideo/ad')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import { validUrl } from '@/utils/validate'
import { adEdit, adInnerLinkOptions } from '@/api/shortvideo'
import { getClassify } from '@/api/classify'
import apiArr from '@/api'
import {adCategoryList} from "@/api/shortvideo";

export default {
  data() {
    var validateDeadline = (rule, value, callback) => {
      if (value == '') {
        callback()
      }
      if (this.form.starttime != '' && this.form.starttime > value) {
        callback(new Error('开始时间不能大于结束时间'))
      } else {
        callback()
      }
    }
    var validateImage = (rule, value, callback) => {
      if (this.form.imageid == '' && value == '') {
        callback(new Error('请上传图片或填写本地图片地址'))
      } else {
        callback()
      }
    }
    var validateLinkUrl = (rule, value, callback) => {
      if (value == '') {
        callback()
      }
      if (!validUrl(value)) {
        callback(new Error('请输入正确的网址'))
      } else {
        callback()
      }
    }
    var validateInnerLink = (rule, value, callback) => {
      if (this.form.target == 1 && value == '') {
        callback(new Error('请选择站内链接'))
      } else {
        callback()
      }
    }
    var validateCompanyId = (rule, value, callback) => {
      if (this.form.target == 2 && value == 0) {
        callback(new Error('请填写企业ID'))
      } else {
        callback()
      }
    }
    return {
      size_text: '',
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      adCategory: [],
      inner_options: [],
      form: {
        title: '',
        cid: [],
        imageid: '',
        imageurl: '',
        explain: '',
        starttime: '',
        deadline: '',
        sort_id: 0,
        target: '0',
        link_url: '',
        inner_link: '',
        inner_link_params: '',
        company_id: 0,
        is_display: true
      },
      imageSrc: '',
      rules: {
        title: [
          {
            required: true,
            message: '请输入广告标题',
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
            message: '请选择广告位',
            trigger: 'change'
          }
        ],
        imageurl: [{ validator: validateImage, trigger: 'blur' }],
        explain: [
          {
            max: 255,
            message: '长度在 0 到 255 个字符',
            trigger: 'blur'
          }
        ],
        starttime: [
          {
            required: true,
            message: '请选择开始时间',
            trigger: 'blur'
          }
        ],
        deadline: [{ validator: validateDeadline, trigger: 'blur' }],
        sort_id: [
          {
            type: 'number',
            message: '排序必须为数字',
            trigger: 'blur'
          }
        ],
        link_url: [
          {
            max: 200,
            message: '长度在 0 到 200 个字符',
            trigger: 'blur'
          },
          { validator: validateLinkUrl, trigger: 'blur' }
        ],
        inner_link: [{ validator: validateInnerLink, trigger: 'change' }],
        company_id: [
          {
            type: 'number',
            message: '企业ID必须为数字',
            trigger: 'blur'
          },
          { validator: validateCompanyId, trigger: 'blur' }
        ]
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  mounted() {},
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchInfo()
  },
  methods: {
    handleChange(e) {
      let list = []
      for (const key in this.adCategory) {
        const item = this.adCategory[key]
        if (item.value == e[0]) {
          list = item.children
          break
        }
      }
      for (const key in list) {
        const item = list[key]
        if (item.value == e[1]) {
          this.size_text = item.w + '*' + item.h
          break
        }
      }
    },
    fetchInfo() {
      adCategoryList()
        .then(response => {
          this.adCategory = response.data
          return adInnerLinkOptions()
        })
        .then(response => {
          this.inner_options = response.data
          const param = { id: this.$route.query.id }
          return adEdit(param, 'get')
        })
        .then(response => {
          this.form = { ...response.data.info }
          this.form.target = this.form.target + ''
          this.form.starttime = this.form.starttime * 1000
          this.form.deadline = this.form.deadline * 1000
          this.form.is_display = this.form.is_display == 1
          this.imageSrc = response.data.imageSrc
          this.handleChange(this.form.cid)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_display = insertData.is_display === true ? 1 : 0
          if (insertData.starttime) {
            const d = new Date(insertData.starttime)
            insertData.starttime =
              d.getFullYear() +
              '-' +
              (d.getMonth() + 1) +
              '-' +
              d.getDate() +
              ' ' +
              d.getHours() +
              ':' +
              d.getMinutes()
          }
          if (insertData.deadline) {
            const d = new Date(insertData.deadline)
            insertData.deadline =
              d.getFullYear() +
              '-' +
              (d.getMonth() + 1) +
              '-' +
              d.getDate() +
              ' ' +
              d.getHours() +
              ':' +
              d.getMinutes()
          }
          adEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/shortvideo/ad')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    handleThumbSuccess(res, file) {
      if (res.code == 200) {
        this.imageSrc = URL.createObjectURL(file.raw)
        this.form.imageid = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeThumbUpload(file) {
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
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
}
</style>
