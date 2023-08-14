<template>
	<el-card>
		<company-title>账号管理</company-title>
		<menu-nav :active-tab="$route.path" :list="navList"></menu-nav>
		<router-view></router-view>
	</el-card>
</template>

<script>
	export default{
		data(){
			return{
				navList: [
					{ label: '账户安全', href: '/company/account/safety', name: '/company/account/safety', active: true },
					{ label: '登录日志', href: '/company/account/loginlog', name: '/company/account/loginlog', active: false },
					{ label: '建议反馈', href: '/company/account/feedback', name: '/company/account/feedback', active: false }
				],
			}
		},
		components:{
		}
	}
</script>

<style>
</style>
