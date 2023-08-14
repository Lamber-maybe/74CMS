<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="true"
    >
      <el-divider content-position="left">基本信息</el-divider>
      <el-form-item label="职位名称" prop="jobname">
        <el-input v-model="form.jobname" />
      </el-form-item>
      <el-form-item label="招聘状态" prop="is_display">
        <el-radio-group v-model="form.is_display">
          <el-radio
            v-for="(item, index) in options_display"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="审核状态" prop="audit">
        <el-radio-group v-model="form.audit">
          <el-radio
            v-for="(item, index) in options_audit"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="职位性质" prop="nature">
        <el-radio-group v-model="form.nature">
          <el-radio
            v-for="(item, index) in options_nature"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="职位类别" prop="jobcategory_arr">
        <el-cascader
          v-model="form.jobcategory_arr"
          :options="options_jobcategory"
          :show-all-levels="false"
          filterable
        />
      </el-form-item>
      <el-form-item label="学历要求" prop="education">
        <el-select v-model="form.education" placeholder="请选择学历要求">
          <el-option label="不限学历" :value="0" />
          <el-option
            v-for="(item, index) in options_education"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="工作经验" prop="experience">
        <el-select v-model="form.experience" placeholder="请选择工作经验">
          <el-option label="不限经验" :value="0" />
          <el-option
            v-for="(item, index) in options_experience"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        v-if="live_fields.amount === true"
        label="招聘人数"
        prop="amount"
      >
        <el-input v-model.number="form.amount" />
        <span class="smalltip">
          <i class="el-icon-info" />
          0表示若干
        </span>
      </el-form-item>
      <el-form-item label="薪资待遇" prop="minwage">
        <el-select
          v-model="form.minwage"
          :disabled="form.negotiable !== false"
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
          v-model="form.maxwage"
          :disabled="form.negotiable !== false"
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
        <el-checkbox
          v-if="live_fields.negotiable === true"
          v-model="form.negotiable"
          style="margin-left:10px;"
        >
          面议
        </el-checkbox>
      </el-form-item>
      <el-form-item label="工作地区" prop="citycategory_arr">
        <el-cascader
          v-model="form.citycategory_arr"
          :options="options_citycategory"
          :show-all-levels="false"
        />
      </el-form-item>
      <el-form-item label="工作地址" prop="address">
        <el-input v-model="form.address" />
      </el-form-item>
      <el-form-item
        label="职位描述"
        prop="content"
      >
        <el-input v-model="form.content" type="textarea" rows="10" />
      </el-form-item>
      <el-divider content-position="left">其他信息</el-divider>
      <el-form-item v-if="live_fields.tag === true" label="岗位福利" prop="tag">
        <el-select
          v-model="form.tag"
          multiple
          filterable
          allow-create
          default-first-option
          placeholder="请选择岗位福利"
          :multiple-limit="6"
          class="tag-input"
        >
          <el-option
            v-for="(item, index) in options_tag"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        v-if="live_fields.department === true"
        label="部门"
        prop="department"
      >
        <el-input v-model="form.department" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.age === true"
        label="年龄要求"
        prop="minwage"
      >
        <el-select
          v-model="form.minage"
          :disabled="form.age_na !== false"
          placeholder="请选择"
          class="age-sel"
          @change="minage_change"
        >
          <el-option
            v-for="(item, index) in options_minage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        -
        <el-select
          v-model="form.maxage"
          :disabled="form.age_na !== false"
          placeholder="请选择"
          class="age-sel"
        >
          <el-option
            v-for="(item, index) in options_maxage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-checkbox
          v-model="form.age_na"
          style="margin-left:10px;"
        >不限</el-checkbox>
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
      <el-divider content-position="left">联系方式</el-divider>
      <el-form-item label="联系方式">
        <el-select v-model="form.contact.use_company_contact" placeholder="请选择">
          <el-option label="使用企业资料联系方式" :value="1" />
          <el-option label="使用其他联系方式" :value="0" />
        </el-select>
      </el-form-item>
      <div v-if="form.contact.use_company_contact==0">
        <el-form-item label="联系人" prop="contact.contact">
          <el-input v-model="form.contact.contact" />
        </el-form-item>
        <el-form-item label="联系手机" prop="contact.mobile">
          <el-input v-model="form.contact.mobile" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.contact.telephone === true"
          label="联系座机"
          prop="contact.telephone"
        >
          <el-input v-model="form.contact.telephone" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.contact.weixin === true"
          label="联系微信"
          prop="contact.weixin"
        >
          <el-input v-model="form.contact.weixin" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.contact.qq === true"
          label="联系QQ"
          prop="contact.qq"
        >
          <el-input v-model="form.contact.qq" />
        </el-form-item>
        <el-form-item
          v-if="live_fields.contact.email === true"
          label="联系邮箱"
          prop="contact.email"
        >
          <el-input v-model="form.contact.email" />
        </el-form-item>
      </div>
      <el-form-item
        label=""
      >
        <el-checkbox v-model="contactHidden">联系方式保密（不想受到骚扰）</el-checkbox>
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          :loading="submitLoading"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <el-button @click="goto('/user/job/list')">返回</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getFieldRule } from '@/api/configuration'
import { jobEdit } from '@/api/job'
import { validMobile, validEmail } from '@/utils/validate'
import { getClassify } from '@/api/classify'

