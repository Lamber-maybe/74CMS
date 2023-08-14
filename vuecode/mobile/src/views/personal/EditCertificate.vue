<template>
  <div id="app">
    <Head>获得证书</Head>
    <div class="box_2">添加专业技能、职业证书或职称；最多可以添加6份证书</div>
    <van-field
      v-model="name"
      required
      label="证书名称"
      placeholder="请输入"
      class="reset_after"
    />
    <van-field
      required
      readonly
      clickable
      :value="obtaintime"
      label=" 获得时间"
      placeholder="请选择"
      @click="showPickerDate = !showPickerDate"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerDate" position="bottom">
      <van-datetime-picker
        v-model="currentDate"
        type="year-month"
        title="选择获得时间"
        :min-date="minDate"
        :max-date="maxDate"
        @confirm="onConfirmDate"
        @cancel="showPickerDate = false"
      />
    </van-popup>
    <div style="margin: 16px 16px 0;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
    <div
      class="box_1"
      v-if="parseInt(id) > 0"
      @click="deleteCurrentCertificate"
    >
      删除此证书
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditCertificate',
  data () {
    return {
      showPickerDate: false,
      currentDate: '',
      id: 0,
      name: '',
      obtaintime: '',
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
      let thisCertificate = this.certificateStore.filter(
        item => parseInt(item.id) === parseInt(this.id)
      )
      this.initData(thisCertificate[0])
      // 恢复获得时间
      let starTimeDateArr = this.obtaintime.split('-')
      this.currentDate = new Date(
        parseInt(starTimeDateArr[0]),
        parseInt(starTimeDateArr[1]) - 1
      )
    }
  },
  computed: {
    certificateStore () {
      return this.$store.state.resume.certificate_list
    }
  },
  methods: {
    // 初始化接口返回数据
    initData (interfaceCertificate) {
      this.name = interfaceCertificate.name
      this.obtaintime = this.dateFormat(
        new Date(interfaceCertificate.obtaintime * 1000)
      )
    },
    // 设置日期插件默认值
    initDate () {
      let nowDate = new Date()
      let nowYear = nowDate.getFullYear()
      let minYear = nowYear - 59
      this.minDate = new Date(minYear, 0)
      this.maxDate = nowDate
      this.currentDate = nowDate
    },
    // 选择获得时间
    onConfirmDate (value) {
      this.obtaintime = this.dateFormat(value)
      this.showPickerDate = !this.showPickerDate
    },
    // 保存
    handleSubmit () {
      if (!this.name) {
        this.$notify('请填写证书名称')
        return false
      }
      if (!this.obtaintime) {
        this.$notify('请选择获得时间')
        return false
      }
      http
        .post(api.resume_certificate_save, {
          id: this.id,
          name: this.name,
          obtaintime: this.obtaintime
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
    // 删除此证书
    deleteCurrentCertificate () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该证书吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_certificate_delete, {
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
.box_2 {
  font-size: 13px;
  color: #666666;
  padding: 15px 16px;
  background-color: #f3f3f3;
}
</style>
