<template>
  <div>
    <Head>{{title}}</Head>
    <Search @doSearch="doSearch" placeholder="请输入姓名/简历ID/手机号"></Search>
    <van-overlay z-index="10" :show="fetchDataFinish===false"><van-loading color="#1989fa" class="loading" >数据加载中...</van-loading></van-overlay>
    <van-empty
      image="search"
      description="没有找到对应的数据"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <van-list
      v-if="dataset.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
    <div class="b1">
      <div class="b_item" @click="jumpShow(item.link)" v-for="(item,index) in dataset" :key="index">
        <img :src="item.photo_img_src" class="ava">
        <p class="t1 substring">{{item.fullname}}</p><van-icon class="arrow" name="arrow" color="#999999" />
        <p class="t2">{{item.age}}/{{item.sex_cn}}/{{item.education_cn}}/{{item.experience_cn}}</p>
        <div class="i_tag" :class="item.audit | auditFilter">{{ options_audit[item.audit] }}</div>
        <div class="t_line">
          <div class="tl_cell"><span class="l_title">等级：</span><span class="l_con">{{item.high_quality==1?'优质':'普通'}}</span></div>
          <div class="tl_cell"><span class="l_title">完整度：</span><span class="l_con">{{item.complete_percent}}%</span></div>
        </div>
        <div class="t_line last">
          <div class="tl_cell"><span class="l_title">手机号：</span><span class="l_con">{{item.mobile}}</span></div>
          <div class="tl_cell"><span class="l_title">刷新：</span><span class="l_con">{{item.refreshtime|timeFilter}}</span></div>
        </div>
        <div class="i_btn b1 blue" @click.stop="funAudit(item)">审核</div><div class="i_btn b2">查看</div>
      </div>
    </div>
    </van-list>
    <van-action-sheet
      v-model="showAudit"
      :actions="auditActions"
      cancel-text="取消"
      close-on-click-action
      @cancel="showAudit=false"
      @select="doAudit"
    />
    <van-dialog v-model="showReason" title="审核不通过原因" :show-confirm-button="false" :show-cancel-button="false" :closeOnClickOverlay="true">
      <van-form @submit="onSubmitReason">
        <van-field
          v-model="auditReason"
          rows="3"
          autosize
          label=""
          type="textarea"
          placeholder="请填写审核不通过原因"
          :rules="[{ required: true, message: '' }]"
        />
        <div style="margin: 16px;">
          <van-button round block type="info" native-type="submit">提交</van-button>
        </div>
      </van-form>
    </van-dialog>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import { formatTime } from '@/utils/index'
  export default {
    name: "resumeList",
    filters: {
      timeFilter (timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      },
      auditFilter(audit) {
        switch (audit) {
          case 0:
            return 'wait'
          case 1:
            return 'passed'
          case 2:
            return 'failed'
          default:
            return 'not'
        }
      }
    },
    data () {
      return {
        fetchDataFinish:false,
        showReason:false,
        auditResult:1,
        auditReason:'',
        auditRow:{},
        auditActions: [],
        showAudit:false,
        dataset: [],
        loading: false,
        finished: false,
        finished_text: '',
        show_empty: false,
        keyword: '',
        page: 1,
        pagesize: 15,
        regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
        regularNumber:/^\d+$/,
        title: '',
        type: 1, // 1->简历管理 0->待审核简历
        options_audit:[]
      }
    },
    created () {
      this.type = parseInt(this.$route.params.type)
      this.title = this.type === 1 ? '简历管理' : '待审核简历'
      this.fetchClassify()
    },
    methods: {
      fetchClassify(){
        http
          .get(api.classify, {type:'resumeAudit'})
          .then(res => {
            this.auditActions = res.data
            this.auditActions.forEach(el => {
              this.options_audit[el.id] = el.name
            })
            this.fetchData(true)
          }).catch(() => {})
      },
      buildCondition(){
        let conditions = {
          keyword:this.keyword,
        }
        conditions.page = this.page
        conditions.pagesize = this.pagesize
        conditions.list_type = this.type==1?'':'noaudit'
        if (this.regularMobile.test(conditions.keyword)) {
          conditions.key_type = 4
        }else if(this.regularNumber.test(conditions.keyword)){
          conditions.key_type = 2
        }else{
          conditions.key_type = 1
        }
        return conditions
      },
      fetchData (init) {
        this.show_empty = false

        if (init === true) {
          this.page = 1
          this.finished_text = ''
          this.finished = false
        }
        let conditions = this.buildCondition()
        http
          .get(api.resumeList, conditions)
          .then(res => {
            if (init === true) {
              this.dataset = [...res.data.items]
            } else {
              this.dataset = this.dataset.concat(res.data.items)
            }
            // 加载状态结束
            this.loading = false
            this.fetchDataFinish = true

            // 数据全部加载完成
            if (res.data.items.length < this.pagesize) {
              this.finished = true
              if (init === false) {
                this.finished_text = '没有更多了'
              } else if (res.data.items.length === 0) {
                this.show_empty = true
              }
            }
          })
          .catch(() => {})
      },
      onLoad () {
        this.page++
        this.fetchData(false)
      },
      doSearch (keyword) {
        this.fetchDataFinish = false
        this.keyword = keyword
        this.fetchData(true)
      },
      funAudit(row){
        this.auditRow = row
        this.showAudit = true
      },
      doAudit(action){
        this.auditResult = action.id
        if(action.id==2){
          this.showAudit = false
          this.showReason = true
        }else{
          this.onSubmitReason()
        }
      },
      onSubmitReason(){
        http
          .post(api.resumeAudit, {id:[this.auditRow.id],audit:this.auditResult,reason:this.auditReason})
          .then(res => {
            this.$toast.success(res.message)
            this.fetchData(true)
            this.showAudit = false
            this.showReason = false
            this.auditRow = {}
            this.auditReason = ''
          })
          .catch(() => {})
      },
      jumpShow (url) {
        location.href = url
      }
    }
  }
