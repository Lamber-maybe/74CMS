<template>
  <div id="app">
    <Head :show_back="'false'">创建简历1/2</Head>
    <div class="form_top_border"></div>
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
      readonly
      clickable
      required
      :value="basic.enter_job_time_name"
      label="开始工作时间"
      placeholder="请选择"
      @click="showPickerEnterJob = !showPickerEnterJob"
      class="form_choose reset_after big_label"
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
      v-model="contact.mobile"
      label="联系电话"
      placeholder="请填写联系电话"
      class="reset_after"
          @input="hanlderMobile"
    />
    <van-field
      v-model="contact.weixin"
      label="联系微信"
      placeholder="请填写联系微信"
      class="reset_after"
          @input="hanlderWeixin"
    />
    <div class="for_btn">
      <div class="sync_phone">
        <van-checkbox v-model="checkedEditPhone" icon-size="16px"
              @change="handlerSync">
          同手机号
        </van-checkbox>
      </div>
    </div>
    <div class="form_split_18"></div>
    <van-field
      readonly
      clickable
      required
      :value="intention.categoryName"
      label="期望职位"
      placeholder="点击选择期望职位"
      @click="showPickerJobCategory = !showPickerJobCategory"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerJobCategory"
               :lazy-render="false"
               position="bottom"
               :style="{ 'max-height': '70%' }"
               ref="dropCategory"
               :lock-scroll="false"
               @click-overlay="handleCategoryOverlay"
               @opened="openedCategory"
    >
      <JobCategoryFilter
        :categories="['', '', '']"
        :type="false"
        @doSelect="doSelectCategory"
        :all="false"
      ></JobCategoryFilter>
    </van-popup>
    <van-field
      readonly
      clickable
      required
      :value="intention.wageName"
      label="期望薪资"
      placeholder="点击选择薪资"
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
      :value="intention.districtName"
      label="期望地区"
      placeholder="点击选择期望地区"
      @click="showPickerDistrict = !showPickerDistrict"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerDistrict"
               position="bottom"
               :style="{ 'max-height': '70%' }"
               :lock-scroll="false"
               ref="dropDistrict"
               @click-overlay="handleDistrictOverlay"
               @opened="openedDistrict">
      <DistrictFilter
        :districts="['', '', '']"
        :type="false"
        @doSelect="doSelectDistrict"
      ></DistrictFilter>
    </van-popup>
    <van-field
      readonly
      clickable
      required
      :value="basic.currentName"
      label="求职状态"
      placeholder="点击选择求职状态"
      @click="showPickerCurrent = !showPickerCurrent"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerCurrent" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsCurrent"
        @confirm="onConfirmCurrent"
        @cancel="showPickerCurrent = false"
      />
    </van-popup>
    <div v-if="isShowAddExpBox">
      <div class="form_split_title">最近一份工作经历</div>
      <van-field
        v-model="work.companyname"
        required
        label="公司名称"
        placeholder="请输入"
        class="reset_after"
      />
      <van-field
        v-model="work.jobname"
        required
        name="jobs"
        label="职位名称"
        placeholder="请输入"
        class="reset_after"
      />
      <van-field
        required
        readonly
        clickable
        :value="work.starttime"
        label="入职时间"
        placeholder="点击选择入职时间"
        @click="showPickerStartDate = true"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerStartDate" position="bottom">
        <van-datetime-picker
          v-model="currentStartDate"
          type="year-month"
          title="选择入职时间"
          :min-date="minDate"
          :max-date="maxDate"
          @confirm="onConfirmStartDate"
          @cancel="showPickerStartDate = false"
        />
      </van-popup>
      <van-field
        required
        readonly
        clickable
        :value="work.endtimeName"
        label="离职时间"
        placeholder="点击选择离职时间"
        @click="showPickerEndDate = true"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerEndDate" position="bottom">
        <div class="for-pop-title" @click="handleWorkEndTime">至今</div>
        <van-datetime-picker
          v-model="currentDateEnterJob"
          type="year-month"
          title=""
          :min-date="minDate"
          :max-date="maxDate"
          @confirm="onConfirmEndDate"
          @cancel="showPickerEndDate = false"
        />
      </van-popup>
      <van-field
        required
        readonly
        clickable
        label="工作职责"
        class="reset_after no_border"
      />
      <van-field
        v-model="work.duty"
        rows="3"
        type="textarea"
        placeholder="简单描述一下你的工作职责"
        show-word-limi
        class="reset_after no_border"
      />
    </div>
    <div style="margin: 16px;">
      <van-button round block type="info" @click="handleSubmit">
        下一步
      </van-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import JobCategoryFilter from '@/components/JobCategoryFilter'
