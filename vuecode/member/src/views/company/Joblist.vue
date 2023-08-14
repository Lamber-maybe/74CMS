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
              </div>


						</div>
					</template>
				</el-table-column>
				<el-table-column header-align="center" align="center" label="收到简历">
					<template slot-scope='scope'>
						<div class="receivedNumber" style="cursor: pointer" @click="$router.push('/company/resume/jobapply?job_id='+scope.row.id+'&options_look=0')">
							<span>{{scope.row.resume_nolook}}</span>/{{scope.row.resume_all}}份
							<p>未查看简历</p>
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
            <div class="extensionBtns" >
              <el-button size="mini" type="danger" :disabled="scope.row.stick==1?true:false" @click="handlerService('jobstick',scope.row.id,scope.row.stick)">职位置顶</el-button>
              <el-button size="mini" type="primary" :disabled="scope.row.auto_refresh==1?true:false" @click="handlerService('job_refresh',scope.row.id,scope.row.auto_refresh)">智能刷新</el-button>
              <el-button size="mini" type="primary" :disabled="scope.row.emergency==1?true:false" @click="handlerService('emergency',scope.row.id,scope.row.emergency)">紧急招聘</el-button>
            </div>
					</template>
				</el-table-column>
			</el-table>
			<div class="btns">
				<el-button size="mini" @click="handlerRefreshBatch">刷新</el-button>
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
						<div class="receivedNumber"  style="cursor: pointer" @click="$router.push('/company/resume/jobapply?job_id='+scope.row.id+'&options_look=0')">
							<span>{{scope.row.resume_nolook}}</span>/{{scope.row.resume_all}}份
							<p>未查看简历</p>
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
						<div class="receivedNumber"  style="cursor: pointer" @click="$router.push('/company/resume/jobapply?job_id='+scope.row.id+'&options_look=0')">
							<span>{{scope.row.resume_nolook}}</span>/{{scope.row.resume_all}}份
							<p>未查看简历</p>
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
<!--            【ID1000223】【bug】PC企业会员中心职位管理-已下线职位修改，审核未通过的改为修改跳转修改页面-->
						<el-button size="mini" v-if="scope.row.audit==2" @click="$router.push('/company/jobedit/' + scope.row.id)">修改</el-button>
            <el-button size="mini" v-else @click="handlerDisplay(scope.row)">恢复</el-button>
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
  <el-dialog
        :visible.sync="showRefreshJobDirectService"
        width='480px'
        height="256"
        custom-class = 'download'
        title="刷新职位"
        center
        show-cancel-button
        :confirm-button-text="refreshJobDirectServiceInfo.btnCn"
        @confirm="handlerRefreshDirectService"
      >
        <div class="dialog_tip_wrapper">
          <div
            class="tx1"
            v-if="refreshJobDirectServiceInfo.use_type == 'points'"
          >
            今日免费刷新职位次数已用完，本次刷新需要支付
            <span class="red">{{ refreshJobDirectServiceInfo.need_points }}</span>
            {{ $store.state.config.points_byname }}。
          </div>
          <div class="tx1" v-if="refreshJobDirectServiceInfo.use_type == 'money'">
            今日免费刷新职位次数已用完，本次刷新需要支付
            <span class="red">{{
                refreshJobDirectServiceInfo.need_expense
              }}</span>
            元。
          </div>
          <div
            class="tx2"
            v-if="parseInt(refreshJobDirectServiceInfo.discount) > 0"
          >
            购买智能刷新低至<span class="red">{{
              refreshJobDirectServiceInfo.discount
            }}</span
          >折，<span style="cursor: pointer" @click="companyServiceDownloadResume" class="blue">(立即了解)</span>
          </div>
          <div class="payment-box tx3" v-if="refreshJobDirectServiceInfo.use_type == 'money'">
            <div  :class="[payment=='alipay'?'borders':'item']" @click="choosePayment('alipay')"><img class="imgs" src="../../assets/images/payment_icon_4.png"/>支付宝支付<i v-if="payment=='alipay'" class="el-icon-check imgi"></i></div>
            <div  :class="[payment=='wxpay'?'borders':'item']" @click="choosePayment('wxpay')"><img class="imgs" src="../../assets/images/payment_icon_5.png" />微信支付 <i v-if="payment=='wxpay'" class="el-icon-check imgi"></i></div>
          </div>
        </div>
        <div slot="footer" class="dialog-footer">
            <el-button type="primary" @click="submit">{{refreshJobDirectServiceInfo.btnCn}}</el-button>
            <el-button @click="showRefreshJobDirectService = false">取 消</el-button>
        </div>
  </el-dialog>
	</el-card>
	<Poster
		v-if="showPoster"
		:poster-id="posterId"
		type="job"
		@closeDialog="showPoster = false"
	/>

  <!-- 微信二维码弹窗 start -->
  <WeChatQrcode ref="weChatQrcodeRef"></WeChatQrcode>
  <!-- 微信二维码弹窗 end -->
	</div>
</template>

