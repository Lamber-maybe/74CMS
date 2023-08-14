<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closePopup')">
      发送面试邀请
    </Head>
    <van-notice-bar
     v-if="mySetmeal.enable_video_interview===0"
      :scrollable="false"
      text="当前套餐不允许使用视频面试，请发起普通面试邀请"
    />
    <van-form @submit="onSubmit">
      <van-field
        required
        class="reset_after"
        name="type"
        v-model="form.type"
        label="面试形式"
        :rules="[{ required: true, message: '请选择面试形式' }]"
      >
        <template #input>
          <van-radio-group
            v-model="form.type"
            direction="horizontal"
            icon-size="14px"
          >
            <van-radio name="1">面试邀请</van-radio>
            <van-radio name="2" :disabled="mySetmeal.enable_video_interview===0?true:false">视频面试</van-radio>
          </van-radio-group>
        </template>
      </van-field>
      <van-field
        readonly
        v-model="apply_fullname"
        required
        label="面试人"
        placeholder="请填写"
        class="reset_after"
      />
      <van-field
        v-if="from != 'apply'"
        readonly
        clickable
        required
        v-model="jobname"
        label="面试职位"
        placeholder="请选择"
        @click="showJobsPicker = true"
        class="form_choose reset_after"
      />
      <van-popup
        v-model="showJobsPicker"
        position="bottom"
        v-if="from != 'apply'"
      >
        <van-picker
          show-toolbar
          :columns="options_jobs"
          @confirm="onConfirmJob"
          @cancel="showJobsPicker = false"
        />
      </van-popup>
      <van-field
        required
        readonly
        clickable
        name="interview_date"
        v-model="form.interview_date"
        label="面试日期"
        placeholder="请选择"
        @click="showDatePicker = true"
        class="form_choose reset_after"
        :rules="[{ required: true, message: '请选择面试日期' }]"
      />
      <van-popup v-model="showDatePicker" position="bottom">
        <van-datetime-picker
          type="date"
          title="选择面试日期"
          :min-date="minDate"
          :formatter="formatterDate"
          @confirm="handlerDate"
        />
      </van-popup>
      <van-field
        required
        readonly
        clickable
        name="interview_time"
        v-model="form.interview_time"
        label="面试时间"
        placeholder="请选择"
        @click="showTimePicker = true"
        class="form_choose reset_after"
        :rules="[{ required: true, message: '请选择面试时间' }]"
      />
      <van-popup v-model="showTimePicker" position="bottom">
        <van-datetime-picker
          type="time"
          title="选择面试时间"
          :min-hour="8"
          :max-hour="20"
          :formatter="formatterTime"
          @confirm="handlerTime"
          :filter="filterTime"
        />
      </van-popup>
      <van-field
        v-if="form.type==1"
        name="address"
        v-model="form.address"
        required
        label="面试地点"
        placeholder="请填写"
        class="reset_after"
        :rules="[{ required: true, message: '请填写面试地点' }]"
      />
      <div class="form_split_10"></div>
      <van-field
        name="contact"
        v-model="form.contact"
        required
        label="联系人"
        placeholder="请填写"
        class="reset_after"
        :rules="[{ required: true, message: '请填写联系人' }]"
      />
      <van-field
        name="tel"
        v-model="form.tel"
        required
        type="number"
        label="联系电话"
        placeholder="请填写"
        class="reset_after"
        :rules="[{ required: true, message: '请填写联系电话' }]"
      />
      <van-field
        name="note"
        v-model="form.note"
        label="备注"
        placeholder="请填写"
        class="reset_after"
      />
      <div style="margin: 16px;">
        <van-button round block type="info" native-type="submit">
          发送面试邀请
        </van-button>
      </div>
    </van-form>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AddInvitation',
  props: [
    'from',
    'apply_id',
    'apply_dataset_index',
    'apply_fullname',
    'resume_id'
  ],
  data () {
    return {
      minDate: new Date(),
      showJobsPicker: false,
      showDatePicker: false,
      showTimePicker: false,
      jobname: '',
      form: {
        type: '1',
        jobid: '',
        interview_date: '',
        interview_time: '',
        address: '',
        contact: '',
        tel: '',
        note: ''
      },
      isFilterTime: false,
      joblist: [],
      options_jobs: [],
      mySetmeal: {}
    }
  },
  watch: {
    'form.interview_date': function (value) {
      this.$nextTick(function () {
        let myDate = new Date() // 当前日期
        let year = myDate.getFullYear() // 获取当前年
        let mon = myDate.getMonth() + 1 // 获取当前月
        let date = myDate.getDate() // 获取当前日
        let chooseDate = value.split('-')
        this.isFilterTime = year === parseInt(chooseDate[0]) && mon === parseInt(chooseDate[1]) && date === parseInt(chooseDate[2])
        this.filterTime()
        if (this.isFilterTime) {
          this.form.interview_time = ''
        }
      })
    }
  },
  methods: {
    initCB () {
      this.jobname = ''
      this.form = {
        type: '1',
        jobid: '',
        interview_date: '',
        interview_time: '',
        address: '',
        contact: '',
        tel: '',
        note: ''
      }
      if (this.from !== 'apply') {
        http
          .get(api.company_published_jobslist, {})
          .then(res => {
            res.data.forEach(element => {
              this.joblist.push(element)
              this.options_jobs.push(element.jobname)
            })
          })
          .catch(() => {})
      }
      this.fetchSetmeal()
      this.fetchIterviewPre()
    },
    fetchSetmeal () {
      http
        .get(api.member_setmeal, {})
        .then(res => {
          this.mySetmeal = res.data.info
        })
        .catch(() => {})
    },
    fetchIterviewPre: function () {
      http
        .get(api.interview_add_pre, {})
        .then(res => {
          this.form.address = res.data.address
          this.form.contact = res.data.contact
          this.form.tel = res.data.tel
        })
        .catch(() => {})
    },
    onSubmit (values) {
      if (this.from === 'apply') {
        values.apply_id = this.apply_id
        http
          .post(api.company_jobapply_set_agree, values)
          .then(res => {
            this.$notify({ type: 'success', message: '邀请面试成功' })
            setTimeout(() => {
              this.$emit('closePopup', this.apply_dataset_index)
            }, 2000)
          })
          .catch(() => {})
      } else {
        values.resume_id = this.resume_id
        values.jobid = this.form.jobid
        http
          .post(api.interview_add, values)
          .then(res => {
            this.$notify({ type: 'success', message: '邀请面试成功' })
            setTimeout(() => {
              this.$emit('closePopup')
            }, 2000)
          })
          .catch(() => {})
      }
    },
    handlerDate (e) {
      let d = new Date(e)
      this.form.interview_date =
        d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate()
      this.showDatePicker = false
    },
    handlerTime (e) {
      this.form.interview_time = e
      this.showTimePicker = false
    },
    formatterDate (type, val) {
      if (type === 'year') {
        return `${val}年`
      } else if (type === 'month') {
        return `${val}月`
      } else if (type === 'day') {
        return `${val}日`
      }
      return val
    },
    formatterTime (type, val) {
      if (type === 'hour') {
        return `${val}时`
      } else if (type === 'minute') {
        return `${val}分`
      }
      return val
    },
    filterTime (type, options) {
      if (this.isFilterTime) {
        if (type === 'hour') {
          return options.filter(option => option > new Date().getHours())
        }
      }
      if (type === 'minute') {
        return options.filter(option => option % 10 === 0)
      }
      return options
    },
    onConfirmJob (value, index) {
      this.jobname = value
      for (const key in this.joblist) {
        if (key == index) {
          this.form.jobid = this.joblist[key].id
          break
        }
      }
      this.showJobsPicker = false
    }
  }
}
</script>

<style lang="scss" scoped>
.split_bar {
  width: 100%;
  height: 9px;
}
</style>
