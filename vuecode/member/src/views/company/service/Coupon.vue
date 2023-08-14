<template>
	<div class="coupon">
		<el-card>
			<company-title>优惠券</company-title>
			<div class="coupon_wrapper" v-if="dataset.length>0">
				<div class="coupon_list" v-for="(item, index) in dataset" :key="index">
					<div class="coupon_text">
						<p>{{item.coupon_name}}</p>
						<span>仅限{{item.setmeal_name}}</span>
						<div class="time_warpper">
							<p>券编号：{{item.number}}</p>
							<p>有效期：{{ item.addtime | timeFilter }}-{{item.deadline | timeFilter}}</p>
						</div>
					</div>
					<div class="coupon_price">
						<div><span>￥</span>{{item.coupon_face_value}}</div>
						<el-button @click="$router.push('/company/service/setmeal/add?setmeal_id=' +item.coupon_bind_setmeal_id +'&coupon_id=' +item.id)">立即使用</el-button>
					</div>
					<p>
						<span></span>
						<span></span>
					</p>
				</div>
			</div>
          <div class="list_empty" v-else>
            <div class="emp_text">没有可用的优惠券~</div>
          </div>
		</el-card>
	</div>
</template>

<script>
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
	export default{
		filters: {
			timeFilter (timestamp) {
			return parseTime(timestamp, '{y}.{m}.{d}')
			}
		},
		data () {
			return { dataset: [] }
		},
		created () {
			this.fetchData()
		},
		methods: {
			fetchData () {
			http
				.get(api.coupon_list, {})
				.then(res => {
					this.dataset = [...res.data.items]
				})
				.catch(() => {})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.coupon{
		height: 786px;
	}
	.coupon .el-card{
		height: 786px;
	}
	.coupon_wrapper{
		display: flex;
		flex-wrap: wrap;
    margin-left: 15px;
	}
	.coupon_list{
		width:340px ;
		height: 171px;
		display: flex;
		margin-right: 30px;
		margin-bottom: 30px;
		background: #fffdfb;
		border-top: 6px solid #ffd1b2;
		position: relative;
   box-shadow: 0px 0px 3px 3px #fcfcfc;
	}
	.coupon_list>p{
		width: 100%;
		border-top: 1px dashed #e2e2e2;
		position: absolute;
		top: 90px;
	}
	.coupon_list>p span{
		width: 18px;
		height: 15px;
		display: inline-block;
		background: #fff;
		border-radius: 50%;
		position: absolute;
		top: -7px;
	}
	.coupon_list>p span:nth-of-type(1){
		left: -9px;
	}
	.coupon_list>p span:nth-of-type(2){
		right: -9px;
	}

	.coupon_text{
		flex: 2.5;
		padding-left: 25px;
	}
	.coupon_text>p{
		font-size:20px ;
		color: #ff803b;
		padding:16px 0;
	}
	.coupon_text>span{
		color: #666666;
		font-size: 14px;
		margin-bottom: 14px;
		display: inline-block;
	}
	.coupon_price{
		flex: 1;
		margin-right: 20px;
		text-align: right;
	}
	.coupon_price>div span{
		font-size: 12px;
		color: #ff6600;
	}
	.coupon_price .el-button{
		width: 80px;
		height: 28px;
		line-height: 28px;
		padding: 0;
		background: #ff6600;
		color:#ffeee9 ;
		font-size: 12px;
		border-radius: 15px;
		margin-top: 20px;
		border: 0;
		transition: all .3s;
		-webkit-transition: all .3s;
		&:hover {
			background: #e95f03;
		}
	}
	.time_warpper{
		margin-top: 25px;
	}
	.time_warpper>p{
		font-size:12px ;
		color: #999;
		margin: 6px 0;
	}
	.coupon_price>div{
		margin: 31px 0 21px 0 ;
		text-align: right;
		font-size: 36px;
		color: #ff6600;
	}
	.list_empty {
		width: 100%;
		height: 300px;
		background: #ffffff url('../../../assets/images/default_list_ico.png') center center no-repeat;
		border-bottom: 1px solid #f3f3f3;
		position: relative;
		.emp_text {
			color: #666;
			text-align: center;
			position: absolute;
			width: 100%;
			line-height: 22px;
			left: 0;
			bottom: 70px;
		}
	}
</style>
