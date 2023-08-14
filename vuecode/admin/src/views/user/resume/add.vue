<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      :inline="true"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="false"
    >
      <el-divider content-position="left">会员信息</el-divider>
      <el-form-item label="用户名" prop="member.username">
        <el-input v-model="form.member.username" />
      </el-form-item>
      <el-form-item label="手机号" prop="member.mobile">
        <el-input v-model="form.member.mobile" />
      </el-form-item>
      <el-form-item label="密码" prop="member.password" class="is-required">
        <el-input v-model="form.member.password" show-password />
      </el-form-item>
      <el-divider content-position="left">简历信息</el-divider>
      <el-form-item label="姓名" prop="fullname">
        <el-input v-model="form.fullname" />
      </el-form-item>
      <el-form-item label="性别" prop="sex">
        <el-radio-group v-model="form.sex">
          <el-radio v-for="item in options_sex" :key="item.id" :label="item.id">
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
          :picker-options="DateRanges"
          :default-value="defaultValue"
          value-format="yyyy-MM"
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
      <div class="clearfix" />
      <el-form-item label="照片" prop="photo_img">
        <el-upload
          class="photo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handlePhotoSuccess"
          :before-upload="beforePhotoUpload"
        >
          <img v-if="form.photo_img" :src="photoUrl" class="photo" />
          <i v-else class="el-icon-plus photo-uploader-icon" />
        </el-upload>
        <span class="smalltip">
          <i class="el-icon-info" />
          建议尺寸120*120
        </span>
      </el-form-item>
      <el-divider content-position="left">求职意向</el-divider>
      <el-form-item label="工作性质" prop="intention.nature">
        <el-radio-group v-model="form.intention.nature">
          <el-radio
            v-for="(item, index) in options_nature"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="意向职位" prop="intention.jobcategory_arr">
        <el-cascader
          v-model="form.intention.jobcategory_arr"
          :options="options_jobcategory"
          :show-all-levels="false"
        />
      </el-form-item>
      <el-form-item label="工作地区" prop="intention.citycategory_arr">
        <el-cascader
          v-model="form.intention.citycategory_arr"
          :options="options_citycategory"
          :show-all-levels="false"
          :props="{ checkStrictly: true }"
          popper-class="cancelRadio"
        />
      </el-form-item>
      <el-form-item label="期望薪资" prop="intention.minwage">
        <el-select
          v-model="form.intention.minwage"
          placeholder="请选择"
          class="wage-sel"
          @change="minwage_change"
        >
          <el-option
            v-for="(item, index) in options_minwage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        -
        <el-select
          v-model="form.intention.maxwage"
          placeholder="请选择"
          class="wage-sel"
        >
          <el-option
            v-for="(item, index) in options_maxwage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        v-if="live_fields.intention.trade === true"
        label="期望行业"
        prop="trade"
      >
        <el-select v-model="form.intention.trade" placeholder="请选择">
          <el-option
            v-for="(item, index) in options_trade"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-divider content-position="left">联系信息</el-divider>
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

      <el-form-item label=" ">
        <el-button
          type="primary"
          :loading="submitLoading"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getFieldRule } from '@/api/configuration'
