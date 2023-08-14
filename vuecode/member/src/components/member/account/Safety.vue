<template>
	<div class="safety">
      <warning title="温馨提示：手机号、邮箱认证通过后可增强您账号的安全性，也可通过已认证的手机号、邮箱快速登录帐号。如需申请注销请"></warning>
      <a @click="openCancellation" :class= "$store.state.LoginType == 1 ? 'clickBtn1' : 'clickBtn2'">点击</a>

    <ul class="information_list">
			<li>
				<div class="name">
					<img src="../../../assets/images/username.png" />用户名
				</div>
				<div class="nameId">{{username}}</div>
				<div class="btn">
					<el-button type="primary" @click="openResetUsername">修改</el-button>
				</div>
			</li>
			<li>
				<div class="name">
					<img src="../../../assets/images/password.png" />密码
				</div>
				<div class="nameId">上次登录时间：{{last_login_time}}</div>
				<div class="btn">
					<el-button type="primary" @click="openResetPassword">{{is_set_password==0?'设置':'修改'}}</el-button>
				</div>
			</li>
			<li v-if="$store.state.LoginType == 1">
				<div class="name">
					<img src="../../../assets/images/comauth.png" />企业认证
				</div>
				<div class="nameId" v-if="company_auth==1"><img src="../../../assets/images/done.png" class="done"/>您已通过企业认证</div>
				<div class="nameId" v-else>认证企业提升企业信誉，您可以选择营业执照认证或负责人认证</div>
				<div class="btn">
					<el-button type="primary" @click="$router.push('/company/auth')">{{company_auth==0?'立即认证':'查看'}}</el-button>
				</div>
			</li>
			<li>
				<div class="name">
					<img src="../../../assets/images/mobileauth.png" />手机认证
				</div>
				<div class="nameId"><img src="../../../assets/images/done.png" class="done"/>您已认证：{{mobile}}</div>
				<div class="btn">
					<el-button type="primary" @click="openResetMobile">修改</el-button>
				</div>
			</li>
			<li>
				<div class="name">
					<img src="../../../assets/images/emailauth.png" />邮箱认证
				</div>
				<div class="nameId" v-if="email!=''"><img src="../../../assets/images/done.png" class="done"/>您已认证：{{email}}</div>
				<div class="nameId" v-else>认证后可使用邮箱登录，邮箱找回登录密码</div>
				<div class="btn">
					<el-button type="primary" @click="openResetEmail">{{email?'修改':'立即认证'}}</el-button>
				</div>
			</li>
		</ul>
		<div class="binding_warpper">
			<div class="binding_title">账号绑定  <span>(授权绑定后，可使用第三方帐号快速登录)</span></div>
			<ul class="information_list">
				<li>
					<div class="name">
						<img src="../../../assets/images/weixin.png" />
						微信
					</div>
					<div class="nameId2" v-if="bind_weixin==1"><img src="../../../assets/images/done.png" class="done"/>您已绑定：{{bind_weixin_nickname?bind_weixin_nickname:'微信帐号'}}</div>
					<div class="nameId2" v-else>绑定后可使用微信快速登录，随时接收面试通知</div>
					<div class="btn">
						<el-button type="primary" @click="handlerBindWeixin">{{bind_weixin==1?'解除绑定':'立即绑定'}}</el-button>
					</div>
				</li>
				<li>
					<div class="name">
						<img src="../../../assets/images/qq.png" />
						QQ
					</div>
					<div class="nameId2" v-if="bind_qq==1"><img src="../../../assets/images/done.png" class="done"/>您已绑定：{{bind_qq_nickname?bind_qq_nickname:'QQ帐号'}}</div>
					<div class="nameId2" v-else>绑定后可使用QQ快速登录网站</div>
					<div class="btn">
						<el-button type="primary" v-if="bind_qq==1" @click="handlerBindQq">解除绑定</el-button>
						<el-button type="primary" v-else @click="handlerBindQq">立即绑定</el-button>
					</div>
				</li>
			</ul>
		</div>


    <el-dialog title="修改用户名" :visible.sync="showResetUsername" width="450px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
       <ResetUsername ref="resetUsername" :username-original="username" @closeDialog="closeResetUsername"></ResetUsername>
    </el-dialog>
    <el-dialog title="修改密码" :visible.sync="showResetPassword" width="450px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
       <ResetPassword ref="resetPassword" :is-set-password="is_set_password" @closeDialog="closeResetPassword"></ResetPassword>
    </el-dialog>
    <el-dialog title="修改手机" :visible.sync="showResetMobile" width="450px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
       <ResetMobile ref="resetMobile" :mobile-original="mobile" @closeDialog="closeResetMobile"></ResetMobile>
    </el-dialog>
    <el-dialog title="修改邮箱" :visible.sync="showResetEmail" width="450px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
       <ResetEmail ref="resetEmail" :email-original="email" @closeDialog="closeResetEmail"></ResetEmail>
    </el-dialog>
    <el-dialog title="绑定微信" :visible.sync="showBindWeixin" width="450px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
       <BindWeixin></BindWeixin>
    </el-dialog>
    <el-dialog :title=dialog_title
               :visible.sync="cancellation" width="647px"
               :destroy-on-close="true">
      <Cancellation
        :utype=$store.state.LoginType
        :mobile=mobile
        :phone=phone
        ref="cancellation"
        @closeDialog="closeResetCancellation">
      </Cancellation>
    </el-dialog>
	</div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import warning from '@/components/Warning'
