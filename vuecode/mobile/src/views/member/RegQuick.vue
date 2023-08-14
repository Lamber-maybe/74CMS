<template>
  <div id="app">
    <Head>快速注册</Head>
    <div class="bx1"></div>
    <div class="bx2">
      <van-field
        v-model="fullname"
        required
        label="姓名"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field required class="reset_after" name="sex" label="性别">
        <template #input>
          <van-radio-group
            v-model="sex"
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
        :value="birthday"
        label="出生日期"
        placeholder="请选择"
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
        :value="educationName"
        label="最高学历"
        placeholder="请选择"
        @click="showPickerEdu = !showPickerEdu"
        class="form_choose reset_after"
      />
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
        readonly
        clickable
        :value="enter_job_time_name"
        label="开始工作时间"
        placeholder="请选择"
        @click="showPickerEnterJob = !showPickerEnterJob"
        class="form_choose reset_after"
      />
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
      <van-field
        required
        v-model="mobile"
        label="联系电话"
        placeholder="请填写联系电话"
        class="reset_after"
      />
      <div class="for_btn">
        <div class="edit_phone" @click="sendSms">{{ $store.state.sendSmsBtnText }}</div>
      </div>
      <van-field
        required
        v-model="code"
        label="验证码"
        placeholder="请输入验证码"
        class="reset_after"
      />
      <van-field
        required
        type="password"
        v-model="password"
        label="密码"
        placeholder="请输入密码"
        class="reset_after"
      />
      <div class="form_split_10"></div>
      <van-field
        readonly
        clickable
        required
        :value="categoryName"
        label="期望职位"
        placeholder="请选择期望职位"
        @click="showPickerJobCategory = !showPickerJobCategory"
        class="form_choose reset_after"
      />
      <van-popup
        v-model="showPickerJobCategory"
        :lazy-render="false"
        position="bottom"
        :style="{ 'max-height': '70%' }"
        ref="dropCategory"
        :lock-scroll="false"
        @click-overlay="handleCategoryOverlay"
        @opened="openedCategory"
      >
        <JobCategoryFilter
          :categories="[category1, category2, category3]"
          :type="false"
          @doSelect="doSelectCategory"
          :all="false"
        ></JobCategoryFilter>
      </van-popup>
      <van-field
        readonly
        clickable
        required
        :value="districtName"
        label="期望地区"
        placeholder="请选择期望地区"
        @click="showPickerDistrict = !showPickerDistrict"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerDistrict"
                 position="bottom"
                 :style="{ 'max-height': '70%' }"
                 ref="dropDistrict"
                 :lock-scroll="false"
                 @click-overlay="handleDistrictOverlay"
                 @opened="openedDistrict"
      >
        <DistrictFilter
          :districts="[district1, district2, district3]"
          :type="false"
          @doSelect="doSelectDistrict"
        ></DistrictFilter>
      </van-popup>
      <van-field
        readonly
        clickable
        required
        name="wage"
        :value="wageName"
        label="期望薪资"
        placeholder="请选择期望薪资"
        @click="showPickerWage = !showPickerWage"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerWage" position="bottom">
        <van-picker
          show-toolbar
          :columns="columnsWage"
          @confirm="onConfirmWage"
          @change="onChangeWage"
          @cancel="showPickerWage = false"
        />
      </van-popup>
      <van-field
        readonly
        clickable
        required
        name="current"
        :value="currentName"
        label="求职状态"
        placeholder="请选择求职状态"
        @click="showPickerCurrent = !showPickerCurrent"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerCurrent" position="bottom">
        <van-picker
          show-toolbar
          :columns="columnsCurrent"
          :default-index="currentDefaultIndex"
          @confirm="onConfirmCurrent"
          @cancel="showPickerCurrent = false"
        />
      </van-popup>
      <div style="padding: 16px;">
        <van-button round block type="info" @click="handleSubmit">
          立即投递
        </van-button>
      </div>
    </div>
    <Captcha ref="captcha"></Captcha>
    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <Login
        :utype="2"
        :single_login="true"
        @afterLogin="afterLogin"
        :goback_custom="true"
        @gobackCustomMethod="closeLogin"
      ></Login>
    </van-popup>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
