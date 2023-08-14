<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span style="font-weight: 600;color: #333;">
          基本信息
          <div class="must">必填</div>
        </span>
        <el-button
          v-if="is_edit === false"
          style="float: right; padding: 6px 18px;font-size: 13px;"
          type="primary"
          round
          @click="is_edit = !is_edit"
        >
          修改
        </el-button>
      </div>
      <div v-if="is_edit === false">
        <div class="photo">
          <img :src="photoUrl" alt="">
          <div style="color: #999;font-size: 14px;">个人照片</div>
        </div>
        <span class="item-row">姓名：{{ form.fullname }}</span>
        <span class="item-row">出生日期：{{ form.birthday }}</span>
        <span class="item-row">性别：{{ form.sex_ }}</span>
        <span class="item-row">工作经验：{{ form.experience_ }}</span>
        <span class="item-row">学历：{{ form.education_ }}</span>
        <span class="item-row">求职状态：{{ form.current_ }}</span>
        <span v-if="live_fields.marriage === true" class="item-row">
          婚姻状况：{{ form.marriage_ }}
        </span>
        <span v-if="live_fields.residence === true" class="item-row">
          现居住地：{{ form.residence | defaultFilter }}
        </span>
        <span v-if="live_fields.major === true" class="item-row">
          所学专业：{{ form.major_text | defaultFilter }}
        </span>
        <span v-if="live_fields.height === true" class="item-row">
          身高：{{ form.height | defaultFilter }}
        </span>
        <span v-if="live_fields.householdaddress === true" class="item-row">
          籍贯：{{ form.householdaddress | defaultFilter }}
        </span>
        <span v-if="live_fields.idcard === true" class="item-row">
          身份证号：{{ form.idcard | defaultFilter }}
        </span>
        <span v-if="live_fields.custom_field_1 === true" class="item-row">
          {{ custom_field_1_cn }}：{{ form.custom_field_1 | defaultFilter }}
        </span>
        <span v-if="live_fields.custom_field_2 === true" class="item-row">
          {{ custom_field_2_cn }}：{{ form.custom_field_2 | defaultFilter }}
        </span>
        <span v-if="live_fields.custom_field_3 === true" class="item-row">
          {{ custom_field_3_cn }}：{{ form.custom_field_3 | defaultFilter }}
        </span>
        <span class="item-row">
          手机号：{{ form.contact.mobile | defaultFilter }}
        </span>
        <span v-if="live_fields.contact.email === true" class="item-row">
          邮箱：{{ form.contact.email | defaultFilter }}
        </span>
        <span v-if="live_fields.contact.weixin === true" class="item-row">
          微信：{{ form.contact.weixin | defaultFilter }}
        </span>
        <span v-if="live_fields.contact.qq === true" class="item-row">
          QQ：{{ form.contact.qq | defaultFilter }}
        </span>
      </div>
      <el-form
        v-if="is_edit"
        ref="form"
        v-loading="infoLoading"
        :inline="true"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="false"
      >
        <el-form-item label="姓名" prop="fullname">
          <el-input v-model="form.fullname" />
        </el-form-item>
        <el-form-item label="性别" prop="sex">
          <el-radio-group v-model="form.sex">
            <el-radio
              v-for="item in options_sex"
              :key="item.id"
              :label="item.id"
            >
              {{ item.name }}
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="出生日期" prop="birthday">
          <el-date-picker
            v-model="form.birthday"
            type="month"
            :editable="false"
            :clearable="false"
            placeholder="选择出生日期"
            value-format="yyyy-MM"
            :picker-options="DateRanges"
            :default-value="defaultValue"
          />
        </el-form-item>
        <el-form-item label="最高学历" prop="education">
          <el-select v-model="form.education" placeholder="请选择">
            <el-option
              v-for="item in options_education"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="参加工作时间" prop="enter_job_time">
          <el-date-picker
            v-model="form.enter_job_time"
            type="month"
            :editable="false"
            :clearable="false"
            placeholder="选择参加工作时间"
            value-format="yyyy-MM"
            style="width: 330px"
            :disabled="enter_job_time_empty === true"
            :picker-options="jobDateRanges"
          />
          &nbsp;
          <el-checkbox
            v-model="enter_job_time_empty"
            @change="check_enter_job_time_empty"
          >
            应届生/无经验
          </el-checkbox>
        </el-form-item>
        <el-form-item label="求职状态" prop="current">
          <el-select v-model="form.current" placeholder="请选择">
            <el-option
              v-for="item in options_current"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="live_fields.marriage === true"
          label="婚姻状况"
          prop="marriage"
        >
          <el-radio-group v-model="form.marriage">
            <el-radio
              v-for="item in options_marriage"
              :key="item.id"
              :label="item.id"
            >
              {{ item.name }}
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="live_fields.residence === true"
          label="现居住地"
          prop="residence"
        >
          <el-input v-model="form.residence" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.major === true"
          label="所学专业"
          prop="major_arr"
        >
          <el-cascader
            v-model="form.major_arr"
            :options="options_major"
            :show-all-levels="false"
          />
        </el-form-item>
        <el-form-item
          v-if="live_fields.height === true"
          label="身高"
          prop="height"
        >
          <el-input v-model.number="form.height" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.householdaddress === true"
          label="籍贯"
          prop="householdaddress"
        >
          <el-input v-model="form.householdaddress" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.idcard === true"
          label="身份证号"
          prop="idcard"
        >
          <el-input v-model="form.idcard" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.custom_field_1 === true"
          :label="custom_field_1_cn"
          prop="custom_field_1"
        >
          <el-input v-model="form.custom_field_1" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.custom_field_2 === true"
          :label="custom_field_2_cn"
          prop="custom_field_2"
        >
          <el-input v-model="form.custom_field_2" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.custom_field_3 === true"
          :label="custom_field_3_cn"
          prop="custom_field_3"
        >
          <el-input v-model="form.custom_field_3" />
        </el-form-item>
        <el-form-item label="手机号" prop="contact.mobile">
          <el-input v-model="form.contact.mobile" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.contact.weixin === true"
          label="微信"
          prop="contact.weixin"
        >
          <el-input v-model="form.contact.weixin" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.contact.email === true"
          label="邮箱"
          prop="contact.email"
        >
          <el-input v-model="form.contact.email" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.contact.qq === true"
          label="QQ"
          prop="contact.qq"
        >
          <el-input v-model="form.contact.qq" />
        </el-form-item>
        <div class="clearfix" />
        <el-form-item label="照片" prop="photo_img">
          <div style="position: relative">
            <div v-if="form.photo_img" class="transparent" @click="logoDel">
              <i class="el-icon-delete" />
              删除
            </div>
            <el-upload
              class="photo-uploader"
              :action="apiUpload"
              :headers="headers"
              :show-file-list="false"
              :on-success="handlePhotoSuccess"
              :before-upload="beforePhotoUpload"
            >
              <img v-if="form.photo_img" :src="photoUrl" class="photo">
              <i v-else class="el-icon-plus photo-uploader-icon" />
            </el-upload>
          </div>
          <span class="smalltip">
            <i class="el-icon-info" />
            建议尺寸120*120
          </span>
        </el-form-item>
        <div class="clearfix" />
        <el-form-item label=" ">
          <el-button
            type="primary"
            :loading="submitLoading"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
          <el-button type="info" plain @click="formCancel()">
            取消
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { validMobile, validEmail } from '@/utils/validate'
import { resumeBasic } from '@/api/resume'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  filters: {
    defaultFilter(data) {
      return data == '' ? '未填写' : data
    }
  },
  props: ['id', 'field_rule'],
  data() {
    var validateContactMobile = (rule, value, callback) => {
      if (!validMobile(value)) {
        callback(new Error('请输入正确的手机号'))
      } else {
        callback()
      }
    }
    var validateContactEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error('请输入正确的邮箱'))
      } else {
        callback()
      }
    }
    var validateEnterjobtime = (rule, value, callback) => {
      if (this.enter_job_time_empty === true) {
        callback()
      } else if (value == '') {
        callback(new Error('请选择参加工作时间'))
      } else {
        callback()
      }
    }
    return {
      DateRanges: this.birthdayDateRange(),
      jobDateRanges: this.enterJobDateRange(),
      defaultValue: this.defaultValueFun(),
      is_edit: false,
      submitLoading: false,
      infoLoading: true,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      live_fields: {
        marriage: true,
        residence: true,
        major: true,
        height: true,
        householdaddress: true,
        idcard: true,
        custom_field_1: true,
        custom_field_2: true,
        custom_field_3: true,
        contact: {
          email: true,
          weixin: true,
          qq: true
        }
      },
      custom_field_1_cn: ' ',
      custom_field_2_cn: ' ',
      custom_field_3_cn: ' ',
      enter_job_time_empty: false,
      form: {
        fullname: '',
        sex: '',
        birthday: '',
        residence: '',
        height: '',
        marriage: '',
        education: '',
        enter_job_time: '',
        current: '',
        householdaddress: '',
        major_arr: [],
        major1: 0,
        major2: 0,
        idcard: '',
        custom_field_1: '',
        custom_field_2: '',
        custom_field_3: '',
        photo_img: '',
        contact: {
          mobile: '',
          email: '',
          weixin: '',
          qq: ''
        }
      },
      photoUrl: '',
      options_sex: [],
      options_education: [],
      options_major: [],
      options_current: [],
      options_marriage: [],
      rules: {
        fullname: [
          { required: true, message: '请输入姓名', trigger: 'blur' },
          {
            max: 30,
            message: '长度在 1 到 15 个字',
            trigger: 'blur'
          }
        ],
        sex: [{ required: true, message: '请选择性别', trigger: 'change' }],
        birthday: [
          {
            required: true,
            message: '请选择出生日期',
            trigger: 'change'
          }
        ],
        education: [
          {
            required: true,
            message: '请选择最高学历',
            trigger: 'change'
          }
        ],
        enter_job_time: [
          { validator: validateEnterjobtime, trigger: 'change' }
        ],
        current: [
          {
            required: true,
            message: '请选择求职状态',
            trigger: 'change'
          }
        ],
        // photo_img: [{ required: true, message: '请上传照片', trigger: 'blur' }],
        residence: [
          {
            required: true,
            message: '请填写现居住地',
            trigger: 'blur'
          }
        ],
        marriage: [
          {
            required: true,
            message: '请选择婚姻状况',
            trigger: 'change'
          }
        ],
        major_arr: [
          {
            required: true,
            message: '请选择所学专业',
            trigger: 'change'
          }
        ],
        height: [
          { required: true, message: '请填写身高', trigger: 'blur' },
          {
            type: 'number',
            message: '身高必须为数字',
            trigger: 'blur'
          }
        ],
        householdaddress: [
          { required: true, message: '请填写籍贯', trigger: 'blur' }
        ],
        idcard: [
          {
            required: true,
            message: '请填写身份证号',
            trigger: 'blur'
          }
        ],
        custom_field_1: [
          {
            required: true,
            message: `请填写custom_field_1`,
            trigger: 'blur'
          }
        ],
        custom_field_2: [
          {
            required: true,
            message: `请填写custom_field_2`,
            trigger: 'blur'
          }
        ],
        custom_field_3: [
          {
            required: true,
            message: `请填写custom_field_3`,
            trigger: 'blur'
          }
        ],
        contact: {
          mobile: [
            {
              required: true,
              message: '请填写手机号',
              trigger: 'blur'
            },
            { validator: validateContactMobile, trigger: 'blur' }
          ],
          email: [
            {
              required: true,
              message: '请填写邮箱',
              trigger: 'blur'
            },
            { validator: validateContactEmail, trigger: 'blur' }
          ],
          weixin: [
            {
              required: true,
              message: '请填写微信',
              trigger: 'blur'
            }
          ],
          qq: [{ required: true, message: '请填写QQ', trigger: 'blur' }]
        }
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  mounted() { },
  created() {
    this.getFieldRule(this.field_rule)
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchInfo()
  },
  methods: {
    logoDel(){
      this.photoUrl = ''
      this.form.photo_img = ''
    },
    getFieldRule(response){
      console.log(response)
      const { Resume, ResumeContact } = {
        ...response
      }
      const extra_rule = {
        Resume,
        ResumeContact
      }
      this.custom_field_1_cn = extra_rule.Resume.custom_field_1.field_cn
      this.custom_field_2_cn = extra_rule.Resume.custom_field_2.field_cn
      this.custom_field_3_cn = extra_rule.Resume.custom_field_3.field_cn
      if (extra_rule.Resume.marriage.is_display == 0) {
        this.live_fields.marriage = false
        this.rules.marriage = []
      } else if (extra_rule.Resume.marriage.is_require == 0) {
        this.rules.marriage = []
      }
      if (extra_rule.Resume.residence.is_display == 0) {
        this.live_fields.residence = false
        this.rules.residence = []
      } else if (extra_rule.Resume.residence.is_require == 0) {
        this.rules.residence = []
      }
      if (extra_rule.Resume.major.is_display == 0) {
        this.live_fields.major = false
        this.rules.major_arr = []
      } else if (extra_rule.Resume.major.is_require == 0) {
        this.rules.major_arr = []
      }
      if (extra_rule.Resume.height.is_display == 0) {
        this.live_fields.height = false
        this.rules.height = []
      } else if (extra_rule.Resume.height.is_require == 0) {
        this.rules.height = []
      }
      if (extra_rule.Resume.householdaddress.is_display == 0) {
        this.live_fields.householdaddress = false
        this.rules.householdaddress = []
      } else if (extra_rule.Resume.householdaddress.is_require == 0) {
        this.rules.householdaddress = []
      }
      if (extra_rule.Resume.idcard.is_display == 0) {
        this.live_fields.idcard = false
        this.rules.idcard = []
      } else if (extra_rule.Resume.idcard.is_require == 0) {
        this.rules.idcard = []
      }
      if (extra_rule.Resume.custom_field_1.is_display == 0) {
        this.live_fields.custom_field_1 = false
        this.rules.custom_field_1 = []
      } else if (extra_rule.Resume.custom_field_1.is_require == 0) {
        this.rules.custom_field_1 = []
      } else {
        this.rules.custom_field_1[0].message = this.rules.custom_field_1[0].message.replace(
          'custom_field_1',
          this.custom_field_1_cn
        )
      }
      if (extra_rule.Resume.custom_field_2.is_display == 0) {
        this.live_fields.custom_field_2 = false
        this.rules.custom_field_2 = []
      } else if (extra_rule.Resume.custom_field_2.is_require == 0) {
        this.rules.custom_field_2 = []
      } else {
        this.rules.custom_field_2[0].message = this.rules.custom_field_2[0].message.replace(
          'custom_field_2',
          this.custom_field_2_cn
        )
      }
      if (extra_rule.Resume.custom_field_3.is_display == 0) {
        this.live_fields.custom_field_3 = false
        this.rules.custom_field_3 = []
      } else if (extra_rule.Resume.custom_field_3.is_require == 0) {
        this.rules.custom_field_3 = []
      } else {
        this.rules.custom_field_3[0].message = this.rules.custom_field_3[0].message.replace(
          'custom_field_3',
          this.custom_field_3_cn
        )
      }
      if (extra_rule.ResumeContact.email.is_display == 0) {
        this.live_fields.contact.email = false
        this.rules.contact.email = []
      } else if (extra_rule.ResumeContact.email.is_require == 0) {
        this.rules.contact.email = []
      }
      if (extra_rule.ResumeContact.weixin.is_display == 0) {
        this.live_fields.contact.weixin = false
        this.rules.contact.weixin = []
      } else if (extra_rule.ResumeContact.weixin.is_require == 0) {
        this.rules.contact.weixin = []
      }
      if (extra_rule.ResumeContact.qq.is_display == 0) {
        this.live_fields.contact.qq = false
        this.rules.contact.qq = []
      } else if (extra_rule.ResumeContact.qq.is_require == 0) {
        this.rules.contact.qq = []
      }
    },
    // PC端创建简历日期选择往前推到16岁 zdq
    birthdayDateRange(){
      return {
        disabledDate(time){
          var myDate = new Date()
          return time.getTime() > new Date(new Date(myDate.getFullYear(), 11).getTime() - 16 * 365 * 24 * 3600 * 1000)
        }
      }
    },
    // 【ID1000572】【bug】后台修改简历，基本资料参加工作时间无限制 yx - 2023.03.02
    enterJobDateRange(){
      return {
        disabledDate(time){
          return time.getTime() > new Date().getTime()
        }
      }
    },
    // PC端创建简历日期选择往前推到16岁 zdq
    defaultValueFun() {
      const nowDate = new Date()
      const nowYear = nowDate.getFullYear()
      const minBirthdayYear = nowYear - 16
      return new Date(minBirthdayYear, 11)
    },
    fetchInfo() {
      this.infoLoading = true
      getClassify({
        type: 'resumeSex,education,major,current,marriage'
      }).then(response => {
        this.options_sex = [...response.data.resumeSex]
        this.options_education = [...response.data.education]
        this.options_major = [...response.data.major]
        this.options_current = [...response.data.current]
        this.options_marriage = [...response.data.marriage]
        const param = {
          id: this.id
        }
        return resumeBasic(param, 'get')
      })
        .then(response => {
          this.form = { ...this.form, ...response.data.info }
          this.form.height =
            this.form.height != '' ? parseInt(this.form.height) : ''
          if (response.data.info.contact != null) {
            this.form.contact = { ...response.data.info.contact }
          }
          this.photoUrl = response.data.photoUrl
          if (this.form.enter_job_time == '') {
            this.enter_job_time_empty = true
          }
          // 【bug】前台有专业，后台不显示
          // zdq 2022-11-03
          // 修改完成后无法回显需要将数组重置为空
          // 新增  this.form.major_arr = []
          this.form.major_arr = []
          if (this.form.major1 != 0) {
            this.form.major_arr.push(this.form.major1)
          }
          if (this.form.major2 != 0) {
            this.form.major_arr.push(this.form.major2)
          }
          this.infoLoading = false
          this.$emit('setLoading', 'resumeBasic')
        })
        .catch(() => { })
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          const tmp_major_arr = this.form.major_arr
          insertData.major1 = tmp_major_arr[0]
          insertData.major2 =
            tmp_major_arr[1] !== undefined ? tmp_major_arr[1] : 0
          resumeBasic(insertData)
            .then(response => {
              if (response.code == 200) {
                this.is_edit = false
                this.submitLoading = false
                this.$message.success(response.message)
                this.$emit('resumeDetails')
                this.fetchInfo()
                return true
              } else {
                this.$emit('resumeDetails')
                this.$message.error(response.message)
                return false
              }
            })
            .catch(() => {
              this.submitLoading = false
              this.$emit('resumeDetails')
            })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    formCancel() {
      this.is_edit = !this.is_edit
      this.fetchInfo()
    },
    handlePhotoSuccess(res, file) {
      if (res.code == 200) {
        this.photoUrl = URL.createObjectURL(file.raw)
        this.form.photo_img = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforePhotoUpload(file) {
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
    check_enter_job_time_empty(val) {
      if (val === true) {
        this.form.enter_job_time = ''
      }
    }
  }
}
</script>

<style lang="scss" scoped>

.transparent{
  cursor:pointer;
  z-index: 1;
  font-size: 12px;
  border-radius: 3px;
  text-align: center;
  line-height: 25px;
  position: absolute;
  bottom: 15px;
  color: #d5cece;
  left: 1px;
  width: 120px;
  height: 25px;
  background: rgba(0, 0, 0, 0.4);
}

.photo{
  text-align: center;
  display: inline-block;
  float:right;
  width: 100px;
  height:100px;
  img{
    width: 100%;
    height: 100%;
    border-radius: 8px;
  }
}
.must{
  display: inline-block;
  width: 38.6px;
  height: 19.3px;
  line-height: 19.3px;
  text-align: center;
  background: #feefea;
  border-radius: 2px;
  font-size: 12px;
  font-weight: 500;
  color: #fe6135;
  -webkit-transform: scale(.83);
  transform: scale(.83);
}
.photo-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.photo-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.photo-uploader:hover {
  border-color: #409eff;
}
.photo-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
.el-form-item {
  margin-bottom: 28px;
  width: 600px;
}
.item-row {
  width: 450px;
  color: #666;
  display: inline-block;
  margin-bottom: 16px;
  font-size: 14px;
}
.el-input,
.el-select,
.el-cascader,
.el-textarea {
  width: 450px;
}
</style>
