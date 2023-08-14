<template>
  <div class="app-container">
    <el-tabs>
      <div class="box">
        <div class="button">
          <div @click="switchTemplate('jobshow')" :class="type=='jobshow' ? 'selectButton' : ''">职位详情</div>
          <div @click="switchTemplate('companyshow')" :class="type=='companyshow' ? 'selectButton' : ''">企业详情</div>
          <div @click="switchTemplate('resumeshow')" :class="type=='resumeshow' ? 'selectButton' : ''">简历详情</div>
        </div>
        <div class="right">
          <tplindex  v-if="type=='index'"/>
          <job_show_tpl v-if="type=='jobshow'"/>
          <company_show_tpl v-if="type=='companyshow'"/>
          <resume_show_tpl v-if="type=='resumeshow'"/>
        </div>
      </div>
    </el-tabs>
  </div>
</template>

<script>
import job_show_tpl from './components/mobile_job_show'
import company_show_tpl from './components/mobile_company_show'
import resume_show_tpl from './components/mobile_resume_show'
export default {
  components: {
    job_show_tpl,
    company_show_tpl,
    resume_show_tpl
  },
  data() {
    return {
      type: 'jobshow'
    }
  },
  methods: {
    switchTemplate(item){
      this.type = item
    }
  }
}
</script>

<style lang="scss" scoped>
.box{
  display: flex;
  .button{
    width: 120px;
    flex-shrink: 0;
    div{
      background-color: #d4d4da30;
      border-radius: 5px;
      text-align: center;
      margin-top: 10px;
      width: 100%;
      height: 35px;
      font-size: 13px;
      line-height: 35px;
      cursor: pointer;
      color: #00000087;
      font-weight: bold;
    }
    .selectButton{
      width: 100%;
      background-color: #0a79eb;
      color: #FFFFFF;
      font-weight: bold;
    }
  }
  .right{
    margin-left: 10px;
    margin-top: -10px;
  }
}
</style>
