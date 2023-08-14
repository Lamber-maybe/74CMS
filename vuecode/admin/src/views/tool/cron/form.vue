<template>
  <div class="app-container">
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item
        label="计划任务名称"
        prop="name"
      >
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item
        label="任务脚本类名称"
        prop="action"
      >
        <el-input v-model="form.action" />
        <el-tooltip
          class="item"
          effect="dark"
          placement="top-start"
        >
          <div slot="content">
            设置本任务的执行程序类名称，请勿包含路径；
            <br>
            程序脚本统一存放于 /application/common/lib/cron/ 目录中
          </div>
          <span class="smalltip">
            <i class="el-icon-info" />
          </span>
        </el-tooltip>
      </el-form-item>
      <el-form-item
        label="每周"
        prop="weekday"
      >
        <el-select
          v-model="form.weekday"
          placeholder="请选择星期几执行"
        >
          <el-option
            label="不限制"
            value="-1"
          />
          <el-option
            label="一"
            value="1"
          />
          <el-option
            label="二"
            value="2"
          />
          <el-option
            label="三"
            value="3"
          />
          <el-option
            label="四"
            value="4"
          />
          <el-option
            label="五"
            value="5"
          />
          <el-option
            label="六"
            value="6"
          />
          <el-option
            label="日"
            value="0"
          />
        </el-select>
        <el-tooltip
          class="item"
          effect="dark"
          placement="top-start"
        >
          <div slot="content">
            设置星期几执行本任务，本设置会覆盖下面的“日”设定
          </div>
          <span class="smalltip">
            <i class="el-icon-info" />
          </span>
        </el-tooltip>
      </el-form-item>
      <el-form-item
        label="每月"
        prop="day"
      >
        <el-select
          v-model="form.day"
          placeholder="设置哪一日执行本任务"
        >
          <el-option
            label="不限制"
            value="-1"
          />
          <el-option
            v-for="(item, index) in options_days"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-tooltip
          class="item"
          effect="dark"
          placement="top-start"
        >
          <div slot="content">
            设置哪一日执行本任务
          </div>
          <span class="smalltip">
            <i class="el-icon-info" />
          </span>
        </el-tooltip>
      </el-form-item>
      <el-form-item
        label="小时"
        prop="hour"
      >
        <el-select
          v-model="form.hour"
          placeholder="设置哪一小时执行本任务"
        >
          <el-option
            label="不限制"
            value="-1"
          />
          <el-option
            v-for="(item, index) in options_hours"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-tooltip
          class="item"
          effect="dark"
          placement="top-start"
        >
          <div slot="content">
            设置哪一小时执行本任务
          </div>
          <span class="smalltip">
            <i class="el-icon-info" />
          </span>
        </el-tooltip>
      </el-form-item>
      <el-form-item
        label="分钟"
        prop="minute"
      >
        <el-input
          v-model="form.minute"
          placeholder="设置哪一分钟执行本任务"
        />
        <el-tooltip
          class="item"
          effect="dark"
          placement="top-start"
        >
          <div slot="content">
            设置哪一分钟执行本任务，可设置每隔几分钟执行，
            <br>
            例如每5分钟执行则填写：*/5
          </div>
          <span class="smalltip">
            <i class="el-icon-info" />
          </span>
        </el-tooltip>
      </el-form-item>
      <el-form-item
        label="刷新简历条数"
        prop="day"
        v-if="form.action == 'RefreshResume'"
      >
        <el-input v-model.number="form.refresh_quantity" label="刷新简历条数"></el-input>
        <el-tooltip
          class="item"
          effect="dark"
          placement="top-start"
        >
          <div slot="content">
            指计划任务执行时要刷新的简历数量<br>
            简历数越小计划任务频率应越高，刷新<br>数越大计划任务频率应越低
          </div>
          <span class="smalltip">
            <i class="el-icon-info" />
          </span>
        </el-tooltip>
      </el-form-item>
      <el-form-item
        label="可用"
        prop="status"
      >
        <el-switch v-model="form.status" />
      </el-form-item>
      <el-form-item label=" ">
        <el-button
          type="primary"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <el-button @click="closeDialog">
          取 消
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { cronAdd, cronEdit } from '@/api/cron'
var options_days = []
for (let index = 1; index <= 31; index++) {
  const arr = {
    name: index + '日',
    id: index + ''
  }
  options_days.push(arr)
}
var options_hours = []

for (let index = 0; index <= 23; index++) {
  const arr = {
    name: index + '时',
    id: index + ''
  }
  options_hours.push(arr)
}
export default {
  props: ['itemInfo'],
  data() {
    return {
      options_days: options_days,
      options_hours: options_hours,
      form: {
        id: 0,
        name: '',
        action: '',
        weekday: '-1',
        day: '-1',
        hour: '-1',
        minute: '',
        status: true,
        refresh_quantity: 1
      },
      rules: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      if (this.itemInfo !== null) {
        this.itemInfo.status = this.itemInfo.status == 1
        this.form = { ...this.form, ...this.itemInfo }
        this.form.weekday = this.form.weekday + ''
        this.form.day = this.form.day + ''
        this.form.hour = this.form.hour + ''
        this.form.minute = this.form.minute + ''
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.status = insertData.status === true ? 1 : 0
          cronAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.status = insertData.status === true ? 1 : 0
          cronEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      if (parseInt(insertData.id) > 0) {
        that.editSave(insertData, formName)
      } else {
        that.addSave(insertData, formName)
      }
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
.el-select,
.el-input {
  width: 300px;
}
</style>
