<template>
  <div>
    <div class="topping_warpper">
    <ul class="topping_con">
      <li class="service">
        <span class="margin">选择服务</span>
        <div class="service_list">
          <div :class="[submitData.service_id==item.id?'border2':'','hoverbg']" @click="changeItem(item)" v-for="(item, index) in dataset" :key="index">
            <div class="item_left">
              <p><span class="service_name">{{ item.name }}<img v-if="item.recommend==1" class="recommend" :src="require('@/assets/images/tuijian2.png')" /></span></p>
              <span>低至 {{item.expense_avg}}<span v-if="item.enable_points_deduct == 1"> ，可使用{{ $store.state.config.points_byname }}抵扣</span></span>
            </div>
            <div class="item_right">
              <strong></strong>
              <span class="priceColor">￥{{ item.expense }}</span>
            </div>
            <i v-show="submitData.service_id==item.id" class="el-icon-check"></i>
          </div>
        </div>
      </li>
      <li v-if="type == 'tag'">
        <span class="margin">选择标签</span>
        <el-tag
          class="tag-item"
          v-for="(item,index) in options_tag"
          :key="index"
          type="warning"
          @click="handlerSelTag(item)"
          :effect="sel_tag_id === item.id?'dark':'plain'">
          {{ item.text }}
        </el-tag>
        <el-tag
          v-if="showCustomTag===false"
          class="tag-item"
          type="warning"
          @click="showCustomTag=!showCustomTag"
          :effect="sel_tag_id === ''?'dark':'plain'">
          {{custom_tag_text === "" ? "自定义" : custom_tag_text}}
        </el-tag>
        <el-input
            v-if="showCustomTag===true"
            class="input-new-tag"
            v-model="custom_tag_text"
            ref="saveTagInput"
            size="small"
            @keyup.enter.native="handleInputConfirm"
            @blur="handleInputConfirm"
          >
          </el-input>
      </li>
      <li v-if="enable_points_deduct == 1">
        <span class="margin">{{ $store.state.config.points_byname }}抵扣</span>
        <span><el-checkbox v-model="is_deduct" @change="changeDeduct">可使用{{ enable_points_deduct_points}}{{ $store.state.config.points_byname }}抵用{{enable_points_deduct_expense}}元</el-checkbox></span>
      </li>
      <li class="priceColor_con">
        <span class="margin">应付金额</span>
        <span class="priceColor">{{amount}}</span>元
      </li>
      <li class="payment_type" v-if="amount > 0">
        <span class="margin">支付方式</span>
        <Payment @choosePayment="choosePayment"></Payment>
      </li>
      <li class="btn">
        <el-button type="primary" @click="submit">{{ amount == 0 ? "立即兑换" : "立即支付" }}</el-button>
      </li>
    </ul>
  </div>
  <PaySubmit ref="paySubmit" :showWaiting="true" :payment="submitData.payment" successUrl="/personal/service/order" failUrl="/personal/service/order"></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from "@/components/member/service/PaySubmit";
