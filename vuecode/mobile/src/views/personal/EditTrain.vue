<template>
  <div id="app">
    <Head>培训经历</Head>
    <div class="form_top_border"></div>
    <van-field
      v-model="agency"
      required
      label="培训机构"
      placeholder="请输入"
      class="reset_after"
    />
    <van-field
      v-model="course"
      required
      label="培训课程"
      placeholder="请输入"
      class="reset_after"
    />
    <van-field
      required
      readonly
      clickable
      :value="starttime"
      label="开始时间"
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
      label=" 结束时间"
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
    <van-field
      required
      readonly
      clickable
      label="培训内容"
      class="reset_after no_border"
    />
    <van-field
      v-model="description"
      rows="3"
      type="textarea"
      placeholder="请填写培训内容"
      show-word-limi
      class="reset_after"
    />
    <div style="margin: 16px 16px 0;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
    <div class="box_1" v-if="parseInt(id) > 0" @click="deleteCurrentTrain">
      删除此条培训经历
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditTrain',
  data () {
    return {
      currentStartDate: '',
      currentEndDate: '',
      id: 0,
      agency: '',
      course: '',
      description: '',
      starttime: '',
      endtime: '',
      endtimeName: '',
      todate: 0,
      showPickerStartDate: false,
      showPickerEndDate: false,
      minDate: '',
      maxDate: ''
    }
  },
  created () {
    this.initDate()
    // 获取路由中的id
    this.id = this.$route.params.id
    if (this.id > 0) {
      // 如果有id，根据id取出对应的培训经历
      let thisTrain = this.trainStore.filter(
        item => parseInt(item.id) === parseInt(this.id)
      )
      this.initData(thisTrain[0])
      // 恢复开始时间和结束时间
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
  },
  computed: {
    trainStore () {
      return this.$store.state.resume.training_list
    }
  },
  methods: {
    // 初始化接口返回数据
    initData (interfaceTrain) {
      this.agency = interfaceTrain.agency
      this.course = interfaceTrain.course
      this.description = interfaceTrain.description
      this.starttime = this.dateFormat(
        new Date(interfaceTrain.starttime * 1000)
      )
      if (parseInt(interfaceTrain.todate)) {
        // 至今
        this.endtime = ''
        this.endtimeName = '至今'
        this.todate = 1
      } else {
        this.endtime = this.dateFormat(new Date(interfaceTrain.endtime * 1000))
        this.endtimeName = this.dateFormat(
          new Date(interfaceTrain.endtime * 1000)
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
    // 选择开始时间
    onConfirmStartDate (value) {
      this.starttime = this.dateFormat(value)
      this.showPickerStartDate = !this.showPickerStartDate
    },
    // 选择结束时间
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
      if (!this.agency) {
        this.$notify('请填写培训机构')
        return false
      }
      if (!this.course) {
        this.$notify('请填写培训课程')
        return false
      }
      if (!this.starttime) {
        this.$notify('请选择开始时间')
        return false
      }
      if (parseInt(this.todate) === 0) {
        if (!this.endtime) {
          this.$notify('请选择结束时间')
          return false
        }
      }
      if (!this.description) {
        this.$notify('请填写培训内容')
        return false
      }
      http
        .post(api.resume_train_save, {
          id: this.id,
          agency: this.agency,
          course: this.course,
          description: this.description,
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
    deleteCurrentTrain () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该条培训经历吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_train_delete, {
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
