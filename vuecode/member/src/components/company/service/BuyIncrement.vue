<template>
  <el-card>
    <company-title v-if="type=='jobstick'">职位置顶</company-title>
    <company-title v-if="type=='emergency'">职位紧急</company-title>
    <company-title v-if="type=='job_refresh'">智能刷新</company-title>
    <company-title v-if="type=='resume_package'">简历增值包</company-title>
    <company-title v-if="type=='im'">职聊增值包</company-title>
    <div class="topping_warpper">
    <ul class="topping_con">
      <li v-if="type != 'resume_package' && type != 'im'">
        <span class="margin">选择职位</span>
        <el-select v-model="submitData.jobid"  placeholder="请选择职位" class="el_select">
          <el-option
          v-for="(item,index) in joblist"
          :key="index"
          :label="item.jobname"
          :value="item.id">
        </el-option>
        </el-select>
      </li>
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
      <li v-if="type == 'job_refresh'">
        <span class="margin">开始时间</span>
        <el-date-picker
         class="el_select"
         format="yyyy-MM-dd HH:mm"
         value-format="yyyy-MM-dd HH:mm"
          v-model="submitData.starttime"
          type="datetime"
          placeholder="请选择开始刷新时间">
        </el-date-picker>
      </li>
      <li v-if="type == 'job_refresh'">
        <span class="margin">刷新间隔</span>
        <el-select v-model="submitData.timerange" class="el_select">
          <el-option
            v-for="(item,index) in options_timerange"
            :key="index"
            :label="item.text"
            :value="item.value">
          </el-option>
        </el-select>
      </li>
      <li v-if="mySetmeal.service_added_discount > 0">
        <span class="margin">专享折扣</span>
       增值服务{{ mySetmeal.service_added_discount }}折
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
        <!--同一时间多次点击支付生成多条订单修改 zch 2022.07.06-->
        <el-button type="primary" @click="submit" :disabled="ispay">{{ amount == 0 ? "立即兑换" : "立即支付" }}</el-button>
      </li>
    </ul>
  </div>
  <PaySubmit ref="paySubmit" :showWaiting="true" :payment="submitData.payment" successUrl="/company/service/order" failUrl="/company/service/order"></PaySubmit>
  </el-card>
</template>

<script>
import PaySubmit from "@/components/member/service/PaySubmit";
import Payment from "@/components/member/service/Payment";
import http from '@/utils/http'
import api from '@/api'
  export default {
    name: 'CompanyBuyIncrement',
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
        joblist:[],
        submitData: {
          service_type: '',
          service_id: 0,
          deduct_points: 0,
          starttime: '',
          timerange: '',
          payment: 'wxpay',
          jobid: ''
        },
        mySetmeal:{},
        options_timerange:[],
        ispay:false   // 同一时间多次点击支付生成多条订单修改
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
      this.options_timerange = []
      for (let i = 1; i <= 12; i++) {
        this.options_timerange.push({ text: i + '小时', value: i * 3600 })
      }
      this.options_timerange.push({ text: '24小时', value: 24 * 3600 })
      this.options_timerange.push({ text: '48小时', value: 48 * 3600 })
      this.fetchSetmeal()
    },
    methods:{
      fetchSetmeal () {
        http
          .get(api.member_setmeal, {})
          .then(res => {
            this.mySetmeal = res.data.info
            this.fetchData()
            if (this.type === 'im' && parseInt(this.mySetmeal.im_max_perday) === 0) {
              this.$confirm('您当前是' +
                this.mySetmeal.name +
                '，暂不能使用聊天功能，建议您升级套餐！',
                '系统提示',
                {
                  confirmButtonText: '升级套餐',
                  cancelButtonText: '取消',
                  type: 'warning'
                })
                .then(() => {
                  this.$router.push('/company/service/setmeal')
                })
                .catch(() => {
                  // on cancel
                })
              this.ispay = true
              return false
            }
            if (this.type === 'resume_package' && parseInt(this.mySetmeal.download_resume_max_perday) === 0) {
              this.$confirm('您当前是' +
                this.mySetmeal.name +
                '，暂不能下载简历，建议您升级套餐！',
                '系统提示',
                {
                  confirmButtonText: '升级套餐',
                  cancelButtonText: '取消',
                  type: 'warning'
                })
                .then(() => {
                  this.$router.push('/company/service/setmeal')
                })
                .catch(() => {
                  // on cancel
                })
              this.ispay = true
              return false
            }
          })
          .catch(() => {
          })
      },
      fetchData () {
        http
          .get(api.company_servicelist, {type:this.type})
          .then(res => {
            this.dataset = []
            let list = res.data.items
            for (const iterator of list) {
              if (iterator.times !== undefined) {
                iterator.expense_avg = iterator.expense / iterator.times
                iterator.expense_avg = iterator.expense_avg.toFixed(2) + '元/次'
              } else if (iterator.days !== undefined) {
                iterator.expense_avg = iterator.expense / iterator.days
                iterator.expense_avg = iterator.expense_avg.toFixed(2) + '元/天'
              } else {
                iterator.expense_avg =
                  iterator.expense / iterator.download_resume_point
                iterator.expense_avg = iterator.expense_avg.toFixed(2) + '元/点'
              }
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
            this.joblist = res.data.joblist
            if (
              this.submitData.jobid === undefined ||
              !this.submitData.jobid
            ) {
              this.submitData.jobid = this.joblist[0].id
            }
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
      submit () {
        // 同一时间多次点击支付生成多条订单修改
        this.ispay = true
        this.submitData.deduct_points = this.is_deduct
          ? this.enable_points_deduct_points
          : 0
        this.submitData.service_type =
          this.type == 'job_refresh'
            ? 'refresh_job_package'
            : this.type
        if (
          this.submitData.service_type == 'refresh_job_package' &&
          this.submitData.starttime == ''
        ) {
          this.$message.error('请选择开始刷新时间')
          this.ispay = false
          return false
        }
        if (
          this.submitData.service_type == 'refresh_job_package' &&
          this.submitData.timerange == ''
        ) {
          this.$message.error('请选择刷新时间间隔')
          this.ispay = false
          return false
        }
        this.submitData.return_url =
          window.location.protocol +
          '//' +
          window.location.host +
          '/'+this.$store.state.config.member_dirname+'/company/service/order'
        // 同一时间多次点击支付生成多条订单修改
        this.$refs.paySubmit.handlerSubmit(api.company_pay,this.submitData,()=>{
          this.ispay = false
        })
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
    color:#666 ;
    font-size:14px ;
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
    color: #999;
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
  .item_right .priceColor{
    color: #ff7c60;
    font-size: 16px;
    font-weight: bold;
  }

  .priceColor_con .priceColor{
    color: #ff7c60;
    font-size: 18px;
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
</style>
