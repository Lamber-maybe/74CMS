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
      <el-form-item label="选择简历" prop="resume_id">
        <el-select
          v-model="form.resume_id"
          filterable
          remote
          reserve-keyword
          placeholder="请输入简历ID/姓名/会员手机号"
          :remote-method="resumeSearch"
          :loading="loading"
        >
          <el-option
            v-for="item in options_resumelist"
            :key="item.id"
            :label="item.fullname"
            :value="item.id"
          >
            <span style="float: left">{{ item.fullname }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">
              ID:{{ item.id }}
            </span>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="推广天数" prop="days">
        <el-input v-model.number="form.days" type="number" />
      </el-form-item>
      <el-form-item label="推广方案" prop="type">
        <el-select v-model="form.type" placeholder="请选择">
          <el-option label="置顶" value="stick" />
          <el-option label="醒目标签" value="tag" />
        </el-select>
      </el-form-item>
      <el-form-item v-if="form.type == 'tag'" label="标签" prop="tag">
        <el-input v-model="form.tag" />
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {
  resumePromotionSearch,
  resumePromotionAdd
} from '@/api/promotion_resume'

export default {
  data() {
    return {
      loading: false,
      options_resumelist: [],
      form: {
        resume_id: '',
        days: '',
        type: '',
        tag: ''
      },
      rules: {
        resume_id: [
          {
            required: true,
            message: '请选择简历',
            trigger: 'change'
          }
        ],
        days: [
          {
            required: true,
            message: '请填写推广天数',
            trigger: 'blur'
          }
        ],
        type: [
          {
            required: true,
            message: '请选择推广方案',
            trigger: 'change'
          }
        ]
      }
    }
  },
  created() {},
  methods: {
    onSubmit(formName) {
      const that = this
      that.$refs[formName].validate(valid => {
        if (valid) {
          const insertData = {
            pid: that.form.resume_id,
            days: that.form.days,
            type: that.form.type,
            tag: that.form.tag
          }
          resumePromotionAdd(insertData)
            .then(response => {
              that.$message.success(response.message)
              that.closeDialog()
              that.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    resumeSearch(query) {
      if (query !== '') {
        this.loading = true
        resumePromotionSearch({ keyword: query })
          .then(response => {
            this.options_resumelist = response.data.items
            this.loading = false
          })
          .catch(() => {})
      } else {
        this.options = []
      }
    },
    closeDialog() {
      this.$emit('setDialogVisible')
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
