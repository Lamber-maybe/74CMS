<template>
  <div id="app">
    <Head>工作经历</Head>
    <div class="form_top_border"></div>
    <van-field
      v-model="companyname"
      required
      label="公司名称"
      placeholder="请输入"
      class="reset_after"
    />
    <van-field
      v-model="jobname"
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
      :value="starttime"
      label="入职时间"
      placeholder="点击选择入职时间"
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
      label="离职时间"
      placeholder="点击选择离职时间"
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
    <van-field
      required
      readonly
      clickable
      label="工作职责"
      class="reset_after no_border"
    />
    <van-field
      v-model="duty"
      rows="3"
      type="textarea"
      placeholder="简单描述一下你的工作职责"
      show-word-limi
      class="reset_after"
    />
    <div style="margin: 16px 16px 0;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
    <div class="box_1" v-if="parseInt(id) > 0" @click="deleteCurrentWork">
      删除此条工作经历
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditWorks',
  data () {
    return {
      showPickerEndDate: false,
      showPickerStartDate: false,
      currentStartDate: '',
      currentEndDate: '',
      id: 0,
      companyname: '',
      jobname: '',
      duty: '',
      starttime: '',
      endtime: '',
      endtimeName: '',
      todate: 0,
      minDate: '',
      maxDate: ''
    }
  },
  created () {
    this.initDate()
    // 获取路由中的id
    this.id = this.$route.params.id
    if (this.id > 0) {
      // 如果有id，根据id取出对应的工作经历
      let thisWork = this.workStore.filter(
        item => parseInt(item.id) === parseInt(this.id)
      )
      this.initData(thisWork[0])
      // 恢复入职时间和离职时间
      let starTimeDateArr = this.starttime.split('-')
      this.currentStartDate = new Date(
        parseInt(starTimeDateArr[0]),
        parseInt(starTimeDateArr[1]) - 1
      )
      if (!parseInt(this.todate)) {
        // 不是至今恢复离职时间
        let endTimeDateArr = this.endtime.split('-')
        this.currentEndDate = new Date(
          parseInt(endTimeDateArr[0]),
          parseInt(endTimeDateArr[1]) - 1
        )
      }
    }
  },
  computed: {
    workStore () {
      return this.$store.state.resume.work_list
    }
  },
  methods: {
    // 初始化接口返回数据
    initData (interfaceWork) {
      this.companyname = interfaceWork.companyname
      this.jobname = interfaceWork.jobname
      this.duty = interfaceWork.duty
      this.starttime = this.dateFormat(new Date(interfaceWork.starttime * 1000))
      if (parseInt(interfaceWork.todate)) {
        // 至今
        this.endtime = ''
        this.endtimeName = '至今'
        this.todate = 1
      } else {
        this.endtime = this.dateFormat(new Date(interfaceWork.endtime * 1000))
        this.endtimeName = this.dateFormat(
          new Date(interfaceWork.endtime * 1000)
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
    // 选择入职时间
    onConfirmStartDate (value) {
      this.starttime = this.dateFormat(value)
      this.showPickerStartDate = !this.showPickerStartDate
    },
    // 选择离职时间
    onConfirmEndDate (value) {
      this.endtime = this.dateFormat(value)
      this.endtimeName = this.dateFormat(value)
      this.todate = 0
      this.showPickerEndDate = !this.showPickerEndDate
    },
    // 选择至今
    handleToDateTime () {
      this.endtime = ''
      this.endtimeName = '至今'
      this.todate = 1
      this.showPickerEndDate = !this.showPickerEndDate
    },
    // 保存
    handleSubmit () {
      if (!this.companyname) {
        this.$notify('请填写公司名称')
        return false
      }
      if (!this.jobname) {
        this.$notify('请填写职位名称')
        return false
      }
      if (!this.starttime) {
        this.$notify('请选择入职时间')
        return false
      }
      if (parseInt(this.todate) === 0) {
        if (!this.endtime) {
          this.$notify('请选择离职时间')
          return false
        }
      }
      if (!this.duty) {
        this.$notify('请填写工作职责')
        return false
      }
      http
        .post(api.resume_work_save, {
          id: this.id,
          companyname: this.companyname,
          jobname: this.jobname,
          duty: this.duty,
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
    // 删除此条工作经历
    deleteCurrentWork () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该条工作经历吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_work_delete, {
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
