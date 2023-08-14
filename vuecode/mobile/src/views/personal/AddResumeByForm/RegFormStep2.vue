<template>
  <div id="app">
    <Head>创建简历2/2</Head>
    <div class="form_split_title">教育经历</div>
    <van-field
      v-model="education.school"
      required
      label="学校名称"
      placeholder="请填写"
      class="reset_after"
    />
    <van-field
      v-model="education.major"
      required
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
      @click="showPickerStartDate = !showPickerStartDate"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerStartDate" position="bottom">
      <van-datetime-picker
        v-model="currentStartDate"
        type="year-month"
        title="选择入学时间"
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
      :value="education.endtimeName"
      label="毕业时间"
      placeholder="请选择"
      @click="showPickerEndDate = !showPickerEndDate"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerEndDate" position="bottom">
      <div class="for-pop-title" @click="handleEduEndTime">至今</div>
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
    <div v-if="isShowAddSpeBox">
      <div class="form_split_title">自我描述</div>
      <van-field
        v-model="specialty"
        rows="5"
        maxlength="50"
        type="textarea"
        placeholder="你可以通过描述你擅长的东西，也可以简单的介绍一下，说说你的求职目标。"
        show-word-limi
        class="no_border"
      />
    </div>
    <div v-if="!isShowAddSpeBox">
      <div class="form_split_18"></div>
      <div class="add_handle_bar" @click="isShowAddSpeBox = !isShowAddSpeBox">
        <div class="add_handle">添加自我描述</div>
      </div>
    </div>
    <div class="form_split_18"></div>
    <div style="margin: 16px;">
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
  name: 'RegFormStep2',
  data () {
    return {
      currentStartDate: '',
      currentEndDate: '',
      showPickerStartDate: false,
      showPickerEndDate: false,
      showPickerEdu: false,
      education: {
        school: '',
        major: '',
        starttime: '',
        endtime: '',
        endtimeName: '',
        todate: 0,
        education: '',
        educationName: ''
      },
      specialty: '',
      isShowAddSpeBox: false,
      minDate: '',
      maxDate: ''
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'education')
    this.initDate()
  },
  computed: {
    columnsEdu () {
      return this.$store.state.classifyEdu
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
    handleEduEndTime () {
      this.education.endtime = ''
      this.education.endtimeName = '至今'
      this.education.todate = 1
      this.showPickerEndDate = !this.showPickerEndDate
    },
    onConfirmEndDate (value) {
      this.education.endtime = this.dateFormat(value)
      this.education.endtimeName = this.dateFormat(value)
      this.education.todate = 0
      this.showPickerEndDate = !this.showPickerEndDate
    },
    onConfirmStartDate (value) {
      this.education.starttime = this.dateFormat(value)
      this.showPickerStartDate = !this.showPickerStartDate
    },
    onConfirmEducation (value) {
      this.education.education = value.id
      this.education.educationName = value.text
      this.showPickerEdu = !this.showPickerEdu
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
        this.$notify('请填写专业名称')
        return false
      }
      if (!this.education.education) {
        this.$notify('请选择学历')
        return false
      }
      if (!this.education.starttime) {
        this.$notify('请选择入学时间')
        return false
      }
      if (parseInt(this.education.todate) === 0) {
        if (!this.education.endtime) {
          this.$notify('请选择毕业时间')
          return false
        }
      }
      if (!this.specialty) {
        this.$notify('请填写自我描述')
        return false
      }
      http.post(api.reg_resume_form_step2, {
        education: {
          school: this.education.school,
          major: this.education.major,
          starttime: this.education.starttime,
          endtime: this.education.endtime,
          todate: this.education.todate,
          education: this.education.education
        },
        specialty: this.specialty
      })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$router.push('/member/personal/resume_finish')
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style scoped></style>
