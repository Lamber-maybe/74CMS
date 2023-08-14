<template>
  <div id="app">
    <Head>完善企业资料</Head>
    <div class="split_bar"></div>
    <div class="box_1">
      <div class="up_box">
        <van-uploader :after-read="afterRead"
          ><img v-if="logo_src" :src="logo_src" class="logo"
        /></van-uploader>
      </div>
      <div class="txt_box">点击上传</div>
    </div>
    <div class="split_bar"></div>
    <van-form @submit="onSubmit">
      <van-field
        :disabled="companynameDisabled"
        v-model="basic.companyname"
        required
        label="企业名称"
        placeholder="请填写"
        :rules="[{ required: true, message: '请填写企业名称' }]"
        class="reset_after"
      />
      <van-field
        v-if="field_rule.basic.short_name.is_display == 1"
        v-model="basic.short_name"
        :required="field_rule.basic.short_name.is_require == 1 ? true : false"
        :label="field_rule.basic.short_name.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.basic.short_name.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.basic.short_name.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <van-field
        readonly
        clickable
        required
        :value="nature_text"
        label="企业性质"
        placeholder="请选择"
        @click="showPickerNature = true"
        class="form_choose reset_after"
      />
      <van-field
        readonly
        clickable
        :value="scale_text"
        label="企业规模"
        placeholder="请选择"
        @click="showPickerScale = true"
        class="form_choose reset_after"
      />
      <van-field
        readonly
        clickable
        required
        :value="trade_text"
        label="所属行业"
        placeholder="请选择"
        @click="showPickerTrade = true"
        class="form_choose reset_after"
      />
      <van-field
        v-if="field_rule.basic.registered.is_display == 1"
        v-model="basic.registered"
        :required="field_rule.basic.registered.is_require == 1 ? true : false"
        :label="field_rule.basic.registered.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.basic.registered.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.basic.registered.field_cn
                }
              ]
            : []
        "
        class="form_choose reset_after"
      />
      <div class="for_btn" @click="showCurrencyPicker = true">
        <div class="right_handle">{{ currency_text }}</div>
      </div>
      <van-field
        v-if="field_rule.info.website.is_display == 1"
        v-model="info.website"
        :required="field_rule.info.website.is_require == 1 ? true : false"
        :label="field_rule.info.website.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.info.website.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.info.website.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <van-field
        v-if="field_rule.basic.tag.is_display == 1"
        readonly
        clickable
        :value="tag_text"
        :label="field_rule.basic.tag.field_cn"
        placeholder="请选择"
        @click="handlerShowTag"
        class="form_choose reset_after"
        :required="field_rule.basic.tag.is_require == 1 ? true : false"
        :rules="
          field_rule.basic.tag.is_require == 1
            ? [
                {
                  required: true,
                  message: '请选择' + field_rule.basic.tag.field_cn
                }
              ]
            : []
        "
      />
      <van-field
        readonly
        clickable
        required
        :value="district_text"
        label="所在地区"
        placeholder="请选择"
        @click="showPickerDistrict = !showPickerDistrict"
        class="form_choose reset_after"
      />
      <div class="box_3">
        <input
          type="text"
          class="text"
          v-model="info.address"
          placeholder="详细地址"
        />
        <div class="tag" @click="handlerShowMap" v-if="$store.state.config.is_open_map == 1">标注</div>
      </div>
      <van-field
        v-if="field_rule.info.short_desc.is_display == 1"
        readonly
        clickable
        :label="field_rule.info.short_desc.field_cn"
        :required="field_rule.info.short_desc.is_require == 1 ? true : false"
        class="reset_after no_border"
      />
      <div class="box_4" v-if="field_rule.info.short_desc.is_display == 1">
        <textarea
          style="background-color:#f9f9f9;"
          :rules="
            field_rule.info.short_desc.is_require == 1
              ? [
                  {
                    required: true,
                    message: '请填写' + field_rule.info.short_desc.field_cn
                  }
                ]
              : []
          "
          v-model="info.short_desc"
          rows="2"
          placeholder="请输入">
        </textarea>
      </div>
      <van-field
        v-if="field_rule.info.content.is_display == 1"
        readonly
        clickable
        :label="field_rule.info.content.field_cn"
        :required="field_rule.info.content.is_require == 1 ? true : false"
        class="reset_after no_border"
      />
      <div class="box_4" v-if="field_rule.info.content.is_display == 1">
        <textarea
          v-model="info.content"
          rows="2"
          style="background-color:#f9f9f9;"
          :rules="
            field_rule.info.content.is_require == 1
              ? [
                  {
                    required: true,
                    message: '请填写' + field_rule.info.content.field_cn
                  }
                ]
              : []
          "
          placeholder="请输入">
        </textarea>
      </div>
      <div class="form_split_10"></div>
      <van-field
        v-model="contact.contact"
        required
        name="contact"
        label="联系人"
        placeholder="请填写"
        :rules="[{ required: true, message: '请填写联系人' },
        { validator: validatorContactLen, message: '长度在 1 到 6 个汉字' }]"
        class="reset_after"
      />
      <van-field
        v-model="contact.mobile"
        required
        name="mobile"
        type="number"
        label="联系电话"
        placeholder="请填写"
        :rules="[{ required: true, message: '请填写联系电话' }]"
        class="reset_after"
        @input="hanlderMobile"
      />
      <div class="for_btn">
        <div class="sync_phone">
          <van-checkbox
            v-model="secrecyHidden"
            icon-size="16px"
            @change="handlerSecrecyHidden"
          >
            隐藏不显示
          </van-checkbox>
        </div>
      </div>
      <van-field
        v-if="field_rule.contact.weixin.is_display == 1"
        :required="field_rule.contact.weixin.is_require == 1 ? true : false"
        v-model="contact.weixin"
        name="weixin"
        :label="field_rule.contact.weixin.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.contact.weixin.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.contact.weixin.field_cn
                }
              ]
            : []
        "
        class="reset_after"
        @input="hanlderWeixin"
      />
      <div class="for_btn" v-if="field_rule.contact.weixin.is_display == 1">
        <div class="sync_phone">
          <van-checkbox
            v-model="weixin_sync_mobile"
            icon-size="16px"
            @change="handlerSync"
          >
            同手机号
          </van-checkbox>
        </div>
      </div>
      <van-field
        v-if="field_rule.contact.telephone.is_display == 1"
        :required="field_rule.contact.telephone.is_require == 1 ? true : false"
        v-model="contact.telephone"
        name="telephone"
        type="number"
        :label="field_rule.contact.telephone.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.contact.telephone.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.contact.telephone.field_cn
                },
                {
                  validator: validatorTel, message: '请输入正确的固话号码'
                }
              ]
            : []
        "
        class="reset_after"
      />
      <van-field
        v-if="field_rule.contact.email.is_display == 1"
        :required="field_rule.contact.email.is_require == 1 ? true : false"
        v-model="contact.email"
        name="email"
        :label="field_rule.contact.email.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.contact.email.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.contact.email.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <van-field
        v-if="field_rule.contact.qq.is_display == 1"
        :required="field_rule.contact.qq.is_require == 1 ? true : false"
        v-model="contact.qq"
        name="qq"
        :label="field_rule.contact.qq.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.contact.qq.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.contact.qq.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <div style="margin: 16px;">
        <van-button round block type="info" native-type="submit">
          保存资料
        </van-button>
      </div>
    </van-form>
    <!--    【优化】 求职登录地区样式问题-->
    <!--    zdq 2022.08.11-->
    <!--    【变更前】-->
    <!--    :style="{ 'max-height': '70%' }"-->
    <!--    【变更后】-->
    <!--    :style="{ 'height': '70%' }"-->
    <van-popup
      v-model="showPickerDistrict"
      position="bottom"
      :lock-scroll="false"
      :style="{ 'height': '70%' }"
      ref="dropDistrict"
      @opened="openedDistrict"
    >
      <DistrictFilter
        :districts="[basic.district1, basic.district2, basic.district3]"
        :type="false"
        @doSelect="doSelectDistrict"
        :all="false"
      ></DistrictFilter>
    </van-popup>
    <van-popup v-model="showPickerTrade" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsTrade"
        :defaultIndex="tradeDefaultIndex"
        @confirm="onConfirmTrade"
        @cancel="showPickerTrade = false"
      />
    </van-popup>
    <van-popup v-model="showPickerNature" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsNature"
        :defaultIndex="natureDefaultIndex"
        @confirm="onConfirmNature"
        @cancel="showPickerNature = false"
      />
    </van-popup>
    <van-popup v-model="showPickerScale" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsScale"
        :defaultIndex="scaleDefaultIndex"
        @confirm="onConfirmScale"
        @cancel="showPickerScale = false"
      />
    </van-popup>
    <van-popup
      v-model="showCurrencyPicker"
      position="bottom"
      :style="{ height: '30%' }"
      ><van-picker
        show-toolbar
        :columns="options_currency"
        @confirm="onConfirmCurrency"
        @cancel="showCurrencyPicker = false"
    /></van-popup>
    <van-popup
      v-model="showTag"
      position="right"
      :lazy-render="false"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <CompanyTag
        ref="companyTag"
        title="企业福利"
        :tag_arr="basic.tag"
        @handlerCloseTag="handlerCloseTag"
      ></CompanyTag>
    </van-popup>
    <van-popup
      v-model="showMap"
      position="top"
      :lazy-render="true"
      :overlay="true"
      style="width:100%;height:90%"
    >
      <Mapset
        ref="mapset"
        title="地图标注"
        :mapLat="basic.map_lat"
        :mapLng="basic.map_lng"
        :mapZoom="basic.map_zoom"
        :address="info.address"
        @closeMap="handlerCloseMap"
      ></Mapset>
    </van-popup>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import Mapset from '@/components/Mapset'
