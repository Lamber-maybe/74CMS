<template>
  <div id="app">
    <Head>创建简历4/4</Head>
    <div class="form_active_title_2">最近一份工作经历</div>
    <van-field
      required
      v-model="work.companyname"
      label="公司名称"
      placeholder="请输入公司名称"
      class="reset_after"
    />
    <van-field
      required
      v-model="work.jobname"
      label="职位名称"
      placeholder="请输入职位名称"
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
        v-model="currentEndDate"
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
      v-model="work.duty"
      rows="3"
      type="textarea"
      label="工作职责"
      placeholder="简单描述一下你的工作职责"
      show-word-limi
      class="reset_after no_border"
    />
    <div v-if="isShowAddEduBox">
      <div class="form_active_title_2 mt_9">教育经历 <span class="title_close" @click="isShowAddEduBox = !isShowAddEduBox"></span></div>
      <van-field
      required
        v-model="education.school"
        label="学校名称"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
      required
        v-model="education.major"
        label="专业名称"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        readonly
        clickable
        required
        :value="education.educationName"
        label="取得学历"
        placeholder="请选择"
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
        readonly
        clickable
        :value="education.starttime"
        label="入学时间"
        placeholder="请选择"
        @click="showPickerEduStartDate = true"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerEduStartDate" position="bottom">
        <van-datetime-picker
          v-model="currentStartDate"
          type="year-month"
          title="选择入学时间"
          :min-date="minDate"
          :max-date="maxDate"
          @confirm="onConfirmEduStartDate"
          @cancel="showPickerEduStartDate = false"
        />
      </van-popup>
      <van-field
      required
        readonly
        clickable
        :value="education.endtimeName"
        label="毕业时间"
        placeholder="请选择"
        @click="showPickerEduEndDate = true"
        class="form_choose reset_after"
      />
      <van-popup v-model="showPickerEduEndDate" position="bottom">
        <div class="for-pop-title" @click="handleEduEndTime">至今</div>
        <van-datetime-picker
          v-model="currentEndDate"
          type="year-month"
          title=""
          :min-date="minDate"
          :max-date="maxDate"
          @confirm="onConfirmEduEndDate"
          @cancel="showPickerEduEndDate = false"
        />
      </van-popup>
    </div>
    <div v-if="!isShowAddEduBox">
      <div class="form_split_18"></div>
      <div class="add_handle_bar" @click="isShowAddEduBox = !isShowAddEduBox">
        <div class="add_handle">添加教育经历</div>
      </div>
    </div>
    <div class="form_split_18"></div>
    <div style="margin: 16px; position: relative">
      <van-button round block type="info" @click="handleSubmit">
        完成
      </van-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'RegIntStep4',
  created () {
    this.$store.dispatch('getClassify', 'education')
    this.initDate()
  },
  computed: {
    columnsEdu () {
      return this.$store.state.classifyEdu
    }
  },
  data () {
    return {
      showPickerEduStartDate: false,
      showPickerEduEndDate: false,
      currentStartDate: '',
      currentEndDate: '',
      showPickerStartDate: false,
      showPickerEndDate: false,
      isShowAddEduBox: false,
      showPickerEdu: false,
      minDate: '',
      maxDate: '',
      'work': {
        'companyname': '',
        'jobname': '',
        'starttime': '',
        'endtime': '',
        'endtimeName': '',
        'todate': 0,
        'duty': ''
      },
      'education': {
        'school': '',
        'major': '',
        'starttime': '',
        'endtime': '',
        'endtimeName': '',
        'todate': 0,
        'education': '',
        'educationName': ''
      }
    }
  },
  methods: {
    // 设置日期插件默认值
    initDate () {
      let nowDate = new Date()
      let nowYear = nowDate.getFullYear()
      let minYear = nowYear - 59
      this.minDate = new Date(minYear, 0)
      this.maxDate = nowDate
      this.currentStartDate = nowDate
      this.currentEndDate = nowDate
    },
    onConfirmEducation (value) {
      this.education.education = value.id
      this.education.educationName = value.text
      this.showPickerEdu = !this.showPickerEdu
    },
    handleWorkEndTime () {
      this.work.endtime = ''
      this.work.endtimeName = '至今'
      this.work.todate = 1
      this.showPickerEndDate = !this.showPickerEndDate
    },
    handleEduEndTime () {
      this.education.endtime = ''
      this.education.endtimeName = '至今'
      this.education.todate = 1
      this.showPickerEduEndDate = !this.showPickerEduEndDate
    },
    onConfirmEndDate (value) {
      this.work.endtime = this.dateFormat(value)
      this.work.endtimeName = this.dateFormat(value)
      this.work.todate = 0
      this.showPickerEndDate = !this.showPickerEndDate
    },
    onConfirmEduEndDate (value) {
      this.education.endtime = this.dateFormat(value)
      this.education.endtimeName = this.dateFormat(value)
      this.education.todate = 0
      this.showPickerEduEndDate = !this.showPickerEduEndDate
    },
    onConfirmStartDate (value) {
      this.work.starttime = this.dateFormat(value)
      this.showPickerStartDate = !this.showPickerStartDate
    },
    onConfirmEduStartDate (value) {
      this.education.starttime = this.dateFormat(value)
      this.showPickerEduStartDate = !this.showPickerEduStartDate
    },
    dateFormat (time) {
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    },
    handleSubmit () {
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
      if (!this.work.duty) {
        this.$notify('请填写工作职责')
        return false
      }
      if (!this.work.endtime) {
        this.work.todate = 1
      }
      let postData = {
        'work': {
          'companyname': this.work.companyname,
          'jobname': this.work.jobname,
          'starttime': this.work.starttime,
          'endtime': this.work.endtime,
          'todate': this.work.todate,
          'duty': this.work.duty
        }
      }
      if (this.isShowAddEduBox) {
        if (!this.education.school) {
          this.$notify('请填写学校名称')
          return false
        }
        if (!this.education.major) {
          this.$notify('请选择专业名称')
          return false
        }
        if (!this.education.education) {
          this.$notify('请选择取得学历')
          return false
        }
        if (!this.education.starttime) {
          this.$notify('请选择入学时间')
          return false
        }
        if (!this.education.endtime) {
          this.education.todate = 1
        }
        postData.education = {
          'school': this.education.school,
          'major': this.education.major,
          'starttime': this.education.starttime,
          'endtime': this.education.endtime,
          'todate': this.education.todate,
          'education': this.education.education
        }
      }

      http.post(api.reg_resume_int_step4, postData)
        .then(res => {
          this.$router.push('/member/personal/resume_finish')
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style scoped>

</style>
