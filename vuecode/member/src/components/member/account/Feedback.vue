<template>
	<div class="feedbackBox">
		<div class="title">
			用户您好，请将您的意见、想法、建议或投诉内容告诉我们，以帮助我们为全体用户提供更加优质的服务。<br/>我们将在第一时间及时回复您的反馈，如您的问题比较紧急，请致电服务热线：{{$store.state.config.contact_tel}}。
		</div>
		<div class="opinion_content">
			<el-form :model="form" ref='form' :rules="rules" label-width="80px">
				<el-form-item label="意见类型">
					<el-button class="opinionTypeItem" size="small" :type="item.id == form.type ? 'primary' : ''" round v-for="(item, index) in options_feedback" :key="index" @click="handlerType(item)">{{ item.text }}</el-button>
				</el-form-item>
				<el-form-item label="反馈内容" prop="content">
					<el-input type="textarea" v-model="form.content" rows="8" placeholder="请描述具体操作步骤及问题，有助于我们快速定位并解决"></el-input>
				</el-form-item>
				<el-button class="sub" type="primary" @click="handleSubmit('form')">提交反馈</el-button>
			</el-form>
		</div>
	</div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
	export default{
		name: 'AccountFeedback',
		data () {
			return {
				form:{
					type: 0,
					content: '',
				},
				rules: {
					content: [
						{ required: true, message: '请输入反馈内容', trigger: 'blur' },
						{ max: 200, message: '最多输入200个字', trigger: 'blur' }
					]
				}
			}
		},
		created(){
			this.$store.dispatch('getClassify', 'feedback').then(res => {
				this.form.type = res.data[0].id
			})
		},
		computed: {
			options_feedback () {
			return this.$store.state.classifyFeedback
			}
		},
		methods: {
			handlerType (item) {
				this.form.type = item.id
			},
			// 提交
			handleSubmit (formName) {
				if (this.form.type == 0) {
					this.$message.error('请选择反馈类型')
					return false
				}
				this.$refs[formName].validate(valid => {
					if (valid) {
						http
							.post(api.feedback, this.form)
							.then(res => {
								this.$message({
									type: 'success',
									message: res.message
								})
								this.form.content = ''
							})
					.catch(() => {})
					}else{
						return false;
					}
				
				})
			}
		}
	}
</script>


<style lang="scss">
.feedbackBox{
	.title{
		margin-left: 20px;
		line-height: 25px;
		font-size: 14px;
		color: #999;
	}
	.opinionTypeItem{
		width: 79px;
		transition: all .3s;
		-webkit-transition: all .3s;
	}
	.el-input{
		width: 283px;
	}
	.el-textarea{
		width: 800px;
	}
	.el-textarea .el-textarea__inner{
		resize: none;
	}
	.opinion_content{margin-top:20px;}
	.opinion_content .sub{
		width: 259px;
		height: 42px;
		margin-left: 100px;
		transition: all .3s;
		-webkit-transition: all .3s;
	}
	.el-form-item{
		margin-left: 20px;
	}
}
	
</style>
