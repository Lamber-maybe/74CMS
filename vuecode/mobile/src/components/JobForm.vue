<template>
  <div id="app">
    <div class="box_1" v-if="type == 'add'">
      您还可发布 {{ enable_addjob_num }} 条职位，升级套餐畅享更多会员权益
      <div
        class="right_txt"
        @click="$router.push('/member/order/add/common?type=setmeal')"
      >
        去了解
      </div>
    </div>
    <van-form @submit="onSubmit">
      <div class="form_split_title">基本信息</div>
      <van-field
        v-model="basic.jobname"
        required
        name="jobname"
        label="职位名称"
        placeholder="请填写"
        :rules="[{ required: true, message: '请填写职位名称' }]"
        class="reset_after"
      />
      <van-field
        readonly
        clickable
        required
        :value="basic.categoryName"
        label="职位类别"
        placeholder="请选择"
        @click="showPickerJobCategory = !showPickerJobCategory"
        class="form_choose reset_after"
      />
      <van-popup
        v-model="showPickerJobCategory"
        :lazy-render="false"
        position="bottom"
        :lock-scroll="false"
        :style="{ 'max-height': '70%' }"
        ref="dropCategory"
        @click-overlay="handleCategoryOverlay"
        @opened="openedCategory"
      >
        <JobCategoryFilter
          :categories="[basic.category1, basic.category2, basic.category3]"
          :type="false"
          :all="false"
          @doSelect="doSelectCategory"
        ></JobCategoryFilter>
      </van-popup>
      <div class="box_2">
        <div class="item" @click="showPickerExperience = true">
          <div class="tx1">经验要求</div>
          <div class="tx2">{{ experience_text }}</div>
        </div>
        <van-popup v-model="showPickerExperience" position="bottom">
          <van-picker
            show-toolbar
            :columns="columnsExperience"
            :default-index="experienceDefaultIndex"
            @confirm="onConfirmExperience"
            @cancel="showPickerExperience = false"
          />
        </van-popup>
        <div class="item" @click="showPickerEducation = true">
          <div class="tx1">学历要求</div>
          <div class="tx2">{{ education_text }}</div>
        </div>
        <van-popup v-model="showPickerEducation" position="bottom">
          <van-picker
            show-toolbar
            :columns="columnsEducation"
            :default-index="educationDefaultIndex"
            @confirm="onConfirmEducation"
            @cancel="showPickerEducation = false"
          />
        </van-popup>
        <div class="item" @click="showPickerWage = true">
          <div class="tx1">薪资待遇</div>
          <div class="tx2">{{ wage_text }}</div>
        </div>
        <van-popup v-model="showPickerWage" position="bottom">
          <div
            class="for-pop-title"
            v-if="field_rule.basic.negotiable.is_display == 1"
            @click="handlerNegotiable"
          >
            面议
          </div>
          <van-picker
            show-toolbar
            :columns="columnsWage"
            @confirm="onConfirmWage"
            @change="onChangeWage"
            @cancel="showPickerWage = false"
          />
        </van-popup>
      </div>
      <van-field
        v-if="field_rule.basic.amount.is_display == 1"
        :required="field_rule.basic.amount.is_require == 1 ? true : false"
        v-model="basic.amount"
        name="amount"
        :label="field_rule.basic.amount.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.basic.amount.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.basic.amount.field_cn
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
        :value="district_text"
        label="工作地区"
        placeholder="请选择"
        @click="showPickerDistrict = !showPickerDistrict"
        class="form_choose reset_after"
      />
      <van-popup
        v-model="showPickerDistrict"
        position="bottom"
        :style="{ 'max-height': '70%' }"
        ref="dropDistrict"
        :lock-scroll="false"
        @click-overlay="handleDistrictOverlay"
        @opened="openedDistrict"
      >
        <DistrictFilter
          :districts="[basic.district1, basic.district2, basic.district3]"
          :type="false"
          :all="false"
          @doSelect="doSelectDistrict"
        ></DistrictFilter>
      </van-popup>
      <div class="box_3">
        <van-field
          v-model="basic.address"
          name="address"
          label=" "
          placeholder="详细地址"
          class="reset_after"
        />
        <div class="tag" @click="handlerShowMap">标注</div>
      </div>
      <van-field
        readonly
        clickable
        required
        label="职位描述"
        class="reset_after no_border"
      />
      <div class="box_4">
        <van-field
          style="background-color:#f9f9f9;"
          v-model="basic.content"
          rows="5"
          autosize
          label=""
          type="textarea"
          placeholder="请输入详细的职位描述"
        />
      </div>
      <div
        class="form_split_title"
        v-if="
          field_rule.basic.tag.is_display == 1 ||
            field_rule.basic.department.is_display == 1 ||
            field_rule.basic.age.is_display == 1 ||
            field_rule.basic.custom_field_1.is_display == 1 ||
            field_rule.basic.custom_field_2.is_display == 1 ||
            field_rule.basic.custom_field_3.is_display == 1
        "
      >
        其他信息
      </div>
      <van-field
        v-if="field_rule.basic.tag.is_display == 1"
        readonly
        clickable
        :value="tag_text"
        :required="field_rule.basic.tag.is_require == 1 ? true : false"
        :label="field_rule.basic.tag.field_cn"
        placeholder="请选择"
        v-model="tag_text"
        @click="handlerShowTag"
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
        class="form_choose reset_after"
      />
      <van-popup
        v-model="showTag"
        position="top"
        :lazy-render="false"
        :overlay="true"
        style="width:100%;height:90%"
      >
        <JobTag
          ref="jobTag"
          title="岗位福利"
          :tag_arr="basic.tag"
          @handlerCloseTag="handlerCloseTag"
        ></JobTag>
      </van-popup>
      <van-field
        v-if="field_rule.basic.department.is_display == 1"
        :required="field_rule.basic.department.is_require == 1 ? true : false"
        v-model="basic.department"
        name="department"
        :label="field_rule.basic.department.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.basic.department.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.basic.department.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <van-field
        v-if="field_rule.basic.age.is_display == 1"
        :required="field_rule.basic.age.is_require == 1 ? true : false"
        readonly
        clickable
        :value="age_text"
        :label="field_rule.basic.age.field_cn"
        placeholder="请选择"
        @click="showPickerAge = true"
        :rules="
          field_rule.basic.age.is_require == 1
            ? [
                {
                  required: true,
                  message: '请选择' + field_rule.basic.age.field_cn
                }
              ]
            : []
        "
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerAge" position="bottom">
        <div class="for-pop-title" @click="handlerAgeNa">
          不限
        </div>
        <van-picker
          show-toolbar
          :columns="columnsAge"
          @confirm="onConfirmAge"
          @change="onChangeAge"
          @cancel="showPickerAge = false"
        />
      </van-popup>
      <van-field
        v-if="field_rule.basic.custom_field_1.is_display == 1"
        :required="
          field_rule.basic.custom_field_1.is_require == 1 ? true : false
        "
        v-model="basic.custom_field_1"
        name="custom_field_1"
        :label="field_rule.basic.custom_field_1.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.basic.custom_field_1.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.basic.custom_field_1.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <van-field
        v-if="field_rule.basic.custom_field_2.is_display == 1"
        :required="
          field_rule.basic.custom_field_2.is_require == 1 ? true : false
        "
        v-model="basic.custom_field_2"
        name="custom_field_2"
        :label="field_rule.basic.custom_field_2.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.basic.custom_field_2.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.basic.custom_field_2.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <van-field
        v-if="field_rule.basic.custom_field_3.is_display == 1"
        :required="
          field_rule.basic.custom_field_3.is_require == 1 ? true : false
        "
        v-model="basic.custom_field_3"
        name="custom_field_3"
        :label="field_rule.basic.custom_field_3.field_cn"
        placeholder="请填写"
        :rules="
          field_rule.basic.custom_field_3.is_require == 1
            ? [
                {
                  required: true,
                  message: '请填写' + field_rule.basic.custom_field_3.field_cn
                }
              ]
            : []
        "
        class="reset_after"
      />
      <div class="form_split_title">联系方式</div>
      <van-field
        readonly
        clickable
        :value="contact_source_text"
        label="联系方式"
        placeholder="请选择"
        @click="showPickerContactSource = true"
        class="form_choose reset_after"
      />
      <van-action-sheet
        v-model="showPickerContactSource"
        :actions="columnsContactSource"
        @select="onConfirmContactSource"
      />
      <div v-if="contact.use_company_contact == 0">
        <van-field
          v-model="contact.contact"
          required
          name="contact"
          label="联系人"
          placeholder="请填写"
          :rules="[{ required: true, message: '请填写联系人' }]"
          class="reset_after"
        />
        <van-field
          v-model="contact.mobile"
          required
          name="mobile"
          label="联系电话"
          placeholder="请填写"
          :rules="[{ required: true, message: '请填写联系电话' }]"
          class="reset_after"
          @input="hanlderMobile"
        />
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
        <div class="for_btn">
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
          :required="
            field_rule.contact.telephone.is_require == 1 ? true : false
          "
          v-model="contact.telephone"
          name="telephone"
          :label="field_rule.contact.telephone.field_cn"
          placeholder="请填写"
          :rules="
            field_rule.contact.telephone.is_require == 1
              ? [
                  {
                    required: true,
                    message: '请填写' + field_rule.contact.telephone.field_cn
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
      </div>
      <div class="form_split_10"></div>
      <van-field
        label="联系方式保密"
        placeholder="（不想受到骚扰）"
        class="reset_after"
      />
      <div class="for_btn">
        <van-switch
          class="self_switch"
          v-model="contactHidden"
          size="18px"
          active-color="#45db5e"
        />
      </div>
      <div class="form_split_10"></div>
      <div style="margin: 16px;">
        <van-button round block type="info" native-type="submit">
          {{ btnText }}
        </van-button>
      </div>
    </van-form>
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
        :address="basic.address"
        @closeMap="handlerCloseMap"
      ></Mapset>
    </van-popup>
  </div>
</template>

<script>
import Mapset from '@/components/Mapset'
import JobCategoryFilter from '@/components/JobCategoryFilter'
import DistrictFilter from '@/components/DistrictFilter'
import JobTag from '@/components/CompanyTag'
export default {
  name: 'JobForm',
  props: ['enable_addjob_num', 'type'],
  components: {
    JobTag,
    JobCategoryFilter,
    DistrictFilter,
    Mapset
  },
  data () {
    return {
      showMap: false,
      btnText: '发布职位',
      field_rule: {
        basic: {
          department: {},
          age: {},
          tag: {},
          amount: {},
          negotiable: {},
          custom_field_1: {},
          custom_field_2: {},
          custom_field_3: {}
        },
        contact: {
          weixin: {},
          telephone: {},
          qq: {},
          email: {}
        }
      },
      basic: {
        id: 0,
        jobname: '',
        category1: 0,
        category2: 0,
        category3: 0,
        categoryName: '',
        experience: 0,
        education: 0,
        minwage: 0,
        maxwage: 0,
        negotiable: 0,
        amount: 0,
        district1: 0,
        district2: 0,
        district3: 0,
        districtName: '',
        content: '',
        tag: [],
        department: '',
        minage: 0,
        maxage: 0,
        age_na: 0,
        map_lat: 0,
        map_lng: 0,
        map_zoom: 0,
        address: '',
        custom_field_1: '',
        custom_field_2: '',
        custom_field_3: ''
      },
      contact: {
        use_company_contact: 1,
        is_display: 1,
        contact: '',
        mobile: '',
        weixin: '',
        telephone: '',
        email: '',
        qq: ''
      },
      showPickerJobCategory: false,
      showPickerExperience: false,
      showPickerEducation: false,
      showPickerAge: false,
      showPickerDistrict: false,
      showPickerWage: false,
      showTag: false,
      showPickerContactSource: false,
      experience_text: '经验不限',
      education_text: '学历不限',
      age_text: '请选择',
      district_text: '请选择',
      tag_text: '',
      wage_text: '请选择',
      contact_source_text: '使用企业资料联系方式',
      columnsContactSource: [
        { id: 1, name: '使用企业资料联系方式' },
        { id: 0, name: '使用其他联系方式' }
      ],
      contactHidden: false,
      weixin_sync_mobile: false,
      wageStart: this.$store.state.minWage,
      wageDefaultIndex1: 0,
      wageDefaultIndex2: 0,
      ageStart: this.$store.state.minAge,
      ageDefaultIndex1: 0,
      ageDefaultIndex2: 0,
      experienceDefaultIndex: 0,
      educationDefaultIndex: 0
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'jobcategory')
    this.$store.dispatch('getClassify', 'education')
    this.$store.dispatch('getClassify', 'citycategory')
    this.$store.dispatch('getClassify', 'experience')
    this.$store.dispatch('getClassifyWage')
    this.$store.dispatch('getClassifyAge')
  },
  mounted () {
    this.restoreJobCategory()
  },
  computed: {
    columnsEducation () {
      let arr = [{id: '0', text: '学历不限'}]
      arr = arr.concat(this.$store.state.classifyEdu)
      return arr
    },
    columnsExperience () {
      let arr = [{id: '0', text: '经验不限'}]
      arr = arr.concat(this.$store.state.classifyExperience)
      return arr
    },
    columnsWage () {
      return [
        {
          values: Object.keys(this.$store.state.classifyWage),
          defaultIndex: this.wageDefaultIndex1
        },
        {
          values: this.$store.state.classifyWage[this.wageStart],
          defaultIndex: this.wageDefaultIndex2
        }
      ]
    },
    columnsAge () {
      return [
        {
          values: Object.keys(this.$store.state.classifyAge),
          defaultIndex: this.ageDefaultIndex1
        },
        {
          values: this.$store.state.classifyAge[this.ageStart],
          defaultIndex: this.ageDefaultIndex2
        }
      ]
    }
  },
  methods: {
    restoreCondition (data) {
      let restoreBasic = data.basic
      let restoreContact = data.contact
      // 恢复职位类别汉字
      this.basic.categoryName = restoreBasic.category_text
      // 恢复经验
      this.experienceDefaultIndex = this.columnsExperience.findIndex(
        item => parseInt(item.id) === parseInt(this.basic.experience)
      )
      this.experience_text = restoreBasic.experience_text == '' ? '经验不限' : restoreBasic.experience_text
      // 恢复学历
      this.educationDefaultIndex = this.columnsEducation.findIndex(
        item => parseInt(item.id) === parseInt(this.basic.education)
      )
      this.education_text = restoreBasic.education_text == '' ? '学历不限' : restoreBasic.education_text
      // 恢复薪资
      let _this = this
      let pickerWage = this.$store.state.classifyWage
      Object.keys(pickerWage).forEach(function (key, index) {
        if (parseInt(key) === parseInt(_this.basic.minwage)) {
          _this.wageDefaultIndex1 = index
          _this.wageStart = _this.basic.minwage
          _this.wageDefaultIndex2 = pickerWage[key].findIndex(
            item => parseInt(item) === _this.basic.maxwage
          )
        }
      })
      this.wage_text = restoreBasic.wage_text
      // 恢复地区汉字
      this.district_text = restoreBasic.district_text
      // 恢复岗位福利
      this.tag_text = restoreBasic.tag_text_arr.join(',')
      // 恢复年龄要求
      let pickerAge = this.$store.state.classifyAge
      Object.keys(pickerAge).forEach(function (key, index) {
        if (parseInt(key) === parseInt(_this.basic.minage)) {
          _this.ageDefaultIndex1 = index
          _this.ageStart = _this.basic.minage
          _this.ageDefaultIndex2 = pickerAge[key].findIndex(
            item => parseInt(item) === _this.basic.maxage
          )
        }
      })
      this.age_text = restoreBasic.age_text + '岁'
      // 联系方式是否保密
      this.contactHidden = parseInt(restoreContact.is_display) === 0
      this.weixin_sync_mobile = this.contact.weixin === this.contact.mobile
      this.btnText = '保存职位'
      // 恢复联系方式
      this.contact.use_company_contact = restoreContact.use_company_contact
      let contactItem = this.columnsContactSource.filter(item => item.id === this.contact.use_company_contact)[0]
      this.contact_source_text = contactItem.name
    },
    // 恢复职位分类
    restoreJobCategory () {
      if (this.basic.category1) {
        // 恢复选中项对应的汉字
        let storeCategory = this.$store.state.classifyJobCategory
        let selectText = []
        let topItem = storeCategory.filter(
          item => parseInt(item.id) === parseInt(this.basic.category1)
        )[0]
        selectText.push(topItem.text)
        if (topItem.children.length) {
          if (parseInt(this.basic.category2)) {
            let secondItem = topItem.children.filter(
              item => parseInt(item.id) === parseInt(this.basic.category2)
            )[0]
            selectText.push(secondItem.text)
            if (secondItem.children.length) {
              if (parseInt(this.basic.category3)) {
                let lowestItem = secondItem.children.filter(
                  item => parseInt(item.id) === parseInt(this.basic.category3)
                )[0]
                selectText.push(lowestItem.text)
              } else {
                selectText.push(`全${selectText[selectText.length - 1]}`)
              }
            }
          } else {
            // 相当于不限
            selectText.push(`全${selectText[selectText.length - 1]}`)
          }
        }
        this.basic.categoryName = selectText[selectText.length - 1]
      }
    },
    doSelectCategory (data) {
      this.basic.category1 = data[0]
      this.basic.category2 = data[1]
      this.basic.category3 = data[2]
      this.basic.categoryName = data[3]
      this.showPickerJobCategory = !this.showPickerJobCategory
    },
    doSelectDistrict (data) {
      this.basic.district1 = data[0]
      this.basic.district2 = data[1]
      this.basic.district3 = data[2]
      this.district_text = data[3]
      this.showPickerDistrict = !this.showPickerDistrict
    },
    handleCategoryOverlay () {
      this.$refs.dropCategory.$children[0].handleSecondOverlay()
    },
    handleDistrictOverlay () {
      this.$refs.dropDistrict.$children[0].handleCityOverlay()
    },
    // 职位分类筛选打开之后给筛选组件赋值
    openedCategory () {
      this.$refs.dropCategory.$children[0].initData()
      this.dynamicAssignFun(this.$refs.dropCategory)
    },
    // 地区筛选打开之后给筛选组件赋值
    openedDistrict () {
      this.$refs.dropDistrict.$children[0].initData()
      this.dynamicAssignFun(this.$refs.dropDistrict)
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
    onConfirmExperience (value) {
      this.basic.experience = value.id
      this.experience_text = value.text
      this.showPickerExperience = !this.showPickerExperience
    },
    onConfirmEducation (value) {
      this.basic.education = value.id
      this.education_text = value.text
      this.showPickerEducation = !this.showPickerEducation
    },
    onConfirmAge (values) {
      this.basic.minage = parseInt(values[0])
      this.basic.maxage = parseInt(values[1])
      this.age_text = `${parseInt(values[0])}-${parseInt(values[1])}岁`
      this.showPickerAge = !this.showPickerAge
    },
    onConfirmWage (values) {
      this.basic.minwage = parseInt(values[0])
      this.basic.maxwage = parseInt(values[1])
      this.wage_text = `${parseInt(values[0])}-${parseInt(values[1])}元`
      this.showPickerWage = !this.showPickerWage
    },
    // 薪资级联
    onChangeWage (picker, values) {
      picker.setColumnValues(1, this.$store.state.classifyWage[values[0]])
    },
    onConfirmContactSource (value) {
      this.contact.use_company_contact = value.id
      this.contact_source_text = value.name
      this.showPickerContactSource = !this.showPickerContactSource
    },
    handlerShowTag () {
      this.showTag = true
      this.$refs.jobTag.initCB()
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
    handlerNegotiable () {
      this.basic.negotiable = 1
      this.basic.minwage = 0
      this.basic.maxwage = 0
      this.wage_text = '面议'
      this.showPickerWage = !this.showPickerWage
    },
    handlerAgeNa () {
      this.basic.age_na = 1
      this.basic.minage = 0
      this.basic.maxage = 0
      this.age_text = '不限'
      this.showPickerAge = !this.showPickerAge
    },
    // 年龄级联
    onChangeAge (picker, values) {
      picker.setColumnValues(1, this.$store.state.classifyAge[values[0]])
    },
    onSubmit (values) {
      this.contact.is_display = this.contactHidden === 1 ? 0 : 1
      this.$emit('submit', {
        basic: this.basic,
        contact: this.contact
      })
    },
    handlerCloseMap (mapInfo) {
      this.showMap = false
      if (mapInfo === undefined) {
        return false
      }
      this.basic.map_lat = mapInfo.lat
      this.basic.map_lng = mapInfo.lng
      this.basic.map_zoom = mapInfo.zoom
      this.basic.address = mapInfo.address
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
.box_4 {
  textarea {
    width: 100%;
    height: 130px;
    border: 0;
    background-color: #f9f9f9;
    font-size: 14px;
    resize: none;
    padding: 8px 15px;
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
    top: 0;
    padding: 15px 18px 15px 0;
    font-size: 14px;
    color: #666666;
    background: url("../assets/images/location_ico_blue.svg") right center
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
  padding: 0 68px 0 0;
}
.box_2 {
  .item {
    &:not(:last-child)::after {
      position: absolute;
      right: 0;
      top: 20px;
      height: 40px;
      border-right: 1px solid #e2e2e2;
      content: " ";
    }
    .tx2 {
      padding: 5px 0 17.5px;
      font-size: 14px;
      color: #333333;
    }
    .tx1 {
      padding-top: 17.5px;
      font-size: 12px;
      color: #666666;
    }
    flex: 1;
    text-align: center;
    position: relative;
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
  display: flex;
  background-color: #ffffff;
  position: relative;
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
.self_switch {
  position: absolute;
  right: 15px;
  top: -35px;
}
.box_1 {
  width: 100%;
  font-size: 12px;
  color: #ff6600;
  position: relative;
  padding: 10.5px 0 10.5px 33px;
  background: #fffbeb url("../assets/images/remind_ico.svg") 13px center
    no-repeat;
  background-size: 15px;
  .right_txt {
    position: absolute;
    right: 0;
    top: 0;
    font-size: 12px;
    color: #1787fb;
    padding: 10.5px 17px;
  }
}
</style>
