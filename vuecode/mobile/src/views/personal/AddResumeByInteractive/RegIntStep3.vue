<template>
  <div id="app">
    <Head>创建简历3/4</Head>
    <div class="form_active_title">“告诉我，你目前的求职状态是什么？”</div>
    <van-field
      readonly
      clickable
      required
      :value="currentName"
      label="当前求职状态"
      placeholder="点击选择求职状态"
      @click="showPickerCurrent = true"
      class="form_choose reset_after big_label"
    />
    <van-popup v-model="showPickerCurrent" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsCurrent"
        @confirm="onConfirmCurrent"
        @cancel="showPickerCurrent = false"
      />
    </van-popup>
    <van-field
      readonly
      clickable
      required
      :value="enter_job_time_name"
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
export default {
  name: 'RegIntStep3',
  created () {
    this.$store.dispatch('getClassify', 'current')
    this.initDate()
  },
  computed: {
    columnsCurrent () {
      return this.$store.state.classifyCurrent
    }
  },
  data () {
    return {
      currentDateEnterJob: '',
      currentDateBirthday: '',
      minDate: '',
      maxDate: '',
      maxBirthdayDate: '',
      enter_job_time: '',
      enter_job_time_name: '',
      current: 0,
      currentName: '',
      showPickerCurrent: false,
      showPickerEnterJob: false
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
      this.currentDateBirthday = nowDate
      this.currentDateEnterJob = nowDate
      let minBirthdayYear = nowYear - 16
      this.maxBirthdayDate = new Date(minBirthdayYear, 11)
    },
    handleNoJobTime () {
      this.enter_job_time = ''
      this.enter_job_time_name = '无工作经验'
      this.showPickerEnterJob = !this.showPickerEnterJob
    },
    onConfirmEnterJob (value) {
      this.enter_job_time = this.dateFormat(value)
      this.enter_job_time_name = this.dateFormat(value)
      this.showPickerEnterJob = !this.showPickerEnterJob
    },
    onConfirmCurrent (value) {
      this.current = value.id
      this.currentName = value.text
      this.showPickerCurrent = !this.showPickerCurrent
    },
    dateFormat (time) {
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    },
    handleSubmit () {
      if (!this.current) {
        this.$notify('请选择求职状态')
        return false
      }

      http.post(api.reg_resume_int_step3, {
        enter_job_time: this.enter_job_time,
        current: this.current
      })
        .then(res => {
          if (!this.enter_job_time) {
            this.$router.push({ path: '/member/personal/resume_add_int/step4_nowork' })
          } else {
            this.$router.push({ path: '/member/personal/resume_add_int/step4' })
          }
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style lang="scss" scoped></style>
