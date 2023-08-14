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
          <span v-if="config.is_open_map == 1" @click="handlerShowMap">
            <el-input
              class="el-select_width"
              v-model="form.info.address"
              placeholder="请标注详细地址"
              disabled
            ></el-input>
          </span>
          <span v-else >
            <el-input
              class="el-select_width"
              v-model="form.info.address"
              placeholder="请输入详细地址"
            ></el-input>
          </span>
          <div class="inp_bz_map" @click="handlerShowMap" v-if="config.is_open_map == 1">标注</div>
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
        <div class="avatar-uploader-box">
          <div class="send_company_logo" @click="logoDialogVisible = true">生成LOGO</div>
        <!-- 文件上传 -->
          <el-upload
            ref="upload"
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
              <div class="Wx_upload" style="text-align: right">
                扫码上传
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
        </div>
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
              max: 6,
              message: '长度在 1 到 6 个汉字',
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
          &nbsp;
          <el-checkbox v-model="secrecyHidden" @change="handlerSecrecyHidden"
          >不对外显示仅接收通知</el-checkbox
          >
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
            :loading="isSubmit"
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
    <el-dialog
      title="智能生成LOGO"
      :visible.sync="logoDialogVisible"
      width="455px"
      append-to-body
      @close="closeDialog"
    >
      <div class="send_logo_box">
        <div id="img" class="logo_img">
          <div v-if="string_type===1" class="backgroudcolor" :style="{ background: backgroudcolor }">
            <span v-if="logo_name==''">
              LOGO
            </span>
            <span v-else>
              {{ logo_name }}
            </span>
          </div>
          <div v-else class="backgroudcolor2" :style="{ background: backgroudcolor }">
            <div class="logo_name_box">
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
              <span class="custom_texts">自定义LOGO文字：</span>
            </div>
            <div class="input">
              <el-input v-model="logo_name" style="width: 291px;" class="custom_text" placeholder="请输入LOGO文字（2-4个汉子）" maxlength="4" minlength="2" />
            </div>
            <div class="custom_color_box">
              <div>
                <span class="asterisk">*</span>
                <span class="custom_color">自定义LOGO颜色：</span>
              </div>
              <div class="color_box2">
                <div
                  v-for="(item,index) in color_arr"
                  :key="index"
                >
                  <div class="color_box" :style="{ background: item}" @click="backgroudcolor = item">
                    <img v-if="item == backgroudcolor" src="../../../assets/images/check_mark.png" alt="" class="color_img">
                  </div>
                </div>
              </div>
            </div>
            <div class="button_box">
              <el-button type="primary" class="send_logo" @click="save_logo">生成</el-button>
              <el-button class="cancellation" @click="closeDialog">取消</el-button>
            </div>
          </div>
        </div>
      </div>
    </el-dialog>
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

/*
      * 【优化】企业会员中心网址格式验证
      * zch 2022.10.13
      * 【旧】
      * import { validMobile, validEmail, validUrl } from "@/utils/validate";
      * 【新】
      * import { validMobile, validEmail } from "@/utils/validate";
      * */
