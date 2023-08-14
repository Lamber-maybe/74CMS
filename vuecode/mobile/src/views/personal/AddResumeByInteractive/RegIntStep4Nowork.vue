<template>
  <div id="app">
    <Head>创建简历4/4</Head>
    <div class="form_active_title_2">教育经历</div>
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
      required
      readonly
      clickable
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
  name: 'RegIntStep4Nowork',
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
      showPickerEdu: false,
      minDate: '',
      maxDate: '',
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
    handleEduEndTime () {
      this.education.endtime = ''
      this.education.endtimeName = '至今'
      this.education.todate = 1
      this.showPickerEduEndDate = !this.showPickerEduEndDate
    },
    onConfirmEduEndDate (value) {
      this.education.endtime = this.dateFormat(value)
      this.education.endtimeName = this.dateFormat(value)
      this.education.todate = 0
      this.showPickerEduEndDate = !this.showPickerEduEndDate
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
      http.post(api.reg_resume_int_step4_nowork, {
        'school': this.education.school,
        'major': this.education.major,
        'starttime': this.education.starttime,
        'endtime': this.education.endtime,
        'todate': this.education.todate,
        'education': this.education.education
      })
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
