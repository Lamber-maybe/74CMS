<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span  style="font-weight: 600;color: #333;">自我描述</span>
        <el-button
          v-if="is_edit === false"
          style="float: right; padding: 6px 18px;font-size: 13px;"
          type="primary"
          round
          @click="is_edit = !is_edit"
        >
          修改
        </el-button>
      </div>
      <div v-if="is_edit === false" class="specialty">
        {{ form.specialty == '' ? '未填写' : form.specialty }}
      </div>
      <el-form
        v-if="is_edit"
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="自我描述" prop="specialty">
          <el-input
            v-model="form.specialty"
            type="textarea"
            rows="10"
          />
        </el-form-item>
        <el-form-item label>
          <el-button
            type="primary"
            :loading="submitLoading"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
          <el-button type="info" plain @click="is_edit = !is_edit">
            取消
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { resumeSpecialty } from '@/api/resume'

export default {
  props: ['id'],
  data() {
    return {
      is_edit: false,
      submitLoading: false,
      infoLoading: true,
      form: {
        specialty: ''
      },
      rules: {
        specialty: [
          {
            required: true,
            message: '请输入自我描述',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {
        id: this.id
      }
      resumeSpecialty(param, 'get')
        .then(response => {
          this.form = { ...response.data.info }
          this.infoLoading = false
          this.$emit('setLoading', 'resumeSpecialty')
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          resumeSpecialty(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.is_edit = false
              this.submitLoading = false
              this.fetchInfo()
              return true
            })
            .catch(() => {})
        } else {
          this.submitLoading = false
          return false
        }
      })
    }
  }
}
</script>
<style scoped>
.specialty {
  font-size: 14px;
  max-width: 80%;
}
</style>
