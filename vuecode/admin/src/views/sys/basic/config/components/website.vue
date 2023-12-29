<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item
        label="网站名称"
        prop="sitename"
      >
        <el-input
          v-model="form.sitename"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="网站域名"
        prop="sitedomain"
      >
        <el-input
          v-model="form.sitedomain"
          class="middle"
        />
        <span class="smalltip">
          <i class="el-icon-info" />
          结尾不要加“/”
        </span>
      </el-form-item>
      <el-form-item
        label="安装目录"
        prop="sitedir"
      >
        <el-input
          v-model="form.sitedir"
          class="middle"
        />
        <span class="smalltip">
          <i class="el-icon-info" />
          以“/”开头和结尾, 如果安装在根目录，则为“/”
        </span>
      </el-form-item>
      <el-form-item
        label="web端会员中心目录名称"
        prop="member_dirname"
      >
        <el-input
          v-model="form.member_dirname"
          class="middle"
        />
        <span class="smalltip">
          <i class="el-icon-info" />
          只填写目录名称，不要加“/”
        </span>
      </el-form-item>
      <el-form-item
        label="联系电话"
        prop="contact_tel"
      >
        <el-input
          v-model="form.contact_tel"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="联系邮箱"
        prop="contact_email"
      >
        <el-input
          v-model="form.contact_email"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="联系地址"
        prop="contact_address"
      >
        <el-input
          v-model="form.contact_address"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="服务时间"
        prop="service_time"
      >
        <el-input
          v-model="form.service_time"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="客服qq"
        prop="service_qq"
      >
        <el-input
          v-model="form.service_qq"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="申请友链qq"
        prop="flink_qq"
      >
        <el-input
          v-model="form.flink_qq"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="其他说明"
        prop="bottom_other"
      >
        <el-input
          v-model="form.bottom_other"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="网站备案号(ICP)"
        prop="icp"
      >
        <el-input
          v-model="form.icp"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="公网安备"
        prop="network_security"
      >
        <el-input
          v-model="form.network_security"
          class="middle"
        />
      </el-form-item>
      <el-form-item label="暂时关闭网站">
        <el-switch v-model="form.isclose" />
      </el-form-item>
      <el-form-item label="关闭网站原因">
        <el-input
          v-model="form.close_reason"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="第三方统计代码"
        prop="statistics"
      >
        <el-input
          v-model="form.statistics"
          class="middle"
          type="textarea"
          :rows="4"
        />
      </el-form-item>
      <el-form-item
        label="网站logo"
        prop="logo"
      >
        <el-upload
          class="logo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handleLogoSuccess"
          :before-upload="beforeLogoUpload"
        >
          <img
            v-if="logoUrl"
            :src="logoUrl"
            class="logo"
          >
          <i
            v-else
            class="el-icon-plus logo-uploader-icon"
          />
          <div v-if="logoUrl" class="transparent" @click.stop="imageDel('logoUrl', 'form.logo')">
            <i class="el-icon-delete" />
            删除
          </div>
        </el-upload>
        <span class="smalltip">
          <i class="el-icon-info" />
          建议尺寸170*70
        </span>
      </el-form-item>
      <el-form-item
        label="网站方形logo"
        prop="square_logo"
      >
        <el-upload
          class="squarelogo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="(res,file)=>{handleSquareLogoSuccess('squarelogo',res,file)}"
          :before-upload="beforeLogoUpload"
        >
          <img
            v-if="squarelogoUrl"
            :src="squarelogoUrl"
            class="squarelogo"
          >
          <i
            v-else
            class="el-icon-plus squarelogo-uploader-icon"
          />
          <div v-if="squarelogoUrl" class="transparent" @click.stop="imageDel('squarelogoUrl', 'form.square_logo')">
            <i class="el-icon-delete" />
            删除
          </div>
        </el-upload>
        <span class="smalltip">
          <i class="el-icon-info" />
          建议尺寸200*200
        </span>
      </el-form-item>

      <el-form-item
        label="电子营业执照"
        prop=""
      >
        <el-input
          v-model="form.qualification_publicity.business_license"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="电子营业执照图片"
      >
        <el-upload
          class="squarelogo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="(res,file)=>{handleSquareLogoSuccess('business_license',res,file)}"
          :before-upload="beforeLogoUpload"
        >
          <img
            v-if="business_license_url"
            :src="business_license_url"
            class="squarelogo"
          >
          <i
            v-else
            class="el-icon-plus squarelogo-uploader-icon"
          />
          <div v-if="business_license_url" class="transparent" @click.stop="imageDel('business_license_url', 'form.qualification_publicity.business_license_id')">
            <i class="el-icon-delete" />
            删除
          </div>
        </el-upload>
      </el-form-item>
      <el-form-item
        label="人力资源服务许可证"
        prop="service_license"
      >
        <el-input
          v-model="form.qualification_publicity.service_license"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="人力资源服务许可证图片"
        prop="square_logo"
      >
        <el-upload
          class="squarelogo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="(res,file)=>{handleSquareLogoSuccess('service_license',res,file)}"
          :before-upload="beforeLogoUpload"
        >
          <img
            v-if="service_license_url"
            :src="service_license_url"
            class="squarelogo"
          >
          <i
            v-else
            class="el-icon-plus squarelogo-uploader-icon"
          />
          <div v-if="service_license_url" class="transparent" @click.stop="imageDel('service_license_url', 'form.qualification_publicity.service_license_id')">
            <i class="el-icon-delete" />
            删除
          </div>
        </el-upload>
      </el-form-item>
      <el-form-item
        label="增值电信业务经营许可证"
      >
        <el-input
          v-model="form.qualification_publicity.business_licenses"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="增值电信业务经营许可证图片"
      >
        <el-upload
          class="squarelogo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="(res,file)=>{handleSquareLogoSuccess('business_licenses',res,file)}"
          :before-upload="beforeLogoUpload"
        >
          <img
            v-if="business_licenses_url"
            :src="business_licenses_url"
            class="squarelogo"
          >
          <i
            v-else
            class="el-icon-plus squarelogo-uploader-icon"
          />
          <div v-if="business_licenses_url" class="transparent" @click.stop="imageDel('business_licenses_url', 'form.qualification_publicity.business_licenses_id')">
            <i class="el-icon-delete" />
            删除
          </div>
        </el-upload>
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  data() {
    var validateSitedomain = (rule, value, callback) => {
      if (value.endsWith('/')) {
        callback(new Error('域名结尾不要加“/”'))
      } else {
        callback()
      }
    }
    var validateSitedir = (rule, value, callback) => {
      if (!value.startsWith('/')) {
        callback(new Error('安装目录要以“/”开始'))
      } else if (!value.endsWith('/')) {
        callback(new Error('安装目录要以“/”结束'))
      } else {
        callback()
      }
    }
    return {
      infoLoading: true,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      form: {
        sitename: '',
        sitedomain: '',
        sitedir: '',
        member_dirname: '',
        contact_tel: '',
        contact_email: '',
        contact_address: '',
        service_time: '',
        service_qq: '',
        bottom_other: '',
        icp: '',
        network_security: '',
        service_license: '',
        flink_qq: '',
        isclose: false,
        close_reason: '',
        statistics: '',
        logo: '',
        square_logo: '',
        qualification_publicity: {
          business_license: '', // 电子营业执照
          business_license_id: 0, // 电子营业执照图片ID
          business_licenses: '', // 增值电信业务经营许可证
          business_licenses_id: 0, // 增值电信业务经营许可证图片ID
          service_license_id: 0, // 人力资源服务许可证图片ID
          service_license: ''
        }
      },
      business_license_url: '', // 电子营业执照图片
      service_license_url: '', // 人力资源服务许可证图片
      business_licenses_url: '', // 增值电信业务经营许可证图片
      logoUrl: '',
      squarelogoUrl: '',
      rules: {
        sitename: [
          { required: true, message: '请输入网站名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        sitedomain: [
          { required: true, message: '请输入网站域名', trigger: 'blur' },
          { max: 100, message: '长度在 1 到 100 个字符', trigger: 'blur' },
          { validator: validateSitedomain, trigger: 'blur' }
        ],
        sitedir: [
          { required: true, message: '请输入安装目录', trigger: 'blur' },
          { validator: validateSitedir, trigger: 'blur' }
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
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            sitename,
            sitedomain,
            sitedir,
            member_dirname,
            contact_tel,
            contact_email,
            contact_address,
            service_time,
            service_qq,
            bottom_other,
            icp,
            network_security,
            service_license,
            flink_qq,
            isclose,
            close_reason,
            statistics,
            logo,
            square_logo,
            qualification_publicity
          } = { ...response.data }
          this.form = {
            sitename,
            sitedomain,
            sitedir,
            member_dirname,
            contact_tel,
            contact_email,
            contact_address,
            service_time,
            service_qq,
            bottom_other,
            icp,
            network_security,
            service_license,
            flink_qq,
            isclose,
            close_reason,
            statistics,
            logo,
            square_logo,
            qualification_publicity
          }
          this.form.isclose = this.form.isclose == 1
          this.logoUrl = response.data.logoUrl
          this.squarelogoUrl = response.data.squarelogoUrl
          this.infoLoading = false
          this.business_license_url = response.data.qualification_publicity.business_license_url
          this.service_license_url = response.data.qualification_publicity.service_license_url
          this.business_licenses_url = response.data.qualification_publicity.business_licenses_url
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      this.form.service_license = this.form.qualification_publicity.service_license

      const insertData = { ...this.form }
      console.log(insertData)
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.isclose = insertData.isclose === true ? 1 : 0
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    handleLogoSuccess(res, file) {
      if (res.code === 200) {
        this.logoUrl = URL.createObjectURL(file.raw)
        this.form.logo = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    handleSquareLogoSuccess(item, res, file) {
      if (res.code === 200) {
        switch (item) {
          case 'squarelogo': // 网站方形logo
            this.squarelogoUrl = URL.createObjectURL(file.raw)
            this.form.square_logo = res.data.file_id
            break
          case 'business_license': // 电子营业执照
            this.business_license_url = URL.createObjectURL(file.raw)
            this.form.qualification_publicity.business_license_id = res.data.file_id
            break
          case 'service_license': // 人力资源服务许可证
            this.service_license_url = URL.createObjectURL(file.raw)
            this.form.qualification_publicity.service_license_id = res.data.file_id
            break
          case 'business_licenses':// 增值电信业务经营许可证
            this.business_licenses_url = URL.createObjectURL(file.raw)
            this.form.qualification_publicity.business_licenses_id = res.data.file_id
            break
        }
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
    /**
     * 删除图片
     * @param field
     * @param fieldId
     * cy 2023-8-1
     */
    imageDel(field, fieldId){
      var that = this;
      that[field] = ''
      // 由于fieldId是多级的，所以需要遍历处理
      const propertyArr = fieldId.split('.');
      var lastProperty = propertyArr.pop();
      for (const property of propertyArr) {
        that = that[property];
      }
      that[lastProperty] = 0
    }
  }
}
</script>

<style scoped>
.squarelogo-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 102px;
  height: 102px;
  position: relative;
}
.squarelogo-uploader img {
  width: 100px;
  height: 100px;
  border-radius: 6px;
}
.squarelogo-uploader:hover {
  border-color: #409eff;
}
.squarelogo-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 100px;
  height: 100px;
  line-height: 100px;
  text-align: center;
}
.logo-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 172px;
  height: 72px;
  position: relative;
}
.logo-uploader img {
  width: 170px;
  height: 70px;
  border-radius: 6px;
}
.logo-uploader:hover {
  border-color: #409eff;
}
.logo-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 170px;
  height: 70px;
  line-height: 70px;
  text-align: center;
}
.transparent{
  cursor: pointer;
  z-index: 1;
  font-size: 12px;
  border-radius: 3px;
  text-align: center;
  line-height: 25px;
  position: absolute;
  bottom: 0;
  color: #d5cece;
  left: 1px;
  width: 100%;
  height: 25px;
  background: rgba(0, 0, 0, 0.4);
}
</style>
