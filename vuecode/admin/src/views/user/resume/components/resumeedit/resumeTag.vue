<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>特长标签</span>
        <el-button
          v-if="is_edit === false"
          style="float: right; padding: 3px 0"
          type="text"
          @click="is_edit = !is_edit"
        >
          修改
        </el-button>
      </div>
      <div v-if="is_edit === false" class="tag">
        {{ form.tag_ == '' ? '未填写' : form.tag_ }}
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
        <el-form-item label="特长标签" prop="tag">
          <el-select
            v-model="form.tag"
            multiple
            filterable
            allow-create
            default-first-option
            placeholder="请选择特长标签"
            :multiple-limit="6"
          >
            <el-option
              v-for="(item, index) in options_tag"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
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
import { getClassify } from '@/api/classify'
import { resumeTag } from '@/api/resume'

export default {
  data() {
    return {
      is_edit: false,
      submitLoading: false,
      infoLoading: true,
      options_tag: [],
      form: {
        tag: []
      },
      rules: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {
        id: this.$route.query.id
      }

      resumeTag(param, 'get')
        .then(response => {
          this.form = { ...response.data.info }

          const tagIds = this.form.tag.toString()
          const arrData = tagIds.split(',')
          for (var i = 0; i < arrData.length; i++) {
            if (!isNaN(arrData[i])) {
              arrData[i] = parseInt(arrData[i])
            }
          }
          this.form.tag = arrData
          return getClassify({ type: 'resumeTag' })
        })
        .then(response => {
          this.options_tag = [...response.data]
          this.infoLoading = false
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
          resumeTag(insertData)
            .then(response => {
              this.is_edit = false
              this.$message.success(response.message)
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
.el-select {
  width: 550px;
}
.tag {
  font-size: 14px;
}
</style>
