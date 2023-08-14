<template>
  <div id="app">
    <div class="box_1">
      <Head :bg="'tran'">基本资料</Head>
      <div class="avatar_box">
        <img
          :src="basic.photo_img_src"
          :alt="basic.fullname"
          :class="basic.photo_img_src ? 'avatar has' : 'avatar'"
        />
        <van-uploader :after-read="uploadAvatar" />
      </div>
    </div>
    <div class="box_2">头像，一份好工作的开始</div>
    <van-field
      v-model="basic.fullname"
      required
      label="姓名"
      placeholder="请填写"
      class="reset_after"
    />
    <van-field required class="reset_after" name="sex" label="性别">
      <template #input>
        <van-radio-group
          v-model="basic.sex"
          direction="horizontal"
          icon-size="14px"
        >
          <van-radio name="1">男</van-radio>
          <van-radio name="2">女</van-radio>
        </van-radio-group>
      </template>
    </van-field>
    <div class="box_5">
      <div class="item" @click="showPickerBirthday = !showPickerBirthday">
        <div class="tx1">出生日期</div>
        <div class="tx2">{{ basic.birthday }}</div>
      </div>
      <div class="item" @click="showPickerEnterJob = !showPickerEnterJob">
        <div class="tx1">开始工作时间</div>
        <div class="tx2">{{ basic.enter_job_time_name }}</div>
      </div>
      <div class="item" @click="showPickerEdu = !showPickerEdu">
        <div class="tx1">最高学历</div>
        <div class="tx2">{{ basic.educationName }}</div>
      </div>
    </div>
    <van-popup v-model="showPickerBirthday" position="bottom">
      <van-datetime-picker
        v-model="currentDateBirthday"
        type="year-month"
        title="选择出生日期"
        :min-date="minDate"
        :max-date="maxBirthdayDate"
        @confirm="onConfirmBirthday"
        @cancel="showPickerBirthday = false"
      />
    </van-popup>
    <van-popup v-model="showPickerEnterJob" position="bottom">
      <div class="for-pop-title" @click="handleNoJobTime">无工作经验</div>
      <van-datetime-picker
        v-model="currentDateEnterJob"
        type="year-month"
        title=""
        :min-date="minDate"
        :max-date="maxDate"
        @confirm="onConfirmEnterJob"
        @cancel="showPickerEnterJob = false"
      />
    </van-popup>
    <van-popup v-model="showPickerEdu" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsEdu"
        :default-index="educationDefaultIndex"
        @confirm="onConfirmEducation"
        @cancel="showPickerEdu = false"
      />
    </van-popup>
    <van-field
      required
      v-model="contact.mobile"
      label="联系电话"
      placeholder="请填写联系电话"
      class="reset_after"
      readonly
    />
    <div class="for_btn">
      <div class="edit_phone" @click="displayMobilePop">修改手机号</div>
    </div>
    <van-field
      :required="fieldStore.contact.weixin.is_require == 1"
      v-if="fieldStore.contact.weixin.is_display == 1"
      v-model="contact.weixin"
      :label="fieldStore.contact.weixin.field_cn"
      placeholder="请填写联系微信"
      class="reset_after no_border"
    />
    <div class="for_btn" v-if="fieldStore.contact.weixin.is_display">
      <div class="sync_phone">
        <van-checkbox v-model="wxSyncPhone" @change="syncWxPhone" icon-size="16px">同手机号</van-checkbox>
      </div>
    </div>
    <div class="form_split_10"></div>
    <van-field
      :required="fieldStore.basic.residence.is_require == 1"
      v-if="fieldStore.basic.residence.is_display == 1"
      v-model="basic.residence"
      :label="fieldStore.basic.residence.field_cn"
      placeholder="请填写"
      class="reset_after"
    />
    <van-field
      :required="fieldStore.basic.marriage.is_require == 1"
      v-if="fieldStore.basic.marriage.is_display == 1"
      readonly
      clickable
      :value="basic.marriageName"
      :label="fieldStore.basic.marriage.field_cn"
      placeholder="请选择"
      @click="showPickerMarriage = !showPickerMarriage"
      class="form_choose reset_after no_border"
    />
    <van-popup v-model="showPickerMarriage" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsMarriage"
        :default-index="marriageDefaultIndex"
        @confirm="onConfirmMarriage"
        @cancel="showPickerMarriage = false"
      />
    </van-popup>
    <div class="form_split_10"></div>
    <div class="box_3" v-if="showMoreBtn" @click="showMoreInfo = !showMoreInfo">
      <div :class="showMoreInfo ? 'txt up' : 'txt'">
        完善信息，获得更多求职机会
      </div>
    </div>
    <div v-if="showMoreBtn" class="form_split_10"></div>
    <div v-if="showMoreInfo">
      <van-field
        :required="fieldStore.basic.major.is_require == 1"
        v-if="fieldStore.basic.major.is_display == 1"
        readonly
        clickable
        :value="basic.majorName"
        :label="fieldStore.basic.major.field_cn"
        placeholder="请选择"
        @click="showPickerMajor = !showPickerMajor"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerMajor" position="bottom">
        <van-picker
          show-toolbar
          :columns="columnsMajor"
          @confirm="onConfirmMajor"
          @change="onChangeMajor"
          @cancel="showPickerMajor = false"
        />
      </van-popup>
      <van-field
        :required="fieldStore.basic.height.is_require == 1"
        v-if="fieldStore.basic.height.is_display == 1"
        v-model="basic.height"
        :label="fieldStore.basic.height.field_cn"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        :required="fieldStore.basic.householdaddress.is_require == 1"
        v-if="fieldStore.basic.householdaddress.is_display == 1"
        v-model="basic.householdaddress"
        :label="fieldStore.basic.householdaddress.field_cn"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        :required="fieldStore.contact.email.is_require == 1"
        v-if="fieldStore.contact.email.is_display == 1"
        v-model="contact.email"
        :label="fieldStore.contact.email.field_cn"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        :required="fieldStore.contact.qq.is_require == 1"
        v-if="fieldStore.contact.qq.is_display == 1"
        v-model="contact.qq"
        :label="fieldStore.contact.qq.field_cn"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        :required="fieldStore.basic.custom_field_1.is_require == 1"
        v-if="fieldStore.basic.custom_field_1.is_display == 1"
        v-model="basic.custom_field_1"
        :label="fieldStore.basic.custom_field_1.field_cn"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        :required="fieldStore.basic.custom_field_2.is_require == 1"
        v-if="fieldStore.basic.custom_field_2.is_display == 1"
        v-model="basic.custom_field_2"
        :label="fieldStore.basic.custom_field_2.field_cn"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        :required="fieldStore.basic.custom_field_3.is_require == 1"
        v-if="fieldStore.basic.custom_field_3.is_display == 1"
        v-model="basic.custom_field_3"
        :label="fieldStore.basic.custom_field_3.field_cn"
        placeholder="请填写"
        class="reset_after no_border"
      />
      <div class="box_4" v-if="fieldStore.basic.idcard.is_display">身份证号仅用于系统身份认证，未经允许不会对外公开</div>
      <van-field
        :required="fieldStore.basic.idcard.is_require == 1"
        v-if="fieldStore.basic.idcard.is_display == 1"
        v-model="basic.idcard"
        :label="fieldStore.basic.idcard.field_cn"
        placeholder="请填写"
        class="reset_after no_border"
      />
    </div>
    <div style="margin: 16px;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
    <van-dialog v-model="showMobilePop" title="更换手机号" show-cancel-button :before-close="beforeClose" @confirm="$refs.update_phone_number.handleSubmit()">
      <UpdatePhoneNumber ref="update_phone_number" :show_mobile="contact.mobile" @closePopup="closeMobilePop"></UpdatePhoneNumber>
    </van-dialog>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import UpdatePhoneNumber from '@/components/UpdatePhoneNumber'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditBasic',
  components: {
    UpdatePhoneNumber
  },
  data () {
    return {
      uploading: false,
      enable_close_mobile: true,
      majorStart: '',
      majorDefaultIndex1: 0,
      majorDefaultIndex2: 0,
      marriageDefaultIndex: 0,
      educationDefaultIndex: 0,
      showPickerEdu: false,
      showPickerEnterJob: false,
      showPickerBirthday: false,
      showPickerMarriage: false,
      showMoreInfo: false,
      showPickerMajor: false,
      showMobilePop: false,
      wxSyncPhone: false,
      isNoJobTime: false,
      currentDateEnterJob: '',
      currentDateBirthday: '',
      minDate: '',
      maxDate: '',
      maxBirthdayDate: '',
      basic: {
        fullname: '',
        sex: '1',
        birthday: '',
        education: 1,
        educationName: '',
        enter_job_time: '',
        enter_job_time_name: '',
        photo_img: 0,
        photo_img_src: '',
        residence: '',
        marriage: 0,
        marriageName: '',
        major1: 0,
        major2: 0,
        majorName: '',
        idcard: '',
        height: 0,
        householdaddress: '',
        custom_field_1: '',
        custom_field_2: '',
        custom_field_3: ''
      },
      contact: {
        mobile: '',
        weixin: '',
        email: '',
        qq: ''
      }
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'education').then(() => {
      // 恢复最高学历
      this.educationDefaultIndex = this.columnsEdu.findIndex(
        item => parseInt(item.id) === parseInt(this.basic.education)
      )
    })
    this.$store.dispatch('getClassify', 'marriage').then(() => {
      // 恢复婚姻状况
      this.marriageDefaultIndex = this.columnsMarriage.findIndex(
        item => parseInt(item.id) === parseInt(this.basic.marriage)
      )
    })
    this.$store.dispatch('getClassify', 'major').then(() => {
      // 恢复所学专业
      let pickerMajor = this.$store.state.classifyMajorOriginal
      if (this.basic.major1) {
        let _this = this
        Object.keys(pickerMajor).forEach(function (key, index) {
          if (parseInt(pickerMajor[key].value) === parseInt(_this.basic.major1)) {
            _this.majorDefaultIndex1 = index
            _this.majorStart = pickerMajor[key].label
            _this.majorDefaultIndex2 = pickerMajor[key].children.findIndex(
              item => parseInt(item.value) === parseInt(_this.basic.major2)
            )
          }
        })
      } else {
        this.majorDefaultIndex1 = 0
        this.majorStart = pickerMajor[0].label
        this.majorDefaultIndex2 = 0
      }
    })
    this.initDate()
    this.initData()
    // 恢复出生日期和开始工作时间
    let birthdayDateArr = this.basic.birthday.split('-')
    this.currentDateBirthday = new Date(
      parseInt(birthdayDateArr[0]),
      parseInt(birthdayDateArr[1]) - 1
    )
    if (this.basic.enter_job_time) {
      // 有工作经验
      let enterJobDateArr = this.basic.enter_job_time.split('-')
      this.currentDateEnterJob = new Date(
        parseInt(enterJobDateArr[0]),
        parseInt(enterJobDateArr[1]) - 1
      )
    }
  },
  watch: {
    'contact.weixin': function () {
      this.$nextTick(function () {
        this.wxSyncPhone = this.contact.mobile === this.contact.weixin
      })
    }
  },
  computed: {
    columnsEdu () {
      return this.$store.state.classifyEdu
    },
    columnsMarriage () {
      return this.$store.state.classifyMarriage
    },
    columnsMajor () {
      return [
        {
          values: Object.keys(this.$store.state.classifyMajor),
          defaultIndex: this.majorDefaultIndex1
        },
        {
          values: this.$store.state.classifyMajor[this.majorStart],
          defaultIndex: this.majorDefaultIndex2
        }
      ]
    },
    basicStore () {
      return this.$store.state.resume.basic
    },
    contactStore () {
      return this.$store.state.resume.contact
    },
    fieldStore () {
      return this.$store.state.resume.field_rule
    },
    showMoreBtn () {
      return this.$store.state.resume.field_rule.basic.major.is_display == 1 ||
                         this.$store.state.resume.field_rule.basic.height.is_display == 1 ||
                         this.$store.state.resume.field_rule.basic.householdaddress.is_display == 1 ||
                         this.$store.state.resume.field_rule.contact.email.is_display == 1 ||
                         this.$store.state.resume.field_rule.contact.qq.is_display == 1 ||
                         this.$store.state.resume.field_rule.basic.custom_field_1.is_display == 1 ||
                         this.$store.state.resume.field_rule.basic.custom_field_2.is_display == 1 ||
                         this.$store.state.resume.field_rule.basic.custom_field_3.is_display == 1 ||
                         this.$store.state.resume.field_rule.basic.idcard.is_display == 1
    }
  },
  methods: {
    beforeClose (action, done) {
      if (action === 'cancel') {
        this.enable_close_mobile = true
      }
      if (this.enable_close_mobile === true) {
        done()
      } else {
        done(false)
      }
    },
    displayMobilePop () {
      this.showMobilePop = true
      this.enable_close_mobile = false
    },
    closeMobilePop (mobile) {
      this.contact.mobile = mobile
      if (this.wxSyncPhone === true) {
        this.contact.weixin = mobile
      }
      this.showMobilePop = false
    },
    // 同手机号
    syncWxPhone (checked) {
      if (checked) {
        this.contact.weixin = this.contact.mobile
      }
    },
    // 头部返回
    goBack () {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    },
    // 初始化接口返回数据
    initData () {
      // 初始化接口返回数据
      let interfaceBasic = this.basicStore
      let interfaceContact = this.contactStore
      this.basic.photo_img_src = interfaceBasic.photo_img_src
      this.basic.photo_img = interfaceBasic.photo_img
      this.basic.fullname = interfaceBasic.fullname
      this.basic.sex = '' + interfaceBasic.sex + ''
      this.basic.birthday = interfaceBasic.birthday
      if (interfaceBasic.enter_job_time) {
        let enterDate = this.dateFormat(
          new Date(interfaceBasic.enter_job_time * 1000)
        )
        this.basic.enter_job_time = enterDate
        this.basic.enter_job_time_name = enterDate
      } else {
        // 无工作经验
        this.basic.enter_job_time = ''
        this.basic.enter_job_time_name = '无工作经验'
        this.isNoJobTime = true
      }
      this.basic.education = interfaceBasic.education
      this.basic.educationName = interfaceBasic.education_text
      this.contact.mobile = interfaceContact.mobile
      this.contact.weixin = interfaceContact.weixin
      this.wxSyncPhone = this.contact.mobile === this.contact.weixin
      this.basic.householdaddress = interfaceBasic.householdaddress
      this.basic.marriage = interfaceBasic.marriage
      this.basic.marriageName = interfaceBasic.marriage_text
      this.basic.major1 = interfaceBasic.major1
      this.basic.major2 = interfaceBasic.major2
      this.basic.majorName = interfaceBasic.major_text
      this.basic.height = interfaceBasic.height
      this.basic.residence = interfaceBasic.residence
      this.contact.email = interfaceContact.email
      this.contact.qq = interfaceContact.qq
      this.basic.idcard = interfaceBasic.idcard
      this.basic.custom_field_1 = interfaceBasic.custom_field_1
      this.basic.custom_field_2 = interfaceBasic.custom_field_2
      this.basic.custom_field_3 = interfaceBasic.custom_field_3
    },
    // 设置日期插件默认值
    initDate () {
      let nowDate = new Date()
      let nowYear = nowDate.getFullYear()
      let minYear = nowYear - 59
      this.minDate = new Date(minYear, 0)
      this.maxDate = nowDate
      this.currentDateBirthday = nowDate
      this.currentDateEnterJob = nowDate
      let minBirthdayYear = nowYear - 16
      this.maxBirthdayDate = new Date(minBirthdayYear, 11)
    },
    // 选择出生日期
    onConfirmBirthday (value) {
      this.basic.birthday = this.dateFormat(value)
      this.showPickerBirthday = !this.showPickerBirthday
    },
    // 选择开始工作时间
    onConfirmEnterJob (value) {
      this.basic.enter_job_time = this.dateFormat(value)
      this.basic.enter_job_time_name = this.dateFormat(value)
      this.isNoJobTime = false
      this.showPickerEnterJob = !this.showPickerEnterJob
    },
    // 选择无工作经验
    handleNoJobTime () {
      this.basic.enter_job_time = ''
      this.basic.enter_job_time_name = '无工作经验'
      this.isNoJobTime = true
      this.showPickerEnterJob = !this.showPickerEnterJob
    },
    // 选择最高学历
    onConfirmEducation (value) {
      this.basic.education = value.id
      this.basic.educationName = value.text
      this.showPickerEdu = !this.showPickerEdu
    },
    // 选择婚姻状况
    onConfirmMarriage (value) {
      this.basic.marriage = value.id
      this.basic.marriageName = value.text
      this.showPickerMarriage = !this.showPickerMarriage
    },
    // 选择所学专业
    onChangeMajor (picker, values) {
      picker.setColumnValues(1, this.$store.state.classifyMajor[values[0]])
    },
    // 选择所学专业
    onConfirmMajor (values, index) {
      let majorCategory = this.$store.state.classifyMajorOriginal
      this.basic.major1 = majorCategory[index[0]].value
      this.basic.major2 = majorCategory[index[0]].children[index[1]].value
      this.basic.majorName = `${values[0]}-${values[1]}`
      this.showPickerMajor = !this.showPickerMajor
    },
    // 保存
    handleSubmit () {
      if (this.fieldStore.basic.photo_img.is_display == 1 && this.fieldStore.basic.photo_img.is_require == 1) {
        if (!this.basic.photo_img) {
          this.$notify('请上传头像')
          return false
        }
      }
      if (!this.basic.fullname) {
        this.$notify('请填写姓名')
        return false
      }
      if (!this.basic.sex) {
        this.$notify('请选择性别')
        return false
      }
      if (!this.basic.birthday) {
        this.$notify('请选择出生日期')
        return false
      }
      if (!this.isNoJobTime) {
        if (!this.basic.enter_job_time) {
          this.$notify('请选择开始工作时间')
          return false
        }
      }
      if (!this.basic.education) {
        this.$notify('请选择学历')
        return false
      }
      if (!this.contact.mobile) {
        this.$notify('请填写联系电话')
        return false
      }
      if (this.fieldStore.contact.weixin.is_display == 1 && this.fieldStore.contact.weixin.is_require == 1) {
        if (!this.contact.weixin) {
          this.$notify(`请填写${this.fieldStore.contact.weixin.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.residence.is_display == 1 && this.fieldStore.basic.residence.is_require == 1) {
        if (!this.basic.residence) {
          this.$notify(`请填写${this.fieldStore.basic.residence.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.marriage.is_display == 1 && this.fieldStore.basic.marriage.is_require == 1) {
        if (this.basic.marriage === '') {
          this.$notify(`请选择${this.fieldStore.basic.marriage.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.major.is_display == 1 && this.fieldStore.basic.major.is_require == 1) {
        if (!this.basic.major1) {
          this.$notify(`请在下方完善信息中选择${this.fieldStore.basic.major.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.height.is_display == 1 && this.fieldStore.basic.height.is_require == 1) {
        if (!this.basic.height) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.basic.height.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.householdaddress.is_display == 1 && this.fieldStore.basic.householdaddress.is_require == 1) {
        if (!this.basic.householdaddress) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.basic.householdaddress.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.contact.email.is_display == 1 && this.fieldStore.contact.email.is_require == 1) {
        if (!this.contact.email) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.contact.email.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.contact.qq.is_display == 1 && this.fieldStore.contact.qq.is_require == 1) {
        if (!this.contact.qq) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.contact.qq.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.custom_field_1.is_display == 1 && this.fieldStore.basic.custom_field_1.is_require == 1) {
        if (!this.basic.custom_field_1) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.basic.custom_field_1.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.custom_field_2.is_display == 1 && this.fieldStore.basic.custom_field_2.is_require == 1) {
        if (!this.basic.custom_field_2) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.basic.custom_field_2.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.custom_field_3.is_display == 1 && this.fieldStore.basic.custom_field_3.is_require == 1) {
        if (!this.basic.custom_field_3) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.basic.custom_field_3.field_cn}`)
          return false
        }
      }
      if (this.fieldStore.basic.idcard.is_display == 1 && this.fieldStore.basic.idcard.is_require == 1) {
        if (!this.basic.idcard) {
          this.$notify(`请在下方完善信息中填写${this.fieldStore.basic.idcard.field_cn}`)
          return false
        }
      }
      http
        .post(api.resume_basic_save, {
          basic: {
            fullname: this.basic.fullname,
            sex: this.basic.sex,
            birthday: this.basic.birthday,
            education: this.basic.education,
            enter_job_time: this.basic.enter_job_time,
            photo_img: this.basic.photo_img,
            residence: this.basic.residence,
            marriage: this.basic.marriage,
            major1: this.basic.major1,
            major2: this.basic.major2,
            idcard: this.basic.idcard,
            height: this.basic.height,
            householdaddress: this.basic.householdaddress,
            custom_field_1: this.basic.custom_field_1,
            custom_field_2: this.basic.custom_field_2,
            custom_field_3: this.basic.custom_field_3
          },
          contact: {
            mobile: this.contact.mobile,
            weixin: this.contact.weixin,
            email: this.contact.email,
            qq: this.contact.qq
          }
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.$router.push({ path: '/member/personal/resume' })
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 上传头像
    uploadAvatar (file) {
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
          this.basic.photo_img_src = res.data.file_url
          this.basic.photo_img = res.data.file_id
        })
        .catch(err => {
          this.uploading = false
          console.log(err)
        })
    },
    // 格式化日期
    dateFormat (time) {
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    }
  },
  mounted () {
    // 改变上传头像样式
    document
      .querySelector('.van-uploader__wrapper')
      .setAttribute('style', 'height: 100%;width:100%')
    document
      .querySelector('.van-uploader__upload')
      .setAttribute('style', 'margin: 0;height: 100%;width:100%')
    document
      .querySelector('.van-uploader__input')
      .setAttribute('style', 'z-index: 2')
  }
}
</script>

<style lang="scss" scoped>
.van-uploader {
  width: 100%;
  height: 100%;
}
.van-uploader__wrapper {
  width: 100%;
  height: 100%;
  .van-uploader__upload {
    width: 100%;
    height: 100%;
    margin: 0;
  }
}
.box_5 {
  .item {
    &:not(:last-child)::after {
      position: absolute;
      right: 0;
      top: 20px;
      height: 40px;
      border-right: 1px solid #e2e2e2;
      content: ' ';
    }
    .tx2 {
      padding: 7.5px 0 17.5px;
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
    content: ' ';
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
.box_4 {
  font-size: 12px;
  color: #999999;
  padding: 12.5px 16px;
  background-color: #f3f3f3;
}
.box_3 {
  width: 100%;
  text-align: center;
  padding: 10px 0;
  background-color: #ffffff;
  .txt {
    font-size: 13px;
    line-height: 22px;
    color: #999999;
    width: 192px;
    margin: 0 auto;
    padding-right: 23px;
    position: relative;
    &::before {
      content: '';
      width: 11px;
      height: 11px;
      border: 1px solid #999999;
      border-radius: 100%;
      position: absolute;
      right: 0;
      top: 5px;
    }
    &::after {
      position: absolute;
      right: 3.45px;
      top: 8px;
      width: 4px;
      height: 4px;
      border-bottom: 1px solid #999999;
      border-right: 1px solid #999999;
      transform: rotate(45deg);
      content: '';
    }
    &.up {
      &::after {
        position: absolute;
        right: 3.45px;
        top: 10px;
        width: 4px;
        height: 4px;
        border-top: 1px solid #999999;
        border-left: 1px solid #999999;
        transform: rotate(45deg);
        content: '';
        border-right: 0;
        border-bottom: 0;
      }
    }
  }
}
.box_2 {
  width: 100%;
  background-color: #ffffff;
  padding: 39px 0 13px;
  text-align: center;
  font-size: 12px;
  color: #666666;
}
.box_1 {
  .avatar_box {
    position: absolute;
    left: 50%;
    top: 73px;
    transform: translate(-50%, 0);
    width: 75px;
    height: 75px;
    border-radius: 100%;
    border: 1px solid #ffffff;
    background-color: #f7f7f7;
    overflow: hidden;
    .avatar {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      border: 0;
      border-radius: 100%;
      &.has {
        z-index: 1;
      }
    }
  }
  position: relative;
  width: 100%;
  height: 120px;
  background-color: #2e95fa;
  .box_head {
    width: 100%;
    height: 52px;
    line-height: 52px;
    text-align: center;
    font-size: 17px;
    color: #ffffff;
    font-weight: bolder;
    position: relative;
    background-color: #2e95fa;
    .head_back {
      position: absolute;
      left: 0;
      top: 0;
      width: 40px;
      height: 100%;
      background: url('../../assets/images/back_ico_white.svg') center no-repeat;
      background-size: 20px;
    }
  }
}
.edit_phone {
  position: absolute;
  right: 0;
  top: -50px;
  font-size: 14px;
  color: #2199ff;
  padding: 0 15px;
  height: 50px;
  line-height: 50px;
}
.sync_phone {
  .van-checkbox { line-height: 50px; height: 50px; }
  position: absolute;
  right: 0;
  top: -50px;
  font-size: 14px;
  color: #515151;
  padding: 0 15px;
  height: 50px;
  line-height: 50px;
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
