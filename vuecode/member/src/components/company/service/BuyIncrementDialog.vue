<template>
  <ul class="dialog_wrapper">
    <li>
      <span class="margin">选择服务</span>
      <el-select v-model="submitData.service_id" class="el_select_width" @change="changeItem">
        <el-option
          v-for="(item,index) in dataset"
          :key="index"
          :label="item.name"
          :value="item.id">
        </el-option>
      </el-select>
    </li>
    <li v-if="type == 'job_refresh'">
      <span class="margin">开始时间</span>
      <el-date-picker
         class="el_select_width"
         format="yyyy-MM-dd HH:mm"
         value-format="yyyy-MM-dd HH:mm"
          v-model="submitData.starttime"
         :picker-options="gtNow"
          type="datetime"
          placeholder="请选择开始刷新时间">
        </el-date-picker>
    </li>
    <li v-if="type == 'job_refresh'">
      <span class="margin">刷新间隔</span>
      <el-select v-model="submitData.timerange" class="el_select_width">
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
      <span class="color_2">增值服务{{ mySetmeal.service_added_discount }}折</span>
    </li>
    <li v-if="enable_points_deduct == 1">
      <span class="margin">{{ $store.state.config.points_byname }}抵扣</span>
      <el-checkbox v-model="is_deduct" @change="changeDeduct">可使用{{ enable_points_deduct_points}}{{ $store.state.config.points_byname }}抵用{{enable_points_deduct_expense}}元</el-checkbox>
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
        old_amount: 0.0,
        amount: 0.0,
        is_deduct: false,
        enable_points_deduct: 0,
        enable_points_deduct_points: 0,
        enable_points_deduct_expense: 0.0,
        after_deduct_expense: 0.0,
        gtNow: {
          disabledDate (time) {
            return time.getTime() < Date.now() - 8.64e7;
          }
        },
        submitData: {
          service_type: '',
          service_id: '',
          deduct_points: 0,
          starttime: '',
          timerange: '',
          payment: 'alipay',
          jobid: ''
        },
        mySetmeal:{},
        options_timerange:[],
        submitLock: false
      }
    },
    mounted(){
      this.initCB()
    },
    methods:{
      initCB(){
        this.fetchData()
        this.options_timerange = []
        for (let i = 1; i <= 12; i++) {
          this.options_timerange.push({ text: i + '小时', value: i * 3600 })
        }
        this.options_timerange.push({ text: '24小时', value: 24 * 3600 })
        this.options_timerange.push({ text: '48小时', value: 48 * 3600 })
      },
      fetchData () {
        http
          .get(api.company_servicelist, {type:this.type})
          .then(res => {
            this.dataset = []
            let list = res.data.items
            for (const iterator of list) {
              let expense_arr = iterator.expense.split('.')
              iterator.expense1 = expense_arr[0]
              iterator.expense2 = expense_arr[1]
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
            if(this.serviceId!==undefined){
              this.changeItem(this.serviceId)
            }else{
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
            }

            this.submitData.jobid = this.jobId===undefined?this.jobId:parseInt(this.jobId)
            if (
              this.submitData.jobid === undefined ||
              !this.submitData.jobid
            ) {
              this.submitData.jobid = 0
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
      changeItem(service_id){
        let item = null
        for (const iterator of this.dataset) {
          if(iterator.id==service_id){
            item = iterator
            break
          }
        }
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
          return false
        }
        if (
          this.submitData.service_type == 'refresh_job_package' &&
          this.submitData.timerange == ''
        ) {
          this.$message.error('请选择刷新时间间隔')
          return false
        }
        this.submitData.return_url =
          window.location.protocol +
          '//' +
          window.location.host +
          '/'+this.$store.state.config.member_dirname+'/company/service/order'
          let that = this
          if (!this.submitLock) {
            this.submitLock = true
            this.$refs.paySubmit.handlerSubmit(api.company_pay,this.submitData,function(){
              that.$emit('submitPay')
            })
          } else {
            console.log('点击过快')
          }
      },
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
