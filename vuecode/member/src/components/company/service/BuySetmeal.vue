<template>
  <el-card>
    <company-title>升级套餐</company-title>
    <div class="topping_warpper">
    <ul class="topping_con">
      <li class="service">
        <span class="margin">选择服务</span>
        <div class="service_list">
          <div :class="[submitData.service_id==item.id?'border2':'','hoverbg']" @click="changeItem(item)" v-for="(item, index) in dataset" :key="index">
            <div class="item_left">
              <p>{{ item.name }}</p>
              <div>
                  <span class="item_left_btm"><span class="color1">职位数</span> <span class="color2">{{item.jobs_meanwhile}}个</span></span>
                  <span class="item_left_btm"><span class="color1">简历点</span> <span class="color2">{{item.download_resume_point}}</span></span>
                  <span class="item_left_btm"><span class="color1">{{$store.state.config.points_byname}}</span> <span class="color2">{{item.gift_point}}</span></span>
                </div>
            </div>
            <div class="item_right" v-if="item.preferential_open==1">
              <strong></strong>
              <div class="right_box">
                <span class="priceColor">￥{{ item.expense }}</span><br/>
                <span>限时特惠价</span><br/>
                <span><span>{{ item.preferential_expense_start | timeFilter }}</span>-<span>{{ item.preferential_expense_end | timeFilter }}</span></span>
              </div>
            </div>
            <div class="item_right" v-else>
              <strong></strong>
              <span class="priceColor">￥{{ item.expense }}</span>
            </div>

            <i v-show="submitData.service_id==item.id" class="el-icon-check"></i>
          </div>
        </div>
      </li>


      <li class="jurisdiction">
        <span class="margin">套餐权益</span>
        <div>
            <div>职位数：<span class="color">{{setmealDetail.jobs_meanwhile}}</span></div>
            <div>简历点：<span class="color">{{setmealDetail.download_resume_point}}</span></div>
            <div>{{$store.state.config.points_byname}}：<span class="color">{{setmealDetail.gift_point}}</span></div>
            <div>免费刷新：<span class="color">{{setmealDetail.refresh_jobs_free_perday}}次/天</span></div>
            <div>下载上限：<span class="color">{{setmealDetail.download_resume_max_perday}}份/天</span></div>
            <div>增值服务专享折扣：<span class="color">{{setmealDetail.service_added_discount>0?(setmealDetail.service_added_discount+'折'):'无折扣'}}</span></div>
            <div>使用微海报：<span class="color">{{setmealDetail.enable_poster==1?'允许':'不允许'}}</span></div>
            <div>使用视频面试：<span class="color">{{setmealDetail.enable_video_interview==1?'允许':'不允许'}}</span></div>
            <div>收到简历查看：<span class="color">{{setmealDetail.show_apply_contact==1?'免费':'不免费'}}</span></div>
            <div>其他说明：<span class="color">{{setmealDetail.note==''?'无':setmealDetail.note}}</span></div>
        </div>
      </li>
      <li>
        <span class="margin">优惠券</span>
        <el-select v-model="submitData.coupon_id" class="el_select" @change="chooseCoupon">
          <el-option
            v-for="(item,index) in couponList"
            :key="index"
            :label="item.text"
            :value="item.id">
          </el-option>
        </el-select>
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
  <PaySubmit ref="paySubmit" :showWaiting="true" :payment="submitData.payment" successUrl="/company/service/order" failUrl="/company/service/order"></PaySubmit>
  </el-card>
</template>

