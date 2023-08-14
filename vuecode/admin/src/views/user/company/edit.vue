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
      <el-divider content-position="left">
        企业信息
      </el-divider>
      <el-form-item label="公司名称" prop="companyname">
        <el-input v-model="form.companyname" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.short_name === true"
        label="公司简称"
        prop="short_name"
      >
        <el-input v-model="form.short_name" />
      </el-form-item>
      <el-form-item label="认证状态" prop="audit">
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
      <el-form-item v-if="live_fields.logo === true" label="Logo" prop="logo">
        <div style="position: relative">
          <div v-if="form.logo" class="transparent" @click="logoDel">
            <i class="el-icon-delete" />
            删除
          </div>
          <el-upload
            class="logo-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleLogoSuccess"
            :before-upload="beforeLogoUpload"
          >
            <img v-if="form.logo" :src="logoUrl" class="logo">
            <i v-else class="el-icon-plus logo-uploader-icon" />
          </el-upload>
        </div>
        <span class="smalltip">
          <i class="el-icon-info" />
          建议尺寸120*120
        </span>
      </el-form-item>
      <el-form-item label="企业性质" prop="nature">
        <el-select v-model="form.nature" placeholder="请选择企业性质">
          <el-option
            v-for="(item, index) in options_nature"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="公司规模" prop="scale">
        <el-select v-model="form.scale" placeholder="请选择公司规模">
          <el-option
            v-for="(item, index) in options_scale"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="所属行业" prop="trade">
        <el-select v-model="form.trade" placeholder="请选择所属行业">
          <el-option
            v-for="(item, index) in options_trade"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        v-if="live_fields.registered === true"
        label="注册资金"
        prop="registered"
      >
        <el-input
          v-model="form.registered"
          placeholder="请输入注册资金"
          class="input-with-select"
        >
          <el-select
            slot="append"
            v-model="form.currency"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="万人民币" value="0" />
            <el-option label="万美元" value="1" />
          </el-select>
        </el-input>
      </el-form-item>
      <el-form-item
        v-if="live_fields.info.website === true"
        label="公司网址"
        prop="info.website"
      >
        <el-input v-model="form.info.website" />
      </el-form-item>
      <el-form-item v-if="live_fields.tag === true" label="企业福利" prop="tag">
        <el-select
          v-model="form.tag"
          multiple
          filterable
          allow-create
          default-first-option
          placeholder="请选择企业福利"
          :multiple-limit="10"
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
      <el-form-item label="所在地区" prop="citycategory_arr">
        <el-cascader
          v-model="form.citycategory_arr"
          :options="options_citycategory"
          :show-all-levels="false"
        />
      </el-form-item>
      <el-form-item label="联系地址" prop="info.address">
        <el-input v-model="form.info.address" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.info.short_desc === true"
        label="公司简介"
        prop="info.short_desc"
      >
        <el-input v-model="form.info.short_desc" type="textarea" rows="3" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.info.content === true"
        label="公司介绍"
        prop="info.content"
      >
        <el-input v-model="form.info.content" type="textarea" rows="3" />
      </el-form-item>
      <el-divider content-position="left">
        联系信息
      </el-divider>
      <el-form-item label="联系人" prop="contact.contact">
        <el-input v-model="form.contact.contact" />
      </el-form-item>
      <el-form-item label="联系手机" prop="contact.mobile">
        <el-input v-model="form.contact.mobile" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.contact.weixin === true"
        label="联系微信"
        prop="contact.weixin"
      >
        <el-input v-model="form.contact.weixin" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.contact.telephone === true"
        label="联系固话"
        prop="contact.telephone"
      >
        <el-input v-model="form.contact.telephone" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.contact.email === true"
        label="联系邮箱"
        prop="contact.email"
      >
        <el-input v-model="form.contact.email" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.contact.qq === true"
        label="联系QQ"
        prop="contact.qq"
      >
        <el-input v-model="form.contact.qq" />
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          :loading="submitLoading"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <!--        <el-button @click="goto('/user/company/list')">-->
        <!--          返回-->
        <!--        </el-button>-->
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getFieldRule } from '@/api/configuration'
import { companyEdit } from '@/api/company'
import { validMobile, validEmail, validUrl } from '@/utils/validate'
import { getToken } from '@/utils/auth'
import { getClassify } from '@/api/classify'
// import { apiUpload } from '@/api/upload'
import apiArr from '@/api'
// import { config } from 'vuex'

