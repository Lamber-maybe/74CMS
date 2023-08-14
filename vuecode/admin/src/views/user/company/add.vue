<template>
  <div class="app-container">
    <el-form ref="form" class="common-form" :model="form" label-width="120px" :rules="rules" :inline-message="true">
      <el-divider content-position="left">会员信息</el-divider>
      <el-form-item label="用户名" prop="member.username"><el-input v-model="form.member.username" /></el-form-item>
      <el-form-item label="手机号" prop="member.mobile" auto-complete="new-mobile"><el-input v-model="form.member.mobile" /></el-form-item>
      <el-form-item label="密码" prop="member.password" class="is-required"><el-input v-model="form.member.password" auto-complete="new-password" show-password /></el-form-item>
      <el-form-item label="所属销售">
        <el-radio-group v-model="form.member.sale">
          <el-radio label="1">我的客户</el-radio>
          <el-radio label="0">公共客户</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-divider content-position="left">企业信息</el-divider>
      <el-form-item label="公司名称" prop="companyname"><el-input v-model="form.companyname" /></el-form-item>
      <el-form-item v-if="live_fields.short_name === true" label="公司简称" prop="short_name"><el-input v-model="form.short_name" /></el-form-item>
      <el-form-item label="认证状态" prop="audit">
        <el-radio-group v-model="form.audit">
          <el-radio v-for="(item, index) in options_audit" :key="index" :label="item.id">{{ item.name }}</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item v-if="live_fields.logo === true" label="Logo" prop="logo">
        <el-upload class="logo-uploader" :action="apiUpload" :headers="headers" :show-file-list="false" :on-success="handleLogoSuccess" :before-upload="beforeLogoUpload">
          <img v-if="form.logo" :src="logoUrl" class="logo">
          <i v-else class="el-icon-plus logo-uploader-icon" />
        </el-upload>
        <span class="smalltip">
          <i class="el-icon-info" />
          建议尺寸120*120
        </span>
      </el-form-item>
      <el-form-item label="企业性质" prop="nature">
        <el-select v-model="form.nature" placeholder="请选择企业性质">
          <el-option v-for="(item, index) in options_nature" :key="index" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>
      <el-form-item label="公司规模" prop="scale">
        <el-select v-model="form.scale" placeholder="请选择公司规模">
          <el-option v-for="(item, index) in options_scale" :key="index" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>
      <el-form-item label="所属行业" prop="trade">
        <el-select v-model="form.trade" placeholder="请选择所属行业">
          <el-option v-for="(item, index) in options_trade" :key="index" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>
      <el-form-item v-if="live_fields.registered === true" label="注册资金" prop="registered">
        <el-input v-model="form.registered" placeholder="请输入注册资金" class="input-with-select">
          <el-select slot="append" v-model="form.currency" placeholder="请选择" class="input-sel">
            <el-option label="万人民币" value="0" />
            <el-option label="万美元" value="1" />
          </el-select>
        </el-input>
      </el-form-item>
      <el-form-item v-if="live_fields.info.website === true" label="公司网址" prop="info.website"><el-input v-model="form.info.website" /></el-form-item>
      <el-form-item v-if="live_fields.tag === true" label="企业福利" prop="tag">
        <el-select v-model="form.tag" multiple filterable allow-create default-first-option placeholder="请选择企业福利" :multiple-limit="10" class="tag-input">
          <el-option v-for="(item, index) in options_tag" :key="index" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>
      <el-form-item label="所在地区" prop="citycategory_arr"><el-cascader v-model="form.citycategory_arr" :options="options_citycategory" :show-all-levels="false" /></el-form-item>
      <el-form-item label="联系地址" prop="info.address"><el-input v-model="form.info.address" /></el-form-item>
      <el-form-item v-if="live_fields.info.short_desc === true" label="公司简介" prop="info.short_desc"><el-input v-model="form.info.short_desc" /></el-form-item>
      <el-form-item v-if="live_fields.info.content === true" label="公司介绍" prop="info.content"><el-input v-model="form.info.content" type="textarea" rows="10" /></el-form-item>
      <el-divider content-position="left">联系信息</el-divider>
      <el-form-item label="联系人" prop="contact.contact"><el-input v-model="form.contact.contact" /></el-form-item>
      <el-form-item label="联系手机" prop="contact.mobile">
        <el-input v-model="form.contact.mobile" />
        <el-checkbox
          v-model="secrecyHidden"
          style="margin-left:10px;"
        >不对外显示仅接收通知</el-checkbox>
      </el-form-item>
      <el-form-item v-if="live_fields.contact.weixin === true" label="联系微信" prop="contact.weixin"><el-input v-model="form.contact.weixin" /></el-form-item>
      <el-form-item v-if="live_fields.contact.telephone === true" label="联系固话" prop="contact.telephone"><el-input v-model="form.contact.telephone" /></el-form-item>
      <el-form-item v-if="live_fields.contact.email === true" label="联系邮箱" prop="contact.email"><el-input v-model="form.contact.email" /></el-form-item>
      <el-form-item v-if="live_fields.contact.qq === true" label="联系QQ" prop="contact.qq"><el-input v-model="form.contact.qq" /></el-form-item>
      <el-divider content-position="left">积分/套餐设置</el-divider>
      <el-form-item label="赠送积分" prop="setmeal.points"><el-input v-model="form.setmeal.points" /></el-form-item>
      <el-form-item label="赠送套餐" prop="setmeal.setmeal_id">
        <el-radio-group v-model="form.setmeal.setmeal_id">
          <el-radio v-for="(item, index) in options_setmeal" :key="index" :label="item.id">{{ item.name }}</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="收费金额" prop="setmeal.charge"><el-input v-model="form.setmeal.charge" /></el-form-item>
      <el-form-item label="">
        <el-button type="primary" :loading="submitLoading" @click="onSubmit('form')">保存</el-button>
        <!--        <el-button @click="goto('/user/company/list')">-->
        <!--          返回-->
        <!--        </el-button>-->
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getFieldRule } from '@/api/configuration'
import { memberCheckUnique } from '@/api/member'
// import { companyAdd } from '@/api/company'
import { companyCrmAdd } from '@/api/company_crm'
import { validMobile, validUsername, validEmail, validUrl } from '@/utils/validate'
import { getToken } from '@/utils/auth'
import { getClassify } from '@/api/classify'
import apiArr from '@/api'
import { clueDetails } from '@/api/company_crm'