<script>
import PaySubmit from "@/components/member/service/PaySubmit";
import Payment from "@/components/member/service/Payment";
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
  export default {
    name: 'BuySetmeal',
    props:['coupon_id'],
    components:{
      PaySubmit,
      Payment
    },
    filters: {
      timeFilter (timestamp) {
        return parseTime(timestamp, '{m}月{d}日')
      }
    },
    data(){
      return{
        dataset:[],
        old_amount:0.0,
        amount: 0.0,
        submitData: {
          service_type: 'setmeal',
          service_id: 0,
          payment: 'alipay',
          coupon_id: 0
        },
        setmealDetail:{},
        couponList: [{ id: 0, text: '不使用优惠券', name: '不使用优惠券' }]
      }
    },
    created(){
      this.fetchSetmeal()
    },
    methods:{
      handlerQuery () {
        if (
          this.$route.query.setmeal_id !== undefined &&
          this.$route.query.setmeal_id > 0
        ) {
        if (
          this.$route.query.coupon_id !== undefined &&
          this.$route.query.coupon_id > 0
        ) {
          this.submitData.coupon_id = parseInt(this.$route.query.coupon_id)
        }
          this.submitData.service_id = this.$route.query.setmeal_id
          for (const key in this.dataset) {
            if (this.dataset[key].id == this.submitData.service_id) {
              this.changeItem(this.dataset[key], key)
              for (const k in this.couponList) {
                if (this.couponList[k].id == this.submitData.coupon_id) {
                  this.chooseCoupon(this.couponList[k])
                  break
                }
              }
              break
            }
          }
        }
      },
      fetchSetmeal () {
        http
          .get(api.company_setmeallist, {})
          .then(res => {
            this.dataset = res.data.items
            if (this.dataset.length > 0) {
              this.submitData.service_id = this.dataset[0].id
              this.old_amount = this.dataset[0].expense
              this.amount = this.dataset[0].expense
              this.dataset[0].couponList.forEach(element => {
                element.text = element.name + '(-￥' + element.face_value + ')'
                this.couponList.push(element)
              })
              this.setmealDetail = this.dataset[0]
              this.handlerQuery()
            }
          })
          .catch(() => {})
      },
      chooseCoupon () {
        this.amount = this.old_amount
        let value = null
        for (const iterator of this.couponList) {
          if(iterator.id===this.submitData.coupon_id){
            value = iterator
            break
          }
        }
        if (value.face_value !== undefined) {
          this.amount = this.amount - value.face_value
          this.amount = this.amount.toFixed(2)
          if (this.amount < 0) {
            this.amount = 0.0
          }
        }
      },
      choosePayment (payment) {
        this.submitData.payment = payment
      },
      changeItem(item){
        this.submitData.service_id = item.id
        this.old_amount = item.expense
        this.amount = item.expense
        this.setmealDetail = item
        this.submitData.coupon_id = 0
        this.couponList = [{ id: 0, text: '不使用优惠券', name: '不使用优惠券' }]
        item.couponList.forEach(element => {
          element.text = element.name + '(-￥' + element.face_value + ')'
          this.couponList.push(element)
        })
      },
      submit () {
        this.submitData.return_url =
          window.location.protocol +
          '//' +
          window.location.host +
          '/'+this.$store.state.config.member_dirname+'/company/service/order'
        this.$refs.paySubmit.handlerSubmit(api.company_pay,this.submitData)
      },
    },
  }
</script>

<style lang="scss" scoped>
    .topping_warpper{
      border-top: 1px solid #ebeef5;
   }
   .topping_con{
     margin-top: 30px;
   }
  .topping_con li{
    margin-bottom: 25px;
  }
  .topping_con .margin{
    margin-right: 15px;
    display:inline-block;
    width:60px;
  }
  .service{
    display: flex;
  }
  .service_list{
    display: flex;
    flex-wrap: wrap;
    width: 1100px;
  }
  .service_list>div{
    width:470px ;
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
  .item_left p{
    color: #333333;
    font-size: 16px;
    margin: 10px 0 ;
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
  .item_right>span{
    display: inline-block;
    line-height: 78px;
  }

  .service_list .hoverbg{
    position: relative;

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
  .right_box .priceColor , .item_right .priceColor{
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
  .el_select{
    width:319px ;
  }
  .integral_text{
    display: inline-block;
    color:#747474 ;
    font-size: 14px;
  }
  .integral_text1{
    margin-right: 60px;
  }
  .el_button{
    width: 260px;
    height: 42px;
  }
  .payment_text{
    color:#666666 ;
    font-size: 14px;
  }


  // 套餐权益
  .jurisdiction{
     display: flex;

   }
   .jurisdiction>div{
     width: 710px;
     height: 134px;
     background: #fbfbfb;
     border: 1px solid #eeeeee;
    padding: 20px;
    flex-wrap:  wrap;
    display: flex;
   }
   .jurisdiction>div div{
     height: 20px;
     margin-right: 40px;
     font-size: 13px;
     color: #666666;
     width:180px;
   }
   .color{
     color: #db8e24;
     font-size: 13px;
   }
   .right_box{
     line-height: 18px;
   }
   .right_box{
     text-align: center;
     margin-top: 9px;
   }
   .right_box span{
     color:#92672c;
     font-size: 12px;
     font-weight: bold;
   }

   .item_left_btm{
     margin-right: 30px;
   }
   .item_left_btm .color1{
     color: #666666;
     font-size: 13px;
   }
   .item_left_btm .color2{
     color: #db8e24;
     font-size: 13px;
   }
</style>
