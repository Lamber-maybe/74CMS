<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/flink/list')"
        >
          返回
        </el-button>
        <el-button
          style="float: right; padding: 0;"
          type="text"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="80px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="名称" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="网址" prop="link_url">
          <el-input v-model="form.link_url" />
        </el-form-item>
        <el-form-item label="是否显示">
          <el-switch v-model="form.is_display" />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" />
        </el-form-item>
        <el-form-item label="备注" prop="notes">
          <el-input v-model="form.notes" type="textarea" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/flink/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { validUrl } from '@/utils/validate'
import { flinkAdd } from '@/api/flink'

export default {
  data() {
    var validateUrl = (rule, value, callback) => {
      if (value == '') {
        callback()
      }
      if (!validUrl(value)) {
        callback(new Error('请输入正确的网址'))
      } else {
        callback()
      }
    }
    return {
      form: {
        name: '',
        notes: '',
        is_display: true,
        link_url: '',
        sort_id: 0
      },
      rules: {
        name: [
          {
            required: true,
            message: '请输入名称',
            trigger: 'blur'
          },
          {
            max: 100,
            message: '长度在 1 到 100 个字符',
            trigger: 'blur'
          }
        ],
        sort_id: [
          {
            type: 'number',
            message: '排序必须为数字',
            trigger: 'blur'
          }
        ],
        link_url: [
          {
            required: true,
            message: '请输入网址',
            trigger: 'blur'
          },
          {
            max: 200,
            message: '长度在 0 到 200 个字符',
            trigger: 'blur'
          },
          { validator: validateUrl, trigger: 'blur' }
        ]
      }
    }
  },
  created() {
  },
  methods: {
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_display = insertData.is_display === true ? 1 : 0
          flinkAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/flink/list')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
          // console.log('error submit!!')
          return false
        }
      })
    },
    goto(target) {
      this.$router.push(target)
    }
  }
}
</script>
<style scoped>
.el-textarea,
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
}
</style>
