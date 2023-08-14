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
      <el-form-item class="logo-el-form-item" v-if="live_fields.logo === true" label="Logo" prop="logo">
        <div style="position: relative;height: 120px;">
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
        <span class="sendLogo" @click="logoDialogVisible = true">智能生成LOGO</span>
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
        <el-checkbox
          v-model="secrecyHidden"
          style="margin-left:10px;"
        >隐藏不显示</el-checkbox>
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
    <el-dialog
      title="智能生成LOGO"
      :visible.sync="logoDialogVisible"
      width="455px"
      append-to-body
      @close="closeDialog"
    >
      <div class="logo_box">
        <div id="img">
          <div v-if="string_type===1" class="backgroudcolor" :style="{ background: backgroudcolor }">
            <span v-if="logo_name == ''">
              LOGO
            </span>
            <span v-else>
              {{ logo_name }}
            </span>
          </div>
          <div v-else class="backgroudcolor2" :style="{ background: backgroudcolor }">
            <div class="logo_background">
              <span class="logo_name">
                {{ logo_name }}
              </span>
            </div>
          </div>
        </div>
        <div>
          <div class="custom">
            <div class="top">
              <span class="asterisk">*</span>
              <span class="custom_text">自定义LOGO文字：</span>
            </div>
            <div class="input">
              <el-input v-model="logo_name" class="custom_text_input" style="width: 291px;" placeholder="请输入LOGO文字（2-4个汉子）" maxlength="4" minlength="2" />
            </div>
            <div class="color_box2">
              <div>
                <span class="asterisk">*</span>
                <span class="custom_colors">自定义LOGO颜色：</span>
              </div>
              <div class="color_box_div">
                <div
                  v-for="(item,index) in color_arr"
                  :key="index"
                >
                  <div class="color_box" :style="{ background: item}" @click="backgroudcolor = item">
                    <img v-if="item == backgroudcolor" src="../../../assets/images/company/crm/check_mark.png" alt="" class="color_img">
                  </div>
                </div>
              </div>
            </div>
            <div class="logo_submit_box">
              <el-button size="small" type="primary" :loading="logoLoading" @click="save_logo">生成</el-button>
              <el-button size="small" @click="closeDialog">取消</el-button>
            </div>
          </div>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getFieldRule } from '@/api/configuration'
import { companyEdit, sendCompanyLogo } from '@/api/company'
import { validMobile, validEmail, validUrl } from '@/utils/validate'
import { getToken } from '@/utils/auth'
import { getClassify } from '@/api/classify'
import html2canvas from 'html2canvas'
import apiArr from '@/api'

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
      string_type: 1,
      color_arr: ['#3E6EF9', '#FF8432', '#6647FF', '#8FAC0C', '#3B83C0', '#2CE7F3', '#FF5656', '#21C78E', '#FECD25', '#F02D94', '#8EBBFB', '#FEA68F', '#BCB4E3', '#DFE2AD', '#98BAD8', '#AFE8EC', '#FFE2E2', '#90E7D2', '#FEF3C6', '#F8B4E7'],
      logoDialogVisible: false,
      logo_name: '',
      backgroudcolor: '#3E6EF9',
      secrecyHidden: false,
      infoLoading: true,
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
              max: 6,
              message: '长度在 1 到 6 个汉字',
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
      },
      logoLoading: false
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  watch: {
    logo_name: {
      handler(newValue, oldValue){
        var pattern = new RegExp('[\u4E00-\u9FA5]+')

        var str = newValue

        if (pattern.test(str) && newValue.toString().length === 4){
          this.string_type = 2
        } else {
          this.string_type = 1
        }
      }
    }
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchData()
  },
  methods: {
    closeDialog(){
      this.logoDialogVisible = false
      this.logo_name = ''
    },
    save_logo(){
      this.logoLoading = true
      const name_length = this.logo_name.length
      if (name_length < 2){
        this.$message.error('自定义LOGO文字至少2个汉字')
        this.logoLoading = false
        return false
      }
      if (name_length > 4){
        this.$message.error('自定义LOGO文字至多4个汉字')
        this.logoLoading = false
        return false
      }
      if (this.form.logo > 0){
        this.$confirm('检测到已存在企业Logo,继续保存将替换原有Logo,是否确认替换？', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.onsubmitLogo()
        })
      } else {
        this.onsubmitLogo()
      }
    },
    onsubmitLogo(){
      html2canvas(document.querySelector('#img'), { width: 80, height: 80, allowTaint: false, useCORS: true }).then(canvas => {
        sendCompanyLogo({ imgBase64: canvas.toDataURL(), company_id: this.id })
          .then(response => {
            this.$message.success(response.message)
            this.logoUrl = response.data.file_url
            this.form.logo = response.data.file_id
            this.logoDialogVisible = false
            this.logoLoading = false
            return true
          })
          .catch(() => {
            this.logoLoading = false
          })
      })
    },
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
          this.secrecyHidden = this.form.contact.is_secrecy != 1
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
          insertData.contact.is_secrecy = this.secrecyHidden === true ? 0 : 1
          companyEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              // setTimeout(function() {
              //   that.$router.push('/user/company/list')
              // }, 1500)
              this.$emit('companyDetails')
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
  bottom: 0;
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
  width: 445px;
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
.sendLogo{
  color: #2196f3;
  margin: 12px 0 2px 10px;
  display: block;
  height: 21px;
  line-height: 21px;
  font-size: 13px;
  cursor: pointer;
}
.backgroudcolor{
  width: 80px;
  height: 80px;
  color: white;
  text-align: center;
  line-height: 77px;
  font-size: 24px;
  border-radius: 5px;
  float: left;
}
.backgroudcolor2{
  width: 80px;
  height: 80px;
  color: white;
  text-align: center;
  font-size: 24px;
  border-radius: 5px;
  float: left;
}
.custom {
  padding: 0 0 39px 99px;
  text-align: left;
}
.top{
  padding-bottom: 10px;
}
.input .el-input{
  width: 245px;
}
.input .el-input__inner{
  height: 26px;
}
.color_box{
  width: 19px;
  height: 19px;
  float: left;
  margin-left: 10px;
  margin-bottom: 9px;
  border-radius: 10px;
}
.logo_box{
  height: 278px;
  padding-top: 18px;
}
.logo_background{
  width: 60px;
  margin: 0 auto;
  margin-top: 10px;
  margin: 8px 0 0 13px
}
.logo_name{
  letter-spacing: 6px;
  line-height: 31px;
}
.asterisk{
  color: #FF4848;
  font-size: 13px
}
.custom_colors{
  color: #303133;
  font-size: 13px;
  padding-left: 5px;
  font-weight: bold;
}
.color_box_div{
  padding-top: 14px;
}
.custom_text{
  font-size: 13px;
  color: #303133;
  padding-left: 5px;
  font-weight: bold;
}
.custom_text_input{
  height: 25px;
  width: 120%;
}
.color_img{
  padding-left: 3px
}
.logo_submit_box{
  padding: 81px 0 0 21px;
}
.color_box2{
  padding-top: 39px;
}
</style>
