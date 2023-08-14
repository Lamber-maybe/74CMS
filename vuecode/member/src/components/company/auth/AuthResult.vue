<template>
  <div>
	<el-card v-if="showAuth===false">
		<company-title>企业认证</company-title>

		<div class="process" v-if="authinfo.audit==3">
			<img src="../../../assets/images/bigExamine.png"/>
			<div>营业执照认证正在审核中</div>
			<P>我们将在一个工作日内审核您的资料信息</P>
		</div>

		<div class="process" v-if="authinfo.audit==1">
			<img src="../../../assets/images/bigSuccess.png"/>
			<div>已通过营业执照认证</div>
		</div>

		<div class="process" v-if="authinfo.audit==2">
			<img src="../../../assets/images/bigFailed.png"/>
			<div>营业执照认证未通过审核</div>
			<P class="font_color">原因：{{ authinfo.audit_reason?authinfo.audit_reason:'无' }}</P>
			<el-button class="btn" type="primary" @click="handlerAuthRepeat">重新认证</el-button>
		</div>
	</el-card>
	<AuthForm v-else :authinfo="authinfo" @reload="reload"></AuthForm>
  </div>
</template>

<script>
import AuthForm from '@/components/company/auth/AuthForm'
export default {
  name: 'AuthResult',
  props: ['authinfo'],
  components: {
	AuthForm
  },
  data () {
    return {
      showAuth: false
    }
  },
  methods: {
    handlerAuthRepeat () {
      this.showAuth = true
    },
    reload () {
      this.showAuth = false
      this.$emit('reload')
    }
  }
}
</script>

<style scoped lang="scss">
	.process{
		text-align: center;
		margin-top: 180px;
		height: 600px;
	}
	.process div{
		color: #333333;
		font-size: 24px;
		margin-bottom: 18px;
	}
	.process p{
		color: #333333;
		font-size: 18px;
	}
	.process img{
		margin-bottom: 36px;
	}
	.process .font_color{
		color: #ff4242;
		font-size:14px ;
		margin-bottom: 54px;
	}
	.btn{
		width: 260px;
		height: 42px;
	}
</style>