import ResetUsername from './components/ResetUsername.vue'
import ResetPassword from './components/ResetPassword.vue'
import ResetMobile from './components/ResetMobile.vue'
import ResetEmail from './components/ResetEmail.vue'
import BindWeixin from './components/BindWeixin.vue'
import Cancellation from './components/Cancellation.vue'

	export default{
		name: 'AccountSafety',
		props: ['utype'],
		data(){
			return{
				showFeedback: false,
				showWeixinQrcode: false,
				is_set_password: 0,
				company_auth: 0,
				company_auth_text: '',
				username: '',
				mobile: '',
				email: '',
				last_login_time:'',
				bind_qq: 0,
				bind_sina: 0,
				bind_weixin: 0,
				bind_weixin_nickname:'',
				bind_qq_nickname:'',
				showResetUsername: false,
				showResetPassword: false,
				showResetMobile: false,
				showResetEmail: false,
				showBindWeixin:false,
        cancellation: false,
        phone: '',
        dialog_title:'注销企业账号',
			}
		},
		components:{
			warning,
			ResetUsername,
			ResetPassword,
			ResetMobile,
			ResetEmail,
			BindWeixin,
      Cancellation
		},
		created () {
			this.fetchData()
      if (this.$store.state.LoginType === 1) {
        this.dialog_title = '注销企业账号'
      } else {
        this.dialog_title = '注销个人账号'
      }
		},
		methods:{
			openResetUsername () {
				this.showResetUsername = true
			},
			closeResetUsername (username) {
				this.username = username
				this.showResetUsername = false
			},
			openResetPassword () {
				this.showResetPassword = true
			},
			closeResetPassword (is_set_password) {
				this.is_set_password = is_set_password
				this.showResetPassword = false
			},
			openResetMobile () {
				if (this.mobile != '') {
					this.$confirm('您的手机已认证，修改认证手机您的登录手机号将同步修改，是否继续？','系统提示',{type:'warning'})
					.then(() => {
						this.showResetMobile = true
					})
					.catch(() => {
						// on cancel
					})
				} else {
					this.showResetMobile = true
				}
			},
			closeResetMobile (mobile) {
				this.mobile = mobile
				this.showResetMobile = false
			},
			openResetEmail () {
				this.showResetEmail = true
			},
			closeResetEmail (email) {
				this.email = email
				this.showResetEmail = false
			},
			fetchData () {
				http
					.post(api.member_account, {})
					.then(res => {
					this.is_set_password = res.data.is_set_password
					this.company_auth = res.data.company_auth
					this.company_auth_text = res.data.company_auth_text
					this.username = res.data.username
					this.mobile = res.data.mobile
					this.email = res.data.email
					this.last_login_time = res.data.last_login_time
					this.bind_qq = res.data.bind_qq
					this.bind_sina = res.data.bind_sina
					this.bind_weixin = res.data.bind_weixin
					this.bind_weixin_nickname = res.data.bind_weixin_nickname
					this.bind_qq_nickname = res.data.bind_qq_nickname
          const tel = this.mobile
          this.phone = tel.replace(/(\d{3})\d{4}(\d{4})/, '$1****$2')
					})
					.catch(() => {})
			},
			handlerBindWeixin(){
				if(this.$store.state.config.wechat_login_open==0){
					this.$alert('暂不支持此功能，如需使用请联系网站客服。', '提示', {
						confirmButtonText: '确定',
						type: 'warning',
						callback: () => {}
					});
					return false
				}
				if(this.bind_weixin==0){
					this.showBindWeixin = true
				}else{
					this.$confirm('解绑后无法使用微信快速登录网站，无法接收面试通知，是否确定解绑？','系统提示',{type:'warning'})
					.then(() => {
						http
							.post(api.unbind, {type:'weixin'})
							.then(() => {
								this.fetchData()
								this.$message({type:'success',message:'解绑成功'})
								this.showBindWeixin = false
							})
							.catch(() => {})

					})
					.catch(() => {
						// on cancel
					})
				}
			},
			handlerBindQq () {
				if(this.$store.state.config.account_qqlogin_open==0){
					this.$alert('暂不支持此功能，如需使用请联系网站客服。', '提示', {
						confirmButtonText: '确定',
						type: 'warning',
						callback: () => {}
					});
					return false
				}
				if(this.bind_qq==0){
					const {href} = this.$router.resolve({path: '/oauth/qq/bind',});
					window.open(href);
				}else{
					this.$confirm('解绑后无法使用QQ快速登录网站，是否确定解绑？','系统提示',{type:'warning'})
					.then(() => {
						http
							.post(api.unbind, {type:'qq'})
							.then(() => {
								this.fetchData()
								this.$message({type:'success',message:'解绑成功'})
							})
							.catch(() => {})
					})
					.catch(() => {
						// on cancel
					})
				}
			},
      openCancellation(){
        this.cancellation = true
      },
      closeResetCancellation () {
        this.cancellation = false
      }
		}
	}
