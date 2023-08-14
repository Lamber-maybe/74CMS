<template>
	<div id="app">
		<Head>账号管理</Head>
		<div class="form_split_10"></div>
		<van-field readonly clickable label="修改用户名" :value="username" :placeholder="username"
			class="form_choose reset_read_after" input-align="right" @click="openResetUsername" />
		<van-field readonly clickable label="修改密码" placeholder="" class="form_choose reset_read_after no_border"
			input-align="right" @click="openResetPassword" />
		<div class="form_split_10"></div>
		<van-field v-if="$store.state.LoginType == 1" readonly clickable label="企业认证"
			:value="company_auth == 1 ? company_auth_text : ''" :placeholder="company_auth_text"
			class="form_choose reset_read_after" input-align="right" @click="handlerCompanyAuth" />
		<van-field readonly clickable label="手机认证" :value="mobile ? mobile : ''" placeholder="未绑定"
			class="form_choose reset_read_after" input-align="right" @click="openResetMobile" />
		<van-field readonly clickable label="绑定邮箱" :value="email ? email : ''" placeholder="未绑定"
			class="form_choose reset_read_after" input-align="right" @click="openBindEmail" />
		<div class="box_title">第三方账号绑定后可以使用绑定的账号快速登录网站</div>
		<van-field readonly clickable label="腾讯QQ" :value="bind_qq == 1 ? '已绑定' : ''" placeholder="未绑定"
			class="form_choose reset_read_after" input-align="right" @click="handlerBindQq" />
		<van-field readonly clickable label="微信" :value="bind_weixin == 1 ? '已绑定' : ''" placeholder="未绑定"
			class="form_choose reset_read_after no_border" input-align="right" @click="handlerBindWeixin" />
		<div class="form_split_10"></div>
		<van-field readonly clickable label="系统消息" value="" placeholder="" class="form_choose reset_read_after"
			input-align="right" @click="
        $router.push(
          '/member/' + (utype == 1 ? 'company' : 'personal') + '/msglist'
        )
      " />
		<van-field readonly clickable label="登录日志" value="" placeholder="" class="form_choose reset_read_after"
			input-align="right" @click="
        $router.push(
          '/member/' + (utype == 1 ? 'company' : 'personal') + '/loginlog'
        )
      " />
		<van-field readonly clickable label="建议反馈" value="" placeholder="" class="form_choose reset_read_after"
			input-align="right" @click="openFeedback" />
		<div class="form_split_10" v-if="$store.state.LoginType===1"></div>
		<van-field v-if="$store.state.LoginType===1" readonly clickable label="注销账号" value="" placeholder=""
			class="form_choose reset_read_after no_border" input-align="right" @click="handlerWithdraw" />
		<div class="form_split_10"></div>
		<div style="margin: 16px;">
			<van-button round block type="info" @click="logout">
				退出登录
			</van-button>
		</div>
		<van-popup :lazy-render="false" v-model="showResetUsername" position="right" :overlay="false"
			style="width:100%;height:100%">
			<ResetUsername ref="resetUsername" :username_original="username" @closePopout="closeResetUsername">
			</ResetUsername>
		</van-popup>
		<van-popup :lazy-render="false" v-model="showResetPassword" position="right" :overlay="false"
			style="width:100%;height:100%">
			<ResetPassword ref="resetPassword" :is_set_password="is_set_password" @closePopout="closeResetPassword">
			</ResetPassword>
		</van-popup>
		<van-popup :lazy-render="false" v-model="showResetMobile" position="right" :overlay="false"
			style="width:100%;height:100%">
			<ResetMobile ref="resetMobile" :mobile_original="mobile" @closePopout="closeResetMobile"></ResetMobile>
		</van-popup>
		<van-popup :lazy-render="false" v-model="showBindEmail" position="right" :overlay="false"
			style="width:100%;height:100%">
			<BindEmail ref="bindEmail" :email_original="email" @closePopout="closeBindEmail"></BindEmail>
		</van-popup>
		<van-popup :lazy-render="false" v-model="showFeedback" position="right" :overlay="false"
			style="width:100%;height:100%">
			<Feedback ref="feedback" @closePopout="showFeedback = false"></Feedback>
		</van-popup>
		<van-dialog v-model="showWeixinQrcode" title="微信认证" confirm-button-text="知道了">
			<div class="dialog_wx_qr">
				<div class="tip orange">截屏保存二维码图片，关注公众号</div>
				<div class="qr_img"><img v-if="scanQrcodeImg" :src="scanQrcodeImg" /></div>
				<div class="tip_text">微信内使用“扫一扫”打开图片，识别关注公众号完成绑定操作。绑定后即可随时随地接收最新消息通知，还可使用微信快速登录网站。</div>
			</div>
		</van-dialog>
	</div>
</template>

