<template>
  <div>
    <Head>订单管理</Head>
    <Search @doSearch="doSearch" placeholder="请输入UID/手机号查询"></Search>
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
      <div class="b_item" @click="jumpShow(item)" v-for="(item,index) in dataset" :key="index">
        <p class="t1 substring">{{utype==1?item.companyname:item.fullname}}</p><van-icon class="arrow" name="arrow" color="#999999" />
        <div class="i_tag" :class="item.status | statusFilter">{{ options_status[item.status] }}</div>
        <div class="t_line">
          <div class="tl_cell substring">
            <span class="l_title">订单号：</span>
            <span class="l_con mr">{{item.oid}}</span>
          </div>
        </div>
        <div class="t_line">
          <div class="tl_cell substring">
            <span class="l_title">服务内容：</span>
            <span class="l_con">{{item.service_name}}</span>
          </div>
        </div>
        <div class="t_line">
          <div class="tl_cell"><span class="l_title">实付金额：</span><span class="l_con">￥{{item.amount}}</span></div>
          <div class="tl_cell"><span class="l_title">下单时间：</span><span class="l_con">{{item.addtime|timeFilter}}</span></div>
        </div>
        <div class="t_line last">
          <div class="tl_cell">
            <span class="l_title">支付方式：</span>
            <span class="l_con" v-if="item.payment == ''">-</span>
            <span class="l_con" v-else>{{ options_payment[item.payment] }}</span>
          </div>
          <div class="tl_cell">
            <span class="l_title">支付时间：</span>
            <span class="l_con" v-if="item.paytime>0">{{item.paytime|timeFilter}}</span>
            <span class="l_con" v-else>-</span>
          </div>
        </div>
        <div class="i_btn b1 blue" @click.stop="funSet(item)" v-if="item.status == 0">设置</div>
        <div class="i_btn b1 grey" v-else @click.stop="return false">设置</div>
        <div class="i_btn b2">查看</div>
      </div>
    </div>
    </van-list>
    <van-action-sheet
      v-model="showSet"
      :actions="setActions"
      cancel-text="取消"
      close-on-click-action
      @cancel="showSet=false"
      @select="doSet"
    />
    <van-dialog v-model="showNote" title="备注" :show-confirm-button="false" :show-cancel-button="false" :closeOnClickOverlay="true">
      <van-form @submit="onSubmitNote">
        <van-field
          v-model="setNote"
          rows="3"
          autosize
          label=""
          type="textarea"
          placeholder="请填写备注"
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
    name: "orderList",
    filters: {
      timeFilter (timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      },
      statusFilter(status) {
        switch (status) {
          case 0:
            return 'wait'
          case 1:
            return 'passed'
          default:
            return 'not'
        }
      }
    },
    data(){
      return {
        fetchDataFinish:false,
        setNote:'',
        showNote:false,
        setRow:{},
        showSet:false,
        setActions:[{name:'确认收款',value:1},{name:'关闭订单',value:0}],
        options_status: [],
        options_payment: [],
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
        utype: 1 // 1->企业会员 2->个人会员
      }
    },
    created () {
      this.utype = parseInt(this.$route.params.utype)
      this.fetchClassify()
    },
    methods: {
      fetchClassify(){
        http
          .get(api.classify, {type:'orderStatus,orderPayment'})
          .then(res => {
            res.data.orderStatus.forEach(el => {
              this.options_status[el.id] = el.name
            })
            res.data.orderPayment.forEach(el => {
              this.options_payment[el.id] = el.name
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
        conditions.utype = this.utype
        if (this.regularMobile.test(conditions.keyword)) {
          conditions.key_type = 4
        }else{
          conditions.key_type = 3
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
          .get(api.orderList, conditions)
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
      jumpShow (row) {
        // 跳转到会员详情页
        this.$router.push(`/order/detail/${row.id}?name=${this.utype==1?row.companyname:row.fullname}`)
      },
      funSet(row){
        this.setRow = row
        this.showSet = true
      },
      doSet(action){
        if(action.value==0){
          this.$dialog
            .confirm({
              title: '系统提示',
              message: '确定关闭该订单吗?'
            })
            .then(() => {
              http
              .post(api.orderCancel, {id:this.setRow.id})
              .then(res => {
                this.$toast.success(res.message)
                this.fetchData(true)
              })
              .catch(() => {})
            })
            .catch(() => {
              // on cancel
            })
        }else{
          this.showSet = false
          this.showNote = true
        }
      },
      onSubmitNote(){
        http
          .post(api.orderConfirm, {id:this.setRow.id,note:this.setNote})
          .then(res => {
            this.$toast.success(res.message)
            this.fetchData(true)
            this.showSet = false
            this.showNote = false
            this.setRow = {}
            this.setNote = ''
          })
          .catch(() => {})
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
      &:nth-of-type(1) { margin-top: 0; }
      .arrow { position: absolute;right: 20px;top: 30px;font-size: 42px; }
      .t1 { font-size: 30px;font-weight: bold;color: #333333;max-width: 510px;margin-bottom: 30px; }
      .t_line {
        display: flex;margin-bottom: 18px;
        &.last { margin-bottom: 0; }
        .tl_cell {
          flex: 1;font-size: 24px;
          .l_title { color: #999999; }
          .l_con {
            color: #333333;
            &.mr { margin-left: 23px; }
          }
        }
      }
      .i_btn {
        position: absolute;bottom: 43px;right: 30px;font-size: 24px;color: #333333;padding: 8px 34px;
        border: 1PX solid #cccccc;border-radius: 999px;
        &.b1 { right: 30px; }
        &.b2 { right: 170px; }
        &.blue { color: #248efb;border-color: #248efb; }
        &.grey { color: #d2cfcf;border-color: #d2cfcf; }
      }
      .i_tag {
        position: absolute;right: 62px;top: 33px;width: 104px;text-align: center;padding: 6px 0;
        font-size: 20px;border-radius: 999px;
        &.wait { background-color: #fdf6ec;color: #ff8e50; }
        &.passed { background-color: #f0f9eb;color: #67c23a; }
        &.failed { background-color: #fef0f0;color: #f56c6c; }
        &.not { background-color: #f4f4f5;color: #909399; }
      }
    }
  }
</style>