</script>

<style scoped lang="scss">
  .safety {
    position: relative;
  }
  .clickBtn1 {
    position: absolute;
    top: 12px;
    left: 758px;
    color: #E6A23C;
    cursor: pointer;
  }
  .clickBtn2 {
    position: absolute;
    top: 12px;
    right: 127px;
    color: #E6A23C;
    cursor: pointer;
  }
	.information_list li{
		line-height: 91px;
		display: flex;
		height: 91px;
		background: #fcfcfc;
		margin-bottom: 20px;
	}
	.name{
		// flex: 2;
		width:146px;
		margin-left: 42px;
    color: #333;
    font-size: 16px;
    position: relative;padding-left: 30px;
	}
	.name img{
		position: absolute;left: 0;top: 35px;
	}
	.nameId{
		flex: 3;
		font-size: 14px;
		color:#666666;
		position: relative;
		padding-left: 30px;
    overflow: hidden;
		.done{
			position: absolute;left: 8px;top: 38px;
		}
	}

  .nameId2{
    flex: 3;
    font-size: 14px;
    color:#999;
	position: relative;
	padding-left: 30px;
	.done{
		position: absolute;left: 8px;top: 38px;
	}
  }

	.btn{
		flex: 3;
		text-align: right;
		margin-right: 81px;
	}
	.btn .el-button{
		width: 160px;
		border: 1px solid #1787fb;
		transition: all .3s;
		-webkit-transition: all .3s;
	}


	.binding_title{
		font-size: 18px;
		color: #333333;
		padding: 20px 0;
	}
	.binding_title span{
		font-size:14px;
		color: #999999;
	}
	.binding_title>div{
		padding-top: 20px;
		margin: 20px 0;
	}
  .el-dialog .dialog-footer {
    display: inline-block;
    height: 15px;
  }
</style>