import { memberCheckUnique } from '@/api/member'
import { getClassify } from '@/api/classify'
import { validMobile, validUsername, validEmail } from '@/utils/validate'
import { resumeAdd } from '@/api/resume'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
var wage_data = []
// 【ID1000220】【bug】后台添加简历薪资范围不一致
let current = 0
const unit1 = 500
const unit2 = 5000
for (let i = 0; i < 37; i++) {
  if (current < 15000){
    current += unit1
  } else {
    current += unit2
  }
  wage_data.push(current)
}
export default {
  filters: {
    defaultFilter(data) {
      return data == '' ? '未填写' : data
    }
  },
  data() {
    var validateUsername = (rule, value, callback) => {
      if (validUsername(value)) {
        callback(new Error('用户名不能是纯数字'))
      } else if (validEmail(value)) {
        callback(new Error('用户名不能是邮箱'))
      } else {
        const param = {
          field: 'username',
          value,
          self: this.uid,
          utype: 2
        }
        memberCheckUnique(param).then(response => {
          if (response.data == 0) {
            callback(new Error('用户名已被占用'))
          } else {
            callback()
          }
        })
      }
    }
    var validateMobile = (rule, value, callback) => {
      if (!validMobile(value)) {
        callback(new Error('请输入正确的手机号'))
      } else {
        const param = {
          field: 'mobile',
          value,
          self: this.uid,
          utype: 2
        }
        memberCheckUnique(param).then(response => {
          if (response.data == 0) {
            callback(new Error('手机号已被占用'))
          } else {
            callback()
          }
        })
      }
    }
    var validatePassword = (rule, value, callback) => {
      if (this.uid > 0) {
        callback()
      } else {
        if (value == '') {
          callback(new Error('请输入密码'))
        } else {
          callback()
        }
      }
    }
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
      submitLoading: false,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      options_nature: [],
      options_jobcategory: [],
      options_citycategory: [],
      options_minwage: [],
      options_maxwage: [],
      options_trade: [],
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
        },
        intention: {
          trade: true
        }
      },
      custom_field_1_cn: ' ',
      custom_field_2_cn: ' ',
      custom_field_3_cn: ' ',
      enter_job_time_empty: false,
      form: {
        member: {
          username: '',
          password: '',
          mobile: ''
        },
        fullname: '',
        sex: 1,
        birthday: '',
        residence: '',
        height: '',
        marriage: 0,
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
        },
        intention: {
          nature: 1,
          jobcategory_arr: [],
          citycategory_arr: [],
          category1: 0,
          category2: 0,
          category3: 0,
          district1: 0,
          district2: 0,
          district3: 0,
          minwage: '',
          maxwage: '',
          trade: ''
        }
      },
      photoUrl: '',
      options_sex: [],
      options_education: [],
      options_major: [],
      options_current: [],
      options_marriage: [],
      rules: {
        member: {
          username: [
            {
              required: true,
              message: '请输入用户名',
              trigger: 'blur'
            },
            {
              min: 6,
              max: 15,
              message: '长度在 6 到 15 个字',
              trigger: 'blur'
            },
            { validator: validateUsername, trigger: 'blur' }
          ],
          password: [
            { validator: validatePassword, trigger: 'blur' },
            {
              min: 6,
              max: 15,
              message: '长度在 6 到 15 个字',
              trigger: 'blur'
            }
          ],
          mobile: [
            {
              required: true,
              message: '请输入手机号',
              trigger: 'blur'
            },
            { validator: validateMobile, trigger: 'blur' }
          ]
        },
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
        },
        intention: {
          nature: [
            {
              required: true,
              message: '请选择工作性质',
              trigger: 'change'
            }
          ],
          jobcategory_arr: [
            {
              required: true,
              message: '请选择意向职位',
              trigger: 'change'
            }
          ],
          citycategory_arr: [
            {
              required: true,
              message: '请选择工作地区',
              trigger: 'change'
            }
          ],
          minwage: [
            {
              required: true,
              message: '请选择期望薪资',
              trigger: 'change'
            }
          ],
          trade: [
            {
              required: true,
              message: '请选择期望行业',
              trigger: 'change'
            }
          ]
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
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchInfo()
  },
  methods: {
    // PC端创建简历日期选择往前推到16岁 zdq
    birthdayDateRange(){
      return {
        disabledDate(time){
          var myDate = new Date();
          return time.getTime() > new Date(new Date(myDate.getFullYear(),11).getTime() - 16 * 365 * 24 * 3600 * 1000);
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
      getFieldRule({}, 'get')
        .then(response => {
          const { Resume, ResumeContact, ResumeIntention } = {
            ...response.data
          }
          const extra_rule = {
            Resume,
            ResumeContact,
            ResumeIntention
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
          if (extra_rule.ResumeIntention.trade.is_display == 0) {
            this.live_fields.intention.trade = false
            this.rules.intention.trade = []
          } else if (extra_rule.ResumeIntention.trade.is_require == 0) {
            this.rules.intention.trade = []
          }
          return getClassify({
            type:
              'resumeSex,education,major,current,marriage,resumeNature,jobcategory,citycategory,trade'
          })
        })
        .then(response => {
          this.options_sex = [...response.data.resumeSex]
          this.options_education = [...response.data.education]
          this.options_major = [...response.data.major]
          this.options_current = [...response.data.current]
          this.options_marriage = [...response.data.marriage]
          this.options_nature = [...response.data.resumeNature]
          this.options_jobcategory = [...response.data.jobcategory]
          this.options_jobcategory = this.options_jobcategory.map((item) => {
            if (item.children.length) {
              let level2Array = item.children
              level2Array = level2Array.map((level2Item) => {
                if (level2Item.children.length) {
                  return { label: level2Item.label, value: level2Item.value, children: level2Item.children }
                } else {
                  return { label: level2Item.label, value: level2Item.value }
                }
              })
              return { value: item.value, label: item.label, children: level2Array }
            } else {
              return { value: item.value, label: item.label }
            }
          })
          this.options_citycategory = [...response.data.citycategory]
          this.options_trade = [...response.data.trade]
          for (let index = 0; index < wage_data.length; index++) {
            const tmp_json = {
              id: wage_data[index],
              name: wage_data[index] + '元/月'
            }
            this.options_minwage.push(tmp_json)
          }
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
          const tmp_jobcategory_arr = this.form.intention.jobcategory_arr
          insertData.intention.category1 = tmp_jobcategory_arr[0]
          insertData.intention.category2 =
            tmp_jobcategory_arr[1] !== undefined ? tmp_jobcategory_arr[1] : 0
          insertData.intention.category3 =
            tmp_jobcategory_arr[2] !== undefined ? tmp_jobcategory_arr[2] : 0
          const tmp_citycategory_arr = this.form.intention.citycategory_arr
          insertData.intention.district1 = tmp_citycategory_arr[0]
          insertData.intention.district2 =
            tmp_citycategory_arr[1] !== undefined ? tmp_citycategory_arr[1] : 0
          insertData.intention.district3 =
            tmp_citycategory_arr[2] !== undefined ? tmp_citycategory_arr[2] : 0
          resumeAdd(insertData)
            .then(response => {
              if (response.code == 200) {
                this.submitLoading = false
                this.$message.success(response.message)
                setTimeout(() => {
                  this.$router.push(
                    '/user/urmList'
                  )
                }, 1500)
                return true
              } else {
                this.submitLoading = false
                this.$message.error(response.message)
                return false
              }
            })
            .catch(() => { })
        } else {
          this.submitLoading = false
          return false
        }
      })
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
    minwage_change(e) {
      this.handle_maxwage(e)
    },
    handle_maxwage(minwage) {
      if (!minwage) {
        return false
      }
      this.options_maxwage = []
      const startindex = wage_data.indexOf(minwage) + 1
      if (minwage >= this.form.intention.maxwage) {
        this.form.intention.maxwage = wage_data[startindex]
      }
      for (let index = startindex; index < wage_data.length; index++) {
        const tmp_json = {
          id: wage_data[index],
          name: wage_data[index] + '元/月'
        }
        this.options_maxwage.push(tmp_json)
      }
    },
    check_enter_job_time_empty(val) {
      if (val === true) {
        this.form.enter_job_time = ''
      }
    }
  }
}
</script>

<style scoped>
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
.el-input,
.el-select,
.el-cascader,
.el-textarea {
  width: 450px;
}
.wage-sel,
.wage-sel .el-input .el-input__inner {
  width: 218px;
}
.wage-sel .el-input {
  width: 218px;
}
</style>
