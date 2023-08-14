<template>
    <el-card>
      <personal-title v-if="utype==2">消息通知</personal-title>
      <div class="notice_title" v-if="utype==1">消息通知</div>
      <ul class="notice_list" v-loading="listLoading">
        <li v-for="(item,index) in dataset" :key="index">
          <p class="notice_item_title">{{ item.type_text }} <span>{{ item.addtime | timeFilter }}</span></p>
          <span class="notice_text">{{ item.content }}<a style="color:#409eff" v-if="item.inner_link" href="javascript:;" @click="handlerDetail(item)">查看详情&gt;</a></span>
        </li>
      </ul>
      <pagination :total="total" :current-page="params.page" :page-size="params.pagesize"  @handleCurrentChange="doSearch"></pagination>
    </el-card>
</template>

<script>
import { parseTime } from '@/utils/index'
  import http from '@/utils/http'
  import api  from '@/api'
  export default {
    name: 'Message',
    props:['utype'],
    filters: {
      timeFilter (timestamp) {
        return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    data() {
      return {
        listLoading:false,
        dataset:[],
        total:1,
        params: {
          page: 1,
          pagesize: 10,
        },
        link_url_web:{}
      }
    },
    mounted(){
      this.link_url_web = this.$store.state.config.link_url_web
      this.fetchData(true)
    },
    methods:{
      fetchData(init){
        this.listLoading=true
        http.get(api.member_msglist,this.params).then((res)=>{
          this.dataset=[...res.data.items]
          this.listLoading=false
          if(init==true){
            this.fetchTotal()
          }
        })
      },
      fetchTotal(){
          http.get(api.member_msglist_total).then((res)=>{
            this.total=res.data
          })
      },
      doSearch(page){
        this.params.page=page
        this.fetchData()
      },
      handlerDetail (item) {
      if (item.utype == 1) {
        switch (item.inner_link) {
          case 'resumeshow':
            window.open(this.link_url_web.resumeshow.replace('_id_',item.inner_link_params))
            break
          case 'job_manage':
            this.$router.push('/company/joblist')
            break
          case 'company_index':
            this.$router.push('/company')
            break
          case 'company_auth':
            this.$router.push('/company/auth')
            break
          case 'ordershow':
            this.$router.push('/company/service/order')
            break
          case 'service':
            this.$router.push('/company/service/increment')
            break
          case 'coupon':
            this.$router.push('/company/service/coupon')
            break
          case 'service_setmeal':
            this.$router.push('/company/service/setmeal')
            break
          case 'interview_list':
            this.$router.push('/company/resume/interview')
            break
          case 'interview_list_video':
            this.$router.push('/company/resume/interview_video')
            break
          case 'recommend_resumelist':
            this.$router.push('/company/recommend')
            break
          default:
            break
        }
      } else {
        if(item.spe_link_params!=''){
          var params_str = item.spe_link_params
          params_str = params_str.replace('c1=','c1/')
          params_str = params_str.replace('&c2=','/c2/')
          params_str = params_str.replace('&c3=','/c3/')
          params_str = params_str.replace('&d1=','/d1/')
          params_str = params_str.replace('&d2=','/d2/')
          params_str = params_str.replace('&d3=','/d3/')
          params_str = params_str.replace('&w1=','/w1/')
          params_str = params_str.replace('&w2=','/w2/')
          window.open(this.link_url_web.joblist+params_str)
          return false
        }
        switch (item.inner_link) {
          case 'companyshow':
            window.open(this.link_url_web.companyshow.replace('_id_',item.inner_link_params))
            break
          case 'interview_list':
            this.$router.push('/personal/job/interview')
            break
          case 'interview_list_video':
            this.$router.push('/personal/job/interview_video')
            break
          case 'apply_list':
            this.$router.push('/personal/job/jobapply')
            break
          case 'ordershow':
            this.$router.push('/personal/service/order')
            break
          case 'joblist':
            window.open(this.link_url_web.joblist)
            break
          case 'resumeedit':
            this.$router.push('/personal/resume')
            break
          case 'recommend_joblist':
            this.$router.push('/personal/recommend')
            break
          default:
            break
        }
      }
    }
    }
  }
</script>

<style scoped="scoped" lang="scss">
  .notice_title{
    padding: 17px 20px 33px;
    color:#333 ;
    font-size: 18px;
    font-weight: bold;
  }
  .notice_list{
    margin:  0 20px 0px  20px;
    border-top: 1px solid #ebeef5;
  }
  .notice_list li{
    padding: 14px 26px 14px 20px;
    border-bottom: 1px solid #ebeef5;
  }

  .notice_list li:hover{
    background: #f9f9f9;
  }

  .notice_item_title{
    color:#333;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
  }
  .notice_item_title span{
    font-weight: normal;
    color: #666;
    font-size: 14px;
  }
  .notice_text{
    color: #666;
    font-size: 14px;
  }
</style>
