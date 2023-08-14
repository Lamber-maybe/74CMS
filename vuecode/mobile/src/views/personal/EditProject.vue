<template>
  <div id="app">
    <Head>项目经历</Head>
    <div class="form_top_border"></div>
    <van-field
      v-model="projectname"
      required
      label="项目名称"
      placeholder="请输入"
      class="reset_after"
    />
    <van-field
      v-model="role"
      required
      label="担任角色"
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
      label="项目描述"
      class="reset_after no_border"
    />
    <div class="reset_after">
      <textarea
        v-model="description"
        rows="3"
        placeholder="请填写项目描述"
      >
    </textarea>
    </div>
    <div style="margin: 16px 16px 0;">
<!--       修改项目经历重复保存  zch 2022/9/16-->
      <van-button round block type="info" @click="handleSubmit" :disabled="issubmit">
        保存
      </van-button>
    </div>
    <div class="box_1" v-if="parseInt(id) > 0" @click="deleteCurrentProject">
      删除此条项目经历
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditProject',
  data () {
    return {
      showPickerStartDate: false,
      showPickerEndDate: false,
      currentStartDate: '',
      currentEndDate: '',
      id: 0,
      projectname: '',
      role: '',
      description: '',
      starttime: '',
      endtime: '',
      endtimeName: '',
      todate: 0,
      minDate: '',
      maxDate: '',
      issubmit: false // 修改项目经历重复保存  zch 2022/9/16
    }
  },
  created () {
    this.initDate()
    // 获取路由中的id
    this.id = this.$route.params.id
    if (this.id > 0) {
      // 如果有id，根据id取出对应的项目经历
      let thisWork = this.projectStore.filter(
        item => parseInt(item.id) === parseInt(this.id)
      )
      this.initData(thisWork[0])
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
    projectStore () {
      return this.$store.state.resume.project_list
    }
  },
  methods: {
    // 初始化接口返回数据
    initData (interfaceProject) {
      this.projectname = interfaceProject.projectname
      this.role = interfaceProject.role
      this.description = interfaceProject.description
      this.starttime = this.dateFormat(
        new Date(interfaceProject.starttime * 1000)
      )
      if (parseInt(interfaceProject.todate)) {
        // 至今
        this.endtime = ''
        this.endtimeName = '至今'
        this.todate = 1
      } else {
        this.endtime = this.dateFormat(
          new Date(interfaceProject.endtime * 1000)
        )
        this.endtimeName = this.dateFormat(
          new Date(interfaceProject.endtime * 1000)
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
      if (!this.projectname) {
        this.$notify('请填写项目名称')
        return false
      }
      if (!this.role) {
        this.$notify('请填写担任角色')
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
        this.$notify('请填写项目描述')
        return false
      }
      // 修改项目经历重复保存  zch 2022/9/16
      this.issubmit = true
      http
        .post(api.resume_project_save, {
          id: this.id,
          projectname: this.projectname,
          role: this.role,
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
            // 修改项目经历重复保存  zch 2022/9/16
            this.issubmit = false
            this.$notify(res.message)
          }
        })
        .catch(err => {
          // 修改项目经历重复保存  zch 2022/9/16
          this.issubmit = false
          console.log(err)
        })
    },
    // 删除此条项目经历
    deleteCurrentProject () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该条项目经历吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_project_delete, {
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
.reset_after{
  textarea{
    width: 100%;
    line-height: 24px;
    padding: 24px 27px;
    border: none;
    font-size: 13.5px;
    color: #000000ba;
    background-color: white;
    resize: none;
  }
  textarea::-webkit-input-placeholder{
    color: #bec7cb;
  }
}
</style>
