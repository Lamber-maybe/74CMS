<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑分站</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/subsite/index')"
        >
          返回
        </el-button>
        <el-button
          style="float: right; padding: 0;"
          type="text"
          :disabled="issubmit"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </div>
      <el-form
        ref="form"
        v-loading="loading"
        class="common-form"
        :model="form"
        label-width="120px"
        :inline-message="true"
        :rules="rules"
      >
        <el-form-item label="分站名称" prop="sitename">
          <el-input v-model="form.sitename" />
        </el-form-item>
<!--        <el-form-item label="分站形式" prop="url_type">-->
<!--          <el-radio-group v-model="form.url_type">-->
<!--            <el-radio :label="1">二级域名</el-radio>-->
<!--            <el-radio :label="2">首页目录</el-radio>-->
<!--          </el-radio-group>-->
<!--        </el-form-item>-->
        <el-form-item
          v-if="form.url_type=='1'"
          label="绑定域名"
          prop="second_domain"
        >
          <el-input v-model="form.second_domain" />
          <br>
          <span class="smalltip">
            <i class="el-icon-info" />
            如：https://taiyuan.tywangcai.com（结尾不要加“/”）
          </span>
        </el-form-item>
<!--        <el-form-item-->
<!--          v-if="form.url_type=='2'"-->
<!--          label="分站目录"-->
<!--          prop="directory"-->
<!--        >-->
<!--          <el-input v-model="form.directory" />-->
<!--          <span class="smalltip">-->
<!--            <i class="el-icon-info" />-->
<!--            如：taiyuan(自定义拼音目录)-->
<!--          </span>-->
<!--        </el-form-item>-->
        <el-form-item label="绑定地区" prop="citycategory_arr">
          <el-cascader
            v-model="form.citycategory_arr"
            filterable
            :props="{ checkStrictly: true }"
            popper-class="cancelRadio"
            :options="options_citycategory"
            :show-all-levels="false"
          />
        </el-form-item>
<!--        <el-form-item label="seo标题" prop="title">-->
<!--          <el-input v-model="form.title" />-->
<!--        </el-form-item>-->
<!--        <el-form-item label="seo关键词" prop="keywords">-->
<!--          <el-input v-model="form.keywords" />-->
<!--        </el-form-item>-->
<!--        <el-form-item label="seo描述" prop="description">-->
<!--          <el-input v-model="form.description" type="textarea" />-->
<!--        </el-form-item>-->
        <el-form-item label="模板" prop="tpl">
          <el-select v-model="form.tpl" placeholder="请选择模板">
            <el-option
              v-for="(item, index) in options_tpl"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="是否显示" prop="is_display">
          <el-radio-group v-model="form.is_display">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model="form.sort_id" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" :disabled="issubmit" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/subsite/index')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { subsiteEdit } from '@/api/subsite'
import { getClassify } from '@/api/classify'

export default {
  data() {
    return {
      issubmit: false,
      loading: true,
      form: {
        sitename: '',
        district1: 0,
        district2: 0,
        district3: 0,
        citycategory_arr: [],
        tpl: '',
        title: '',
        keywords: '',
        description: '',
        is_display: 1,
        sort_id: 0,
        url_type: 1,
        second_domain: '',
        directory: ''
      },
      options_citycategory: [],
      options_tpl: [],
      rules: {
        sitename: [
          {
            required: true,
            message: '请输入分站名称',
            trigger: 'blur'
          }
        ],
        url_type: [
          {
            required: true,
            message: '请选择分站形式',
            trigger: 'change'
          }
        ],
        second_domain: [
          {
            required: true,
            message: '请输入分站绑定域名',
            trigger: 'blur'
          }
        ],
        citycategory_arr: [
          {
            required: true,
            message: '请选择绑定地区',
            trigger: 'change'
          }
        ],
        tpl: [
          {
            required: true,
            message: '请选择模板',
            trigger: 'change'
          }
        ]
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData(){
      this.loading = true
      getClassify({
        type:
              'citycategory,tplOfIndex'
      })
        .then(response => {
          this.options_tpl = [...response.data.tplOfIndex]
          this.options_citycategory = [...response.data.citycategory]
          this.options_citycategory = this.options_citycategory.map((item) => {
            if (item.children.length) {
              let level2Array = item.children
              level2Array = level2Array.map((level2Item) => {
                if (level2Item.children.length) {
                  return { label: level2Item.label, value: level2Item.value, children: level2Item.children }
                } else {
                  return { label: level2Item.label, value: level2Item.value }
                }
              })
              return { value: item.value, label: item.label, children: level2Array }
            } else {
              return { value: item.value, label: item.label }
            }
          })
          return subsiteEdit({ id: this.$route.query.id }, 'get')
        })
        .then(response => {
          this.form = { ...response.data.info }
          this.form.citycategory_arr = []
          if (response.data.info.district1 != 0) {
            this.form.citycategory_arr.push(response.data.info.district1)
          }
          if (response.data.info.district2 != 0) {
            this.form.citycategory_arr.push(response.data.info.district2)
          }
          if (response.data.info.district3 != 0) {
            this.form.citycategory_arr.push(response.data.info.district3)
          }
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    onSubmit(formName) {
      const that = this
      that.issubmit = true
      this.$refs[formName].validate(valid => {
        if (valid) {
          const submitData = JSON.parse(JSON.stringify(this.form))
          const tmp_citycategory_arr = submitData.citycategory_arr
          submitData.district1 = tmp_citycategory_arr[0]
          submitData.district2 =
            tmp_citycategory_arr[1] !== undefined ? tmp_citycategory_arr[1] : 0
          submitData.district3 =
            tmp_citycategory_arr[2] !== undefined ? tmp_citycategory_arr[2] : 0
          subsiteEdit(submitData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/subsite/index')
              }, 1500)
              return true
            })
            .catch(() => {
              that.issubmit = false
              return false
            })
        } else {
          that.issubmit = false
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
.el-input,.el-textarea{
  max-width:500px;
}
</style>
