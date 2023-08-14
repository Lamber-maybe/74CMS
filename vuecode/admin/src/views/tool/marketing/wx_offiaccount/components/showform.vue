<template>
  <div>
    <el-form class="common-form" label-width="100px">
      <el-form-item label="模板选择">
        <el-radio-group v-model="tpl" @change="changeTpl">
          <el-radio v-for="(item,index) in options_tpl" :key="index" :label="item.index">
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="链接类型">
        <el-radio-group v-model="link">
          <el-radio v-for="(item,index) in options_link" :key="index" :label="item.value">
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="" style="margin-bottom:20px;">
        <div
          id="htmlcontent"
          v-loading="loading"
          class="html-block"
          element-loading-text="正在生成模板..."
        >
          <components :is="tplComponents" :datalist="datalist" :link="link" />
        </div>
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          @click="copy"
        >
          一键复制（复制后在微信编辑器粘贴内容即可）
        </el-button>
        <el-button
          @click="loading=true;$emit('goback')"
        >
          返回
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
var tplData = {
  joblist: [
    { index: 1, name: '职位列表1' },
    { index: 2, name: '职位列表2' },
    { index: 3, name: '职位列表3' },
    { index: 4, name: '职位列表4' },
    { index: 5, name: '职位列表5' }
  ],
  resumelist: [
    { index: 1, name: '简历列表1' },
    { index: 2, name: '简历列表2' },
    { index: 3, name: '简历列表3' },
    { index: 4, name: '简历列表4' },
    { index: 5, name: '简历列表5' }
  ],
  companylist: [
    { index: 1, name: '企业列表1' },
    { index: 2, name: '企业列表2' },
    { index: 3, name: '企业列表3' },
    { index: 4, name: '企业列表4' },
    { index: 5, name: '企业列表5' }
  ],
  companyshow: [
    { index: 1, name: '单企业推广1' },
    { index: 2, name: '单企业推广2' },
    { index: 3, name: '单企业推广3' },
    { index: 4, name: '单企业推广4' },
    { index: 5, name: '单企业推广5' }
  ]
}
import joblist1 from './tpl/joblist1.vue'
import joblist2 from './tpl/joblist2.vue'
import joblist3 from './tpl/joblist3.vue'
import joblist4 from './tpl/joblist4.vue'
import joblist5 from './tpl/joblist5.vue'
import companylist1 from './tpl/companylist1.vue'
import companylist2 from './tpl/companylist2.vue'
import companylist3 from './tpl/companylist3.vue'
import companylist4 from './tpl/companylist4.vue'
import companylist5 from './tpl/companylist5.vue'
import resumelist1 from './tpl/resumelist1.vue'
import resumelist2 from './tpl/resumelist2.vue'
import resumelist3 from './tpl/resumelist3.vue'
import resumelist4 from './tpl/resumelist4.vue'
import resumelist5 from './tpl/resumelist5.vue'
import companyshow1 from './tpl/joblist1.vue'
import companyshow2 from './tpl/joblist2.vue'
import companyshow3 from './tpl/joblist3.vue'
import companyshow4 from './tpl/joblist4.vue'
import companyshow5 from './tpl/joblist5.vue'
export default {
  components: {
    joblist1,
    joblist2,
    joblist3,
    joblist4,
    joblist5,
    companylist1,
    companylist2,
    companylist3,
    companylist4,
    companylist5,
    resumelist1,
    resumelist2,
    resumelist3,
    resumelist4,
    resumelist5,
    companyshow1,
    companyshow2,
    companyshow3,
    companyshow4,
    companyshow5
  },
  props: ['datalist', 'type'],
  data() {
    return {
      loading: true,
      tplComponents: 'joblist1',
      tpl: 1,
      link: 'normal',
      options_tpl: [],
      options_link: [
        { value: 'normal', name: '普通二维码' },
        { value: 'weixin', name: '公众号二维码' },
        { value: 'mobile', name: '移动端链接' },
        { value: 'miniprogram', name: '小程序链接' }
      ]
    }
  },
  watch: {
    type: function (newVal, oldVal) {
      this.options_tpl = tplData[newVal]
      this.tplComponents = this.type + '1'
    },
    datalist: function (newVal, oldVal) {
      if (newVal !== undefined){
        this.loading = false
      }
    }
  },
  created() {
    this.options_tpl = tplData[this.type]
  },
  methods: {
    changeTpl(val){
      this.tplComponents = this.type + val
    },
    copy () {
      const output = document.getElementById('htmlcontent').innerHTML
      const copyHandler = this.copys(output)
      document.addEventListener('copy', copyHandler)
      document.execCommand('copy')
      removeEventListener('copy', copyHandler)
      this.$message({ type: 'success', message: '复制成功' })
    },
    copys(content) {
      return function(event) {
        event.clipboardData.setData('text/html', content)
        event.preventDefault()
      }
    }
  }
}
</script>
<style scoped>
.html-block{
  border:1px solid #f3f3f3;
  height:500px;
  width:500px;
  overflow-y:scroll;
  padding:20px;
}
</style>
