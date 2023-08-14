<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加实地认证</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/business/company/report')"
        >
          返回
        </el-button>
        <el-button
          style="float: right; padding: 0;"
          type="text"
          @click="onSubmit('form')"
          :disabled="issubmit"
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
        style="width:800px"
      >
        <el-divider content-position="left">工商登记信息</el-divider>
        <el-row>
          <el-col :span="24">
            <el-form-item label="选择企业" prop="company_id">
              <el-select
                v-model="form.company_id"
                filterable
                remote
                reserve-keyword
                placeholder="请输入企业ID/企业名称"
                :remote-method="companySearch"
                :loading="loading"
              >
                <el-option
                  v-for="item in options_companylist"
                  :key="item.id"
                  :label="item.companyname"
                  :value="item.id"
                  :disabled="item.company_id != null"
                >
                  <span style="float: left">{{ item.companyname }}</span>
                  <span style="float: right; color: #8492a6; font-size: 13px">
                    {{ item.company_id != null ? '已添加认证' : '' }}
                  </span>
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="企业法人" prop="corporate">
              <el-input v-model="form.corporate" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="主体类型" prop="com_type">
              <el-input v-model="form.com_type" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="成立时间" prop="reg_time">
              <el-date-picker
                v-model="form.reg_time"
                type="date"
                format="yyyy-MM-dd"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="注册资金" prop="reg_capital">
              <el-input v-model="form.reg_capital">
                <template slot="append">万元</template>
              </el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item label="注册地址" prop="reg_address">
              <el-input v-model="form.reg_address" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item
              label="办公地点"
              prop="office_address"
              style="display:block"
            >
              <el-input v-model="form.office_address" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item
              label="登记机关"
              prop="registrar"
              style="display:block"
            >
              <el-input v-model="form.registrar" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item label="经营范围" prop="scope" style="display:block">
              <el-input v-model="form.scope" type="textarea" rows="5" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-divider content-position="left">实地认证情况</el-divider>
        <el-row>
          <el-col :span="12">
            <el-form-item label="办公面积" prop="office_area">
              <el-input v-model="form.office_area">
                <template slot="append">平米</template>
              </el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="办公环境" prop="office_env">
              <el-radio-group v-model="form.office_env">
                <el-radio :label="1">一般</el-radio>
                <el-radio :label="2">良好</el-radio>
                <el-radio :label="3">优美</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="办公场所" prop="workplace">
              <el-input v-model="form.workplace" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="员工人数" prop="number">
              <el-input v-model="form.number" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="男女比例" prop="sex_ratio">
              <el-input v-model="form.sex_ratio" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="平均年龄" prop="average_age">
              <el-input v-model="form.average_age" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item label="乘车路线" prop="route">
              <el-input v-model="form.route" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item label="认证照片" style="display:block">
              <el-upload
                class="thumb-uploader"
                :action="apiUpload"
                :headers="headers"
                :show-file-list="false"
                :on-success="handleThumbSuccess"
                :before-upload="beforeThumbUpload"
              >
                <img v-if="form.thumb" :src="imageUrl" class="thumb">
                <i v-else class="el-icon-plus thumb-uploader-icon" />
              </el-upload>
              <span class="smalltip">
                <i class="el-icon-info" />
                最多可上传 5 张
              </span>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row v-if="img_list.length > 0">
          <el-col :span="24">
            <el-form-item label="已上传">
              <el-card
                v-for="(item, index) in img_list"
                :key="index"
                class="img-content-box"
                :body-style="{ padding: '0px' }"
              >
                <div class="imgbox">
                  <img
                    :src="
                      item.img_src != ''
                        ? item.img_src
                        : 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png'
                    "
                    class="image"
                  >
                </div>
                <div class="bottom ">
                  <el-button type="text" @click="funDelImg(index, item)">
                    删除
                  </el-button>
                </div>
              </el-card>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item
              label="人力资源师评价"
              prop="evaluation"
              style="display:block"
            >
              <el-input v-model="form.evaluation" type="textarea" rows="5" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="认证人" prop="certifier">
              <el-input v-model="form.certifier" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="认证时间">
              <el-date-picker
                v-model="form.addtime"
                type="date"
                format="yyyy-MM-dd"
                placeholder="如不选择则为当前时间"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item label=" ">
              <el-button type="primary" @click="onSubmit('form')" :disabled="issubmit">
                保存
              </el-button>
              <el-button @click="goto('/business/company/report')">
                返回
              </el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import {
  companyReportAdd,
  companyReportSearchCompany
} from '@/api/company_report'
import apiArr from '@/api'

