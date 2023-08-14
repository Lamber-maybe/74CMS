<template>
	<el-card>
		<menuheader>面试邀请</menuheader>
		<div class="stations">
			<span class="resume_header_font">面试职位：</span>
			<el-select  placeholder="请选择" v-model="params.jobid" @change="doSearch(1,true)">
			<el-option  v-for="(item,index) in options_job"  :key="index" :label="item.text"  :value="item.value"></el-option>
			</el-select>
			<div class="seestate">
				发送时间：
				<el-select  placeholder="全部" v-model="params.settr" @change="doSearch(1,true)">
				<el-option  v-for="(item,index) in options_settr"  :key="index"   :label="item.text"  :value="item.value"></el-option>
				</el-select>
			</div>
		</div>

		<el-table :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#b8babd'}"  v-loading="listLoading">
			<el-table-column width="280"  header-align="left"   align="left" label="基本信息">
				<template slot-scope="scope">
					<div class="information_wrapper">
						<div class="portraitImg">
							<img :src="scope.row.photo_img_src" />
						</div>
						<div class="information_list">
              <div class="fn_bar">
                <a class="name a-link" :href="scope.row.resume_link_url_web" target="_blank">{{scope.row.fullname}}</a>
                <div class="hq" v-if="scope.row.high_quality > 0"></div>
                <div class="clear"></div>
              </div>
							<p class="information">{{scope.row.age}}岁 | {{scope.row.education_text}} | {{scope.row.experience_text}} | {{scope.row.sex_text}}
							</p>
						</div>
					</div>
				</template>
			</el-table-column>
			<el-table-column label="面试职位" align="center">
				<template slot-scope="scope"  >
					<a class="stations_fonts a-link" :href="scope.row.job_link_url_web" target="_blank">{{scope.row.jobname}}</a>
				</template>
			</el-table-column>
			<el-table-column header-align="center" align="center" width="200px" label="面试时间">
				<template slot-scope="scope"  >
          <div class="time">{{scope.row.interview_time | interviewTimeFilter}}</div>
				</template>
			</el-table-column>
			<el-table-column header-align="center" align="center"  label="操作状态">
				<template slot-scope="scope">
					<div class="btnsColor1 btn" v-if="scope.row.is_look==0"  type="warning" size="mini">未查看</div>
					<div class="btnsColor2 btn" v-if="scope.row.is_look==1" type="primary" size="mini">已查看</div>
				</template>
			</el-table-column>
			<el-table-column header-align="center" align="center" width="150"   label="操作">
				<template slot-scope="scope">
					<el-button size="mini" type="primary" @click="handlerDetailShow(scope.row)">详情</el-button>
				</template>
			</el-table-column>
		</el-table>
		<pagination
			:total="total"
			:current-page="params.page"
			:page-size="params.pagesize"
			@handleCurrentChange="doSearch"
		></pagination>
    <el-dialog width='540px'  title="面试详情"  :visible.sync="showDetail" >
      <InterviewDetail :info="info"></InterviewDetail>
      <span slot="footer" class="dialog-footer">
        <el-button  type="primary" @click="showDetail = false">确 定</el-button>
        <el-button  @click="showDetail = false">取 消</el-button>
      </span>
    </el-dialog>

	</el-card>
</template>

<script>
	import { parseTime } from '@/utils/index'
	import http from '@/utils/http'
	import api from '@/api'
	import menuheader from './components/menuHeader.vue'
  import InterviewDetail from './components/InterviewDetail.vue'
	export default{
		components:{
			menuheader,
			InterviewDetail
		},
		filters: {
			interviewTimeFilter (timestamp) {
			return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
			}
		},
		data() {
			return {
				showDetail:false,
				info:{},
				listLoading:false,
				dataset: [],
				total: 0,
				params: {
					jobid: 0,
					settr: '',
					page: 1,
					pagesize: 10,
				},
				options_job: [{ text: '全部', value: 0 }],
				options_settr: [
					{ text: '不限', value: '' },
					{ text: '3天内', value: 3 },
					{ text: '7天内', value: 7 },
					{ text: '15天内', value: 15 },
					{ text: '30天内', value: 30 }
				],
			}
		},
		created() {
			this.fetchData(true)
		},
		methods: {
			handlerDetailShow(item){
				this.info = item
				this.showDetail = true
			},
			fetchData(init) {
				this.listLoading = true
				http
				.get(api.company_interview_list, this.params)
				.then((res) => {
					let options_job = [...res.data.option_jobs]
					options_job.forEach(element => {
					this.options_job.push({
						text: element.jobname,
						value: element.id
					})
					})
					this.dataset = [...res.data.items]
					this.listLoading = false
					if(init===true){
						this.fetchTotal()
					}
				})
				.catch(() => {})
			},
			fetchTotal(){
				http
					.get(api.company_interview_list_total,this.params)
					.then((res) => {
						this.total = res.data
					})
					.catch(() => {})
			},
			doSearch(page,initPagination) {
				this.params.page = page
				this.fetchData(initPagination)
			},
		},
	}
</script>

<style lang="scss" scoped>
.a-link{
  cursor:pointer;
}
.a-link:hover{
  text-decoration:underline;
  color:#1787fb;
}
	.browsing_wrapper{
		display: flex;
		line-height: 40px;
		margin-bottom: 27px;
	}
	.header_input{
		width:  305px;
	}
	.browsing_wrapper>p{
		flex: 1;
		font-size: 17px;
		color: #333333;
		font-weight: bold;
	}
	.stations{
		background: #f9f9f9;
		padding: 25px 0 30px 25px;
	}
	.seestate{
		display: inline-block;
		margin-left:46px;
	}
	.resume_header_font{
		font-size: 14px;
		color: #666666;
	}
	.el-select{
		width: 259px;
	}
	.el-checkbox{
		margin-left: 20px;
	}



	.information_wrapper{
		display: flex;

	}
	.portraitImg{
		flex: 1;
		margin-right: 10px;
		border-radius: 50%;
	}
	.portraitImg img{
		border-radius: 50%;
		width:52px;
		height:52px;
	}
	.information_list{
		flex: 8;
	}

  .information_list .name{
    color:#333 ;
    font-size: 16px;
  }
  .information_list .fn_bar .name{
  display: block;
  float: left;
  height: 20px;
  line-height: 20px;
  cursor:pointer;
  color: #333;
  font-size: 16px;
  }
  .information_list .fn_bar .hq {
  float: left;
  margin-left: 10px;
  width: 45px;
  height: 20px;
  background: url("../../../assets/images/great.png") 0 center no-repeat;
  }
  .information_list p{
    color: #999;
    font-size: 14px;
    margin-top: 6px;
  }

	.information_list img{
		vertical-align: sub;
	}
	.el-pagination{
		text-align: center;
		margin-top: 20px;
	}
	.intention_post , .intention_local{
		font-size: 14px;
		color: #bfbfbf;
	}
	.intention_post span,.intention_local span{
		color: #666666;
	}
	.del_btn {
		margin-left: 14px;
		margin-top: 20px;
	}
	.del_btn .el-button{
		margin-left: 15px;
	}
	.stations_fonts {
		font-size: 14px;
		color: #666;
	}

  .time{
    color: #666;
    font-size: 14px;
  }

	.btn{
		width: 60px;
		height: 24px;
		display: inline-block;
		font-size: 12px;
	}
	.btnsColor1{
		background: #fdf6ec;
		color: #e6a451;
	}
	.btnsColor2{
		background: #ecf5ff;
		color: #4a88f7;
	}
	.btnsColor3{
		background: #fef0f0;
		color: #f68b8b;
	}
</style>