import CompanyTag from '@/components/CompanyTag'
import http from '@/utils/http'
import api from '@/api'
import DistrictFilter from '@/components/DistrictFilter'
export default {
  name: 'BasicInfo',
  components: {
    Mapset,
    CompanyTag,
    DistrictFilter
  },
  data () {
    return {
      uploading: false,
      companynameDisabled: true,
      showMap: false,
      currency_text: '万人民币',
      options_currency: [
        { text: '万人民币', value: 0 },
        { text: '万美元', value: 1 }
      ],
      showCurrencyPicker: false,
      natureDefaultIndex: 0,
      tradeDefaultIndex: 0,
      scaleDefaultIndex: 0,
      showTag: false,
      showPickerNature: false,
      showPickerTrade: false,
      showPickerScale: false,
      field_rule: {
        basic: {
          logo: {},
          short_name: {},
          registered: {},
          tag: {}
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
      basic: {
        logo: 0,
        companyname: '',
        short_name: '',
        registered: 0,
        currency: 0,
        nature: 1,
        trade: 1,
        scale: 0,
        district1: 1,
        district2: 2,
        district3: 3,
        map_lat: 0,
        map_lng: 0,
        map_zoom: 0,
        tag: []
      },
      info: {
        content: '',
        short_desc: '',
        website: '',
        address: ''
      },
      contact: {
        contact: '',
        mobile: '',
        weixin: '',
        telephone: '',
        email: '',
        qq: '',
        is_secrecy: 1
      },
      logo_src: '',
      nature_text: '',
      trade_text: '',
      scale_text: '',
      district_text: '',
      tag_text: '',
      weixin_sync_mobile: false,
      showPickerDistrict: false,
      secrecyHidden: false
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'companyNature')
    this.$store.dispatch('getClassify', 'trade')
    this.$store.dispatch('getClassify', 'companyScale')
    this.$store.dispatch('getClassify', 'citycategory')
    /**
     * 【ID1000476】
     * 【bug】注册企业后点发布职位页面空白
     * yx - 2022.12.26
     * [新增]:
     * jobNature
     */
    this.$store.dispatch('getClassify', 'jobNature')
    this.fetchData()
  },
  computed: {
    columnsNature () {
      return this.$store.state.classifyCompanyNature
    },
    columnsTrade () {
      return this.$store.state.classifyTrade
    },
    columnsScale () {
      return this.$store.state.classifyCompanyScale
    },
    areaDistrict () {
      return this.$store.state.classifyCityCategory
    }
  },
  methods: {
    validatorTel (val) {
      return /^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$/.test(val)
    },
    validatorContactLen (val) {
      return val.length <= 6
    },
    onConfirmCurrency (value) {
      this.currency_text = value.text
      this.basic.currency = value.value
      this.showCurrencyPicker = false
    },
    doSelectDistrict (data) {
      this.basic.district1 = data[0]
      this.basic.district2 = data[1]
      this.basic.district3 = data[2]
      this.district_text = data[3]
      this.showPickerDistrict = !this.showPickerDistrict
    },
    // 地区筛选打开之后给筛选组件赋值
    openedDistrict () {
      this.$refs.dropDistrict.$children[0].initData()
      setTimeout(() => {
        this.dynamicAssignFun(this.$refs.dropDistrict)
      }, 300)
    },
    // 动态赋值筛选组件公用方法
    dynamicAssignFun (obj) {
      let popHeight = obj.$el.clientHeight
      if (obj.$children[0]) {
        obj.$children[0].layHeight = popHeight
        let offTop = obj.$el.offsetTop
        obj.$children[0].offTop = parseInt(
          parseInt(offTop) + parseInt(popHeight) / 2
        )
      }
    },
    hanlderMobile (val) {
      if (this.weixin_sync_mobile === true) {
        this.contact.weixin = val
      }
    },
    hanlderWeixin () {
      if (this.contact.weixin !== this.contact.mobile) {
        this.weixin_sync_mobile = false
      } else {
        this.weixin_sync_mobile = true
      }
    },
    handlerSync () {
      if (this.weixin_sync_mobile === true) {
        this.contact.weixin = this.contact.mobile
      }
    },
    handlerSecrecyHidden () {
      if (this.secrecyHidden === true) {
        this.contact.is_secrecy = 0
      } else {
        this.contact.is_secrecy = 1
      }
    },
    fetchData () {
      http
        .get(api.company_profile, {})
        .then(res => {
          const { basic, contact, info, field_rule } = { ...res.data }
          if (basic.companyname === undefined || basic.companyname == '') {
            this.companynameDisabled = false
          }
          // 修改完善企业资料注册资金单位回显不正确问题 zch 2022/9/16
          this.currency_text = basic.currency ? '万美元' : '万人民币'
          this.basic = {
            logo: basic.logo,
            companyname: basic.companyname,
            short_name: basic.short_name,
            registered: basic.registered,
            currency: basic.currency,
            nature: basic.nature,
            trade: basic.trade,
            scale: basic.scale,
            district1: basic.district1,
            district2: basic.district2,
            district3: basic.district3,
            map_lat: basic.map_lat,
            map_lng: basic.map_lng,
            map_zoom: basic.map_zoom,
            tag: basic.tag
          }
          this.info = {
            content: info.content,
            short_desc: info.short_desc,
            website: info.website,
            address: info.address
          }
          this.contact = {
            contact: contact.contact,
            mobile: contact.mobile,
            weixin: contact.weixin,
            telephone: contact.telephone,
            email: contact.email,
            qq: contact.qq,
            is_secrecy: contact.is_secrecy
          }
          if (
            this.contact.mobile !== '' &&
            this.contact.mobile === this.contact.weixin
          ) {
            this.weixin_sync_mobile = true
          }
          this.field_rule = field_rule
          this.tag_text = basic.tag_text
          this.logo_src = basic.logo_src
          this.nature_text = basic.nature_text
          this.trade_text = basic.trade_text
          this.scale_text = basic.scale_text
          this.district_text = basic.district_text
          this.natureDefaultIndex = this.columnsNature.findIndex(
            item => parseInt(item.id) === parseInt(this.basic.nature)
          )
          this.tradeDefaultIndex = this.columnsTrade.findIndex(
            item => parseInt(item.id) === parseInt(this.basic.trade)
          )
          this.scaleDefaultIndex = this.columnsScale.findIndex(
            item => parseInt(item.id) === parseInt(this.basic.scale)
          )
          this.secrecyHidden = this.contact.is_secrecy != 1
        })
        .catch(() => {})
    },
    // 选择所在地区
    onConfirmDistrict (value) {
      this.basic.district1 = value[0].code
      this.basic.district2 = value[1].code
      this.basic.district3 = value[2].code
      this.district_text = `${value[0].name}/${value[1].name}/${value[2].name}`
      this.showPickerDistrict = !this.showPickerDistrict
    },
    // 选择企业性质
    onConfirmNature (value) {
      this.basic.nature = value.id
      this.nature_text = value.text
      this.showPickerNature = !this.showPickerNature
    },
    // 选择企业行业
    onConfirmTrade (value) {
      this.basic.trade = value.id
      this.trade_text = value.text
      this.showPickerTrade = !this.showPickerTrade
    },
    // 选择企业规模
    onConfirmScale (value) {
      this.basic.scale = value.id
      this.scale_text = value.text
      this.showPickerScale = !this.showPickerScale
    },
    onConfirmTag () {
      this.$refs.companyTag.initCB()
    },
    afterRead (file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$notify('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      this.uploading = true
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          this.uploading = false
          this.basic.logo = res.data.file_id
          this.logo_src = res.data.file_url
        })
        .catch(() => {
          this.uploading = false
        })
    },
    onSubmit (values) {
      if (
        this.field_rule.basic.logo.is_display === 1 &&
        this.field_rule.basic.logo.is_require === 1 &&
        this.basic.logo === 0
      ) {
        this.$notify('请上传' + this.field_rule.basic.logo.field_cn)
        return false
      }
      http
        .post(api.company_profile, {
          basic: this.basic,
          info: this.info,
          contact: this.contact
        })
        .then(res => {
          this.$notify({ type: 'success', message: res.message })
          window.history.length > 1
            ? this.$router.go(-1)
            : this.$router.push('/member/company/index')
        })
        .catch(() => {})
    },
    handlerShowTag () {
      this.showTag = true
      this.$refs.companyTag.initCB()
    },
    handlerCloseTag (tagInfo) {
      this.showTag = false
      if (tagInfo === undefined) {
        return false
      }
      this.basic.tag = tagInfo.tagIdArr
      this.tag_text =
        tagInfo.tagTextArr.length > 0 ? tagInfo.tagTextArr.join(',') : ''
    },
    handlerCloseMap (mapInfo) {
      this.showMap = false
      if (mapInfo === undefined) {
        return false
      }
      this.basic.map_lat = mapInfo.lat
      this.basic.map_lng = mapInfo.lng
      this.basic.map_zoom = mapInfo.zoom
      this.info.address = mapInfo.address
    },
    handlerShowMap () {
      // this.$refs.mapset.initCB()
      this.showMap = true
      // this.$router.push('/member/company/mapset')
    }
  }
}
</script>

<style lang="scss" scoped>
// .van-popup {
//   top: 80%;
//   right: 0;
//   -webkit-transform: translate3d(0, -80%, 0);
//   transform: translate3d(0, -80%, 0);
// }
.right_handle {
  right: 10px;
}
.box_4 {
  textarea {
    width: 100%;
    height: 80px;
    border: 0;
    background-color: #f9f9f9;
    font-size: 14px;
    resize: none;
    padding: 13px 25px;
    line-height: 1.8;
    &::placeholder {
      color: #c9c9c9;
    }
  }
  width: 100%;
  background-color: #ffffff;
  position: relative;
  padding: 0 20px 20px 20px;
}
.box_3 {
  .tag {
    position: absolute;
    right: 17px;
    top: 6px;
    padding: 12px 18px 12px 0;
    font-size: 14px;
    color: #666666;
    background: url("../../assets/images/location_ico_blue.svg") right center
      no-repeat;
    background-size: 14px;
  }
  .text {
    &::placeholder {
      color: #c9c9c9;
    }
    font-size: 14px;
    border: 0;
    width: 180px;
    height: 40px;
    position: absolute;
    left: 119px;
    top: 5px;
    color: #333333;
  }
  &::after {
    position: absolute;
    left: 0;
    box-sizing: border-box;
    content: " ";
    pointer-events: none;
    right: 0;
    bottom: 0;
    transform: scaleY(0.5);
    border-bottom: 1px solid #ebebeb;
  }
  width: 100%;
  height: 50px;
  background-color: #ffffff;
  position: relative;
  padding: 0 68px 0 119px;
}
.sync_phone {
  position: absolute;
  right: 12px;
  top: -50px;
  font-size: 14px;
  color: #515151;
  padding: 15px 0;
  line-height: 24px;
}
.box_1 {
  .van-uploader__upload {
    margin: 0;
  }
  .txt_box {
    padding: 16px 0 20px;
    font-size: 15px;
    color: #a7a7a7;
  }
  .up_box {
    width: 75px;
    height: 100px;
    position: relative;
    padding-top: 25px;
    margin: 0 auto;
    .logo {
      //   position: absolute;
      //   left: 0;
      //   top: 25px;
      width: 75px;
      height: 75px;
      border: 0;
      border-radius: 100%;
      //   &.has {
      //     z-index: 1;
      //   }
    }
  }
  width: 100%;
  background-color: #ffffff;
  text-align: center;
}
.split_bar {
  width: 100%;
  height: 9px;
}
.van-overlay{
  text-align:center;
  z-index:2;
}
.van-loading{
  top:36%;
}
.van-loading__text{
  color:#c3c3c3;
}
</style>
