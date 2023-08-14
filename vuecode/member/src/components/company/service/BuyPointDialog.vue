<template>
  <ul class="dialog_wrapper">
    <li>
      <span class="margin">选择套餐</span>
      <el-select v-model="submitData.service_id" class="el_select_width" @change="changeItem">
        <el-option
          v-for="(item,index) in dataset"
          :key="index"
          :label="item.name"
          :value="item.id">
        </el-option>
      </el-select>
    </li>
    <li>
      <span class="margin">应付金额</span>
      <span class="color">{{amount}}</span>元
    </li>
    <li class="payment_type" v-if="amount > 0">
      <span class="margin">支付方式</span>
      <Payment @choosePayment="choosePayment"></Payment>
    </li>
    <li class="btn">
      <span class="margin"></span>
      <el-button type="primary" @click="submit">{{ amount == 0 ? "立即兑换" : "立即支付" }}</el-button>
    </li>
    <PaySubmit ref="paySubmit" :showWaiting="true" :payment="submitData.payment" successUrl="/company/service/order" failUrl="/company/service/order"></PaySubmit>
  </ul>
</template>

<script>
import PaySubmit from "@/components/member/service/PaySubmit";
import Payment from "@/components/member/service/Payment";
import http from '@/utils/http'
import api from '@/api'
  export default{
    props:['type','serviceId','jobId'],
    components:{
      PaySubmit,
      Payment
    },
    data(){
      return{
        order_id:0,
        dataset:[],
        amount: 0.0,
        submitData: {
          service_type: 'points',
          service_id: '',
          payment: 'alipay'
        }
      }
    },
    mounted(){
      this.initCB()
    },
    methods:{
      initCB(){
        this.fetchData()
      },
      fetchData () {
        http
          .get(api.points_setmeal_list, {})
          .then(res => {
            this.dataset = res.data.items
            this.amount = this.dataset[0].expense
            this.submitData.service_id=this.dataset[0].id
          })
          .catch(() => {})
      },
      choosePayment (payment) {
        this.submitData.payment = payment
      },
      changeItem(service_id){
        let item = null
        for (const iterator of this.dataset) {
          if(iterator.id==service_id){
            item = iterator
            break
          }
        }
        this.submitData.service_id=item.id
        this.amount=item.expense
      },
      submit () {
        this.submitData.return_url =
          window.location.protocol +
          '//' +
          window.location.host +
          '/'+this.$store.state.config.member_dirname+'/company/service/order'
          let that = this
        this.$refs.paySubmit.handlerSubmit(api.company_pay,this.submitData,function(){
          that.$emit('submitPay')
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dialog_wrapper{
    padding: 0 15px;
  }
  .dialog_wrapper li{
    margin-bottom: 20px;
  }
  .payment_type{
    display: flex;
    line-height: 44px;
  }
  .margin{
    margin-right: 15px;
    color: #666666;
    font-size: 13px;
    width:55px;
    display:inline-block;
  }
  .color{
    color: #ff6600;
    font-size:14px ;
  }
  .color_2{
    color: #333333;
    font-size: 15px;
  }
  .el_select_width{
    width: 270px;
  }
</style>
