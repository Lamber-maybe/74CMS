<template>
  <div class="app-container">
    <div class="tip">
      <p>快速筛选出符合条件的职位进行复制到微信公众号中推送；职位默认按刷新时间排序</p>
      <p>使用公众号二维码，用户需先关注公众号后才能查看，二维码有效期为30天；使用普通二维码，用户无需关注公众号直接识别即可打开触屏链接，二维码永久有效</p>
    </div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>信息导出 - 公众号模板</span>
      </div>
      <el-form
        ref="form"
        class="common-form"
        label-width="120px"
        :inline-message="true"
      >
        <el-form-item label="模板选择">
          <el-radio-group v-model="tpl">
            <el-radio :label="1">网络招聘会模板1</el-radio>
            <el-radio :label="2">网络招聘会模板2</el-radio>
            <el-radio :label="3">网络招聘会模板3</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="链接类型">
          <el-radio-group v-model="link">
            <el-radio :label="'mobile'">移动端二维码</el-radio>
            <el-radio :label="'qrcode'">公众号二维码</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="">
          <component :is="'wx_tpl_' + tpl" :company="list" :link="link" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" class="copy" @click="copy">一键复制</el-button>
          复制后在微信编辑器粘贴内容即可
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { exportCompanyJobsList } from '@/api/jobfairol'
import wx_tpl_1 from './components/wx_tpl_1.vue'
import wx_tpl_2 from './components/wx_tpl_2.vue'
import wx_tpl_3 from './components/wx_tpl_3.vue'

export default {
  components: {
    wx_tpl_1,
    wx_tpl_2,
    wx_tpl_3
  },
  data() {
    return {
      tpl: 1,
      link: 'mobile',
      jobfair_id: '',
      list: [],
      jobfair: {}
    }
  },
  created() {
    this.jobfair_id = this.$route.query.id
    this.getCompany()
  },
  methods: {
    getCompany: function(){
      var t = this
      const param = {
        jobfair_id: this.jobfair_id
      }
      exportCompanyJobsList(param).then(res => {
        t.list = res.data.items
        t.jobfair = res.data.jobfair
        t.copyInit()
      })
    },
    copyInit: function(){
      window.temp = document.getElementById('J_tem_box').innerHTML
      // 创建mousemove监听事件，当检测到有文字被选中，则移除copy，否则则添加copy
      document.addEventListener('mousemove', function () {
        const selObj = window.getSelection()
        const selectedText = selObj.toString()
        if (selectedText != '') {
          // 移除copy监听事件
          document.removeEventListener('copy', myFunction)
        } else {
          // 添加copy监听事件
          document.addEventListener('copy', myFunction)
        }
      })
      function myFunction(e) {
        e.preventDefault()
        e.clipboardData.setData('text/html', window.temp)
      }
    },
    copy: function() {
      window.temp = document.getElementById('J_tem_box').innerHTML
      document.execCommand('copy')
      alert('内容已复制，请到微信公众平台粘贴')
    }
  }
}
</script>
