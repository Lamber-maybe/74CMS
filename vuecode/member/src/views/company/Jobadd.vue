<template>
	<div>
		<JobForm
			ref="child"
			v-if="showResult === false"
			type="add"
			:enable_addjob_num="enable_addjob_num"
			:enable_addjob_num_total="enable_addjob_num_total"
			@submit="submit"
		></JobForm>
		<div class="success" v-if="showResult === true">
			<el-card>
				<div class="success_text"><span>发布职位成功</span></div>
				<p>推荐使用职位置顶，立即提升招聘效果</p>
				<div class="btns_wrapper">
					<el-button type="primary" @click="$router.push('/company')">会员中心</el-button>
					<el-button @click="addAgain">再发一条</el-button>
					<el-button @click="$router.push('/company/joblist')">职位管理</el-button>
				</div>
				<div class="Topping_con" v-if="result_audit == 1">
					<div class="Topping_left">
						<p @click="handlerService(2)">置顶职位 <span>信息黄金位置展示，曝光率提升</span> <a>10倍 </a></p>
						<div class="Topping_days_con" >
						<div v-for="(item, index) in result_service_list" :key="index" @click="handlerService(item.id)">
							{{ item.days }}天
							<span v-if="item.recommend==1" class="recommend">推荐<i></i> </span>
						</div>
						</div>
					</div>
					<div class="Topping_right">
						<span></span>
						<div>
						<p>置顶效果</p>
						<div class="Topping_effect">
							<div class="Topping_box">
							<div class="Topping_box_bg1"></div>
							</div>
							<div class="Topping_line">
							<div class="Topping_box_bg1"></div>
							<div class="Topping_box_bg1"></div>
							<div class="Topping_box_bg1"></div>
							</div>
						</div>

						<div class="Topping_effect">
							<div class="Topping_box">
							<div class="Topping_box_bg2"></div>
							</div>
							<div class="Topping_line">
							<div class="Topping_box_bg2"></div>
							<div class="Topping_box_bg2"></div>
							<div class="Topping_box_bg2"></div>
							</div>
						</div>
						</div>

					</div>
					</div>
			</el-card>
		</div>
		<el-dialog  :title="'购买'+serviceTitle"  :visible.sync="showBuyService"  width="540px" @opened="initBuyService">
			<BuyIncrementDialog :job-id="result_id" :service-id="serviceId" ref="buyService" @submitPay="submitPay" :type="serviceType"></BuyIncrementDialog>
		</el-dialog>
		<el-dialog  title="支付提醒"  :visible.sync="showWaitingPay"  width="400px" >
			<span class="payment_text">请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。</span>
			<span slot="footer" class="dialog-footer">
			<el-button type="primary"  @click="handlerPaySuccess">支付成功</el-button>
			<el-button   @click="handlerPayFail">支付失败</el-button>
			</span>
		</el-dialog>

	</div>
</template>

<script>
import BuyIncrementDialog from '@/components/company/service/BuyIncrementDialog.vue'
import JobForm from '@/components/company/JobForm'
import http from '@/utils/http'
import api from '@/api'
	export default{
		components: {
			JobForm,
			BuyIncrementDialog
		},
		data(){
			return{
				showWaitingPay:false,
				showBuyService:false,
				serviceTitle:'',
				serviceType:'',
				serviceId:0,
				showResult: false,
				result_id: 0,
				result_audit: 0,
				result_service_list: [],
				enable_addjob_num_total: 0,
				enable_addjob_num: 0
			}
		},
		created () {
			this.fetchData()
		},
		methods:{
			addAgain () {
				this.fetchData()
				this.showResult = !this.showResult
			},
			fetchData () {
				http
					.get(api.company_check_jobadd_num, {})
					.then(res => {
					this.enable_addjob_num = res.data.enable_addjob_num
					this.enable_addjob_num_total = res.data.enable_addjob_num_total
					})
					.catch(() => {})
				http
					.get(api.company_jobadd_pre, {})
					.then(res => {
					this.$refs.child.field_rule = res.data.field_rule
					})
					.catch(() => {})
			},
			submit (values) {
				http
					.post(api.company_jobadd_save, values)
					.then(res => {
					this.result_id = res.data.id
					this.result_audit = [...res.data.audit]
					this.result_service_list = [...res.data.service_stick_list]
					this.showResult = true
					})
					.catch(() => {})
			},
			handlerService(service_id){
				this.serviceId = service_id
				this.serviceTitle = '职位置顶'
				this.serviceType = 'jobstick'
				this.showBuyService = true
			},
			initBuyService(){
				this.$refs.buyService.initCB()
			},
			submitPay(isFinish){
				this.showBuyService = false
				if(isFinish===true){
					this.showResult = false
					this.$router.push('/company/joblist')
				}else{
					this.showWaitingPay = true
				}
			},
			handlerPaySuccess(){
				this.showWaitingPay = false
				this.$router.push('/company/joblist')
			},
			handlerPayFail(){
				this.showWaitingPay = false
				this.showResult = false
			}
		},
	}
