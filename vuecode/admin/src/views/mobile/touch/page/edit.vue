<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑页面</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/mobile/touch/page')"
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
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="80px"
        :inline-message="true"
      >
        <el-form-item label="页面名称" prop="name">
          <el-input v-model="form.name" disabled style="width:200px;" />
        </el-form-item>
        <el-form-item label="缓存时长" prop="expire">
          <el-input v-model.number="form.expire" type="number" :disabled="form.enable_cache==0" style="width:200px;">
            <template slot="append">秒</template>
          </el-input>
        </el-form-item>
        <el-form-item label="seo标题" prop="seo_title">
          <el-input id="seo_title" v-model="form.seo_title" /><br>
          <el-tag v-for="(item,index) in form.params" :key="index" type="info" @click="handlerTagToTitle(item.value)">{{ item.name }}</el-tag>
        </el-form-item>
        <el-form-item label="seo关键词" prop="seo_keywords">
          <el-input id="seo_keywords" v-model="form.seo_keywords" /><br>
          <el-tag v-for="(item,index) in form.params" :key="index" type="info" @click="handlerTagToKeywords(item.value)">{{ item.name }}</el-tag>
        </el-form-item>
        <el-form-item label="seo描述" prop="seo_description">
          <el-input id="seo_description" v-model="form.seo_description" type="textarea" rows="4" /><br>
          <el-tag v-for="(item,index) in form.params" :key="index" type="info" @click="handlerTagToDescription(item.value)">{{ item.name }}</el-tag>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/mobile/touch/page')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { pageMobileEdit } from '@/api/page_mobile'

export default {
  data() {
    return {
      form: {
        id: '',
        expire: '',
        seo_title: '',
        seo_keywords: '',
        seo_description: ''
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      pageMobileEdit({ id: this.$route.query.id }, 'get')
        .then(response => {
          this.form = { ...response.data.info }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      pageMobileEdit(insertData)
        .then(response => {
          this.$message.success(response.message)
          setTimeout(function() {
            that.$router.push('/mobile/touch/page')
          }, 1500)
          return true
        })
        .catch(() => {})
    },
    goto(target) {
      this.$router.push(target)
    },
    async insertVariable(domid, value) {
      const inputDom = document.querySelector('#' + domid)
      if (inputDom.selectionStart || inputDom.selectionStart === 0) {
        const startPos = inputDom.selectionStart
        const endPos = inputDom.selectionEnd
        this.form[domid] = inputDom.value.substring(0, startPos) + value +
                    inputDom.value.substring(endPos, inputDom.value.length)
        await this.$nextTick()
        inputDom.focus()
        inputDom.setSelectionRange(endPos + value.length, endPos + value.length)
      } else {
        this.form[domid] = value
      }
    },
    handlerTagToTitle(val){
      val = '{' + val + '}'
      this.insertVariable('seo_title', val)
    },
    handlerTagToKeywords(val){
      val = '{' + val + '}'
      this.insertVariable('seo_keywords', val)
    },
    handlerTagToDescription(val){
      val = '{' + val + '}'
      this.insertVariable('seo_description', val)
    }
  }
}
</script>
<style scoped>
.el-textarea,
.el-input{
  width: 550px;
}
.el-tag{margin-right:10px;cursor:pointer;}
</style>
