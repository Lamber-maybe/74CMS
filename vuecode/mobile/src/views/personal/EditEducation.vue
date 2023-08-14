<template>
  <div id="app">
    <Head>教育经历</Head>
    <div class="form_top_border"></div>
    <van-field
      v-model="school"
      required
      label="学校名称"
      placeholder="请填写"
      class="reset_after"
    />
    <van-field
      :required="fieldStore.education.major.is_require === 1"
      v-if="fieldStore.education.major.is_display"
      v-model="major"
      :label="fieldStore.education.major.field_cn"
      placeholder="请填写"
      class="reset_after"
    />
    <van-field
      readonly
      clickable
      required
      :value="educationName"
      label="取得学历"
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
      :value="starttime"
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
      :value="endtimeName"
      label="毕业时间"
      placeholder="请选择"
      @click="showPickerEndDate = !showPickerEndDate"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerEndDate" position="bottom">
      <div class="for-pop-title" @click="handleToDateTime">至今</div>
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
    <div style="margin: 16px 16px 0;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
    <div class="box_1" v-if="parseInt(id) > 0" @click="deleteCurrentEducation">
      删除此教育经历
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditEducation',
  data () {
    return {
      educationDefaultIndex: 0,
      showPickerEndDate: false,
      showPickerStartDate: false,
      currentStartDate: '',
      currentEndDate: '',
      showPickerEdu: false,
      id: 0,
      school: '',
      major: '',
      education: '',
      educationName: '',
      starttime: '',
      endtime: '',
      endtimeName: '',
      todate: 0,
      minDate: '',
      maxDate: ''
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'education')
    this.initDate()
    // 获取路由中的id
    this.id = this.$route.params.id
    if (this.id > 0) {
      // 如果有id，根据id取出对应的教育经历
      let thisEducation = this.educationStore.filter(
        item => parseInt(item.id) === parseInt(this.id)
      )
      this.initData(thisEducation[0])
      // 恢复最高学历
      this.educationDefaultIndex = this.columnsEdu.findIndex(
        item => parseInt(item.id) === parseInt(this.education)
      )
      // 恢复入学时间和毕业时间
      let starTimeDateArr = this.starttime.split('-')
      this.currentStartDate = new Date(
        parseInt(starTimeDateArr[0]),
        parseInt(starTimeDateArr[1]) - 1
      )
      if (!parseInt(this.todate)) {
        // 不是至今恢复结束时间
        let endTimeDateArr = this.endtime.split('-')
        this.currentEndDate = new Date(
          parseInt(endTimeDateArr[0]),
          parseInt(endTimeDateArr[1]) - 1
        )
      }
    }
    console.log(this.columnsEdu)
  },
  computed: {
    columnsEdu () {
      return this.$store.state.classifyEdu
    },
    educationStore () {
      return this.$store.state.resume.education_list
    },
    fieldStore () {
      return this.$store.state.resume.field_rule
    }
  },
  methods: {
    // 初始化接口返回数据
    initData (interfaceEducation) {
      this.school = interfaceEducation.school
      this.major = interfaceEducation.major
      this.education = interfaceEducation.education
      this.educationName = this.columnsEdu.filter(
        item => parseInt(item.id) === parseInt(this.education)
      )[0].text
      this.starttime = this.dateFormat(
        new Date(interfaceEducation.starttime * 1000)
      )
      if (parseInt(interfaceEducation.todate)) {
        // 至今
        this.endtime = ''
        this.endtimeName = '至今'
        this.todate = 1
      } else {
        this.endtime = this.dateFormat(
          new Date(interfaceEducation.endtime * 1000)
        )
        this.endtimeName = this.dateFormat(
          new Date(interfaceEducation.endtime * 1000)
        )
        this.todate = 0
      }
    },
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
    // 选择取得学历
    onConfirmEducation (value) {
      this.education = value.id
      this.educationName = value.text
      this.showPickerEdu = !this.showPickerEdu
    },
    // 选择入学时间
    onConfirmStartDate (value) {
      this.starttime = this.dateFormat(value)
      this.showPickerStartDate = !this.showPickerStartDate
    },
    // 选择至今
    handleToDateTime () {
      this.endtime = ''
      this.endtimeName = '至今'
      this.todate = 1
      this.showPickerEndDate = !this.showPickerEndDate
    },
    // 选择毕业时间
    onConfirmEndDate (value) {
      this.endtime = this.dateFormat(value)
      this.endtimeName = this.dateFormat(value)
      this.todate = 0
      this.showPickerEndDate = !this.showPickerEndDate
    },
    // 保存
    handleSubmit () {
      if (!this.school) {
        this.$notify('请填写学校名称')
        return false
      }
      if (this.fieldStore.education.major.is_require) {
        if (!this.major) {
          this.$notify(`请填写${this.fieldStore.education.major.field_cn}`)
          return false
        }
      }
      if (!this.education) {
        this.$notify('请选择学历')
        return false
      }
      if (!this.starttime) {
        this.$notify('请选择入学时间')
        return false
      }
      if (parseInt(this.todate) === 0) {
        if (!this.endtime) {
          this.$notify('请选择毕业时间')
          return false
        }
      }
      http
        .post(api.resume_education_save, {
          id: this.id,
          school: this.school,
          major: this.major,
          education: this.education,
          starttime: this.starttime,
          endtime: this.endtime,
          todate: this.todate
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
    // 删除此条教育经历
    deleteCurrentEducation () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该条教育经历吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_education_delete, {
              id: this.id
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
        })
        .catch(() => {
          // 取消按钮
        })
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
.box_1 {
  font-size: 13px;
  color: #999999;
  text-align: center;
  width: 100%;
  padding: 15px 0;
}
</style>
