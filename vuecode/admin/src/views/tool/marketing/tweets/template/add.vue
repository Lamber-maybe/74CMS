<template>
  <el-form
    ref="form"
    :inline-message="true"
    :model="form"
    :rules="rules"
    class="common-form"
    label-width="100px"
  >
    <el-form-item label="模板名称" prop="temname">
      <el-input id="form_temname" v-model="form.temname" />
    </el-form-item>

    <el-form-item label="头部内容" prop="title">
      <el-input id="form_title" v-model="form.title" rows="2" type="textarea" />
      <div class="clearfix" />
      <el-tag
        v-for="(item,index) in params_arr"
        :key="index"
        class="param_tag"
        size="small"
        @click="handlerTag('title',item)"
      >
        {{ item.name }}
      </el-tag>
    </el-form-item>

    <el-form-item label="主体内容" prop="content">
      <el-input id="form_content" v-model="form.content" rows="10" type="textarea" />

      <div class="clearfix" />
      <el-tag
        v-for="(item,index) in content_params_arr"
        :key="index"
        class="param_tag"
        size="small"
        @click="handlerTag('content',item)"
      >
        {{ item.name }}
      </el-tag>
    </el-form-item>
    <el-form-item label="尾部内容" prop="footer">
      <el-input id="form_footer" v-model="form.footer" rows="2" type="textarea" />
      <div class="clearfix" />
      <el-tag
        v-for="(item,index) in params_arr"
        :key="index"
        class="param_tag"
        size="small"
        @click="handlerTag('footer',item)"
      >
        {{ item.name }}
      </el-tag>
    </el-form-item>

    <el-form-item label=" ">
      <el-button
        type="primary"
        @click="onSubmit('form')"
      >
        确定
      </el-button>
      <el-button
        @click="close()"
      >
        取消
      </el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { tweetslabelList } from '@/api/tweets_label'
import { tweetsTemplateAdd } from '@/api/tweets_template'
export default {
  props: ['dialogAddFormVisible'],
  data() {
    return {
      form: {
        temname: '',
        title: '',
        content: '',
        footer: ''
      },
      params_arr: [],
      content_params_arr: [],
      rules: {
        temname: [
          { required: true, message: '请输入模板名称', trigger: 'blur' }
        ],
        title: [
          { required: false, message: '请输入头部内容', trigger: 'blur' }
        ],
        content: [
          { required: true, message: '请输入主体内容', trigger: 'blur' }
        ],
        footer: [
          { required: false, message: '请输入尾部内容', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData(){
      this.listLoading = true

      tweetslabelList({}, 'get')
        .then(response => {
          this.params_arr = response.data.items.footer
          this.content_params_arr = response.data.items.content
        })
        .catch(() => {})
    },
    closeDialog() {
      this.$emit('closeAddDia')
    },
    goto(target) {
      this.$router.push(target)
    },
    handlerTag(source, item) {
      const insertTxt = ' #' + item.value + '# '
      var elInput = document.getElementById('form_' + source)
      var startPos = elInput.selectionStart
      var endPos = elInput.selectionEnd
      if (startPos === undefined || endPos === undefined) return
      var txt = elInput.value
      var result = txt.substring(0, startPos) + insertTxt + txt.substring(endPos)
      elInput.value = result
      elInput.focus()
      elInput.selectionStart = startPos + insertTxt.length
      elInput.selectionEnd = startPos + insertTxt.length
      this.form[source] = result
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          tweetsTemplateAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.dialogFormVisible = false
              this.closeDialog()
              this.close()
              this.fetchData()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    pageReload() {
      this.$emit('pageReload')
    },
    close() {
      this.$emit('closeAddDia')
    }
  }
}
</script>
<style scoped>
.param_tag{
  margin-right: 10px;
  cursor: pointer;
}
</style>
