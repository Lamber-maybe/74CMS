<template>
	<div>
		<el-card>
			<div class="setmeal_header">
				<div class="header_left" >
					<div class="header_img">
						<img :src="companyInfo.logo_src" alt="logo" class="logo" />
					</div>
					<div class="header_title">
						<div>{{companyInfo.companyname}}</div>
						<p>我的套餐：<span>{{ mySetmeal.name }}</span>
						</p>
						<p>服务期限：<span v-if="mySetmeal.deadline == 0">无限期</span><span v-else>有效期至 {{ mySetmeal.deadline | timeFilter }}</span></p>
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
						<p>剩余职聊次数</p>
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

		<el-card>
			<menu-nav :active-tab="currentTab" :list="navList" :disableRouterPush="true"></menu-nav>
			<div class="privilege_wrapper" v-if="currentTab=='info'">
        <div class="privilege_line">
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_1.png"/>
            </div>
            <p>免费刷新职位次数</p>
            <span>{{ mySetmeal.refresh_jobs_free_perday }}次/天</span>
          </div>
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_2.png"/>
            </div>
            <p>下载简历数上限</p>
            <span>{{ mySetmeal.download_resume_max_perday }}份/天</span>
          </div>
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_5.png"/>
            </div>
            <p>职聊次数上限</p>
            <span>{{ mySetmeal.im_max_perday }}次/天</span>
          </div>
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_3.png"/>
            </div>
            <p>增值服务专享折扣</p>
            <span v-if="mySetmeal.service_added_discount > 0">{{ mySetmeal.service_added_discount }} 折</span>
            <span v-else>无折扣</span>
          </div>
        </div>
				<div class="privilege_line">
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_4.png"/>
            </div>
            <p>收到的简历免费查看</p>
            <span>{{ mySetmeal.show_apply_contact == 1 ? "支持" : "暂不支持" }}</span>
          </div>
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_7.png"/>
            </div>
            <p>微海报</p>
            <span>{{ mySetmeal.enable_poster == 1 ? "支持" : "暂不支持" }}</span>
          </div>
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_6.png"/>
            </div>
            <p>视频面试</p>
            <span>{{ mySetmeal.enable_video_interview == 1 ? "支持" : "暂不支持" }}</span>
          </div>
          <div class="privilege_list">
            <div class="list_img">
              <img src="../../../assets/images/privilege_icon_8.png"/>
            </div>
            <p>专属金牌客服</p>
            <span>客服快速响应</span>
          </div>
        </div>
			</div>
			<div class="setmeallog" v-if="currentTab=='log'">
				<el-table :header-cell-style="{background:'#fcfcfc',color:'#999ba1'}"   :data="dataset"  style="width: 100%">
					<el-table-column prop="content"  label="操作类型"  ></el-table-column>
					<el-table-column label="时间" width="170">
						<template slot-scope='scope'>
							{{ scope.row.addtime | timeFilter }}
						</template>
					</el-table-column>
				</el-table>
				<pagination
					:total="total"
					:current-page="params.page"
					:page-size="params.pagesize"
					@handleCurrentChange="doSearch"
				></pagination>
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
			if(timestamp===undefined){
				return ''
			}
			return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
		}
	},
    data(){
      return{
		dataset:[],
		total: 0,
		params: {
			page: 1,
			pagesize: 10,
		},
		companyInfo:{},
		mySetmeal: {},
		myPoints:0,
		currentTab:'info',
        navList: [
            { label: '我的特权', href: '/company/service/setmeal/detail', name: 'info', active: true },
            { label: '套餐使用明细', href: '/company/service/setmeal/detail?tab=log', name: 'log', active: false },
          ],
      }
    },
	watch:{
		'$route':function(newVal){
			this.currentTab = newVal.query.tab===undefined?'info':'log'
		}
	},
	created () {
		this.currentTab = this.$route.query.tab===undefined?'info':'log'
		this.fetchSetmeal()
	},
	methods: {
		fetchSetmeal () {
		http
			.get(api.member_setmeal, {})
			.then(res => {
			this.mySetmeal = res.data.info
			this.companyInfo = res.data.company_info
			this.myPoints = res.data.points
			this.fetchSetmealLog(true)
			})
			.catch(() => {})
		},
		fetchSetmealLog (init) {
		http
			.get(api.company_setmeallog, this.params)
			.then(res => {
				this.dataset = [...res.data.items]
				if(init===true){
					this.fetchSetmealLogTotal()
				}
			})
			.catch(() => {})
		},
		fetchSetmealLogTotal () {
		http
			.get(api.company_setmeallog_total, this.params)
			.then(res => {
				this.total = res.data
			})
			.catch(() => {})
		},
		doSearch(page) {
			this.params.page = page
			this.fetchSetmealLog()
		},
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
		margin-top:30px;
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
	.header_title p{
		margin-bottom: 20px;
		font-size:15px ;
		color: #adadad;
	}
	.header_title p:nth-of-type(1) a{
		color: #1787fb;
		font-size: 15px;
		margin-left: 44px;
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
		font-size: 15px;
		color: #666666;
		margin: 30px 0 23px 0;
	}
	.right_item >div{
		color: #ff704f;
		font-size: 15px;
		letter-spacing:-2px;
	}
	.right_item >div>span{
		font-size: 24px;
	}

.privilege_wrapper{
    flex-wrap: wrap;
    text-align: center;
    margin-bottom: 30px;
  }
.privilege_line {
  display: flex;
}
  .privilege_list{
    flex: 1;
      height: 200px;
      background: #fff7ec;
      margin: 0 30px 30px 0;
    transition: linear all .2s;
    &:hover {
      -webkit-transform:translateY(-5px);transform:translateY(-5px);z-index:1;
    }
  }
  .privilege_list p{
    font-size: 17px;
    color: #5d5c5b;
    margin-top: 17px;
    margin-bottom: 10px;
  }
  .privilege_list span{
    color: #ff704f;
    font-size: 14px;
  }
  .list_img{
    margin-top: 40px;
  }
  .setmeallog{
      height: 780px;
  }
</style>