<script>
import Feedback from '@/components/account/Feedback'
import ResetUsername from '@/components/account/ResetUsername'
import ResetPassword from '@/components/account/ResetPassword'
import ResetMobile from '@/components/account/ResetMobile'
import BindEmail from '@/components/account/BindEmail'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'MemberAccount',
  props: ['utype'],
  components: {
    ResetUsername,
    ResetPassword,
    ResetMobile,
    BindEmail,
    Feedback
  },
  data () {
    return {
      showFeedback: false,
      showWeixinQrcode: false,
      is_set_password: 0,
      company_auth: 0,
      company_auth_text: '',
      username: '',
      mobile: '',
      email: '',
      bind_qq: 0,
      bind_sina: 0,
      bind_weixin: 0,
      showResetUsername: false,
      showResetPassword: false,
      showResetMobile: false,
      showBindEmail: false,
      scanQrcodeImg: ''
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    initQrcode () {
      http.get(api.get_qrcode, {
        type: 'bind_weixin'
      }).then(res => {
        this.scanQrcodeImg = res.data
      })
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
          this.bind_qq = res.data.bind_qq
          this.bind_sina = res.data.bind_sina
          this.bind_weixin = res.data.bind_weixin
        })
        .catch(() => {})
    },
    handlerWithdraw () {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '提交申请将删除您的所有会员数据，永久注销会员账号。是否继续提交注销申请？'
        })
        .then(() => {
          http
            .post(api.account_cancel_apply, {})
            .then(res => {
              this.$notify({
                type: 'success',
                message: res.message
              })
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    logout () {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定退出此账号吗？'
        })
        .then(() => {
          localStorage.setItem('selectArr', null) // 职位
          localStorage.setItem('selectResumeArr', null) // 简历
          localStorage.setItem('selectComArr', null) // 企业
          this.$store.commit('setLoginState', {
            whether: false,
            utype: 0,
            token: '',
            mobile: '',
            userIminfo: {}
          })
          this.$router.push('/')
        })
        .catch(() => {
          // on cancel
        })
    },
    openFeedback () {
      this.$refs.feedback.initCB()
      this.showFeedback = true
    },
    openResetUsername () {
      this.$refs.resetUsername.initCB()
      this.showResetUsername = true
    },
    closeResetUsername (username) {
      this.username = username
      this.showResetUsername = false
    },
    openResetPassword () {
      this.$refs.resetPassword.initCB()
      this.showResetPassword = true
    },
    closeResetPassword (is_set_password) {
      this.is_set_password = is_set_password
      this.showResetPassword = false
    },
    openResetMobile () {
      if (this.mobile != '') {
        this.$dialog
          .confirm({
            title: '系统提示',
            message: '您的手机已认证，修改认证手机您的登录手机号将同步修改，是否继续？'
          })
          .then(() => {
            this.$refs.resetMobile.initCB()
            this.showResetMobile = true
          })
          .catch(() => {
            // on cancel
          })
      } else {
        this.$refs.resetMobile.initCB()
        this.showResetMobile = true
      }
    },
    closeResetMobile (mobile) {
      this.mobile = mobile
      this.showResetMobile = false
    },
    openBindEmail () {
      if (this.email != '') {
        this.$dialog
          .confirm({
            title: '系统提示',
            message: '您的邮箱已认证，修改认证邮箱您的登录邮箱将同步修改，是否继续？'
          })
          .then(() => {
            this.$refs.bindEmail.initCB()
            this.showBindEmail = true
          })
          .catch(() => {
            // on cancel
          })
      } else {
        this.$refs.bindEmail.initCB()
        this.showBindEmail = true
      }
    },
    closeBindEmail (email) {
      this.email = email
      this.showBindEmail = false
    },
    handlerCompanyAuth () {
      this.$router.push('/member/company/auth')
    },
    handlerBindQq () {
      if (this.$store.state.config.account_qqlogin_open == 0) {
        this.$dialog.alert({
          message: '暂不支持此功能<br />如需使用请联系网站客服'
        }).then(() => {
          // on close
        })
        return false
      }
      if (this.bind_qq == 0) {
        const {
          href
        } = this.$router.resolve({
          path: '/member/oauth/qq/bind'
        })
        window.location.href = href
      } else {
        this.$dialog
          .confirm({
            title: '系统提示',
            message: '解绑后无法使用QQ快速登录网站，是否确定解绑？'
          })
          .then(() => {
            http
              .post(api.unbind, {
                type: 'qq'
              })
              .then(() => {
                this.fetchData()
                this.$notify({
                  type: 'success',
                  message: '解绑成功'
                })
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      }
    },
    handlerBindWeixin () {
      if (this.$store.state.config.wechat_login_open == 0) {
        this.$dialog.alert({
          message: '暂不支持此功能<br />如需使用请联系网站客服'
        }).then(() => {
          // on close
        })
        return false
      }
      if (this.bind_weixin === 1) {
        this.$dialog
          .confirm({
            title: '系统提示',
            message: '解绑后无法使用微信快速登录网站，是否确定解绑？'
          })
          .then(() => {
            http
              .post(api.unbind, {
                type: 'weixin'
              })
              .then(() => {
                this.fetchData()
                this.$notify({
                  type: 'success',
                  message: '解绑成功'
                })
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      } else {
        this.showWeixinQrcode = true
        this.initQrcode()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.box_title {
  font-size: 12px;
  color: #999999;
  width: 100%;
  background-color: #f3f3f3;
  padding: 11px 16px;
}
.box_split_bar {
  width: 100%;
  height: 9px;
}
</style>