</script>

<style lang="scss" scoped>
.loading{
  text-align: center;
  top: 50%;
}
.van-overlay{
  background-color:rgba(255, 255, 255, 0.9)
}
  .b1 {
    .b_item {
      width: 100%;background-color: #ffffff;margin-top: 10px;position: relative;padding: 30px 30px 130px 30px;
      &:nth-of-type(1) { margin-top: 0; }
      .ava { position: absolute;left: 30px;top: 30px;width: 70px;height: 70px;border-radius: 70px;border: 0; }
      .arrow { position: absolute;right: 20px;top: 45px;font-size: 42px; }
      .t1 { font-size: 30px;font-weight: bold;color: #333333;max-width: 422px;margin-bottom: 56px;padding-left: 88px; }
      .t2 { position: absolute;left: 118px;top: 75px;font-size: 24px;color: #999999; }
      .t_line {
        display: flex;margin-bottom: 18px;
        &.last { margin-bottom: 0; }
        .tl_cell {
          flex: 1;font-size: 24px;
          .l_title { color: #999999; }
          .l_con { color: #333333; }
        }
      }
      .i_btn {
        position: absolute;bottom: 43px;right: 30px;font-size: 24px;color: #333333;padding: 8px 34px;
        border: 1PX solid #cccccc;border-radius: 999px;
        &.b1 { right: 30px; }
        &.b2 { right: 170px; }
        &.blue { color: #248efb;border-color: #248efb; }
      }
      .i_tag {
        position: absolute;right: 62px;top: 48px;width: 104px;text-align: center;padding: 6px 0;
        font-size: 20px;border-radius: 999px;
        &.wait { background-color: #fdf6ec;color: #ff8e50; }
        &.passed { background-color: #f0f9eb;color: #67c23a; }
        &.failed { background-color: #fef0f0;color: #f56c6c; }
        &.not { background-color: #f4f4f5;color: #909399; }
      }
    }
  }
</style>
