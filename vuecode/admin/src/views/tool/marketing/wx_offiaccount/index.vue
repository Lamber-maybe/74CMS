<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>公众号营销</span>
      </div>
      <div class="tip">
        <p>快速筛选出符合条件的职位进行复制到微信公众号中推送；</p>
        <p>职位默认按刷新时间排序；</p>
        <p>使用公众号二维码，用户需先关注公众号后才能查看，二维码有效期为30天；</p>
        <p>使用普通二维码，用户无需关注公众号直接识别即可打开触屏链接，二维码永久有效</p>
      </div>
      <el-form v-show="chooseDone===false" class="common-form" label-width="100px">
        <el-form-item label="信息类型" style="margin-bottom:20px;">
          <el-radio-group v-model="data.type">
            <el-radio label="joblist">
              职位列表
            </el-radio>
            <el-radio label="companylist">
              企业列表
            </el-radio>
            <el-radio label="resumelist">
              简历列表
            </el-radio>
            <el-radio label="companyshow">
              单企业推广
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <components :is="data.type" ref="conditionData" />
        <el-form-item label="">
          <el-button
            type="primary"
            :loading="submiting"
            @click="onSubmit('form')"
          >
            生成
          </el-button>
        </el-form-item>
      </el-form>
      <showform v-show="chooseDone===true" :datalist="datalist" :type="data.type" @goback="chooseDone=false" />
    </el-card>
  </div>
</template>

<script>
import joblist from './components/joblist.vue'
import companylist from './components/companylist.vue'
import resumelist from './components/resumelist.vue'
import companyshow from './components/companyshow.vue'
import showform from './components/showform.vue'
import { marketingWxoffiaccount } from '@/api/marketing_wxoffiaccount'

export default {
  components: {
    joblist,
    companylist,
    resumelist,
    companyshow,
    showform
  },
  data() {
    return {
      chooseDone: false,
      submiting: false,
      data: {
        type: 'joblist',
        condition: null
      },
      datalist: []
    }
  },
  created() {},
  methods: {
    onSubmit(formName) {
      const that = this
      if (that.data.type == 'companyshow' && (that.$refs.conditionData.form.company_id === undefined || that.$refs.conditionData.form.company_id === '')){
        that.$message.error('请选择企业')
        that.submiting = false
        return false
      }
      that.chooseDone = true
      if (that.submiting === true) {
        return false
      }
      that.submiting = true
      that.data.condition = { ...that.$refs.conditionData.form }
      const insertData = { ...that.data }
      marketingWxoffiaccount(insertData)
        .then(response => {
          that.submiting = false
          that.datalist = response.data
          return true
        })
        .catch(() => {
          that.submiting = false
        })
    }
  }
}
</script>