import { validMobile, validEmail } from "@/utils/validate";
import http from "@/utils/http";
import api from "@/api";
import html2canvas from 'html2canvas'
export default {
  components: {
    Mapset
  },
  data() {
    return {
      string_type: 1,
      color_arr: ['#3E6EF9', '#FF8432', '#6647FF', '#8FAC0C', '#3B83C0', '#2CE7F3', '#FF5656', '#21C78E', '#FECD25', '#F02D94', '#8EBBFB', '#FEA68F', '#BCB4E3', '#DFE2AD', '#98BAD8', '#AFE8EC', '#FFE2E2', '#90E7D2', '#FEF3C6', '#F8B4E7'],
      logoDialogVisible: false,
      logo_name: '',
      backgroudcolor: '#3E6EF9',
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
          qq: "",
          is_secrecy: 1
        }
      },
      dialogVisible: false,
      timer: '',
      scanQrcode: '',
      secrecyHidden: false,
      isSubmit: false
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
    },
    config() {
      return this.$store.state.config
    }
  },
  watch: {
    logo_name: {
      handler(newValue, oldValue){
        var pattern = new RegExp('[\u4E00-\u9FA5]+')
        console.log(oldValue)
        var str = newValue

        if (pattern.test(str) && newValue.toString().length === 4){
          this.string_type = 2
        } else {
          this.string_type = 1
        }
      }
    }
  },
  methods: {
    closeDialog(){
      this.logoDialogVisible = false
      this.logo_name = ''
    },
    save_logo(){
      if (this.form.basic.logo > 0){
        this.$confirm('检测到已存在企业Logo,继续保存将替换原有Logo,是否确认替换？', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.onsubmitLogo()
            .catch(() => {
              this.logoDialogVisible = false
            })
        }).catch()
      } else {
        this.onsubmitLogo()
      }
    },
    onsubmitLogo(){
      html2canvas(document.querySelector("#img"),{width:80,height:80,allowTaint: false,useCORS:true}).then(canvas => {
        console.log(canvas.toDataURL())
        http
          .post(api.sendCompanyLogo, { imgBase64: canvas.toDataURL() })
          .then(res => {
            this.scanNewUpload = true
            this.logoUrl = res.data.file_url
            this.form.basic.logo = res.data.file_id
            this.logoDialogVisible = false
            this.backgroudcolor = '#3E6EF9'
            this.$message({ type: "success", message: res.message });
          })
          .catch(() => { })
      });
    },
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
      /*
      * 【优化】企业会员中心网址格式验证
      * zch 2022.10.13
      * 【旧】
      * !validUrl(value)
      * 【新】
      * !/^http[s]?:\/\/.*.test(value)
      * */
      if (!/^http[s]?:\/\/.*/.test(value)) {
        callback(new Error("请输入正确的网址 https://www.xxx.com"));
      } else {
        callback();
      }
    },
    onSubmit(formName) {
      this.isSubmit = true
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
              this.isSubmit = false
            })
            .catch(() => { this.isSubmit = false });
        } else {
          this.isSubmit = false
          this.$nextTick(() => {
            const isError = document.getElementsByClassName('is-error')
            isError[0].scrollIntoView({
              // 滚动到指定节点
              // 值有start,center,end，nearest，当前显示在视图区域中间
              block: 'center',
              // 值有auto、instant,smooth，缓动动画（当前是慢速的）
              behavior: 'smooth'
            })
            return false
          })
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
    handlerSecrecyHidden () {
      if (this.secrecyHidden === true) {
        this.form.contact.is_secrecy = 0
      } else {
        this.form.contact.is_secrecy = 1
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
            qq: contact.qq,
            is_secrecy: contact.is_secrecy
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
          this.secrecyHidden = this.form.contact.is_secrecy != 1
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
.avatar-uploader-box{
  position: absolute;
  top: 120px;
  right: 200px;
}
.avatar-uploader {
  //position: absolute;
  //left: 885px;
  //top: 141px;
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
.sendLogo{
  color: #2196f3;
  padding-left: 10px;
  display: block;
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
.send_company_logo{
  cursor: pointer;
  color: #4d9afc;
  font-size: 13px;
  float: left;
  position: absolute;
  left: 6px;
  bottom: 26px;
  z-index: 2000
}
.send_logo_box{
  height: 253px
}
.el-input__inner{
  height: 34px;
  width: 120%;
}
.logo_name_box{
  width: 60px;
  margin: 0 auto;
  margin-top: 10px;
  margin: 8px 0 0 14px
}
.logo_name{
  letter-spacing: 6px;
  line-height: 31px;
}
.asterisk{
  color: #FF4848;
  font-size: 13px
}
.custom_text{
  height: 25px;
}
.custom_texts{
  font-size: 13px;
  color: #303133;
  padding-left: 5px;
  font-weight: bold;
}
.custom_color{
  color: #303133;
  font-size: 13px;
  padding-left: 5px;
  font-weight: bold;
}
.color_box2{
  padding-top: 14px;
}
.send_logo{
  width: 80px;
  height: 32px;
  line-height: 0;
}
.cancellation{
  width: 80px;
  height: 32px;
  line-height: 0;
}
.color_img{
  padding-left: 3px
}
.button_box{
  padding: 81px 0 0 21px;
}
.custom_color_box{
  padding-top: 39px;
}
.el-dialog__body {
  padding: 22px 29px;
  color: #606266;
  font-size: 14px;
  word-break: break-all;
}
</style>
