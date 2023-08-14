<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加广告位</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/ad/category')"
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
        label-width="100px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="所属平台" prop="platform">
          <el-select v-model="form.platform" placeholder="请选择所属平台">
            <el-option
              v-for="(item, index) in options_platform"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="广告位名称" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="调用名称" prop="alias">
          <el-input v-model="form.alias" />
          <span class="smalltip">
            <i class="el-icon-info" />
            自定义广告位调用名不可以以 “QS_”开头
          </span>
        </el-form-item>
        <el-form-item label="数量" prop="ad_num">
          <el-input v-model.number="form.ad_num" />
        </el-form-item>
        <el-form-item label="建议宽度" prop="width">
          <el-input v-model.number="form.width" />
        </el-form-item>
        <el-form-item label="建议高度" prop="height">
          <el-input v-model.number="form.height" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/ad/category')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { categoryAdd } from '@/api/ad_category'

export default {
  data() {
    var validateAlias = (rule, value, callback) => {
      if (value == '') {
        callback()
      }
      if (value.startsWith('QS_')) {
        callback(new Error('自定义广告位调用名不可以以 “QS_”开头'))
      } else {
        callback()
      }
    }
    return {
      options_platform: [],
      form: {
        platform: '',
        name: '',
        alias: '',
        ad_num: '',
        width: '',
        height: ''
      },
      rules: {
        platform: [
          { required: true, message: '请选择所属平台', trigger: 'change' }
        ],
        name: [
          { required: true, message: '请输入广告位名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        alias: [
          { required: true, message: '请输入调用名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' },
          { validator: validateAlias, trigger: 'blur' }
        ],
        ad_num: [
          { required: true, message: '请输入数量', trigger: 'blur' },
          { type: 'number', message: '数量必须为数字', trigger: 'blur' }
        ],
        width: [
          { required: true, message: '请输入建议宽度', trigger: 'blur' },
          { type: 'number', message: '建议宽度必须为数字', trigger: 'blur' }
        ],
        height: [
          { required: true, message: '请输入建议高度', trigger: 'blur' },
          { type: 'number', message: '建议高度必须为数字', trigger: 'blur' }
        ]
      }
    }
  },
  mounted() {},
  created() {
    this.fetchCategoryData()
  },
  methods: {
    fetchCategoryData() {
      const param = { type: 'adPlatform' }
      getClassify(param)
        .then(response => {
          this.options_platform = response.data
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          categoryAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/ad/category')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
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
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
}
</style>
