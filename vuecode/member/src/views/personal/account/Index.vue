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
					{ label: '账户安全', href: '/personal/account/safety', name: '/personal/account/safety', active: true },
					{ label: '登录日志', href: '/personal/account/loginlog', name: '/personal/account/loginlog', active: false },
					{ label: '建议反馈', href: '/personal/account/feedback', name: '/personal/account/feedback', active: false },
				],
			}
		},
		components:{
		}
	}
</script>

<style>
</style>
