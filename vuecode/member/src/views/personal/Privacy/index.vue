<template>
  <el-card>
    <company-title>隐私设置</company-title>
    <menu-nav :active-tab="$route.path" :list="navList"></menu-nav>
    <router-view></router-view>
  </el-card>

</template>

<script>
  export default{
    data(){
      return{
        navList: [
          { label: '隐私设置', href: '/personal/privacy/setPrivacy', name: '/personal/privacy/setPrivacy', active: true },
          { label: '屏蔽企业', href: '/personal/privacy/shield', name: '/personal/privacy/shield', active: false },
        ]
      }
    }
  }
</script>

<style>
</style>