import Payment from "@/components/member/service/Payment";
import http from '@/utils/http'
import api from '@/api'
  export default {
    name: 'PersonalBuyIncrement',
    props:['type'],
    components:{
      PaySubmit,
      Payment
    },
    data(){
      return{
        dataset:[],
        old_amount: 0.0,
        amount: 0.0,
        is_deduct: false,
        enable_points_deduct: 0,
        enable_points_deduct_points: 0,
        enable_points_deduct_expense: 0.0,
        after_deduct_expense: 0.0,
        submitData: {
          service_type: '',
          service_id: 0,
          deduct_points: 0,
          payment: 'wxpay',
          tag_text: ''
        },
        custom_tag_text:'',
        sel_tag_id:0,
        showCustomTag: false,
      }
    },
    computed: {
      options_tag () {
        return this.$store.state.classifyResumeStrongTag
      }
    },
    watch:{
        amount:function(newVal){
            if(newVal==0){
                this.submitData.payment = 'free'
            }else{
                this.submitData.payment = 'wxpay'
            }
        }
    },
    created(){
      this.fetchData()
      console.log(this.type)
      if(this.type=='tag'){
        this.handlerSelTag(this.options_tag[0])
      }
    },
    methods:{
      handleInputConfirm() {
        this.showCustomTag = false;
        this.sel_tag_id = ''
        this.submitData.tag_text = this.custom_tag_text
      },
      fetchData () {
        http
          .get(api.personal_servicelist, {type:this.type})
          .then(res => {
            this.dataset = []
            let list = res.data.items
            for (const iterator of list) {
              iterator.expense_avg = iterator.expense / iterator.days
              iterator.expense_avg = iterator.expense_avg.toFixed(2) + '元/天'
              this.dataset.push(iterator)
            }
            this.submitData.service_id = list[0].id
            this.enable_points_deduct = list[0].enable_points_deduct
            this.enable_points_deduct_points =
              list[0].enable_points_deduct_points
            this.enable_points_deduct_expense =
              list[0].enable_points_deduct_expense
            this.after_deduct_expense = list[0].after_deduct_expense
            // 计算最终需要支付的金额
            this.old_amount = list[0].expense
            this.handlerFinalAmount()
          })
          .catch(() => {})
      },
      choosePayment (payment) {
        this.submitData.payment = payment
      },
      changeDeduct () {
        // 计算最终需要支付的金额
        this.handlerFinalAmount()
      },
      handlerFinalAmount () {
        if (this.is_deduct) {
          this.amount = this.after_deduct_expense
        } else {
          this.amount = this.old_amount
        }
      },
      changeItem(item){
        this.enable_points_deduct = item.enable_points_deduct
        this.enable_points_deduct_points = item.enable_points_deduct_points
        this.enable_points_deduct_expense = item.enable_points_deduct_expense
        this.after_deduct_expense = item.after_deduct_expense
        // 计算最终需要支付的金额
        this.old_amount = item.expense
        this.handlerFinalAmount()
        this.submitData.service_id = item.id
      },
      handlerSelTag (tag) {
        this.submitData.tag_text = tag.text
        this.sel_tag_id = tag.id
      },
      submit () {
        this.submitData.deduct_points = this.is_deduct
          ? this.enable_points_deduct_points
          : 0
        this.submitData.service_type = this.type
        if (
          this.submitData.service_type == 'tag' &&
          this.submitData.tag_text == ''
        ) {
          this.$message.error('请选择醒目标签')
          return false
        }
        this.submitData.return_url =
          window.location.protocol +
          '//' +
          window.location.host +
          '/'+this.$store.state.config.member_dirname+'/personal/service/order'
        this.$refs.paySubmit.handlerSubmit(api.personal_pay,this.submitData)
      },
    },
  }
</script>

<style lang="scss" scoped="scoped">
  .topping_con li{
    margin-bottom: 25px;
  }
  .topping_con .margin{
    margin-right: 15px;
  }
  .service{
    display: flex;
  }
  .service_list{
    display: flex;
    flex-wrap: wrap;
    flex: 9;
  }
  .service_list>div{
    width:360px ;
    height:78px ;
    border: 1px solid #eeeeee;
    margin-right: 20px;
    margin-bottom: 14px;
    display: flex;
    cursor:pointer;
  }
  .item_left{
    flex: 2;
    margin-left: 20px;
  }
  .item_left .service_name{
    position:relative;
    color: #333333;
    font-size: 16px;
  }
  .item_left .service_name .recommend{
    position:absolute;
    top:-10px;
    right:-40px;
  }
  .item_left p{
    margin: 10px 0 8px;
  }
  .item_left span{
    font-size:13px ;
    color: #a9a9a9;
  }
  .item_right{
    flex: 1;
    display: flex;
  }
  .item_right strong{
    height:34px ;
    display: inline-block;
    border: 1px dashed #e2e2e2;
    margin-top: 20px;
    margin-right: 18px;
  }
  .item_right span{
    display: inline-block;
    line-height: 78px;
  }

  .service_list .hoverbg{
    position: relative;
    &:hover {
      border:1px solid #dab577 ;
    }

  }
  .service_list .border2{
    border:1px solid #dab577 ;
     background:#fffcf5 ;
  }
  .hoverbg i{
    border-left:10px solid  transparent;
    border-right:10px solid  #dab577;
    border-top:10px solid  #dab577;
    border-bottom:10px solid  transparent;
    width: 20px;
    height: 20px;
    box-sizing: border-box;
    display: inline-block;
    color: #fff2ea;
    position: absolute;
    right: 0;
    top: 0;
    line-height: 0px;
  }
  .hoverbg i::before{
    width: 14px;
    position: absolute;
    right: -12px;
    top:-4px;
  }

  .payment_type{
    display: flex;
    line-height: 44px;
  }
  .priceColor{
    color: #ff7c60;
    font-size: 15px;
    font-weight: bold;
  }
  .btn{
    margin-left: 78px;
  }
  .btn .el-button{
    width:260px ;
    height: 46px;
    margin-top: 25px;
  }
  .tag-item{
    margin-right:10px;
    cursor:pointer;
  }
  .input-new-tag {
    width: 90px;
    margin-left: 10px;
    vertical-align: bottom;
  }
</style>
