<template>
  <div>
	<el-card v-if="showLicense===false && showLegal===false">
		<company-title>企业认证</company-title>
    <el-alert title="您可以任选其中一种方式进行认证，认证后即可获得专属认证标识"  type="warning" :closable='false'  show-icon> </el-alert>
		<div class="authentication_type" >
			<div class="type_1">
				<div>
					<div class="imgType" >
						<img src="../../../assets/images/authentication_type1.png" />
					</div>
					<span>营业执照认证</span>
					<p>认证营业执照，开启更多招聘功能</p>
					<p>求职者更信赖通过认证的企业</p>
					<el-button  @click="showLicense = true" class="button" type="primary">立即认证</el-button>
				</div>
			</div>
			<div class="type_2">
				<div>
					<div class="imgType">
						<img src="../../../assets/images/authentication_type2.png" />
					</div>
					<span>负责人身份认证</span>
					<p>若没有营业执照，请选择负责人认证</p>
					<p>上传身份证，申请负责人身份认证</p>
					<el-button @click="showLegal = true" class="button" type="primary">立即认证</el-button>
				</div>
			</div>
		</div>
	</el-card>
	<AuthLicense
		v-if="showLicense === true"
        ref="license"
        @reload="reload"
      ></AuthLicense>
      <AuthLegalPersonal
		v-if="showLegal === true"
        ref="legal"
        @reload="reload"
      ></AuthLegalPersonal>
  </div>
</template>

<script>
import AuthLicense from '@/components/company/auth/AuthLicense'
import AuthLegalPersonal from '@/components/company/auth/AuthLegalPersonal'
export default {
  name: 'Auth',
  components: {
    AuthLicense,
    AuthLegalPersonal
  },
  data () {
    return {
      showLicense: false,
      showLegal: false
    }
  },
  methods: {
    initCB () {},
    handlerLicense () {
      this.showLicense = true
    },
    reload () {
      this.$emit('reload')
    }
  }
}
</script>

<style scoped lang="scss">
  .el-alert {
    padding: 12px;margin-bottom: 20px;
  }
	.authentication_type{
		display: flex;
		margin: 0 30px;
	}
	.type_1,.type_2{
		flex: 1;
		background: #fcfcfc;
		height: 460px;
		position: relative;
	}
	.type_1{
		margin-right: 40px;
	}
	.type_1>div,  .type_2>div{
		width: 290px;
		height: 290px;
		background:#FFFFFF;
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%,-50%);
		text-align: center;
	}
	.type_1>div p,  .type_2>div p{
		font-size: 13px;
		color: #d3d3d3;
		margin-top: 10px;
	}
	.type_1>div span,  .type_2>div span{
		font-size: 18px;
		color: #333333;
		display: inline-block;
		margin-top: 18px;
	}
	.imgType{
		margin-top: 54px;
	}
	.button{
		width: 180px;
		margin-top: 30px;
	}
</style>
