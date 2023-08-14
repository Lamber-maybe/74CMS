<template>
	<div>
		<el-card>
		<company-title>管理职位</company-title>
		<div class="menu_content">
			<menu-nav :active-tab="params.type" :list="navList"></menu-nav>
			<el-button icon="el-icon-plus" @click="handlerJobadd" :loading="btnLoading" >发布职位</el-button>
		</div>
      <el-alert :title="`亲爱的HR，您的帐号可同时发布 ${enable_addjob_num_total} 个职位，现已发布 ${enable_addjob_num_total-enable_addjob_num} 个(包含审核中和未通过的)职位。`"
                type="warning" :closable='false' show-icon> </el-alert>
		<div v-if="params.type==0">
			<el-table :header-cell-style="{background:'#fcfcfc',color:'#909399'}"  :data="dataset" v-loading="listLoading" @selection-change="handleSelectionChange">
				<el-table-column type="selection"></el-table-column>
				<el-table-column width="380" prop='jobname' label="职位名称">
					<template slot-scope="scope">
						<div class="name_text" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</div>
						<div class="time">更新时间：{{scope.row.refreshtime}} <a href="javascript:;" @click="handlerRefresh(scope.row)">[刷新]</a> </div>
						<div class="operation"><router-link :to="'/company/jobedit/' + scope.row.id">修改</router-link><router-link :to="'/company/recommend?id='+scope.row.id">匹配</router-link><a href="javascript:;" @click="handlerDisplay(scope.row)">关闭</a><a href="javascript:;" @click="handlerDel(scope.row)">删除</a></div>
					</template>
				</el-table-column>
				<el-table-column width="326" header-align="center" align="center" label="招聘情况">
					<template slot-scope="scope">
						<div class="situation">
              <div class="situation_box">
                <div>
                  <img src="../../assets/images/Publishing_icon_1.png" />被投递{{scope.row.resume_all}}次
                </div>
                <div>
                  <img src="../../assets/images/Publishing_icon_2.png" />被浏览{{scope.row.click}}次
                </div>
              </div>

						</div>
						<div class="situation">
              <div class="situation_box">
                <div class="btn" @click="handlerShare(scope.row)">
                  <img src="../../assets/images/Publishing_icon_3.png" />分享到朋友圈
                </div>
                <div class="btn" @click="handlerMicroposte(scope.row)">
                  <img src="../../assets/images/Publishing_icon_4.png"/>开启微海报
                </div>
              </div>


						</div>
					</template>
				</el-table-column>
				<el-table-column header-align="center" align="center" label="收到简历">
					<template slot-scope='scope'>
						<div class="receivedNumber">
							<span>{{scope.row.resume_nolook}}</span>/{{scope.row.resume_all}}份
							<p>待处理简历</p>
						</div>
					</template>
				</el-table-column>
				<el-table-column header-align="center" align="center" label="职位状态">
					<template slot-scope='scope'>
						<div class="state_title">{{ scope.row.job_status_cn }}</div>
					</template>
				</el-table-column>
				<el-table-column width="248" header-align="center"  label="职位推广">
					<template slot-scope='scope'>
						<el-button size="mini" type="danger" :disabled="scope.row.stick==1?true:false" @click="handlerService('jobstick',scope.row.id,scope.row.stick)">职位置顶</el-button>
						<el-button size="mini" type="primary" :disabled="scope.row.auto_refresh==1?true:false" @click="handlerService('job_refresh',scope.row.id,scope.row.auto_refresh)">智能刷新</el-button>
						<el-button size="mini" type="primary" :disabled="scope.row.emergency==1?true:false" @click="handlerService('emergency',scope.row.id,scope.row.emergency)">紧急招聘</el-button>
					</template>
				</el-table-column>
			</el-table>
			<div class="btns">
				<el-button size="mini" @click="handlerRefreshBatch">刷新</el-button>
				<el-button size="mini" @click="handlerMicroposteBatch">生成海报</el-button>
				<el-button size="mini" @click="handlerCloseBatch">关闭</el-button>
				<el-button size="mini" @click="handlerDelBatch">删除</el-button>
			</div>
		</div>
		<div v-if="params.type==1">
			<el-table :header-cell-style="{background:'#fcfcfc',color:'#909399',fontSize:'12px'}"  :data="dataset" v-loading="listLoading" @selection-change="handleSelectionChange">
				<el-table-column type="selection"></el-table-column>
				<el-table-column width="369" label="职位名称">
					<template slot-scope="scope">
						<div class="name_text" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</div>
						<div class="time">更新时间：{{scope.row.refreshtime}}</div>
					</template>
				</el-table-column>
				<el-table-column header-align="center" align="center" label="收到简历">
					<template slot-scope='scope'>
						<div class="receivedNumber">
							<span>{{scope.row.resume_nolook}}</span>/{{scope.row.resume_all}}份
							<p>待处理简历</p>
						</div>
					</template>
				</el-table-column>
				<el-table-column width="300" label="职位状态">
					<template slot-scope='scope'>
						<div class="state_title">{{ scope.row.job_status_cn }}</div>
					</template>
				</el-table-column>
				<el-table-column width="252" header-align="center" align="center" label="操作">
					<template slot-scope='scope'>
						<el-button size="mini" @click="$router.push('/company/jobedit/' + scope.row.id)" >修改</el-button>
						<el-button size="mini" @click="handlerDel(scope.row)">删除</el-button>
					</template>
				</el-table-column>
			</el-table>
			<div class="btns">
				<el-button size="mini" @click="handlerDelBatch">删除</el-button>
			</div>
		</div>
		<div v-if="params.type==2">
			<el-table :header-cell-style="{background:'#fcfcfc',color:'#909399',fontSize:'12px'}"  :data="dataset" v-loading="listLoading" @selection-change="handleSelectionChange">
				<el-table-column type="selection"></el-table-column>
				<el-table-column width="369" label="职位名称">
					<template slot-scope="scope">
						<div class="name_text" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</div>
						<div class="time">更新时间：{{scope.row.refreshtime}}</div>
						<div class="err">{{scope.row.audit_reason}}</div>
					</template>
				</el-table-column>
				<el-table-column header-align="center" align="center" label="收到简历">
					<template slot-scope='scope'>
						<div class="receivedNumber">
							<span>{{scope.row.resume_nolook}}</span>/{{scope.row.resume_all}}份
							<p>待处理简历</p>
						</div>
					</template>
				</el-table-column>
				<el-table-column width="300" label="职位状态">
					<template slot-scope='scope'>
						<div class="state_title">{{ scope.row.job_status_cn }}</div>
					</template>
				</el-table-column>
				<el-table-column width="252" header-align="center" align="center" label="操作">
					<template slot-scope='scope'>
						<el-button size="mini" @click="handlerDisplay(scope.row)">恢复</el-button>
						<el-button size="mini" @click="handlerDel(scope.row)">删除</el-button>
					</template>
				</el-table-column>
			</el-table>
			<div class="btns">
				<el-button size="mini" @click="handlerDelBatch">删除</el-button>
			</div>
		</div>

		<pagination
			:total="total"
			:current-page="params.page"
			:page-size="params.pagesize"
			@handleCurrentChange="doSearch"
		></pagination>

    <el-dialog title="分享到朋友圈"  :visible.sync="showShare"  width="30%" >
      <div class="share_box">
        <div>
          <img class="qrcode" :src="shareQrcode"/>
        </div>
        <p>微信扫码分享到朋友圈</p>
        <p>让朋友帮你转，招人更快更简单</p>
      </div>
    </el-dialog>
	<el-dialog :title="'购买'+serviceTitle" :visible.sync="showBuyService" v-if="showBuyService" width="540px">
		<BuyIncrementDialog ref="buyService" @submitPay="submitPay" :type="serviceType" :job-id="jobId"></BuyIncrementDialog>
	</el-dialog>
	</el-card>
	<Poster
		v-if="showPoster"
		:poster-id="posterId"
		type="job"
		@closeDialog="showPoster = false"
	/>
	</div>
