<template>
  <div>
    <el-form
      :model="form"
      label-position="right"
      ref="form"
      label-width="100px"
    >
      <el-alert
        v-show="scanNewUpload === true"
        title="企业logo已上传，点击页面底部保存按钮即可保存！"
        type="warning"
        style="margin-bottom: 10px"
      ></el-alert>
      <el-card>
        <company-title>企业资料</company-title>
        <div class="enterpriseData">基本信息</div>
        <el-form-item
          label="企业名称"
          prop="basic.companyname"
          :rules="[
            {
              required: true,
              message: '请输入公司名称',
              trigger: 'blur',
            },
            {
              max: 60,
              message: '长度在 1 到 60 个字符',
              trigger: 'blur',
            },
          ]"
        >
          <el-input
            :disabled="companynameDisabled"
            v-model="form.basic.companyname"
            placeholder="请填写"
            class="el-select_width"
          >
            ></el-input
          >
        </el-form-item>
        <el-form-item
          v-if="field_rule.basic.short_name.is_display == 1"
          :rules="[
            {
              required:
                field_rule.basic.short_name.is_require == 1 ? true : false,
              message: '请输入' + field_rule.basic.short_name.field_cn,
              trigger: 'blur',
            },
            { max: 60, message: '长度在 1 到 60 个字符', trigger: 'blur' },
          ]"
          :label="field_rule.basic.short_name.field_cn"
          prop="basic.short_name"
        >
          <el-input
            v-model="form.basic.short_name"
            placeholder="请填写"
            class="el-select_width"
          >
            ></el-input
          >
        </el-form-item>
        <el-form-item
          label="企业性质"
          prop="basic.nature"
          :rules="[
            {
              required: true,
              message: '请选择公司性质',
              trigger: 'change',
            },
          ]"
        >
          <el-select v-model="form.basic.nature" class="el-select_width">
            <el-option
              v-for="item in columnsNature"
              :key="item.id"
              :label="item.text"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item
          label="企业规模"
          prop="basic.scale"
          :rules="[
            {
              required: true,
              message: '请选择公司规模',
              trigger: 'change',
            },
          ]"
        >
          <el-select v-model="form.basic.scale" class="el-select_width">
            <el-option
              v-for="item in columnsScale"
              :key="item.id"
              :label="item.text"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item
          label="所属行业"
          prop="basic.trade"
          :rules="[
            {
              required: true,
              message: '请选择所属行业',
              trigger: 'change',
            },
          ]"
        >
          <el-select v-model="form.basic.trade" class="el-select_width">
            <el-option
              v-for="item in columnsTrade"
              :key="item.id"
              :label="item.text"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="field_rule.basic.registered.is_display === 1"
          :rules="[
            {
              required: field_rule.basic.registered.is_require === 1,
              message: '请输入注册资金',
              trigger: 'blur',
            },
            {
              max: 15,
              message: '长度在 1 到 15 个字符',
              trigger: 'blur',
            },
          ]"
          :label="field_rule.basic.registered.field_cn"
          prop="basic.registered"
        >
          <el-input
            v-model="form.basic.registered"
            :placeholder="'请输入' + field_rule.basic.registered.field_cn"
            class="input-with-select el-select_width"
          >
            <el-select
              style="width: 120px"
              slot="append"
              v-model="form.basic.currency"
              placeholder="请选择"
            >
              <el-option label="万人民币" :value="0" />
              <el-option label="万美元" :value="1" />
            </el-select>
          </el-input>
        </el-form-item>
        <el-form-item
          v-if="field_rule.info.website.is_display === 1"
          :rules="[
            {
              required: field_rule.info.website.is_require === 1,
              message: '请输入' + field_rule.info.website.field_cn,
              trigger: 'blur',
            },
            {
              max: 50,
              message: '长度在 0 到 50 个字符',
              trigger: 'blur',
            },
            { validator: validateUrl, trigger: 'blur' },
          ]"
          :label="field_rule.info.website.field_cn"
          prop="info.website"
        >
          <el-input
            v-model="form.info.website"
            placeholder="http://"
            class="el-select_width"
          >
          </el-input>
        </el-form-item>
        <el-form-item
          v-if="field_rule.basic.tag.is_display == 1"
          :rules="[
            {
              required: field_rule.basic.tag.is_require == 1 ? true : false,
              message: '请选择' + field_rule.basic.tag.field_cn,
              trigger: 'change',
            },
          ]"
          :label="field_rule.basic.tag.field_cn"
          prop="basic.tag"
        >
          <el-select
            class="el-select_width"
            v-model="form.basic.tag"
            multiple
            filterable
            allow-create
            default-first-option
            :multiple-limit="10"
            placeholder="请选择"
          >
            <el-option
              v-for="(item, index) in columnsTag"
              :key="index"
              :label="item.text"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item
          label="所在地区"
          prop="basic.citycategory_arr"
          :rules="[
            {
              type: 'array',
              type: 'array',
              required: true,
              message: '请选择所在地区',
              trigger: 'change',
            },
          ]"
        >
          <el-cascader
            ref="cascaderAddr"
            v-if="areaDistrict.length > 0"
            class="el_input_widht"
            v-model="form.basic.citycategory_arr"
            :options="areaDistrict"
            :show-all-levels="true"
            filterable
            @change="setLocation"
          ></el-cascader>
          <span @click="handlerShowMap">
            <el-input
              class="el-select_width"
              v-model="form.info.address"
              placeholder="请标注详细地址"
              disabled
            ></el-input>
          </span>
          <div class="inp_bz_map" @click="handlerShowMap">标注</div>
        </el-form-item>
        <el-form-item
          v-if="field_rule.info.short_desc.is_display == 1"
          :rules="[
            {
              required:
                field_rule.info.short_desc.is_require == 1 ? true : false,
              message: '请输入公司简介',
              trigger: 'blur',
            },
            {
              max: 255,
              message: '长度在 0 到 255 个字符',
              trigger: 'blur',
            },
          ]"
          :label="field_rule.info.short_desc.field_cn"
          prop="info.short_desc"
        >
          <el-input
            v-model="form.info.short_desc"
            type="textarea"
            class="synopsis"
            placeholder="请简短介绍一下您的企业"
            autosize
          ></el-input>
        </el-form-item>
        <el-form-item
          v-if="field_rule.info.content.is_display === 1"
          :rules="[
            {
              required: field_rule.info.content.is_require === 1,
              message: '请输入' + field_rule.info.content.field_cn,
              trigger: 'blur',
            },
          ]"
          :label="field_rule.info.content.field_cn"
          prop="info.content"
        >
          <el-input
            v-model="form.info.content"
            type="textarea"
            class="synopsis"
            placeholder="请介绍一下您的企业"
            :autosize="{ minRows: 3 }"
          ></el-input>
        </el-form-item>
        <!-- 文件上传 -->
        <el-upload
          :http-request="handlerUpload"
          action="#"
          :show-file-list="false"
          class="avatar-uploader"
        >
          <div class="upload_icon_con">
            <img v-if="form.basic.logo" :src="logoUrl" class="avatar" />
            <i v-else class="el-icon-plus avatar-uploader-icon"></i><br />
            <p v-if="!form.basic.logo">上传logo</p>
          </div>
          <div class="uploader_text">
            <div class="Wx_upload">
              <img src="../../../assets/images/baseinfo_icon.png" />
              使用微信扫码上传
              <div class="codeImg">
                <img :src="scanQrcode" />
                <p>
                  微信扫描二维码<br />
                  快速上传手机相册图片
                </p>
                <em>&#9670;</em>
                <span>&#9670;</span>
              </div>
            </div>
            建议尺寸：120*120
          </div>
        </el-upload>
      </el-card>
      <el-card>
        <company-title>联系方式</company-title>
        <el-form-item
          label="联系人"
          prop="contact.contact"
          :rules="[
            {
              required: true,
              message: '请输入联系人',
              trigger: 'blur',
            },
            {
              max: 30,
              message: '长度在 1 到 30 个字符',
              trigger: 'blur',
            },
          ]"
        >
          <el-input
            v-model="form.contact.contact"
            class="el-select_width"
            placeholder="请输入联系人"
          ></el-input>
        </el-form-item>
        <el-form-item
          label="联系电话"
          prop="contact.mobile"
          :rules="[
            {
              required: true,
              message: '请输入联系手机',
              trigger: 'blur',
            },
            { validator: validateContactMobile, trigger: 'blur' },
          ]"
        >
          <el-input
            v-model="form.contact.mobile"
            class="el-select_width"
            placeholder="请输入联系电话"
            @input="hanlderMobile"
          ></el-input>
        </el-form-item>
        <el-form-item
          v-if="field_rule.contact.weixin.is_display == 1"
          :rules="[
            {
              required:
                field_rule.contact.weixin.is_require == 1 ? true : false,
              message: '请输入' + field_rule.contact.weixin.field_cn,
              trigger: 'blur',
            },
          ]"
          :label="field_rule.contact.weixin.field_cn"
          prop="contact.weixin"
        >
          <el-input
            v-model="form.contact.weixin"
            class="el-select_width"
            :placeholder="'请输入' + field_rule.contact.weixin.field_cn"
            @input="hanlderWeixin"
          >
            ></el-input
          >
          &nbsp;
          <el-checkbox v-model="weixin_sync_mobile" @change="handlerSync"
            >同手机号</el-checkbox
          >
        </el-form-item>
        <el-form-item
          v-if="field_rule.contact.telephone.is_display == 1"
          :rules="[
            {
              required:
                field_rule.contact.telephone.is_require == 1 ? true : false,
              message: '请输入' + field_rule.contact.telephone.field_cn,
              trigger: 'blur',
            },
          ]"
          :label="field_rule.contact.telephone.field_cn"
          prop="contact.telephone"
        >
          <el-input
            v-model="form.contact.telephone"
            class="el-select_width"
            :placeholder="
              '请输入' +
              field_rule.contact.telephone.field_cn +
              '(格式:区号+电话号)'
            "
          >
            ></el-input
          >
        </el-form-item>
        <el-form-item
          v-if="field_rule.contact.email.is_display === 1"
          :rules="[
            {
              required: field_rule.contact.email.is_require === 1,
              message: '请输入' + field_rule.contact.email.field_cn,
              trigger: 'blur',
            },
            { validator: validateContactEmail, trigger: 'blur' },
          ]"
          :label="field_rule.contact.email.field_cn"
          prop="contact.email"
        >
          <el-input
            v-model="form.contact.email"
            class="el-select_width"
            :placeholder="'请输入' + field_rule.contact.email.field_cn"
            >></el-input
          >
        </el-form-item>
        <el-form-item
          v-if="field_rule.contact.qq.is_display == 1"
          :rules="[
            {
              required: field_rule.contact.qq.is_require == 1 ? true : false,
              message: '请输入' + field_rule.contact.qq.field_cn,
              trigger: 'blur',
            },
          ]"
          :label="field_rule.contact.qq.field_cn"
          prop="contact.qq"
        >
          <el-input
            v-model="form.contact.qq"
            class="el-select_width"
            :placeholder="'请输入' + field_rule.contact.qq.field_cn"
            >></el-input
          >
        </el-form-item>
        <el-form-item label="">
          <el-button
            class="preservation_btn"
            type="primary"
            @click="onSubmit('form')"
            >保存</el-button
          >
        </el-form-item>
      </el-card>
    </el-form>

    <div class="dateRange">
      <el-dialog
        title="标注详情地址"
        :visible.sync="showMap"
        width="800px"
        height="500px"
        @opened="handlerMapOpened"
        @closed="handlerMapClose"
      >
        <Mapset
          ref="mapset"
          title="地图标注"
          :location="location"
          :mapLat="form.basic.map_lat"
          :mapLng="form.basic.map_lng"
          :mapZoom="form.basic.map_zoom"
          :address="form.info.address"
        ></Mapset>
        <div class="preserveBtn">
          <span slot="footer" class="dialog-footer ">
            <el-button type="primary" @click="handlerMapClose(true)">保 存</el-button>
            <el-button @click="handlerMapClose">取 消</el-button>
          </span>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
