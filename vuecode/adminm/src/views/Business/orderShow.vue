<template>
  <div class="body">
    <Head>订单详情</Head>
    <van-cell-group>
      <van-cell class="substring" v-if="info.utype!==undefined">
        <template #title>
          <span class="cus_title">{{info.utype==1?'企业名':'姓名'}}：</span><span class="cus_content">{{name}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">服务名称：</span><span class="cus_content">{{info.service_name}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">支付状态：</span><span class="cus_content">{{info.status_cn}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">服务金额：</span><span class="cus_content">{{info.service_amount}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">折后金额：</span><span class="cus_content">{{info.service_amount_after_discount}}</span>
        </template>
      </van-cell>
      <van-cell class="substring">
        <template #title>
          <span class="cus_title">优惠抵扣：</span><span class="cus_content">{{info.deduct_amount}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">实付金额：</span><span class="cus_content">{{info.amount}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">订单号：</span><span class="cus_content">{{info.oid}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">下单时间：</span><span class="cus_content">{{info.addtime}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">支付时间：</span><span class="cus_content">{{info.paytime}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">支付方式：</span><span class="cus_content">{{info.payment}}</span>
        </template>
      </van-cell>
      <van-field
          class="textarea_box"
          v-if="info.status==0"
          v-model="note"
          rows="6"
          label="备注信息："
          type="textarea"
          maxlength="150"
          placeholder="请输入备注信息"
          show-word-limit
      />
    </van-cell-group>
    <div class="bottom clear" v-if="info.status==0">
      <div class="l_btn" @click="funConfirm">确认收款</div>
      <div class="r_btn" @click="funCancel">关闭</div>
    </div>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import { parseTime } from '@/utils/index'
  export default {
    name: "orderShow",
    data () {
      return {
        name:'',
        note:'',
        id: 0,
        info:{},
        options_status: [],
        options_payment: [],
      }
    },
    created () {
      this.name = this.$route.query.name
      this.id = parseInt(this.$route.params.id)
      this.fetchClassify()
    },
    methods:{
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
            this.fetchData()
          }).catch(() => {})
      },
      fetchData () {
        http
          .get(api.orderDetail, {id:this.id})
          .then(res => {
            this.info = {
                utype:res.data.utype,
                oid: res.data.oid,
                service_name: res.data.service_name,
                service_amount: '￥' + res.data.service_amount,
                service_amount_after_discount:'￥' + res.data.service_amount_after_discount,
                amount: '￥' + res.data.amount,
                deduct_amount: '￥' + res.data.deduct_amount,
                addtime: parseTime(res.data.addtime, '{y}-{m}-{d} {h}:{i}'),
                paytime: res.data.paytime == 0 ? '未支付'  : parseTime(res.data.paytime, '{y}-{m}-{d} {h}:{i}'),
                status_cn: this.options_status[res.data.status],
                status: res.data.status,
                amount_detail: res.data.amount_detail,
                payment:res.data.payment==''?'-':this.options_payment[res.data.payment]
              }
          })
          .catch(() => {})
      },
      funConfirm(){
        if(this.note==''){
          this.$toast.fail('请输入备注信息')
          return false
        }
        this.$dialog
            .confirm({
              title: '系统提示',
              message: '确定设置为已完成吗?'
            })
            .then(() => {
              http
              .post(api.orderConfirm, {id:this.id,note:this.note})
              .then(res => {
                this.$toast.success(res.message)
                this.fetchData()
              })
              .catch(() => {})
            })
            .catch(() => {
              // on cancel
            })
      },
      funCancel(){
        this.$dialog
            .confirm({
              title: '系统提示',
              message: '确定关闭该订单吗?'
            })
            .then(() => {
              http
              .post(api.orderCancel, {id:this.id})
              .then(res => {
                this.$toast.success(res.message)
                this.fetchData()
              })
              .catch(() => {})
            })
            .catch(() => {
              // on cancel
            })
      }
    }
  }
</script>

<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .cus_title { font-size: 28px;color: #999999;width: 155px;display: inline-block; }
  .cus_content { font-size: 28px;color: #333333; }
  .van-hairline--top-bottom::after, .van-hairline-unset--top-bottom::after { border-top: 0; }
  .van-cell-group { padding-bottom: 132px; }
  .bottom {
    position: fixed;left: 0;bottom: 0;right: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px;
    background-color: #ffffff;
    .l_btn {
      float: left;width: 490px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #1787fb;
    }
    .r_btn {
      float: right;width: 180px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #f56c6c;
    }
  }
</style>