</script>

<style lang="scss" scoped>
	// 发布成功
	.success{
		height:670px;
		text-align: center;
	}
	.success .el-card{
		height: 100%;
	}
	.success_text{
		margin: 190px auto 0;padding-left: 66px;
    background: url("../../assets/images/bigSuccess.png") 0 center no-repeat;
    background-size: 50px 50px;
    height: 50px;
    line-height: 50px;
    width: fit-content;
	}
	.success_text span{
		color: #333333;
		font-size:29px;
	}
	.el-card p{
		color: #333333;
		font-size:18px;
		margin-top: 10px;
	}
	.btns_wrapper{
		margin-top: 48px;
	}
	.btns_wrapper .el-button{
		width:180px ;
		height: 42px;
		margin-right: 30px;
		margin-left: 0;
		// background: #1787fb;
	}
	.Topping_con{
		display: flex;
		width: 1050px;
		background: #fefce8;
		height: 150px;
		border: 1px solid #ffe6b5;
		margin:40px auto;
	}
	.Topping_left{
		width: 630px;
	}
	.Topping_left p{
		font-size: 18px;
	}
	.Topping_left p span{
		font-size: 14px;
		color: #666666;
	}
	.Topping_left p a{
		color: #ff4646;
		font-size: 14px;
	}
	.Topping_con>div{
		padding: 20px;
	}
	.Topping_days_con{
		display: flex;
		margin-top: 30px;
	}
	.Topping_con div p{
		margin-top: 10px;
    text-align: left;
	}
	.Topping_days_con>div{
		width: 100px;
		height: 38px;
		border: 1px solid #e2e2e2;
		line-height: 38px;
		text-align: center;
		margin-right: 20px;
		background: #fff;
		font-size: 14px;
		color: #666666;
		position: relative;
		cursor:pointer;
	}
	.recommend{
		width:34px ;
		height: 18px;
		background:#ff3030 ;
		position: absolute;
		right: -10px;
		top: -13px;
		line-height: 18px;
		font-size: 12px;
		color: #ffebeb;
		border-radius: 8px;
	}
	.recommend i{
		border-width: 0 0 5px 5px;
		border-style: solid;
		border-color: transparent transparent red;
		position: absolute;
		right: 10px;
		bottom: -5px;
		transform: rotate(170deg);
	}
	.Topping_right{
		flex: 1;
		display: flex;
	}
	.Topping_right span{
		display: inline-block;
		height: 90px;
		width: 1px;
		background: #e2e2e2;
		margin-right: 60px;
		margin-top: 14px;
	}
	.Topping_right p{
		color: #ff803b;
		font-size: 14px;
		padding-bottom: 5px;

	}
	.btn_wrapper{
		text-align: center;
	}
	.btn_wrapper .el-button{
		width:260px ;
		height: 42px;
		margin-top: 50px;
		margin-bottom: 20px;
	}
	.Topping_effect{
		display: flex;
		margin: 15px 0;
	}
	.Topping_box div{
		width: 30px;
		height: 20px;
	}
	.Topping_box_bg1{
		background: #ffe6b5;
	}
	.Topping_box_bg2{
		background: #e2e2e2;
	}
	.Topping_effect .Topping_line{
		width: 240px;
		height: 20px;
		margin-left: 15px;
	}
	.Topping_line div:nth-of-type(1){
		height: 5px;
		margin-bottom: 6px;
	}
	.Topping_line div:nth-of-type(2),
	.Topping_line div:nth-of-type(3){
		height: 2px;
	}
	.Topping_line div:nth-of-type(2){
		margin-bottom: 4px;
	}
</style>