export default {
  props: {
    id: {
      default: ''
    }
  },
  data() {
    var validateUrl = (rule, value, callback) => {
      if (value == '') {
        callback()
      }
      if (!validUrl(value)) {
        callback(new Error('请输入正确的网址'))
      } else {
        callback()
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
    return {
      infoLoading: false,
      submitLoading: false,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      options_audit: [],
      options_nature: [],
      options_trade: [],
      options_citycategory: [],
      options_scale: [],
      options_tag: [],
      live_fields: {
        logo: true,
        short_name: true,
        registered: true,
        tag: true,
        contact: {
          weixin: true,
          telephone: true,
          qq: true,
          email: true
        },
        info: {
          website: true,
          short_desc: true,
          content: true
        }
      },
      form: {
        companyname: '',
        short_name: '',
        nature: '',
        trade: '',
        logo: '',
        citycategory_arr: [],
        scale: '',
        registered: '',
        currency: '0',
        tag: [],
        audit: 0,
        contact: {
          contact: '',
          mobile: '',
          weixin: '',
          telephone: '',
          qq: '',
          email: ''
        },
        info: {
          website: '',
          short_desc: '',
          content: '',
          address: ''
        }
      },
      logoUrl: '',
      rules: {
        logo: [
          {
            required: true,
            message: '请上传logo',
            trigger: 'blur'
          }
        ],
        companyname: [
          {
            required: true,
            message: '请输入公司名称',
            trigger: 'blur'
          },
          {
            max: 60,
            message: '长度在 1 到 60 个字符',
            trigger: 'blur'
          }
        ],
        short_name: [
          {
            required: true,
            message: '请输入公司简称',
            trigger: 'blur'
          },
          {
            max: 60,
            message: '长度在 1 到 60 个字符',
            trigger: 'blur'
          }
        ],
        nature: [
          {
            required: true,
            message: '请选择公司性质',
            trigger: 'change'
          }
        ],
        trade: [
          {
            required: true,
            message: '请选择所属行业',
            trigger: 'change'
          }
        ],
        citycategory_arr: [
          {
            required: true,
            message: '请选择所在地区',
            trigger: 'change'
          }
        ],
        scale: [
          {
            required: true,
            message: '请选择公司规模',
            trigger: 'change'
          }
        ],
        registered: [
          {
            required: true,
            message: '请输入注册资金',
            trigger: 'blur'
          },
          {
            max: 15,
            message: '长度在 1 到 15 个字符',
            trigger: 'blur'
          }
        ],
        tag: [
          {
            required: true,
            message: '请选择企业福利',
            trigger: 'change'
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
        },
        info: {
          website: [
            {
              required: true,
              message: '请输入公司网址',
              trigger: 'blur'
            },
            {
              max: 200,
              message: '长度在 0 到 200 个字符',
              trigger: 'blur'
            },
            { validator: validateUrl, trigger: 'blur' }
          ],
          short_desc: [
            {
              required: true,
              message: '请输入公司简介',
              trigger: 'blur'
            },
            {
              max: 255,
              message: '长度在 0 到 255 个字符',
              trigger: 'blur'
            }
          ],
          content: [
            {
              required: true,
              message: '请输入公司介绍',
              trigger: 'blur'
            }
          ],
          address: [
            {
              required: true,
              message: '请输入公司地址',
              trigger: 'blur'
            },
            {
              max: 255,
              message: '长度在 0 到 255 个字符',
              trigger: 'blur'
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
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchData()
  },
  methods: {
    logoDel(){
      this.logoUrl = ''
      this.form.logo = ''
    },
    fetchData() {
      getFieldRule({}, 'get')
        .then(response => {
          const extra_rule = response.data
          if (extra_rule.Company.logo.is_display == 0) {
            this.live_fields.logo = false
            this.rules.logo = []
          } else if (extra_rule.Company.logo.is_require == 0) {
            this.rules.logo = []
          }
          if (extra_rule.Company.short_name.is_display == 0) {
            this.live_fields.short_name = false
            this.rules.short_name = []
          } else if (extra_rule.Company.short_name.is_require == 0) {
            this.rules.short_name = []
          }
          if (extra_rule.Company.registered.is_display == 0) {
            this.live_fields.registered = false
            this.rules.registered = []
          } else if (extra_rule.Company.registered.is_require == 0) {
            this.rules.registered = []
          }
          if (extra_rule.Company.tag.is_display == 0) {
            this.live_fields.tag = false
            this.rules.tag = []
          } else if (extra_rule.Company.tag.is_require == 0) {
            this.rules.tag = []
          }
          if (extra_rule.CompanyInfo.website.is_display == 0) {
            this.live_fields.info.website = false
            this.rules.info.website = []
          } else if (extra_rule.CompanyInfo.website.is_require == 0) {
            this.rules.info.website = []
          }
          if (extra_rule.CompanyInfo.short_desc.is_display == 0) {
            this.live_fields.info.short_desc = false
            this.rules.info.short_desc = []
          } else if (extra_rule.CompanyInfo.short_desc.is_require == 0) {
            this.rules.info.short_desc = []
          }
          if (extra_rule.CompanyInfo.content.is_display == 0) {
            this.live_fields.info.content = false
            this.rules.info.content = []
          } else if (extra_rule.CompanyInfo.content.is_require == 0) {
            this.rules.info.content = []
          }
          if (extra_rule.CompanyContact.weixin.is_display == 0) {
            this.live_fields.contact.weixin = false
            this.rules.contact.weixin = []
          } else if (extra_rule.CompanyContact.weixin.is_require == 0) {
            this.rules.contact.weixin = []
          }
          if (extra_rule.CompanyContact.telephone.is_display == 0) {
            this.live_fields.contact.telephone = false
            this.rules.contact.telephone = []
          } else if (extra_rule.CompanyContact.telephone.is_require == 0) {
            this.rules.contact.telephone = []
          }
          if (extra_rule.CompanyContact.qq.is_display == 0) {
            this.live_fields.contact.qq = false
            this.rules.contact.qq = []
          } else if (extra_rule.CompanyContact.qq.is_require == 0) {
            this.rules.contact.qq = []
          }
          if (extra_rule.CompanyContact.email.is_display == 0) {
            this.live_fields.contact.email = false
            this.rules.contact.email = []
          } else if (extra_rule.CompanyContact.email.is_require == 0) {
            this.rules.contact.email = []
          }
          return getClassify({
            type:
              'companyAudit,companyNature,trade,citycategory,companyScale,jobTag,setmealList'
          })
        })
        .then(response => {
          this.options_audit = [...response.data.companyAudit]
          this.options_nature = [...response.data.companyNature]
          this.options_trade = [...response.data.trade]
          this.options_citycategory = [...response.data.citycategory]
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
          this.options_scale = [...response.data.companyScale]
          this.options_tag = [...response.data.jobTag]
          this.options_setmeal = [...response.data.setmealList]
          if (this.id != ''){
            return companyEdit({ id: this.id }, 'get')
          }
        })
        .then(response => {
          this.form = { ...response.data.info }
          this.form.nature = response.data.info.nature == 0 ? '' : response.data.info.nature
          this.form.scale = response.data.info.scale == 0 ? '' : response.data.info.scale
          this.form.trade = response.data.info.trade == 0 ? '' : response.data.info.trade
          this.logoUrl = response.data.logoUrl
          this.form.currency = this.form.currency + ''
          var citycategory_arr = []
          this.form.tag = this.form.tag == '' ? [] : this.form.tag.split(',')
          for (let index = 0; index < this.form.tag.length; index++) {
            this.form.tag[index] = parseInt(this.form.tag[index])
          }
          if (response.data.info.district1 != 0) {
            citycategory_arr.push(response.data.info.district1)
          }
          if (response.data.info.district2 != 0) {
            citycategory_arr.push(response.data.info.district2)
          }
          if (response.data.info.district3 != 0) {
            citycategory_arr.push(response.data.info.district3)
          }
          this.$set(this.form, 'citycategory_arr', citycategory_arr)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const that = this
      const insertData = { ...this.form }

      this.$refs[formName].validate(valid => {
        if (valid) {
          const tmp_citycategory_arr = this.form.citycategory_arr
          insertData.district1 = tmp_citycategory_arr[0]
          insertData.district2 =
            tmp_citycategory_arr[1] !== undefined ? tmp_citycategory_arr[1] : 0
          insertData.district3 =
            tmp_citycategory_arr[2] !== undefined ? tmp_citycategory_arr[2] : 0
          insertData.contact = { ...this.form.contact }
          insertData.info = { ...this.form.info }
          companyEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              // setTimeout(function() {
              //   that.$router.push('/user/company/list')
              // }, 1500)
              this.submitLoading = false
              return true
            })
            .catch(() => {
              this.submitLoading = false
            })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    handleLogoSuccess(res, file) {
      if (res.code == 200) {
        this.logoUrl = URL.createObjectURL(file.raw)
        this.form.logo = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeLogoUpload(file) {
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
.transparent-top{
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
.logo-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.logo-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.logo-uploader:hover {
  border-color: #409eff;
}
.logo-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
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
</style>
