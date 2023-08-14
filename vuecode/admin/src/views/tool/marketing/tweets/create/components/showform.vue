<template>
  <div>
    <div class="divleft">
      <el-form class="common-form" label-width="100px">
        <el-form-item label="选择模板" style="margin-bottom:20px;" prop="temname">
          <el-select v-model="temname" placeholder="不限" class="large">
            <el-option v-for="item in options_temtype" :key="item.id" :label="item.temname"	:value="item.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="职位" style="margin-bottom:20px;">
          <div
            id="htmlcontents"
            v-loading="loading"
            class="html-block"
            element-loading-text="正在搜索符合条件的职位..."
          >
            <div v-show="!loading">
              <el-tag
                v-for="(item,index) in datalist"
                :key="index"
                closable
                :disable-transitions="false"
                style="margin:5px;float:left;"
                @close="delJobs(item)"
              >
                {{ item.jobname }}
              </el-tag>

            </div>
          </div>
        </el-form-item>
        <el-form-item label="">
          <el-button
            type="primary"
            @click="onCreate('form')"
          >
            生成
          </el-button>
          <el-button
            @click="loading=true;$emit('goback')"
          >
            返回
          </el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="divright">
      <div style="width: 65px;margin-left: 50px;line-height: 50px;font-size:16px;font-weight:bold;color:#666;">推文预览</div>
      <el-form class="common-form" label-width="50px">
        <el-form-item label="">
          <div
            id="htmlcontent"
            v-loading="loadings"
            class="html-block-r"
            element-loading-text="正在生成模板..."
          >
            <components :is="tplComponents" v-if="showtem" :datalist="datalist" :item-info-id="itemInfoId" :params-arr="params_arr" :content-params-arr="content_params_arr" :title-footer="title_footer" @copyhtml="copyhtmlFun" />
          </div>
        </el-form-item>

        <el-form-item label="">
          <el-button
            type="primary"
            @click="copy"
          >
            一键复制
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
import { tweetsTemplateList, tweetsTitleFooter } from '@/api/tweets_template'
import { tweetslabelList } from '@/api/tweets_label'
import jobresult from './tpl/jobresult.vue'
export default {
  components: {
    jobresult
  },
  props: ['datalist'],
  data() {
    return {
      loading: true,
      loadings: false,
      showtem: false,
      temname: 1,
      html: '',
      tplComponents: '',
      options_temtype: [],
      params_arr: [],
      content_params_arr: [],
      title_footer: [],
      itemInfoId: ''
    }
  },
  watch: {
    datalist: function (newVal, oldVal) {
      if (newVal !== undefined){
        this.loading = false
      }
    },
    temname: function (newVal, oldVal) {
      if (newVal !== undefined){
        this.itemInfoId = this.temname
        this.showtem = false
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    copyhtmlFun(html){
      this.html = html
    },
    delJobs(item) {
      const index = this.datalist.indexOf(item)
      if (index != -1){
        this.datalist.splice(index, 1)
      }
    },
    onCreate() {
      if (this.temname == ''){
        this.$message.error('请选择模板')
      } else {
        this.showtem = false
        this.showtem = true
        this.itemInfoId = this.temname
        this.tplComponents = ''
        this.tplComponents = 'jobresult'
      }
    },
    fetchInfo() {
      const that = this
      tweetsTemplateList({}).then(response => {
        that.options_temtype = response.data.items
      })
        .catch(() => {})
      tweetslabelList({}, 'get')
        .then(response => {
          that.params_arr = response.data.items.footer
          that.content_params_arr = response.data.items.content
        })
        .catch(() => {})
      tweetsTitleFooter({}, 'get')
        .then(response => {
          that.title_footer = response.data
        })
        .catch(() => {})
    },
    copy () {
      const output = this.html
      const copyHandler = this.copys(output)
      document.addEventListener('copy', copyHandler)
      document.execCommand('copy')
      removeEventListener('copy', copyHandler)
      this.$message({ type: 'success', message: '复制成功' })
    },
    copys(content) {
      return function(event) {
        event.clipboardData.setData('text/plain', content)
        event.preventDefault()
      }
    }
  }
}
</script>
<style scoped>
.divleft{
  width: 49%;
  float: left;
}
.html-block{
  width:500px;
  min-height:100px;
  margin-bottom: 100px;
  text-align: center;
}
.divright{
  width: 50%;
  float: left;
  height:700px;
  border-left:1px solid #dcdfe6;
}
.divright .html-block-r{
  width:500px;
  height:450px;
}
</style>
