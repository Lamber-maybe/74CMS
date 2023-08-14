<template>
	<div>
		<el-card>
			<div class="setmeal_header">
				<div class="header_left" >
					<div class="header_img">
						<img :src="companyInfo.logo_src" alt="logo" class="logo" />
					</div>
					<div class="header_title">
						<div class="text_1">{{companyInfo.companyname}}</div>
						<p class="text_2">我的套餐：<span>{{ mySetmeal.name }}</span>
						<router-link to="/company/service/setmeal/detail">我的特权></router-link>
						</p>
						<p class="text_3">服务期限：<span v-if="mySetmeal.deadline == 0">无限期</span><span v-else>有效期至 {{ mySetmeal.deadline | timeFilter }}</span></p>
					</div>
				</div>
				<div class="header_right">
					<span></span>
					<div class="right_item">
						<p>职位并发数</p>
						<div>
							<span>{{ mySetmeal.jobs_meanwhile }} </span>
							条
						</div>
					</div>

          <div class="right_item">
            <p>简历剩余点数</p>
            <div>
              <span>{{ mySetmeal.download_resume_point }} </span>
              点
            </div>
          </div>
					<div class="right_item">
						<p>职聊剩余次数</p>
						<div>
							<span>{{ mySetmeal.im_total }} </span>
							次
						</div>
					</div>

					<div class="right_item">
						<p>免费刷新次数</p>
						<div>
							<span>{{ mySetmeal.refresh_jobs_free_perday }} </span>
							次/天
						</div>
					</div>

					<div class="right_item">
						<p>我的{{$store.state.config.points_byname}}</p>
						<div>
							<span>{{ myPoints }} </span>
							{{$store.state.config.points_quantifier}}
						</div>
					</div>

				</div>
			</div>
		</el-card>
		<div class="upgradesetmeal_con">
			<el-card>
        <company-title>请选择套餐</company-title>
        <div class="waning_text">
          <img src="../../../assets/images/warning_icon.png" />
          在当前套餐未结束前，购买新的套餐，将获得套餐时间{{$store.state.config.reopen_setmeal_deadline==0?'不':''}}叠加，资源{{$store.state.config.reopen_setmeal_resource==0?'不':''}}叠加，权限以新购买的会员为准
        </div>
        <div class="item_wrapper">
          <div class="item_con">
            <div ref="item_conRef" class="item_com_cti">
              <div class="item" v-for="(item,index) in setmealList" :key="index" :ref="'itemDom' + index">
                <div class="item_title">
                  <img v-if="item.recommend==1" class="recommend" :src="require('@/assets/images/tuijian1.png')" />
                <!--<p :style="item.preferential_open==1?'padding:13px 0;':''">
                  {{item.name}}<br />
                  <span v-if="item.preferential_open==1">限时特惠</span>
                </p>-->
                  <p class="st_name">{{item.name}}</p><p class="st_day">{{ parseInt(item.days) > 0 ? item.days + '天' : '无限期' }}</p>
                  <div class="ellipse"></div>
                </div>
                <div class="item_box">
                  <div class="Price">
                    <p>￥<span>{{item.expense}}</span></p>
                  </div>
                  <p class="or_price" v-if="parseInt(item.preferential_open) === 1">原价: ￥{{ item.original_expense }}</p>
                  <div class="btn">
                    <el-button class="el_button" @click="buyNow(item)">立即购买</el-button>
                  </div>
                  <p class="or_pre_date" v-if="parseInt(item.preferential_open) === 1">
                    限时特惠 : {{ item.preferential_expense_start | timeFilterTwo }} 至 {{ item.preferential_expense_end | timeFilterTwo }}
                  </p>
                </div>
                <div class="details">
                <div class="border"></div>
                  <p><span>职位并发数</span><span>{{item.jobs_meanwhile}}条</span></p>
                  <p><span>赠送简历点数</span><span>{{item.download_resume_point}}点</span></p>
                  <p><span>增值服务折扣</span><span v-if="item.service_added_discount>0">{{item.service_added_discount}}折</span><span v-else>无折扣</span></p>
                  <div class="type" @mouseover="hover(item,index)" @mouseout="removehove(item,index)">
                    <span class="type_left">查看更多特权</span>
                    <span class="type_right"><img src="../../../assets/images/upgradeSetmeal_icon.png" /></span>
                    <div :class="[hoverIndex?'padding2':'padding','hover_box']" v-show="item.show_box">
                      <span :class="[hoverIndex?'arrow_right':'arrow_left','arrow']"></span>
                      <div>
                        <div class="hover_title">套餐资源</div>
                        <ul>
                          <li>职位并发数：<span>{{item.jobs_meanwhile}}</span></li>
                          <li>赠送简历点数：<span>{{item.download_resume_point}}</span></li>
                          <li>职聊次数：<span>{{item.im_total}}</span></li>
                          <li>赠送会员{{$store.state.config.points_byname}}：<span>{{item.gift_point}}</span></li>
                          <li>免费刷新职位：<span>{{item.refresh_jobs_free_perday}}次/天</span></li>
                          <li>下载简历上限：<span>{{item.download_resume_max_perday}}份/天</span></li>
                          <li>每日职聊上限：<span>{{item.im_max_perday}}次/天</span></li>
                        </ul>
                      </div>
                      <div v-if="item.service_added_discount>0 || item.enable_poster==1 || item.enable_video_interview==1 || item.show_apply_contact==1 || item.note!=''">
                        <div class="hover_title">套餐特权</div>
                        <ul>
                          <li v-if="item.service_added_discount>0">套餐增值包折扣{{item.service_added_discount}}折</li>
                          <li v-if="item.enable_poster==1">允许使用微海报</li>
                          <li v-if="item.enable_video_interview==1">允许使用视频面试</li>
                          <li v-if="item.show_apply_contact==1">收到简历免费查看</li>
                        </ul>
                        <div class="hover_otd" v-if="item.note!=''">
                          <span>其他说明：</span><span class="dec">{{item.note}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <span class="el-icon-arrow-right" v-if="false" @click="next()"></span>
        </div>
			</el-card>
		</div>
	</div>
</template>

<script>
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
  export default{
	filters: {
		timeFilter (timestamp) {
			if(timestamp===undefined){
				return ''
			}
			return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
		},
    timeFilterTwo (timestamp) {
      return parseTime(timestamp, '{m}-{d}')
    }
	},
    data(){
      return{
		companyInfo:{},
		mySetmeal: {},
		myPoints:0,
		setmealList:[],
    hoverIndex:false,
        index:0,
        startNum: 5
      }
    },
	created () {
		this.fetchSetmeal()
	},
    mounted() {
      console.log(this.$refs)
    },
    methods: {

      // 【bug】后台企业套餐设置显示开关和是否允许申请开关关系
    buyNow(item) {
      if (item.is_apply === 1) {
        this.$router.push('/company/service/setmeal/add?setmeal_id='+item.id)
      }else {
          this.$message({
            message: '此套餐暂不允许购买',
            type: 'error',
          });
      }
    },
		fetchSetmealList () {
			this.setmealList = []
		http
			.get(api.company_setmeallist, {})
			.then(res => {
				let list = res.data.items
				list.forEach(element => {
					element.show_box = false
					this.setmealList.push(element)
				});
			})
			.catch(() => {})
		},
		fetchSetmeal () {
		http
			.get(api.member_setmeal, {})
			.then(res => {
			this.mySetmeal = res.data.info
			this.companyInfo = res.data.company_info
			this.myPoints = res.data.points
			this.fetchSetmealList()
			})
			.catch(() => {})
		},
		hover(item,index) {
			item.show_box = true
      // 根据当前 item 距右侧距离是否大于 hover_box 的宽度，改变 hover_box 显示位置
      let itemDom = eval("this.$refs.itemDom" + index)[0]
      let docWidth = document.documentElement.clientWidth
      let itemDomOff = itemDom.getBoundingClientRect()
      this.hoverIndex = (docWidth - itemDomOff.right) < 450
		},
		removehove(item) {
			item.show_box = false
		},
		next () {
			if (this.index >= this.setmealList.length - 1 || this.startNum === this.setmealList.length) {
			this.index = 0
        this.startNum = 5
			} else {
			this.index++
        this.startNum++
			}
			this.$refs.item_conRef.style.transform = `translate3d(-${267*this.index}px, 0px, 0px)`
		}
	}
  }
</script>

<style lang="scss" scoped>
	.el-card{
		margin-bottom: 20px;
	}
	.setmeal_header{
		display: flex;
		padding: 10px 0;
	}
	.header_left{
		width: 488px;
		display: flex;
	}
	.header_img{
		// flex: 1;
		width:100px;
		margin-top:24px;
		.logo{
			width:80px;
			height:80px;
		}
	}
	.header_title{
		flex: 5;
	}
	.header_title>div{
		font-size: 18px;
		color: #333333;
		margin: 20px 0;
	}
	.header_title>p{
		font-size:15px ;
		color: #666;
	}
	.header_title>p:nth-of-type(1) a{
		color: #1787fb;
		font-size: 14px;
		margin-left: 44px;
	}
  .header_title .text_1{
    margin: 20px 0 15px;
    font-weight: bold;
  }
  .header_title .text_2{
    margin-bottom:12px ;
  }
  .header_title .text_3{
    margin-bottom: 12px;
  }
	.header_right{
		flex: 1;
		display: flex;
	}
	.header_right>span{
		height: 90px;
		background: #f3f3f3;
		width: 1px;
		display: inline-block;
		margin-top: 36px;
		margin-right: 60px;
	}
	.right_item{
		text-align: center;
		flex: 1;
	}
	.right_item >p{
		font-size: 16px;
		color: #666666;
		margin: 30px 0 18px 0;
	}
	.right_item >div{
		color: #ff704f;
		font-size: 16px;
		letter-spacing:-2px;
	}
	.right_item >div>span{
		font-size: 24px;
	}
  .upgradesetmeal_con {
    .el-card {
      height: 100%;
    }
    .waning_text {
      font-size: 14px;
      color: #795f21;
      background: #fefce8;
      padding: 19px 0;
    }
    .waning_text img {
      margin-left: 20px;
      vertical-align: middle;
    }
    .item_wrapper {
      margin-top: 15px;
      position: relative;
      margin-left: 40px;
    }
    .item_con {
      padding-top: 10px;
    }
    .item_com_cti {
    }
    .item_wrapper>span {
      width: 40px;
      height: 392px;
      background: #fff;
      display: block;
      font-size: 16px;
      position: absolute;
      right: 0;
      top: 50%;
      margin-top: -224px;
      cursor: pointer;
      line-height: 392px;
    }
    .item {
      float: left;
      width: 227px;
      height: 409px;
      background: #e9bc79;
      border-radius: 10px;
      box-shadow: 0 0 5px 5px #f1f1f1;
      position: relative;
      margin: 0 22px 44px 22px;
      transition: linear all .2s;
      &:hover {
      -webkit-transform:translateY(-3px);transform:translateY(-3px);z-index:1;
      }
    }
    .item_title {
      text-align: center;
      position:relative;
    }
    .item_title .recommend{
      position:absolute;
      top:0;
      right:0;
    }
    .item_title  {
      .st_name { padding: 18px 0 5px;font-size: 18px;color: #fff;font-weight: bold; }
      .st_day { font-size: 14px;color: #92672c;padding-bottom: 14px; }
    }
    .or_price {
      position: absolute;left: 0;top: 138px;width: 100%;font-size: 12px;color: #999999;text-decoration: line-through;
    }
    .or_pre_date {
      position: absolute;left: 0;top: 209px;width: 100%;font-size: 12px;color: #92672c;
    }
    .item_title>p span {
      font-size: 12px;
    }
    .ellipse {
      height: 14px;
      background: #fff;
      border-radius: 50% / 100% 100% 0 0;
    }
    .item_box {
      background: #fff;
      text-align: center;
    }
    .Price>p {
      font-size: 14px;
      color: #333333;
      padding-bottom: 12px;
    }
    .Price>p>span:nth-of-type(1) {
      font-size: 36px;
    }
    .Price>p>span:nth-of-type(2) {
      color: #999999;
      font-size: 14px;
    }
    .btn {
      padding-bottom: 25px;
    }
    .el_button {
      width: 130px;
      border-radius: 24px;
      background: #e8c487;
      font-size: 14px;
      color: #9f753b;
      margin: 10px 0;
      border: 0;
      transition: all .3s;
      -webkit-transition: all .3s;
      &:hover {
        background: #d4b37a;
      }
    }
    .details {
      padding-bottom: 22px;
      background: #fff;
      border-radius: 0 0px 10px 10px;
    }
    .details>p {
      padding: 10px 20px;
      display: flex;
      font-size: 12px;
      color: #666666;
    }
    .type_left, .type_right {
      flex: 1;
    }
    .type_left {
      text-align: left;
    }
    .type_right {
      text-align: right;
    }
    .border {
      margin-bottom: 10px;
      border: 1px solid #f3f3f3;
    }
   .type {
      color: #ff704f;
      padding: 10px 20px;
      display: flex;
      font-size: 12px;
    }
    .hover_box {
      width: 400px;
      background: #fff;
      z-index: 10;
      box-shadow: 0 0 5px 3px #ccc;
      padding: 0 20px 40px;
      border-radius: 5px;
    }
    .hover_otd {
      font-size: 14px;color: #666;word-break: break-all;line-height: 1.8;
      .dec {color: #333;}
    }
    .padding{
      position: absolute;
      right: -400px;
      bottom: -1px;
    }
    .padding2{
      position: absolute;
      right: 42px;
      bottom: -1px;
    }
    .hover_box .arrow {
      display: block;
      width: 10px;
      border-top: 5px solid transparent;
      border-right: 10px solid transparent;
      border-bottom: 5px solid transparent;
      border-left: 10px solid transparent;
    }
    .hover_box .arrow_left{
      position: absolute;
      bottom: 73px;
      left: -15px;
      border-right:10px solid #fff ;
    }
    .hover_box .arrow_right{
      position: absolute;
      bottom: 73px;
      right: -15px;
      border-left:10px solid #fff ;
    }
    .hover_title {
      padding: 30px 0 15px;
      font-size: 18px;
      color: #333333;
      font-weight: bold;
    }
    .hover_box ul {
      display: flex;
      flex-wrap: wrap;
    }
    .hover_box ul .order_explain{
      width: 100%;
    }
    .hover_box ul li {
      width: 180px;
      font-size: 14px;
      color: #666;
    }
    .hover_box ul li span {
      font-size: 14px;
      color: #333333;
    }
    .dialog_foter_box{
      text-align: center;
      padding:40px 20px;
    }
    .dialog_foter_box .el-button{
      width: 260px;
      height: 42px;
    }
}



</style>