import DistrictFilter from '@/components/DistrictFilter'

export default {
  name: 'RegFormStep1',
  components: {
    JobCategoryFilter,
    DistrictFilter
  },
  data () {
    return {
      showPickerEndDate: false,
      showPickerStartDate: false,
      showPickerCurrent: false,
      showPickerDistrict: false,
      showPickerBirthday: false,
      showPickerEdu: false,
      showPickerEnterJob: false,
      checkedEditPhone: true,
      showPickerJobCategory: false,
      showPickerWage: false,
      currentDateEnterJob: '',
      currentDateBirthday: '',
      minDate: '',
      maxDate: '',
      maxBirthdayDate: '',
      currentStartDate: '',
      basic: {
        fullname: '',
        sex: '1',
        birthday: '',
        education: '',
        educationName: '',
        enter_job_time: '',
        enter_job_time_name: '',
        current: '',
        currentName: ''
      },
      contact: {
        mobile: '',
        weixin: ''
      },
      intention: {
        categoryName: '',
        category1: '',
        category2: '',
        category3: '',
        wageName: '',
        minwage: '',
        maxwage: '',
        districtName: '',
        district1: '',
        district2: '',
        district3: ''
      },
      work: {
        companyname: '',
        jobname: '',
        starttime: '',
        endtime: '',
        endtimeName: '',
        todate: 0,
        duty: ''
      },
      isShowAddExpBox: false,
      isNoJobTime: false
    }
  },
  created () {
    this.initDate()
    this.$store.dispatch('getClassify', 'citycategory')
    this.$store.dispatch('getClassify', 'education')
    this.$store.dispatch('getClassify', 'current')
    this.$store.dispatch('getClassifyWage')
    this.contact.mobile = this.$store.state.userMobile
    this.contact.weixin = this.$store.state.userMobile
  },
  computed: {
    columnsEdu () {
      return this.$store.state.classifyEdu
    },
    columnsWage () {
      return [
        { values: Object.keys(this.$store.state.classifyWage) },
        { values: this.$store.state.classifyWage[500] }
      ]
    },
    columnsCurrent () {
      return this.$store.state.classifyCurrent
    },
    columnsJobCategory () {
      return this.$store.state.classifyJobCategory
    },
    areaDistrict () {
      return this.$store.state.classifyCityCategory
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
    // 设置日期插件默认值
    initDate () {
      let nowDate = new Date()
      let nowYear = nowDate.getFullYear()
      let minYear = nowYear - 59
      this.minDate = new Date(minYear, 0)
      this.maxDate = nowDate
      this.currentDateBirthday = nowDate
      this.currentDateEnterJob = nowDate
      this.currentStartDate = nowDate
      let minBirthdayYear = nowYear - 16
      this.maxBirthdayDate = new Date(minBirthdayYear, 11)
    },
    doSelectCategory (data) {
      this.intention.category1 = data[0]
      this.intention.category2 = data[1]
      this.intention.category3 = data[2]
      this.intention.categoryName = data[3]
      this.showPickerJobCategory = !this.showPickerJobCategory
    },
    doSelectDistrict (data) {
      this.intention.district1 = data[0]
      this.intention.district2 = data[1]
      this.intention.district3 = data[2]
      this.intention.districtName = data[3]
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
    handleWorkEndTime () {
      this.work.endtime = ''
      this.work.endtimeName = '至今'
      this.work.todate = 1
      this.showPickerEndDate = !this.showPickerEndDate
    },
    handleNoJobTime () {
      this.basic.enter_job_time = ''
      this.basic.enter_job_time_name = '无工作经验'
      this.isShowAddExpBox = false
      this.isNoJobTime = true
      this.showPickerEnterJob = !this.showPickerEnterJob
    },
    onChangeWage (picker, values) {
      picker.setColumnValues(1, this.$store.state.classifyWage[values[0]])
    },
    onConfirmBirthday (value) {
      this.basic.birthday = this.dateFormat(value)
      this.showPickerBirthday = !this.showPickerBirthday
    },
    onConfirmEndDate (value) {
      this.work.endtime = this.dateFormat(value)
      this.work.endtimeName = this.dateFormat(value)
      this.work.todate = 0
      this.showPickerEndDate = !this.showPickerEndDate
    },
    onConfirmStartDate (value) {
      this.work.starttime = this.dateFormat(value)
      this.showPickerStartDate = !this.showPickerStartDate
    },
    onConfirmCurrent (value) {
      this.basic.current = value.id
      this.basic.currentName = value.text
      this.showPickerCurrent = !this.showPickerCurrent
    },
    onConfirmDistrict (value) {
      this.intention.district1 = value[0].code
      this.intention.district2 = value[1].code
      this.intention.district3 = value[2].code
      this.intention.districtName = `${value[0].name}/${value[1].name}/${value[2].name}`
      this.showPickerDistrict = !this.showPickerDistrict
    },
    onConfirmWage (values) {
      this.intention.minwage = parseInt(values[0])
      this.intention.maxwage = parseInt(values[1])
      this.intention.wageName = `${parseInt(values[0])}-${parseInt(
        values[1]
      )}元`
      this.showPickerWage = !this.showPickerWage
    },
    onConfirmJobCategory (values, index) {
      let jobCategory = this.$store.state.classifyJobCategory
      this.intention.category1 = jobCategory[index[0]].id
      this.intention.category2 = jobCategory[index[0]].children[index[1]].id
      this.intention.category3 =
        jobCategory[index[0]].children[index[1]].children[index[2]].id
      this.intention.categoryName = `${values}`
      this.showPickerJobCategory = !this.showPickerJobCategory
    },
    onConfirmEnterJob (value) {
      this.basic.enter_job_time = this.dateFormat(value)
      this.basic.enter_job_time_name = this.dateFormat(value)
      this.showPickerEnterJob = !this.showPickerEnterJob
      this.isShowAddExpBox = true
      this.isNoJobTime = false
    },
    onConfirmEducation (value) {
      this.basic.education = value.id
      this.basic.educationName = value.text
      this.showPickerEdu = !this.showPickerEdu
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
        this.$notify('请选择出生日期')
        return false
      }
      if (!this.basic.education) {
        this.$notify('请选择学历')
        return false
      }
      if (!this.isNoJobTime) {
        if (!this.basic.enter_job_time) {
          this.$notify('请选择开始工作时间')
          return false
        }
      }
      if (!this.contact.mobile) {
        this.$notify('请填写联系电话')
        return false
      }
      if (!this.contact.weixin) {
        this.$notify('请填写联系微信')
        return false
      }
      if (!this.intention.category1) {
        this.$notify('请选择期望职位')
        return false
      }
      if (!this.intention.minwage || !this.intention.maxwage) {
        this.$notify('请选择薪资')
        return false
      }
      if (!this.intention.district1) {
        this.$notify('请选择期望地区')
        return false
      }
      if (!this.basic.current) {
        this.$notify('请选择求职状态')
        return false
      }
      let dataValue = {
        basic: {
          fullname: this.basic.fullname,
          sex: this.basic.sex,
          birthday: this.basic.birthday,
          education: this.basic.education,
          enter_job_time: this.basic.enter_job_time,
          current: this.basic.current
        },
        contact: {
          mobile: this.contact.mobile,
          weixin: this.contact.weixin
        },
        intention: {
          category1: this.intention.category1,
          category2: this.intention.category2,
          category3: this.intention.category3,
          minwage: this.intention.minwage,
          maxwage: this.intention.maxwage,
          district1: this.intention.district1,
          district2: this.intention.district2,
          district3: this.intention.district3
        }
      }
      if (this.isShowAddExpBox) {
        if (!this.work.companyname) {
          this.$notify('请填写公司名称')
          return false
        }
        if (!this.work.jobname) {
          this.$notify('请填写职位名称')
          return false
        }
        if (!this.work.starttime) {
          this.$notify('请选择入职时间')
          return false
        }
        if (parseInt(this.work.todate) === 0) {
          if (!this.work.endtime) {
            this.$notify('请选择离职时间')
            return false
          }
        }
        if (!this.work.duty) {
          this.$notify('请填写工作职责')
          return false
        }
        Object.assign(dataValue, {
          work: {
            companyname: this.work.companyname,
            jobname: this.work.jobname,
            starttime: this.work.starttime,
            endtime: this.work.endtime,
            todate: this.work.todate,
            duty: this.work.duty
          }
        })
      }
      http.post(api.reg_resume_form_step1, dataValue)
        .then(res => {
          this.$router.push({ path: '/member/personal/resume_add_form/step2' })
        })
        .catch(err => {
          console.log(err)
        })
    },
    dateFormat (time) {
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    }
  }
}
</script>
<style lang="scss" scoped>
.edit_phone {
  position: absolute;
  right: 12px;
  top: -100px;
  font-size: 14px;
  color: #2199ff;
  padding: 13px 0;
  line-height: 24px;
}
.sync_phone {
  position: absolute;
  right: 12px;
  top: -50px;
  font-size: 14px;
  color: #515151;
  padding: 13px 0;
  line-height: 24px;
}
</style>
