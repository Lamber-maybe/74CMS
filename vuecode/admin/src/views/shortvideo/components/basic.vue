<template>
  <div class="app-container">
    <div class="spaceline"/>
    <el-divider content-position="left">基础配置</el-divider>
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="200px"
      :inline-message="true"
    >
      <el-form-item label="视频功能启用">
        <el-radio-group v-model.number="form.shortvideo_enable">
          <el-radio :label="1">开启</el-radio>
          <el-radio :label="0">关闭</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="视频招聘浏览量随机值">
        <el-input v-model.number="form.shortvideo_jobing_view_init" class="small" style="width:200px;"/>
        <span class="smalltip">
          <i class="el-icon-info"/>浏览量在“1~设置值”之间随机增加，0为不干预
        </span>
      </el-form-item>
      <el-form-item label="视频求职浏览量随机值">
        <el-input style="width:200px;" v-model.number="form.shortvideo_finding_view_init" class="small"/>
        <span class="smalltip">
          <i class="el-icon-info"/>浏览量在“1~设置值”之间随机增加，0为不干预
        </span>
      </el-form-item>
      <el-form-item label="视频招聘浏览量热门值">
        <el-input style="width:200px;" v-model.number="form.shortvideo_jobing_hot" class="small"/>
      </el-form-item>
      <el-form-item label="视频求职浏览量热门值">
        <el-input style="width:200px;" v-model.number="form.shortvideo_finding_hot" class="small"/>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form', 0)">保存</el-button>
      </el-form-item>
      <el-divider content-position="left">企业发布配置</el-divider>

      <el-form-item label="允许企业套餐发布">
        <el-checkbox-group v-model="form.shortvideo_enable_setmeal">
          <el-checkbox v-for="v in setMealList" :key="v.id" :label="v.id"><span v-text="v.name"></span></el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form', 1)">保存</el-button>
      </el-form-item>
      <el-divider content-position="left">审核配置</el-divider>
      <el-form-item label="新发布视频招聘默认审核状态">
        <el-radio-group v-model="form.shortvideo_new_jobing_audit">
          <el-radio :label="1">未审核</el-radio>
          <el-radio :label="2">已审核</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="修改视频招聘后默认审核状态">
        <el-radio-group v-model="form.shortvideo_edited_jobing_audit">
          <el-radio :label="0">保持不变</el-radio>
          <el-radio :label="1">未审核</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="新发布视频简历默认审核状态">
        <el-radio-group v-model="form.shortvideo_new_finding_audit">
          <el-radio :label="1">未审核</el-radio>
          <el-radio :label="2">已审核</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="修改视频简历后默认审核状态">
        <el-radio-group v-model="form.shortvideo_edited_finding_audit">
          <el-radio :label="0">保持不变</el-radio>
          <el-radio :label="1">未审核</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form', 2)">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {setConfig} from '@/api/configuration'
import {setmealList} from '@/api/setmeal'
import {checkQiniu} from '@/api/upload'

export default {
  data() {
    return {
      infoLoading: true,
      setMealList: [],
      form: {
        shortvideo_enable: 0,
        shortvideo_jobing_view_init: 0,
        shortvideo_finding_view_init: 0,
        shortvideo_jobing_hot: 0,
        shortvideo_finding_hot: 0,
        shortvideo_enable_setmeal: [],
        shortvideo_new_jobing_audit: 1,
        shortvideo_edited_jobing_audit: 0,
        shortvideo_new_finding_audit: 1,
        shortvideo_edited_finding_audit: 0
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      let that = this
      this.infoLoading = true
      const param = {}
      setmealList(param)
        .then(response => {
          that.setMealList = response.data.items
        })
      setConfig(param, 'get')
        .then(response => {
          let res = response.data
          that.form = {
            shortvideo_enable: parseInt(res.shortvideo_enable),
            shortvideo_jobing_view_init: res.shortvideo_jobing_view_init,
            shortvideo_finding_view_init: res.shortvideo_finding_view_init,
            shortvideo_jobing_hot: res.shortvideo_jobing_hot,
            shortvideo_finding_hot: res.shortvideo_finding_hot,
            shortvideo_enable_setmeal: Array.isArray(res.shortvideo_enable_setmeal)?res.shortvideo_enable_setmeal.map(function(v){return parseInt(v)}):[] ,
            shortvideo_new_jobing_audit: parseInt(res.shortvideo_new_jobing_audit),
            shortvideo_edited_jobing_audit: parseInt(res.shortvideo_edited_jobing_audit),
            shortvideo_new_finding_audit: parseInt(res.shortvideo_new_finding_audit),
            shortvideo_edited_finding_audit: parseInt(res.shortvideo_edited_finding_audit)
          }
          that.infoLoading = false
        })
        .catch(() => {
        })
    },
    async onSubmit(formName, id) {
      let insertData = {...this.form}
      if (id == 0) {
        delete insertData.shortvideo_enable_setmeal
        delete insertData.shortvideo_new_jobing_audit
        delete insertData.shortvideo_edited_jobing_audit
        delete insertData.shortvideo_new_finding_audit
        delete insertData.shortvideo_edited_finding_audit
        if(parseInt(insertData.shortvideo_enable)>0){
          let res = await checkQiniu()
          if(res.code == 200){
            console.log(res, 'checkQiniu')
          } else{
            this.$message.error('七牛云配置有误,请确认')
            return false
          }
        }
      } else if (id == 1) {
        insertData = {shortvideo_enable_setmeal: this.form.shortvideo_enable_setmeal}
      } else if (id == 2) {
        insertData = {
          shortvideo_new_jobing_audit: this.form.shortvideo_new_jobing_audit,
          shortvideo_edited_jobing_audit: this.form.shortvideo_edited_jobing_audit,
          shortvideo_new_finding_audit: this.form.shortvideo_new_finding_audit,
          shortvideo_edited_finding_audit: this.form.shortvideo_edited_finding_audit
        }
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {
            })
        } else {
          return false
        }
      })
    }
  }
}
</script>