var getCascaderObj = function (val, opt) {
  return val.map(function (value) {
    for (var itm of opt) {
      if (itm.value == value) { opt = itm.children; return itm; }
    }
    return null;
  });
}
import Mapset from '@/components/company/Mapset'
import { validMobile, validEmail, validUrl } from "@/utils/validate";
import http from "@/utils/http";
import api from "@/api";
export default {
  components: {
    Mapset
  },
  data() {
    return {
      location: '',
      companynameDisabled: false,
      scanNewUpload: false,
      showMap: false,
      areaDistrictKey: 0,
      logoUrl: "",
      weixin_sync_mobile: false,
      // 企业资料对象
      field_rule: {
        basic: {
          logo: {},
          short_name: {},
          registered: {},
          tag: {},
        },
        contact: {
          weixin: {},
          telephone: {},
          qq: {},
          email: {}
        },
        info: {
          website: {},
          short_desc: {},
          content: {}
        }
      },
      form: {
        basic: {
          logo: 0,
          companyname: "",
          short_name: "",
          registered: 0,
          currency: 0,
          nature: '',
          trade: '',
          scale: '',
          citycategory_arr: [],
          district1: 0,
          district2: 0,
          district3: 0,
          map_lat: 0,
          map_lng: 0,
          map_zoom: 0,
          tag: []
        },
        info: {
          content: "",
          short_desc: "",
          website: "",
          address: ""
        },
        contact: {
          contact: "",
          mobile: "",
          weixin: "",
          telephone: "",
          email: "",
          qq: ""
        }
      },
      dialogVisible: false,
      timer: '',
      scanQrcode: ''
    };
  },
  created() {
    clearInterval(this.timer);
    this.$store.dispatch("getClassify", "companyNature");
    this.$store.dispatch("getClassify", "trade");
    this.$store.dispatch("getClassify", "companyScale");
    this.$store.dispatch("getClassify", "citycategory");
    this.$store.dispatch("getClassify", "jobTag");
    this.fetchData();
    let locationUrl = this.$store.state.config.mobile_domain + 'scan_upload?type=company_logo&access=' + this.$store.state.userToken
    locationUrl = encodeURIComponent(locationUrl)
    this.scanQrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + locationUrl
  },
  mounted() {
    this.timer = setInterval(this.scanUploadResult, 5000);
  },
  beforeDestroy() {
    clearInterval(this.timer);
  },
  computed: {
    columnsNature() {
      return this.$store.state.classifyCompanyNature;
    },
    columnsTrade() {
      return this.$store.state.classifyTrade;
    },
    columnsScale() {
      return this.$store.state.classifyCompanyScale;
    },
    areaDistrict() {
      return this.$store.state.classifyCityCategory;
    },
    columnsTag() {
      return this.$store.state.classifyJobTag;
    }
  },
  methods: {

    setLocation(e) {
      let that = this
      that.location = ''
      let locationArr = getCascaderObj(e, that.areaDistrict);
      locationArr.forEach(element => {
        that.location += element.label
      });
    },
    scanUploadResult() {
      http
        .post(api.scan_upload_result, { type: 'company_logo' })
        .then(res => {
          if (res.data != 0) {
            this.scanNewUpload = true
            this.logoUrl = res.data.file_url
            this.form.basic.logo = res.data.file_id
          }
        })
        .catch(() => { })
    },
    validateContactMobile(rule, value, callback) {
      if (!validMobile(value)) {
        callback(new Error("请输入正确的手机号"));
      } else {
        callback();
      }
    },
    validateContactEmail(rule, value, callback) {
      if (value === "" || value === undefined) {
        callback();
      }
      if (!validEmail(value)) {
        callback(new Error("请输入正确的邮箱"));
      } else {
        callback();
      }
    },
    validateUrl(rule, value, callback) {
      if (value === "" || value === undefined) {
        callback();
      }
      if (!validUrl(value)) {
        callback(new Error("请输入正确的网址 https://www.xxx.com"));
      } else {
        callback();
      }
    },
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (
            this.field_rule.basic.logo.is_display === 1 &&
            this.field_rule.basic.logo.is_require === 1 &&
            this.form.basic.logo === 0
          ) {
            this.$message.error("请上传" + this.field_rule.basic.logo.field_cn);
            return false;
          }
          http
            .post(api.company_profile, {
              basic: this.form.basic,
              info: this.form.info,
              contact: this.form.contact
            })
            .then(res => {
              this.$message({ type: "success", message: res.message });
            })
            .catch(() => { });
        } else {
          return false;
        }
      });
    },
    hanlderMobile(val) {
      if (this.weixin_sync_mobile === true) {
        this.form.contact.weixin = val;
      }
    },
    hanlderWeixin() {
      this.weixin_sync_mobile = this.form.contact.weixin === this.form.contact.mobile;
    },
    handlerSync() {
      if (this.weixin_sync_mobile === true) {
        this.form.contact.weixin = this.form.contact.mobile;
      }
    },
    fetchData() {
      http
        .get(api.company_profile, {})
        .then(res => {
          const { basic, contact, info, field_rule } = { ...res.data };
          if (basic.companyname !== undefined && basic.companyname != '') {
            this.companynameDisabled = true
          }
          this.form.basic = {
            logo: basic.logo,
            companyname: basic.companyname,
            short_name: basic.short_name,
            registered: basic.registered,
            currency: basic.currency,
            nature: basic.nature ? basic.nature : '',
            trade: basic.trade ? basic.trade : '',
            scale: basic.scale ? basic.scale : '',
            district1: basic.district1,
            district2: basic.district2,
            district3: basic.district3,
            map_lat: basic.map_lat,
            map_lng: basic.map_lng,
            map_zoom: basic.map_zoom,
            tag: basic.tag,
            citycategory_arr: []
          };
          this.form.info = {
            content: info.content,
            short_desc: info.short_desc,
            website: info.website,
            address: info.address
          };
          this.form.contact = {
            contact: contact.contact,
            mobile: contact.mobile,
            weixin: contact.weixin,
            telephone: contact.telephone,
            email: contact.email,
            qq: contact.qq
          };
          if (
            this.form.contact.mobile !== "" &&
            this.form.contact.mobile === this.form.contact.weixin
          ) {
            this.weixin_sync_mobile = true;
          }
          for (const key in this.form.basic.tag) {
            if (!isNaN(this.form.basic.tag[key])) {
              this.form.basic.tag[key] = parseInt(this.form.basic.tag[key]);
            }
          }

          this.field_rule = field_rule;
          this.logoUrl = basic.logo_src;
          if (basic.district1 != 0) {
            this.form.basic.citycategory_arr.push(basic.district1);
          }
          if (basic.district2 != 0) {
            this.form.basic.citycategory_arr.push(basic.district2);
          }
          if (basic.district3 != 0) {
            this.form.basic.citycategory_arr.push(basic.district3);
          }
          this.location = basic.district_text_full
        })
        .catch(() => { });
    },
    handlerUpload(file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || "").split("/");
      let filetype = filetypeArr[1];
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (-1 == tyepArr.indexOf(filetype)) {
        this.$message.error('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$message.error('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      http
        .postFormData(api.uploadFile, { file: fileRaw })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.form.basic.logo = res.data.file_id;
            this.logoUrl = res.data.file_url
            this.$message({ type: 'success', message: res.message })
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlerShowMap() {
      this.showMap = true
    },
    handlerMapOpened() {
      this.$refs.mapset.initCB()
    },
    handlerMapClose(setData) {
      this.$refs.mapset.mapLocation.address = ''
      if (setData === true) {
        if(this.$store.state.baiduMapFrom.location == ''){
          return this.$message("请填写详细信息")
        }
        this.form.basic.map_lat = this.$refs.mapset.mapData.lat
        this.form.basic.map_lng = this.$refs.mapset.mapData.lng
        this.form.basic.map_zoom = this.$refs.mapset.mapData.zoom
        // this.form.info.address = this.$refs.mapset.mapData.address
        this.form.info.address = this.$store.state.baiduMapFrom.location
      }
      this.showMap = false
    }
  }
};
</script>