</template>

<script>
import BuyIncrementDialog from '@/components/company/service/BuyIncrementDialog.vue'
import http from '@/utils/http'
import api from '@/api'
import Poster from '@/components/Poster'
	export default{
		components:{
			BuyIncrementDialog,
			Poster
		},
		data(){
			return{
				showBuyService:false,
				serviceTitle:'',
				serviceType:'',
				jobId:0,
				showPoster: false,
				posterId: '',
				btnLoading:false,
				multipleSelection:[],
				listLoading:false,
				total:0,
				enable_addjob_num_total: 0,
				enable_addjob_num: 0,
				navList: [
					{ label: '发布中', href: '/company/joblist', name: '0', active: true },
					{ label: '审核中', href: '/company/joblist?type=1', name: '1', active: false },
					{ label: '已下线', href: '/company/joblist?type=2', name: '2', active: false },
				],
				dataset: [],
				params: {
					type: 0,
					page: 1,
					pagesize: 10
				},
				showShare:false,
				shareQrcode:'',
				enable_poster:0,
			}
		},
		watch:{
			'$route':function(newVal){
				this.params.type = newVal.query.type===undefined?'0':newVal.query.type
				this.params.page = 1
				this.dataset = []
				this.fetchData(true)
			}
		},
		created () {
			this.params.type = this.$route.query.type===undefined?'0':this.$route.query.type
			this.params.page = 1
			this.fetchData(true)
		},
		methods:{
			handlerOuterLink(url){
				window.open(url)
			},
			submitPay(){
				this.showBuyService = false
				this.fetchData(true)
			},
			handlerService(type,jobid,hasBuy){
				if(hasBuy==1){
					return false
				}
				if(type=='jobstick'){
					this.serviceTitle = '职位置顶'
				}else if(type=='job_refresh'){
					this.serviceTitle = '职位智能刷新'
				}else if(type=='emergency'){
					this.serviceTitle = '职位紧急'
				}
				this.jobId = jobid
				this.serviceType = type
				this.showBuyService = true
			},
			fetchData (init) {
				this.listLoading = true
				http
					.get(api.company_joblist, this.params)
					.then(res => {
						this.dataset = [...res.data.items]
						this.enable_poster = res.data.enable_poster
						this.listLoading = false
						if(init===true){
							this.fetchTotal()
							this.checkJobNum()
						}
					})
					.catch(() => {})
			},
			checkJobNum () {
				http
					.get(api.company_check_jobadd_num, {})
					.then(res => {
						this.enable_addjob_num_total = res.data.enable_addjob_num_total
						this.enable_addjob_num = res.data.enable_addjob_num
					})
					.catch(() => {})
			},
			fetchTotal () {
				http
					.get(api.company_joblist_total, this.params)
					.then(res => {
						this.total = res.data
					})
					.catch(() => {})
			},
			doSearch(page,initPagination) {
				this.params.page = page
				this.fetchData()
				if(initPagination===true){
					this.fetchTotal()
				}
			},
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			handlerJobadd () {
			this.btnLoading = true
			http
				.get(api.company_check_jobadd_num, {})
				.then(res => {
				this.btnLoading = false
				if (res.data.enable_addjob_num <= 0) {
					this.$confirm('您当前是' +
						res.data.setmeal_name +
						'，当前可发布职位数为0，建议您立即升级套餐或将暂时不招聘的职位设为关闭！','系统提示',{
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
					return false
				} else {
					this.$router.push('/company/jobadd')
				}
				})
				.catch(() => {})
			},
			handlerRefresh (item) {
				this.$confirm('确定刷新该职位吗？','系统提示',{
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				})
				.then(() => {
					http
						.post(api.company_job_refresh, { id: item.id })
						.then(res => {
							if (res.data.done === 0) {
								this.$message.error(res.message)
								return false
							} else {
								this.fetchData()
								this.$message({ type: 'success', message: res.message })
							}
						})
						.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			handlerDisplay (item) {
			let is_display = item.is_display === 1 ? 0 : 1
			let is_display_text = is_display === 1 ? '恢复' : '关闭'
			this.$confirm('确定' + is_display_text + '该职位吗？','系统提示',{
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				})
				.then(() => {
				http
					.post(api.company_job_set_display, { id: item.id, is_display })
					.then(res => {
						this.fetchData()
						this.fetchTotal()
						this.checkJobNum()
            if (parseInt(res.code) === 200) {
              this.$message({ type: 'success', message: is_display_text + '成功' })
            }
					})
					.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			handlerDel (item) {
			this.$confirm('确定删除该职位吗？','系统提示',{
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				})
				.then(() => {
				http
					.post(api.company_job_del, { id: item.id })
					.then(res => {
						this.fetchData()
						this.fetchTotal()
						this.checkJobNum()
						this.$message({ type: 'success', message: res.message })
					})
					.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			handlerRefreshBatch () {
				if(this.multipleSelection.length===0){
					this.$message.error('请选择职位')
					return false
				}
				this.$confirm('确定刷新选中的职位吗？','系统提示',{
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
					})
					.then(() => {
						let jobarr = []
						this.multipleSelection.forEach(element => {
							jobarr.push(element.id)
						});
					http
						.post(api.company_job_refresh_batch, {id:jobarr})
						.then(res => {
						if (res.data.done === 0) {
							this.$confirm(res.message,'系统提示',{
								confirmButtonText: '确定',
								cancelButtonText: '取消',
								type: 'warning'
							})
							.then(() => {
							})
							.catch(() => {
								// on cancel
							})
							return false
						} else {
							this.fetchData()
							this.$message({ type: 'success', message: res.message })
						}
						})
						.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			handlerDelBatch () {
				if(this.multipleSelection.length===0){
					this.$message.error('请选择职位')
					return false
				}
				this.$confirm('确定删除选中的职位吗？','系统提示',{
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
					})
					.then(() => {
						let jobarr = []
						this.multipleSelection.forEach(element => {
							jobarr.push(element.id)
						});
					http
						.post(api.company_job_del_batch, {id:jobarr})
						.then(res => {
						if (res.data.done === 0) {
							this.$confirm(res.message,'系统提示',{
								confirmButtonText: '确定',
								cancelButtonText: '取消',
								type: 'warning'
							})
							.then(() => {
							})
							.catch(() => {
								// on cancel
							})
							return false
						} else {
							this.fetchData()
							this.fetchTotal()
							this.checkJobNum()
							this.$message({ type: 'success', message: res.message })
						}
						})
						.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			handlerCloseBatch () {
				if(this.multipleSelection.length===0){
					this.$message.error('请选择职位')
					return false
				}
				this.$confirm('确定关闭选中的职位吗？','系统提示',{
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
					})
					.then(() => {
						let jobarr = []
						this.multipleSelection.forEach(element => {
							jobarr.push(element.id)
						});
					http
						.post(api.company_job_set_close_batch, {id:jobarr})
						.then(res => {
						if (res.data.done === 0) {
							this.$confirm(res.message,'系统提示',{
								confirmButtonText: '确定',
								cancelButtonText: '取消',
								type: 'warning'
							})
							.then(() => {
							})
							.catch(() => {
								// on cancel
							})
							return false
						} else {
							this.fetchData()
							this.fetchTotal()
							this.checkJobNum()
							this.$message({ type: 'success', message: res.message })
						}
						})
						.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			handlerShare2(item){
				this.showShare = true
				this.shareQrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url='+encodeURIComponent(this.$store.state.config.mobile_domain + 'job/'+item.id)
			},
			handlerShare(item) {
				this.showPoster = true
				this.posterId = item.id
			},
			handlerMicroposte(item){
				if(this.enable_poster==0){
					this.$message.error('您当前的套餐不允许使用微海报，请先升级套餐')
					return false
				}
				this.$router.push('/company/microposte?jobid='+item.id)
			},
			handlerMicroposteBatch(){
				if(this.multipleSelection.length===0){
					this.$message.error('请选择职位')
					return false
				}
				if(this.enable_poster==0){
					this.$message.error('您当前的套餐不允许使用微海报，请先升级套餐')
					return false
				}
				let jobarr = []
				this.multipleSelection.forEach(element => {
					jobarr.push(element.id)
				});
				this.$router.push('/company/microposte?jobid='+jobarr.join(','))
			}
		}
	}
</script>

<style scoped lang="scss">
  .el-alert {
    padding: 16px;margin-bottom: 20px;
  }
	.menu_content{
		position: relative;
	}
	.menu_content .el-button{
		position: absolute;
		right: 0;
		top: 0;
		background: #ff7859;
		color: #fff8f6;
		margin-right: 20px;border: 0;
		&:hover {
			background: #f06f52;
		}
	}
	.Tips_text{
		padding: 16px 0;
		font-size: 14px;
		color: #846d39;
		background: #fefce8;
		margin-bottom: 20px;
	}
	.Tips_text img{
		vertical-align: middle;
		margin: 0  10px 0  20px;
	}
	.state_title{
		font-size:12px ;
		color: #4a88f7;
		background: #ecf5ff;
		padding: 1px 12px;
		border: 1px solid #d9ecff;
		border-radius: 5px;
		display: inline-block;
	}
	.el-button{
		margin: 0 10px 10px 0;
	}
	.btn_bg{
		background: #f56c6c;
		color: #ffffff;
	}
	.name_text{
		font-size: 16px;cursor: pointer;color: #333;
    &:hover { text-decoration: underline; }
	}
	.time{
		font-size: 12px;
		color: #999;
	}
  .err {
    color: #F56C6C;
  }
	.time a, .operation a{
		color: #1787fb;
		font-size: 12px;
		margin-right: 7px;
	}

  .situation_box{
    display: flex;
    margin: 0 45px;
  }

	.situation .btn{
		cursor:pointer;
	}

  .situation_box div{
    font-size: 12px;
    color: #999;
  }

	.situation_box div:nth-of-type(1){
		width:110px;
		position: relative;
		padding-left: 20px;
		text-align: left;
	}
	.situation_box div:nth-of-type(2){
		width:110px;
		position: relative;
		padding-left: 20px;
		text-align: left;
	}

	.situation_box div img{
		vertical-align: middle;
		position: absolute;
		left: 0;
		top: 50%;
		transform: translate(0, -50%)
	}
	.receivedNumber{
		font-size:14px ;
		color: #666666;
	}
	.receivedNumber span{
		color: #ff704f;
		font-size: 14px;
	}
	.receivedNumber p{
		font-size: 12px;
		color: #666666;
	}
	.btns{
		margin-top: 20px;
	}
	.btns .el-checkbox{
		padding: 0 15px;
	}

  // 弹框
  .share_box{
    text-align: center;
	padding: 36px 0  54px 0;
	.qrcode{
		height:124px;
		width:124px;
	}
  }
  .share_box p:nth-of-type(1){
    margin-top: 30px;
    color:#666666 ;
    font-size: 16px;
  }
  .share_box p:nth-of-type(2){
    margin-top: 15px;
    color: #b0b0b0;
    font-size:13px ;
  }
</style>