export default {
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
          utype: 1
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
          utype: 1
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
    return {
      secrecyHidden: false,
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
      options_setmeal: [],
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
        member: {
          username: '',
          password: '',
          mobile: '',
          sale: '1'
        },
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
        },
        setmeal: {
          points: 0,
          setmeal_id: 1,
          charge: 0
        }
      },
      logoUrl: '',
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
            message: '长度在 1 到 60 个字',
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
            message: '长度在 1 到 60 个字',
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
            message: '长度在 1 到 15 个字',
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
              message: '长度在 1 到 30 个字',
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
              message: '长度在 0 到 200 个字',
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
              message: '长度在 0 到 255 个字',
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
              message: '长度在 0 到 255 个字',
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
    if (this.$route.query.id) {
      clueDetails({ clue_id: this.$route.query.id })
        .then(res => {
          this.form.contact.weixin = res.data.weixin
          this.form.contact.mobile = res.data.mobile
          this.form.contact.contact = res.data.contacts
          this.form.companyname = res.data.name
          this.form.scale = res.data.scale == null ? '' : res.data.scale
          this.form.info.address = res.data.address
          this.form.trade = res.data.trade == null ? '' : res.data.trade
          if (res.data.district1 != 0) {
            this.form.citycategory_arr.push(res.data.district1)
          }
          if (res.data.district2 != 0) {
            this.form.citycategory_arr.push(res.data.district2)
          }
          if (res.data.district3 != 0) {
            this.form.citycategory_arr.push(res.data.district3)
          }
          this.secrecyHidden = this.form.contact.is_secrecy != 1
        })
        .catch(() => {})
    }
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchData()
  },
  methods: {
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
            type: 'companyAudit,companyNature,trade,citycategory,companyScale,jobTag,setmealList'
          })
        })
        .then(response => {
          this.options_audit = [...response.data.companyAudit]
          this.options_nature = [...response.data.companyNature]
          this.options_trade = [...response.data.trade]
          this.options_citycategory = [...response.data.citycategory]
          this.options_citycategory = this.options_citycategory.map(item => {
            if (item.children.length) {
              let level2Array = item.children
              level2Array = level2Array.map(level2Item => {
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
          insertData.district2 = tmp_citycategory_arr[1] !== undefined ? tmp_citycategory_arr[1] : 0
          insertData.district3 = tmp_citycategory_arr[2] !== undefined ? tmp_citycategory_arr[2] : 0
          insertData.contact = { ...this.form.contact }
          insertData.contact.is_secrecy = this.secrecyHidden === true ? 0 : 1
          insertData.clue_id = this.$route.query.id
          // companyAdd(insertData)  之前的
          companyCrmAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              if (this.form.member.sale == '0') {
                setTimeout(function() {
                  that.$router.push('/user/company/crm/pubilceClient')
                }, 1500)
              } else {
                setTimeout(function() {
                  that.$router.push('/user/company/crm/myClient')
                }, 1500)
              }
              return true
            })
            .catch(() => {
              this.submitLoading = false
            })
        } else {
          this.submitLoading = false
          // 校验不通过自动定位到不通过的表单项
          this.moveToErr()
          return false
        }
      })
    },
    // 自动定位到表单报错项
    moveToErr() {
      this.$nextTick(() => {
        const isError = document.getElementsByClassName('is-error')
        if (isError.length) {
          isError[0].scrollIntoView({
            block: 'center',
            behavior: 'smooth'
          })
          // 这个当滑动到报错项之后自动获取输入框的焦点，方便用户直接进行输入，延迟 800ms 是因为需要都能到定位成功后在进行获取焦点体验更好一些
          setTimeout(() => {
            if (isError[0].previousElementSibling.querySelector('input')) {
              isError[0].previousElementSibling.querySelector('input').focus()
            }
          }, 800)
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