export default {
  data() {
    return {
      issubmit: false,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      loading: false,
      options_companylist: [],
      img_list: [],
      form: {
        company_id: '',
        corporate: '',
        com_type: '',
        reg_time: '',
        reg_capital: '',
        reg_address: '',
        office_address: '',
        registrar: '',
        scope: '',
        office_area: '',
        office_env: 1,
        workplace: '',
        number: '',
        sex_ratio: '',
        average_age: '',
        route: '',
        img: '',
        evaluation: '',
        addtime: '',
        certifier: ''
      },
      imageUrl: '',
      rules: {
        company_id: [
          {
            required: true,
            message: '请选择企业',
            trigger: 'change'
          }
        ],
        corporate: [
          {
            required: true,
            message: '请填写企业法人',
            trigger: 'blur'
          }
        ],
        com_type: [
          {
            required: true,
            message: '请填写主体类型',
            trigger: 'blur'
          }
        ],
        reg_time: [
          {
            required: true,
            message: '请选择成立时间',
            trigger: 'change'
          }
        ],
        reg_capital: [
          {
            required: true,
            message: '请填写注册资金',
            trigger: 'blur'
          }
        ],
        reg_address: [
          {
            required: true,
            message: '请填写注册地址',
            trigger: 'blur'
          }
        ],
        office_address: [
          {
            required: true,
            message: '请填写办公地点',
            trigger: 'blur'
          }
        ],
        registrar: [
          {
            required: true,
            message: '请填写登记机关',
            trigger: 'blur'
          }
        ],
        scope: [
          {
            required: true,
            message: '请填写经营范围',
            trigger: 'blur'
          }
        ],
        evaluation: [
          {
            required: true,
            message: '请填写人力资源师评价',
            trigger: 'blur'
          }
        ],
        certifier: [
          {
            required: true,
            message: '请填写认证人',
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
  mounted() {},
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
  },
  methods: {
    companySearch(query) {
      if (query !== '') {
        this.loading = true
        companyReportSearchCompany({ keyword: query })
          .then(response => {
            this.options_companylist = response.data.items
            this.loading = false
          })
          .catch(() => {})
      } else {
        this.options = []
      }
    },
    onSubmit(formName) {
      const that = this
      that.issubmit = true
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (insertData.reg_time) {
            const d = new Date(insertData.reg_time)
            insertData.reg_time =
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
          if (insertData.addtime) {
            const d = new Date(insertData.addtime)
            insertData.addtime =
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
          companyReportAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/business/company/report')
              }, 1500)
              return true
            })
            .catch(() => {
              that.issubmit = false
              return false
            })
        } else {
          that.issubmit = false
          return false
        }
      })
    },
    handleThumbSuccess(res, file) {
      if (res.code == 200) {
        const img_info = {
          id: res.data.file_id,
          img_src: URL.createObjectURL(file.raw)
        }
        this.img_list.push(img_info)
        const img_arr = this.form.img == '' ? [] : this.form.img.split(',')
        img_arr.push(img_info.id)
        this.form.img = img_arr.join(',')
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeThumbUpload(file) {
      if (this.img_list.length >= 5) {
        this.$message.error('最多可上传5张')
        return false
      }
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
    },
    funDelImg(index, row) {
      this.img_list.splice(index, 1)
      const img_arr = this.form.img == '' ? [] : this.form.img.split(',')
      img_arr.splice(index, 1)
      this.form.img = img_arr.join(',')
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
.el-select {
  display: block;
}
.el-date-editor {
  width: 100%;
}
/* .el-textarea,
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
} */
.img-content-box {
  width: 200px;
  margin-right: 10px;
  float: left;
  margin-bottom: 10px;
}
.img-content-box .imgbox {
  width: 100%;
  display: block;
  height: 120px;
  border-bottom: 1px solid #e3e3e3;
  background-color: #999;
  overflow: hidden;
}
.img-content-box .bottom {
  /* margin-top: 13px; */
  line-height: 12px;
  text-align: center;
}
.img-content-box .botton {
  padding: 6px;
}
.img-content-box .image {
  width: 100%;
  display: block;
}
</style>
