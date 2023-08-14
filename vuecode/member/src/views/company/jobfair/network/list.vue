<template>
  <el-card>
    <menu-nav :active-tab="$route.path" :list="navList"></menu-nav>
    <router-view></router-view>
  </el-card>
</template>

<script>
  export default{
    data(){
      return{
        navList: [
        { label: '全部', href: '/company/jobfair/network/all', name: '/company/jobfair/network/all', active: true },
        { label: '已报名', href: '/company/jobfair/network/apply', name: '/company/jobfair/network/apply', active: false },
        ]
      }
    }
  }
</script>

<style>
</style>
