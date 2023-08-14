<template>
  <div>
    <Head>照片作品</Head>
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
      <div class="b_item" v-for="(item,index) in dataset" :key="index">
        <img :src="item.img_src" class="photo" @click="preview(item)">
        <div class="i_tag" :class="item.audit | auditFilter">{{ options_audit[item.audit] }}</div>
        <div class="i_tip">上传：{{item.addtime|timeFilter}}</div>
        <div class="i_btn b1 blue" @click.stop="funAudit(item)">审核</div>
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
  </div>
</template>

<script>
  import { ImagePreview } from 'vant';
  import http from '@/utils/http'
  import api from '@/api'
  import { formatTime } from '@/utils/index'
  export default {
    name: "photoList",
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
        auditRow:{},
        auditActions: [],
        showAudit:false,
        dataset: [],
        loading: false,
        finished: false,
        finished_text: '',
        show_empty: false,
        page: 1,
        pagesize: 15,
        options_audit:[],
      }
    },
    created () {
      this.fetchClassify()
    },
    methods: {
      fetchClassify(){
        http
          .get(api.classify, {type:'resumeImgAudit'})
          .then(res => {
            this.auditActions = res.data
            this.auditActions.forEach(el => {
              this.options_audit[el.id] = el.name
            })
            this.fetchData(true)
          }).catch(() => {})
      },
      buildCondition(){
        let conditions = {}
        conditions.page = this.page
        conditions.pagesize = this.pagesize
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
          .get(api.resumeImgList, conditions)
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
      funAudit(row){
        this.auditRow = row
        this.showAudit = true
      },
      doAudit(action){
        http
          .post(api.resumeImgAudit, {id:this.auditRow.id,audit:action.id})
          .then(res => {
            this.$toast.success(res.message)
            this.fetchData(true)
            this.showAudit = false
            this.auditRow = {}
          })
          .catch(() => {})
      },
      preview(row){
        ImagePreview([row.img_src]);
      },
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
      height: 290px;
      .photo { position: absolute; left: 30px;top: 43px;width: 200px;height: 200px;border: 0; }
      &:nth-of-type(1) { margin-top: 0; }
      .i_btn {
        position: absolute;bottom: 43px;right: 30px;font-size: 24px;color: #333333;padding: 8px 34px;
        border: 1PX solid #cccccc;border-radius: 999px;
        &.b1 { right: 30px; }
        &.blue { color: #248efb;border-color: #248efb; }
      }
      .i_tip { position: absolute;left: 252px;bottom: 53px;font-size: 24px;color: #999999; }
      .i_tag {
        position: absolute;right: 30px;top: 45px;width: 104px;text-align: center;padding: 6px 0;
        font-size: 20px;border-radius: 999px;
        &.wait { background-color: #fdf6ec;color: #ff8e50; }
        &.passed { background-color: #f0f9eb;color: #67c23a; }
        &.failed { background-color: #fef0f0;color: #f56c6c; }
        &.not { background-color: #f4f4f5;color: #909399; }
      }
    }
  }
</style>