import JobCategoryFilter from '@/components/JobCategoryFilter'
import DistrictFilter from '@/components/DistrictFilter'
import Login from '@/components/Login'
export default {
  name: 'RegQuick',
  components: {
    Captcha,
    JobCategoryFilter,
    DistrictFilter,
    Login
  },
  data () {
    return {
      fullname: '',
      sex: '1',
      birthday: '',
      education: '',
      educationName: '',
      mobile: '',
      enter_job_time: '',
      enter_job_time_name: '',
      showPickerEdu: false,
      showPickerBirthday: false,
      showPickerEnterJob: false,
      currentDateBirthday: '',
      maxBirthdayDate: '',
      minDate: '',
      currentDateEnterJob: '',
      isNoJobTime: false,
      educationDefaultIndex: 0,
      code: '',
      password: '',
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
      showPickerWage: false,
      showPickerDistrict: false,
      showPickerJobCategory: false,
      district1: '',
      district2: '',
      district3: '',
      district: '',
      districtName: '',
      category1: '',
      category2: '',
      category3: '',
      categoryName: '',
      minwage: '',
      maxwage: '',
      wageName: '',
      current: '',
      currentName: '',
      showPickerCurrent: false,
      currentDefaultIndex: 0,
      wageStart: this.$store.state.minWage,
      wageDefaultIndex1: 0,
      wageDefaultIndex2: 0,
      doRegQuickBtnDisabled: false,
      id: 0,
      showLogin: false
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'education')
    this.$store.dispatch('getClassify', 'jobcategory')
    this.$store.dispatch('getClassify', 'citycategory')
    this.$store.dispatch('getClassifyWage')
    this.$store.dispatch('getClassify', 'current')
    this.id = this.$route.query.id
    this.initDate()
  },
  mounted () {
    this.$store.commit('clearCountDownFun')
  },
  computed: {
    columnsEdu () {
      return this.$store.state.classifyEdu
    },
    areaDistrict () {
      return this.$store.state.classifyCityCategory
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
    columnsCurrent () {
      return this.$store.state.classifyCurrent
    }
  },
  methods: {
    closeLogin () {
      this.showLogin = false
    },
    // 登录成功之后做的操作
    afterLogin (data) {
      this.showLogin = false
      setTimeout(() => {
        this.$router.push('/job/' + this.id)
      }, 300)
    },
    // 立即投递
    handleSubmit () {
      if (this.doRegQuickBtnDisabled === true) {
        return false
      }
      if (!this.fullname) {
        this.$notify('请填写姓名')
        return false
      }
      if (!this.sex) {
        this.$notify('请选择性别')
        return false
      }
      if (!this.birthday) {
        this.$notify('请选择出生日期')
        return false
      }
      if (!this.education) {
        this.$notify('请选择学历')
        return false
      }
      if (!this.isNoJobTime) {
        if (!this.enter_job_time) {
          this.$notify('请选择开始工作时间')
          return false
        }
      }
      if (!this.mobile) {
        this.$notify('请填写联系电话')
        return false
      }
      if (!this.regularMobile.test(this.mobile)) {
        this.$notify('手机号格式不正确')
        return false
      }
      if (!this.code) {
        this.$notify('请填写验证码')
        return false
      }
      if (!this.password) {
        this.$notify('请填写密码')
        return false
      }
      if (!this.category1) {
        this.$notify('请选择期望职位')
        return false
      }
      if (!this.district1) {
        this.$notify('请选择期望地区')
        return false
      }
      if (!this.minwage || !this.maxwage) {
        this.$notify('请选择薪资')
        return false
      }
      if (!this.current) {
        this.$notify('请选择求职状态')
        return false
      }
      this.doRegQuickBtnDisabled = true
      http.post(api.reg_resume_quick, {
        jobid: this.id,
        fullname: this.fullname,
        sex: this.sex,
        birthday: this.birthday,
        education: this.education,
        enter_job_time: this.enter_job_time,
        category1: this.category1,
        category2: this.category2,
        category3: this.category3,
        district1: this.district1,
        district2: this.district2,
        district3: this.district3,
        minwage: this.minwage,
        maxwage: this.maxwage,
        current: this.current,
        mobile: this.mobile,
        code: this.code,
        password: this.password
      }).then(res => {
        this.doRegQuickBtnDisabled = false
        if (parseInt(res.code) === 200) {
          // 设置登录状态
          this.$store.commit('setLoginState', {
            whether: true,
            utype: res.data.utype,
            token: res.data.token,
            mobile: res.data.mobile,
            userIminfo: res.data.user_iminfo
          })
          if (parseInt(res.data.next_code) === 50005) {
            // 去完善简历
            this.$dialog
              .confirm({
                title: '提示',
                message: res.message,
                confirmButtonText: '继续完善',
                showCancelButton: false
              })
              .then(() => {
                // 继续完善
                this.$router.push('/member/personal/resume')
              })
          } else {
            this.$notify({ type: 'success', message: res.message })
            // 投递完成跳转到职位详情页
            setTimeout(() => {
              this.$router.push('/job/' + this.id)
            }, 1000)
          }
        } else {
          this.$notify(res.message)
        }
      }).catch(err => {
        this.doRegQuickBtnDisabled = false
        console.log(err)
      })
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
      this.birthday = this.dateFormat(value)
      this.showPickerBirthday = !this.showPickerBirthday
    },
    // 选择最高学历
    onConfirmEducation (value) {
      this.education = value.id
      this.educationName = value.text
      this.showPickerEdu = !this.showPickerEdu
    },
    // 选择无工作经验
    handleNoJobTime () {
      this.enter_job_time = ''
      this.enter_job_time_name = '无工作经验'
      this.isNoJobTime = true
      this.showPickerEnterJob = !this.showPickerEnterJob
    },
    // 选择开始工作时间
    onConfirmEnterJob (value) {
      this.enter_job_time = this.dateFormat(value)
      this.enter_job_time_name = this.dateFormat(value)
      this.isNoJobTime = false
      this.showPickerEnterJob = !this.showPickerEnterJob
    },
    // 发送验证码
    sendSms () {
      let _this = this
      if (this.$store.state.sendSmsBtnDisabled) {
        return false
      }
      if (!this.mobile) {
        this.$notify('请输入手机号')
        return false
      }
      if (!this.regularMobile.test(this.mobile)) {
        this.$notify('手机号格式不正确')
        return false
      }
      this.$refs.captcha.show(res => {
        this.$store
          .dispatch('sendSmsFun', {
            url: api.get_reg_resume_quick_code,
            mobile: this.mobile,
            type: 2,
            captcha: res
          })
          .then(response => {
            if (response.code === 200) {
              if (parseInt(response.data.notice) === 1) {
                _this.$store.commit('clearCountDownFun')
                // 手机号已注册
                _this.$dialog
                  .confirm({
                    title: '提示',
                    message: response.message,
                    confirmButtonText: '是',
                    cancelButtonText: '否'
                  })
                  .then(() => {
                    // 登录
                    _this.showLogin = true
                  })
                  .catch(() => {})
              } else {
                _this.$notify({type: 'success', message: _this.$store.state.sendSmsMessage})
              }
            } else {
              _this.$notify(_this.$store.state.sendSmsMessage)
            }
          })
      })
    },
    doSelectCategory (data) {
      this.category1 = data[0]
      this.category2 = data[1]
      this.category3 = data[2]
      this.categoryName = data[3]
      this.showPickerJobCategory = !this.showPickerJobCategory
    },
    doSelectDistrict (data) {
      this.district1 = data[0]
      this.district2 = data[1]
      this.district3 = data[2]
      this.districtName = data[3]
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
      setTimeout(() => {
        this.dynamicAssignFun(this.$refs.dropCategory)
      }, 300)
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
        obj.$children[0].offTop = parseInt(parseInt(offTop) + parseInt(popHeight) / 2)
      }
    },
    // 选择期望薪资
    onConfirmWage (values) {
      this.minwage = parseInt(values[0])
      this.maxwage = parseInt(values[1])
      this.wageName = `${parseInt(values[0])}-${parseInt(values[1])}元`
      this.showPickerWage = !this.showPickerWage
    },
    // 薪资级联
    onChangeWage (picker, values) {
      picker.setColumnValues(1, this.$store.state.classifyWage[values[0]])
    },
    // 选择求职状态
    onConfirmCurrent (value) {
      this.current = value.id
      this.currentName = value.text
      this.showPickerCurrent = !this.showPickerCurrent
    },
    // 格式化日期
    dateFormat (time) {
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    }
  }
}
</script>

<style lang="scss" scoped>
  .bx1 {
    width: 100%;height: 180px;background: url("../../assets/images/reg_quick_bg.png") center 0 no-repeat;
    background-size: 375px 180px;
  }
  .bx2 {
    background-color: #f3f3f3;position: relative;
    &::after {
      content: '';position: absolute;left: 0;top: -16px;z-index: 1;width: 100%;height: 16px;background-color: #ffffff;
      border-radius: 20px 20px 0 0;
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
</style>
