<template>
	<el-card>
		<menuheader>视频面试</menuheader>
		<div class="warning">
			<p><img src="../../../assets/images/warning_icon.png"/>为提高视频面试效率，建议您在面试前提前检查您的设备是否能够正常连通  <a :href="test_link" target="_blank">点击立即检测>></a>  </p>
			<p>视频面试房间仅在面试当天开启，在面试时间15天之后自动关闭</p>
		</div>
		<el-table  :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#b8babd'}" v-loading="listLoading">
			<el-table-column  header-align="left"   align="left" label="基本信息">
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
			<el-table-column header-align="center"  align="center" prop="jobname" label="面试职位">
				<template slot-scope="scope"  >
					<a class="a-link interview_post" target="_blank" :href="scope.row.job_link_url_web">{{scope.row.jobname}}</a>
				</template>
			</el-table-column>
			<el-table-column header-align="center" align="center" label="面试时间">
				<template slot-scope="scope"  >
          <div class="time">
            {{scope.row.interview_time | interviewTimeFilter}}
          </div>
				</template>
			</el-table-column>
			<el-table-column width="210" header-align="center" prop="tagger_bts" label="操作">
				<template slot-scope="scope">
					<div v-if="scope.row.room_status=='nostart'" >
						<el-button size="mini" type="primary" @click="handlerDetailShow(scope.row)">查看详情</el-button>
						<el-button size="mini">房间未开启</el-button>
					</div>
					<div v-if="scope.row.room_status=='overtime'" >
						<el-button size="mini" type="primary" @click="handlerDetailShow(scope.row)">查看详情</el-button>
						<el-button size="mini">房间已关闭</el-button>
					</div>
					<div v-if="scope.row.room_status=='opened'" class="operationBtn">
						<el-button size="mini" type="primary" @click="handlerDetailShow(scope.row)">查看详情</el-button>
						<el-button size="mini" type="primary" @click="enterRoom(scope.row.id)">进入房间</el-button>
						<el-button size="mini" type="primary" @click="copyUrl(scope.row.link_url)">复制链接</el-button>
						<el-button size="mini" type="danger" @click="handlerNotice(scope.row.id)">提醒上线</el-button>
					</div>
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
      <VideoDetail :info="info"></VideoDetail>
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
	import VideoDetail from './components/VideoDetail.vue'
	export default{
		components:{
			menuheader,
			VideoDetail
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
				multipleSelection:[],
				dataset: [],
				total: 0,
				params: {
					page: 1,
					pagesize: 10,
				},
				test_link:'',
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
				.get(api.company_interview_video_list, this.params)
				.then((res) => {
					this.dataset = [...res.data.items]
					this.test_link = res.data.test_link
					this.listLoading = false
					if(init===true){
						this.fetchTotal()
					}
				})
				.catch(() => {})
			},
			fetchTotal(){
				http
					.get(api.company_interview_video_list_total,this.params)
					.then((res) => {
						this.total = res.data
					})
					.catch(() => {})
			},
			doSearch(page) {
				this.params.page = page
				this.fetchData()
			},
			handlerNotice (id) {
			this.$confirm('确定提醒求职者吗？','系统提示',{type:'warning'})
				.then(() => {
				http
					.post(api.company_interview_video_notice, { id })
					.then(() => {
					this.$message({
						type: 'success',
						message: '提醒成功'
					})
					})
					.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			copyUrl (url) {
				var oInput = document.createElement('input')
				oInput.value = url
				document.body.appendChild(oInput)
				oInput.select() // 选择对象
				document.execCommand('Copy') // 执行浏览器复制命令
				oInput.className = 'oInput'
				oInput.style.display = 'none'
				this.$message({type:'success',message:'复制成功'})
			},
      enterRoom (id) {
        this.$router.push('/video/' + id)
      }
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
.warning{
	background: #fefce8;
	color: #795f21;
	font-size: 12px;
	padding: 10px 0 10px 20px;
	margin-bottom: 13px;
}
.warning p{
	padding: 5px 0;
}
.warning p a{
	color: #1787fb;
}
.warning p:nth-of-type(1)img{
	vertical-align: middle;
	margin-right: 10px;
}
.warning p:nth-of-type(2){
	margin-left: 26px;
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
.information_list .fn_bar .name{
  display: block;float: left;height: 20px;line-height: 20px;
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

.information_list .information{
  color:#999 ;
  font-size: 14px;
  margin-top: 6px;
}
.interview_post{
  color: #666;
  font-size: 14px;
}
.time{
  color: #666;
  font-size: 14px;
}

.el-pagination{
	text-align: center;
	margin-top: 20px;
}
.operationBtn .el-button{
	margin: 5px 10px 5px 0;
}
</style>