<script>
import BuyIncrementDialog from '@/components/company/service/BuyIncrementDialog.vue'
import http from '@/utils/http'
import api from '@/api'
import Poster from '@/components/Poster'
import WeChatQrcode from '@/components/WeChatQrcode'
	export default{
    components: {
      BuyIncrementDialog,
      Poster,
      WeChatQrcode
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
        showRefreshJobDirectService:false,
        refreshJobDirectServiceInfo:[],
        payment:'wxpay'
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
    mounted() {
      // 从编辑页面跳转到列表时触发
      if (this.$route.query.operate_type && this.$route.query.operate_type == 'edit') {
        /**
         * 【ID1000719】
         * 【新增】公众号引导弹窗场景（修改职位）
         * cy 2023-7-17
         */
        this.popupWechatQrcodeWindow('company_pc_save_job')
      }
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
                this.showRefreshJobDirectService = true
                this.refreshJobDirectServiceInfo = {
                  use_type: res.data.use_type,
                  need_points: res.data.need_points,
                  need_expense: res.data.need_expense,
                  discount: res.data.discount,
                  jobid: item.id,
                  btnCn: res.data.use_type === 'points' ? '立即兑换' : '立即支付'
                }
								return false
							} else {
								this.fetchData()
								this.$message({ type: 'success', message: res.message })
                /**
                 * 【ID1000719】
                 * 【新增】公众号引导弹窗场景（刷新职位）
                 * cy 2023-7-17
                 */
                this.popupWechatQrcodeWindow('company_pc_refresh_job')
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
              /**
               * 【ID1000719】
               * 【新增】公众号引导弹窗场景（刷新职位）
               * cy 2023-7-17
               */
              this.popupWechatQrcodeWindow('company_pc_refresh_job')
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
      handlerRefreshDirectService () {
        if (this.refreshJobDirectServiceInfo.use_type === 'points') {
          this.handlerSubmitRefreshJob('points')
        } else {
          this.showPayment = true
        }
      },
      submit(){
          this.handlerSubmitRefreshJob()
      },
      handlerSubmitRefreshJob () {
        let pay_data = {
          service_type: 'single_job_refresh',
          deduct_points:
            this.refreshJobDirectServiceInfo.use_type === 'points'
              ? this.refreshJobDirectServiceInfo.need_points
              : 0,
          payment:this.payment,
          jobid: this.refreshJobDirectServiceInfo.jobid,
          return_url:  window.location.protocol +
            '//' +
            window.location.host +
            '/'+this.$store.state.config.member_dirname+'/company/service/order',
        }
        http
          .post(api.company_pay_direct_service, pay_data)
          .then(res => {
            if (res.data.pay_status === 1) {
              this.$notify({ type: 'success', message: '支付成功' })
              this.showRefreshJobDirectService = false
              this.fetchData()
              this.fetchTotal()
              this.checkJobNum()
              return false
            } else {
              this.handlerPay(res)
            }
          })
          .catch(() => {})
      },
      choosePayment(payment){
        this.payment = payment
        this.$emit('choosePayment',this.payment)
      },
      handlerPay (res) {
        if(this.payment == 'wxpay'){
          window.open(this.$store.state.config.sitedomain+'/member/wxpay?parameter='+res.data.parameter+'&oid='+res.data.order_oid+'&amount='+res.data.order_amount+'&custom_location=0')
        }else{
          window.open(res.data.parameter)
        }
      },
      companyServiceDownloadResume(){
        window.open('/company/service/increment/add/job_refresh')
      },
      // 弹出微信二维码弹框
      popupWechatQrcodeWindow(val) {
        this.$refs.weChatQrcodeRef.handleOpen(val, '扫码绑定，随时随地接收简历信息')
      }
		}
	}
</script>

<style scoped lang="scss">
//企业会员中心-职位管理-生成海报错误修改
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
  .extensionBtns{
    .el-button{
      margin: 0 10px 10px 0;
    }
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

.download{
  font-size: 18px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  color: #373737;
  border-radius: 6px;
}
.tx1{
  font-weight: 400;
  font-size: 14px;
  line-height: 0px;   //企业会员中心-职位管理-生成海报错误修改
  text-align: center;
  padding-top: 20px;
}
.tx2{
margin-top: 15px;
font-weight: 400;
font-size: 14px;
text-align: center;
}
.red{
color:red;
}
.blue{
color:#409EFF;
}
.borders{
width: 150px;
height: 44px;
margin-right: 20px;
text-align: center;
font-size:14px;
color: #333333;
cursor: pointer;
position: relative;
user-select: none;
border: 2px solid #f60;
}
.payment-box{
display:flex;
line-height:42px;
margin-left: 55px;
margin-top:30px;
}
.payment-box .item{
width: 150px;
height: 44px;
border: 2px solid #e2e2e2;
margin-right: 20px;
text-align: center;
font-size:14px;
color: #333333;
cursor: pointer;
position: relative;
user-select: none;
}
.imgs{
vertical-align: middle;
margin-right: 3px;
}
.imgi{
width: 0;
height: 27px;
border-left:13px solid  transparent;
border-right:13px solid  #ff6600;
border-top:13px solid  transparent;
border-bottom:13px solid  #ff6600;
color: #fff2ea;
position: absolute;
right: 0;
bottom: 0;
text-align: right;
line-height: 13px;
padding-right: 1px;
}
</style>
