<template>
  <div class="body">
    <Head>新增推广</Head>
    <van-field
      readonly
      clickable
      :value="companyname"
      label="选择企业"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after big_left"
      @click="showSearch = true"
    />
    <van-field
      readonly
      clickable
      :value="jobname"
      label="选择职位"
      placeholder="选择职位"
      input-align="right"
      :colon="true"
      class="form_choose reset_after"
      @click="funSearchJob"
    />
    <van-field
      readonly
      clickable
      :value="promotion_name"
      label="推广方案"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after"
      @click="showPicker = true"
    />
    <van-field
      v-model="form.days"
      label="推广天数"
      placeholder="输入推广天数"
      input-align="right"
      :colon="true"
      class="reset_after"
      type="number"
    />
    <div class="bottom"><van-button type="primary" block color="#1787fb" @click="onSubmit">开通推广</van-button></div>
    <van-popup
        :lazy-render="false"
        v-model="showSearch"
        position="right"
        style="width:80%;height:100%"
    >
      <search-company @onConfirm="confirmCompany"></search-company>
    </van-popup>
    <van-popup
        :lazy-render="true"
        v-model="showSearchJob"
        position="right"
        style="width:80%;height:100%"
    >
      <search-job @onConfirm="confirmJob" :comid="comid"></search-job>
    </van-popup>
    <van-popup v-model="showPicker" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsPicker"
        @confirm="onConfirmPicker"
        @cancel="showPicker = false"
      />
    </van-popup>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import searchCompany from '@/components/searchCompany'
  import searchJob from '@/components/searchJob'
  import Vue from 'vue'
  import { Picker } from 'vant'
  Vue.use(Picker)
  export default {
    name: "promotionAdd",
    components: {
      searchCompany,
      searchJob
    },
    data () {
      return {
        columnsPicker:[{text:'置顶',id:'jobstick'},{text:'紧急',id:'emergency'}],
        showPicker:false,
        comid:'',
        jobname:'',
        promotion_name:'',
        companyname:'',
        showSearch: false,
        showSearchJob:false,
        form:{
          pid:0,
          type:'',
          days:''
        }
      }
    },
    created () {
    },
    methods:{
      funSearchJob(){
        if(!this.comid){
          this.$toast.fail('请先选择企业')
          return false
        }
        this.showSearchJob = true
      },
      confirmJob(row){
        this.form.pid = row.id
        this.jobname = row.jobname
        this.showSearchJob = false
      },
      confirmCompany(row){
        this.comid = row.id
        this.companyname = row.companyname
        this.form.pid = 0
        this.jobname = ''
        this.showSearch = false
      },
      onConfirmPicker(value){
        this.form.type = value.id
        this.promotion_name = value.text
        this.showPicker = !this.showPicker
      },
      onSubmit(){
        if(this.form.pid=='' || this.form.pid==0){
          this.$toast.fail('请选择职位')
          return false
        }
        if(this.form.type==''){
          this.$toast.fail('请选择推广方案')
          return false
        }
        if(this.form.days==''){
          this.$toast.fail('请输入推广天数')
          return false
        }
        http
          .post(api.promotionJobAdd, this.form)
          .then(res => {
            this.$toast.success(res.message)
            this.$router.push('/business')
          }).catch(() => {})
      }
    }
  }
</script>
<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .bottom { position: absolute;left: 0;bottom: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px; }
</style>