var age_data = []

for (let i = 16; i <= 65; i++) {
  age_data.push(i)
}
var wage_data = []
for (let i = 1000; i <= 15000; i += 500) {
  wage_data.push(i)
}
export default {
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
    return {
      contactHidden: false,
      infoLoading: true,
      submitLoading: false,
      options_display: [],
      options_audit: [],
      options_nature: [],
      options_education: [],
      options_experience: [],
      options_jobcategory: [],
      options_citycategory: [],
      options_tag: [],
      options_minwage: [],
      options_maxwage: [],
      options_minage: [],
      options_maxage: [],
      live_fields: {
        department: true,
        age: true,
        tag: true,
        amount: true,
        negotiable: true,
        custom_field_1: true,
        custom_field_2: true,
        custom_field_3: true,
        contact: {
          weixin: true,
          telephone: true,
          qq: true,
          email: true
        }
      },
      custom_field_1_cn: ' ',
      custom_field_2_cn: ' ',
      custom_field_3_cn: ' ',
      form: {
        jobname: '',
        is_display: '',
        audit: '',
        nature: '',
        jobcategory_arr: '',
        citycategory_arr: '',
        minwage: '',
        maxwage: '',
        negotiable: false,
        department: '',
        education: 0,
        experience: 0,
        minage: '',
        maxage: '',
        age_na: false,
        amount: '',
        tag: [],
        content: '',
        address: '',
        custom_field_1: '',
        custom_field_2: '',
        custom_field_3: '',
        contact: {
          contact: '',
          mobile: '',
          weixin: '',
          telephone: '',
          qq: '',
          email: '',
          is_display: 1,
          use_company_contact: 1
        }
      },
      rules: {
        jobname: [
          {
            required: true,
            message: '请输入职位名称',
            trigger: 'blur'
          },
          {
            max: 50,
            message: '长度在 1 到 50 个字符',
            trigger: 'blur'
          }
        ],
        nature: [
          {
            required: true,
            message: '请选择职位性质',
            trigger: 'change'
          }
        ],
        jobcategory_arr: [
          {
            required: true,
            message: '请选择职位分类',
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
            message: '请选择薪资待遇',
            trigger: 'change'
          }
        ],
        department: [
          {
            required: true,
            message: '请输入部门',
            trigger: 'blur'
          },
          {
            max: 15,
            message: '长度在 1 到 15 个字符',
            trigger: 'blur'
          }
        ],
        education: [
          {
            required: true,
            message: '请选择学历要求',
            trigger: 'change'
          }
        ],
        experience: [
          {
            required: true,
            message: '请选择工作经验',
            trigger: 'change'
          }
        ],
        minage: [
          {
            required: true,
            message: '请选择年龄要求',
            trigger: 'change'
          }
        ],
        amount: [
          {
            required: true,
            message: '请输入招聘人数',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '请正确输入招聘人数',
            trigger: 'blur'
          }
        ],
        content: [
          {
            required: true,
            message: '请输入职位描述',
            trigger: 'blur'
          }
        ],
        tag: [
          {
            required: true,
            message: '请选择岗位福利',
            trigger: 'change'
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
          contact: [
            {
              required: true,
              message: '请输入联系人',
              trigger: 'blur'
            },
            {
              max: 30,
              message: '长度在 1 到 30 个字符',
              trigger: 'blur'
            }
          ],
          mobile: [
            {
              required: true,
              message: '请输入联系手机',
              trigger: 'blur'
            },
            { validator: validateContactMobile, trigger: 'blur' }
          ],
          weixin: [
            {
              required: true,
              message: '请输入微信',
              trigger: 'blur'
            }
          ],
          telephone: [
            {
              required: true,
              message: '请输入联系座机',
              trigger: 'blur'
            }
          ],
          qq: [
            {
              required: true,
              message: '请输入联系QQ',
              trigger: 'blur'
            }
          ],
          email: [
            {
              required: true,
              message: '请输入联系邮箱',
              trigger: 'blur'
            },
            { validator: validateContactEmail, trigger: 'blur' }
          ]
        }
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      getFieldRule({}, 'get')
        .then(response => {
          const { Job, JobContact } = {
            ...response.data
          }
          const extra_rule = {
            Job,
            JobContact
          }
          this.custom_field_1_cn = extra_rule.Job.custom_field_1.field_cn
          this.custom_field_2_cn = extra_rule.Job.custom_field_2.field_cn
          this.custom_field_3_cn = extra_rule.Job.custom_field_3.field_cn
          if (extra_rule.Job.department.is_display == 0) {
            this.live_fields.department = false
            this.rules.department = []
          } else if (extra_rule.Job.department.is_require == 0) {
            this.rules.department = []
          }
          if (extra_rule.Job.age.is_display == 0) {
            this.live_fields.age = false
            this.rules.minage = []
          } else if (extra_rule.Job.age.is_require == 0) {
            this.rules.minage = []
          }
          if (extra_rule.Job.tag.is_display == 0) {
            this.live_fields.tag = false
            this.rules.tag = []
          } else if (extra_rule.Job.tag.is_require == 0) {
            this.rules.tag = []
          }
          if (extra_rule.Job.amount.is_display == 0) {
            this.live_fields.amount = false
            this.rules.amount = []
          } else if (extra_rule.Job.amount.is_require == 0) {
            this.rules.amount = []
          }
          if (extra_rule.Job.negotiable.is_display == 0) {
            this.live_fields.Job.negotiable = false
          }
          if (extra_rule.Job.custom_field_1.is_display == 0) {
            this.live_fields.custom_field_1 = false
            this.rules.custom_field_1 = []
          } else if (extra_rule.Job.custom_field_1.is_require == 0) {
            this.rules.custom_field_1 = []
          } else {
            this.rules.custom_field_1[0].message = this.rules.custom_field_1[0].message.replace(
              'custom_field_1',
              this.custom_field_1_cn
            )
          }
          if (extra_rule.Job.custom_field_2.is_display == 0) {
            this.live_fields.custom_field_2 = false
            this.rules.custom_field_2 = []
          } else if (extra_rule.Job.custom_field_2.is_require == 0) {
            this.rules.custom_field_2 = []
          } else {
            this.rules.custom_field_2[0].message = this.rules.custom_field_2[0].message.replace(
              'custom_field_2',
              this.custom_field_2_cn
            )
          }
          if (extra_rule.Job.custom_field_3.is_display == 0) {
            this.live_fields.custom_field_3 = false
            this.rules.custom_field_3 = []
          } else if (extra_rule.Job.custom_field_3.is_require == 0) {
            this.rules.custom_field_3 = []
          } else {
            this.rules.custom_field_3[0].message = this.rules.custom_field_3[0].message.replace(
              'custom_field_3',
              this.custom_field_3_cn
            )
          }
          if (extra_rule.JobContact.weixin.is_display == 0) {
            this.live_fields.contact.weixin = false
            this.rules.contact.weixin = []
          } else if (extra_rule.JobContact.weixin.is_require == 0) {
            this.rules.contact.weixin = []
          }
          if (extra_rule.JobContact.telephone.is_display == 0) {
            this.live_fields.contact.telephone = false
            this.rules.contact.telephone = []
          } else if (extra_rule.JobContact.telephone.is_require == 0) {
            this.rules.contact.telephone = []
          }
          if (extra_rule.JobContact.qq.is_display == 0) {
            this.live_fields.contact.qq = false
            this.rules.contact.qq = []
          } else if (extra_rule.JobContact.qq.is_require == 0) {
            this.rules.contact.qq = []
          }
          if (extra_rule.JobContact.email.is_display == 0) {
            this.live_fields.contact.email = false
            this.rules.contact.email = []
          } else if (extra_rule.JobContact.email.is_require == 0) {
            this.rules.contact.email = []
          }
          return getClassify({
            type:
              'jobDisplay,jobAudit,jobNature,jobcategory,citycategory,jobTag,education,experience'
          })
        })
        .then(response => {
          this.options_display = [...response.data.jobDisplay]
          this.options_audit = [...response.data.jobAudit]
          this.options_nature = [...response.data.jobNature]
          this.options_jobcategory = [...response.data.jobcategory]
          this.options_citycategory = [...response.data.citycategory]
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
          this.options_citycategory = this.options_citycategory.map((item) => {
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
          this.options_tag = [...response.data.jobTag]
          this.options_education = [...response.data.education]
          this.options_experience = [...response.data.experience]
          for (let index = 0; index < wage_data.length; index++) {
            const tmp_json = {
              id: wage_data[index],
              name: wage_data[index] + '元/月'
            }
            this.options_minwage.push(tmp_json)
          }
          for (let index = 0; index < age_data.length; index++) {
            const tmp_json = {
              id: age_data[index],
              name: age_data[index] + '岁'
            }
            this.options_minage.push(tmp_json)
          }
          return jobEdit({ id: this.$route.query.id }, 'get')
        })
        .then(response => {
          this.form = { ...response.data.info }
          this.form.citycategory_arr = []
          this.form.jobcategory_arr = []

          if (response.data.info.category1 != 0) {
            this.form.jobcategory_arr.push(response.data.info.category1)
          }
          if (response.data.info.category2 != 0) {
            this.form.jobcategory_arr.push(response.data.info.category2)
          }
          if (response.data.info.category3 != 0) {
            this.form.jobcategory_arr.push(response.data.info.category3)
          }
          if (response.data.info.district1 != 0) {
            this.form.citycategory_arr.push(response.data.info.district1)
          }
          if (response.data.info.district2 != 0) {
            this.form.citycategory_arr.push(response.data.info.district2)
          }
          if (response.data.info.district3 != 0) {
            this.form.citycategory_arr.push(response.data.info.district3)
          }
          const tagIds = this.form.tag.toString()
          const arrData = tagIds.split(',')
          for (var i = 0; i < arrData.length; i++) {
            if (!isNaN(arrData[i])) {
              arrData[i] = parseInt(arrData[i])
            }
          }
          if (this.form.minwage) {
            this.handle_maxwage(this.form.minwage)
          }
          if (this.form.minage) {
            this.handle_maxage(this.form.minage)
          }
          if (this.live_fields.negotiable === false) {
            this.form.negotiable = false
          } else {
            this.form.negotiable = this.form.negotiable == 1
          }
          this.form.age_na = this.form.age_na == 1

          this.form.tag = arrData
          this.contactHidden = this.form.contact.is_display != 1
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      // this.submitLoading = true;
      const that = this
      const insertData = { ...this.form }
      insertData.contact.is_display = this.contactHidden === true ? 0 : 1
      this.$refs[formName].validate(valid => {
        if (valid) {
          const tmp_jobcategory_arr = this.form.jobcategory_arr
          insertData.category1 = tmp_jobcategory_arr[0]
          insertData.category2 =
            tmp_jobcategory_arr[1] !== undefined ? tmp_jobcategory_arr[1] : 0
          insertData.category3 =
            tmp_jobcategory_arr[2] !== undefined ? tmp_jobcategory_arr[2] : 0
          const tmp_citycategory_arr = this.form.citycategory_arr
          insertData.district1 = tmp_citycategory_arr[0]
          insertData.district2 =
            tmp_citycategory_arr[1] !== undefined ? tmp_citycategory_arr[1] : 0
          insertData.district3 =
            tmp_citycategory_arr[2] !== undefined ? tmp_citycategory_arr[2] : 0
          insertData.contact = { ...this.form.contact }
          insertData.negotiable = insertData.negotiable == true ? 1 : 0
          insertData.age_na = insertData.age_na == true ? 1 : 0
          jobEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/user/job/list')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    goto(target) {
      this.$router.push(target)
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
      if (minwage >= this.form.maxwage) {
        this.form.maxwage = wage_data[startindex]
      }
      for (let index = startindex; index < wage_data.length; index++) {
        const tmp_json = {
          id: wage_data[index],
          name: wage_data[index] + '元/月'
        }
        this.options_maxwage.push(tmp_json)
      }
    },
    minage_change(e) {
      this.handle_maxage(e)
    },
    handle_maxage(minage) {
      if (!minage) {
        return false
      }
      this.options_maxage = []
      const startindex = age_data.indexOf(minage) + 1
      if (minage >= this.form.maxage) {
        this.form.maxage = age_data[startindex]
      }
      for (let index = startindex; index < age_data.length; index++) {
        const tmp_json = {
          id: age_data[index],
          name: age_data[index] + '岁'
        }
        this.options_maxage.push(tmp_json)
      }
    }
  }
}
</script>

<style scoped>
.el-input,
.el-select,
.el-cascader,
.el-textarea {
  width: 450px;
}
.el-form.common-form .input-sel {
  width: 120px;
}
.el-form.common-form .input-sel .el-input {
  width: 120px;
}
.el-form.common-form .input-sel .el-input .el-input__inner {
  width: 120px;
}
.wage-sel.el-select {
  width: 220px;
}
.age-sel.el-select {
  width: 220px;
}
.el-select.tag-input {
  width: 550px;
}
</style>
