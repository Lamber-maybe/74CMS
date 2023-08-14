<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="32%"
      :rules="rules"
      :inline-message="false"
      size="small"
      :inline="false"
    >
      <el-row>
        <el-col :span="11">
          <el-form-item label="套餐名称" prop="name">
            <el-input v-model="form.name" class="small" />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="使用视频面试">
            <el-radio-group v-model="form.enable_video_interview">
              <el-radio :label="1">允许</el-radio>
              <el-radio :label="0">不允许</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="价格" prop="expense">
            <el-input
              v-model.number="form.expense"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'expense')"
            >
              <template slot="append">元</template>
            </el-input>
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="使用微海报">
            <el-radio-group v-model="form.enable_poster">
              <el-radio :label="1">允许</el-radio>
              <el-radio :label="0">不允许</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="时长" prop="days">
            <el-input
              v-model.number="form.days"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'days')"
            >
              <template slot="append">天</template>
            </el-input>
            <el-tooltip
              class="item"
              effect="dark"
              content="0表示无限期"
              placement="top-start"
            >
              <i class="el-icon-info" />
            </el-tooltip>
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="收到简历免费查看">
            <el-radio-group v-model="form.show_apply_contact">
              <el-radio :label="1">允许</el-radio>
              <el-radio :label="0">不允许</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="开启限时特惠">
            <el-radio-group
              v-model="form.preferential_open"
              @change="fun_change_preferential_expense"
            >
              <el-radio :label="1">开启</el-radio>
              <el-radio :label="0">关闭</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="是否推荐">
            <el-radio-group v-model="form.recommend">
              <el-radio :label="1">推荐</el-radio>
              <el-radio :label="0">不推荐</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="特惠购买价" prop="preferential_expense">
            <el-input
              v-model.number="form.preferential_expense"
              type="number"
              class="small"
              min="0"
              :disabled="form.preferential_open == 0"
              @blur="format_number(0, 'preferential_expense')"
            >
              <template slot="append">元</template>
            </el-input>
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="允许申请">
            <el-radio-group v-model="form.is_apply">
              <el-radio :label="1">允许</el-radio>
              <el-radio :label="0">不允许</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="特惠时间" prop="preferential_expense_timerange">
            <el-date-picker
              v-model="form.preferential_expense_timerange"
              type="daterange"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              value-format="yyyy-MM-dd"
              :disabled="form.preferential_open == 0"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="是否显示">
            <el-radio-group v-model="form.is_display">
              <el-radio :label="1">显示</el-radio>
              <el-radio :label="0">隐藏</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="下载简历点数" prop="download_resume_point">
            <el-input
              v-model.number="form.download_resume_point"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'download_resume_point')"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="下载简历上限" prop="download_resume_max_perday">
            <el-input
              v-model.number="form.download_resume_max_perday"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'download_resume_max_perday')"
            >
              <template slot="append">份 / 天</template>
            </el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="赠送积分数" prop="gift_point">
            <el-input
              v-model.number="form.gift_point"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'gift_point')"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="免费刷新职位" prop="refresh_jobs_free_perday">
            <el-input
              v-model.number="form.refresh_jobs_free_perday"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'refresh_jobs_free_perday')"
            >
              <template slot="append">次 / 天</template>
            </el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="同时在招职位数" prop="jobs_meanwhile">
            <el-input
              v-model.number="form.jobs_meanwhile"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'jobs_meanwhile')"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="套餐增值包折扣" prop="service_added_discount">
            <el-input
              v-model.number="form.service_added_discount"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'service_added_discount')"
            />
            <el-tooltip class="item" effect="dark" placement="top-start">
              <div slot="content">
                0表示无折扣
                <br>
                例：七五折请填写7.5
              </div>
              <i class="el-icon-info" />
            </el-tooltip>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="排序" prop="sort_id" style="display: block">
            <el-input
              v-model.number="form.sort_id"
              type="number"
              class="mini"
              min="0"
              @blur="format_number(0, 'sort_id')"
            />
            <el-tooltip
              class="item"
              effect="dark"
              content="数字越大越靠前"
              placement="top-start"
            >
              <i class="el-icon-info" />
            </el-tooltip>
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="图标" prop="icon" style="display: block">
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
            <el-tooltip
              class="item"
              effect="dark"
              content="建议尺寸28*28"
              placement="top-start"
            >
              <i class="el-icon-info" />
            </el-tooltip>
            <el-button type="text" @click="delIcon">[删除]</el-button>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label=" ">
            <el-button type="primary" @click="onSubmit('form')">保存</el-button>
            <el-button @click="closeDialog">取 消</el-button>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import { setmealAdd, setmealEdit } from '@/api/setmeal'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  props: ['id'],
  data() {
    var validateLowExpenseTime = (rule, value, callback) => {
      if (this.form.preferential_open == 1 && value.length == 0) {
        callback(new Error('请选择特惠时间'))
      }
      callback()
    }
    return {
      infoLoading: false,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      form: {
        preferential_open: 0,
        preferential_expense_timerange: [],
        id: 0,
        name: '',
        is_display: 1,
        is_apply: 1,
        days: 0,
        expense: 0,
        preferential_expense: 0,
        service_added_discount: 0,
        jobs_meanwhile: 0,
        refresh_jobs_free_perday: 0,
        download_resume_point: 0,
        download_resume_max_perday: 0,
        enable_video_interview: 1,
        enable_poster: 1,
        recommend: 1,
        gift_point: 0,
        show_apply_contact: 1,
        sort_id: 0,
        preferential_expense_start: '',
        preferential_expense_end: '',
        icon: 0
      },
      iconUrl: '',
      rules: {
        name: [
          { required: true, message: '请填写套餐名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        expense: [
          { required: true, message: '请填写价格', trigger: 'blur' }
        ],
        days: [
          { required: true, message: '请填写时长', trigger: 'blur' },
          { type: 'number', message: '时长只能填写数字', trigger: 'blur' }
        ],
        preferential_expense_timerange: [
          { validator: validateLowExpenseTime, trigger: 'blur' }
        ],
        download_resume_point: [
          { required: true, message: '请填写下载简历点数', trigger: 'blur' },
          {
            type: 'number',
            message: '下载简历点数只能填写数字',
            trigger: 'blur'
          }
        ],
        gift_point: [
          { required: true, message: '请填写赠送积分数', trigger: 'blur' },
          {
            type: 'number',
            message: '赠送积分数只能填写数字',
            trigger: 'blur'
          }
        ],
        jobs_meanwhile: [
          { required: true, message: '请填写同时在招职位数', trigger: 'blur' },
          {
            type: 'number',
            message: '同时在招职位数只能填写数字',
            trigger: 'blur'
          }
        ],
        download_resume_max_perday: [
          {
            required: true,
            message: '请填写下载简历数上限',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '下载简历数上限只能填写数字',
            trigger: 'blur'
          }
        ],
        refresh_jobs_free_perday: [
          {
            required: true,
            message: '请填写免费刷新职位次数',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '免费刷新职位次数只能填写数字',
            trigger: 'blur'
          }
        ],
        service_added_discount: [
          {
            required: true,
            message: '请填写套餐增值包折扣',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '套餐增值包折扣只能填写数字',
            trigger: 'blur'
          }
        ],
        sort_id: [
          { required: true, message: '请填写排序', trigger: 'blur' },
          {
            type: 'number',
            message: '排序只能填写数字',
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
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      const id = this.id
      if (id > 0) {
        const param = { id }
        setmealEdit(param, 'get')
          .then(response => {
            Object.assign(this.form, { ...response.data.info })
            if (
              response.data.info.preferential_expense_start != 0 &&
              response.data.info.preferential_expense_end != 0
            ) {
              this.form.preferential_expense_timerange = [
                response.data.info.preferential_expense_start,
                response.data.info.preferential_expense_end
              ]
            } else {
              this.form.preferential_expense_timerange = []
            }
            this.iconUrl = response.data.iconUrl
            this.infoLoading = false
          })
          .catch(() => {})
      } else {
        this.infoLoading = false
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          setmealAdd(insertData)
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
          setmealEdit(insertData)
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
      if (insertData.preferential_expense_timerange.length != 0) {
        insertData.preferential_expense_start =
          insertData.preferential_expense_timerange[0]
        insertData.preferential_expense_end =
          insertData.preferential_expense_timerange[1]
      } else {
        insertData.preferential_expense_start = 0
        insertData.preferential_expense_end = 0
      }
      if (parseInt(insertData.id) > 0) {
        that.editSave(insertData, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        that.addSave(data, formName)
      }
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    },
    format_number(default_val, field) {
      if (this.form[field] == '' || parseInt(this.form[field]) < default_val) {
        this.form[field] = default_val
      }
    },
    fun_change_preferential_expense(val) {
      if (val == 0) {
        this.form.preferential_expense_timerange = []
        this.form.preferential_expense = 0
      }
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
    },
    delIcon(){
      this.form.icon = 0
      this.iconUrl = ''
    }
  }
}
</script>
<style scoped>
.el-date-editor,
.el-input,
.el-input-group {
  width: 90%;
}
.el-form-item--small .el-form-item__content,
.el-form-item--small .el-form-item__label {
  width: 100%;
}
.icon-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 2px;
  cursor: pointer;
  width: 60px;
  height: 60px;
}
.icon-uploader img {
  width: 58px;
  height: 58px;
  border-radius: 2px;
}
.icon-uploader:hover {
  border-color: #409eff;
}
.icon-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 58px;
  height: 58px;
  line-height: 58px;
  text-align: center;
}
</style>