<style lang="scss" scoped>
.inp_bz_map {
  display: inline-block;
  padding-left: 27px;
  margin-left: 10px;
  font-size: 14px;
  background: url("../../../assets/images/tagging.png") 0 center no-repeat;
  cursor: pointer;
}
.el-form {
  margin-left: 15px;
}
.el-select_width {
  width: 430px;
}
.el_input_widht {
  width: 219px;
  margin-right: 20px;
}
.enterpriseData {
  font-size: 16px;
  color: #333;
  margin-bottom: 20px;
}
.synopsis {
  width: 890px;
  // height: 190px;
}

.preservation_btn {
  width: 258px;
  // margin-left: 65px;
}
.el_checkbox {
  margin-left: 65px;
}
.el-card {
  margin-bottom: 10px;
  position: relative;
}
.avatar-uploader {
  position: absolute;
  left: 885px;
  top: 141px;
}
.upload_icon_con {
  width: 101px;
  margin: auto;
  height: 101px;
  border: 1px dashed #d9d9d9;
  color: #1787fb;
}
.upload_icon_con > p {
  padding-top: 13px;
}
.avatar-uploader .el-upload {
  border-radius: 6px;
  cursor: pointer;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  text-align: center;
  margin-top: 20px;
}
.avatar {
  width: 99px;
  height: 99px;
  display: block;
}
.uploader_text {
  text-align: left;
  margin-top: 10px;
  font-size: 14px;
  color: #a9a9a9;
  line-height: 23px;
  padding: 0 10px;
}
.avatar-uploader-icon {
  color: #1787fb;
}
.taggingImg {
  vertical-align: middle;
}
.el-form-item__content > span {
  cursor: pointer;
}
.el-form-item__content > a {
  color: #1787fb;
  cursor: pointer;
}
.Wx_upload {
  font-size: 13px;
  color: #4d9afc;
  position: relative;
}
.Wx_upload img {
  vertical-align: -2px;
}
.Wx_upload:hover .codeImg {
  display: block;
}
.codeImg {
  // width:150px ;
  // height: 160px;
  width: 180px;
  height: 240px;
  border: 1px solid #eeeeee;
  position: absolute;
  right: -190px;
  top: -108px;
  text-align: center;
  font-size: 12px;
  display: none;
}
.codeImg p {
  line-height: 18px;
}
.codeImg img {
  width: 150px;
  height: 150px;
  margin: 19px 0 10px 0;
}

.codeImg em,
.codeImg span {
  font-style: normal;
  font-size: 30px;
  position: absolute;
  left: -10px;
  top: 108px;
  color: #efefef;
}
.codeImg span {
  left: -8px;
  color: white;
}
.preserveBtn{
  margin-top: -10px;
  text-align: center;
}
</style>
