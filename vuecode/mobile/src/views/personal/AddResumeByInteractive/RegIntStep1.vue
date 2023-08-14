<template>
  <div id="app">
    <Head :show_back="'false'">创建简历1/4</Head>
    <div class="form_active_title">“简单的自我介绍给企业留个好印象”</div>
    <van-field
      required
      v-model="basic.fullname"
      label="姓名"
      placeholder="请填写"
      class="reset_after"
    />
    <van-field
      required class="reset_after" label="性别">
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
    <van-field
      required
      readonly
      clickable
      :value="basic.birthday"
      label="出生日期"
      placeholder="点击选择出生日期"
      @click="showPickerBirthday = !showPickerBirthday"
      class="form_choose reset_after"
    />
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
    <van-field
      readonly
      clickable
      required
      :value="basic.educationName"
      label="最高学历"
      placeholder="点击选择学历"
      @click="showPickerEdu = !showPickerEdu"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerEdu" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsEdu"
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
          @input="hanlderMobile"
    />
    <van-field
      required
      v-model="contact.weixin"
      label="联系微信"
      placeholder="请填写联系微信"
      class="reset_after"
      @input="hanlderWeixin"
    />
    <div style="margin: 16px; position: relative">
      <van-button round block type="info" @click="handleSubmit">
        下一步
      </van-button>
      <div class="sync_phone">
        <van-checkbox v-model="checkedEditPhone" icon-size="16px" @change="handlerSync">同手机号</van-checkbox>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'RegIntStep1',
  created () {
    this.$store.dispatch('getClassify', 'education')
    this.initDate()
    this.contact.mobile = this.$store.state.userMobile
    this.contact.weixin = this.$store.state.userMobile
  },
  computed: {
    columnsEdu () {
      return this.$store.state.classifyEdu
    }
  },
  data () {
    return {
      enable_close_mobile: true,
      showMobilePop: false,
      currentDateEnterJob: '',
      currentDateBirthday: '',
      minDate: '',
      maxDate: '',
      maxBirthdayDate: '',
      checkedEditPhone: true,
      showPickerBirthday: false,
      showPickerEdu: false,
      basic: {
        fullname: '',
        sex: '1',
        birthday: '',
        education: '',
        educationName: ''
      },
      contact: {
        mobile: '',
        weixin: ''
      }
    }
  },
  methods: {
    hanlderMobile () {
      if (this.contact.weixin !== this.contact.mobile) {
        this.checkedEditPhone = false
      } else {
        this.checkedEditPhone = true
      }
    },
    hanlderWeixin () {
      if (this.contact.weixin !== this.contact.mobile) {
        this.checkedEditPhone = false
      } else {
        this.checkedEditPhone = true
      }
    },
    handlerSync () {
      if (this.checkedEditPhone === true) {
        this.contact.weixin = this.contact.mobile
      }
    },
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
      if (this.checkedEditPhone === true) {
        this.contact.weixin = mobile
      }
      this.showMobilePop = false
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
    onConfirmBirthday (value) {
      this.basic.birthday = this.dateFormat(value)
      this.showPickerBirthday = !this.showPickerBirthday
    },
    onConfirmEducation (value) {
      this.basic.education = value.id
      this.basic.educationName = value.text
      this.showPickerEdu = !this.showPickerEdu
    },
    dateFormat (time) {
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    },
    handleSubmit () {
      if (!this.basic.fullname) {
        this.$notify('请填写姓名')
        return false
      }
      if (!this.basic.sex) {
        this.$notify('请选择性别')
        return false
      }
      if (!this.basic.birthday) {
        this.$notify('请选择出生年份')
        return false
      }
      if (!this.basic.education) {
        this.$notify('请选择最高学历')
        return false
      }
      if (!this.contact.mobile) {
        this.$notify('请填写联系电话')
        return false
      }
      if (!this.contact.weixin) {
        this.$notify('请填写联系微信')
        return false
      }
      http.post(api.reg_resume_int_step1, {
        basic: {
          fullname: this.basic.fullname,
          sex: this.basic.sex,
          birthday: this.basic.birthday,
          education: this.basic.education
        },
        contact: {
          mobile: this.contact.mobile,
          weixin: this.contact.weixin
        }
      })
        .then(res => {
          this.$router.push({ path: '/member/personal/resume_add_int/step2' })
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.edit_phone {
  position: absolute;
  right: 0;
  top: -116px;
  font-size: 14px;
  color: #2199ff;
  padding: 13px 0;
  line-height: 24px;
}
.sync_phone {
  position: absolute;
  right: 0;
  top: -66px;
  font-size: 14px;
  color: #515151;
  padding: 13px 0;
  line-height: 24px;
}
</style>
